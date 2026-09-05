@props(['size' => 'leaderboard', 'class' => ''])

@php
    $dimensions = [
        'leaderboard' => 'w-full max-w-[728px] h-[90px]', // Standard 728x90
        'rectangle' => 'w-[300px] h-[250px]',           // Medium Rectangle 300x250
        'large-rectangle' => 'w-[336px] h-[280px]',     // Large Rectangle 336x280
        'billboard' => 'w-full max-w-[970px] h-[250px]',// Billboard 970x250
    ];

    $classes = $dimensions[$size] ?? $dimensions['leaderboard'];
@endphp

<div class="flex flex-col items-center justify-center my-6 {{ $class }}">
    <span class="text-[10px] text-gray-400 uppercase tracking-widest mb-1 font-sans">Advertisement</span>
    <div class="{{ $classes }} bg-gray-100 border border-gray-200 flex items-center justify-center rounded-lg shadow-inner overflow-hidden relative group">
        <!-- Placeholder for AdSense / Direct Ad -->
        <span class="text-gray-400 font-sans font-medium text-sm group-hover:text-city-accent transition-colors">
            Ad Space ({{ $size }})
        </span>
    </div>
</div>
