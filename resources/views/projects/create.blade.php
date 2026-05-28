<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-white text-sm transition-colors">
                &lt; BOARDS
            </a>
            <span class="text-gray-700">/</span>
            <h1 class="text-xl font-bold text-neon-green">> NEW_BOARD_</h1>
        </div>
    </x-slot>

    <div class="p-8 max-w-2xl">
        <form action="{{ route('projects.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-xs text-gray-500 uppercase tracking-widest mb-2">PROJECT_NAME *</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="input-brutal" required placeholder="e.g. API_Redesign_v2">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">> {{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs text-gray-500 uppercase tracking-widest mb-2">DESCRIPTION</label>
                <textarea name="description" rows="3"
                          class="input-brutal resize-none"
                          placeholder="What is this project about?">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-gray-500 uppercase tracking-widest mb-2">START_DATE</label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}" class="input-brutal">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 uppercase tracking-widest mb-2">END_DATE</label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}" class="input-brutal">
                </div>
            </div>

            <div>
                <label class="block text-xs text-gray-500 uppercase tracking-widest mb-3">BOARD_COLOR</label>
                <div class="flex gap-3 flex-wrap" x-data="{ selected: '{{ old('color', '#39FF14') }}' }">
                    @foreach(['#39FF14','#3b82f6','#ef4444','#f59e0b','#8b5cf6','#ec4899','#ffffff','#06b6d4'] as $c)
                        <label class="cursor-pointer relative" title="{{ $c }}">
                            <input type="radio" name="color" value="{{ $c }}" class="sr-only"
                                   @click="selected = '{{ $c }}'"
                                   {{ old('color', '#39FF14') === $c ? 'checked' : '' }}>
                            <div class="w-10 h-10 border-4 transition-all"
                                 :class="selected === '{{ $c }}' ? 'border-white scale-110' : 'border-gray-700 hover:border-gray-400'"
                                 style="background: {{ $c }}"></div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex gap-4 pt-4 border-t-2 border-gray-800">
                <button type="submit" class="btn-brutal">INIT_BOARD</button>
                <a href="{{ route('dashboard') }}"
                   class="border-2 border-gray-700 px-6 py-2 text-gray-400 hover:border-white hover:text-white transition-colors font-bold font-mono">
                    CANCEL
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
