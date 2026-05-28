<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('projects.show', $project) }}"
                   class="text-gray-500 hover:text-white text-sm transition-colors">
                    &lt; BOARD
                </a>
                <span class="text-gray-700">/</span>
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 border-2 border-white" style="background: {{ $project->color }}"></div>
                    <h1 class="text-xl font-bold text-white uppercase">{{ $project->name }}</h1>
                    <span class="text-xs border px-2 py-0.5 border-gray-600 text-gray-600">TIMELINE</span>
                </div>
            </div>
            <div class="text-xs text-gray-600 font-mono">
                {{ $rangeStart->format('d M Y') }} &mdash; {{ $rangeEnd->format('d M Y') }}
                ({{ $totalDays }}d)
            </div>
        </div>
    </x-slot>

    <div class="p-6 bg-pitch-black min-h-screen">

        @if($withDates->isEmpty() && $withoutDates->isEmpty())
            <div class="border-2 border-dashed border-gray-800 p-16 text-center">
                <p class="text-gray-700 text-xs">NO_TASKS_IN_THIS_PROJECT</p>
            </div>
        @else

        {{-- Gantt Chart --}}
        @if($withDates->isNotEmpty())
        <div class="border-2 border-gray-800 mb-8 overflow-x-auto">

            {{-- Header --}}
            <div class="bg-dark-gray border-b-2 border-gray-800 px-4 py-2">
                <span class="text-xs font-bold text-gray-500 tracking-widest">// GANTT_VIEW &mdash; TASKS_WITH_DEADLINES</span>
            </div>

            <div class="p-4">
                {{-- Date ruler --}}
                @php
                    $markerInterval = max(1, (int)floor($totalDays / 8));
                    $markers = [];
                    for ($i = 0; $i <= $totalDays; $i += $markerInterval) {
                        $markers[] = [
                            'pct'   => $i / $totalDays * 100,
                            'label' => $rangeStart->copy()->addDays($i)->format('d M'),
                        ];
                    }

                    $todayPct = $rangeStart->diffInDays(now()->startOfDay()) / $totalDays * 100;
                    $todayPct = max(0, min(100, $todayPct));
                    $todayInRange = $todayPct >= 0 && $todayPct <= 100;
                @endphp

                {{-- Ruler row --}}
                <div class="relative h-6 mb-1 border-b border-gray-800" style="min-width: 600px;">
                    @foreach($markers as $marker)
                        <div class="absolute top-0 flex flex-col items-center"
                             style="left: {{ $marker['pct'] }}%; transform: translateX(-50%);">
                            <span class="text-xs text-gray-700 whitespace-nowrap">{{ $marker['label'] }}</span>
                        </div>
                    @endforeach
                    @if($todayInRange)
                        <div class="absolute top-0 flex flex-col items-center z-10"
                             style="left: {{ $todayPct }}%; transform: translateX(-50%);">
                            <span class="text-xs font-bold text-neon-green whitespace-nowrap">TODAY</span>
                        </div>
                    @endif
                </div>

                {{-- Task rows --}}
                <div class="space-y-2" style="min-width: 600px;">
                    @foreach($withDates as $task)
                        @php
                            $taskStart = $task->created_at->startOfDay()->lessThan($rangeStart)
                                ? $rangeStart->copy()
                                : $task->created_at->startOfDay()->copy();

                            $taskEnd = $task->due_date->startOfDay()->copy();
                            if ($taskEnd->greaterThan($rangeEnd)) $taskEnd = $rangeEnd->copy();

                            $leftPct  = $rangeStart->diffInDays($taskStart) / $totalDays * 100;
                            $spanDays = max(1, $taskStart->diffInDays($taskEnd));
                            $widthPct = min($spanDays / $totalDays * 100, 100 - $leftPct);
                            $widthPct = max(0.8, $widthPct);

                            $barColor = match(true) {
                                $task->isOverdue()        => '#ef4444',
                                $task->status === 'done'  => '#39FF14',
                                $task->status === 'doing' => '#eab308',
                                default                   => '#4b5563',
                            };
                        @endphp

                        <div class="relative flex items-center gap-2 group">
                            {{-- Label (fixed left column) --}}
                            <div class="w-40 flex-shrink-0 pr-2">
                                <a href="{{ route('tasks.show', $task) }}"
                                   class="text-xs text-gray-400 hover:text-white transition-colors truncate block leading-tight
                                       {{ $task->status === 'done' ? 'line-through text-gray-600' : '' }}">
                                    {{ $task->title }}
                                </a>
                            </div>

                            {{-- Bar area --}}
                            <div class="flex-1 relative h-7 bg-gray-900 border border-gray-800">
                                {{-- Today line --}}
                                @if($todayInRange)
                                    <div class="absolute top-0 bottom-0 w-px z-10"
                                         style="left: {{ $todayPct }}%; background: #39FF14; opacity: 0.4;"></div>
                                @endif

                                {{-- Task bar --}}
                                <div class="absolute top-1 bottom-1 flex items-center px-1.5 overflow-hidden transition-opacity group-hover:opacity-90"
                                     style="left: {{ $leftPct }}%; width: {{ $widthPct }}%; background: {{ $barColor }}; min-width: 4px;">
                                    <span class="text-xs font-bold truncate leading-none"
                                          style="color: {{ $task->status === 'done' ? '#000' : ($barColor === '#4b5563' ? '#9ca3af' : '#000') }}; font-size: 9px;">
                                        {{ strtoupper(substr($task->title, 0, 20)) }}
                                    </span>
                                </div>
                            </div>

                            {{-- Meta --}}
                            <div class="w-24 flex-shrink-0 text-right text-xs text-gray-700">
                                {{ $task->due_date->format('d M') }}
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Legend --}}
                <div class="flex items-center gap-6 mt-4 pt-3 border-t border-gray-800 text-xs text-gray-600">
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 inline-block" style="background:#4b5563"></span> TODO</span>
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 inline-block" style="background:#eab308"></span> DOING</span>
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 inline-block" style="background:#39FF14"></span> SHIPPED</span>
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 inline-block" style="background:#ef4444"></span> OVERDUE</span>
                </div>
            </div>
        </div>
        @endif

        {{-- Tasks without due dates --}}
        @if($withoutDates->isNotEmpty())
        <div class="border-2 border-gray-800">
            <div class="bg-dark-gray border-b-2 border-gray-800 px-4 py-2 flex justify-between items-center">
                <span class="text-xs font-bold text-gray-500 tracking-widest">// NO_DEADLINE</span>
                <span class="text-xs text-gray-700">{{ $withoutDates->count() }} task{{ $withoutDates->count() > 1 ? 's' : '' }}</span>
            </div>
            <div class="divide-y divide-gray-900">
                @foreach($withoutDates as $task)
                    @php
                        $statusLabel = match($task->status) {
                            'done'  => ['text' => 'SHIPPED',      'class' => 'text-neon-green border-neon-green'],
                            'doing' => ['text' => 'IN_PROGRESS',  'class' => 'text-yellow-500 border-yellow-500'],
                            default => ['text' => 'TO_DO',        'class' => 'text-gray-500 border-gray-600'],
                        };
                    @endphp
                    <div class="px-4 py-3 flex items-center justify-between hover:bg-gray-900 transition-colors">
                        <a href="{{ route('tasks.show', $task) }}"
                           class="text-sm text-gray-300 hover:text-white transition-colors
                               {{ $task->status === 'done' ? 'line-through text-gray-600' : '' }}">
                            {{ $task->title }}
                        </a>
                        <div class="flex items-center gap-3 text-xs">
                            @if($task->assignee)
                                <span class="text-gray-600">{{ strtoupper(substr($task->assignee->name, 0, 8)) }}</span>
                            @endif
                            <span class="border px-2 py-0.5 {{ $statusLabel['class'] }}">
                                {{ $statusLabel['text'] }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        @endif
    </div>
</x-app-layout>
