@props(['headlines'])

@if($headlines->isNotEmpty())
    <section class="mb-16 mt-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="font-display font-bold text-3xl text-ink">
                    <span class="text-city-accent mr-2">|</span>Trending
                </h2>
                <p class="font-sans text-gray-500 mt-1">Headlines from other local news sources, updated automatically.</p>
            </div>
            <a href="{{ route('external.index') }}" class="font-sans font-semibold text-sm text-city-accent hover:text-ink transition-colors uppercase tracking-wide">
                See All &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($headlines->take(6) as $headline)
                <div class="bg-white p-6 border border-gray-100 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                    @if($headline->thumbnail_url)
                        <div class="h-48 -mx-6 -mt-6 mb-5 overflow-hidden rounded-t-2xl">
                            <img src="{{ $headline->thumbnail_url }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                    @endif
                    <h3 class="font-display font-bold text-xl text-ink leading-snug mb-3">
                        <a href="{{ route('external.show', $headline->id) }}" class="hover:text-city-accent transition-colors">
                            {{ $headline->title }}
                        </a>
                    </h3>
                    <p class="font-sans text-sm text-gray-500 mb-4 line-clamp-3">
                        {{ $headline->original_snippet }}
                    </p>
                    <div class="flex items-center gap-2">
                        @if($headline->newsSource->hasMedia('logo'))
                            <img src="{{ $headline->newsSource->getFirstMediaUrl('logo') }}" alt="{{ $headline->newsSource->name }}" class="w-4 h-4 rounded-full">
                        @endif
                        <span class="font-sans text-xs font-semibold text-gray-500 uppercase tracking-wide">
                            {{ $headline->newsSource->name }}
                        </span>
                        <span class="font-sans text-xs text-gray-400">&bull;</span>
                        <span class="font-sans text-xs text-gray-400">
                            {{ $headline->published_at ? $headline->published_at->diffForHumans() : $headline->fetched_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endif
