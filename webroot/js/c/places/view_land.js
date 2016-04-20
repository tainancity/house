var map, marker;
var loadedJson = {}, loadingFile = '';
$(function () {
    map = new google.maps.Map(document.getElementById('mapCanvas'), {
        zoom: 18,
        scaleControl: true,
        navigationControl: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });
    if (place.Land) {
        $.getJSON(jsonBaseUrl + place.Land.file, {}, function (r) {
            for (k in r.features) {
                if (place.Land.code == r.features[k].properties.AA49) {
                    console.log(place);
                    console.log(r.features[k].properties);
                    var newBounds = new google.maps.LatLngBounds;
                    for (k in r.features[k].geometry.coordinates) {
                        for (j in r.features[k].geometry.coordinates[k]) {
                            newBounds.extend(new google.maps.LatLng(r.features[k].geometry.coordinates[k][j][1], r.features[k].geometry.coordinates[k][j][0]));
                        }
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
                    map.data.addGeoJson(r.features[k]);
                }
            }
        });
    } else if (pointLatLng) {
        marker = new google.maps.Marker({
            position: pointLatLng,
            map: map,
            title: '土地'
        });
        map.setCenter(pointLatLng);
    }
});