@props(['leftAd' => null, 'rightAd' => null])

<!-- Left Gutter Skyscraper Ad (Wide Screens / Desktop) -->
@if($leftAd && $leftAd->is_active)
    @php
        $leftImg = $leftAd->getFirstMediaUrl('image');
    @endphp
    <aside x-data="{ open: true }" 
           x-show="open" 
           x-transition:leave="transition ease-in duration-200"
           x-transition:leave-start="opacity-100 scale-100"
           x-transition:leave-end="opacity-0 scale-95"
           class="hidden [@media(min-width:1820px)]:block fixed z-30 top-44 w-56 2xl:w-64 select-none group"
           style="right: calc(50% + 660px);"
           aria-label="Left Skyscraper Advertisement">
        
        <div class="h-[600px] 2xl:h-[660px] bg-gradient-to-b from-white via-amber-50/50 to-yellow-50/80 rounded-3xl border-2 border-amber-300/90 shadow-[0_20px_50px_rgba(0,0,0,0.15)] p-4 relative flex flex-col justify-between overflow-hidden">
            <!-- Decorative corner accent -->
            <div class="absolute -top-12 -right-12 w-28 h-28 bg-amber-200/50 rounded-full blur-xl pointer-events-none"></div>

            <!-- Header bar with dismiss button -->
            <div class="flex items-center justify-between pb-2 mb-1 border-b border-amber-200/80 text-[10px] font-sans font-black text-amber-900 uppercase tracking-widest relative z-10">
                <span class="inline-flex items-center gap-1.5 bg-amber-100 border border-amber-300 px-2.5 py-0.5 rounded-full">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span>
                    {{ $leftAd->badge_text ?: 'Ad · विज्ञापन' }}
                </span>
                <button type="button" @click="open = false" title="विज्ञापन बंद करें" class="text-gray-400 hover:text-ink hover:bg-gray-100 rounded-full w-5 h-5 flex items-center justify-center text-sm font-bold transition-colors">
                    &times;
                </button>
            </div>

            @if($leftAd->ad_code)
                <div class="ad-code-container text-xs flex-grow flex items-center justify-center">
                    {!! $leftAd->ad_code !!}
                </div>
            @else
                <!-- Body Content -->
                <div class="flex flex-col items-center text-center flex-grow justify-start py-2 relative z-10">
                    @if($leftImg)
                        <div class="w-full h-36 rounded-2xl overflow-hidden mb-3 bg-gray-100 border-2 border-amber-200 shadow-md">
                            <img src="{{ $leftImg }}" alt="{{ $leftAd->title }}" class="w-full h-full object-cover">
                        </div>
                    @elseif($leftAd->icon)
                        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-amber-400 via-yellow-400 to-amber-500 border-2 border-amber-300 flex items-center justify-center text-4xl shadow-md my-2">
                            {{ $leftAd->icon }}
                        </div>
                    @endif

                    <h4 class="font-display font-black text-lg 2xl:text-xl text-ink leading-tight tracking-tight mt-1">
                        {{ $leftAd->title }}
                    </h4>

                    @if($leftAd->subtitle)
                        <span class="text-xs font-bold text-amber-950 bg-amber-200/80 px-2.5 py-1 rounded-full border border-amber-300/80 my-2 inline-block">
                            {{ $leftAd->subtitle }}
                        </span>
                    @endif

                    @if($leftAd->description)
                        <p class="font-sans text-xs 2xl:text-sm text-gray-700 font-normal leading-relaxed my-2 line-clamp-4 px-1">
                            {{ $leftAd->description }}
                        </p>
                    @endif

                    <!-- Trust indicators / rating -->
                    <div class="my-2 py-1 px-3 bg-white/80 rounded-xl border border-amber-200/80 shadow-sm inline-flex items-center gap-1.5">
                        <span class="text-amber-500 text-xs">★★★★★</span>
                        <span class="text-[11px] font-sans font-bold text-gray-700">4.9 (850+ यात्री)</span>
                    </div>
                </div>

                <!-- Bottom CTA & Helpline -->
                <div class="pt-2 border-t border-amber-200/80 space-y-2 relative z-10">
                    @if($leftAd->phone)
                        <div class="bg-white rounded-xl p-2 border border-amber-200 text-center shadow-sm">
                            <span class="text-[9px] font-sans font-bold text-gray-400 uppercase tracking-widest block">Helpline &amp; Booking</span>
                            <a href="tel:{{ $leftAd->phone }}" class="text-sm 2xl:text-base font-black font-mono text-amber-950 hover:text-amber-700 block tracking-tight mt-0.5">
                                📞 {{ $leftAd->phone }}
                            </a>
                        </div>
                    @endif

                    @if($leftAd->cta_url && $leftAd->cta_text)
                        <a href="{{ $leftAd->cta_url }}" class="w-full text-center bg-gradient-to-r from-amber-400 via-yellow-400 to-amber-500 hover:from-amber-500 hover:to-yellow-500 text-stone-950 font-black text-xs 2xl:text-sm uppercase tracking-wider py-3.5 rounded-xl border border-yellow-300 shadow-md hover:shadow-lg transition-all hover:scale-105 font-sans block">
                            {{ $leftAd->cta_text }} &rarr;
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </aside>
@endif

<!-- Right Gutter Skyscraper Ad (Wide Screens / Desktop) -->
@if($rightAd && $rightAd->is_active)
    @php
        $rightImg = $rightAd->getFirstMediaUrl('image');
    @endphp
    <aside x-data="{ open: true }" 
           x-show="open" 
           x-transition:leave="transition ease-in duration-200"
           x-transition:leave-start="opacity-100 scale-100"
           x-transition:leave-end="opacity-0 scale-95"
           class="hidden [@media(min-width:1820px)]:block fixed z-30 top-44 w-56 2xl:w-64 select-none group"
           style="left: calc(50% + 660px);"
           aria-label="Right Skyscraper Advertisement">
        
        <div class="h-[600px] 2xl:h-[660px] bg-gradient-to-b from-white via-amber-50/50 to-orange-50/80 rounded-3xl border-2 border-amber-300/90 shadow-[0_20px_50px_rgba(0,0,0,0.15)] p-4 relative flex flex-col justify-between overflow-hidden">
            <!-- Decorative corner accent -->
            <div class="absolute -top-12 -left-12 w-28 h-28 bg-orange-200/50 rounded-full blur-xl pointer-events-none"></div>

            <!-- Header bar with dismiss button -->
            <div class="flex items-center justify-between pb-2 mb-1 border-b border-amber-200/80 text-[10px] font-sans font-black text-amber-900 uppercase tracking-widest relative z-10">
                <span class="inline-flex items-center gap-1.5 bg-amber-100 border border-amber-300 px-2.5 py-0.5 rounded-full">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span>
                    {{ $rightAd->badge_text ?: 'Ad · प्रायोजक' }}
                </span>
                <button type="button" @click="open = false" title="विज्ञापन बंद करें" class="text-gray-400 hover:text-ink hover:bg-gray-100 rounded-full w-5 h-5 flex items-center justify-center text-sm font-bold transition-colors">
                    &times;
                </button>
            </div>

            @if($rightAd->ad_code)
                <div class="ad-code-container text-xs flex-grow flex items-center justify-center">
                    {!! $rightAd->ad_code !!}
                </div>
            @else
                <!-- Body Content -->
                <div class="flex flex-col items-center text-center flex-grow justify-start py-2 relative z-10">
                    @if($rightImg)
                        <div class="w-full h-36 rounded-2xl overflow-hidden mb-3 bg-gray-100 border-2 border-amber-200 shadow-md">
                            <img src="{{ $rightImg }}" alt="{{ $rightAd->title }}" class="w-full h-full object-cover">
                        </div>
                    @elseif($rightAd->icon)
                        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-orange-400 via-amber-400 to-yellow-500 border-2 border-amber-300 flex items-center justify-center text-4xl shadow-md my-2">
                            {{ $rightAd->icon }}
                        </div>
                    @endif

                    <h4 class="font-display font-black text-lg 2xl:text-xl text-ink leading-tight tracking-tight mt-1">
                        {{ $rightAd->title }}
                    </h4>

                    @if($rightAd->subtitle)
                        <span class="text-xs font-bold text-amber-950 bg-amber-200/80 px-2.5 py-1 rounded-full border border-amber-300/80 my-2 inline-block">
                            {{ $rightAd->subtitle }}
                        </span>
                    @endif

                    @if($rightAd->description)
                        <p class="font-sans text-xs 2xl:text-sm text-gray-700 font-normal leading-relaxed my-2 line-clamp-4 px-1">
                            {{ $rightAd->description }}
                        </p>
                    @endif

                    <!-- Trust indicators / rating -->
                    <div class="my-2 py-1 px-3 bg-white/80 rounded-xl border border-amber-200/80 shadow-sm inline-flex items-center gap-1.5">
                        <span class="text-amber-500 text-xs">★★★★★</span>
                        <span class="text-[11px] font-sans font-bold text-gray-700">4.9 (1200+ यजमान)</span>
                    </div>
                </div>

                <!-- Bottom CTA & Helpline -->
                <div class="pt-2 border-t border-amber-200/80 space-y-2 relative z-10">
                    @if($rightAd->phone)
                        <div class="bg-white rounded-xl p-2 border border-amber-200 text-center shadow-sm">
                            <span class="text-[9px] font-sans font-bold text-gray-400 uppercase tracking-widest block">Helpline &amp; Booking</span>
                            <a href="tel:{{ $rightAd->phone }}" class="text-sm 2xl:text-base font-black font-mono text-amber-950 hover:text-amber-700 block tracking-tight mt-0.5">
                                📞 {{ $rightAd->phone }}
                            </a>
                        </div>
                    @endif

                    @if($rightAd->cta_url && $rightAd->cta_text)
                        <a href="{{ $rightAd->cta_url }}" class="w-full text-center bg-gradient-to-r from-amber-400 via-yellow-400 to-amber-500 hover:from-amber-500 hover:to-yellow-500 text-stone-950 font-black text-xs 2xl:text-sm uppercase tracking-wider py-3.5 rounded-xl border border-yellow-300 shadow-md hover:shadow-lg transition-all hover:scale-105 font-sans block">
                            {{ $rightAd->cta_text }} &rarr;
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </aside>
@endif
