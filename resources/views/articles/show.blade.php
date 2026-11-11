<x-app-layout>
    <article class="max-w-5xl mx-auto px-6 py-12 md:py-16">
        <header class="mb-10 max-w-3xl">
            @if($article->categories->count())
                <span
                    class="text-amber-800 font-sans font-bold text-xs tracking-widest uppercase mb-4 inline-flex items-center gap-1.5 bg-amber-50 px-3 py-1 rounded-full border border-amber-200">{{ $article->categories->first()->name }}</span>
            @endif

            <h1 class="font-display font-black text-5xl md:text-7xl text-ink leading-[1.1] mb-6">
                {{ $article->title }}
            </h1>

            @if($article->dek)
                <h2 class="font-sans text-xl md:text-2xl text-gray-500 font-light mb-8 leading-relaxed">
                    {{ $article->dek }}
                </h2>
            @endif

            <div class="font-sans text-sm text-gray-500 flex items-center gap-2 mb-8">
                <span>By <span class="font-semibold text-ink">{{ $article->author->name ?? 'Staff' }}</span></span>
                <span>&middot;</span>
                <span>{{ $article->published_at?->format('F j, Y') ?? 'Draft' }}</span>
            </div>
        </header>

        @php
            $articleFeaturedImage = $article->getFirstMediaUrl('featured_image') ?: $article->getFirstMediaUrl('cover');
        @endphp

        @if($articleFeaturedImage)
            <div class="aspect-video max-w-4xl bg-newsprint rounded-xl overflow-hidden mb-16 shadow-lg relative">
                <img src="{{ $articleFeaturedImage }}" alt="{{ $article->title }}"
                    class="w-full h-full object-cover">
            </div>
        @endif

        <div
            class="prose prose-xl prose-ink max-w-2xl font-serif leading-relaxed mb-10 prose-img:rounded-xl prose-img:shadow-lg prose-headings:font-display prose-headings:font-bold prose-a:text-city-accent hover:prose-a:text-ink prose-p:first-of-type:first-letter:text-7xl prose-p:first-of-type:first-letter:font-display prose-p:first-of-type:first-letter:font-black prose-p:first-of-type:first-letter:float-left prose-p:first-of-type:first-letter:mr-3 prose-p:first-of-type:first-letter:-mt-2 prose-p:first-of-type:first-letter:text-ink [&_figcaption.attachment__caption]:hidden">
            {!! $article->body !!}
        </div>

        <!-- Rectangle Ad Placeholder inside article flow -->
        <x-ad-space size="large-rectangle" class="mb-16 -ml-4 md:ml-0" />

        @if($article->mentionedBusinesses->count())
            <section class="border-t border-newsprint pt-10">
                <h3 class="font-display font-bold text-2xl text-ink mb-6">Mentioned in this article</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($article->mentionedBusinesses as $business)
                        <div class="p-6 border border-newsprint rounded bg-paper flex flex-col">
                            <h4 class="font-display font-bold text-xl text-ink mb-2">{{ $business->name }}</h4>
                            <p class="font-sans text-sm text-gray-600 mb-4">{{ $business->address }}</p>

                            @if($business->phone || $business->website)
                                <div class="font-sans text-sm text-gray-500 flex flex-col gap-1 mt-auto">
                                    @if($business->phone)
                                        <span>{{ $business->phone }}</span>
                                    @endif
                                    @if($business->website)
                                        <a href="{{ $business->website }}" target="_blank"
                                            class="text-city-accent hover:underline">Visit Website &rarr;</a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </article>
</x-app-layout>