var map, marker;
var loadedJson = {}, loadingFile = '', currentObj = false;
$(function () {
    map = new google.maps.Map(document.getElementById('mapCanvas'), {
        zoom: 14,
        center: {lat: 23, lng: 121},
        scaleControl: true,
        navigationControl: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var currentTerm;
    $('input#landInput').autocomplete({
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

            }
        },
        minLength: 1
    });

});

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
	$('#lnglat').val(centerPoint.lng()+","+centerPoint.lat());
	$('#latlng').val(centerPoint.lat()+","+centerPoint.lng());
	$('#lat').val(centerPoint.lat());
    $('#lng').val(centerPoint.lng());
}