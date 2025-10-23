<div id="patrol-map" style="height: 420px; border-radius: 10px; margin-top: 10px;"></div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const mapContainer = document.getElementById('patrol-map');
    if (!mapContainer) return;

    const checkpoints = @json($patrolCheckpoints);
    const start = @json($start);
    const end = @json($end);

    const latlngs = [];

    if (start?.lat && start?.lon) latlngs.push([start.lat, start.lon]);
    checkpoints.forEach(cp => latlngs.push([cp.lat, cp.lon]));
    if (end?.lat && end?.lon) latlngs.push([end.lat, end.lon]);

    if (latlngs.length === 0) {
        mapContainer.innerHTML = "<p class='text-gray-500 text-center mt-4'>No patrol location data available</p>";
        return;
    }

    const map = L.map('patrol-map');
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Start marker (green)
    if (start?.lat && start?.lon) {
        L.marker([start.lat, start.lon], {
            title: 'Start Point',
            icon: L.divIcon({ className: 'start-marker', html: '🟢' })
        }).addTo(map).bindPopup(`<b>Start Patrol</b><br>${start.time}`);
    }

    // Checkpoint markers (blue)
    checkpoints.forEach((cp, i) => {
        L.marker([cp.lat, cp.lon], {
            title: `Checkpoint ${i + 1}`,
            icon: L.divIcon({ className: 'checkpoint-marker', html: '📍' })
        }).addTo(map)
            .bindPopup(`<b>Checkpoint ${i + 1}</b><br>${cp.scanned_at}`);
    });

    // End marker (red)
    if (end?.lat && end?.lon) {
        L.marker([end.lat, end.lon], {
            title: 'End Point',
            icon: L.divIcon({ className: 'end-marker', html: '🔴' })
        }).addTo(map).bindPopup(`<b>End Patrol</b><br>${end.time}`);
    }

    // Polyline
    const polyline = L.polyline(latlngs, {
        color: 'blue',
        weight: 4,
        opacity: 0.75,
    }).addTo(map);

    map.fitBounds(polyline.getBounds());
});
</script>
