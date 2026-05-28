<div wire:poll.15s>

    {{-- Comments list --}}
    <div class="space-y-4 mb-6">
        @forelse($comments as $comment)
            <div class="border-l-2 border-gray-700 pl-4 hover:border-neon-green transition-colors group">
                <div class="flex items-center justify-between mb-1">
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 bg-gray-800 border border-gray-700 flex items-center justify-center text-xs font-bold text-neon-green">
                            {{ strtoupper(substr($comment->user->name, 0, 2)) }}
                        </div>
                        <span class="text-xs font-bold text-white">{{ strtoupper($comment->user->name) }}</span>
                        <span class="text-xs text-gray-700">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    @if($comment->user_id === auth()->id())
                        <button wire:click="deleteComment({{ $comment->id }})"
                                wire:confirm="Delete this comment?"
                                class="text-gray-700 hover:text-red-500 transition-colors text-xs font-mono opacity-0 group-hover:opacity-100">
                            [ DEL ]
                        </button>
                    @endif
                </div>
                <p class="text-sm text-gray-300 leading-relaxed whitespace-pre-wrap ml-9">{{ $comment->content }}</p>
            </div>
        @empty
            <p class="text-xs text-gray-700 italic">No comments yet. Start the conversation below.</p>
        @endforelse
    </div>

    {{-- New comment form --}}
    <div class="border-2 border-gray-800 p-4 hover:border-gray-600 transition-colors">
        <h4 class="text-xs text-gray-600 uppercase tracking-widest mb-3">ADD_COMMENT</h4>

        <textarea wire:model.live.debounce.200ms="content"
                  rows="4"
                  placeholder="Write your comment..."
                  class="input-brutal resize-none mb-1 text-sm w-full"
                  maxlength="5000"></textarea>

        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-mono {{ strlen($content) > 4800 ? 'text-red-500' : 'text-gray-700' }}">
                {{ strlen($content) }}/5000
            </span>
        </div>

        @error('content')
            <p class="text-red-500 text-xs mb-2">> {{ $message }}</p>
        @enderror

        <button wire:click="submit"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50 cursor-not-allowed"
                class="btn-brutal-sm">
            <span wire:loading.remove wire:target="submit">POST_COMMENT</span>
            <span wire:loading wire:target="submit">POSTING...</span>
        </button>
    </div>

</div>
