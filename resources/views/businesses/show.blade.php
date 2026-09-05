<x-app-layout>
    <div class="max-w-4xl mx-auto bg-white min-h-screen pb-20 shadow-sm border-x border-gray-100">
        <!-- Header Section -->
        <div class="px-6 pt-10 pb-6">
            <h1 class="font-display font-medium text-4xl text-ink tracking-tight mb-2">{{ $business->name }}</h1>
            
            <div class="flex flex-wrap items-center gap-2 text-sm text-gray-600 font-sans">
                @if($business->reviews_avg_rating)
                    <span class="font-medium text-ink">{{ number_format($business->reviews_avg_rating, 1) }}</span>
                    <div class="flex items-center text-yellow-500">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </div>
                    <a href="#reviews" class="underline hover:text-ink transition-colors ml-1">
                        ({{ $business->reviews_count }})
                    </a>
                    <span class="px-1">&middot;</span>
                @endif

                @if($business->status === 'claimed')
                    <span class="flex items-center gap-1 text-green-700 font-medium">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Verified
                    </span>
                    <span class="px-1">&middot;</span>
                @endif

                @if($business->categories->count())
                    <span>{{ $business->categories->pluck('name')->join(', ') }}</span>
                    <span class="px-1">&middot;</span>
                @endif

                @if($business->neighborhoods->count())
                    <span>{{ $business->neighborhoods->pluck('name')->join(', ') }}</span>
                @endif
            </div>
            
            <div class="flex items-center gap-4 mt-4">
                @if($business->hasMedia('logo'))
                    <div class="w-12 h-12 rounded-full overflow-hidden border border-gray-200 bg-white shadow-sm flex-shrink-0">
                        <img src="{{ $business->getFirstMediaUrl('logo') }}" class="w-full h-full object-contain p-1" alt="{{ $business->name }} Logo">
                    </div>
                @endif
            </div>
        </div>

        <!-- Media Grid Section -->
        <div class="px-6 pb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 grid-rows-2 gap-2 h-[400px] rounded-2xl overflow-hidden">
                @if($business->hasMedia('cover'))
                    <div class="md:col-span-2 md:row-span-2 relative group cursor-pointer">
                        <img src="{{ $business->getFirstMediaUrl('cover') }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="{{ $business->name }} Cover">
                        <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-colors"></div>
                    </div>
                @else
                    <div class="md:col-span-2 md:row-span-2 bg-gray-100 flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                @endif

                @if($business->hasMedia('gallery'))
                    @foreach($business->getMedia('gallery')->take(4) as $media)
                        <div class="relative group cursor-pointer overflow-hidden {{ $loop->iteration > 2 ? 'hidden md:block' : '' }}">
                            <img src="{{ $media->getUrl() }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="Gallery image">
                            <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-colors"></div>
                            @if($loop->iteration === 4 && $business->getMedia('gallery')->count() > 4)
                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center backdrop-blur-sm transition-colors group-hover:bg-black/40">
                                    <span class="text-white font-sans font-medium text-lg">+{{ $business->getMedia('gallery')->count() - 4 }}</span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                    @for($i = $business->getMedia('gallery')->count(); $i < 4; $i++)
                        <div class="bg-gray-50 {{ $i > 1 ? 'hidden md:block' : '' }}"></div>
                    @endfor
                @else
                    @for($i = 0; $i < 4; $i++)
                        <div class="bg-gray-50 {{ $i > 1 ? 'hidden md:block' : '' }}"></div>
                    @endfor
                @endif
            </div>
        </div>

        <!-- Quick Action Buttons -->
        <div class="px-6 pb-6 border-b border-gray-200">
            <div class="flex flex-wrap justify-center gap-3">
                @if($business->website)
                    <a href="{{ $business->website }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 px-5 py-2 rounded-full border border-gray-300 hover:bg-red-50 hover:border-red-200 transition-colors cursor-pointer">
                        <svg class="w-5 h-5 text-city-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                        <span class="text-sm font-medium text-gray-700">Website</span>
                    </a>
                @endif
                
                @if($business->lat && $business->lng)
                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $business->lat }},{{ $business->lng }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 px-5 py-2 rounded-full border border-gray-300 hover:bg-red-50 hover:border-red-200 transition-colors cursor-pointer">
                        <svg class="w-5 h-5 text-city-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                        <span class="text-sm font-medium text-gray-700">Directions</span>
                    </a>
                @endif

                <button class="flex items-center gap-2 px-5 py-2 rounded-full border border-gray-300 hover:bg-red-50 hover:border-red-200 transition-colors cursor-pointer">
                    <svg class="w-5 h-5 text-city-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                    <span class="text-sm font-medium text-gray-700">Save</span>
                </button>
                
                <button class="flex items-center gap-2 px-5 py-2 rounded-full border border-gray-300 hover:bg-red-50 hover:border-red-200 transition-colors cursor-pointer">
                    <svg class="w-5 h-5 text-city-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                    <span class="text-sm font-medium text-gray-700">Share</span>
                </button>
                
                @if($business->phone)
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $business->phone) }}" class="flex items-center justify-center gap-2 w-full mt-2 px-6 py-2.5 rounded-full border border-gray-300 hover:bg-red-50 hover:border-red-200 transition-colors cursor-pointer">
                        <svg class="w-5 h-5 text-city-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        <span class="text-sm font-medium text-ink">Call</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- Information List -->
        <div class="px-6 py-2 border-b border-gray-200">
            <!-- Address Row with Map Thumbnail -->
            <div class="flex items-start py-4 border-b border-gray-100 last:border-0 relative min-h-[96px]">
                <div class="w-12 flex-shrink-0 flex justify-center mt-1">
                    <svg class="w-6 h-6 text-city-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <div class="flex-1 pr-24">
                    <p class="font-sans text-gray-800 text-sm leading-relaxed">{{ $business->address ?: 'Address not provided' }}</p>
                </div>
                <!-- Map Thumbnail -->
                @if($business->lat && $business->lng)
                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $business->lat }},{{ $business->lng }}" target="_blank" class="absolute right-0 top-3 w-20 h-20 rounded-lg overflow-hidden border border-gray-200 block hover:opacity-90 transition-opacity">
                        <x-directory-map :initial-markers="[['lat' => $business->lat, 'lng' => $business->lng, 'name' => $business->name, 'address' => $business->address, 'url' => '#']]" />
                    </a>
                @endif
            </div>

            <!-- Phone Row -->
            @if($business->phone)
                <div class="flex items-center py-4 border-b border-gray-100 last:border-0 group cursor-pointer">
                    <div class="w-12 flex-shrink-0 flex justify-center">
                        <svg class="w-6 h-6 text-city-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    </div>
                    <div class="flex-1">
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $business->phone) }}" class="font-sans text-gray-800 text-sm group-hover:text-city-accent transition-colors">{{ $business->phone }}</a>
                    </div>
                </div>
            @endif

            <!-- Email Row -->
            @if($business->email)
                <div class="flex items-center py-4 border-b border-gray-100 last:border-0 group cursor-pointer">
                    <div class="w-12 flex-shrink-0 flex justify-center">
                        <svg class="w-6 h-6 text-city-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <div class="flex-1">
                        <a href="mailto:{{ $business->email }}" class="font-sans text-gray-800 text-sm group-hover:text-city-accent transition-colors">{{ $business->email }}</a>
                    </div>
                </div>
            @endif
        </div>

        <!-- About Section -->
        @if($business->description)
            <div class="px-6 py-8 border-b border-gray-200">
                <h3 class="font-display font-medium text-xl text-ink mb-4">About</h3>
                <div class="font-sans text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $business->description }}</div>
            </div>
        @endif

        <!-- Mentioned Articles -->
        @if($articles->count())
            <div class="px-6 py-8 border-b border-gray-200">
                <h3 class="font-display font-medium text-xl text-ink mb-6">In the News</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @foreach($articles->take(4) as $article)
                        <x-article-card :article="$article" variant="standard" />
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Reviews Section -->
        <div id="reviews" class="px-6 py-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="font-display font-medium text-xl text-ink">Reviews</h3>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="font-medium text-2xl text-ink">{{ $business->reviews_avg_rating ? number_format($business->reviews_avg_rating, 1) : 'No rating' }}</span>
                        <div class="flex items-center text-yellow-500">
                            @for($i=1; $i<=5; $i++)
                                <svg class="w-5 h-5 {{ $business->reviews_avg_rating >= $i ? 'fill-current' : 'text-gray-200 fill-current' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <span class="text-sm text-gray-500">({{ $business->reviews_count }})</span>
                    </div>
                </div>
                <livewire:claim-business :business="$business" />
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10 mb-8">
                @forelse($reviews as $review)
                    <div>
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 font-medium font-sans text-lg">
                                {{ strtoupper(substr($review->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <h5 class="font-sans font-medium text-ink">{{ $review->user->name }}</h5>
                                <div class="flex items-center gap-1 mt-0.5">
                                    <div class="flex text-yellow-500">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-3.5 h-3.5 {{ $review->rating >= $i ? 'fill-current' : 'text-gray-200 fill-current' }}" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="text-sm text-gray-500 ml-2 font-sans">&middot; {{ $review->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                        <p class="font-sans text-gray-700 leading-relaxed">{{ $review->body }}</p>
                    </div>
                @empty
                    <div class="col-span-2 py-8 text-gray-500 font-sans">
                        No reviews yet. Be the first to share your experience!
                    </div>
                @endforelse
            </div>
            
            <div class="border border-gray-200 rounded-2xl p-6 bg-gray-50 shadow-sm">
                @livewire('leave-review', ['business' => $business])
            </div>
        </div>
    </div>
</x-app-layout>
