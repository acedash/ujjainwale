<div class="bg-ink text-white py-12 border-t border-newsprint">
    <div class="max-w-7xl mx-auto px-8 md:px-12 text-center">
        <h2 class="font-display font-bold text-3xl mb-4">Get the best of Indore, delivered.</h2>
        <p class="font-sans text-gray-300 mb-8 max-w-2xl mx-auto">Sign up for our weekly newsletter to get the latest local news, business features, and event guides straight to your inbox.</p>
        <form class="flex flex-col sm:flex-row max-w-md mx-auto gap-4">
            <input type="email" placeholder="Your email address" class="flex-grow px-4 py-3 rounded text-ink font-sans focus:outline-none focus:ring-2 focus:ring-city-accent" required>
            <button type="submit" class="bg-city-accent text-white px-6 py-3 rounded font-sans font-bold hover:bg-opacity-90 transition">Subscribe</button>
        </form>
    </div>
</div>

<footer class="border-t border-newsprint py-12 bg-white">
    <div class="max-w-7xl mx-auto px-8 md:px-12 grid grid-cols-1 md:grid-cols-4 gap-8">
        <div>
            <h3 class="font-display font-bold text-xl mb-4">Indore.</h3>
            <p class="font-sans text-sm text-gray-600">The premier local news and business directory for the city.</p>
        </div>
        <div>
            <h4 class="font-sans font-semibold mb-4">Sections</h4>
            <ul class="space-y-2 font-sans text-sm text-gray-600">
                <li><a href="#" class="hover:text-city-accent">News</a></li>
                <li><a href="#" class="hover:text-city-accent">Opinion</a></li>
                <li><a href="#" class="hover:text-city-accent">Arts</a></li>
                <li><a href="{{ route('directory') }}" class="hover:text-directory-blue">Local Businesses</a></li>
            </ul>
        </div>
        <div>
            <h4 class="font-sans font-semibold mb-4">Company</h4>
            <ul class="space-y-2 font-sans text-sm text-gray-600">
                <li><a href="#" class="hover:text-city-accent">About Us</a></li>
                <li><a href="#" class="hover:text-city-accent">Contact</a></li>
                <li><a href="#" class="hover:text-city-accent">Advertise</a></li>
            </ul>
        </div>
        <div>
            <h4 class="font-sans font-semibold mb-4">Follow Us</h4>
            <div class="flex space-x-4">
                <a href="#" class="text-gray-400 hover:text-city-accent">Twitter</a>
                <a href="#" class="text-gray-400 hover:text-city-accent">Facebook</a>
            </div>
        </div>
    </div>
</footer>