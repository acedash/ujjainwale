@php
    $isExternal = $article instanceof \App\Models\ExternalHeadline;
    $title = $article->title;
    $url = $isExternal ? route('external.show', $article) : route('articles.show', $article);
    $target = '';

    // For external, maybe the image is low quality, we can just use a fallback or the image itself
    // We will use the image if available, but wrap it or we can just ignore it for external to be safe.
    // Let's use the image if available, but provide a solid fallback if not.
    $imageUrl = $isExternal ? $article->thumbnail_url : $article->getFirstMediaUrl('featured_image');

    $categoryName = $article->categories->first()?->name;
    $date = $isExternal
        ? ($article->published_at ?? $article->fetched_at)?->format('M j, Y')
        : ($article->published_at?->format('M j, Y') ?? 'Draft');
    $excerpt = $isExternal ? $article->original_snippet : Str::limit(strip_tags($article->body), 150);
    $authorOrSource = $isExternal ? $article->newsSource->name : ($article->author->name ?? 'Staff');

    $containerClass = ''; 
@endphp

@if($variant === 'hero')
    <article
        class="relative w-full h-[60vh] min-h-[500px] max-h-[800px] rounded-2xl overflow-hidden group shadow-2xl mb-12 {{ $isExternal ? 'opacity-95' : '' }}">
        <!-- Background Image -->
        @if($imageUrl && !$isExternal)
            <div class="absolute inset-0 bg-black">
                <img src="{{ $imageUrl }}" alt="{{ $title }}"
                    class="w-full h-full object-cover opacity-80 group-hover:scale-105 transition-transform duration-1000 ease-out">
            </div>
        @elseif($isExternal && $imageUrl)
            <div class="absolute inset-0 bg-black">
                <!-- Desaturate external images slightly if they are low quality -->
                <img src="{{ $imageUrl }}" alt="{{ $title }}"
                    class="w-full h-full object-cover opacity-60 grayscale-[30%] group-hover:scale-105 transition-transform duration-1000 ease-out">
            </div>
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-directory-blue to-ink"></div>
        @endif

        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-ink via-ink/60 to-transparent opacity-90"></div>

        <!-- Content -->
        <div class="absolute inset-0 flex flex-col justify-end p-8 md:p-12 lg:p-16 text-white z-10">
            <div class="max-w-4xl">
                @if($isExternal)
                    <span
                        class="inline-flex items-center gap-1.5 bg-white/20 backdrop-blur-md text-white px-3 py-1 rounded text-xs font-sans font-bold tracking-widest uppercase mb-4 border border-white/30">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        Aggregated &middot; via {{ $authorOrSource }}
                    </span>
                @elseif($categoryName)
                    <span
                        class="text-city-accent font-sans font-bold text-sm tracking-widest uppercase mb-4 block">{{ $categoryName }}</span>
                @endif

                <h2
                    class="font-display font-bold text-3xl md:text-4xl lg:text-5xl text-white leading-tight mb-6 line-clamp-3">
                    <a href="{{ $url }}" {!! $target !!} class="hover:text-gray-200 transition-colors block">
                        {{ $title }}
                    </a>
                </h2>

                <p
                    class="font-sans text-lg md:text-xl text-gray-200 mb-6 line-clamp-2 leading-relaxed max-w-3xl font-light">
                    {{ $excerpt }}
                </p>

                <div
                    class="flex items-center gap-4 font-sans text-sm font-semibold text-gray-300 uppercase tracking-widest">
                    @if(!$isExternal)
                        <span class="text-white">By {{ $authorOrSource }}</span>
                        <span class="text-gray-400">&bull;</span>
                    @endif
                    <span>{{ $date }}</span>
                </div>
            </div>
        </div>
    </article>

@elseif($variant === 'standard')
    <article
        class="flex flex-col gap-3 h-full bg-white border border-gray-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] rounded-xl p-4 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 {{ $containerClass }}">
        <div class="aspect-[4/3] bg-gray-100 rounded-lg overflow-hidden mb-2 relative flex items-center justify-center">
            @if($imageUrl)
                <img src="{{ $imageUrl }}" alt="{{ $title }}"
                    class="w-full h-full object-cover opacity-90 hover:opacity-100 transition-opacity">
            @elseif($isExternal)
                <div class="absolute inset-0 bg-gradient-to-br from-gray-100 to-gray-200"></div>
                <span class="font-display font-bold text-gray-300 text-2xl relative z-10">{{ $authorOrSource }}</span>
            @endif
        </div>
        @if($isExternal)
            <span
                class="inline-flex items-center gap-1 bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-[10px] font-sans font-bold tracking-wider uppercase w-fit">
                Aggregated &middot; {{ $authorOrSource }}
            </span>
        @elseif($categoryName)
            <span class="text-city-accent font-sans font-bold text-xs tracking-wider uppercase">{{ $categoryName }}</span>
        @endif
        <h3 class="font-display font-bold text-xl text-ink leading-snug">
            <a href="{{ $url }}" {!! $target !!}
                class="hover:text-city-accent transition-colors before:absolute before:inset-0">{{ $title }}</a>
        </h3>
        <div class="font-sans text-xs text-gray-500 mt-auto">
            {{ $date }}
        </div>
    </article>

@elseif($variant === 'compact')
    <article class="flex gap-4 rounded-xl hover:bg-gray-50 transition-colors group {{ $containerClass }}">
        <div
            class="w-2/5 aspect-[4/3] bg-gray-100 rounded-lg overflow-hidden flex-shrink-0 relative flex items-center justify-center">
            @if($imageUrl)
                <img src="{{ $imageUrl }}" alt="{{ $title }}"
                    class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition-opacity">
            @elseif($isExternal)
                <div class="absolute inset-0 bg-gradient-to-br from-gray-100 to-gray-200"></div>
                <span
                    class="font-display font-bold text-gray-300 text-sm relative z-10 text-center px-2">{{ $authorOrSource }}</span>
            @endif
        </div>
        <div class="w-3/5 flex flex-col justify-center py-1">
            @if($isExternal)
                <span
                    class="inline-flex items-center gap-1 bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded text-[9px] font-sans font-bold tracking-wider uppercase mb-1 w-fit">
                    Aggregated &middot; {{ $authorOrSource }}
                </span>
            @elseif($categoryName)
                <span
                    class="text-city-accent font-sans font-bold text-xs tracking-wider uppercase mb-1">{{ $categoryName }}</span>
            @endif
            <h3 class="font-display font-bold text-xl text-ink leading-tight mb-2 line-clamp-3">
                <a href="{{ $url }}" {!! $target !!} class="hover:text-city-accent transition-colors">{{ $title }}</a>
            </h3>
            <div class="font-sans text-xs text-gray-500 mt-auto">
                {{ $date }}
            </div>
        </div>
    </article>

@elseif($variant === 'compact-rail')
    <article
        class="flex gap-4 min-w-[300px] w-[300px] p-4 bg-white border border-gray-100 rounded-xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] snap-center hover:-translate-y-1 hover:shadow-xl transition-all duration-300 {{ $isExternal ? 'bg-gray-50' : '' }}">
        <div
            class="w-20 h-20 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden relative flex items-center justify-center">
            @if($imageUrl)
                <img src="{{ $imageUrl }}" alt="{{ $title }}"
                    class="w-full h-full object-cover opacity-90 hover:opacity-100 transition-opacity">
            @elseif($isExternal)
                <div class="absolute inset-0 bg-gradient-to-br from-gray-100 to-gray-200"></div>
                <span
                    class="font-display font-bold text-gray-300 text-[10px] relative z-10 text-center px-1 truncate w-full">{{ $authorOrSource }}</span>
            @endif
        </div>
        <div class="flex flex-col justify-center">
            @if($isExternal)
                <span class="text-gray-500 font-sans font-bold text-[9px] tracking-wider uppercase mb-1">Aggregated &middot;
                    {{ $authorOrSource }}</span>
            @elseif($categoryName)
                <span
                    class="text-city-accent font-sans font-bold text-[10px] tracking-wider uppercase mb-1">{{ $categoryName }}</span>
            @endif
            <h4 class="font-display font-semibold text-sm text-ink leading-tight line-clamp-3">
                <a href="{{ $url }}" {!! $target !!}
                    class="hover:text-city-accent transition-colors before:absolute before:inset-0">{{ $title }}</a>
            </h4>
        </div>
    </article>
@endif