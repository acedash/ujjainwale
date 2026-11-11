@php
    $isExternal = $article instanceof \App\Models\ExternalHeadline;
    $title = $article->title;
    $url = $isExternal ? route('external.show', $article) : route('articles.show', $article);
    $target = '';

    $imageUrl = $isExternal ? $article->thumbnail_url : ($article->getFirstMediaUrl('featured_image') ?: $article->getFirstMediaUrl('cover'));

    $categoryName = $article->categories->first()?->name;
    $date = $isExternal
        ? ($article->published_at ?? $article->fetched_at)?->format('M j, Y')
        : ($article->published_at?->format('M j, Y') ?? 'Draft');
    $excerpt = $isExternal ? $article->original_snippet : Str::limit(strip_tags($article->body), 150);
    $authorOrSource = $isExternal ? $article->newsSource->name : ($article->author->name ?? 'Staff');

    $containerClass = ''; 
@endphp

@if($variant === 'hero-split')
    <article class="flex flex-col md:flex-row w-full h-full group {{ $isExternal ? 'opacity-95' : '' }}">
        <!-- Left Side: Text Content -->
        <div class="w-full md:w-1/2 bg-midnight flex flex-col justify-center p-8 md:p-12 lg:p-16 text-white order-2 md:order-1 relative z-10">
            <div class="max-w-2xl">
                @if($isExternal)
                    <span class="inline-flex items-center gap-1.5 bg-white/10 text-white px-3 py-1 rounded-sm text-[10px] font-sans font-bold tracking-widest uppercase mb-4 border border-white/20">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        Aggregated &middot; {{ $authorOrSource }}
                    </span>
                @elseif($categoryName)
                    <span class="text-city-accent border-b border-city-accent pb-1 font-sans font-bold text-[10px] tracking-widest uppercase mb-4 inline-block">{{ $categoryName }}</span>
                @endif

                <h2 class="font-sans font-bold text-3xl md:text-4xl text-white leading-tight mb-4 tracking-tight group-hover:text-city-accent transition-colors duration-500 line-clamp-3">
                    <a href="{{ $url }}" {!! $target !!} class="block">
                        {{ $title }}
                    </a>
                </h2>

                <p class="font-sans text-base lg:text-lg text-gray-300 mb-6 line-clamp-3 leading-relaxed font-light">
                    {{ $excerpt }}
                </p>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3 font-sans text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                        @if(!$isExternal)
                            <span class="text-white">By {{ $authorOrSource }}</span>
                            <span class="text-city-accent">&bull;</span>
                        @endif
                        <span>{{ $date }}</span>
                    </div>
                    
                    <a href="{{ $url }}" {!! $target !!} class="hidden lg:flex items-center gap-1.5 font-sans font-bold text-white hover:text-city-accent transition-colors uppercase tracking-widest text-[10px]">
                        Read 
                        <svg class="w-4 h-4 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Side: Image -->
        <div class="w-full md:w-1/2 relative bg-gray-100 order-1 md:order-2 h-64 md:h-auto overflow-hidden">
            @if($imageUrl)
                <img src="{{ $imageUrl }}" alt="{{ $title }}"
                    class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000 ease-out {{ $isExternal ? 'grayscale-[20%]' : '' }}">
            @else
                <div class="absolute inset-0 bg-dark-accent"></div>
            @endif
            <!-- Subtle gradient to blend edges slightly on mobile -->
            <div class="absolute inset-0 bg-gradient-to-t from-midnight via-transparent to-transparent opacity-80 md:hidden"></div>
        </div>
    </article>

@elseif($variant === 'hero')
    {{-- Fallback for old hero component just in case it's used elsewhere --}}
    <article class="relative w-full h-[60vh] min-h-[500px] max-h-[800px] rounded-sm overflow-hidden group mb-12">
        @if($imageUrl)
            <div class="absolute inset-0 bg-black">
                <img src="{{ $imageUrl }}" alt="{{ $title }}" class="w-full h-full object-cover opacity-80 group-hover:scale-105 transition-transform duration-1000 ease-out">
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
                <img src="{{ $imageUrl }}" alt="{{ $title }}" class="w-full h-full object-cover opacity-90 group-hover:scale-105 transition-transform duration-700 ease-out">
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
                <a href="{{ $url }}" {!! $target !!} class="hover:text-gray-500 transition-colors">{{ $title }}</a>
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
                <img src="{{ $imageUrl }}" alt="{{ $title }}" class="w-full h-full object-cover opacity-90 group-hover:scale-105 transition-transform duration-700 ease-out">
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
                <img src="{{ $imageUrl }}" alt="{{ $title }}" class="w-full h-full object-cover opacity-90 group-hover:scale-105 transition-transform duration-700 ease-out">
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