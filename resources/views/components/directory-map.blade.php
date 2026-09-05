@props(['initialMarkers' => []])

<div x-data="directoryMap(@js($initialMarkers))" class="w-full h-full relative z-0">
    <div id="map" class="w-full h-full bg-gray-100" wire:ignore></div>
</div>

@pushonce('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('directoryMap', (initialMarkers) => ({
            map: null,
            markersLayer: null,
            
            init() {
                // Initialize map centered on Indore
                this.map = L.map('map').setView([22.7196, 75.8577], 13);
                
                // Add standard OpenStreetMap tiles
                // Note: The standard tile.openstreetmap.org server prohibits heavy/bulk traffic and can rate-limit without warning.
                // This is fine for development and early low-traffic launch, but this should be revisited for a dedicated tile provider (e.g. Mapbox, Jawg, or paid CARTO) before real traffic scales up.
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                    maxZoom: 19
                }).addTo(this.map);

                // Initialize MarkerClusterGroup
                this.markersLayer = L.markerClusterGroup({
                    showCoverageOnHover: false,
                    maxClusterRadius: 40
                });
                this.map.addLayer(this.markersLayer);

                // Load initial markers
                this.updateMarkers(initialMarkers);

                // Listen for Livewire updates
                Livewire.on('update-markers', (data) => {
                    this.updateMarkers(data[0].markers);
                });
            },

            updateMarkers(markers) {
                this.markersLayer.clearLayers();

                if (!markers || markers.length === 0) return;

                let bounds = L.latLngBounds();
                
                markers.forEach(business => {
                    if (business.lat && business.lng) {
                        let marker = L.marker([business.lat, business.lng]);
                        marker.bindPopup(`
                            <div class="p-2 font-sans">
                                <h4 class="font-bold text-ink text-sm mb-1">${business.name}</h4>
                                <p class="text-xs text-gray-600 mb-2">${business.address}</p>
                                <a href="${business.url}" class="text-xs text-city-accent hover:underline">View Profile &rarr;</a>
                            </div>
                        `);
                        
                        marker.on('click', () => {
                            let card = document.getElementById('business-card-' + business.id);
                            if (card) {
                                card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                
                                // Highlight effect
                                card.classList.add('ring-4', 'ring-city-accent', 'scale-[1.02]', 'z-50');
                                setTimeout(() => {
                                    card.classList.remove('ring-4', 'ring-city-accent', 'scale-[1.02]', 'z-50');
                                }, 1500);
                            }
                        });

                        this.markersLayer.addLayer(marker);
                        bounds.extend([business.lat, business.lng]);
                    }
                });

                if (markers.length > 0) {
                    this.map.fitBounds(bounds, { padding: [50, 50], maxZoom: 15 });
                }
            }
        }))
    })
</script>

<style>
    /* Custom Map Overrides */
    .leaflet-popup-content-wrapper {
        border-radius: 0.375rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    .leaflet-popup-content {
        margin: 10px;
        line-height: 1.4;
    }
    .leaflet-container a.leaflet-popup-close-button {
        color: #706f6c;
        padding: 4px;
    }
</style>
@endpushonce
