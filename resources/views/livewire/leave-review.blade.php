<div class="bg-paper p-6 border border-newsprint rounded mt-8">
    <h3 class="font-display font-bold text-2xl text-ink mb-4">Leave a Review</h3>
    
    @if($submitted)
        <div class="bg-green-50 border border-green-200 text-green-800 p-4 rounded font-sans text-sm">
            <p class="font-bold">Thank you for your review!</p>
            <p>Your review has been submitted and is currently pending moderation. It will appear publicly once approved.</p>
        </div>
    @elseif(!auth()->check())
        <div class="font-sans text-sm text-gray-600 mb-4">
            You must be logged in to leave a review.
        </div>
        <a href="{{ route('login') }}" class="inline-block bg-city-accent text-white font-bold py-2 px-4 rounded hover:bg-opacity-90 transition-colors">Log In or Register</a>
    @else
        <form wire:submit="submit" class="space-y-4">
            <div>
                <label class="block font-sans font-bold text-sm text-ink mb-2">Rating</label>
                <div class="flex gap-2">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button" wire:click="$set('rating', {{ $i }})" class="focus:outline-none">
                            <svg class="w-8 h-8 {{ $rating >= $i ? 'text-city-accent' : 'text-gray-300 hover:text-gray-400' }} transition-colors" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </button>
                    @endfor
                </div>
                @error('rating') <span class="text-red-500 text-xs mt-1 block font-sans">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="body" class="block font-sans font-bold text-sm text-ink mb-2">Your Review</label>
                <textarea wire:model="body" id="body" rows="4" class="w-full border-newsprint rounded p-3 font-sans text-sm focus:border-city-accent focus:ring-city-accent" placeholder="Tell us about your experience..."></textarea>
                @error('body') <span class="text-red-500 text-xs mt-1 block font-sans">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="bg-ink text-white font-bold py-2 px-6 rounded hover:bg-opacity-90 transition-colors disabled:opacity-50" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="submit">Submit Review</span>
                <span wire:loading wire:target="submit">Submitting...</span>
            </button>
        </form>
    @endif
</div>
