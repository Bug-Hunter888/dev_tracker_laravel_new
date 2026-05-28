<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-white text-sm transition-colors">&lt; ADMIN</a>
                <span class="text-gray-700">/</span>
                <h1 class="text-xl font-bold text-white uppercase">TASKS</h1>
                <span class="text-xs border border-gray-600 text-gray-400 px-2 py-0.5">{{ $tasks->total() }} TOTAL</span>
            </div>

            <form method="GET" class="flex flex-wrap gap-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search task title..."
                       class="bg-black border border-gray-700 text-white text-xs px-3 py-1.5 focus:border-neon-green focus:outline-none font-mono w-48">

                <select name="status"
                        class="bg-black border border-gray-700 text-white text-xs px-2 py-1.5 focus:border-neon-green focus:outline-none font-mono">
                    <option value="">ALL STATUS</option>
                    <option value="backlog" {{ request('status') === 'backlog' ? 'selected' : '' }}>BACKLOG</option>
                    <option value="todo"    {{ request('status') === 'todo'    ? 'selected' : '' }}>TO_DO</option>
                    <option value="doing"   {{ request('status') === 'doing'   ? 'selected' : '' }}>IN_PROGRESS</option>
                    <option value="done"    {{ request('status') === 'done'    ? 'selected' : '' }}>SHIPPED</option>
                </select>

                <select name="priority"
                        class="bg-black border border-gray-700 text-white text-xs px-2 py-1.5 focus:border-neon-green focus:outline-none font-mono">
                    <option value="">ALL PRIORITY</option>
                    <option value="critical" {{ request('priority') === 'critical' ? 'selected' : '' }}>CRITICAL</option>
                    <option value="normal"   {{ request('priority') === 'normal'   ? 'selected' : '' }}>NORMAL</option>
                    <option value="low"      {{ request('priority') === 'low'      ? 'selected' : '' }}>LOW</option>
                </select>

                <button type="submit" class="btn-brutal-sm">FILTER</button>
                @if(request()->hasAny(['search','status','priority']))
                    <a href="{{ route('admin.tasks.index') }}" class="text-xs border border-gray-700 px-3 py-1.5 text-gray-500 hover:text-white hover:border-white transition-colors">CLEAR</a>
                @endif
            </form>
        </div>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto">

        @if(session('success'))
            <div class="mb-4 border border-neon-green px-4 py-2 text-xs text-neon-green font-mono">
                &gt; {{ session('success') }}
            </div>
        @endif

        <div class="border-2 border-gray-800 bg-black">

            {{-- Header --}}
            <div class="grid grid-cols-12 gap-4 px-4 py-2 border-b border-gray-800 text-xs text-gray-600 tracking-widest uppercase">
                <div class="col-span-4">TASK</div>
                <div class="col-span-2 hidden md:block">PROJECT / TEAM</div>
                <div class="col-span-1 hidden md:block">ASSIGNEE</div>
                <div class="col-span-2">STATUS</div>
                <div class="col-span-2">PRIORITY</div>
                <div class="col-span-1 text-right">DEL</div>
            </div>

            @forelse($tasks as $task)
                <div class="grid grid-cols-12 gap-4 px-4 py-3 border-b border-gray-900 last:border-0 items-center">

                    {{-- Task title --}}
                    <div class="col-span-4">
                        <p class="text-xs font-bold text-white truncate">{{ $task->title }}</p>
                        @if($task->due_date)
                            <p class="text-xs {{ $task->isOverdue() ? 'text-red-500' : 'text-gray-700' }}">
                                DUE {{ $task->due_date->format('d M Y') }}
                                @if($task->isOverdue()) <span class="font-bold">OVERDUE</span> @endif
                            </p>
                        @endif
                    </div>

                    {{-- Project / Team — nullsafe chain for orphaned tasks --}}
                    <div class="col-span-2 hidden md:block">
                        <p class="text-xs text-gray-400 truncate">{{ $task->project?->name ?? '—' }}</p>
                        <p class="text-xs text-gray-700 truncate">{{ $task->project?->team?->name ?? '—' }}</p>
                    </div>

                    {{-- Assignee --}}
                    <div class="col-span-1 hidden md:block text-xs text-gray-500 truncate">
                        {{ $task->assignee?->name ?? '—' }}
                    </div>

                    {{-- Inline status edit --}}
                    <div class="col-span-2">
                        <form action="{{ route('admin.tasks.update', $task) }}" method="POST">
                            @csrf @method('PATCH')
                            <select name="status" onchange="this.form.submit()"
                                    class="w-full bg-black text-xs font-bold border border-gray-800 px-1 py-1 focus:border-neon-green focus:outline-none font-mono
                                    {{ match($task->status) {
                                        'done'    => 'text-neon-green',
                                        'doing'   => 'text-yellow-500',
                                        'backlog'  => 'text-gray-600',
                                        default   => 'text-gray-400',
                                    } }}">
                                <option value="backlog" {{ $task->status === 'backlog' ? 'selected' : '' }}>BACKLOG</option>
                                <option value="todo"    {{ $task->status === 'todo'    ? 'selected' : '' }}>TO_DO</option>
                                <option value="doing"   {{ $task->status === 'doing'   ? 'selected' : '' }}>IN_PROG</option>
                                <option value="done"    {{ $task->status === 'done'    ? 'selected' : '' }}>SHIPPED</option>
                            </select>
                        </form>
                    </div>

                    {{-- Inline priority edit --}}
                    <div class="col-span-2">
                        <form action="{{ route('admin.tasks.update', $task) }}" method="POST">
                            @csrf @method('PATCH')
                            <select name="priority" onchange="this.form.submit()"
                                    class="w-full bg-black text-xs font-bold border border-gray-800 px-1 py-1 focus:border-neon-green focus:outline-none font-mono
                                    {{ match($task->priority) {
                                        'critical' => 'text-red-400',
                                        'low'      => 'text-gray-600',
                                        default    => 'text-neon-green',
                                    } }}">
                                <option value="low"      {{ $task->priority === 'low'      ? 'selected' : '' }}>LOW</option>
                                <option value="normal"   {{ $task->priority === 'normal'   ? 'selected' : '' }}>NORMAL</option>
                                <option value="critical" {{ $task->priority === 'critical' ? 'selected' : '' }}>CRITICAL</option>
                            </select>
                        </form>
                    </div>

                    {{-- Delete --}}
                    <div class="col-span-1 flex justify-end">
                        <form action="{{ route('admin.tasks.destroy', $task) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Delete task \'{{ addslashes($task->title) }}\'?')"
                                    class="text-xs border border-gray-800 px-2 py-1 text-gray-700 hover:border-red-500 hover:text-red-500 transition-colors font-mono">
                                DEL
                            </button>
                        </form>
                    </div>

                </div>
            @empty
                <div class="px-4 py-12 text-center">
                    <p class="text-xs text-gray-700">NO_TASKS_FOUND</p>
                </div>
            @endforelse
        </div>

        @if($tasks->hasPages())
            <div class="mt-4">
                {{ $tasks->links() }}
            </div>
        @endif

    </div>
</x-app-layout>
