<div>
    <div class="flex space-x-2">
        <flux:input
            label="Site Search"
        />

        <flux:input
            label="Truck Search"
        />
    </div>

    <div class="mt-8 relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
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
</div>

<script>
    function mapComponent({ sites, delivery_companies, routes }) {
        return {
            map: null,

            initMap() {
                this.map = L.map('map').setView([54.5, -3], 6);

                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png')
                    .addTo(this.map);

                sites.forEach(site => {
                    L.marker([site.lat, site.lng]).addTo(this.map)
                        .bindPopup(site.name);
                });

                const redIcon = new L.Icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                });

                delivery_companies.forEach(site => {
                    L.marker([site.lat, site.lng], { icon: redIcon })
                        .addTo(this.map)
                        .bindPopup(site.name);
                });

                routes.forEach(route => {
                    const points = [
                        [route.from.lat, route.from.lng],
                        [route.to.lat, route.to.lng]
                    ];

                    const color = route.status === 'complete' ? 'green' : 'orange';

                    L.polyline(points, {
                        color: color,
                        weight: 4
                    }).addTo(this.map)
                        .bindPopup(`Route for ${route.company}`);
                });
            }
        }
    }
</script>
