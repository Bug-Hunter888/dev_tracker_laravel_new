<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-bold text-white uppercase">PAYMENT_SUCCESS</h1>
    </x-slot>

    <div class="p-8 max-w-2xl mx-auto">
        <div class="border-2 border-neon-green bg-black p-12 text-center">

            <div class="w-16 h-16 border-2 border-neon-green flex items-center justify-center mx-auto mb-6">
                <svg class="w-8 h-8 text-neon-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <p class="text-xs text-neon-green tracking-widest font-mono mb-3">// TRANSACTION_COMPLETE</p>
            <h2 class="text-3xl font-bold text-white mb-3 uppercase">
                Welcome to <span class="text-neon-green">{{ strtoupper($plan) }}</span>
            </h2>
            <p class="text-sm text-gray-500 mb-8 font-mono leading-relaxed">
                Your team has been upgraded. All {{ strtoupper($plan) }} features are now active.
            </p>

            <div class="bg-gray-950 border border-gray-800 p-4 font-mono text-xs text-left max-w-sm mx-auto mb-8 space-y-1.5">
                <p class="text-gray-600 mb-2">// RECEIPT</p>
                <div class="flex justify-between"><span class="text-gray-600">Plan</span> <span class="text-white uppercase font-bold">{{ $plan }}</span></div>
                <div class="flex justify-between"><span class="text-gray-600">Amount</span> <span class="text-white">{{ $plan === 'pro' ? '$9.00' : '$29.00' }} / month</span></div>
                <div class="flex justify-between"><span class="text-gray-600">Status</span> <span class="text-neon-green font-bold">PAID</span></div>
                <div class="flex justify-between"><span class="text-gray-600">Date</span> <span class="text-white">{{ now()->format('d M Y') }}</span></div>
                <div class="flex justify-between"><span class="text-gray-600">Ref</span> <span class="text-white font-mono">{{ $ref }}</span></div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('dashboard') }}"
                   class="px-6 py-3 bg-neon-green text-black font-mono font-bold text-sm uppercase tracking-wider hover:bg-neon-green/90 transition-colors">
                    GO TO DASHBOARD
                </a>
                <a href="{{ route('analytics') }}"
                   class="px-6 py-3 border border-gray-700 text-gray-300 font-mono text-sm uppercase tracking-wider hover:border-gray-500 transition-colors">
                    VIEW ANALYTICS
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
