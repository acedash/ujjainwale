<x-dashboard-layout>
    <div class="relative bg-white/70 backdrop-blur-xl p-8 border border-white/50 rounded-3xl shadow-sm overflow-hidden">
        <!-- Subtle Glow -->
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-city-accent/5 rounded-full blur-[80px] pointer-events-none"></div>

        <h2 class="font-display font-black text-3xl text-ink mb-2">Overview</h2>
        <p class="font-sans text-gray-500 mb-10 text-lg max-w-2xl">
            Welcome to your control center. Manage your profile, track your reviews, and oversee your local business listings from here.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10">
            <!-- Reviews Card -->
            <div class="group bg-gradient-to-br from-white to-gray-50 p-8 rounded-2xl border border-gray-200/60 shadow-sm hover:shadow-md transition-all hover:-translate-y-1">
                <div class="flex items-start justify-between mb-6">
                    <div class="w-12 h-12 rounded-xl bg-yellow-50 text-yellow-500 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                    </div>
                    <span class="font-display font-black text-4xl text-ink">{{ auth()->user()->reviews()->count() }}</span>
                </div>
                <h3 class="font-sans font-bold text-lg text-ink mb-1">My Reviews</h3>
                <p class="font-sans text-sm text-gray-500 mb-6">Reviews you've shared.</p>
                <a href="{{ route('dashboard.reviews') }}" class="inline-flex items-center gap-2 text-city-accent font-sans font-bold text-sm group-hover:gap-3 transition-all">
                    View Activity
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
            
            <!-- Businesses Card -->
            <div class="group bg-gradient-to-br from-white to-gray-50 p-8 rounded-2xl border border-gray-200/60 shadow-sm hover:shadow-md transition-all hover:-translate-y-1">
                <div class="flex items-start justify-between mb-6">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <span class="font-display font-black text-4xl text-ink">{{ auth()->user()->businesses()->count() }}</span>
                </div>
                <h3 class="font-sans font-bold text-lg text-ink mb-1">My Businesses</h3>
                <p class="font-sans text-sm text-gray-500 mb-6">Listings you manage.</p>
                <a href="{{ route('dashboard.businesses') }}" class="inline-flex items-center gap-2 text-city-accent font-sans font-bold text-sm group-hover:gap-3 transition-all">
                    Manage Listings
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
        </div>
    </div>
</x-dashboard-layout>
