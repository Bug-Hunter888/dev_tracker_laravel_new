<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <span class="text-xs text-gray-600 font-mono tracking-widest">SETUP_WIZARD</span>
            <span class="text-gray-800">/</span>
            <h1 class="text-xl font-bold text-white uppercase">WELCOME</h1>
        </div>
    </x-slot>

    <div class="min-h-[70vh] flex items-center justify-center p-6">
        <div class="w-full max-w-2xl">

            {{-- Step indicator --}}
            <div class="flex items-center gap-0 mb-12">
                @foreach([['01','WELCOME',true], ['02','SPRINT',false], ['03','BACKLOG',false], ['04','BOARD',false]] as [$num, $label, $active])
                    <div class="flex items-center {{ !$loop->last ? 'flex-1' : '' }}">
                        <div class="flex flex-col items-center">
                            <span class="w-8 h-8 flex items-center justify-center text-xs font-bold font-mono border-2
                                {{ $active ? 'border-neon-green text-neon-green' : 'border-gray-800 text-gray-700' }}">
                                {{ $num }}
                            </span>
                            <span class="text-xs font-mono mt-1 {{ $active ? 'text-neon-green' : 'text-gray-700' }}">{{ $label }}</span>
                        </div>
                        @if(!$loop->last)
                            <div class="flex-1 h-px bg-gray-800 mx-2 mt-[-1rem]"></div>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Content --}}
            <div class="border-2 border-gray-800 bg-black p-10 text-center">
                <p class="text-xs text-gray-600 font-mono tracking-widest mb-6">// SYSTEM_INITIALISED</p>
                <h2 class="text-4xl font-bold text-white mb-4 tracking-tight">WELCOME TO<br><span class="text-neon-green">DEVTRACKER</span></h2>
                <p class="text-sm text-gray-500 font-mono mb-2">
                    Let's get your workspace set up in three quick steps.
                </p>
                <p class="text-xs text-gray-700 font-mono mb-10">
                    Sprint &rarr; Backlog &rarr; Board. Takes about 60 seconds.
                </p>

                <div class="grid grid-cols-3 gap-4 mb-10 text-left">
                    <div class="border border-gray-800 p-4">
                        <p class="text-neon-green text-xs font-mono mb-2">01 / SPRINT</p>
                        <p class="text-xs text-gray-500">Define your first sprint with a name and timeline.</p>
                    </div>
                    <div class="border border-gray-800 p-4">
                        <p class="text-yellow-500 text-xs font-mono mb-2">02 / BACKLOG</p>
                        <p class="text-xs text-gray-500">Add your first tasks and issues to the backlog.</p>
                    </div>
                    <div class="border border-gray-800 p-4">
                        <p class="text-blue-400 text-xs font-mono mb-2">03 / BOARD</p>
                        <p class="text-xs text-gray-500">Name your project board and pick a colour.</p>
                    </div>
                </div>

                <a href="{{ route('onboarding.sprint') }}"
                   class="inline-block bg-neon-green text-black text-sm font-bold font-mono px-10 py-3 hover:bg-white transition-colors tracking-widest">
                    GET_STARTED &rarr;
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
