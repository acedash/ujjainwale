@php
    $isExternal = $article instanceof \App\Models\ExternalHeadline;
    $title = $article->title;
    $url = $isExternal ? route('external.show', $article) : route('articles.show', $article);
    $target = '';

    $imageUrl = $isExternal ? $article->thumbnail_url : ($article->getFirstMediaUrl('featured_image') ?: ($article->getFirstMediaUrl('cover') ?: $article->getFirstMediaUrl('media')));

    $categoryName = $article->categories->first()?->name;
    $date = $isExternal
        ? ($article->published_at ?? $article->fetched_at)?->format('M j, Y')
        : ($article->published_at?->format('M j, Y') ?? 'Draft');
    $excerpt = $isExternal ? $article->original_snippet : Str::limit(strip_tags($article->body), 150);
    $authorOrSource = $isExternal ? ($article->newsSource->name ?? 'News') : ($article->author->name ?? 'Editorial');

    $containerClass = ''; 
@endphp

@if($variant === 'hero-split')
    <article class="flex flex-col md:flex-row w-full h-full group {{ $isExternal ? 'opacity-95' : '' }}">
        <!-- Left / Bottom Side: Text Content -->
        <div class="w-full md:w-1/2 bg-gradient-to-b md:bg-gradient-to-r from-slate-950 via-slate-900 to-slate-950 flex flex-col justify-between p-6 sm:p-8 md:p-10 lg:p-14 text-white order-2 md:order-1 relative z-10 overflow-hidden">
            <div class="space-y-3 sm:space-y-4">
                <!-- Badges & Category -->
                <div class="flex items-center gap-2 flex-wrap">
                    @if($isExternal)
                        <span class="inline-flex items-center gap-1.5 bg-blue-500/20 text-blue-300 border border-blue-400/30 px-3 py-1 rounded-full text-[10px] font-sans font-bold tracking-widest uppercase">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
                            Live &middot; {{ $authorOrSource }}
                        </span>
                        @if($categoryName)
                            <span class="inline-flex items-center bg-amber-400/20 text-amber-300 border border-amber-400/30 px-2.5 py-0.5 rounded-full text-[10px] font-sans font-bold tracking-wider">
                                {{ $categoryName }}
                            </span>
                        @endif
                    @else
                        <span class="inline-flex items-center gap-1.5 bg-amber-500/20 text-amber-300 border border-amber-400/30 px-3 py-1 rounded-full text-[10px] font-sans font-bold tracking-widest uppercase">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                            Editorial Blog &middot; सम्पादकीय
                        </span>
                        @if($categoryName)
                            <span class="text-white/80 text-[10px] font-sans font-bold uppercase tracking-wider bg-white/10 px-2.5 py-0.5 rounded-full border border-white/10">
                                {{ str_replace('उज्जैन>', '', $categoryName) }}
                            </span>
                        @endif
                    @endif
                </div>

                <!-- Title -->
                <h2 class="font-display font-black text-xl sm:text-2xl md:text-3xl lg:text-4xl text-white leading-tight sm:leading-snug tracking-tight group-hover:text-amber-400 transition-colors duration-300 line-clamp-3">
                    <a href="{{ $url }}" {!! $target !!} class="block">
                        {{ $title }}
                    </a>
                </h2>

                <!-- Excerpt -->
                <p class="font-sans text-xs sm:text-sm md:text-base text-gray-300 line-clamp-2 sm:line-clamp-3 leading-relaxed font-light">
                    {{ $excerpt }}
                </p>
            </div>

            <!-- Footer: Source/Date + Read Button -->
            <div class="flex items-center justify-between pt-4 mt-2 border-t border-white/10">
                <div class="flex items-center gap-2 font-sans text-[11px] font-medium text-gray-400">
                    <span class="text-amber-400 font-bold">{{ $authorOrSource }}</span>
                    <span>&bull;</span>
                    <span>{{ $date }}</span>
                </div>
                
                <a href="{{ $url }}" {!! $target !!} class="inline-flex items-center gap-1.5 font-sans font-bold text-xs uppercase tracking-wider text-amber-400 hover:text-amber-300 transition-colors">
                    <span>{{ $isExternal ? 'Read News' : 'Read Article' }}</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
        </div>

        <!-- Right / Top Side: Image with No-Referrer and Fallback -->
        <div class="w-full md:w-1/2 relative bg-slate-900 order-1 md:order-2 h-56 sm:h-64 md:h-full overflow-hidden shrink-0" style="background-color: #0f172a;">
            @if($imageUrl)
                <img src="{{ $imageUrl }}" 
                     alt="{{ $title }}"
                     referrerpolicy="no-referrer"
                     loading="eager"
                     onerror="this.onerror=null; this.src='{{ asset('images/ujjain-wale.jpeg') }}';"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
            @else
                <img src="{{ asset('images/ujjain-wale.jpeg') }}" 
                     alt="{{ $title }}"
                     class="w-full h-full object-cover opacity-80">
            @endif
            <!-- Gradient overlays to guarantee text & edge readability -->
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent md:hidden opacity-90 pointer-events-none"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-transparent to-transparent hidden md:block opacity-60 pointer-events-none"></div>
        </div>
    </article>

@elseif($variant === 'hero')
    {{-- Fallback for old hero component just in case it's used elsewhere --}}
    <article class="relative w-full h-[60vh] min-h-[500px] max-h-[800px] rounded-sm overflow-hidden group mb-12">
        @if($imageUrl)
            <div class="absolute inset-0 bg-black">
                <img src="{{ $imageUrl }}" alt="{{ $title }}" referrerpolicy="no-referrer" class="w-full h-full object-cover opacity-80 group-hover:scale-105 transition-transform duration-1000 ease-out">
            </div>
        @else
            <div class="absolute inset-0 bg-midnight"></div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-midnight via-midnight/60 to-transparent opacity-90"></div>
        <div class="absolute inset-0 flex flex-col justify-end p-8 md:p-12 text-white z-10">
            @if($categoryName)
                <span class="text-city-accent font-sans font-bold text-sm tracking-widest uppercase mb-4 block">{{ $categoryName }}</span>
            @endif
            <h2 class="font-display font-black text-4xl lg:text-5xl text-white leading-tight mb-6 line-clamp-3 group-hover:text-city-accent transition-colors">
                <a href="{{ $url }}" {!! $target !!} class="block">{{ $title }}</a>
            </h2>
            <p class="font-sans text-lg text-gray-200 mb-6 line-clamp-2 font-light">{{ $excerpt }}</p>
        </div>
    </article>

@elseif($variant === 'standard')
    <!-- BORDERLESS EDITORIAL CARD -->
    <article class="flex flex-col gap-4 group {{ $containerClass }}">
        <a href="{{ $url }}" {!! $target !!} class="block aspect-[3/2] bg-gray-100 overflow-hidden mb-2 relative flex items-center justify-center rounded-xl">
            @if($imageUrl)
                <img src="{{ $imageUrl }}" 
                     alt="{{ $title }}" 
                     referrerpolicy="no-referrer"
                     loading="lazy"
                     onerror="this.onerror=null; this.src='{{ asset('images/ujjain-wale.jpeg') }}';"
                     class="w-full h-full object-cover opacity-95 group-hover:scale-105 transition-transform duration-700 ease-out">
            @elseif($isExternal)
                <div class="absolute inset-0 bg-gray-200"></div>
                <span class="font-display font-black text-gray-400 text-3xl relative z-10">{{ $authorOrSource }}</span>
            @endif
            <div class="absolute inset-0 bg-black/5 group-hover:bg-transparent transition-colors duration-500"></div>
        </a>
        
        <div>
            @if($isExternal)
                <span class="inline-flex items-center gap-1 text-gray-500 text-[10px] font-sans font-bold tracking-widest uppercase mb-3">
                    Aggregated &middot; {{ $authorOrSource }}
                </span>
            @elseif($categoryName)
                <span class="text-gray-800 font-sans font-bold text-[10px] tracking-widest uppercase mb-3 block border-b border-gray-200 pb-1 inline-block">{{ $categoryName }}</span>
            @endif
            
            <h3 class="font-sans font-bold text-xl lg:text-2xl text-ink leading-tight mb-3 tracking-tight">
                <a href="{{ $url }}" {!! $target !!} class="hover:text-amber-800 transition-colors">{{ $title }}</a>
            </h3>
            
            <p class="font-sans text-sm text-gray-600 line-clamp-3 mb-4 font-light leading-relaxed">{{ $excerpt }}</p>
            
            <div class="font-sans text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                {{ $date }}
            </div>
        </div>
    </article>

@elseif($variant === 'compact')
    <article class="flex gap-6 group {{ $containerClass }}">
        <a href="{{ $url }}" {!! $target !!} class="w-1/3 aspect-[4/3] bg-gray-100 overflow-hidden flex-shrink-0 relative">
            @if($imageUrl)
                <img src="{{ $imageUrl }}" 
                     alt="{{ $title }}" 
                     referrerpolicy="no-referrer"
                     loading="lazy"
                     onerror="this.onerror=null; this.src='{{ asset('images/ujjain-wale.jpeg') }}';"
                     class="w-full h-full object-cover opacity-95 group-hover:scale-105 transition-transform duration-700 ease-out">
            @endif
            <div class="absolute inset-0 bg-black/5 group-hover:bg-transparent transition-colors duration-500"></div>
        </a>
        <div class="w-2/3 flex flex-col justify-center">
            @if($categoryName)
                <span class="text-city-accent font-sans font-bold text-[10px] tracking-widest uppercase mb-1">{{ $categoryName }}</span>
            @endif
            <h3 class="font-display font-black text-xl text-ink leading-[1.15] mb-2 tracking-tight line-clamp-3">
                <a href="{{ $url }}" {!! $target !!} class="hover:text-city-accent transition-colors">{{ $title }}</a>
            </h3>
            <div class="font-sans text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-auto">
                {{ $date }}
            </div>
        </div>
    </article>

@elseif($variant === 'compact-rail')
    <article class="flex gap-4 min-w-[300px] w-[300px] group snap-center">
        <a href="{{ $url }}" {!! $target !!} class="w-24 h-24 flex-shrink-0 bg-gray-100 overflow-hidden relative">
            @if($imageUrl)
                <img src="{{ $imageUrl }}" 
                     alt="{{ $title }}" 
                     referrerpolicy="no-referrer"
                     loading="lazy"
                     onerror="this.onerror=null; this.src='{{ asset('images/ujjain-wale.jpeg') }}';"
                     class="w-full h-full object-cover opacity-95 group-hover:scale-105 transition-transform duration-700 ease-out">
            @endif
        </a>
        <div class="flex flex-col justify-center py-1">
            @if($categoryName)
                <span class="text-city-accent font-sans font-bold text-[9px] tracking-widest uppercase mb-1">{{ $categoryName }}</span>
            @endif
            <h4 class="font-display font-bold text-base text-ink leading-tight line-clamp-3 tracking-tight">
                <a href="{{ $url }}" {!! $target !!} class="hover:text-city-accent transition-colors">{{ $title }}</a>
            </h4>
        </div>
    </article>
@endif