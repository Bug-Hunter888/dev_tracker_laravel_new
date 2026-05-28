<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-white text-sm transition-colors">&lt; BOARDS</a>
            <span class="text-gray-700">/</span>
            <h1 class="text-xl font-bold text-white uppercase">LABELS_</h1>
        </div>
        <p class="text-xs text-gray-500 mt-1">{{ $team->name }} &mdash; color-coded tags applied to tasks across all boards</p>
    </x-slot>

    <div class="p-6 bg-pitch-black min-h-screen max-w-3xl">

        {{-- Preset suggestions (only shown when no labels exist) --}}
        @if($labels->isEmpty())
        <div class="border-2 border-gray-800 p-5 mb-6">
            <p class="text-xs text-gray-600 uppercase tracking-widest font-bold mb-4">// QUICK_START — click to pre-fill</p>
            <div class="flex flex-wrap gap-2" id="presets">
                @foreach([
                    ['Bug',      '#ef4444'],
                    ['Feature',  '#39FF14'],
                    ['Urgent',   '#f97316'],
                    ['Backend',  '#3b82f6'],
                    ['UI/UX',    '#a855f7'],
                    ['Docs',     '#6b7280'],
                    ['Testing',  '#eab308'],
                    ['Security', '#ec4899'],
                ] as [$pName, $pColor])
                <button type="button"
                        onclick="fillPreset('{{ $pName }}', '{{ $pColor }}')"
                        class="text-xs px-3 py-1 border-2 font-bold transition-opacity hover:opacity-80"
                        style="color:{{ $pColor }};border-color:{{ $pColor }};">
                    {{ $pName }}
                </button>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Create form --}}
        <div class="border-2 border-gray-800 p-5 mb-8" x-data="{ color: '#39FF14' }">
            <p class="text-xs text-gray-600 uppercase tracking-widest font-bold mb-4">// CREATE_LABEL</p>
            <form action="{{ route('labels.store') }}" method="POST">
                @csrf
                <div class="flex gap-3 items-end flex-wrap">

                    <div class="flex-1 min-w-48">
                        <label class="text-xs text-gray-600 block mb-1">NAME</label>
                        <input id="label-name" type="text" name="name" value="{{ old('name') }}"
                               placeholder="e.g. Bug, Feature, Urgent..."
                               maxlength="50" required
                               class="w-full bg-black text-white border-2 border-gray-700 px-3 py-2 text-sm font-mono focus:border-neon-green focus:outline-none">
                    </div>

                    <div>
                        <label class="text-xs text-gray-600 block mb-1">COLOR</label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="color" id="color-picker"
                                   x-model="color"
                                   value="{{ old('color', '#39FF14') }}"
                                   class="w-10 h-10 border-2 border-gray-700 bg-black cursor-pointer p-0.5">
                            <span class="text-xs font-mono w-16" :style="'color:'+color" x-text="color"></span>
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                                class="bg-neon-green text-black font-mono font-bold text-sm px-5 py-2 hover:bg-white transition-colors">
                            + CREATE
                        </button>
                    </div>
                </div>

                {{-- Preview --}}
                <div class="mt-4 flex items-center gap-2">
                    <span class="text-xs text-gray-700 font-mono">PREVIEW:</span>
                    <span class="text-xs px-2 py-0.5 border-2 font-bold font-mono"
                          :style="'color:'+color+';border-color:'+color"
                          x-text="document.getElementById('label-name')?.value || 'LABEL_NAME'">
                    </span>
                </div>

                @error('name')  <p class="text-red-500 text-xs mt-2">&gt; {{ $message }}</p> @enderror
                @error('color') <p class="text-red-500 text-xs mt-1">&gt; {{ $message }}</p> @enderror
            </form>
        </div>

        {{-- Labels list --}}
        <div>
            <p class="text-xs text-gray-600 uppercase tracking-widest font-bold mb-4">
                // TEAM_LABELS <span class="text-gray-700 ml-1">({{ $labels->count() }})</span>
            </p>

            @forelse($labels as $label)
            <div class="border border-gray-800 mb-2 hover:border-gray-600 transition-colors"
                 x-data="{ editing: false, color: '{{ $label->color }}' }">

                {{-- Display row --}}
                <div x-show="!editing" class="flex items-center justify-between px-4 py-3">
                    <div class="flex items-center gap-4">
                        <span class="w-3 h-3 inline-block border"
                              style="background:{{ $label->color }};border-color:{{ $label->color }}"></span>
                        <span class="text-sm font-bold font-mono px-2 py-0.5 border-2"
                              style="color:{{ $label->color }};border-color:{{ $label->color }}">
                            {{ $label->name }}
                        </span>
                        <span class="text-xs text-gray-600 font-mono">{{ $label->tasks_count }} task{{ $label->tasks_count !== 1 ? 's' : '' }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <button @click="editing = true"
                                class="text-xs text-gray-600 hover:text-white transition-colors font-mono">[ EDIT ]</button>
                        <form action="{{ route('labels.destroy', $label) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Delete label \'{{ $label->name }}\'? It will be removed from all tasks.')"
                                    class="text-xs text-gray-700 hover:text-red-500 transition-colors font-mono">[ DEL ]</button>
                        </form>
                    </div>
                </div>

                {{-- Edit row --}}
                <div x-show="editing" x-cloak class="px-4 py-3 border-t border-gray-800 bg-gray-950">
                    <form action="{{ route('labels.update', $label) }}" method="POST">
                        @csrf @method('PATCH')
                        <div class="flex gap-3 items-end flex-wrap">
                            <div class="flex-1 min-w-36">
                                <label class="text-xs text-gray-600 block mb-1">NAME</label>
                                <input type="text" name="name" value="{{ $label->name }}"
                                       required maxlength="50"
                                       class="w-full bg-black text-white border-2 border-gray-700 px-3 py-1.5 text-sm font-mono focus:border-neon-green focus:outline-none">
                            </div>
                            <div>
                                <label class="text-xs text-gray-600 block mb-1">COLOR</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" name="color" x-model="color"
                                           class="w-9 h-9 border border-gray-700 bg-black cursor-pointer p-0.5">
                                    <span class="text-xs font-mono w-16" :style="'color:'+color" x-text="color"></span>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button type="submit"
                                        class="text-xs border-2 border-neon-green text-neon-green px-4 py-1.5 hover:bg-neon-green hover:text-black transition-colors font-bold font-mono">
                                    SAVE
                                </button>
                                <button type="button" @click="editing = false"
                                        class="text-xs border border-gray-700 px-3 py-1.5 text-gray-500 hover:text-white transition-colors font-mono">
                                    ESC
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            @empty
            <div class="border-2 border-dashed border-gray-800 p-10 text-center">
                <p class="text-gray-700 text-xs font-mono mb-1">NO_LABELS_YET</p>
                <p class="text-gray-700 text-xs">Create your first label above or use a quick-start preset.</p>
            </div>
            @endforelse
        </div>

    </div>

    @push('scripts')
    <script>
    function fillPreset(name, color) {
        const nameInput = document.getElementById('label-name');
        const colorPicker = document.getElementById('color-picker');
        if (nameInput)  nameInput.value  = name;
        if (colorPicker) {
            colorPicker.value = color;
            colorPicker.dispatchEvent(new Event('input'));
        }
        nameInput?.focus();
    }
    </script>
    @endpush
</x-app-layout>
