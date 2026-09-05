<x-app-layout>
    <x-neighborhood-strip :neighborhoods="$neighborhoods" />

    <div class="max-w-7xl mx-auto px-8 md:px-12 pt-8 pb-12">
        <!-- Hero & Secondary Section -->
        @if($heroItems->isNotEmpty() || $secondaryItems->isNotEmpty())
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-16">
                @if($heroItems->isNotEmpty())
                    <div class="lg:col-span-8" x-data="heroCarousel(@js($heroItems->count()))" @mouseenter="pause()" @mouseleave="resume()">
                        <div class="relative group">
                            @foreach($heroItems as $index => $heroArticle)
                                <div x-show="activeSlide === {{ $index }}"
                                     x-transition:enter="transition ease-out duration-500"
                                     x-transition:enter-start="opacity-0 translate-x-4"
                                     x-transition:enter-end="opacity-100 translate-x-0"
                                     x-transition:leave="transition ease-in duration-300 absolute inset-0"
                                     x-transition:leave-start="opacity-100"
                                     x-transition:leave-end="opacity-0 -translate-x-4"
                                     style="display: {{ $index === 0 ? 'block' : 'none' }};"
                                     class="w-full">
                                    <x-article-card :article="$heroArticle" variant="hero" />
                                </div>
                            @endforeach

                            <!-- Carousel Controls -->
                            <div class="absolute top-4 right-4 flex items-center gap-2 bg-ink/70 backdrop-blur-md px-3 py-2 rounded-full z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 focus-within:opacity-100">
                                <button @click="togglePlayPause()" class="text-white hover:text-city-accent focus:outline-none focus:ring-2 focus:ring-city-accent rounded-full p-1 transition-colors" aria-label="Play/Pause Carousel">
                                    <template x-if="isPlaying">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z"/></svg>
                                    </template>
                                    <template x-if="!isPlaying">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </template>
                                </button>
                                
                                <div class="flex items-center gap-2 ml-2">
                                    @foreach($heroItems as $index => $heroArticle)
                                        <button @click="goToSlide({{ $index }})" 
                                                class="w-2 h-2 rounded-full transition-all focus:outline-none focus:ring-2 focus:ring-city-accent focus:ring-offset-2 focus:ring-offset-ink"
                                                :class="activeSlide === {{ $index }} ? 'bg-city-accent w-5' : 'bg-white/60 hover:bg-white'"
                                                aria-label="Go to slide {{ $index + 1 }}"></button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if($secondaryItems->isNotEmpty())
                    <div class="lg:col-span-4 flex flex-col">
                        <div class="border-b-2 border-gray-100 mb-6 pb-4">
                            <h3 class="font-display font-medium text-xl text-ink">Latest Updates</h3>
                        </div>
                        <div class="flex flex-col divide-y divide-gray-200">
                            @foreach($secondaryItems as $article)
                                <div class="py-4 first:pt-0 last:pb-0">
                                    <x-article-card :article="$article" variant="compact" />
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif

@pushonce('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('heroCarousel', (slideCount) => ({
            activeSlide: 0,
            isPlaying: true,
            interval: null,
            
            init() {
                if (slideCount <= 1) {
                    this.isPlaying = false;
                    return;
                }
                this.startInterval();
            },
            
            startInterval() {
                if (!this.interval && this.isPlaying) {
                    this.interval = setInterval(() => {
                        this.nextSlide();
                    }, 6500);
                }
            },
            
            stopInterval() {
                if (this.interval) {
                    clearInterval(this.interval);
                    this.interval = null;
                }
            },
            
            pause() {
                this.stopInterval();
            },
            
            resume() {
                if (this.isPlaying) {
                    this.startInterval();
                }
            },
            
            togglePlayPause() {
                this.isPlaying = !this.isPlaying;
                if (this.isPlaying) {
                    this.startInterval();
                } else {
                    this.stopInterval();
                }
            },
            
            nextSlide() {
                this.activeSlide = (this.activeSlide + 1) % slideCount;
            },
            
            goToSlide(index) {
                this.activeSlide = index;
                this.stopInterval();
                if (this.isPlaying) {
                    this.startInterval();
                }
            }
        }))
    })
</script>
@endpushonce

        <hr class="border-newsprint my-12">

        <!-- Category Rails -->
        <div class="space-y-16">
            @foreach($categories as $index => $category)
                @if($category->mixed_items->count() > 0)
                    <section class="mb-12">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="font-display font-medium text-2xl text-ink flex items-center gap-3">
                                <span class="w-2 h-2 rounded-full bg-blue-600"></span>{{ $category->name }}
                            </h2>
                            <a href="{{ route('categories.show', $category) }}" class="font-sans font-semibold text-sm text-city-accent hover:text-ink transition-colors uppercase tracking-wide">
                                More {{ $category->name }} &rarr;
                            </a>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" x-data="{ stagger: 0 }">
                            @foreach($category->mixed_items as $article)
                                <div class="relative group" 
                                     x-data="{ shown: false }" 
                                     x-intersect.once="setTimeout(() => shown = true, stagger * 100); stagger = (stagger + 1) % 4"
                                     :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                                     class="transition-all duration-700 ease-out h-full">
                                    <x-article-card :article="$article" />
                                </div>
                            @endforeach
                        </div>
                        </section>
                    @endif

                <!-- Insert Local Businesses Module after the second category, or after the first if it's the only one -->
                @if(($index === 1 || ($loop->last && $index < 1)) && $featuredBusinesses->isNotEmpty())
                    <section class="bg-gray-50 rounded-3xl p-8 lg:p-12 my-16 border border-gray-100 relative overflow-hidden">
                        
                        <div class="flex flex-col sm:flex-row items-baseline justify-between mb-10 relative z-10">
                            <div>
                                <h2 class="font-display font-medium text-3xl text-ink tracking-tight flex items-center gap-3">
                                    <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>Local Spotlight
                                </h2>
                                <p class="font-sans text-gray-500 mt-2 ml-6">Discover top-rated businesses and hidden gems in Indore.</p>
                            </div>
                            <a href="/directory" class="mt-4 sm:mt-0 font-sans font-medium text-sm text-blue-600 hover:text-blue-700 transition-colors uppercase tracking-widest flex items-center gap-2 group">
                                View Directory 
                                <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                            </a>
                        </div>
                        
                        <div class="flex overflow-x-auto gap-6 pb-8 -mx-4 px-4 snap-x snap-mandatory hide-scrollbar relative z-10" style="scrollbar-width: none;">
                            @foreach($featuredBusinesses as $business)
                                <div class="snap-center shrink-0 w-80 sm:w-96 group relative">
                                    @if($business->hasMedia('cover'))
                                        <div class="absolute -inset-2 bg-cover bg-center rounded-3xl opacity-0 group-hover:opacity-40 blur-2xl transition-opacity duration-700 transform translate-y-2 scale-95"
                                             style="background-image: url('{{ $business->getFirstMediaUrl('cover') }}'); transform: translateZ(0);"></div>
                                    @endif
                                    <a href="/directory/{{ $business->slug }}" class="block relative h-full bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:border-blue-100 hover:shadow-md transition-all duration-300 transform group-hover:-translate-y-1">
                                        @if($business->hasMedia('cover'))
                                            <div class="h-48 -mx-6 -mt-6 mb-5 overflow-hidden rounded-t-2xl relative">
                                                <div class="absolute inset-0 bg-black/5 group-hover:bg-transparent transition-colors duration-500 z-10"></div>
                                                <img src="{{ $business->getFirstMediaUrl('cover') }}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700 ease-out">
                                            </div>
                                        @endif
                                        <h3 class="font-display font-medium text-2xl text-ink mb-2 group-hover:text-blue-600 transition-colors">{{ $business->name }}</h3>
                                        <p class="font-sans text-sm text-gray-500 line-clamp-2 leading-relaxed">{{ $business->description }}</p>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
            @endforeach
            
            <!-- Fallback if there are no categories at all but there are businesses -->
            @if($categories->isEmpty() && $featuredBusinesses->isNotEmpty())
                <section class="bg-gray-50 rounded-3xl p-8 lg:p-12 my-16 border border-gray-100 relative overflow-hidden">
                    
                    <div class="flex flex-col sm:flex-row items-baseline justify-between mb-10 relative z-10">
                        <div>
                            <h2 class="font-display font-medium text-3xl text-ink tracking-tight flex items-center gap-3">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>Local Spotlight
                            </h2>
                            <p class="font-sans text-gray-500 mt-2 ml-6">Discover top-rated businesses and hidden gems in Indore.</p>
                        </div>
                        <a href="/directory" class="mt-4 sm:mt-0 font-sans font-medium text-sm text-blue-600 hover:text-blue-700 transition-colors uppercase tracking-widest flex items-center gap-2 group">
                            View Directory 
                            <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                        </a>
                    </div>
                    
                    <div class="flex overflow-x-auto gap-6 pb-8 -mx-4 px-4 snap-x snap-mandatory hide-scrollbar relative z-10" style="scrollbar-width: none;">
                        @foreach($featuredBusinesses as $business)
                            <div class="snap-center shrink-0 w-80 sm:w-96 group relative">
                                @if($business->hasMedia('cover'))
                                    <div class="absolute -inset-2 bg-cover bg-center rounded-3xl opacity-0 group-hover:opacity-40 blur-2xl transition-opacity duration-700 transform translate-y-2 scale-95"
                                         style="background-image: url('{{ $business->getFirstMediaUrl('cover') }}'); transform: translateZ(0);"></div>
                                @endif
                                <a href="/directory/{{ $business->slug }}" class="block relative h-full bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:border-blue-100 hover:shadow-md transition-all duration-300 transform group-hover:-translate-y-1">
                                    @if($business->hasMedia('cover'))
                                        <div class="h-48 -mx-6 -mt-6 mb-5 overflow-hidden rounded-t-2xl relative">
                                            <div class="absolute inset-0 bg-black/5 group-hover:bg-transparent transition-colors duration-500 z-10"></div>
                                            <img src="{{ $business->getFirstMediaUrl('cover') }}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700 ease-out">
                                        </div>
                                    @endif
                                    <h3 class="font-display font-medium text-2xl text-ink mb-2 group-hover:text-blue-600 transition-colors">{{ $business->name }}</h3>
                                    <p class="font-sans text-sm text-gray-500 line-clamp-2 leading-relaxed">{{ $business->description }}</p>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
        
        <!-- Trending Elsewhere (External Aggregation) -->
        <div class="mt-16 mb-8">
            <x-trending-elsewhere :headlines="$externalHeadlines" />
        </div>
    </div>
</x-app-layout>
