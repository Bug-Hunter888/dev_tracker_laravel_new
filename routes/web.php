<?php

use App\Http\Controllers\Admin\AdminCollaboratorController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminTeamController;
use App\Http\Controllers\Admin\AdminTaskController;
use App\Http\Controllers\Admin\AdminProjectController;
use App\Http\Controllers\Admin\AdminSprintController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\AutomationController;
use App\Http\Controllers\EpicController;
use App\Http\Controllers\SprintController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\TeamInvitationController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index'])->name('home');

// Override Jetstream's invitation controller to add expiry checks and correct redirect.
// Must be registered before Jetstream's service provider registers its own route.
Route::get('/team-invitations/{invitation}', [TeamInvitationController::class, 'accept'])
    ->middleware(['signed'])
    ->name('team-invitations.accept');

// Onboarding flow — auth required, but NOT gated by onboarded middleware
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->prefix('onboarding')->name('onboarding.')->group(function () {
        Route::get('/',            [OnboardingController::class, 'welcome'])     ->name('welcome');
        Route::get('/sprint',      [OnboardingController::class, 'sprint'])      ->name('sprint');
        Route::post('/sprint',     [OnboardingController::class, 'storeSprint']) ->name('sprint.store');
        Route::get('/backlog',     [OnboardingController::class, 'backlog'])     ->name('backlog');
        Route::post('/backlog',    [OnboardingController::class, 'storeBacklog'])->name('backlog.store');
        Route::post('/backlog/skip',   [OnboardingController::class, 'skipBacklog']) ->name('backlog.skip');
        Route::post('/invite',         [OnboardingController::class, 'invite'])      ->name('invite');
        Route::get('/board',       [OnboardingController::class, 'board'])       ->name('board');
        Route::post('/board',      [OnboardingController::class, 'storeBoard'])  ->name('board.store');
        Route::post('/board/skip', [OnboardingController::class, 'skipBoard'])   ->name('board.skip');
    });

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'onboarded',
])->group(function () {
    Route::get('/dashboard', [ProjectController::class, 'index'])->name('dashboard');
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
    Route::get('/search', SearchController::class)->name('search');
    Route::get('/help', fn () => view('help'))->name('help');

    Route::get('/labels', [LabelController::class, 'index'])->name('labels.index');
    Route::post('/labels', [LabelController::class, 'store'])->name('labels.store');
    Route::patch('/labels/{label}', [LabelController::class, 'update'])->name('labels.update');
    Route::delete('/labels/{label}', [LabelController::class, 'destroy'])->name('labels.destroy');

    Route::get('/projects/{project}/epics', [EpicController::class, 'index'])->name('epics.index');
    Route::post('/projects/{project}/epics', [EpicController::class, 'store'])->name('epics.store');
    Route::get('/projects/{project}/epics/{epic}', [EpicController::class, 'show'])->name('epics.show');
    Route::patch('/projects/{project}/epics/{epic}', [EpicController::class, 'update'])->name('epics.update');
    Route::delete('/projects/{project}/epics/{epic}', [EpicController::class, 'destroy'])->name('epics.destroy');

    Route::get('/projects/{project}/backlog', [ProjectController::class, 'backlog'])->name('projects.backlog');
    Route::get('/projects/{project}/timeline', [ProjectController::class, 'timeline'])->name('projects.timeline');

    Route::get('/projects/{project}/sprints', [SprintController::class, 'index'])->name('sprints.index');
    Route::post('/projects/{project}/sprints', [SprintController::class, 'store'])->name('sprints.store');
    Route::get('/projects/{project}/sprints/{sprint}', [SprintController::class, 'show'])->name('sprints.show');
    Route::patch('/projects/{project}/sprints/{sprint}/start', [SprintController::class, 'start'])->name('sprints.start');
    Route::patch('/projects/{project}/sprints/{sprint}/complete', [SprintController::class, 'complete'])->name('sprints.complete');
    Route::delete('/projects/{project}/sprints/{sprint}', [SprintController::class, 'destroy'])->name('sprints.destroy');
    Route::post('/projects/{project}/sprints/{sprint}/tasks/{task}', [SprintController::class, 'addTask'])->name('sprints.tasks.add');
    Route::delete('/projects/{project}/sprints/{sprint}/tasks/{task}', [SprintController::class, 'removeTask'])->name('sprints.tasks.remove');
    Route::resource('projects', ProjectController::class)->except(['index']);

    Route::post('/projects/{project}/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status');
    Route::patch('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    Route::post('/tasks/{task}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    Route::post('/tasks/{task}/attachments', [AttachmentController::class, 'store'])->name('attachments.store');
    Route::get('/attachments/{attachment}/download', [AttachmentController::class, 'download'])->name('attachments.download');
    Route::delete('/attachments/{attachment}', [AttachmentController::class, 'destroy'])->name('attachments.destroy');

    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');

    Route::get('/projects/{project}/automations', [AutomationController::class, 'index'])->name('automations.index');
    Route::post('/projects/{project}/automations', [AutomationController::class, 'store'])->name('automations.store');
    Route::patch('/automations/{automation}/toggle', [AutomationController::class, 'toggle'])->name('automations.toggle');
    Route::delete('/automations/{automation}', [AutomationController::class, 'destroy'])->name('automations.destroy');

    Route::get('/notifications', function () {
        return response()->json(
            Auth::user()->notifications()->latest()->take(20)->get()->map(fn($n) => [
                'id'      => $n->id,
                'message' => $n->data['message'] ?? '',
                'task_id' => $n->data['task_id'] ?? null,
                'read'    => !is_null($n->read_at),
                'time'    => $n->created_at->diffForHumans(),
            ])
        );
    })->name('notifications.index');

    Route::post('/notifications/read-all', function () {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['ok' => true]);
    })->name('notifications.readAll');
});

// Billing routes — auth required but NOT gated by onboarded (users can upgrade at any time)
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('/billing/upgrade',   [BillingController::class, 'upgrade'])  ->name('billing.upgrade');
    Route::post('/billing/subscribe', [BillingController::class, 'subscribe'])->name('billing.subscribe');
    Route::get('/billing/success',   [BillingController::class, 'success'])  ->name('billing.success');
});

// Super-admin routes
Route::prefix('admin')->name('admin.')->middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'admin',
])->group(function () {
    Route::get('/',                                    [AdminController::class,        'dashboard'])    ->name('dashboard');

    // Users
    Route::get('/users',                               [AdminUserController::class,    'index'])        ->name('users.index');
    Route::post('/users',                              [AdminUserController::class,    'store'])        ->name('users.store');
    Route::get('/users/{user}',                        [AdminUserController::class,    'show'])         ->name('users.show');
    Route::patch('/users/{user}',                      [AdminUserController::class,    'update'])       ->name('users.update');
    Route::patch('/users/{user}/ban',                  [AdminUserController::class,    'ban'])          ->name('users.ban');
    Route::patch('/users/{user}/make-admin',           [AdminUserController::class,    'makeAdmin'])    ->name('users.makeAdmin');
    Route::patch('/users/{user}/reset-password',       [AdminUserController::class,    'resetPassword'])->name('users.resetPassword');
    Route::delete('/users/{user}/data',                [AdminUserController::class,    'deleteData'])   ->name('users.deleteData');
    Route::delete('/users/{user}',                     [AdminUserController::class,    'destroy'])      ->name('users.destroy');

    // Teams
    Route::get('/teams',                               [AdminTeamController::class,    'index'])        ->name('teams.index');
    Route::patch('/teams/{team}/plan',                 [AdminTeamController::class,    'changePlan'])   ->name('teams.plan');
    Route::delete('/teams/{team}',                     [AdminTeamController::class,    'destroy'])      ->name('teams.destroy');

    // Tasks
    Route::get('/tasks',                               [AdminTaskController::class,    'index'])        ->name('tasks.index');
    Route::patch('/tasks/{task}',                      [AdminTaskController::class,    'update'])       ->name('tasks.update');
    Route::delete('/tasks/{task}',                     [AdminTaskController::class,    'destroy'])      ->name('tasks.destroy');

    // Projects
    Route::get('/projects',                            [AdminProjectController::class, 'index'])        ->name('projects.index');
    Route::post('/projects',                           [AdminProjectController::class, 'store'])        ->name('projects.store');
    Route::patch('/projects/{project}',                [AdminProjectController::class, 'update'])       ->name('projects.update');
    Route::delete('/projects/{project}',               [AdminProjectController::class, 'destroy'])      ->name('projects.destroy');

    // Sprints
    Route::post('/sprints',                            [AdminSprintController::class,  'store'])        ->name('sprints.store');
    Route::patch('/sprints/{sprint}',                  [AdminSprintController::class,  'update'])       ->name('sprints.update');
    Route::delete('/sprints/{sprint}',                 [AdminSprintController::class,  'destroy'])      ->name('sprints.destroy');

    // Collaborators
    Route::get('/collaborators',                       [AdminCollaboratorController::class, 'index'])          ->name('collaborators.index');
    Route::delete('/collaborators/{invitation}/revoke', [AdminCollaboratorController::class, 'revokeInvitation'])->name('collaborators.revoke');
});
