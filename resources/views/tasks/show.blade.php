<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3 flex-wrap">
            <a href="{{ route('projects.show', $project) }}" class="text-gray-500 hover:text-white text-sm transition-colors">
                &lt; {{ strtoupper($project->name) }}
            </a>
            <span class="text-gray-700">/</span>
            <span class="text-white text-sm font-mono truncate max-w-xs lg:max-w-lg">{{ $task->title }}</span>
        </div>
        <div class="flex items-center gap-3 mt-2 flex-wrap">
            @php
                $sc = match($task->status) {
                    'doing' => ['border-yellow-500', 'text-yellow-500'],
                    'done'  => ['border-neon-green', 'text-neon-green'],
                    default => ['border-gray-400', 'text-gray-400'],
                };
                $pc = match($task->priority) {
                    'critical' => ['border-red-400', 'text-red-400'],
                    'low'      => ['border-gray-600', 'text-gray-600'],
                    default    => ['border-neon-green', 'text-neon-green'],
                };
                $statusLabel = match($task->status) {
                    'doing' => 'IN_PROGRESS',
                    'done'  => 'SHIPPED',
                    default => 'TO_DO',
                };
            @endphp
            <span class="text-xs font-bold px-2 py-0.5 border {{ $sc[0] }} {{ $sc[1] }}">{{ $statusLabel }}</span>
            <span class="text-xs font-bold px-2 py-0.5 border {{ $pc[0] }} {{ $pc[1] }}">{{ strtoupper($task->priority) }}</span>
            @if($task->isOverdue())
                <span class="text-xs font-bold px-2 py-0.5 border border-red-500 text-red-500">!! OVERDUE</span>
            @endif
        </div>
    </x-slot>

    <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-6 min-h-screen bg-pitch-black">

        {{-- ── Left: Title / Description / Comments ── --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Title --}}
            <div x-data="{ editing: false }">
                <div x-show="!editing" class="group cursor-pointer" @click="editing = true">
                    <h2 class="text-2xl font-bold text-white uppercase tracking-wide leading-tight
                        {{ $task->status === 'done' ? 'line-through text-gray-500' : '' }}">
                        {{ $task->title }}
                    </h2>
                    <span class="text-xs text-gray-700 group-hover:text-gray-500 transition-colors mt-1 block">[ CLICK TO EDIT ]</span>
                </div>
                <div x-show="editing" x-cloak>
                    <form action="{{ route('tasks.update', $task) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="text" name="title" value="{{ $task->title }}"
                               class="input-brutal text-xl font-bold mb-2 w-full" required autofocus>
                        <div class="flex gap-2">
                            <button type="submit" class="btn-brutal-sm">SAVE_TITLE</button>
                            <button type="button" @click="editing = false"
                                    class="text-xs border border-gray-700 px-3 py-1 text-gray-500 hover:text-white transition-colors">ESC</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Description --}}
            <div x-data="{ editing: false }">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-xs text-gray-600 uppercase tracking-widest font-bold">DESCRIPTION</h3>
                    <button @click="editing = !editing"
                            class="text-xs text-gray-700 hover:text-neon-green transition-colors font-mono">[EDIT]</button>
                </div>
                <div x-show="!editing">
                    @if($task->description)
                        <p class="text-sm text-gray-300 leading-relaxed whitespace-pre-wrap">{{ $task->description }}</p>
                    @else
                        <p class="text-sm text-gray-700 italic">No description. Click [EDIT] to add one.</p>
                    @endif
                </div>
                <div x-show="editing" x-cloak>
                    <form action="{{ route('tasks.update', $task) }}" method="POST">
                        @csrf @method('PATCH')
                        <textarea name="description" rows="6" placeholder="Describe the task..."
                                  class="input-brutal resize-none mb-2 w-full text-sm">{{ $task->description }}</textarea>
                        <div class="flex gap-2">
                            <button type="submit" class="btn-brutal-sm">SAVE_DESC</button>
                            <button type="button" @click="editing = false"
                                    class="text-xs border border-gray-700 px-3 py-1 text-gray-500 hover:text-white transition-colors">ESC</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Comments (Livewire — no page reload, live char count, auto-refresh) --}}
            <div>
                <h3 class="text-xs text-gray-600 uppercase tracking-widest font-bold mb-4 border-b-2 border-gray-800 pb-2">
                    COMMENTS
                </h3>
                <livewire:task-comment-form :task-id="$task->id" />
            </div>
        </div>

        {{-- ── Right: Sidebar ── --}}
        <div class="space-y-6">

            {{-- Properties --}}
            <div class="border-2 border-gray-800 p-4">
                <h3 class="text-xs text-gray-600 uppercase tracking-widest font-bold mb-4 border-b border-gray-800 pb-2">PROPERTIES</h3>
                <form action="{{ route('tasks.update', $task) }}" method="POST" class="space-y-4">
                    @csrf @method('PATCH')

                    <div>
                        <label class="text-xs text-gray-600 block mb-1">STATUS</label>
                        <select name="status"
                                class="w-full bg-black text-white border-2 border-gray-700 p-2 text-xs focus:border-neon-green focus:outline-none font-mono">
                            <option value="todo"  {{ $task->status === 'todo'  ? 'selected' : '' }}>TO_DO</option>
                            <option value="doing" {{ $task->status === 'doing' ? 'selected' : '' }}>IN_PROGRESS</option>
                            <option value="done"  {{ $task->status === 'done'  ? 'selected' : '' }}>SHIPPED</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs text-gray-600 block mb-1">PRIORITY</label>
                        <select name="priority"
                                class="w-full bg-black text-white border-2 border-gray-700 p-2 text-xs focus:border-neon-green focus:outline-none font-mono">
                            <option value="low"      {{ $task->priority === 'low'      ? 'selected' : '' }}>LOW</option>
                            <option value="normal"   {{ $task->priority === 'normal'   ? 'selected' : '' }}>NORMAL</option>
                            <option value="critical" {{ $task->priority === 'critical' ? 'selected' : '' }}>CRITICAL</option>
                        </select>
                    </div>

                    @if($projectEpics->isNotEmpty())
                    <div>
                        <label class="text-xs text-gray-600 block mb-1">EPIC</label>
                        <select name="epic_id"
                                class="w-full bg-black text-white border-2 border-gray-700 p-2 text-xs focus:border-neon-green focus:outline-none font-mono">
                            <option value="">NO EPIC</option>
                            @foreach($projectEpics as $epic)
                                <option value="{{ $epic->id }}" {{ $task->epic_id === $epic->id ? 'selected' : '' }}>
                                    {{ $epic->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div>
                        <label class="text-xs text-gray-600 block mb-1">STORY_POINTS</label>
                        <input type="number" name="story_points" min="0" max="999"
                               value="{{ $task->story_points }}"
                               placeholder="—"
                               class="w-full bg-black text-white border-2 border-gray-700 p-2 text-xs focus:border-neon-green focus:outline-none font-mono">
                    </div>

                    <div>
                        <label class="text-xs text-gray-600 block mb-1">ASSIGN_TO</label>
                        <select name="assigned_to"
                                class="w-full bg-black text-white border-2 border-gray-700 p-2 text-xs focus:border-neon-green focus:outline-none font-mono">
                            <option value="">UNASSIGNED</option>
                            @foreach($teamMembers as $member)
                                <option value="{{ $member->id }}" {{ $task->assigned_to === $member->id ? 'selected' : '' }}>
                                    {{ $member->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-xs text-gray-600 block mb-1">DUE_DATE</label>
                        <input type="date" name="due_date"
                               value="{{ $task->due_date?->format('Y-m-d') }}"
                               class="w-full bg-black text-white border-2 border-gray-700 p-2 text-xs focus:border-neon-green focus:outline-none">
                    </div>

                    @if($teamLabels->isNotEmpty())
                        <div>
                            <label class="text-xs text-gray-600 block mb-2">LABELS</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($teamLabels as $label)
                                    <label class="cursor-pointer" x-data="{ checked: {{ $task->labels->contains($label) ? 'true' : 'false' }} }">
                                        <input type="checkbox" name="label_ids[]" value="{{ $label->id }}"
                                               class="sr-only"
                                               {{ $task->labels->contains($label) ? 'checked' : '' }}
                                               @change="checked = !checked">
                                        <span class="text-xs px-2 py-0.5 border-2 font-bold block transition-opacity cursor-pointer"
                                              :class="checked ? 'opacity-100' : 'opacity-30'"
                                              style="color: {{ $label->color }}; border-color: {{ $label->color }}">
                                            {{ $label->name }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <p class="text-xs text-gray-700">No labels created for this team yet.</p>
                    @endif

                    <button type="submit" class="btn-brutal-sm w-full text-center">SAVE_PROPERTIES</button>
                </form>
            </div>

            {{-- Attachments --}}
            <div class="border-2 border-gray-800 p-4">
                <h3 class="text-xs text-gray-600 uppercase tracking-widest font-bold mb-4 border-b border-gray-800 pb-2">
                    ATTACHMENTS <span class="text-gray-700">({{ $task->attachments->count() }})</span>
                </h3>

                <div class="space-y-2 mb-4">
                    @forelse($task->attachments as $attachment)
                        <div class="flex items-center justify-between border border-gray-800 p-2 hover:border-gray-600 transition-colors">
                            <div class="flex-1 min-w-0 mr-2">
                                <p class="text-xs font-bold text-white truncate">{{ $attachment->original_name }}</p>
                                <p class="text-xs text-gray-700">{{ $attachment->humanSize() }} · {{ $attachment->uploader->name }}</p>
                            </div>
                            <div class="flex gap-2 shrink-0">
                                <a href="{{ route('attachments.download', $attachment) }}"
                                   class="text-xs text-neon-green hover:underline font-mono">DL</a>
                                <form action="{{ route('attachments.destroy', $attachment) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Remove attachment?')"
                                            class="text-xs text-gray-700 hover:text-red-500 transition-colors font-mono">DEL</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-gray-700 italic">No files attached yet.</p>
                    @endforelse
                </div>

                <form action="{{ route('attachments.store', $task) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label for="file-upload"
                           class="block border-2 border-dashed border-gray-700 p-4 text-center cursor-pointer hover:border-neon-green transition-colors">
                        <input type="file" name="file" id="file-upload" class="hidden" onchange="this.form.submit()">
                        <p class="text-xs text-gray-600 font-bold">+ UPLOAD_FILE</p>
                        <p class="text-xs text-gray-700 mt-1">Max 10 MB · Click to browse</p>
                    </label>
                    @error('file')
                        <p class="text-red-500 text-xs mt-1">> {{ $message }}</p>
                    @enderror
                </form>
            </div>

            {{-- Activity Log --}}
            <div class="border-2 border-gray-800 p-4">
                <h3 class="text-xs text-gray-600 uppercase tracking-widest font-bold mb-4 border-b border-gray-800 pb-2">ACTIVITY</h3>
                <div class="space-y-3">
                    @forelse($task->activities as $activity)
                        <div class="text-xs border-l border-gray-800 pl-3">
                            <span class="text-neon-green font-bold">{{ strtoupper(substr($activity->user->name, 0, 10)) }}</span>
                            <span class="text-gray-500"> {{ $activity->description() }}</span>
                            <span class="text-gray-700 block mt-0.5">{{ $activity->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <p class="text-xs text-gray-700 italic">No activity yet.</p>
                    @endforelse
                </div>
            </div>

            {{-- Metadata --}}
            <div class="text-xs text-gray-700 space-y-1 font-mono">
                <div>CREATED_BY: <span class="text-gray-500">{{ $task->creator->name }}</span></div>
                <div>CREATED_AT: <span class="text-gray-500">{{ $task->created_at->format('d M Y H:i') }}</span></div>
                <div>UPDATED_AT: <span class="text-gray-500">{{ $task->updated_at->format('d M Y H:i') }}</span></div>
                <div class="pt-2 border-t border-gray-800">
                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit"
                                onclick="return confirm('Delete this task permanently?')"
                                class="text-gray-700 hover:text-red-500 transition-colors text-xs">DELETE_TASK</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
