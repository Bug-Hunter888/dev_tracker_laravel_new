<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-white text-sm transition-colors">
                &lt; BOARDS
            </a>
            <span class="text-gray-700">/</span>
            <h1 class="text-xl font-bold text-white uppercase">ANALYTICS_</h1>
        </div>
        <p class="text-xs text-gray-500 mt-1">{{ $team->name }}</p>
    </x-slot>

    <div class="p-8 max-w-3xl mx-auto">
        <div class="border-2 border-yellow-600 bg-black p-12 text-center">
            <p class="text-xs text-yellow-600 tracking-widest mb-3">// FEATURE_LOCKED</p>
            <h2 class="text-3xl font-bold text-white mb-4">ANALYTICS REQUIRE PRO</h2>
            <p class="text-sm text-gray-500 mb-8 max-w-lg mx-auto leading-relaxed">
                Your team is on the <span class="text-white font-bold">FREE</span> plan.
                Upgrade to <span class="text-neon-green font-bold">PRO</span> or
                <span class="text-yellow-500 font-bold">TEAM</span> to unlock full analytics.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-left max-w-xl mx-auto mb-8">
                <div class="border border-gray-800 bg-gray-950 p-4 font-mono text-xs text-gray-600 space-y-2">
                    <p class="text-gray-400 font-bold mb-2">WHAT YOU GET:</p>
                    <p><span class="text-neon-green">+</span> 30-day activity heatmap</p>
                    <p><span class="text-neon-green">+</span> Tasks shipped per member</p>
                    <p><span class="text-neon-green">+</span> Project completion charts</p>
                    <p><span class="text-neon-green">+</span> Per-project task breakdown</p>
                    <p><span class="text-neon-green">+</span> Overdue task tracking</p>
                </div>
                <div class="border border-gray-800 bg-gray-950 p-4 font-mono text-xs space-y-3">
                    <p class="text-gray-400 font-bold mb-2">PLANS WITH ANALYTICS:</p>
                    <div>
                        <span class="border border-neon-green text-neon-green px-2 py-0.5 text-xs font-bold">PRO</span>
                        <p class="text-gray-600 mt-1">$9/mo per team</p>
                    </div>
                    <div>
                        <span class="border border-yellow-500 text-yellow-500 px-2 py-0.5 text-xs font-bold">TEAM</span>
                        <p class="text-gray-600 mt-1">$29/mo per team</p>
                    </div>
                </div>
            </div>

            <p class="text-xs text-gray-700">Contact your team admin to upgrade the plan.</p>
        </div>
    </div>
</x-app-layout>
