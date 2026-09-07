<x-app-layout>
    <x-neighborhood-strip :neighborhoods="$neighborhoods" />
    @livewire('news-ticker')

    <!-- Premium Alpine Carousel Hero -->
    @if($heroItems->isNotEmpty())
        @php 
            $carouselItems = $heroItems->take(5); 
            $carouselCount = $carouselItems->count();
        @endphp
        <div class="max-w-7xl mx-auto px-6 md:px-10 mt-8 mb-12">
            <div x-data="{ 
                    activeSlide: 1,
                    totalSlides: {{ $carouselCount }},
                    autoPlay() {
                        setInterval(() => {
                            this.activeSlide = this.activeSlide === this.totalSlides ? 1 : this.activeSlide + 1;
                        }, 5000);
                    }
                 }" 
                 x-init="autoPlay()"
                 class="relative w-full h-auto md:h-[60vh] min-h-[500px] overflow-hidden rounded-3xl shadow-2xl group bg-midnight">
                
                <!-- Slides -->
                <div class="w-full h-full relative">
                    @foreach($carouselItems as $index => $article)
                        <div x-show="activeSlide === {{ $index + 1 }}"
                             x-transition:enter="transition ease-out duration-700"
                             x-transition:enter-start="opacity-0 translate-x-8"
                             x-transition:enter-end="opacity-100 translate-x-0"
                             x-transition:leave="transition ease-in duration-500"
                             x-transition:leave-start="opacity-100 translate-x-0"
                             x-transition:leave-end="opacity-0 -translate-x-8"
                             class="absolute inset-0 w-full h-full"
                             style="display: {{ $index === 0 ? 'block' : 'none' }};">
                            <x-article-card :article="$article" variant="hero-split" />
                        </div>
                    @endforeach
                </div>

                @if($carouselCount > 1)
                    <!-- Navigation Arrows -->
                    <button @click="activeSlide = activeSlide === 1 ? totalSlides : activeSlide - 1" class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 text-white backdrop-blur-md opacity-0 group-hover:opacity-100 transition-all duration-300 z-20 border border-white/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <button @click="activeSlide = activeSlide === totalSlides ? 1 : activeSlide + 1" class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 text-white backdrop-blur-md opacity-0 group-hover:opacity-100 transition-all duration-300 z-20 border border-white/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>

                    <!-- Indicators -->
                    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex items-center gap-3 z-20">
                        @for($i = 1; $i <= $carouselCount; $i++)
                            <button @click="activeSlide = {{ $i }}" 
                                    :class="{'w-8 bg-city-accent': activeSlide === {{ $i }}, 'w-2 bg-white/50 hover:bg-white/80': activeSlide !== {{ $i }}}"
                                    class="h-2 rounded-full transition-all duration-300"></button>
                        @endfor
                    </div>
                @endif
            </div>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-6 md:px-10 pb-20 relative mt-16">
        <!-- Secondary items right below hero in a slick row -->
        @if($heroItems->count() > 1 || $secondaryItems->isNotEmpty())
            <div class="mb-20 border-t border-gray-200 pt-8 mt-12">
                <h3 class="font-sans font-bold text-2xl uppercase tracking-widest mb-8 text-ink">The Latest</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12">
                    @foreach($heroItems->skip(isset($carouselCount) ? $carouselCount : 0)->merge($secondaryItems)->take(6) as $article)
                        <x-article-card :article="$article" variant="standard" />
                    @endforeach
                </div>
            </div>
        @endif

        <x-ad-space size="leaderboard" class="mb-16" />

        <div class="mb-16 pt-8">
            <x-trending-elsewhere :headlines="$externalHeadlines" />
        </div>

        <!-- Masonry Category Layout -->
        <div class="space-y-24">
            @foreach($categories as $category)
                @if($category->mixed_items->count() > 0)
                    <section>
                        <div class="flex items-end justify-between border-b border-gray-200 pb-4 mb-10">
                            <h2 class="font-sans font-bold text-3xl text-ink uppercase tracking-widest">
                                {{ $category->name }}
                            </h2>
                            <a href="{{ route('categories.show', $category) }}" class="font-sans font-bold text-sm text-gray-500 hover:text-ink transition-colors uppercase tracking-widest">
                                View All &rarr;
                            </a>
                        </div>
                        <!-- CSS Columns for Masonry -->
                        <div class="columns-1 md:columns-2 lg:columns-3 gap-12 space-y-12">
                            @foreach($category->mixed_items as $article)
                                <div class="break-inside-avoid">
                                    <x-article-card :article="$article" variant="standard" />
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
            @endforeach
        </div>
    </div>

    <!-- Zig Zag Local Spotlight (Dark Theme) -->
    @if($featuredBusinesses->isNotEmpty())
        <section class="bg-midnight py-24 text-white">
            <div class="max-w-7xl mx-auto px-6 md:px-10">
                <div class="text-center mb-20">
                    <h2 class="font-display font-black text-5xl md:text-7xl tracking-tighter text-city-accent mb-6 uppercase">Ujjain Directory</h2>
                    <p class="font-sans text-xl text-gray-400 font-light max-w-2xl mx-auto">Discover the finest local businesses, curated for you.</p>
                </div>

                <div class="space-y-32">
                    @foreach($featuredBusinesses as $index => $business)
                        <div class="flex flex-col {{ $index % 2 == 0 ? 'lg:flex-row' : 'lg:flex-row-reverse' }} items-center gap-12 lg:gap-24 group">
                            <!-- Image Half -->
                            <div class="w-full lg:w-1/2">
                                <a href="/directory/{{ $business->slug }}" class="block relative aspect-[4/3] rounded-sm overflow-hidden shadow-2xl">
                                    @if($business->hasMedia('cover'))
                                        <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors duration-700 z-10"></div>
                                        <img src="{{ $business->getFirstMediaUrl('cover') }}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-1000 ease-out">
                                    @else
                                        <div class="w-full h-full bg-dark-accent"></div>
                                    @endif
                                </a>
                            </div>
                            <!-- Text Half -->
                            <div class="w-full lg:w-1/2 space-y-6">
                                <span class="text-city-accent font-sans font-bold text-sm tracking-widest uppercase border-b border-city-accent pb-1">Spotlight</span>
                                <h3 class="font-display font-black text-5xl lg:text-6xl group-hover:text-city-accent transition-colors leading-none tracking-tight">
                                    <a href="/directory/{{ $business->slug }}">{{ $business->name }}</a>
                                </h3>
                                <p class="font-sans text-xl text-gray-300 font-light leading-relaxed">
                                    {{ $business->description }}
                                </p>
                                <a href="/directory/{{ $business->slug }}" class="inline-block font-sans font-bold text-white hover:text-city-accent transition-colors uppercase tracking-widest mt-8 flex items-center gap-2">
                                    Read Profile 
                                    <svg class="w-5 h-5 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-app-layout>
