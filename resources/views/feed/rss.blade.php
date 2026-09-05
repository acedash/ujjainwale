<?= '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>{{ config('app.name') }}</title>
        <link>{{ url('/') }}</link>
        <description>The latest news and local updates from {{ config('app.name') }}.</description>
        <language>en</language>
        <pubDate>{{ now()->toRfc2822String() }}</pubDate>
        <atom:link href="{{ url('/feed.xml') }}" rel="self" type="application/rss+xml"/>
        
        @foreach($articles as $article)
            <item>
                <title><![CDATA[{{ $article->title }}]]></title>
                <link>{{ route('articles.show', $article) }}</link>
                <description><![CDATA[{{ Str::limit(strip_tags($article->body), 250) }}]]></description>
                <author>{{ $article->author->email ?? 'staff@example.com' }} ({{ $article->author->name ?? 'Staff' }})</author>
                <pubDate>{{ $article->published_at?->toRfc2822String() ?? $article->created_at->toRfc2822String() }}</pubDate>
                <guid>{{ route('articles.show', $article) }}</guid>
            </item>
        @endforeach
    </channel>
</rss>
