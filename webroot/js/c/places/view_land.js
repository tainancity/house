var map, marker, newBounds;
var loadedJson = {}, loadingFile = '';
$(function () {
    newBounds = new google.maps.LatLngBounds;
    map = new google.maps.Map(document.getElementById('mapCanvas'), {
        zoom: 18,
        scaleControl: true,
        navigationControl: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });
    if (place.PlaceLink) {
        for (k in place.PlaceLink) {
            $.getJSON(jsonBaseUrl + place.PlaceLink[k].Land.file, {}, function (r) {
                for (b in r.features) {
                    if (place.PlaceLink[k].Land.code == r.features[b].properties.AA49) {
                        for (k in r.features[b].geometry.coordinates) {
                            for (j in r.features[b].geometry.coordinates[k]) {
                                newBounds.extend(new google.maps.LatLng(r.features[b].geometry.coordinates[k][j][1], r.features[b].geometry.coordinates[k][j][0]));
                            }
                        }

                        map.data.addGeoJson(r.features[b]);
                    }
                }
            });
        }
        map.fitBounds(newBounds);
        if (!pointLatLng) {
            pointLatLng = newBounds.getCenter();
        }
        marker = new google.maps.Marker({
            position: pointLatLng,
            map: map,
            title: '土地'
        });
        map.setCenter(pointLatLng);
    } else if (pointLatLng) {
        marker = new google.maps.Marker({
            position: pointLatLng,
            map: map,
            title: '土地'
        });
        map.setCenter(pointLatLng);
    }
});