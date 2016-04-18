var map, marker;
$(function () {
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

    if (place.Land) {
        $.getJSON(jsonBaseUrl + place.Land.file, {}, function (r) {
            for (k in r.features) {
                if (place.Land.code == r.features[k].properties.AA49) {
                    map.data.addGeoJson(r.features[k]);
                }
            }
        });
    }

});