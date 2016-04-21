var map, marker;
var loadedJson = {}, loadingFile = '', currentObj = false;
$(function () {
    var pointLatLng = new google.maps.LatLng(23.01, 120.22);
    map = new google.maps.Map(document.getElementById('mapCanvas'), {
        zoom: 14,
        center: pointLatLng,
        scaleControl: true,
        navigationControl: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });
    marker = new google.maps.Marker({
        position: pointLatLng,
        draggable: true,
        map: map,
        title: '土地'
    });
    marker.addListener('dragend', markerDrag);

    $('input#mapHelper').autocomplete({
        source: function (request, response) {
            currentTerm = request.term;
            $.ajax({
                url: queryUrl + request.term,
                dataType: "json",
                data: {},
                success: function (data) {
                    response(data.result);
                }
            });
        },
        select: function (event, ui) {
            if (ui.item.file) {
                if (!loadedJson[ui.item.file]) {
                    loadingFile = ui.item.file;
                    $.getJSON(jsonBaseUrl + ui.item.file, {}, function (r) {
                        loadedJson[loadingFile] = r;
                        for (k in r.features) {
                            if (ui.item.code == r.features[k].properties.AA49) {
                                showJson(r.features[k]);
                            }
                        }
                    });
                } else {
                    for (k in loadedJson[ui.item.file].features) {
                        if (ui.item.code == loadedJson[ui.item.file].features[k].properties.AA49) {
                            showJson(loadedJson[ui.item.file].features[k]);
                        }
                    }
                }
                $('#PlaceTitle').val(ui.item.label);
                $('#PlaceForeignId').val(ui.item.id);
            }
        },
        minLength: 1
    });
    $('#PlaceLogDateVisited').datepicker({
        dateFormat: 'yy-mm-dd'
    });
    
    $('a#geoInput').click(function () {
        getLocation(placeLocation);
        return false;
    });
    $('a#geoGoogle').click(function () {
        if (address = prompt('請輸入住址或座標')) {
            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({'address': address}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    map.setCenter(results[0].geometry.location);
                    marker.setPosition(results[0].geometry.location);
                    $('input#PlaceTitle').val(results[0].formatted_address);
                    $('#PlaceLatitude').val(results[0].geometry.location.lat());
                    $('#PlaceLongitude').val(results[0].geometry.location.lng());
                } else {
                    alert("輸入住址找不到座標");
                }
            });
        }
        return false;
    });
});

function markerDrag(e) {
    $('#PlaceLatitude').val(e.latLng.lat());
    $('#PlaceLongitude').val(e.latLng.lng());
}

function showJson(obj) {
    if (currentObj) {
        for (k in currentObj) {
            map.data.remove(currentObj[k]);
        }
    }
    var newBounds = new google.maps.LatLngBounds;
    currentObj = map.data.addGeoJson(obj);
    for (k in obj.geometry.coordinates) {
        for (j in obj.geometry.coordinates[k]) {
            newBounds.extend(new google.maps.LatLng(obj.geometry.coordinates[k][j][1], obj.geometry.coordinates[k][j][0]));
        }
    }
    map.fitBounds(newBounds);
    var centerPoint = newBounds.getCenter();
    marker.setPosition(centerPoint);
    $('#PlaceLatitude').val(centerPoint.lat());
    $('#PlaceLongitude').val(centerPoint.lng());
}

function placeLocation(pos) {
    var point = new google.maps.LatLng(pos.coords.latitude, pos.coords.longitude);
    var geocoder = new google.maps.Geocoder();
    $('#PlaceLatitude').val(pos.coords.latitude);
    $('#PlaceLongitude').val(pos.coords.longitude);
    marker.setPosition(point);
    map.setCenter(point);
    geocoder.geocode({'location': point}, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            $('input#PlaceTitle').val(results[0].formatted_address);
        } else {
            alert("輸入住址找不到座標");
        }
    });
}