<x-app-layout>
    <!-- Left & Right Gutter Skyscraper Advertisements (Admin Managed) -->
    @if(isset($advertisements))
        <x-side-rail-ads 
            :leftAd="$advertisements->get('left_rail')?->first()" 
            :rightAd="$advertisements->get('right_rail')?->first()" 
        />
    @endif

    <!-- Neighborhoods Strip & Breaking News Ticker (Top Hero Elements) -->
    <x-neighborhood-strip :neighborhoods="$neighborhoods" />
    @livewire('news-ticker')

    <!-- Hero Carousel Section -->
    @if($heroItems->isNotEmpty())
        @php 
            $carouselItems = $heroItems->take(5); 
            $carouselCount = $carouselItems->count();
        @endphp
        <div class="max-w-7xl mx-auto px-6 md:px-10 mt-6 mb-10">
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

    <div class="max-w-7xl mx-auto px-6 md:px-10 pb-24 space-y-16">

        <!-- ============================================== -->
        <!-- 1. ADVERTISEMENT SECTION (विज्ञापन)           -->
        <!-- ============================================== -->
        @php
            $billboardAd = isset($advertisements) ? $advertisements->get('billboard')?->first() : null;
            $leftAd = isset($advertisements) ? $advertisements->get('left_rail')?->first() : null;
            $rightAd = isset($advertisements) ? $advertisements->get('right_rail')?->first() : null;
            $sponsorCards = isset($advertisements) ? ($advertisements->get('sponsor_card') ?? collect()) : collect();
            $inFeedAd = isset($advertisements) ? $advertisements->get('in_feed')?->first() : null;
        @endphp

        <section aria-label="Advertisement" class="w-full">
            <div class="flex flex-col items-center justify-center">
                <span class="text-[10px] text-gray-400 uppercase tracking-widest font-sans font-bold mb-2 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                    Sponsored Advertisements &middot; प्रमुख प्रायोजक एवं विज्ञापन
                </span>

                <div class="w-full space-y-6">
                    <!-- Main Billboard Ad -->
                    @if($billboardAd)
                        @if($billboardAd->ad_code)
                            <div class="w-full bg-white rounded-2xl p-4 border border-gray-200 shadow-sm overflow-hidden flex justify-center">
                                {!! $billboardAd->ad_code !!}
                            </div>
                        @else
                            @php
                                $bImg = $billboardAd->getFirstMediaUrl('image');
                            @endphp
                            <div class="w-full bg-gradient-to-r from-stone-900 via-neutral-900 to-amber-950 text-white border border-amber-500/30 rounded-2xl p-6 md:p-8 shadow-xl flex flex-col lg:flex-row items-center justify-between gap-6 relative overflow-hidden group">
                                <div class="absolute -right-10 -top-10 w-48 h-48 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
                                <div class="absolute top-3 right-4">
                                    <span class="text-[9px] font-sans font-bold uppercase tracking-widest bg-amber-500/20 text-amber-300 border border-amber-500/30 px-2 py-0.5 rounded">
                                        {{ $billboardAd->badge_text ?: 'Ad · विज्ञापन' }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-5 z-10 w-full lg:w-auto">
                                    @if($bImg)
                                        <div class="w-20 h-20 md:w-24 md:h-24 rounded-2xl overflow-hidden shrink-0 border border-amber-300 shadow-lg bg-stone-800">
                                            <img src="{{ $bImg }}" alt="{{ $billboardAd->title }}" class="w-full h-full object-cover">
                                        </div>
                                    @elseif($billboardAd->icon)
                                        <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-3xl md:text-4xl shadow-lg shrink-0 border border-amber-300">
                                            {{ $billboardAd->icon }}
                                        </div>
                                    @endif

                                    <div class="space-y-1">
                                        @if($billboardAd->subtitle)
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <span class="bg-amber-400 text-stone-950 text-[10px] font-sans font-black uppercase px-2 py-0.5 rounded tracking-wider">
                                                    {{ $billboardAd->subtitle }}
                                                </span>
                                            </div>
                                        @endif
                                        <h3 class="font-display font-black text-xl md:text-2xl text-white tracking-tight">
                                            {{ $billboardAd->title }}
                                        </h3>
                                        @if($billboardAd->description)
                                            <p class="font-sans text-xs md:text-sm text-gray-300 max-w-xl font-light">
                                                {{ $billboardAd->description }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row items-center gap-3 shrink-0 z-10 w-full lg:w-auto justify-end">
                                    @if($billboardAd->phone)
                                        <div class="text-right hidden sm:block pr-2">
                                            <span class="text-[10px] text-gray-400 uppercase tracking-widest block font-sans">Helpline &amp; Booking</span>
                                            <span class="text-base font-bold text-amber-400 font-mono tracking-tight">{{ $billboardAd->phone }}</span>
                                        </div>
                                    @endif
                                    @if($billboardAd->cta_url && $billboardAd->cta_text)
                                        <a href="{{ $billboardAd->cta_url }}" class="w-full sm:w-auto text-center bg-city-accent hover:bg-yellow-300 text-ink font-black px-6 py-3 rounded-full border border-yellow-300 shadow-md transition-all text-xs tracking-wider uppercase font-sans hover:scale-105">
                                            {{ $billboardAd->cta_text }} &rarr;
                                        </a>
                                    @endif
                                    <a href="{{ route('directory.register') }}" class="w-full sm:w-auto text-center bg-white/10 hover:bg-white/20 text-white font-bold px-4 py-3 rounded-full border border-white/20 transition-all text-xs tracking-wider uppercase font-sans">
                                        Advertise Here
                                    </a>
                                </div>
                            </div>
                        @endif
                    @else
                        <!-- Default Billboard Fallback if no billboard ad active in Admin -->
                        <div class="w-full bg-gradient-to-r from-amber-50 via-yellow-50 to-orange-50 border border-amber-200/80 rounded-2xl p-6 md:p-8 shadow-sm flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden group">
                            <div class="space-y-1.5 text-center md:text-left z-10">
                                <span class="inline-flex items-center gap-2 bg-white/80 border border-amber-200 text-amber-900 px-3 py-1 rounded-full text-xs font-sans font-bold uppercase tracking-wider mb-1">
                                    Featured Partner
                                </span>
                                <h3 class="font-display font-black text-2xl md:text-3xl text-ink tracking-tight">
                                    Grow Your Business Across Ujjain
                                </h3>
                                <p class="font-sans text-sm md:text-base text-gray-600 max-w-2xl font-light">
                                    Connect your brand with over 50,000+ local readers, pilgrims, and residents daily. Manage all ad banners directly from the staff panel.
                                </p>
                            </div>
                            <div class="flex flex-col sm:flex-row items-center gap-3 shrink-0 z-10">
                                <a href="{{ route('directory.register') }}" class="w-full sm:w-auto text-center bg-city-accent hover:bg-yellow-300 text-ink font-black px-6 py-3 rounded-full border border-yellow-300 shadow-sm transition-all text-xs tracking-wider uppercase font-sans">
                                    Book Ad Space &rarr;
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Left & Right Featured Advertisements Grid (Dual Showcase) -->
                    @if($leftAd || $rightAd)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Left Advertisement -->
                            @if($leftAd)
                                @php
                                    $lImg = $leftAd->getFirstMediaUrl('image');
                                @endphp
                                <div class="bg-gradient-to-br from-white via-amber-50/40 to-yellow-50/60 rounded-3xl border-2 border-amber-300/90 shadow-md p-6 flex flex-col justify-between relative overflow-hidden group hover:shadow-xl hover:border-amber-400 transition-all">
                                    <div class="absolute -right-10 -bottom-10 w-36 h-36 bg-amber-200/40 rounded-full blur-2xl pointer-events-none"></div>

                                    <div>
                                        <div class="flex items-center justify-between mb-4">
                                            <span class="inline-flex items-center gap-1.5 bg-amber-100 border border-amber-300 text-amber-950 px-3 py-1 rounded-full text-[10px] font-sans font-black tracking-widest uppercase">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span>
                                                {{ $leftAd->badge_text ?: 'Sponsored · विज्ञापन' }}
                                            </span>
                                            <span class="text-xs text-amber-700 font-sans font-bold">★★★★★ 4.9</span>
                                        </div>

                                        <div class="flex items-start gap-4 mb-4">
                                            @if($lImg)
                                                <div class="w-20 h-20 rounded-2xl overflow-hidden shrink-0 border-2 border-amber-200 shadow-md bg-white">
                                                    <img src="{{ $lImg }}" alt="{{ $leftAd->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                                </div>
                                            @elseif($leftAd->icon)
                                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-amber-400 to-yellow-400 border-2 border-amber-300 text-3xl flex items-center justify-center shrink-0 shadow-md">
                                                    {{ $leftAd->icon }}
                                                </div>
                                            @endif
                                            <div>
                                                @if($leftAd->subtitle)
                                                    <span class="text-xs font-bold text-amber-900 block mb-0.5">
                                                        {{ $leftAd->subtitle }}
                                                    </span>
                                                @endif
                                                <h3 class="font-display font-black text-xl text-ink leading-tight">
                                                    {{ $leftAd->title }}
                                                </h3>
                                            </div>
                                        </div>

                                        @if($leftAd->description)
                                            <p class="text-xs md:text-sm text-gray-700 font-normal leading-relaxed mb-4">
                                                {{ $leftAd->description }}
                                            </p>
                                        @endif
                                    </div>

                                    <div class="pt-4 border-t border-amber-200/80 flex flex-col sm:flex-row items-center justify-between gap-3">
                                        @if($leftAd->phone)
                                            <div>
                                                <span class="text-[9px] font-sans font-bold text-gray-400 uppercase tracking-widest block">Helpline</span>
                                                <a href="tel:{{ $leftAd->phone }}" class="text-sm font-black font-mono text-ink hover:text-amber-700 block">
                                                    📞 {{ $leftAd->phone }}
                                                </a>
                                            </div>
                                        @endif
                                        @if($leftAd->cta_url && $leftAd->cta_text)
                                            <a href="{{ $leftAd->cta_url }}" class="w-full sm:w-auto text-center bg-gradient-to-r from-amber-400 via-yellow-400 to-amber-500 hover:from-amber-500 hover:to-yellow-500 text-stone-950 font-black text-xs uppercase tracking-wider px-6 py-3 rounded-xl border border-yellow-300 shadow-md hover:scale-105 transition-all">
                                                {{ $leftAd->cta_text }} &rarr;
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Right Advertisement -->
                            @if($rightAd)
                                @php
                                    $rImg = $rightAd->getFirstMediaUrl('image');
                                @endphp
                                <div class="bg-gradient-to-br from-white via-orange-50/40 to-amber-50/60 rounded-3xl border-2 border-amber-300/90 shadow-md p-6 flex flex-col justify-between relative overflow-hidden group hover:shadow-xl hover:border-amber-400 transition-all">
                                    <div class="absolute -right-10 -bottom-10 w-36 h-36 bg-orange-200/40 rounded-full blur-2xl pointer-events-none"></div>

                                    <div>
                                        <div class="flex items-center justify-between mb-4">
                                            <span class="inline-flex items-center gap-1.5 bg-amber-100 border border-amber-300 text-amber-950 px-3 py-1 rounded-full text-[10px] font-sans font-black tracking-widest uppercase">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span>
                                                {{ $rightAd->badge_text ?: 'Sponsored · प्रायोजक' }}
                                            </span>
                                            <span class="text-xs text-amber-700 font-sans font-bold">★★★★★ 4.9</span>
                                        </div>

                                        <div class="flex items-start gap-4 mb-4">
                                            @if($rImg)
                                                <div class="w-20 h-20 rounded-2xl overflow-hidden shrink-0 border-2 border-amber-200 shadow-md bg-white">
                                                    <img src="{{ $rImg }}" alt="{{ $rightAd->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                                </div>
                                            @elseif($rightAd->icon)
                                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-orange-400 to-amber-500 border-2 border-amber-300 text-3xl flex items-center justify-center shrink-0 shadow-md">
                                                    {{ $rightAd->icon }}
                                                </div>
                                            @endif
                                            <div>
                                                @if($rightAd->subtitle)
                                                    <span class="text-xs font-bold text-amber-900 block mb-0.5">
                                                        {{ $rightAd->subtitle }}
                                                    </span>
                                                @endif
                                                <h3 class="font-display font-black text-xl text-ink leading-tight">
                                                    {{ $rightAd->title }}
                                                </h3>
                                            </div>
                                        </div>

                                        @if($rightAd->description)
                                            <p class="text-xs md:text-sm text-gray-700 font-normal leading-relaxed mb-4">
                                                {{ $rightAd->description }}
                                            </p>
                                        @endif
                                    </div>

                                    <div class="pt-4 border-t border-amber-200/80 flex flex-col sm:flex-row items-center justify-between gap-3">
                                        @if($rightAd->phone)
                                            <div>
                                                <span class="text-[9px] font-sans font-bold text-gray-400 uppercase tracking-widest block">Helpline</span>
                                                <a href="tel:{{ $rightAd->phone }}" class="text-sm font-black font-mono text-ink hover:text-amber-700 block">
                                                    📞 {{ $rightAd->phone }}
                                                </a>
                                            </div>
                                        @endif
                                        @if($rightAd->cta_url && $rightAd->cta_text)
                                            <a href="{{ $rightAd->cta_url }}" class="w-full sm:w-auto text-center bg-gradient-to-r from-amber-400 via-yellow-400 to-amber-500 hover:from-amber-500 hover:to-yellow-500 text-stone-950 font-black text-xs uppercase tracking-wider px-6 py-3 rounded-xl border border-yellow-300 shadow-md hover:scale-105 transition-all">
                                                {{ $rightAd->cta_text }} &rarr;
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Sponsor Cards (Admin Managed Grid) -->
                    @if($sponsorCards->isNotEmpty())
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-{{ min($sponsorCards->count(), 2) }} gap-4">
                            @foreach($sponsorCards as $sc)
                                @php
                                    $scImg = $sc->getFirstMediaUrl('image');
                                @endphp
                                @if($sc->ad_code)
                                    <div class="bg-white border border-gray-200 rounded-xl p-3 shadow-sm flex items-center justify-center overflow-hidden">
                                        {!! $sc->ad_code !!}
                                    </div>
                                @else
                                    <div class="bg-gradient-to-r from-amber-50/80 to-orange-50/80 border border-amber-200/90 rounded-xl p-4 md:p-5 flex items-center justify-between gap-4 shadow-sm relative group hover:border-amber-300 transition-colors">
                                        <div class="flex items-center gap-3.5">
                                            @if($scImg)
                                                <div class="w-14 h-14 rounded-xl overflow-hidden shrink-0 border border-amber-200 shadow-sm bg-white">
                                                    <img src="{{ $scImg }}" alt="{{ $sc->title }}" class="w-full h-full object-cover">
                                                </div>
                                            @elseif($sc->icon)
                                                <div class="w-12 h-12 rounded-xl bg-amber-100 border border-amber-300 text-2xl flex items-center justify-center shrink-0 shadow-sm">
                                                    {{ $sc->icon }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="flex items-center gap-2 mb-0.5">
                                                    <span class="text-[9px] font-sans font-bold uppercase tracking-wider text-amber-800 bg-amber-200/60 px-1.5 py-0.5 rounded">
                                                        {{ $sc->badge_text ?: 'Sponsored · विज्ञापन' }}
                                                    </span>
                                                    @if($sc->subtitle)
                                                        <span class="text-[10px] text-gray-500 font-sans font-medium">{{ $sc->subtitle }}</span>
                                                    @endif
                                                </div>
                                                <h4 class="font-display font-bold text-base text-ink leading-tight">
                                                    {{ $sc->title }}
                                                </h4>
                                                @if($sc->description)
                                                    <p class="text-xs text-gray-600 font-sans mt-0.5 line-clamp-2">
                                                        {{ $sc->description }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                        @if($sc->cta_url && $sc->cta_text)
                                            <a href="{{ $sc->cta_url }}" class="shrink-0 bg-white hover:bg-amber-100 text-amber-900 border border-amber-300 text-xs font-sans font-bold px-3.5 py-2 rounded-lg transition-colors shadow-sm">
                                                {{ $sc->cta_text }}
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <!-- ============================================== -->
        <!-- 2. BREAKING NEWS (Not all News - Curated 4)   -->
        <!-- ============================================== -->
        @if($breakingNews->isNotEmpty())
            <section aria-label="Breaking News">
                <div class="flex items-center justify-between border-b-2 border-red-600/20 pb-3 mb-8">
                    <div class="flex items-center gap-3">
                        <span class="relative flex h-3.5 w-3.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-red-600"></span>
                        </span>
                        <h2 class="font-display font-black text-2xl md:text-3xl text-ink uppercase tracking-tight flex items-center gap-2">
                            Breaking News
                            <span class="hidden sm:inline-block text-[11px] font-sans font-bold bg-red-600 text-white px-2.5 py-0.5 rounded-full tracking-wider uppercase">Live</span>
                        </h2>
                    </div>
                    <a href="{{ url('/trending') }}" class="font-sans font-bold text-xs text-red-600 hover:text-red-700 transition-colors uppercase tracking-widest flex items-center gap-1">
                        View All Trending &rarr;
                    </a>
                </div>

                @php
                    $leadStory = $breakingNews->first();
                    $sideStories = $breakingNews->skip(1)->take(3);
                @endphp

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <!-- Main Lead Breaking Story (Left 7 Cols) -->
                    @if($leadStory)
                        <div class="lg:col-span-7">
                            <x-article-card :article="$leadStory" variant="standard" />
                        </div>
                    @endif

                    <!-- Side Breaking Stories (Right 5 Cols) -->
                    <div class="lg:col-span-5 flex flex-col justify-between divide-y divide-gray-100 bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                        <div class="space-y-6">
                            @foreach($sideStories as $story)
                                @php
                                    $isExt = $story instanceof \App\Models\ExternalHeadline;
                                    $storyImg = $isExt ? $story->thumbnail_url : ($story->getFirstMediaUrl('featured_image') ?: $story->getFirstMediaUrl('cover'));
                                    $storyCat = $story->categories->first()?->name ?? ($isExt ? $story->newsSource?->name : 'Breaking');
                                    $storyUrl = $isExt ? route('external.show', $story) : route('articles.show', $story);
                                    $storyDate = ($story->published_at ?? $story->created_at)?->format('M j, Y');
                                @endphp
                                <article class="flex items-start gap-4 group pt-4 first:pt-0">
                                    @if($storyImg)
                                        <a href="{{ $storyUrl }}" class="w-24 h-24 rounded-xl overflow-hidden shrink-0 bg-gray-100 block">
                                            <img src="{{ $storyImg }}" alt="{{ $story->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        </a>
                                    @endif
                                    <div class="flex-grow">
                                        <span class="text-amber-800 font-sans font-bold text-[10px] tracking-wider uppercase block mb-1">
                                            {{ str_replace('उज्जैन>', '', $storyCat) }}
                                        </span>
                                        <h3 class="font-sans font-bold text-base text-ink group-hover:text-amber-700 transition-colors line-clamp-2 leading-snug">
                                            <a href="{{ $storyUrl }}">{{ $story->title }}</a>
                                        </h3>
                                        <span class="text-[11px] font-sans font-medium text-gray-400 mt-2 block uppercase tracking-wide">
                                            {{ $storyDate }}
                                        </span>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <div class="pt-6 mt-6 border-t border-gray-100">
                            <a href="{{ route('blog') }}" class="w-full py-2.5 text-center block bg-gray-50 hover:bg-gray-100 text-ink font-sans font-bold text-xs uppercase tracking-wider rounded-xl transition-colors">
                                Browse All News Stories &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <!-- ============================================== -->
        <!-- 3. CATEGORY 1 (Curated Featured Category)     -->
        <!-- ============================================== -->
        @if($category1 && $category1->feedItems->isNotEmpty())
            @php
                $cat1Name = str_replace('उज्जैन>', '', $category1->name);
            @endphp
            <section aria-label="{{ $cat1Name }}">
                <div class="flex items-end justify-between border-b border-gray-200 pb-4 mb-8">
                    <div>
                        <span class="text-amber-800 font-sans font-bold text-xs tracking-widest uppercase mb-1 inline-flex items-center gap-1.5 bg-amber-50 px-3 py-0.5 rounded-full border border-amber-200">
                            Featured Category
                        </span>
                        <h2 class="font-display font-black text-3xl md:text-4xl text-ink uppercase tracking-tight mt-2">
                            {{ $cat1Name }}
                        </h2>
                    </div>
                    <a href="{{ route('categories.show', $category1) }}" class="font-sans font-bold text-xs text-gray-600 hover:text-ink transition-colors uppercase tracking-widest">
                        View All in {{ $cat1Name }} &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($category1->feedItems->take(4) as $article)
                        <x-article-card :article="$article" variant="standard" />
                    @endforeach
                </div>
            </section>
        @endif

        <!-- In-Feed Leaderboard Sponsor Ad (Admin Managed) -->
        @if($inFeedAd)
            @if($inFeedAd->ad_code)
                <div class="w-full bg-white border border-gray-200 rounded-2xl p-4 shadow-sm flex items-center justify-center overflow-hidden">
                    {!! $inFeedAd->ad_code !!}
                </div>
            @else
                @php
                    $inFeedImg = $inFeedAd->getFirstMediaUrl('image');
                @endphp
                <div class="w-full bg-gradient-to-r from-amber-50 via-yellow-50/70 to-orange-50/60 border-2 border-amber-300/90 rounded-3xl p-6 md:p-8 shadow-md flex flex-col lg:flex-row items-center justify-between gap-6 relative overflow-hidden group">
                    <div class="absolute -right-12 -bottom-12 w-48 h-48 bg-yellow-200/50 rounded-full blur-2xl pointer-events-none"></div>

                    <div class="flex items-center gap-5 z-10 w-full lg:w-auto">
                        @if($inFeedImg)
                            <div class="w-20 h-20 md:w-24 md:h-24 rounded-2xl overflow-hidden shrink-0 border-2 border-amber-300 shadow-md bg-white">
                                <img src="{{ $inFeedImg }}" alt="{{ $inFeedAd->title }}" class="w-full h-full object-cover">
                            </div>
                        @elseif($inFeedAd->icon)
                            <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl bg-gradient-to-br from-amber-400 to-amber-600 text-3xl md:text-4xl flex items-center justify-center shrink-0 shadow-md border-2 border-amber-300">
                                {{ $inFeedAd->icon }}
                            </div>
                        @endif
                        <div class="space-y-1">
                            <div class="flex items-center gap-2 mb-1 flex-wrap">
                                <span class="text-[10px] font-sans font-black uppercase tracking-widest text-amber-950 bg-amber-200 px-3 py-0.5 rounded-full border border-amber-300">
                                    {{ $inFeedAd->badge_text ?: 'Special Sponsor · प्रायोजक' }}
                                </span>
                                @if($inFeedAd->subtitle)
                                    <span class="text-xs text-amber-800 font-sans font-bold bg-white/80 px-2.5 py-0.5 rounded-full border border-amber-200">{{ $inFeedAd->subtitle }}</span>
                                @endif
                            </div>
                            <h3 class="font-display font-black text-xl md:text-2xl text-ink leading-tight">
                                {{ $inFeedAd->title }}
                            </h3>
                            @if($inFeedAd->description)
                                <p class="text-xs md:text-sm text-gray-700 font-normal leading-relaxed max-w-2xl">
                                    {{ $inFeedAd->description }}
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center gap-3 shrink-0 z-10 w-full lg:w-auto justify-end">
                        @if($inFeedAd->phone)
                            <div class="text-right hidden sm:block pr-2">
                                <span class="text-[10px] text-gray-400 uppercase tracking-widest block font-sans">Helpline</span>
                                <span class="text-sm font-bold text-ink font-mono tracking-tight">{{ $inFeedAd->phone }}</span>
                            </div>
                        @endif
                        @if($inFeedAd->cta_url && $inFeedAd->cta_text)
                            <a href="{{ $inFeedAd->cta_url }}" class="w-full sm:w-auto text-center bg-gradient-to-r from-amber-600 to-amber-700 hover:from-amber-700 hover:to-amber-800 text-white font-sans font-black text-xs md:text-sm uppercase tracking-wider px-7 py-3.5 rounded-full transition-all shadow-md hover:shadow-lg hover:scale-105">
                                {{ $inFeedAd->cta_text }} &rarr;
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        @endif

        <!-- ============================================== -->
        <!-- 4. CATEGORY 2 (Curated Featured Category)     -->
        <!-- ============================================== -->
        @if($category2 && $category2->feedItems->isNotEmpty())
            @php
                $cat2Name = str_replace('उज्जैन>', '', $category2->name);
            @endphp
            <section aria-label="{{ $cat2Name }}">
                <div class="flex items-end justify-between border-b border-gray-200 pb-4 mb-8">
                    <div>
                        <span class="text-amber-800 font-sans font-bold text-xs tracking-widest uppercase mb-1 inline-flex items-center gap-1.5 bg-amber-50 px-3 py-0.5 rounded-full border border-amber-200">
                            Spotlight
                        </span>
                        <h2 class="font-display font-black text-3xl md:text-4xl text-ink uppercase tracking-tight mt-2">
                            {{ $cat2Name }}
                        </h2>
                    </div>
                    <a href="{{ route('categories.show', $category2) }}" class="font-sans font-bold text-xs text-gray-600 hover:text-ink transition-colors uppercase tracking-widest">
                        View All in {{ $cat2Name }} &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($category2->feedItems->take(4) as $article)
                        <x-article-card :article="$article" variant="standard" />
                    @endforeach
                </div>
            </section>
        @endif

        <!-- ============================================== -->
        <!-- 5. CATEGORY 3 (Curated Featured Category)     -->
        <!-- ============================================== -->
        @if($category3 && $category3->feedItems->isNotEmpty())
            @php
                $cat3Name = str_replace('उज्जैन>', '', $category3->name);
            @endphp
            <section aria-label="{{ $cat3Name }}">
                <div class="flex items-end justify-between border-b border-gray-200 pb-4 mb-8">
                    <div>
                        <span class="text-amber-800 font-sans font-bold text-xs tracking-widest uppercase mb-1 inline-flex items-center gap-1.5 bg-amber-50 px-3 py-0.5 rounded-full border border-amber-200">
                            Culture &amp; Life
                        </span>
                        <h2 class="font-display font-black text-3xl md:text-4xl text-ink uppercase tracking-tight mt-2">
                            {{ $cat3Name }}
                        </h2>
                    </div>
                    <a href="{{ route('categories.show', $category3) }}" class="font-sans font-bold text-xs text-gray-600 hover:text-ink transition-colors uppercase tracking-widest">
                        View All in {{ $cat3Name }} &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($category3->feedItems->take(4) as $article)
                        <x-article-card :article="$article" variant="standard" />
                    @endforeach
                </div>
            </section>
        @endif

        <!-- ============================================== -->
        <!-- LIVE AGGREGATED UJJAIN HEADLINES (दैनिक भास्कर & लाइव हिन्दुस्तान) -->
        <!-- ============================================== -->
        @if(isset($latestHeadlines) && $latestHeadlines->isNotEmpty())
            <section aria-label="Latest Live Headlines" class="bg-gradient-to-br from-amber-50/60 via-white to-orange-50/50 rounded-3xl p-6 md:p-8 border border-amber-200/90 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 mb-6 border-b border-amber-200/70 gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="relative flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-600"></span>
                            </span>
                            <span class="text-[10px] font-sans font-black uppercase tracking-widest text-amber-900 bg-amber-100 px-2.5 py-0.5 rounded-full border border-amber-300">
                                Live Aggregated Updates &middot; ताज़ा सुर्खियां
                            </span>
                        </div>
                        <h2 class="font-display font-black text-2xl md:text-3xl text-ink uppercase tracking-tight">
                            Latest Ujjain Headlines
                        </h2>
                    </div>
                    <a href="{{ route('external.index') }}" class="inline-flex items-center gap-1 font-sans font-bold text-xs text-amber-900 hover:text-amber-700 bg-amber-200/70 hover:bg-amber-200 px-4 py-2 rounded-xl transition-all border border-amber-300 shadow-sm self-start sm:self-auto">
                        View All Trending News &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($latestHeadlines as $headline)
                        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
                            <div>
                                @if($headline->thumbnail_url)
                                    <div class="h-44 -mx-5 -mt-5 mb-4 overflow-hidden rounded-t-2xl bg-gray-100">
                                        <img src="{{ $headline->thumbnail_url }}" alt="{{ $headline->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    </div>
                                @endif
                                <div class="flex items-center gap-2 mb-2 flex-wrap">
                                    <span class="text-[10px] font-sans font-black text-blue-900 bg-blue-50 px-2 py-0.5 rounded border border-blue-200 uppercase tracking-wider">
                                        {{ $headline->newsSource?->name ?: 'News' }}
                                    </span>
                                    @if($headline->categories->isNotEmpty())
                                        @foreach($headline->categories->take(2) as $hCat)
                                            <span class="text-[10px] font-sans font-bold text-amber-900 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                                                {{ $hCat->name }}
                                            </span>
                                        @endforeach
                                    @endif
                                </div>
                                <h3 class="font-display font-bold text-base text-ink group-hover:text-amber-800 transition-colors line-clamp-2 leading-snug mb-2">
                                    <a href="{{ route('external.show', $headline) }}">{{ $headline->title }}</a>
                                </h3>
                                @if($headline->original_snippet)
                                    <p class="text-xs text-gray-600 line-clamp-2 font-light leading-relaxed mb-4">
                                        {{ $headline->original_snippet }}
                                    </p>
                                @endif
                            </div>
                            <div class="flex items-center justify-between pt-3 border-t border-gray-100 text-[11px] font-sans text-gray-400">
                                <span>{{ ($headline->published_at ?? $headline->fetched_at)?->diffForHumans() }}</span>
                                <a href="{{ route('external.show', $headline) }}" class="font-bold text-amber-800 hover:text-amber-900">
                                    Read Story &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- ============================================== -->
        <!-- 6. LOCAL BUSINESS DIRECTORY SHOWCASE         -->
        <!-- ============================================== -->
        <section aria-label="Business Directory" class="bg-gradient-to-br from-amber-50 via-yellow-100/50 to-amber-100/60 rounded-3xl p-8 md:p-12 border-2 border-yellow-300/80 shadow-lg relative overflow-hidden">
            <!-- Decorative Background Watermark -->
            <div class="absolute -right-12 -bottom-12 opacity-10 pointer-events-none select-none font-display font-black text-9xl text-amber-900 uppercase">
                Directory
            </div>

            <div class="max-w-4xl mb-10 relative z-10">
                <span class="inline-flex items-center gap-2 bg-city-accent border border-yellow-400 text-ink px-4 py-1.5 rounded-full text-xs font-sans font-black tracking-widest uppercase mb-3 shadow-sm">
                    <span>★</span> Ujjain Business Directory &middot; स्थानीय डायरेक्टरी
                </span>
                <h2 class="font-display font-black text-3xl md:text-5xl text-ink tracking-tight uppercase leading-none mb-3">
                    Discover Local Businesses &amp; Services
                </h2>
                <p class="font-sans text-base md:text-lg text-gray-700 font-light max-w-2xl">
                    Your complete verified guide to famous sweet shops, temples, pandits, hotels, dharamshalas, doctors, transport, and commercial services across Ujjain.
                </p>
            </div>

            <!-- Quick Category Tags -->
            <div class="flex flex-wrap gap-2 mb-10 relative z-10">
                <a href="{{ route('directory') }}" class="px-4 py-2 rounded-full bg-white hover:bg-city-accent text-ink border border-yellow-300 font-sans text-xs font-bold transition-all shadow-sm">
                    🛕 Temples &amp; Pandits
                </a>
                <a href="{{ route('directory') }}" class="px-4 py-2 rounded-full bg-white hover:bg-city-accent text-ink border border-yellow-300 font-sans text-xs font-bold transition-all shadow-sm">
                    🏨 Hotels &amp; Dharamshala
                </a>
                <a href="{{ route('directory') }}" class="px-4 py-2 rounded-full bg-white hover:bg-city-accent text-ink border border-yellow-300 font-sans text-xs font-bold transition-all shadow-sm">
                    🍽️ Sweets &amp; Restaurants
                </a>
                <a href="{{ route('directory') }}" class="px-4 py-2 rounded-full bg-white hover:bg-city-accent text-ink border border-yellow-300 font-sans text-xs font-bold transition-all shadow-sm">
                    🏥 Hospitals &amp; Clinics
                </a>
                <a href="{{ route('directory') }}" class="px-4 py-2 rounded-full bg-white hover:bg-city-accent text-ink border border-yellow-300 font-sans text-xs font-bold transition-all shadow-sm">
                    🚗 Cabs &amp; Transport
                </a>
            </div>

            <!-- Featured Businesses Grid -->
            @if($featuredBusinesses->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12 relative z-10">
                    @foreach($featuredBusinesses as $business)
                        @php
                            $coverUrl = $business->getFirstMediaUrl('cover');
                            $catName = $business->categories->first()?->name ?? 'Local Business';
                        @endphp
                        <div class="bg-white rounded-2xl p-5 border border-yellow-200/80 shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
                            <div>
                                @if($coverUrl)
                                    <div class="w-full h-32 rounded-xl overflow-hidden mb-3 bg-gray-100">
                                        <img src="{{ $coverUrl }}" alt="{{ $business->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    </div>
                                @endif
                                <span class="text-[10px] font-sans font-bold text-amber-800 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-full uppercase tracking-wider mb-2 inline-block">
                                    {{ $catName }}
                                </span>
                                <h3 class="font-display font-bold text-lg text-ink group-hover:text-amber-800 transition-colors line-clamp-1 leading-tight mb-1">
                                    {{ $business->name }}
                                </h3>
                                <p class="font-sans text-xs text-gray-500 line-clamp-2 font-light mb-3">
                                    {{ $business->address }}
                                </p>
                            </div>

                            <a href="{{ route('directory.show', $business) }}" class="inline-flex items-center justify-between text-xs font-sans font-bold text-ink hover:text-amber-800 pt-3 border-t border-gray-100 transition-colors">
                                <span>View Details</span>
                                <span>&rarr;</span>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Direct Links / CTAs -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-6 pt-6 border-t-2 border-yellow-300/80 relative z-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-city-accent flex items-center justify-center font-bold text-ink text-lg border border-yellow-400">
                        📍
                    </div>
                    <div>
                        <h4 class="font-sans font-bold text-sm text-ink">Own a business or service in Ujjain?</h4>
                        <p class="font-sans text-xs text-gray-600">List your shop, clinic, or service in the verified Ujjain city directory.</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('directory') }}" class="w-full sm:w-auto text-center bg-ink hover:bg-gray-800 text-white font-black px-6 py-3 rounded-full shadow-sm transition-all text-xs tracking-wider uppercase font-sans">
                        Browse All Businesses &rarr;
                    </a>
                    <a href="{{ route('directory.register') }}" class="w-full sm:w-auto text-center bg-city-accent hover:bg-yellow-300 text-ink font-black px-6 py-3 rounded-full border border-yellow-400 shadow-sm transition-all text-xs tracking-wider uppercase font-sans hover:shadow">
                        + List Business Free
                    </a>
                </div>
            </div>
        </section>

    </div>
</x-app-layout>
