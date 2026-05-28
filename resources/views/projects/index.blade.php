<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-neon-green">> BOARDS_</h1>
                @php
                    $planColor = match($team->plan) {
                        'pro'  => 'border-neon-green text-neon-green',
                        'team' => 'border-yellow-500 text-yellow-500',
                        default => 'border-gray-700 text-gray-500',
                    };
                @endphp
                <span class="text-xs border px-2 py-0.5 font-bold {{ $planColor }}">
                    {{ $team->planLabel() }}_PLAN
                </span>
                @if($team->plan === 'free')
                    <span class="text-xs text-gray-600">
                        {{ $projects->count() }}/3 boards
                    </span>
                @endif
            </div>

            @if($team->isAtProjectLimit())
                <span class="text-xs border border-red-900 text-red-500 px-3 py-1.5 font-mono">
                    LIMIT_REACHED — ask admin to upgrade
                </span>
            @else
                <a href="{{ route('projects.create') }}" class="btn-brutal text-sm">+ NEW_BOARD</a>
            @endif
        </div>

        @if($team->plan === 'free' && $projects->count() >= 2)
            <div class="mt-2 text-xs text-yellow-600 border border-yellow-900 bg-yellow-950/20 px-3 py-2">
                > FREE plan: {{ $projects->count() }}/3 boards used.
                @if($team->isAtProjectLimit())
                    Board limit reached — contact your admin to upgrade to PRO for unlimited boards.
                @else
                    {{ 3 - $projects->count() }} board{{ 3 - $projects->count() === 1 ? '' : 's' }} remaining before limit.
                @endif
            </div>
        @endif
    </x-slot>

    <div class="p-8">
        @if($projects->isEmpty())
            <div class="border-2 border-dashed border-gray-700 p-20 text-center">
                <p class="text-gray-500 text-2xl font-bold mb-3">> NO_BOARDS_FOUND</p>
                <p class="text-gray-600 text-sm mb-10">Create your first board to start tracking tasks with your team.</p>
                <a href="{{ route('projects.create') }}" class="btn-brutal">INIT_FIRST_BOARD</a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($projects as $project)
                    <a href="{{ route('projects.show', $project) }}"
                       class="block border-4 border-white bg-dark-gray p-6 hover:shadow-hard transition-all group hover:-translate-y-1 hover:border-neon-green">
                        <div class="flex justify-between items-start mb-4">
                            <div class="w-4 h-4 border-2 border-white"
                                 style="background: {{ $project->color }}"></div>
                            <span class="text-xs border px-2 py-0.5 font-bold
                                {{ $project->status === 'active' ? 'border-neon-green text-neon-green' : 'border-gray-600 text-gray-600' }}">
                                {{ strtoupper($project->status) }}
                            </span>
                        </div>

                        <h3 class="text-xl font-bold text-white mb-2 group-hover:text-neon-green transition-colors uppercase tracking-wide">
                            {{ $project->name }}
                        </h3>

                        <p class="text-sm text-gray-500 mb-6 leading-relaxed">
                            {{ $project->description ?? 'No description set.' }}
                        </p>

                        <div class="grid grid-cols-3 gap-3 border-t-2 border-gray-800 pt-4 text-center">
                            <div>
                                <div class="text-lg font-black text-white">{{ $project->todo_count }}</div>
                                <div class="text-xs text-gray-600 uppercase">Todo</div>
                            </div>
                            <div>
                                <div class="text-lg font-black text-yellow-500">{{ $project->doing_count }}</div>
                                <div class="text-xs text-gray-600 uppercase">Doing</div>
                            </div>
                            <div>
                                <div class="text-lg font-black text-neon-green">{{ $project->done_count }}</div>
                                <div class="text-xs text-gray-600 uppercase">Done</div>
                            </div>
                        </div>

                        @if($project->end_date)
                            <div class="mt-4 text-xs text-gray-600 border-t border-gray-800 pt-3">
                                DUE: {{ $project->end_date->format('d M Y') }}
                            </div>
                        @endif
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
