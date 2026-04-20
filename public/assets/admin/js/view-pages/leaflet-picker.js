"use strict";

// Lightweight Leaflet map picker to replace Google Maps when no API key.
// Usage:
//   window.npInitLeafletPicker({
//     mapId: "map",
//     searchInputId: "pac-input",
//     latInputId: "latitude",
//     lngInputId: "longitude",
//     startLat: 23.757989,
//     startLng: 90.360587,
//     zoom: 13
//   });
window.npInitLeafletPicker = function (opts) {
    if (!window.L) return;
    const mapId = opts && opts.mapId ? opts.mapId : "map";
    const mapEl = document.getElementById(mapId);
    if (!mapEl) return;

    const searchInput = document.getElementById(opts.searchInputId || "pac-input");
    const latEl = document.getElementById(opts.latInputId || "latitude");
    const lngEl = document.getElementById(opts.lngInputId || "longitude");

    const startLat = Number(opts.startLat);
    const startLng = Number(opts.startLng);
    const zoom = Number(opts.zoom || 13);

    const leafletMap = L.map(mapId).setView([startLat, startLng], zoom);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(leafletMap);

    let marker = L.marker([startLat, startLng]).addTo(leafletMap);

    function setLatLng(lat, lng) {
        if (latEl) latEl.value = lat;
        if (lngEl) lngEl.value = lng;
        marker.setLatLng([lat, lng]);
    }

    leafletMap.on('click', function (e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;
        setLatLng(lat, lng);
    });

    async function geocode(q) {
        const url = `https://nominatim.openstreetmap.org/search?format=jsonv2&limit=1&q=${encodeURIComponent(q)}`;
        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
        if (!res.ok) return null;
        const results = await res.json();
        return results && results[0] ? results[0] : null;
    }

    if (searchInput) {
        searchInput.addEventListener('keydown', async function (ev) {
            if (ev.key !== 'Enter') return;
            ev.preventDefault();
            const q = (searchInput.value || '').trim();
            if (!q) return;
            try {
                const first = await geocode(q);
                if (first && first.lat && first.lon) {
                    const lat = parseFloat(first.lat);
                    const lng = parseFloat(first.lon);
                    setLatLng(lat, lng);
                    leafletMap.setView([lat, lng], Math.max(leafletMap.getZoom(), zoom));
                }
            } catch (e) {
                // ignore
            }
        });
    }
};

