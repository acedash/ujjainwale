<div wire:ignore.self x-data="{ searchOpen: false }" @open-search.window="searchOpen = true; $nextTick(() => $refs.searchInput.focus())" @keydown.escape.window="searchOpen = false">
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
             class="relative min-h-screen md:min-h-0 md:mt-24 md:max-w-3xl mx-auto bg-white w-full md:rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[85vh]">
            
            <!-- Search Input -->
            <form wire:submit.prevent="submit" class="relative flex items-center p-2 border-b border-gray-100 bg-gray-50/50">
                <svg class="w-6 h-6 text-city-accent absolute left-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input wire:model.live.debounce.300ms="search" x-ref="searchInput" type="text" placeholder="Search news, businesses, and more..." class="w-full !pl-16 !pr-16 py-5 text-xl font-display font-medium text-ink bg-transparent border-none focus:ring-0 placeholder-gray-400">
                
                <div wire:loading class="absolute right-16">
                    <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-city-accent"></div>
                </div>

                <button type="button" @click="searchOpen = false" class="absolute right-6 text-gray-400 hover:text-ink transition-colors bg-white shadow-sm p-1.5 rounded-full border border-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </form>

            <!-- Results Area -->
            <div class="overflow-y-auto p-2 flex-grow bg-white">
                @if(strlen($search) >= 2)
                    @if(count($articles) === 0 && count($businesses) === 0)
                        <div class="text-center py-16">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h3 class="font-display font-medium text-lg text-ink">No results found</h3>
                            <p class="font-sans text-gray-500 mt-1">We couldn't find anything matching "{{ $search }}"</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-gray-100">
                            
                            <!-- Articles -->
                            @if(count($articles) > 0)
                                <div class="p-4">
                                    <h3 class="font-sans font-bold text-xs text-city-accent uppercase tracking-wider mb-4 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                                        News Articles
                                    </h3>
                                    <div class="space-y-1">
                                        @foreach($articles as $article)
                                            <a href="{{ route('articles.show', $article) }}" class="flex items-start gap-4 p-3 rounded-xl hover:bg-gray-50 group transition-colors">
                                                @if($article->hasMedia('featured_image'))
                                                    <img src="{{ $article->getFirstMediaUrl('featured_image', 'thumb') }}" class="w-14 h-14 rounded-lg object-cover bg-gray-100 shrink-0 border border-gray-100 group-hover:border-blue-100 transition-colors">
                                                @else
                                                    <div class="w-14 h-14 rounded-lg bg-gray-100 flex items-center justify-center shrink-0 border border-gray-100">
                                                        <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h4 class="font-display font-medium text-ink group-hover:text-city-accent transition-colors line-clamp-2 leading-tight">{{ $article->title }}</h4>
                                                    <p class="font-sans text-[11px] font-medium text-gray-400 mt-1 uppercase tracking-wide">{{ $article->published_at?->format('M j, Y') }}</p>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Businesses -->
                            @if(count($businesses) > 0)
                                <div class="p-4">
                                    <h3 class="font-sans font-bold text-xs text-directory-blue uppercase tracking-wider mb-4 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        Local Directory
                                    </h3>
                                    <div class="space-y-1">
                                        @foreach($businesses as $business)
                                            <a href="{{ route('directory.show', $business) }}" class="flex items-start gap-4 p-3 rounded-xl hover:bg-gray-50 group transition-colors">
                                                @if($business->hasMedia('cover'))
                                                    <img src="{{ $business->getFirstMediaUrl('cover', 'thumb') }}" class="w-14 h-14 rounded-lg object-cover bg-gray-100 shrink-0 border border-gray-100 group-hover:border-blue-100 transition-colors">
                                                @else
                                                    <div class="w-14 h-14 rounded-lg bg-gray-100 flex items-center justify-center shrink-0 border border-gray-100">
                                                        <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h4 class="font-display font-medium text-ink group-hover:text-directory-blue transition-colors line-clamp-1">{{ $business->name }}</h4>
                                                    <p class="font-sans text-xs text-gray-500 mt-1 line-clamp-1">{{ $business->address }}</p>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                    
                                    <a href="{{ route('directory', ['search' => $search]) }}" class="inline-flex items-center gap-2 mt-6 mx-3 font-sans text-sm font-bold text-directory-blue hover:text-ink transition-colors bg-blue-50/50 hover:bg-blue-50 px-4 py-2 rounded-lg">
                                        Search full directory for "{{ $search }}" &rarr;
                                    </a>
                                </div>
                            @endif

                        </div>
                    @endif
                @else
                    <div class="text-center py-20">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 border border-gray-100 shadow-sm relative">
                            <svg class="w-8 h-8 text-city-accent absolute" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            <div class="absolute inset-0 border-2 border-city-accent/20 rounded-full animate-ping" style="animation-duration: 3s;"></div>
                        </div>
                        <h3 class="font-display font-medium text-xl text-ink">What are you looking for?</h3>
                        <p class="font-sans text-gray-500 mt-2 max-w-sm mx-auto">Search for breaking news, specific businesses, or topics across the portal.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
