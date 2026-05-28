<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('onboarding.welcome') }}" class="text-gray-500 hover:text-white text-sm transition-colors">&lt; BACK</a>
            <span class="text-gray-700">/</span>
            <span class="text-xs text-gray-600 font-mono tracking-widest">SETUP_WIZARD</span>
            <span class="text-gray-800">/</span>
            <h1 class="text-xl font-bold text-white uppercase">CREATE_SPRINT</h1>
        </div>
    </x-slot>

    <div class="min-h-[70vh] flex items-center justify-center p-6">
        <div class="w-full max-w-2xl">

            {{-- Step indicator --}}
            <div class="flex items-center gap-0 mb-12">
                @foreach([['01','WELCOME',false,true], ['02','SPRINT',true,false], ['03','BACKLOG',false,false], ['04','BOARD',false,false]] as [$num, $label, $active, $done])
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
            <div class="border-2 border-gray-800 bg-black p-8">
                <p class="text-xs text-gray-600 font-mono tracking-widest mb-2">// STEP_02</p>
                <h2 class="text-2xl font-bold text-white mb-1">CREATE YOUR FIRST SPRINT</h2>
                <p class="text-xs text-gray-600 font-mono mb-8">A sprint is a time-boxed period of focused work. Give it a name and dates.</p>

                <form action="{{ route('onboarding.sprint.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label class="text-xs text-gray-500 font-mono block mb-1.5">SPRINT_NAME *</label>
                        <input type="text" name="sprint_name" value="{{ old('sprint_name', 'Sprint 1') }}" required autofocus
                               placeholder="e.g. Sprint 1 — MVP Launch"
                               class="w-full bg-black border {{ $errors->has('sprint_name') ? 'border-red-500' : 'border-gray-700' }}
                                      text-white text-sm px-4 py-3 focus:border-neon-green focus:outline-none font-mono">
                        @error('sprint_name')
                            <p class="text-red-500 text-xs mt-1 font-mono">&gt; {{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-gray-500 font-mono block mb-1.5">START_DATE *</label>
                            <input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required
                                   class="w-full bg-black border {{ $errors->has('start_date') ? 'border-red-500' : 'border-gray-700' }}
                                          text-white text-sm px-4 py-3 focus:border-neon-green focus:outline-none font-mono">
                            @error('start_date')
                                <p class="text-red-500 text-xs mt-1 font-mono">&gt; {{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 font-mono block mb-1.5">END_DATE *</label>
                            <input type="date" name="end_date" value="{{ old('end_date', date('Y-m-d', strtotime('+14 days'))) }}" required
                                   class="w-full bg-black border {{ $errors->has('end_date') ? 'border-red-500' : 'border-gray-700' }}
                                          text-white text-sm px-4 py-3 focus:border-neon-green focus:outline-none font-mono">
                            @error('end_date')
                                <p class="text-red-500 text-xs mt-1 font-mono">&gt; {{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="border border-gray-800 p-4 bg-gray-950">
                        <p class="text-xs text-gray-600 font-mono">
                            <span class="text-neon-green">TIP:</span> Most teams run 1–2 week sprints. You can create more sprints later.
                        </p>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <a href="{{ route('onboarding.welcome') }}"
                           class="text-xs text-gray-600 hover:text-white transition-colors font-mono">
                            &lt; BACK
                        </a>
                        <button type="submit"
                                class="bg-neon-green text-black text-sm font-bold font-mono px-8 py-3 hover:bg-white transition-colors tracking-widest">
                            CREATE_SPRINT &rarr;
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
