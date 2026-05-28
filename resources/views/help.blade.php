<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <h1 class="text-xl font-bold text-white uppercase">SYSTEM_GUIDE_</h1>
            <span class="text-xs border border-yellow-600 text-yellow-500 px-2 py-0.5 font-mono">USER MANUAL</span>
        </div>
        <p class="text-xs text-gray-500 mt-2">Complete reference for DevTracker — project management built for developers.</p>
    </x-slot>

    <div class="flex bg-pitch-black min-h-screen">

        {{-- Sticky sidebar TOC --}}
        <aside class="hidden lg:block w-56 shrink-0 border-r-2 border-gray-800 sticky top-[73px] self-start h-[calc(100vh-73px)] overflow-y-auto py-6 px-4">
            <p class="text-xs text-gray-700 uppercase tracking-widest font-bold mb-4">// CONTENTS</p>
            <nav class="space-y-1 text-xs font-mono">
                <a href="#overview"       class="block text-gray-500 hover:text-neon-green py-1 transition-colors">00. OVERVIEW</a>
                <a href="#getting-started" class="block text-gray-500 hover:text-neon-green py-1 transition-colors">01. GETTING_STARTED</a>
                <a href="#projects"       class="block text-gray-500 hover:text-neon-green py-1 transition-colors">02. PROJECTS</a>
                <a href="#tasks"          class="block text-gray-500 hover:text-neon-green py-1 transition-colors">03. TASKS</a>
                <a href="#backlog"        class="block text-gray-500 hover:text-neon-green py-1 transition-colors">04. BACKLOG</a>
                <a href="#sprints"        class="block text-gray-500 hover:text-neon-green py-1 transition-colors">05. SPRINTS</a>
                <a href="#burndown"       class="block text-gray-500 hover:text-neon-green py-1 transition-colors ml-3">↳ BURNDOWN</a>
                <a href="#epics"          class="block text-gray-500 hover:text-neon-green py-1 transition-colors">06. EPICS</a>
                <a href="#labels"         class="block text-gray-500 hover:text-neon-green py-1 transition-colors">07. LABELS</a>
                <a href="#timeline"       class="block text-gray-500 hover:text-neon-green py-1 transition-colors">08. TIMELINE</a>
                <a href="#team-chat"      class="block text-gray-500 hover:text-neon-green py-1 transition-colors">09. TEAM_CHAT</a>
                <a href="#notifications"  class="block text-gray-500 hover:text-neon-green py-1 transition-colors">10. NOTIFICATIONS</a>
                <a href="#search"         class="block text-gray-500 hover:text-neon-green py-1 transition-colors">11. SEARCH</a>
                <a href="#analytics"      class="block text-gray-500 hover:text-neon-green py-1 transition-colors">12. ANALYTICS</a>
                <a href="#shortcuts"      class="block text-gray-500 hover:text-neon-green py-1 transition-colors">13. SHORTCUTS</a>
                <a href="#subscriptions"  class="block text-gray-500 hover:text-neon-green py-1 transition-colors">14. SUBSCRIPTIONS</a>
            </nav>
        </aside>

        {{-- Main content --}}
        <main class="flex-1 px-6 lg:px-12 py-8 max-w-3xl">

            {{-- 00 OVERVIEW --}}
            <section id="overview" class="mb-14 scroll-mt-24">
                <p class="text-xs text-gray-700 font-mono mb-1">// 00</p>
                <h2 class="text-lg font-bold text-neon-green uppercase mb-4">OVERVIEW</h2>
                <p class="text-sm text-gray-300 leading-relaxed mb-4">
                    DevTracker is a team project management tool built for developers. It combines a Kanban board, sprint planning, burndown charts, epics, a backlog, and a timeline — all in one dark, minimal interface.
                </p>
                <div class="border border-gray-800 bg-black p-4 text-xs font-mono space-y-2 text-gray-400">
                    <p><span class="text-neon-green">BOARD</span>      — drag-free Kanban: TO_DO → IN_PROGRESS → SHIPPED</p>
                    <p><span class="text-neon-green">BACKLOG</span>    — parking lot for unstarted work</p>
                    <p><span class="text-neon-green">SPRINTS</span>    — time-boxed iterations with burndown charts</p>
                    <p><span class="text-neon-green">EPICS</span>      — group tasks under high-level features</p>
                    <p><span class="text-neon-green">TIMELINE</span>   — Gantt-style view of tasks with due dates</p>
                    <p><span class="text-neon-green">TEAM_CHAT</span>  — real-time team messages</p>
                    <p><span class="text-neon-green">ANALYTICS</span>  — team-wide completion and throughput stats</p>
                </div>
            </section>

            {{-- 01 GETTING STARTED --}}
            <section id="getting-started" class="mb-14 scroll-mt-24">
                <p class="text-xs text-gray-700 font-mono mb-1">// 01</p>
                <h2 class="text-lg font-bold text-neon-green uppercase mb-4">GETTING_STARTED</h2>

                <div class="space-y-6">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-widest font-bold mb-2">REGISTER</p>
                        <ol class="text-sm text-gray-300 leading-relaxed space-y-1 list-none">
                            <li class="flex gap-3"><span class="text-gray-700 shrink-0">1.</span> Click <span class="text-white font-bold">REGISTER</span> on the landing page and fill in your name, email, and password.</li>
                            <li class="flex gap-3"><span class="text-gray-700 shrink-0">2.</span> Verify your email via the link sent to your inbox.</li>
                            <li class="flex gap-3"><span class="text-gray-700 shrink-0">3.</span> You will be taken to the dashboard where a personal team is auto-created for you.</li>
                        </ol>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-widest font-bold mb-2">CREATE OR JOIN A TEAM</p>
                        <ol class="text-sm text-gray-300 leading-relaxed space-y-1 list-none">
                            <li class="flex gap-3"><span class="text-gray-700 shrink-0">1.</span> Click your name in the top-right nav → <span class="text-white font-bold">TEAM_SETTINGS</span>.</li>
                            <li class="flex gap-3"><span class="text-gray-700 shrink-0">2.</span> To invite teammates: enter their email address in the <span class="text-white font-bold">Team Members</span> section and click <span class="text-white font-bold">ADD</span>.</li>
                            <li class="flex gap-3"><span class="text-gray-700 shrink-0">3.</span> Invited members receive an email. Once they accept, they appear on the team roster and can be assigned tasks.</li>
                        </ol>
                    </div>

                    <div class="border-l-2 border-yellow-600 pl-4">
                        <p class="text-xs text-yellow-500 font-bold font-mono mb-1">NOTE</p>
                        <p class="text-xs text-gray-400">Team member limits depend on your subscription tier. See section 14 — SUBSCRIPTIONS.</p>
                    </div>
                </div>
            </section>

            {{-- 02 PROJECTS --}}
            <section id="projects" class="mb-14 scroll-mt-24">
                <p class="text-xs text-gray-700 font-mono mb-1">// 02</p>
                <h2 class="text-lg font-bold text-neon-green uppercase mb-4">PROJECTS</h2>

                <p class="text-sm text-gray-300 leading-relaxed mb-4">
                    A project is the top-level container for all work. Each project has its own board, backlog, sprints, epics, and timeline.
                </p>

                <div class="space-y-5">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-widest font-bold mb-2">CREATE A PROJECT</p>
                        <ol class="text-sm text-gray-300 leading-relaxed space-y-1 list-none">
                            <li class="flex gap-3"><span class="text-gray-700 shrink-0">1.</span> From the dashboard (BOARDS), click <span class="text-white font-bold">+ NEW_PROJECT</span>.</li>
                            <li class="flex gap-3"><span class="text-gray-700 shrink-0">2.</span> Give it a name and an optional description.</li>
                            <li class="flex gap-3"><span class="text-gray-700 shrink-0">3.</span> The project appears on your dashboard as a card with a live task count.</li>
                        </ol>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-widest font-bold mb-2">THE KANBAN BOARD</p>
                        <p class="text-sm text-gray-300 leading-relaxed mb-2">Click a project to open its board. Tasks are arranged in three columns:</p>
                        <div class="border border-gray-800 bg-black p-4 text-xs font-mono space-y-2">
                            <p><span class="text-white font-bold">TO_DO</span>        — tasks ready to be picked up</p>
                            <p><span class="text-yellow-500 font-bold">IN_PROGRESS</span>  — tasks currently being worked on</p>
                            <p><span class="text-neon-green font-bold">SHIPPED</span>      — completed tasks</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-3">Use the arrow buttons on task cards to move tasks between columns, or open a task and change its status in the Properties sidebar.</p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-widest font-bold mb-2">BOARD NAVIGATION</p>
                        <p class="text-sm text-gray-300 leading-relaxed">Every project board has a top-right navigation strip:</p>
                        <div class="mt-2 flex flex-wrap gap-2 text-xs font-mono">
                            <span class="border border-blue-800 text-blue-400 px-2 py-1">SPRINTS</span>
                            <span class="border border-gray-700 text-gray-400 px-2 py-1">KANBAN</span>
                            <span class="border border-yellow-800 text-yellow-500 px-2 py-1">TIMELINE</span>
                            <span class="border border-purple-800 text-purple-400 px-2 py-1">EPICS</span>
                        </div>
                    </div>
                </div>
            </section>

            {{-- 03 TASKS --}}
            <section id="tasks" class="mb-14 scroll-mt-24">
                <p class="text-xs text-gray-700 font-mono mb-1">// 03</p>
                <h2 class="text-lg font-bold text-neon-green uppercase mb-4">TASKS</h2>

                <p class="text-sm text-gray-300 leading-relaxed mb-5">
                    Tasks are the atomic unit of work. They live on the board, in the backlog, or inside sprints.
                </p>

                <div class="space-y-5">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-widest font-bold mb-2">CREATE A TASK</p>
                        <p class="text-sm text-gray-300 leading-relaxed">Click <span class="text-white font-bold">+ ADD_TASK</span> in any board column, or use <span class="text-white font-bold">+ ADD_TO_BACKLOG</span> on the Backlog page. Fill in the title and any optional fields.</p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-widest font-bold mb-2">TASK PROPERTIES</p>
                        <div class="space-y-2 text-sm text-gray-300">
                            <div class="flex gap-3 border-b border-gray-900 pb-2">
                                <span class="text-gray-500 font-mono w-32 shrink-0">TITLE</span>
                                <span>Required. Short description of the work item (max 255 chars).</span>
                            </div>
                            <div class="flex gap-3 border-b border-gray-900 pb-2">
                                <span class="text-gray-500 font-mono w-32 shrink-0">DESCRIPTION</span>
                                <span>Optional longer context, acceptance criteria, or notes.</span>
                            </div>
                            <div class="flex gap-3 border-b border-gray-900 pb-2">
                                <span class="text-gray-500 font-mono w-32 shrink-0">PRIORITY</span>
                                <span><span class="text-red-400 font-bold">CRITICAL</span> / <span class="text-neon-green font-bold">NORMAL</span> / <span class="text-gray-500 font-bold">LOW</span>. Shown as a colored badge on cards.</span>
                            </div>
                            <div class="flex gap-3 border-b border-gray-900 pb-2">
                                <span class="text-gray-500 font-mono w-32 shrink-0">ASSIGN_TO</span>
                                <span>Assign to a team member. Tasks can be unassigned.</span>
                            </div>
                            <div class="flex gap-3 border-b border-gray-900 pb-2">
                                <span class="text-gray-500 font-mono w-32 shrink-0">DUE_DATE</span>
                                <span>Optional deadline. Turns red on the board and backlog when overdue.</span>
                            </div>
                            <div class="flex gap-3 border-b border-gray-900 pb-2">
                                <span class="text-gray-500 font-mono w-32 shrink-0">STORY_POINTS</span>
                                <span>Effort estimate (0–999). Used by the burndown chart if set. Leave blank if you prefer task-count burndown.</span>
                            </div>
                            <div class="flex gap-3 border-b border-gray-900 pb-2">
                                <span class="text-gray-500 font-mono w-32 shrink-0">EPIC</span>
                                <span>Group this task under a high-level epic. See section 06 — EPICS.</span>
                            </div>
                            <div class="flex gap-3 pb-2">
                                <span class="text-gray-500 font-mono w-32 shrink-0">LABELS</span>
                                <span>Color-coded tags (e.g. bug, feature, backend). See section 07 — LABELS.</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-widest font-bold mb-2">STATUS FLOW</p>
                        <div class="flex items-center gap-2 text-xs font-mono flex-wrap">
                            <span class="border border-purple-700 text-purple-400 px-2 py-1">BACKLOG</span>
                            <span class="text-gray-700">→ PROMOTE →</span>
                            <span class="border border-white text-white px-2 py-1">TO_DO</span>
                            <span class="text-gray-700">→</span>
                            <span class="border border-yellow-600 text-yellow-500 px-2 py-1">IN_PROGRESS</span>
                            <span class="text-gray-700">→</span>
                            <span class="border border-neon-green text-neon-green px-2 py-1">SHIPPED</span>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-widest font-bold mb-2">TASK DETAIL PAGE</p>
                        <p class="text-sm text-gray-300 leading-relaxed">Click the title of any task (or the VIEW button) to open its full detail page. Here you can:</p>
                        <ul class="mt-2 text-sm text-gray-400 space-y-1 list-none">
                            <li class="flex gap-2"><span class="text-gray-700">—</span> Edit all properties in the right-hand sidebar</li>
                            <li class="flex gap-2"><span class="text-gray-700">—</span> Add comments (markdown-supported)</li>
                            <li class="flex gap-2"><span class="text-gray-700">—</span> Upload and manage file attachments</li>
                            <li class="flex gap-2"><span class="text-gray-700">—</span> View the full activity / comment history</li>
                        </ul>
                    </div>
                </div>
            </section>

            {{-- 04 BACKLOG --}}
            <section id="backlog" class="mb-14 scroll-mt-24">
                <p class="text-xs text-gray-700 font-mono mb-1">// 04</p>
                <h2 class="text-lg font-bold text-neon-green uppercase mb-4">BACKLOG</h2>

                <p class="text-sm text-gray-300 leading-relaxed mb-4">
                    The backlog is a prioritised holding area for work that is not yet ready to go on the board. Think of it as your product backlog — everything planned but not yet started.
                </p>

                <div class="space-y-4">
                    <div class="flex gap-4 items-start">
                        <span class="text-neon-green font-mono text-xs shrink-0 pt-0.5">ADD</span>
                        <p class="text-sm text-gray-300">Use the <span class="text-white font-bold">+ ADD_TO_BACKLOG</span> button at the top of the Backlog page. Items created here start with <span class="text-purple-400">backlog</span> status.</p>
                    </div>
                    <div class="flex gap-4 items-start">
                        <span class="text-neon-green font-mono text-xs shrink-0 pt-0.5">PROMOTE</span>
                        <p class="text-sm text-gray-300">Click <span class="text-white font-bold">PROMOTE →</span> on any backlog item to move it to the board as a <span class="text-white">TO_DO</span> task.</p>
                    </div>
                    <div class="flex gap-4 items-start">
                        <span class="text-neon-green font-mono text-xs shrink-0 pt-0.5">SPRINT</span>
                        <p class="text-sm text-gray-300">If a sprint is in planning or active, a <span class="text-blue-400 font-bold">+ SPRINT</span> button appears. Click it to add the task directly into that sprint's scope.</p>
                    </div>
                    <div class="flex gap-4 items-start">
                        <span class="text-neon-green font-mono text-xs shrink-0 pt-0.5">SORT</span>
                        <p class="text-sm text-gray-300">Items are sorted by priority (CRITICAL first, then NORMAL, then LOW). Reorder by changing a task's priority in its detail page.</p>
                    </div>
                </div>
            </section>

            {{-- 05 SPRINTS --}}
            <section id="sprints" class="mb-8 scroll-mt-24">
                <p class="text-xs text-gray-700 font-mono mb-1">// 05</p>
                <h2 class="text-lg font-bold text-neon-green uppercase mb-4">SPRINTS</h2>

                <p class="text-sm text-gray-300 leading-relaxed mb-5">
                    Sprints are time-boxed iterations — a fixed window (e.g. two weeks) in which your team commits to delivering a set of tasks. DevTracker tracks each sprint through three states: PLANNING → ACTIVE → COMPLETED.
                </p>

                <div class="space-y-5">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-widest font-bold mb-2">SPRINT STATES</p>
                        <div class="space-y-2 text-sm text-gray-300">
                            <div class="flex gap-3 border-b border-gray-900 pb-2">
                                <span class="text-yellow-500 font-mono font-bold w-24 shrink-0">PLANNING</span>
                                <span>Sprint is being scoped. Add tasks from the backlog. No burndown yet. Only one sprint can be in planning at a time.</span>
                            </div>
                            <div class="flex gap-3 border-b border-gray-900 pb-2">
                                <span class="text-neon-green font-mono font-bold w-24 shrink-0">ACTIVE</span>
                                <span>Sprint has started. Burndown chart is live. Team works through tasks on the sprint board. Only one active sprint per project.</span>
                            </div>
                            <div class="flex gap-3 pb-2">
                                <span class="text-gray-500 font-mono font-bold w-24 shrink-0">COMPLETED</span>
                                <span>Sprint is closed. The final burndown chart is preserved for review. Incomplete tasks remain in the sprint for reference.</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-widest font-bold mb-2">HOW TO RUN A SPRINT</p>
                        <ol class="text-sm text-gray-300 space-y-2 list-none">
                            <li class="flex gap-3"><span class="text-gray-700 shrink-0">1.</span> Go to <span class="text-white font-bold">SPRINTS</span> from any project view. Click <span class="text-white font-bold">+ NEW_SPRINT</span> and set a name, start date, and end date.</li>
                            <li class="flex gap-3"><span class="text-gray-700 shrink-0">2.</span> The sprint opens in <span class="text-yellow-500 font-bold">PLANNING</span> mode. Use the <span class="text-blue-400 font-bold">+ SPRINT</span> buttons on backlog items to pull tasks in.</li>
                            <li class="flex gap-3"><span class="text-gray-700 shrink-0">3.</span> When the team is ready, click <span class="text-white font-bold">START SPRINT</span>. The sprint becomes <span class="text-neon-green font-bold">ACTIVE</span> and the burndown chart begins.</li>
                            <li class="flex gap-3"><span class="text-gray-700 shrink-0">4.</span> Work through tasks: move them from TO_DO → IN_PROGRESS → SHIPPED on the sprint board.</li>
                            <li class="flex gap-3"><span class="text-gray-700 shrink-0">5.</span> At the end of the sprint, click <span class="text-white font-bold">COMPLETE SPRINT</span> to close it. Historical burndown is saved.</li>
                        </ol>
                    </div>
                </div>
            </section>

            {{-- BURNDOWN --}}
            <section id="burndown" class="mb-14 scroll-mt-24 ml-4 border-l-2 border-gray-800 pl-6">
                <p class="text-xs text-gray-700 font-mono mb-1">// 05.1</p>
                <h3 class="text-base font-bold text-yellow-400 uppercase mb-3">BURNDOWN_CHART</h3>

                <p class="text-sm text-gray-300 leading-relaxed mb-4">
                    The burndown chart appears on active and completed sprints. It shows how much work remains over time versus the ideal pace.
                </p>

                <div class="space-y-3 text-sm text-gray-300">
                    <div class="flex gap-3">
                        <span class="text-gray-500 font-mono w-24 shrink-0">IDEAL LINE</span>
                        <span>Dashed gray line. A straight linear decrease from total work on day 1 to zero on the last day.</span>
                    </div>
                    <div class="flex gap-3">
                        <span class="text-neon-green font-mono w-24 shrink-0">ACTUAL LINE</span>
                        <span>Solid neon green line. Shows real remaining work based on when tasks were marked SHIPPED.</span>
                    </div>
                </div>

                <div class="mt-4 border border-gray-800 bg-black p-4 text-xs font-mono space-y-1 text-gray-400">
                    <p class="text-gray-600 mb-2">// UNIT SELECTION (automatic)</p>
                    <p>If any sprint tasks have <span class="text-white">story_points</span> set → Y-axis = story points</p>
                    <p>If no story points are set &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;→ Y-axis = task count</p>
                </div>

                <p class="text-xs text-gray-500 mt-3">Tip: Set story points on your tasks before starting the sprint for a more meaningful burndown.</p>
            </section>

            {{-- 06 EPICS --}}
            <section id="epics" class="mb-14 scroll-mt-24">
                <p class="text-xs text-gray-700 font-mono mb-1">// 06</p>
                <h2 class="text-lg font-bold text-neon-green uppercase mb-4">EPICS</h2>

                <p class="text-sm text-gray-300 leading-relaxed mb-5">
                    Epics are large bodies of work that span multiple tasks and sprints. Use them to group related tasks under a single high-level feature or goal — for example, "User Authentication" or "Payment Integration".
                </p>

                <div class="space-y-5">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-widest font-bold mb-2">CREATE AN EPIC</p>
                        <ol class="text-sm text-gray-300 space-y-1 list-none">
                            <li class="flex gap-3"><span class="text-gray-700 shrink-0">1.</span> Go to <span class="text-white font-bold">EPICS</span> from any project view.</li>
                            <li class="flex gap-3"><span class="text-gray-700 shrink-0">2.</span> Click <span class="text-white font-bold">+ NEW_EPIC</span> and enter a name, optional description, and a color (defaults to purple).</li>
                            <li class="flex gap-3"><span class="text-gray-700 shrink-0">3.</span> The epic is created in <span class="text-purple-400">OPEN</span> status. Close it when the feature is complete.</li>
                        </ol>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-widest font-bold mb-2">ASSIGN TASKS TO AN EPIC</p>
                        <ol class="text-sm text-gray-300 space-y-1 list-none">
                            <li class="flex gap-3"><span class="text-gray-700 shrink-0">1.</span> Open any task's detail page.</li>
                            <li class="flex gap-3"><span class="text-gray-700 shrink-0">2.</span> In the right-hand Properties sidebar, find the <span class="text-white font-bold">EPIC</span> dropdown.</li>
                            <li class="flex gap-3"><span class="text-gray-700 shrink-0">3.</span> Select the epic. The task now shows a colored <span class="font-bold" style="color:#8B5CF6">◆ Epic Name</span> badge across the board, backlog, and sprint views.</li>
                        </ol>
                        <p class="text-xs text-gray-500 mt-2">You can also set the epic directly when adding a task from the Backlog create form.</p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-widest font-bold mb-2">EPIC PROGRESS</p>
                        <p class="text-sm text-gray-300 leading-relaxed">The epic detail page shows a progress bar: tasks in <span class="text-neon-green">SHIPPED</span> status count as done. The percentage updates automatically as tasks are completed.</p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-widest font-bold mb-2">CLOSE AN EPIC</p>
                        <p class="text-sm text-gray-300 leading-relaxed">When all tasks are done, click <span class="text-white font-bold">CLOSE EPIC</span> on the epic's detail page. Closed epics are hidden from the active list but can be reopened at any time. Deleting an epic does not delete its tasks — they simply lose their epic association.</p>
                    </div>
                </div>
            </section>

            {{-- 07 LABELS --}}
            <section id="labels" class="mb-14 scroll-mt-24">
                <p class="text-xs text-gray-700 font-mono mb-1">// 07</p>
                <h2 class="text-lg font-bold text-neon-green uppercase mb-4">LABELS</h2>

                <p class="text-sm text-gray-300 leading-relaxed mb-4">
                    Labels are free-form color-coded tags you apply to tasks. Common uses: <span class="text-white">bug</span>, <span class="text-white">feature</span>, <span class="text-white">backend</span>, <span class="text-white">design</span>, <span class="text-white">urgent</span>.
                </p>

                <div class="space-y-3">
                    <div class="flex gap-4 items-start">
                        <span class="text-neon-green font-mono text-xs shrink-0 pt-0.5 w-20">MANAGE</span>
                        <p class="text-sm text-gray-300">Go to <span class="text-white font-bold">LABELS</span> in the top nav. Create labels with a name and a hex color. Labels are shared across all projects in your team.</p>
                    </div>
                    <div class="flex gap-4 items-start">
                        <span class="text-neon-green font-mono text-xs shrink-0 pt-0.5 w-20">APPLY</span>
                        <p class="text-sm text-gray-300">Open a task detail page → Properties sidebar → <span class="text-white font-bold">LABELS</span> multi-select. Labels appear as colored badges on task cards everywhere.</p>
                    </div>
                    <div class="flex gap-4 items-start">
                        <span class="text-neon-green font-mono text-xs shrink-0 pt-0.5 w-20">REMOVE</span>
                        <p class="text-sm text-gray-300">Deselect a label in the same multi-select and save, or delete the label globally from the LABELS page.</p>
                    </div>
                </div>
            </section>

            {{-- 08 TIMELINE --}}
            <section id="timeline" class="mb-14 scroll-mt-24">
                <p class="text-xs text-gray-700 font-mono mb-1">// 08</p>
                <h2 class="text-lg font-bold text-neon-green uppercase mb-4">TIMELINE</h2>

                <p class="text-sm text-gray-300 leading-relaxed mb-4">
                    The Timeline is a Gantt-style view of all tasks that have a due date. It gives a visual overview of what is due when across the project.
                </p>

                <div class="space-y-3 text-sm text-gray-300">
                    <div class="flex gap-4 items-start">
                        <span class="text-gray-500 font-mono shrink-0 w-24">ACCESS</span>
                        <span>Click <span class="text-white font-bold">TIMELINE</span> from any project's header navigation.</span>
                    </div>
                    <div class="flex gap-4 items-start">
                        <span class="text-gray-500 font-mono shrink-0 w-24">BARS</span>
                        <span>Each task is represented as a horizontal bar spanning its due date. Overdue tasks are highlighted in red.</span>
                    </div>
                    <div class="flex gap-4 items-start">
                        <span class="text-gray-500 font-mono shrink-0 w-24">FILTER</span>
                        <span>Only tasks with a due date appear. Add a due date to a task from its detail page to include it on the timeline.</span>
                    </div>
                </div>
            </section>

            {{-- 09 TEAM CHAT --}}
            <section id="team-chat" class="mb-14 scroll-mt-24">
                <p class="text-xs text-gray-700 font-mono mb-1">// 09</p>
                <h2 class="text-lg font-bold text-neon-green uppercase mb-4">TEAM_CHAT</h2>

                <p class="text-sm text-gray-300 leading-relaxed mb-4">
                    A real-time chat panel is available on every page — look for the <span class="text-white font-bold">[TEAM_CHAT]</span> button in the bottom-right corner of the screen.
                </p>

                <div class="space-y-3 text-sm text-gray-300">
                    <div class="flex gap-4 items-start">
                        <span class="text-gray-500 font-mono shrink-0 w-24">OPEN</span>
                        <span>Click <span class="text-white font-bold">[TEAM_CHAT]</span> to expand the panel. A badge on the button shows unread message count when the panel is collapsed.</span>
                    </div>
                    <div class="flex gap-4 items-start">
                        <span class="text-gray-500 font-mono shrink-0 w-24">SEND</span>
                        <span>Type a message and press Enter or click <span class="text-white font-bold">SEND</span>. Messages are visible to all members of your current team in real-time.</span>
                    </div>
                    <div class="flex gap-4 items-start">
                        <span class="text-gray-500 font-mono shrink-0 w-24">SCOPE</span>
                        <span>Chat is scoped to your <span class="text-white">current team</span>. If you switch teams, you will see that team's message history.</span>
                    </div>
                </div>
            </section>

            {{-- 10 NOTIFICATIONS --}}
            <section id="notifications" class="mb-14 scroll-mt-24">
                <p class="text-xs text-gray-700 font-mono mb-1">// 10</p>
                <h2 class="text-lg font-bold text-neon-green uppercase mb-4">NOTIFICATIONS</h2>

                <p class="text-sm text-gray-300 leading-relaxed mb-4">
                    DevTracker notifies you when tasks are moved between board columns by any team member.
                </p>

                <div class="space-y-3 text-sm text-gray-300">
                    <div class="flex gap-4 items-start">
                        <span class="text-gray-500 font-mono shrink-0 w-24">[ALERTS]</span>
                        <span>Click <span class="text-white font-bold">[ALERTS]</span> in the top nav to open the notification panel. Unread notifications show a red counter badge.</span>
                    </div>
                    <div class="flex gap-4 items-start">
                        <span class="text-gray-500 font-mono shrink-0 w-24">MARK READ</span>
                        <span>All notifications are marked as read automatically when you open the panel.</span>
                    </div>
                    <div class="flex gap-4 items-start">
                        <span class="text-gray-500 font-mono shrink-0 w-24">REAL-TIME</span>
                        <span>Notifications arrive instantly via WebSockets. You do not need to refresh the page.</span>
                    </div>
                </div>
            </section>

            {{-- 11 SEARCH --}}
            <section id="search" class="mb-14 scroll-mt-24">
                <p class="text-xs text-gray-700 font-mono mb-1">// 11</p>
                <h2 class="text-lg font-bold text-neon-green uppercase mb-4">SEARCH</h2>

                <p class="text-sm text-gray-300 leading-relaxed mb-4">
                    Global search finds tasks, projects, and team members across your entire workspace.
                </p>

                <div class="space-y-3 text-sm text-gray-300">
                    <div class="flex gap-4 items-start">
                        <span class="text-gray-500 font-mono shrink-0 w-24">ACCESS</span>
                        <span>Click the <span class="text-white font-bold">search...</span> box in the top nav, or press <span class="text-white font-bold font-mono border border-gray-600 px-1">Ctrl</span> + <span class="text-white font-bold font-mono border border-gray-600 px-1">K</span> from anywhere.</span>
                    </div>
                    <div class="flex gap-4 items-start">
                        <span class="text-gray-500 font-mono shrink-0 w-24">SCOPE</span>
                        <span>Search matches task titles, project names, and members. Results link directly to the relevant item.</span>
                    </div>
                </div>
            </section>

            {{-- 12 ANALYTICS --}}
            <section id="analytics" class="mb-14 scroll-mt-24">
                <p class="text-xs text-gray-700 font-mono mb-1">// 12</p>
                <h2 class="text-lg font-bold text-neon-green uppercase mb-4">ANALYTICS</h2>

                <p class="text-sm text-gray-300 leading-relaxed mb-4">
                    The Analytics page provides a team-wide overview of productivity and project health.
                </p>

                <div class="space-y-2 text-sm text-gray-300">
                    <div class="flex gap-4 items-start">
                        <span class="text-gray-500 font-mono shrink-0 w-32">ACCESS</span>
                        <span>Click <span class="text-white font-bold">ANALYTICS</span> in the top nav.</span>
                    </div>
                    <div class="flex gap-4 items-start">
                        <span class="text-gray-500 font-mono shrink-0 w-32">METRICS</span>
                        <span>Total tasks, completion rates per project, tasks per member, overdue counts, and sprint velocity trends.</span>
                    </div>
                </div>
            </section>

            {{-- 13 SHORTCUTS --}}
            <section id="shortcuts" class="mb-14 scroll-mt-24">
                <p class="text-xs text-gray-700 font-mono mb-1">// 13</p>
                <h2 class="text-lg font-bold text-neon-green uppercase mb-4">KEYBOARD_SHORTCUTS</h2>

                <div class="border border-gray-800 bg-black p-4 space-y-3">
                    <div class="flex items-center gap-4 text-sm">
                        <div class="flex gap-1 shrink-0">
                            <kbd class="bg-gray-900 border border-gray-700 text-gray-300 text-xs px-2 py-1 font-mono">Ctrl</kbd>
                            <span class="text-gray-700 self-center">+</span>
                            <kbd class="bg-gray-900 border border-gray-700 text-gray-300 text-xs px-2 py-1 font-mono">K</kbd>
                        </div>
                        <span class="text-gray-300">Focus global search bar</span>
                    </div>
                    <div class="flex items-center gap-4 text-sm">
                        <div class="flex gap-1 shrink-0">
                            <kbd class="bg-gray-900 border border-gray-700 text-gray-300 text-xs px-2 py-1 font-mono">Esc</kbd>
                        </div>
                        <span class="text-gray-300">Close open dropdown forms (Alpine x-data panels)</span>
                    </div>
                </div>
            </section>

            {{-- 14 SUBSCRIPTIONS --}}
            <section id="subscriptions" class="mb-14 scroll-mt-24">
                <p class="text-xs text-gray-700 font-mono mb-1">// 14</p>
                <h2 class="text-lg font-bold text-neon-green uppercase mb-4">SUBSCRIPTION_TIERS</h2>

                <p class="text-sm text-gray-300 leading-relaxed mb-5">
                    DevTracker offers three plans. You can upgrade or downgrade from your Profile page.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="border-2 border-gray-700 p-4">
                        <p class="text-white font-bold font-mono text-sm mb-1">FREE</p>
                        <p class="text-neon-green text-xl font-bold font-mono mb-3">$0<span class="text-gray-600 text-xs">/mo</span></p>
                        <ul class="text-xs text-gray-400 space-y-1.5 font-mono">
                            <li>— Up to 5 team members</li>
                            <li>— Up to 3 projects</li>
                            <li>— All core features</li>
                            <li>— Community support</li>
                        </ul>
                    </div>
                    <div class="border-2 border-neon-green p-4 relative">
                        <span class="absolute -top-2.5 left-3 bg-neon-green text-black text-xs font-bold px-2 py-0.5 font-mono">POPULAR</span>
                        <p class="text-white font-bold font-mono text-sm mb-1">PRO</p>
                        <p class="text-neon-green text-xl font-bold font-mono mb-3">$12<span class="text-gray-600 text-xs">/mo</span></p>
                        <ul class="text-xs text-gray-400 space-y-1.5 font-mono">
                            <li>— Up to 15 team members</li>
                            <li>— Unlimited projects</li>
                            <li>— 5 GB file storage</li>
                            <li>— Priority support</li>
                        </ul>
                    </div>
                    <div class="border-2 border-gray-700 p-4">
                        <p class="text-white font-bold font-mono text-sm mb-1">TEAM</p>
                        <p class="text-neon-green text-xl font-bold font-mono mb-3">$29<span class="text-gray-600 text-xs">/mo</span></p>
                        <ul class="text-xs text-gray-400 space-y-1.5 font-mono">
                            <li>— Unlimited members</li>
                            <li>— Unlimited projects</li>
                            <li>— 50 GB file storage</li>
                            <li>— Dedicated support</li>
                        </ul>
                    </div>
                </div>
            </section>

            {{-- Footer --}}
            <div class="border-t-2 border-gray-900 pt-6 text-xs text-gray-700 font-mono">
                <p>DevTracker — built with Laravel, Jetstream, Alpine.js, Tailwind CSS, Chart.js</p>
                <p class="mt-1">For issues or feedback, contact your team administrator.</p>
            </div>

        </main>
    </div>
</x-app-layout>
