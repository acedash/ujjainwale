<div x-data="{ searchOpen: false }" @open-search.window="searchOpen = true; $nextTick(() => $refs.searchInput.focus())" @keydown.escape.window="searchOpen = false">
    <!-- Search Modal Overlay -->
    <div x-show="searchOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <!-- Backdrop -->
        <div x-show="searchOpen" x-transition.opacity class="fixed inset-0 bg-ink/80 backdrop-blur-sm" @click="searchOpen = false"></div>

        <!-- Search Panel -->
        <div x-show="searchOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative min-h-screen md:min-h-0 md:mt-24 md:max-w-3xl mx-auto bg-paper w-full md:rounded shadow-2xl overflow-hidden flex flex-col max-h-screen">
            
            <!-- Search Input -->
            <div class="relative flex items-center p-4 border-b border-newsprint bg-white">
                <svg class="w-6 h-6 text-gray-400 absolute left-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input wire:model.live.debounce.300ms="search" x-ref="searchInput" type="text" placeholder="Search news, businesses, and more..." class="w-full pl-12 pr-12 py-4 text-xl font-sans text-ink bg-transparent border-none focus:ring-0 placeholder-gray-400">
                
                <div wire:loading class="absolute right-16">
                    <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-city-accent"></div>
                </div>

                <button @click="searchOpen = false" class="absolute right-6 text-gray-400 hover:text-ink">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Results Area -->
            <div class="overflow-y-auto p-6 flex-grow">
                @if(strlen($search) >= 2)
                    @if(count($articles) === 0 && count($businesses) === 0)
                        <div class="text-center py-12">
                            <p class="font-sans text-gray-500 text-lg">No results found for "{{ $search }}"</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            
                            <!-- Articles -->
                            @if(count($articles) > 0)
                                <div>
                                    <h3 class="font-display font-bold text-lg text-gray-400 uppercase tracking-wider mb-4 border-b border-newsprint pb-2">Articles</h3>
                                    <div class="space-y-4">
                                        @foreach($articles as $article)
                                            <a href="{{ route('articles.show', $article) }}" class="block group">
                                                <h4 class="font-display font-bold text-ink group-hover:text-city-accent transition-colors">{{ $article->title }}</h4>
                                                <p class="font-sans text-xs text-gray-500 mt-1">{{ $article->published_at?->format('M j, Y') }}</p>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Businesses -->
                            @if(count($businesses) > 0)
                                <div>
                                    <h3 class="font-display font-bold text-lg text-gray-400 uppercase tracking-wider mb-4 border-b border-newsprint pb-2">Businesses</h3>
                                    <div class="space-y-4">
                                        @foreach($businesses as $business)
                                            <a href="{{ route('directory.show', $business) }}" class="block group">
                                                <h4 class="font-display font-bold text-ink group-hover:text-city-accent transition-colors">{{ $business->name }}</h4>
                                                <p class="font-sans text-xs text-gray-500 mt-1">{{ $business->address }}</p>
                                            </a>
                                        @endforeach
                                    </div>
                                    
                                    <a href="{{ route('directory', ['search' => $search]) }}" class="inline-block mt-6 font-sans text-sm font-bold text-city-accent hover:underline">
                                        Search directory for "{{ $search }}" &rarr;
                                    </a>
                                </div>
                            @endif

                        </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 16l2.879-2.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="font-sans text-gray-400">Start typing to search...</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
