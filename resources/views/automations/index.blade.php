<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('projects.show', $project) }}" class="text-gray-500 hover:text-white text-sm transition-colors">
                &lt; {{ strtoupper($project->name) }}
            </a>
            <span class="text-gray-700">/</span>
            <h1 class="text-xl font-bold text-white uppercase">AUTOMATIONS</h1>
        </div>
        <p class="text-xs text-gray-600 mt-1">Auto-run actions when tasks change — only admins & project creators can manage these.</p>
    </x-slot>

    <div class="p-6 max-w-4xl mx-auto space-y-8">

        @if(!$canUseAutomations)
        {{-- Upgrade wall --}}
        <div class="border-2 border-yellow-600 bg-black p-8 text-center">
            <p class="text-xs text-yellow-600 tracking-widest mb-2">// FEATURE_LOCKED</p>
            <h2 class="text-2xl font-bold text-white mb-3">AUTOMATIONS REQUIRE PRO</h2>
            <p class="text-sm text-gray-500 mb-6 max-w-md mx-auto">
                Your team is on the <span class="text-white font-bold">FREE</span> plan.
                Upgrade to <span class="text-neon-green font-bold">PRO</span> or <span class="text-yellow-500 font-bold">TEAM</span>
                to unlock workflow automations.
            </p>
            <div class="border border-gray-800 bg-gray-950 p-4 text-left max-w-sm mx-auto mb-6 font-mono text-xs text-gray-600 space-y-1">
                <p><span class="text-neon-green">+</span> Auto-notify assignees on task events</p>
                <p><span class="text-neon-green">+</span> Auto-set priority when status changes</p>
                <p><span class="text-neon-green">+</span> Auto-move tasks between columns</p>
                <p><span class="text-neon-green">+</span> Unlimited automation rules</p>
            </div>
            <p class="text-xs text-gray-700">Contact your team admin to upgrade the plan.</p>
        </div>
        @else

        {{-- Create Form --}}
        <div class="border-2 border-neon-green bg-dark-gray p-6">
            <h2 class="text-sm font-bold text-neon-green tracking-widest mb-4">+ NEW_AUTOMATION</h2>

            <form action="{{ route('automations.store', $project) }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="text-xs text-gray-600 block mb-1">AUTOMATION_NAME</label>
                    <input type="text" name="name" required maxlength="255"
                           placeholder="e.g. Notify assignee on ship"
                           class="input-brutal text-sm w-full" value="{{ old('name') }}">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs text-gray-600 block mb-1">TRIGGER (when...)</label>
                        <select name="trigger" required
                                class="w-full bg-black text-white border-2 border-gray-700 p-2 text-xs focus:border-neon-green focus:outline-none">
                            <option value="task_marked_done">Task marked SHIPPED</option>
                            <option value="task_marked_doing">Task marked IN_PROGRESS</option>
                            <option value="task_marked_todo">Task moved to TO_DO</option>
                            <option value="task_assigned">Task assigned to someone</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs text-gray-600 block mb-1">ACTION (then...)</label>
                        <select name="action_type" required id="action_type"
                                class="w-full bg-black text-white border-2 border-gray-700 p-2 text-xs focus:border-neon-green focus:outline-none"
                                onchange="toggleActionFields(this.value)">
                            <option value="notify_assignee">Notify assignee</option>
                            <option value="notify_creator">Notify task creator</option>
                            <option value="set_priority">Set priority</option>
                            <option value="move_status">Move to status</option>
                        </select>
                    </div>
                </div>

                {{-- Dynamic action fields --}}
                <div id="field_message">
                    <label class="text-xs text-gray-600 block mb-1">NOTIFICATION_MESSAGE (optional)</label>
                    <input type="text" name="action_message" maxlength="500"
                           placeholder="Leave blank for default message"
                           class="input-brutal text-xs w-full" value="{{ old('action_message') }}">
                </div>

                <div id="field_value" class="hidden">
                    <label class="text-xs text-gray-600 block mb-1" id="field_value_label">VALUE</label>
                    <select name="action_value" id="action_value_select"
                            class="w-full bg-black text-white border-2 border-gray-700 p-2 text-xs focus:border-neon-green focus:outline-none">
                        {{-- populated by JS --}}
                    </select>
                </div>

                @error('name')<p class="text-red-500 text-xs">{{ $message }}</p>@enderror
                @error('trigger')<p class="text-red-500 text-xs">{{ $message }}</p>@enderror
                @error('action_type')<p class="text-red-500 text-xs">{{ $message }}</p>@enderror

                <button type="submit" class="btn-brutal-sm">CREATE_AUTOMATION</button>
            </form>
        </div>

        {{-- Existing automations --}}
        <div>
            <h2 class="text-xs font-bold text-gray-600 tracking-widest mb-3">ACTIVE_RULES ({{ $automations->count() }})</h2>

            @forelse($automations as $auto)
                <div class="border-2 {{ $auto->enabled ? 'border-gray-700' : 'border-gray-800 opacity-50' }} bg-black p-4 mb-3 flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3 mb-1">
                            <span class="text-sm font-bold text-white">{{ $auto->name }}</span>
                            <span class="text-xs px-2 py-0.5 border {{ $auto->enabled ? 'border-neon-green text-neon-green' : 'border-gray-700 text-gray-700' }}">
                                {{ $auto->enabled ? 'ON' : 'OFF' }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500">
                            <span class="text-yellow-500">WHEN:</span>
                            {{ match($auto->trigger) {
                                'task_marked_done'  => 'Task marked SHIPPED',
                                'task_marked_doing' => 'Task marked IN_PROGRESS',
                                'task_marked_todo'  => 'Task moved to TO_DO',
                                'task_assigned'     => 'Task assigned to someone',
                                default             => $auto->trigger,
                            } }}
                        </p>
                        @foreach($auto->actions as $act)
                            <p class="text-xs text-gray-500 mt-0.5">
                                <span class="text-neon-green">THEN:</span>
                                {{ match($act['type'] ?? '') {
                                    'notify_assignee' => 'Notify assignee' . (isset($act['message']) && $act['message'] ? ' — "' . $act['message'] . '"' : ''),
                                    'notify_creator'  => 'Notify task creator' . (isset($act['message']) && $act['message'] ? ' — "' . $act['message'] . '"' : ''),
                                    'set_priority'    => 'Set priority → ' . strtoupper($act['value'] ?? ''),
                                    'move_status'     => 'Move to → ' . strtoupper($act['value'] ?? ''),
                                    default           => $act['type'] ?? '?',
                                } }}
                            </p>
                        @endforeach
                    </div>

                    <div class="flex gap-2 shrink-0">
                        <form action="{{ route('automations.toggle', $auto) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="text-xs border border-gray-700 px-2 py-1 {{ $auto->enabled ? 'text-gray-400 hover:border-yellow-500 hover:text-yellow-500' : 'text-gray-600 hover:border-neon-green hover:text-neon-green' }} transition-colors">
                                {{ $auto->enabled ? 'DISABLE' : 'ENABLE' }}
                            </button>
                        </form>
                        <form action="{{ route('automations.destroy', $auto) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Delete this automation?')"
                                    class="text-xs border border-gray-800 px-2 py-1 text-gray-700 hover:border-red-500 hover:text-red-500 transition-colors">
                                DEL
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="border-2 border-dashed border-gray-800 p-8 text-center">
                    <p class="text-gray-700 text-xs">NO_AUTOMATIONS_YET — create one above.</p>
                </div>
            @endforelse
        </div>
    </div>

        @endif

@push('scripts')
<script>
const priorityOpts = ['low','normal','critical'];
const statusOpts   = ['todo','doing','done'];

function toggleActionFields(type) {
    const msgDiv = document.getElementById('field_message');
    const valDiv = document.getElementById('field_value');
    const valLabel = document.getElementById('field_value_label');
    const valSelect = document.getElementById('action_value_select');

    if (type === 'set_priority' || type === 'move_status') {
        msgDiv.classList.add('hidden');
        valDiv.classList.remove('hidden');
        const opts = type === 'set_priority' ? priorityOpts : statusOpts;
        valLabel.textContent = type === 'set_priority' ? 'PRIORITY' : 'STATUS';
        valSelect.innerHTML = opts.map(o => `<option value="${o}">${o.toUpperCase()}</option>`).join('');
    } else {
        msgDiv.classList.remove('hidden');
        valDiv.classList.add('hidden');
    }
}
</script>
@endpush
</x-app-layout>
