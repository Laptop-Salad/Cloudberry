<div x-data="mapComponent()" x-init="initMap()">
    <div id="map" style="height:500px; width:100%;"></div>
</div>

<script>
    function mapComponent() {
        return {
            map: null,
            initMap() {
                // Center on UK (roughly)
                this.map = L.map('map').setView([54.5, -3], 6);

                // Add OpenStreetMap tiles
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(this.map);

                // Example: add a marker
                L.marker([51.5074, -0.1278]).addTo(this.map) // London
                    .bindPopup('Example site')
                    .openPopup();
            }
        }
    }
</script>
