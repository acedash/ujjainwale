<div class="max-w-6xl mx-auto flex flex-col md:flex-row min-h-[calc(100vh-80px)] relative border-x border-gray-200 shadow-sm bg-white">
    
    <!-- Mobile Toggle Button -->
    <div class="md:hidden fixed bottom-6 left-1/2 -translate-x-1/2 z-40">
        <button wire:click="toggleMobileMap" class="bg-ink text-white px-6 py-3 rounded-full shadow-xl font-sans font-medium flex items-center gap-2 border border-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-ink transition-all">
            @if($showMapOnMobile)
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                Show List
            @else
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                Map View
            @endif
        </button>
    </div>

    <!-- Left Column: Filters and List -->
    <div class="w-full md:w-[400px] lg:w-[450px] flex-shrink-0 flex flex-col bg-white border-r border-gray-200 {{ $showMapOnMobile ? 'hidden md:flex' : 'flex' }}">
        
        <!-- Filters Header -->
        <div class="p-6 border-b border-gray-200 sticky top-0 z-20 bg-white/95 backdrop-blur-sm">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-display font-bold text-ink">Explore Indore</h1>
                    <a href="{{ route('directory.register') }}" class="bg-city-accent hover:bg-city-accent/90 text-white shadow-sm px-4 py-2 rounded-full font-sans font-medium text-sm transition-colors">Add Business</a>
                </div>

                <!-- Search -->
                <div class="relative mb-4">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search places, categories..." class="w-full pl-11 pr-4 py-3 border border-gray-200 bg-white rounded-xl font-sans text-sm focus:border-city-accent focus:ring-4 focus:ring-city-accent/10 transition-all text-ink placeholder-gray-400 shadow-sm">
                </div>

                <!-- Filters -->
                <div class="flex gap-3">
                    <!-- Category Filter -->
                    <select wire:model.live="category" class="w-full px-4 py-3 border border-gray-200 bg-white rounded-xl font-sans text-sm focus:border-city-accent focus:ring-4 focus:ring-city-accent/10 transition-all text-gray-700 shadow-sm">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>

                    <!-- Neighborhood Filter -->
                    <select wire:model.live="neighborhood" class="w-full px-4 py-3 border border-gray-200 bg-white rounded-xl font-sans text-sm focus:border-city-accent focus:ring-4 focus:ring-city-accent/10 transition-all text-gray-700 shadow-sm">
                        <option value="">All Neighborhoods</option>
                        @foreach($neighborhoods as $hood)
                            <option value="{{ $hood->id }}">{{ $hood->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

        <!-- Business List -->
        <div class="flex-grow overflow-y-auto p-0 relative" x-data="{ stagger: 0 }">
            <!-- Loading overlay -->
            <div wire:loading class="absolute inset-0 bg-white/60 z-10 flex items-center justify-center backdrop-blur-sm">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            </div>

            <div class="flex flex-col gap-4 p-6 pt-2">
                @forelse($businesses as $business)
                    <div class="relative group" 
                         x-data="{ shown: false }" 
                         x-intersect.once="setTimeout(() => shown = true, stagger * 100); stagger = (stagger + 1) % 5"
                         :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                         class="transition-all duration-500 ease-out">
                         
                        <!-- Card Content -->
                        <a id="business-card-{{ $business->id }}" href="{{ route('directory.show', $business->slug) }}" class="block bg-white p-5 rounded-2xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(0,0,0,0.05)] hover:border-city-accent/30 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all">
                            <div class="flex gap-4">
                                <!-- Thumbnail (Left side) -->
                                <div class="flex-shrink-0">
                                    @if($business->hasMedia('cover'))
                                        <img src="{{ $business->getFirstMediaUrl('cover') }}" class="w-[100px] h-[100px] md:w-[120px] md:h-[120px] rounded-xl object-cover border border-gray-100 shadow-sm">
                                    @elseif($business->hasMedia('logo'))
                                        <img src="{{ $business->getFirstMediaUrl('logo') }}" class="w-[100px] h-[100px] md:w-[120px] md:h-[120px] rounded-xl object-contain border border-gray-100 bg-white p-2 shadow-sm">
                                    @else
                                        <div class="w-[100px] h-[100px] md:w-[120px] md:h-[120px] rounded-xl bg-gray-50 flex items-center justify-center border border-gray-100 shadow-sm">
                                            <span class="font-display font-medium text-gray-300 text-3xl">{{ substr($business->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex-grow flex flex-col justify-between overflow-hidden">
                                    <div>
                                        <h3 class="font-display font-medium text-lg text-ink truncate mb-1 group-hover:text-city-accent transition-colors">{{ $business->name }}</h3>
                                        
                                        <!-- Rating & Category -->
                                        <div class="flex items-center gap-1.5 text-sm mb-1.5">
                                            <span class="font-sans font-medium text-gray-800">{{ $business->reviews_avg_rating ? number_format($business->reviews_avg_rating, 1) : 'New' }}</span>
                                            @if($business->reviews_avg_rating)
                                                <div class="flex items-center text-yellow-500">
                                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                </div>
                                            @endif
                                            <span class="text-gray-500 font-sans">({{ $business->reviews_count }})</span>
                                            <span class="text-gray-300 mx-0.5">&middot;</span>
                                            <span class="text-gray-600 font-sans truncate">{{ $business->categories->first()?->name ?? 'Business' }}</span>
                                        </div>
                                        
                                        <!-- Address / Neighborhood -->
                                        <div class="text-sm text-gray-500 font-sans leading-relaxed line-clamp-2">
                                            {{ $business->neighborhoods->first()?->name ?? 'Indore' }} &middot; {{ $business->address }}
                                        </div>
                                    </div>
                                    
                                    <!-- Status -->
                                    <div class="text-xs font-sans mt-3 flex items-center gap-2">
                                        <span class="text-green-700 font-medium bg-green-50 px-2 py-0.5 rounded border border-green-100">Open</span>
                                        @if($business->status === 'claimed')
                                            <span class="text-city-accent font-medium bg-red-50 px-2 py-0.5 rounded border border-red-100 flex items-center gap-1">
                                                <svg class="w-3 h-3 text-city-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Verified
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="text-center py-16 px-6 bg-gray-50/50 rounded-2xl border border-dashed border-gray-200">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100 shadow-sm">
                            <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-medium text-ink font-display mb-2">No businesses found</h3>
                        <p class="text-sm text-gray-500 font-sans max-w-sm mx-auto leading-relaxed">We couldn't find any businesses matching your criteria. Try adjusting your filters or search term.</p>
                        <button wire:click="$set('search', '')" class="mt-6 text-city-accent hover:opacity-80 font-sans text-sm font-bold transition-colors uppercase tracking-wider">Clear all filters</button>
                    </div>
                @endforelse
            </div>

            @if($businesses->hasPages())
                <div class="p-6 border-t border-gray-100">
                    {{ $businesses->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Right Column: Map -->
    <div class="flex-grow h-[calc(100vh-80px)] sticky top-[80px] {{ $showMapOnMobile ? 'block' : 'hidden md:block' }}">
        <x-directory-map :initial-markers="$mapBusinesses" />
    </div>

</div>
