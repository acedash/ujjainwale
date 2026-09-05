<x-dashboard-layout>
    <div class="relative bg-white/70 backdrop-blur-xl p-8 border border-white/50 rounded-3xl shadow-sm overflow-hidden">
        <!-- Subtle Glow -->
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-yellow-500/5 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 relative z-10">
            <div>
                <h2 class="font-display font-black text-3xl text-ink">My Reviews</h2>
                <p class="font-sans text-gray-500 mt-1">Your recent feedback on local businesses.</p>
            </div>
            <a href="{{ route('directory') }}" class="inline-flex items-center gap-2 font-sans text-sm font-bold text-ink bg-white border border-gray-200 hover:border-city-accent px-5 py-2.5 rounded-full transition-all shadow-sm">
                Explore Directory
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>

        <div class="relative z-10">
            @if($reviews->count() > 0)
                <div class="space-y-6">
                    @foreach($reviews as $review)
                        <div class="group bg-white rounded-2xl border border-gray-200/60 p-6 sm:p-8 shadow-sm hover:shadow-lg hover:border-yellow-400/30 transition-all relative overflow-hidden">
                            <!-- Quotation Mark Accent -->
                            <svg class="absolute top-4 right-4 w-24 h-24 text-gray-50 opacity-50 group-hover:text-yellow-50 group-hover:scale-110 transition-all pointer-events-none" fill="currentColor" viewBox="0 0 32 32"><path d="M10 8c-3.3 0-6 2.7-6 6v10h10V14H8c0-2.2 1.8-4 4-4V8zm14 0c-3.3 0-6 2.7-6 6v10h10V14h-6c0-2.2 1.8-4 4-4V8z"></path></svg>
                            
                            <div class="relative z-10 flex flex-col md:flex-row md:items-start justify-between gap-4">
                                <div>
                                    <h3 class="font-display font-bold text-xl text-ink mb-1 group-hover:text-city-accent transition-colors">
                                        <a href="{{ route('directory.show', $review->business) }}">
                                            {{ $review->business->name }}
                                        </a>
                                    </h3>
                                    
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center gap-0.5 text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-5 h-5 {{ $i <= $review->rating ? 'fill-current' : 'text-gray-200 fill-current' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            @endfor
                                        </div>
                                        <span class="font-sans text-sm text-gray-400 font-medium border-l border-gray-200 pl-3">
                                            {{ $review->created_at->format('F j, Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="relative z-10 mt-5">
                                <p class="font-sans text-gray-700 leading-relaxed whitespace-pre-line text-lg">{{ $review->content }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16 bg-white/50 backdrop-blur-sm rounded-2xl border border-dashed border-gray-300">
                    <div class="w-20 h-20 bg-yellow-50 rounded-full flex items-center justify-center mx-auto mb-4 text-yellow-500">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                    </div>
                    <h3 class="font-display font-bold text-xl text-ink mb-2">No Reviews Yet</h3>
                    <p class="font-sans text-gray-500 mb-6 max-w-sm mx-auto">You haven't written any reviews yet. Share your experiences to help others!</p>
                    <a href="{{ route('directory') }}" class="inline-flex font-sans text-sm font-bold text-white bg-ink px-6 py-3 rounded-full hover:bg-city-accent transition-all shadow-lg shadow-ink/20 hover:-translate-y-0.5">Explore the Directory</a>
                </div>
            @endif
        </div>
    </div>
</x-dashboard-layout>
