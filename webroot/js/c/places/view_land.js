var map, marker;
var loadedJson = {}, loadingFile = '';
$(function () {
    if (place.Land) {
        map = new google.maps.Map(document.getElementById('mapCanvas'), {
            zoom: 18,
            scaleControl: true,
            navigationControl: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        $.getJSON(jsonBaseUrl + place.Land.file, {}, function (r) {
            for (k in r.features) {
                if (place.Land.code == r.features[k].properties.AA49) {
                    var newBounds = new google.maps.LatLngBounds;
                    for (k in obj.geometry.coordinates) {
                        for (j in obj.geometry.coordinates[k]) {
                            newBounds.extend(new google.maps.LatLng(obj.geometry.coordinates[k][j][1], obj.geometry.coordinates[k][j][0]));
                        }
                    }
                    map.fitBounds(newBounds);
                    if (!pointLatLng) {
                        var centerPoint = newBounds.getCenter();
                        marker = new google.maps.Marker({
                            position: centerPoint,
                            map: map,
                            title: '土地'
                        });
                    }
                }
            }
        });
    } else if (pointLatLng) {
        map = new google.maps.Map(document.getElementById('mapCanvas'), {
            zoom: 18,
            center: pointLatLng,
            scaleControl: true,
            navigationControl: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        marker = new google.maps.Marker({
            position: pointLatLng,
            map: map,
            title: '土地'
        });
    }
});