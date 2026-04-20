"use strict";

let zonePolygon = null;
let map = null;
let bounds = null;
let infoWindow = null;
let geocoder = null;
let myMarker = null;
let searchMarkers = [];

const MAP_ENABLED = !!(window.mapConfig && window.mapConfig.mapApiKey);

const outOfZoneEl = document.getElementById('outOfZone');
if (outOfZoneEl) {
    outOfZoneEl.style.setProperty("display", "none", "important");
}

function setLatLngInputs(lat, lng) {
    const latEl = document.getElementById('latitude');
    const lngEl = document.getElementById('longitude');
    if (latEl) latEl.value = lat;
    if (lngEl) lngEl.value = lng;
}

function setAddressText(value) {
    const visibleAddress = document.querySelector('.lang_form:not(.d-none) textarea[name="address[]"]');
    if (visibleAddress) {
        visibleAddress.value = value;
        return;
    }
    const addressEl = document.getElementById('address');
    if (addressEl) addressEl.value = value;
}

function checkZonePlain(lat, lng) {
    if (!window.mapConfig || !window.mapConfig.urls || !window.mapConfig.urls.zoneGetZone) return;
    checkModuleType();
    $.get({
        url: window.mapConfig.urls.zoneGetZone,
        data: { lat: lat, lng: lng },
        dataType: 'json',
        success: function (data) {
            if (data.id) {
                $('#choice_zones').data('from-map', true).val(data.id).trigger('change');
                const latlngEl = document.getElementById('latlng');
                if (latlngEl) latlngEl.style.setProperty('display', 'flex', 'important');
                if (outOfZoneEl) outOfZoneEl.style.setProperty('display', 'none', 'important');
            } else {
                $('#choice_zones').val('').trigger('change');
                setLatLngInputs('', '');
                const latlngEl = document.getElementById('latlng');
                if (latlngEl) latlngEl.style.setProperty('display', 'none', 'important');
                if (outOfZoneEl) outOfZoneEl.style.setProperty('display', 'block', 'important');
            }
        },
    });
}

function initLeafletMap() {
    if (MAP_ENABLED || !window.L) return;
    const mapEl = document.getElementById('map');
    if (!mapEl) return;

    const { defaultLocation, oldLat, oldLng, oldAddress } = window.mapConfig || {};
    const hasOldCoords = !isNaN(oldLat) && !isNaN(oldLng);
    const startLat = Number(hasOldCoords ? oldLat : (defaultLocation ? defaultLocation.lat : 23.757989));
    const startLng = Number(hasOldCoords ? oldLng : (defaultLocation ? defaultLocation.lng : 90.360587));

    // Leaflet needs an explicit height; container already has h-280
    const leafletMap = L.map('map').setView([startLat, startLng], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(leafletMap);

    let marker = L.marker([startLat, startLng]).addTo(leafletMap);
    let zoneLayer = null;
    let zonePolygonLngLat = null; // turf polygon coordinates [[lng,lat],...closed]

    async function loadZonePolygon(zoneId) {
        if (!window.mapConfig || !window.mapConfig.urls || !window.mapConfig.urls.zoneCoordinates) return;
        if (!zoneId) {
            if (zoneLayer) {
                leafletMap.removeLayer(zoneLayer);
                zoneLayer = null;
            }
            zonePolygonLngLat = null;
            return;
        }
        try {
            const url = window.mapConfig.urls.zoneCoordinates.replace(':coordinatesZoneId', zoneId);
            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
            if (!res.ok) return;
            const data = await res.json();
            if (!data || !data.coordinates || !Array.isArray(data.coordinates)) return;

            const latlngs = data.coordinates.map(c => [Number(c.lat), Number(c.lng)]).filter(p => !isNaN(p[0]) && !isNaN(p[1]));
            if (latlngs.length < 3) return;

            if (zoneLayer) {
                leafletMap.removeLayer(zoneLayer);
            }
            zoneLayer = L.polygon(latlngs, { color: '#FF0000', weight: 2, fill: false }).addTo(leafletMap);
            leafletMap.fitBounds(zoneLayer.getBounds(), { padding: [12, 12] });

            // Build turf-friendly polygon ring: [[lng,lat],...closed]
            const ring = data.coordinates
                .map(c => [Number(c.lng), Number(c.lat)])
                .filter(p => !isNaN(p[0]) && !isNaN(p[1]));
            if (ring.length >= 3) {
                const first = ring[0];
                const last = ring[ring.length - 1];
                if (!last || last[0] !== first[0] || last[1] !== first[1]) {
                    ring.push(first);
                }
                zonePolygonLngLat = ring;
            }
        } catch (e) {
            // ignore
        }
    }

    function isInsideZone(lat, lng) {
        if (!zonePolygonLngLat) return true; // if no zone drawn yet, allow
        if (!window.turf || !turf.booleanPointInPolygon) return true; // soft fallback
        try {
            const pt = turf.point([Number(lng), Number(lat)]);
            const poly = turf.polygon([zonePolygonLngLat]);
            return turf.booleanPointInPolygon(pt, poly);
        } catch (e) {
            return true;
        }
    }
    setLatLngInputs(startLat, startLng);
    if (oldAddress) {
        const pac = document.getElementById('pac-input');
        if (pac) pac.value = oldAddress;
    }

    async function reverseGeocode(lat, lng) {
        try {
            const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${encodeURIComponent(lat)}&lon=${encodeURIComponent(lng)}`;
            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
            if (!res.ok) return;
            const json = await res.json();
            if (json && json.display_name) {
                setAddressText(json.display_name);
                const pac = document.getElementById('pac-input');
                if (pac) pac.value = json.display_name;
            }
        } catch (e) {
            // ignore network errors
        }
    }

    function setFromLatLng(lat, lng, alsoReverse = true) {
        const zoneOk = isInsideZone(lat, lng);
        if (!zoneOk) {
            setLatLngInputs('', '');
            if (outOfZoneEl) outOfZoneEl.style.setProperty('display', 'block', 'important');
            return;
        }
        setLatLngInputs(lat, lng);
        marker.setLatLng([lat, lng]);
        leafletMap.setView([lat, lng], Math.max(leafletMap.getZoom(), 13));
        if (outOfZoneEl) outOfZoneEl.style.setProperty('display', 'none', 'important');
        checkZonePlain(lat, lng);
        if (alsoReverse) reverseGeocode(lat, lng);
    }

    leafletMap.on('click', function (e) {
        setFromLatLng(e.latlng.lat, e.latlng.lng, true);
    });

    // Simple search using Nominatim (no key)
    const pacInput = document.getElementById('pac-input');
    if (pacInput) {
        pacInput.addEventListener('keydown', async function (ev) {
            if (ev.key !== 'Enter') return;
            ev.preventDefault();
            const q = (pacInput.value || '').trim();
            if (!q) return;
            try {
                const url = `https://nominatim.openstreetmap.org/search?format=jsonv2&limit=1&q=${encodeURIComponent(q)}`;
                const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                if (!res.ok) return;
                const results = await res.json();
                const first = results && results[0];
                if (first && first.lat && first.lon) {
                    setFromLatLng(parseFloat(first.lat), parseFloat(first.lon), false);
                    setAddressText(first.display_name || q);
                }
            } catch (e) {
                // ignore
            }
        });
    }

    // When zone changes, draw boundary like Google
    const zoneSelect = document.getElementById('choice_zones');
    if (zoneSelect) {
        zoneSelect.addEventListener('change', function () {
            loadZonePolygon(zoneSelect.value);
        });
        if (zoneSelect.value) {
            loadZonePolygon(zoneSelect.value);
        }
    }
}

function setAddressFromLatLng(latlng) {
    if (!geocoder) return;
    geocoder.geocode({ location: latlng }, function (results, status) {
        if (status === 'OK' && results[0]) {
            const addr = results[0].formatted_address;
            const visibleAddress = document.querySelector('.lang_form:not(.d-none) textarea[name="address[]"]');
            if (visibleAddress) {
                visibleAddress.value = addr;
            } else {
                const addressEl = document.getElementById('address');
                if (addressEl) addressEl.value = addr;
            }
            const pacInput = document.getElementById('pac-input');
            if (pacInput) pacInput.value = addr;
        }
    });
}

function initMap() {
    if (!MAP_ENABLED || !(window.google && google.maps)) {
        return;
    }
    const { mapApiKey, defaultLocation, oldLat, oldLng, oldAddress, translations } = window.mapConfig;
    const hasOldCoords = !isNaN(oldLat) && !isNaN(oldLng);

    let myLatlng = {
        lat: Number(hasOldCoords ? oldLat : (defaultLocation ? defaultLocation.lat : 23.757989)),
        lng: Number(hasOldCoords ? oldLng : (defaultLocation ? defaultLocation.lng : 90.360587))
    };

    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 13,
        center: myLatlng,
        mapId: mapApiKey,
    });
    geocoder = new google.maps.Geocoder();

    if (oldAddress) {
        const pac = document.getElementById('pac-input');
        if (pac) pac.value = oldAddress;
    }

    map.addListener("click", (mapsMouseEvent) => {
        infoWindow.close();
        if (myMarker) {
            myMarker.setMap(null);
        }
        searchMarkers.forEach(m => m.map = null);
        searchMarkers = [];
        const { AdvancedMarkerElement } = google.maps.marker;
        myMarker = new AdvancedMarkerElement({
            position: mapsMouseEvent.latLng,
            map: map,
            title: translations.selectedLocation
        });
        const coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
        const obj = JSON.parse(coordinates);
        document.getElementById('latitude').value = obj['lat'];
        document.getElementById('longitude').value = obj['lng'];
        if (outOfZoneEl) outOfZoneEl.style.setProperty("display", "none", "important");
        setAddressFromLatLng(mapsMouseEvent.latLng);
        checkZone(obj['lat'], obj['lng']);
    });

    zonePolygon = null;

    infoWindow = new google.maps.InfoWindow({
        content: translations.clickMap,
        position: myLatlng,
    });

    bounds = new google.maps.LatLngBounds();

    // infoWindow = new google.maps.InfoWindow();
    if (!hasOldCoords && navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                myLatlng = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                };
                infoWindow.setPosition(myLatlng);
                infoWindow.setContent(translations.selectZone);
                // infoWindow.open(map);
                map.setCenter(myLatlng);
            },
            () => {
                handleLocationError(true, infoWindow, map.getCenter());
            }
        );
    } else if (hasOldCoords) {
        infoWindow.setPosition(myLatlng);
        infoWindow.setContent(translations.selectZone);
        // infoWindow.open(map);
        map.setCenter(myLatlng);
        const { AdvancedMarkerElement } = google.maps.marker;
        myMarker = new AdvancedMarkerElement({
            position: myLatlng,
            map: map,
            title: translations.selectedLocation
        });
    } else {
        // Browser doesn't support Geolocation
        handleLocationError(false, infoWindow, map.getCenter());
    }

    const input = document.getElementById("pac-input");
    const searchBox = new google.maps.places.SearchBox(input);
    map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
    // let markers = [];
    searchBox.addListener("places_changed", () => {
        const places = searchBox.getPlaces();
        if (places.length == 0) {
            return;
        }
        if (myMarker) {
            myMarker.setMap(null);
            myMarker = null;
        }

        searchMarkers.forEach(m => m.map = null);
        searchMarkers = [];
        const first = places[0];
        if (first && first.geometry && first.geometry.location) {
            const loc = first.geometry.location;
            const lat = (typeof loc.lat === 'function') ? loc.lat() : loc.lat;
            const lng = (typeof loc.lng === 'function') ? loc.lng() : loc.lng;
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
            setAddressFromLatLng(loc);
            checkZone(lat, lng);
        }
        const bounds = new google.maps.LatLngBounds();
        places.forEach((place) => {
            if (!place.geometry || !place.geometry.location) {
                console.log("Returned place contains no geometry");
                return;
            }

            const { AdvancedMarkerElement } = google.maps.marker;
            searchMarkers.push(
                new AdvancedMarkerElement({
                    map,
                    title: place.name,
                    position: place.geometry.location,
                })
            );
            if (place.geometry.viewport) {
                bounds.union(place.geometry.viewport);
            } else {
                bounds.extend(place.geometry.location);
            }
        });
        map.fitBounds(bounds);
    });
}

function handleLocationError(browserHasGeolocation, infoWindow, pos) {
    infoWindow.setPosition(pos);
    infoWindow.setContent(
        browserHasGeolocation ?
            window.mapConfig.translations.selectZone :
            window.mapConfig.translations.geolocationError
    );
    infoWindow.open(map);
}

$(document).on('ready', function () {
    if (window.mapConfig.oldZoneId) {
        $('#choice_zones').trigger('change');
    }
});

$(document).ready(function () {
    var selectedZone = $('#choice_zones').val();
    if (selectedZone) {
        $('#choice_zones').trigger('change');
    }
});

$('#choice_zones').on('change', function () {
    checkModuleType();
    const latlngEl = document.getElementById('latlng');
    if (latlngEl) latlngEl.style.setProperty('display', 'flex', 'important');
    if (outOfZoneEl) outOfZoneEl.style.setProperty('display', 'block', 'important');

    if (!MAP_ENABLED || !(window.google && google.maps)) {
        // Map is disabled: skip all polygon/map rendering logic.
        return;
    }
    const isFromMap = $(this).data('from-map');
    $(this).data('from-map', false);

    const prevLat = document.getElementById('latitude').value;
    const prevLng = document.getElementById('longitude').value;

    // if (!isFromMap) {
    //     document.getElementById('latitude').value = '';
    //     document.getElementById('longitude').value = '';
    // }

    if (infoWindow) {
        infoWindow.close();
    }
    var id = $(this).val();
    if (!id) {
        if (zonePolygon) {
            zonePolygon.setMap(null);
            zonePolygon = null;
        }
        return;
    }
    $.get({
        url: window.mapConfig.urls.zoneCoordinates.replace(':coordinatesZoneId', id),
        dataType: 'json',
        success: function (data) {
            if (zonePolygon) {
                zonePolygon.setMap(null);
            }
            zonePolygon = new google.maps.Polygon({
                paths: data.coordinates,
                strokeColor: "#FF0000",
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: 'white',
                fillOpacity: 0,
            });
            zonePolygon.setMap(map);

            bounds = new google.maps.LatLngBounds();
            zonePolygon.getPaths().forEach(function (path) {
                path.forEach(function (latlng) {
                    bounds.extend(latlng);
                });
            });
            map.fitBounds(bounds);

            infoWindow = new google.maps.InfoWindow({
                content: window.mapConfig.translations.clickMap,
                position: bounds.getCenter(),
            });
            // infoWindow.open(map);
            if (!isFromMap) {
                map.setCenter(data.center);
            }

            const { oldLat, oldLng, oldAddress } = window.mapConfig;
            const hasOldCoords = !isNaN(oldLat) && !isNaN(oldLng);

            let latToCheck = null;
            let lngToCheck = null;

            if (hasOldCoords && !isFromMap && id == window.mapConfig.oldZoneId) {
                latToCheck = oldLat;
                lngToCheck = oldLng;
            } else if (prevLat && prevLng) {
                latToCheck = prevLat;
                lngToCheck = prevLng;
            }

            if (latToCheck && lngToCheck) {
                const latLngToCheck = new google.maps.LatLng(Number(latToCheck), Number(lngToCheck));
                if (google.maps.geometry.poly.containsLocation(latLngToCheck, zonePolygon)) {
                    document.getElementById('latitude').value = latToCheck;
                    document.getElementById('longitude').value = lngToCheck;
                    setAddressFromLatLng(latLngToCheck);

                    if (hasOldCoords && !isFromMap && id == window.mapConfig.oldZoneId) {
                        map.setCenter(latLngToCheck);
                    }

                    if (outOfZoneEl) outOfZoneEl.style.setProperty("display", "none", "important");
                }
            }

            google.maps.event.addListener(zonePolygon, 'click', function (mapsMouseEvent) {
                if (outOfZoneEl) outOfZoneEl.style.setProperty("display", "none", "important");
                infoWindow.close();
                if (myMarker) {
                    myMarker.setMap(null);
                }
                searchMarkers.forEach(m => m.map = null);
                searchMarkers = [];
                const { AdvancedMarkerElement } = google.maps.marker;
                myMarker = new AdvancedMarkerElement({
                    position: mapsMouseEvent.latLng,
                    map: map,
                    title: window.mapConfig.translations.selectedLocation
                });
                const coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                const obj = JSON.parse(coordinates);
                document.getElementById('latitude').value = obj['lat'];
                document.getElementById('longitude').value = obj['lng'];
                setAddressFromLatLng(mapsMouseEvent.latLng);
            });
        },
    });
});

document.addEventListener('keypress', function (e) {
    if (e.keyCode === 13 || e.which === 13) {
        e.preventDefault();
        return false;
    }
});

function checkZone(lat, lng) {
    checkModuleType();
    if (!MAP_ENABLED || !(window.google && google.maps)) {
        return;
    }
    $.get({
        url: window.mapConfig.urls.zoneGetZone,
        data: { lat: lat, lng: lng },
        dataType: 'json',
        success: function (data) {
            if (data.id) {
                $('#choice_zones').data('from-map', true).val(data.id).trigger('change');
                const latlngEl = document.getElementById('latlng');
                if (latlngEl) latlngEl.style.setProperty('display', 'flex', 'important');
                if (outOfZoneEl) outOfZoneEl.style.setProperty('display', 'none', 'important');
            } else {
                $('#choice_zones').val('').trigger('change');
                $('#latitude').val('');
                $('#longitude').val('');
                const latlngEl = document.getElementById('latlng');
                if (latlngEl) latlngEl.style.setProperty('display', 'none', 'important');
                if (outOfZoneEl) outOfZoneEl.style.setProperty('display', 'block', 'important');
                if (infoWindow) {
                    infoWindow.close();
                }
                infoWindow = new google.maps.InfoWindow({
                    content: window.mapConfig.translations.outOfZone,
                    position: { lat: lat, lng: lng },
                });
                // infoWindow.open(map);
            }
        },
    });
}


function initModuleSelect2() {
    $('#module_id').select2({
        ajax: {
            url: getAllModules,
            data: function (params) {
                return {
                    q: params.term,
                    page: params.page,
                    zone_id: $('#choice_zones').val()
                };
            },
            processResults: function (data) {
                return { results: data };
            },
            __port: function (params, success, failure) {
                let $request = $.ajax(params);
                $request.then(success);
                $request.fail(failure);
                return $request;
            }
        }
    });
}

function loadModuleType(moduleId) {
    $.ajax({
        url: getModuleType,
        method: 'GET',
        data: { id: moduleId, zone_id: $('#choice_zones').val() },
        success: function (response) {
            $('#show_sub_packages').html(response.view);

            if (response.module_type === 'rental') {
                $('#pickup-zone-container').show();
                $('.multiple-select2').prop('disabled', false);
                $('.module-select-time').html(
                    estimatedPickupText
                );
            } else {
                $('#pickup-zone-container').hide();
                $('.multiple-select2').prop('disabled', true);
                $('.module-select-time').html(
                    approxDeliveryText
                );
            }
        },
        error: function (xhr, status, error) {
            console.log("Error fetching module type:", error);
        }
    });
}

function checkModuleType() {
    if (checkModuleTypeUrl) {
        $.ajax({
            url: checkModuleTypeUrl,
            method: 'GET',
            data: { id: $('#module_id').val(), zone_id: $('#choice_zones').val() },
            success: function (response) {

                if (response.module_zone == false) {
                    $('#module_id').val('').trigger('change');
                    $('#pickup-zone-container').hide();
                    $('.multiple-select2').prop('disabled', true);
                    $('.module-select-time').html(
                        approxDeliveryText
                    );
                }
            },
            error: function (xhr, status, error) {
                console.log("Error fetching module type:", error);
            }
        });
    }
}

$(document).ready(function () {

    initModuleSelect2();
    initLeafletMap();

    $('#module_id').on('change', function () {
        const moduleId = $(this).val();
        loadModuleType(moduleId);
    });

});


