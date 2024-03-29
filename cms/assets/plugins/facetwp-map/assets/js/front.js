var facetwp_map, facetwp_markers = [], facetwp_markerCluster, facetwp_infowindow;

function facetwpMapInit() {
    var bounds = new google.maps.LatLngBounds();
    if (!facetwp_map) {
        facetwp_map = new google.maps.Map(document.getElementById('facetwp-map'), {});
        facetwp_infowindow = new google.maps.InfoWindow({
            content: ''
        });
    }

    // Add markers to the map.
    facetwp_markers = FWP.settings.map.locations.map(function (location, i) {
        //location.animation = google.maps.Animation.DROP;
        var marker = new google.maps.Marker(location);
        marker.addListener('click', function (e) {
            if (FWP.settings.map.locations[i].content) {
                facetwp_infowindow.setContent(FWP.settings.map.locations[i].content);
                facetwp_infowindow.open(facetwp_map, marker);
            }
        });

        bounds.extend(marker.position);
        return marker;
    });
    if (typeof MarkerClusterer !== 'undefined' && FWP.settings.map.config.group_markers) {

        // Add a marker clusterer to manage the markers.
        facetwp_markerCluster = new MarkerClusterer(facetwp_map, facetwp_markers,
            {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
    } else {
        facetwp_markers.map(function (marker) {
            marker.setMap(facetwp_map);
        });
    }

    //if lat/lng defaults are set set the map center when no markers exist
    if (
        'undefined' !== typeof FWP.settings.map.config.default_location_lat &&
        'undefined' !== typeof FWP.settings.map.config.default_location_lng &&
        0 === facetwp_markers.length
    ) {
        facetwp_map.setCenter(
            {
                lat: parseFloat(FWP.settings.map.config.default_location_lat),
                lng: parseFloat(FWP.settings.map.config.default_location_lng)
            }
        );
    } else {
        facetwp_map.fitBounds(bounds);
    }

    // set options
    facetwp_map.setOptions(FWP.settings.map.init);
}

function facetwpMapReset() {
    // remove markers if set.
    if (facetwp_markers.length) {
        facetwp_markers.map(function (marker) {
            marker.setMap(null);
        });
        facetwp_markers = [];
    }
    // clusteres
    if (typeof MarkerClusterer !== 'undefined' && facetwp_markerCluster) {
        facetwp_markerCluster.clearMarkers();
    }
}

(function($) {
    var facetwp_init_map = true;

    $(document).on('facetwp-loaded', function() {
        if (true === facetwp_init_map) {
            facetwpMapInit();
        }
        else{
            facetwp_init_map = true;
        }
    });

    $(document).on('facetwp-refresh', function() {
        // determine reload
        if (true === FWP.soft_refresh && 'all' === FWP.settings.map.config.result_count) {
            facetwp_init_map = false;
        }
        else{
            facetwpMapReset();
        }
    });
})(jQuery);
