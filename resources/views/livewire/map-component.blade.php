<div>
    <p class="text-2xl font-semibold">Map</p>

    <flux:separator />

    <div wire:ignore class="mt-8 relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
        <div
            x-data="mapComponent({
                sites: @js($this->production_sites),
                delivery_companies: @js($this->delivery_companies),
                routes: @js($this->routes)
            })"
            x-init="initMap()"
        >
            <div id="map" style="height:500px; width:100%;" class="relative !z-10"></div>
        </div>
    </div>

    <div class="flex space-x-2 mt-8">
        <flux:field>
            <flux:label class="bg-[#EFFF00]">Site Search</flux:label>

            <flux:input wire:model.live="search_sites" />

            <flux:error name="search_sites" />
        </flux:field>

        <flux:field>
            <flux:label class="bg-[#EFFF00]">Truck Search</flux:label>

            <flux:input wire:model.live="search_trucks" />

            <flux:error name="search_trucks" />
        </flux:field>
    </div>
</div>

<script>
    function mapComponent({ sites = [], delivery_companies = [], routes = [] }) {
        return {
            map: null,
            markers: [],
            polylines: [],

            initMap() {
                // Initialize map
                this.map = L.map('map').setView([54.5, -3], 6);

                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors',
                    maxZoom: 19
                }).addTo(this.map);

                // Add initial data
                this.updateMap({ sites, delivery_companies, routes });

                // Listen for Livewire updates
                window.addEventListener('map-update', (event) => {
                    this.updateMap(event.detail);
                });
            },

            clearMap() {
                this.markers.forEach(m => this.map.removeLayer(m));
                this.polylines.forEach(l => this.map.removeLayer(l));
                this.markers = [];
                this.polylines = [];
            },

            updateMap({ sites = [], delivery_companies = [], routes = [] }) {
                this.clearMap();

                // Add production sites
                sites.forEach(site => {
                    if (site.lat && site.lng) {
                        this.markers.push(
                            L.marker([site.lat, site.lng]).addTo(this.map)
                                .bindPopup(site.name)
                        );
                    }
                });

                // Red icon for delivery companies
                const redIcon = new L.Icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34]
                });

                delivery_companies.forEach(company => {
                    if (company.lat && company.lng) {
                        this.markers.push(
                            L.marker([company.lat, company.lng], { icon: redIcon })
                                .addTo(this.map)
                                .bindPopup(company.name)
                        );
                    }
                });

                // Add routes
                routes.forEach(route => {
                    if (route.from.lat && route.from.lng && route.to.lat && route.to.lng) {
                        const line = L.polyline([
                            [route.from.lat, route.from.lng],
                            [route.to.lat, route.to.lng]
                        ], {
                            color: route.status === 'pending' ? 'orange' : 'green',
                            weight: 4
                        }).addTo(this.map);

                        this.polylines.push(line);
                    }
                });

                // Optional: fit map bounds to show all markers/routes
                const allPoints = [
                    ...sites.map(s => [s.lat, s.lng]),
                    ...delivery_companies.map(c => [c.lat, c.lng]),
                    ...routes.flatMap(r => [[r.from.lat, r.from.lng], [r.to.lat, r.to.lng]])
                ].filter(p => p[0] && p[1]);

                if (allPoints.length) this.map.fitBounds(allPoints, { padding: [50, 50] });
            }
        }
    }
</script>
