<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('onboarding.sprint') }}" class="text-gray-500 hover:text-white text-sm transition-colors">&lt; BACK</a>
            <span class="text-gray-700">/</span>
            <span class="text-xs text-gray-600 font-mono tracking-widest">SETUP_WIZARD</span>
            <span class="text-gray-800">/</span>
            <h1 class="text-xl font-bold text-white uppercase">SETUP_BACKLOG</h1>
        </div>
    </x-slot>

    <div class="min-h-[70vh] flex items-center justify-center p-6">
        <div class="w-full max-w-3xl">

            {{-- Step indicator --}}
            <div class="flex items-center gap-0 mb-10">
                @foreach([['01','WELCOME',false,true], ['02','SPRINT',false,true], ['03','BACKLOG',true,false], ['04','BOARD',false,false]] as [$num, $label, $active, $done])
                    <div class="flex items-center {{ !$loop->last ? 'flex-1' : '' }}">
                        <div class="flex flex-col items-center">
                            <span class="w-8 h-8 flex items-center justify-center text-xs font-bold font-mono border-2
                                {{ $active ? 'border-neon-green text-neon-green bg-neon-green/10' : ($done ? 'border-gray-700 text-gray-600' : 'border-gray-800 text-gray-700') }}">
                                {{ $done ? '✓' : $num }}
                            </span>
                            <span class="text-xs font-mono mt-1 {{ $active ? 'text-neon-green' : 'text-gray-700' }}">{{ $label }}</span>
                        </div>
                        @if(!$loop->last)
                            <div class="flex-1 h-px bg-gray-800 mx-2 mt-[-1rem]"></div>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Flash messages --}}
            @if(session('invite_success'))
                <div class="border border-neon-green px-4 py-2 text-xs text-neon-green font-mono mb-4">
                    &gt; {{ session('invite_success') }}
                </div>
            @endif
            @if(session('invite_error'))
                <div class="border border-red-500 px-4 py-2 text-xs text-red-500 font-mono mb-4">
                    &gt; {{ session('invite_error') }}
                </div>
            @endif

            {{-- Sprint context --}}
            @if($sprint)
                <div class="flex items-center gap-3 mb-4 px-4 py-3 border border-gray-800 bg-gray-950">
                    <span class="w-2 h-2 bg-neon-green rounded-full"></span>
                    <span class="text-xs text-gray-500 font-mono">SPRINT:</span>
                    <span class="text-xs text-white font-mono font-bold">{{ $sprint->name }}</span>
                    <span class="text-xs text-gray-700 font-mono ml-auto">{{ $sprint->start_date->format('d M') }} &rarr; {{ $sprint->end_date->format('d M Y') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

                {{-- LEFT: Backlog task form (2 cols wide) --}}
                <div class="lg:col-span-2 border-2 border-gray-800 bg-black p-6">

                    <div class="flex items-start justify-between mb-5">
                        <div>
                            <p class="text-xs text-gray-600 font-mono tracking-widest mb-1">// STEP_03</p>
                            <h2 class="text-xl font-bold text-white">SET UP YOUR BACKLOG</h2>
                            <p class="text-xs text-gray-600 font-mono mt-1">Add tasks to get started. Assign them to teammates.</p>
                        </div>
                        <form action="{{ route('onboarding.backlog.skip') }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="text-xs text-gray-700 hover:text-white transition-colors font-mono border border-gray-800 px-3 py-1.5">
                                SKIP &rarr;
                            </button>
                        </form>
                    </div>

                    <form action="{{ route('onboarding.backlog.store') }}" method="POST"
                          x-data="{
                              tasks: ['', '', ''],
                              assignees: ['', '', ''],
                              addTask() {
                                  this.tasks.push('');
                                  this.assignees.push('');
                                  this.$nextTick(() => {
                                      const inputs = this.$el.querySelectorAll('input[name^=tasks]');
                                      if (inputs.length) inputs[inputs.length - 1].focus();
                                  });
                              },
                              removeTask(i) {
                                  if (this.tasks.length > 1) {
                                      this.tasks.splice(i, 1);
                                      this.assignees.splice(i, 1);
                                  }
                              }
                          }">
                        @csrf

                        {{-- Column headers --}}
                        <div class="flex items-center gap-2 mb-1.5">
                            <span class="w-6"></span>
                            <span class="flex-1 text-xs text-gray-700 font-mono tracking-widest">TASK_TITLE</span>
                            <span class="w-36 text-xs text-gray-700 font-mono tracking-widest">ASSIGN_TO</span>
                            <span class="w-5"></span>
                        </div>

                        <div class="space-y-2 mb-4">
                            <template x-for="(task, index) in tasks" :key="index">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-gray-700 font-mono w-6 text-right shrink-0"
                                          x-text="(index + 1).toString().padStart(2, '0')"></span>
                                    <input type="text"
                                           :name="'tasks[' + index + ']'"
                                           x-model="tasks[index]"
                                           :placeholder="index === 0 ? 'Set up authentication' : (index === 1 ? 'Build landing page' : 'Add a task...')"
                                           class="flex-1 bg-black border border-gray-700 text-white text-sm px-3 py-2
                                                  focus:border-neon-green focus:outline-none font-mono placeholder-gray-800"
                                           @keydown.enter.prevent="addTask()">
                                    <select :name="'assigned_to[' + index + ']'"
                                            x-model="assignees[index]"
                                            class="w-36 bg-black border border-gray-700 text-white text-xs px-2 py-2
                                                   focus:border-neon-green focus:outline-none font-mono">
                                        <option value="">UNASSIGNED</option>
                                        @foreach($members as $member)
                                            <option value="{{ $member->id }}">{{ $member->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" @click="removeTask(index)"
                                            x-show="tasks.length > 1"
                                            class="text-gray-700 hover:text-red-500 transition-colors text-xs font-mono w-5">
                                        &times;
                                    </button>
                                </div>
                            </template>
                        </div>

                        <button type="button" @click="addTask()"
                                class="text-xs text-gray-600 hover:text-neon-green transition-colors font-mono
                                       border border-gray-800 hover:border-neon-green px-3 py-1.5 mb-6">
                            + ADD_ANOTHER
                        </button>

                        @error('tasks')
                            <p class="text-red-500 text-xs font-mono mb-3">&gt; {{ $message }}</p>
                        @enderror

                        <div class="flex items-center justify-between pt-4 border-t border-gray-900">
                            <a href="{{ route('onboarding.sprint') }}"
                               class="text-xs text-gray-600 hover:text-white transition-colors font-mono">
                                &lt; BACK
                            </a>
                            <button type="submit"
                                    class="bg-neon-green text-black text-sm font-bold font-mono px-6 py-2.5
                                           hover:bg-white transition-colors tracking-widest">
                                SAVE_BACKLOG &rarr;
                            </button>
                        </div>
                    </form>
                </div>

                {{-- RIGHT: Invite panel --}}
                <div class="lg:col-span-1 space-y-4">

                    {{-- Invite form --}}
                    <div class="border-2 border-gray-800 bg-black p-5" x-data="{ open: {{ session('invite_error') ? 'true' : 'false' }} }">
                        <button type="button" @click="open = !open"
                                class="w-full flex items-center justify-between mb-0">
                            <div>
                                <p class="text-xs text-gray-600 font-mono tracking-widest">// INVITE_TEAMMATE</p>
                                <p class="text-sm font-bold text-white mt-0.5">Bring someone in</p>
                            </div>
                            <span class="text-gray-600 font-mono text-xs" x-text="open ? '▲' : '▼'"></span>
                        </button>

                        <div x-show="open" x-cloak class="mt-4">
                            <p class="text-xs text-gray-700 font-mono mb-3">
                                They'll get an email link to join your team. Once accepted, they'll appear in the assign dropdown.
                            </p>
                            <form action="{{ route('onboarding.invite') }}" method="POST" class="space-y-3">
                                @csrf
                                <div>
                                    <label class="text-xs text-gray-500 font-mono block mb-1">EMAIL *</label>
                                    <input type="email" name="email" value="{{ old('email') }}"
                                           placeholder="teammate@example.com" required
                                           class="w-full bg-black border border-gray-700 text-white text-xs px-3 py-2
                                                  focus:border-neon-green focus:outline-none font-mono">
                                </div>
                                <button type="submit"
                                        class="w-full text-xs border border-neon-green text-neon-green font-mono py-2
                                               hover:bg-neon-green hover:text-black transition-colors tracking-widest">
                                    SEND_INVITE
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Pending invitations --}}
                    @if($pendingInvites->isNotEmpty())
                        <div class="border border-gray-800 bg-black p-5">
                            <p class="text-xs text-gray-600 font-mono tracking-widest mb-3">// PENDING_INVITES</p>
                            <div class="space-y-2">
                                @foreach($pendingInvites as $invite)
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="min-w-0">
                                            <p class="text-xs text-white font-mono truncate">{{ $invite->email }}</p>
                                            <p class="text-xs text-gray-700 font-mono">{{ $invite->created_at->diffForHumans() }}</p>
                                        </div>
                                        <span class="text-xs border border-yellow-700 text-yellow-700 px-1.5 py-0.5 font-mono shrink-0">
                                            PENDING
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                            <p class="text-xs text-gray-800 font-mono mt-3">
                                Accepted invites will appear in the assign dropdown.
                            </p>
                        </div>
                    @else
                        <div class="border border-gray-900 p-4 text-center">
                            <p class="text-xs text-gray-800 font-mono">NO_PENDING_INVITES</p>
                        </div>
                    @endif

                </div>

            </div>
        </div>
    </div>
</x-app-layout>
