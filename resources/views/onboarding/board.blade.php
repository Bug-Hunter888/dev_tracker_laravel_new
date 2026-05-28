<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('onboarding.backlog') }}" class="text-gray-500 hover:text-white text-sm transition-colors">&lt; BACK</a>
            <span class="text-gray-700">/</span>
            <span class="text-xs text-gray-600 font-mono tracking-widest">SETUP_WIZARD</span>
            <span class="text-gray-800">/</span>
            <h1 class="text-xl font-bold text-white uppercase">CREATE_BOARD</h1>
        </div>
    </x-slot>

    <div class="min-h-[70vh] flex items-center justify-center p-6">
        <div class="w-full max-w-2xl">

            {{-- Step indicator --}}
            <div class="flex items-center gap-0 mb-12">
                @foreach([['01','WELCOME',false,true], ['02','SPRINT',false,true], ['03','BACKLOG',false,true], ['04','BOARD',true,false]] as [$num, $label, $active, $done])
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

            {{-- Form --}}
            <div class="border-2 border-gray-800 bg-black p-8" x-data="{ color: '{{ $project->color ?? '#39FF14' }}' }">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <p class="text-xs text-gray-600 font-mono tracking-widest mb-2">// STEP_04 — OPTIONAL</p>
                        <h2 class="text-2xl font-bold text-white mb-1">NAME YOUR BOARD</h2>
                        <p class="text-xs text-gray-600 font-mono">Give your project board a name and colour. You can change this any time.</p>
                    </div>
                    <form action="{{ route('onboarding.board.skip') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="text-xs text-gray-700 hover:text-white transition-colors font-mono border border-gray-800 px-3 py-1.5">
                            SKIP &rarr;
                        </button>
                    </form>
                </div>

                <form action="{{ route('onboarding.board.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label class="text-xs text-gray-500 font-mono block mb-1.5">BOARD_NAME *</label>
                        <input type="text" name="name" value="{{ old('name', $project->name ?? 'My First Project') }}" required autofocus
                               placeholder="e.g. DevTracker App"
                               class="w-full bg-black border {{ $errors->has('name') ? 'border-red-500' : 'border-gray-700' }}
                                      text-white text-sm px-4 py-3 focus:border-neon-green focus:outline-none font-mono">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1 font-mono">&gt; {{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs text-gray-500 font-mono block mb-1.5">BOARD_COLOR</label>
                        <div class="flex items-center gap-4">
                            <input type="color" name="color" x-model="color"
                                   class="w-12 h-10 bg-black border border-gray-700 cursor-pointer p-1">
                            <div class="flex gap-2">
                                @foreach(['#39FF14','#f97316','#60a5fa','#a78bfa','#f43f5e','#facc15','#ffffff'] as $preset)
                                    <button type="button"
                                            @click="color = '{{ $preset }}'"
                                            class="w-6 h-6 rounded-full border-2 transition-all"
                                            :class="color === '{{ $preset }}' ? 'border-white scale-110' : 'border-transparent'"
                                            style="background: {{ $preset }}"></button>
                                @endforeach
                            </div>
                            <span class="text-xs text-gray-600 font-mono" x-text="color"></span>
                        </div>
                    </div>

                    {{-- Preview --}}
                    <div class="border border-gray-800 p-4 bg-gray-950">
                        <p class="text-xs text-gray-700 font-mono mb-3">PREVIEW:</p>
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full" :style="'background:' + color"></span>
                            <span class="text-sm font-bold text-white font-mono">
                                {{ old('name', $project->name ?? 'My First Project') }}
                            </span>
                            <span class="text-xs border border-neon-green text-neon-green px-1.5 py-0.5 font-mono">ACTIVE</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-2 border-t border-gray-900">
                        <a href="{{ route('onboarding.backlog') }}"
                           class="text-xs text-gray-600 hover:text-white transition-colors font-mono">
                            &lt; BACK
                        </a>
                        <button type="submit"
                                class="bg-neon-green text-black text-sm font-bold font-mono px-8 py-3 hover:bg-white transition-colors tracking-widest">
                            LAUNCH_WORKSPACE &rarr;
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
