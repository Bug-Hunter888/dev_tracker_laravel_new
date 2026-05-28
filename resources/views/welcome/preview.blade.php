{{-- ═══ PREVIEW ═══ --}}
<section id="preview" class="py-24 overflow-hidden" style="background:#0d0d0d;border-top:1px solid #161616;">
    <div class="max-w-7xl mx-auto px-6">

        <div class="mb-14 text-center reveal">
            <span class="section-label">// dashboard</span>
            <h2 class="text-3xl lg:text-4xl font-bold tracking-tight text-white">
                Your team's command center.
            </h2>
            <p class="mt-3 text-base max-w-lg mx-auto" style="color:#666;">
                Boards, analytics, team activity, and sprint tracking — all in one place.
            </p>
        </div>

        <div class="reveal">
            <div class="card overflow-hidden" style="box-shadow:0 32px 80px rgba(0,0,0,0.55);">

                {{-- Window chrome --}}
                <div class="flex items-center justify-between px-5 py-3.5 border-b" style="border-color:#1e1e1e;">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full" style="background:rgba(239,68,68,0.4);"></div>
                        <div class="w-2.5 h-2.5 rounded-full" style="background:rgba(234,179,8,0.4);"></div>
                        <div class="w-2.5 h-2.5 rounded-full" style="background:rgba(57,255,20,0.4);"></div>
                        <span class="ml-4 text-xs font-mono" style="color:#3a3a3a;">devtracker — v2-launch-board</span>
                    </div>
                    <span class="text-xs font-mono flex items-center gap-1.5" style="color:rgba(57,255,20,0.5);">
                        <span class="w-1.5 h-1.5 rounded-full bg-neon-green animate-pulse"></span>
                        {{ $boardData['totalMembers'] }} online
                    </span>
                </div>

                {{-- Content --}}
                <div class="flex flex-col xl:grid xl:grid-cols-4" style="background:#0e0e0e;">

                    {{-- Kanban (3 cols) --}}
                    <div class="xl:col-span-3 p-5 border-b xl:border-b-0 xl:border-r" style="border-color:#1a1a1a;">

                        {{-- Sprint header --}}
                        <div class="flex items-center justify-between mb-5">
                            <div>
                                <h3 class="text-sm font-bold text-white font-mono">Sprint 5 — v2 Launch</h3>
                                <p class="text-xs font-mono mt-0.5" style="color:#3a3a3a;">Due in 4 days · {{ $boardData['totalTasks'] }} tasks</p>
                            </div>
                            <div class="flex items-center -space-x-2">
                                @foreach($boardData['teamMembers'] as $member)
                                <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-mono font-bold"
                                     title="{{ $member['name'] }}"
                                     style="{{ $member['status'] === 'active'
                                        ? 'background:rgba(57,255,20,0.12);border:2px solid rgba(57,255,20,0.28);color:#39FF14;'
                                        : 'background:#1a1a1a;border:2px solid #0e0e0e;color:#444;' }}">
                                    {{ $member['initials'][0] }}
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Board columns --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            @foreach($boardData['columns'] as $col)
                            <div>
                                <div class="flex items-center justify-between mb-2.5">
                                    <span class="text-xs font-mono font-bold uppercase tracking-wider {{ $col['header_classes'] }}">
                                        {{ $col['label'] }}
                                    </span>
                                    <span class="text-xs font-mono" style="color:#2a2a2a;">{{ count($col['tasks']) }}</span>
                                </div>

                                <div class="space-y-1.5">
                                    @foreach($col['tasks'] as $task)
                                    @php
                                        $taskColor = $task['color'] ?? 'gray';
                                        $pColor = ['red'=>'#f87171','yellow'=>'#eab308','gray'=>'#555'][$taskColor] ?? '#555';
                                        $pBg    = ['red'=>'rgba(248,113,113,0.1)','yellow'=>'rgba(234,179,8,0.1)','gray'=>'rgba(255,255,255,0.04)'][$taskColor] ?? 'rgba(255,255,255,0.04)';
                                        $hasAccent = !empty($col['task_accent']);
                                    @endphp
                                    <div class="rounded-lg p-2.5 border"
                                         style="background:#111;border-color:#1e1e1e;{{ $hasAccent ? 'border-left:2px solid rgba(234,179,8,0.4);' : '' }}">
                                        @if($col['shipped'])
                                            <p class="text-xs line-through mb-1 leading-snug" style="color:#3a3a3a;">{{ $task['title'] }}</p>
                                            <span class="text-xs font-mono" style="color:rgba(57,255,20,0.4);">✓ shipped</span>
                                        @else
                                            <p class="text-xs mb-2 leading-snug" style="color:#888;">{{ $task['title'] }}</p>
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs font-mono px-1.5 py-0.5 rounded"
                                                      style="color:{{ $pColor }};background:{{ $pBg }};">
                                                    {{ strtolower($task['priority']) }}
                                                </span>
                                                <div class="w-5 h-5 rounded-full flex items-center justify-center text-xs font-mono"
                                                     style="background:#1a1a1a;border:1px solid #2a2a2a;color:#555;">
                                                    {{ $task['user'][0] }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Sidebar --}}
                    <div class="hidden xl:flex flex-col gap-6 p-5">

                        {{-- Velocity --}}
                        <div>
                            <p class="text-xs font-mono uppercase tracking-wider mb-3" style="color:#333;">Velocity</p>
                            <div class="flex items-end gap-1 h-14">
                                @foreach($velocityBars as $bar)
                                <div class="flex-1 rounded-sm"
                                     style="height:{{ $bar }}%;background:rgba(57,255,20,{{ number_format(0.15 + ($bar / 100) * 0.55, 2) }});"></div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Sprint stats --}}
                        <div>
                            <p class="text-xs font-mono uppercase tracking-wider mb-3" style="color:#333;">Sprint</p>
                            <div class="space-y-2">
                                @foreach($boardData['sprint'] as $s)
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-mono" style="color:#444;">{{ $s['label'] }}</span>
                                    <span class="text-xs font-mono font-bold {{ $s['color'] }}">{{ $s['value'] }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Team --}}
                        <div>
                            <p class="text-xs font-mono uppercase tracking-wider mb-3" style="color:#333;">Team</p>
                            <div class="space-y-2.5">
                                @foreach($boardData['teamMembers'] as $member)
                                <div class="flex items-center gap-2">
                                    <div class="relative shrink-0">
                                        <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-mono"
                                             style="background:#1a1a1a;border:1px solid #2a2a2a;color:#555;">
                                            {{ $member['initials'][0] }}
                                        </div>
                                        <span class="absolute -bottom-0.5 -right-0.5 w-2 h-2 rounded-full border"
                                              style="border-color:#0e0e0e;background:{{ $member['status'] === 'active' ? '#39FF14' : ($member['status'] === 'idle' ? '#eab308' : '#333') }};"></span>
                                    </div>
                                    <span class="text-xs font-mono" style="color:#444;">&#64;{{ $member['name'] }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Activity --}}
                        <div>
                            <p class="text-xs font-mono uppercase tracking-wider mb-3" style="color:#333;">Activity</p>
                            <div class="space-y-2">
                                @foreach($boardData['activity'] as $item)
                                <div class="flex items-start gap-2">
                                    <span class="text-xs font-mono mt-0.5 shrink-0 {{ $item['color'] }}">{{ $item['icon'] }}</span>
                                    <span class="text-xs font-mono" style="color:#444;">{{ $item['text'] }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
