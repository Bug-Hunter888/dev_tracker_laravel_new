<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-white text-sm transition-colors">
                &lt; BACK
            </a>
            <span class="text-gray-700">/</span>
            <h1 class="text-xl font-bold text-white uppercase">UPGRADE_PLAN</h1>
        </div>
        <p class="text-xs text-gray-500 mt-1">{{ $team->name }} &mdash; currently on <span class="text-neon-green font-bold font-mono uppercase">{{ $team->plan }}</span></p>
    </x-slot>

    <div class="p-6 max-w-4xl mx-auto">

        {{-- Demo badge --}}
        <div class="mb-6 flex items-center gap-2 border border-yellow-600 bg-yellow-950/30 text-yellow-500 text-xs font-mono px-4 py-2 w-fit">
            <span class="font-bold">// DEMO_MODE</span>
            <span class="text-yellow-700">— no real payment will be charged</span>
        </div>

        <form action="{{ route('billing.subscribe') }}" method="POST" id="checkout-form">
            @csrf

            {{-- Hidden input that holds the selected plan — this is what actually gets submitted --}}
            <input type="hidden" name="plan" id="selected-plan" value="{{ $preselect }}">

            {{-- Plan selector --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">

                {{-- PRO --}}
                <div id="card-pro"
                     onclick="selectPlan('pro')"
                     class="plan-card cursor-pointer border border-neon-green bg-neon-green/5 p-6 transition-all h-full"
                     style="cursor:pointer;">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <span class="text-xs font-mono tracking-widest text-gray-600 uppercase">Tier 02</span>
                            <h3 class="text-xl font-bold font-mono text-white uppercase mt-0.5">PRO</h3>
                        </div>
                        <div id="dot-pro" class="w-5 h-5 rounded-full border-2 flex items-center justify-center mt-1" style="border-color:#39FF14;">
                            <div id="dot-pro-fill" class="radio-dot w-2.5 h-2.5 rounded-full" style="background:#39FF14;display:block;"></div>
                        </div>
                    </div>
                    <div class="flex items-end gap-1 mb-5">
                        <span class="text-3xl font-bold font-mono text-neon-green">$9</span>
                        <span class="text-sm text-gray-600 font-mono mb-1">/mo per team</span>
                    </div>
                    <ul class="space-y-2 text-xs font-mono text-gray-500">
                        <li><span class="text-neon-green mr-2">+</span>Unlimited projects</li>
                        <li><span class="text-neon-green mr-2">+</span>Full analytics dashboard</li>
                        <li><span class="text-neon-green mr-2">+</span>Automation rules</li>
                        <li><span class="text-neon-green mr-2">+</span>Up to 10 team members</li>
                        <li><span class="text-neon-green mr-2">+</span>Priority support</li>
                    </ul>
                </div>

                {{-- TEAM --}}
                <div id="card-team"
                     onclick="selectPlan('team')"
                     class="plan-card cursor-pointer border border-gray-800 bg-black p-6 transition-all h-full"
                     style="cursor:pointer;">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <div class="inline-block mb-1 px-2 py-0.5 text-xs font-mono font-bold bg-yellow-500/10 text-yellow-500 border border-yellow-500/30">MOST POPULAR</div>
                            <span class="text-xs font-mono tracking-widest text-gray-600 uppercase block">Tier 03</span>
                            <h3 class="text-xl font-bold font-mono text-white uppercase mt-0.5">TEAM</h3>
                        </div>
                        <div id="dot-team" class="w-5 h-5 rounded-full border-2 flex items-center justify-center mt-1" style="border-color:#374151;">
                            <div id="dot-team-fill" class="radio-dot w-2.5 h-2.5 rounded-full" style="background:#eab308;display:none;"></div>
                        </div>
                    </div>
                    <div class="flex items-end gap-1 mb-5">
                        <span class="text-3xl font-bold font-mono text-yellow-500">$29</span>
                        <span class="text-sm text-gray-600 font-mono mb-1">/mo per team</span>
                    </div>
                    <ul class="space-y-2 text-xs font-mono text-gray-500">
                        <li><span class="text-yellow-500 mr-2">+</span>Everything in PRO</li>
                        <li><span class="text-yellow-500 mr-2">+</span>Unlimited team members</li>
                        <li><span class="text-yellow-500 mr-2">+</span>Advanced role permissions</li>
                        <li><span class="text-yellow-500 mr-2">+</span>Custom labels &amp; workflows</li>
                        <li><span class="text-yellow-500 mr-2">+</span>Dedicated support channel</li>
                    </ul>
                </div>
            </div>

            {{-- Fake card form --}}
            <div class="border border-gray-800 bg-black p-6 mb-6">
                <p class="text-xs font-mono text-gray-600 tracking-widest uppercase mb-5">// PAYMENT_DETAILS</p>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-mono text-gray-500 mb-1.5 uppercase tracking-wider">Card Number</label>
                        <input type="text"
                               value="4242 4242 4242 4242"
                               class="w-full bg-gray-950 border border-gray-800 text-white font-mono text-sm px-4 py-3 focus:outline-none focus:border-neon-green transition-colors"
                               placeholder="1234 5678 9012 3456"
                               maxlength="19"
                               id="card-number">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-mono text-gray-500 mb-1.5 uppercase tracking-wider">Expiry</label>
                            <input type="text"
                                   value="12/28"
                                   class="w-full bg-gray-950 border border-gray-800 text-white font-mono text-sm px-4 py-3 focus:outline-none focus:border-neon-green transition-colors"
                                   placeholder="MM/YY"
                                   maxlength="5">
                        </div>
                        <div>
                            <label class="block text-xs font-mono text-gray-500 mb-1.5 uppercase tracking-wider">CVC</label>
                            <input type="text"
                                   value="424"
                                   class="w-full bg-gray-950 border border-gray-800 text-white font-mono text-sm px-4 py-3 focus:outline-none focus:border-neon-green transition-colors"
                                   placeholder="000"
                                   maxlength="4">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-mono text-gray-500 mb-1.5 uppercase tracking-wider">Name on Card</label>
                        <input type="text"
                               value="{{ auth()->user()->name }}"
                               class="w-full bg-gray-950 border border-gray-800 text-white font-mono text-sm px-4 py-3 focus:outline-none focus:border-neon-green transition-colors"
                               placeholder="Full name">
                    </div>
                </div>
            </div>

            {{-- Order summary --}}
            <div class="border border-gray-800 bg-black p-5 mb-6 font-mono text-sm">
                <p class="text-xs text-gray-600 tracking-widest uppercase mb-3">// ORDER_SUMMARY</p>
                <div class="flex justify-between text-gray-400 mb-1">
                    <span>Plan</span>
                    <span id="summary-plan" class="text-white uppercase font-bold">PRO</span>
                </div>
                <div class="flex justify-between text-gray-400 mb-1">
                    <span>Billing</span>
                    <span class="text-white">Monthly</span>
                </div>
                <div class="flex justify-between text-gray-400 mb-3">
                    <span>Team</span>
                    <span class="text-white">{{ $team->name }}</span>
                </div>
                <div class="border-t border-gray-800 pt-3 flex justify-between font-bold">
                    <span class="text-gray-300">Total today</span>
                    <span class="text-neon-green" id="summary-price">$9.00</span>
                </div>
            </div>

            <button type="submit"
                    id="pay-btn"
                    class="w-full py-4 font-mono font-bold text-sm uppercase tracking-wider bg-neon-green text-black hover:bg-neon-green/90 transition-colors">
                SUBSCRIBE &amp; PAY NOW
            </button>

            <p class="text-xs text-gray-700 font-mono text-center mt-3">
                Secured by 256-bit SSL encryption &bull; Cancel anytime
            </p>
        </form>
    </div>

    <script>
        const planMeta = {
            pro:  { label: 'PRO',  price: '$9.00'  },
            team: { label: 'TEAM', price: '$29.00' },
        };

        function selectPlan(plan) {
            // Update the hidden form input — this is what gets submitted
            document.getElementById('selected-plan').value = plan;

            // Update order summary text
            document.getElementById('summary-plan').textContent  = planMeta[plan].label;
            document.getElementById('summary-price').textContent = planMeta[plan].price;

            // Style: deselect all cards
            document.getElementById('card-pro').style.borderColor  = '#1f2937'; // gray-800
            document.getElementById('card-pro').style.background   = '#000';
            document.getElementById('card-team').style.borderColor = '#1f2937';
            document.getElementById('card-team').style.background  = '#000';

            // Hide all radio dots
            document.querySelectorAll('.radio-dot').forEach(d => d.style.display = 'none');

            // Style: highlight selected card
            if (plan === 'pro') {
                document.getElementById('card-pro').style.borderColor = '#39FF14';
                document.getElementById('card-pro').style.background  = 'rgba(57,255,20,0.04)';
                document.getElementById('dot-pro-fill').style.display  = 'block';
                document.getElementById('dot-pro').style.borderColor   = '#39FF14';
                document.getElementById('dot-team').style.borderColor  = '#374151';
            } else {
                document.getElementById('card-team').style.borderColor = '#eab308';
                document.getElementById('card-team').style.background  = 'rgba(234,179,8,0.04)';
                document.getElementById('dot-team-fill').style.display  = 'block';
                document.getElementById('dot-team').style.borderColor   = '#eab308';
                document.getElementById('dot-pro').style.borderColor    = '#374151';
            }
        }

        // Simulate processing state on submit
        document.getElementById('checkout-form').addEventListener('submit', function () {
            const btn = document.getElementById('pay-btn');
            btn.textContent = 'PROCESSING...';
            btn.disabled = true;
            setTimeout(() => { btn.disabled = false; btn.textContent = 'SUBSCRIBE & PAY NOW'; }, 3000);
        });

        // Apply pre-selection from server (e.g. ?plan=team from pricing page)
        selectPlan('{{ $preselect }}');

        // Format card number with spaces
        document.getElementById('card-number').addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').replace(/(.{4})/g, '$1 ').trim().slice(0, 19);
        });
    </script>
</x-app-layout>
