var map, marker, theBounds;
var loadedJson = {}, loadingFile = '', currentObj = {}, currentItem = {};
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
    theBounds = new google.maps.LatLngBounds;
    map.data.addListener('click', mapObjClick);

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
            currentItem = ui.item;
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
			else if(currentItem.value.length>12)
			{//12 is key-in address length (7 section name+5 land code)
				//console.log(currentItem.value+"@"+currentItem.value.length);
				showAddress(currentItem.value);
				
			}
        },
        minLength: 1
    });
    $('#PlaceLogDateVisited').datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $('#PlaceDateBegin').datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $('#PlaceAdoptBegin').datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $('#PlaceAdoptEnd').datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $('#PlaceAdoptClosed').datepicker({
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
    var objKey = obj.properties.UNIT + obj.properties.AA48 + obj.properties.AA49;
    if (!currentObj[objKey] || currentObj[objKey] === false) {
        currentObj[objKey] = map.data.addGeoJson(obj);
        for (k in obj.geometry.coordinates) {
            for (j in obj.geometry.coordinates[k]) {
                theBounds.extend(new google.maps.LatLng(obj.geometry.coordinates[k][j][1], obj.geometry.coordinates[k][j][0]));
            }
        }
        map.fitBounds(theBounds);
        var centerPoint = theBounds.getCenter();
        marker.setPosition(centerPoint);
        $('#PlaceLatitude').val(centerPoint.lat());
        $('#PlaceLongitude').val(centerPoint.lng());
        if($('#PlaceTitle').val() === '') {
            $('#PlaceTitle').val(currentItem.label);
        }
        var objItem = $('<a class="btnMapItem btn btn-default" id="btn' + objKey + '" data-id="' + objKey + '">' + currentItem.label + '</a>');
        objItem.click(btnObjClick);
        objItem.append('<input type="hidden" name="data[PlaceLink][]" value="' + currentItem.id + '" />');
        $('#mapItems').append(objItem);
    }
}
function showAddress(addr) {
    var objItem = $('<a class="btnMapItem btn btn-default" id="btn' + currentItem.btn_id + '" data-id="' + currentItem.btn_id  + '">' + currentItem.label + '</a>');
	objItem.click(btnObjClick);
	objItem.append('<input type="hidden" name="data[PlaceLink][]" value="' + currentItem.id + '" />');
	$('#mapItems').append(objItem);
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

function mapObjClick(e) {
    var objKey = e.feature.getProperty('UNIT') + e.feature.getProperty('AA48') + e.feature.getProperty('AA49');
    for (k in currentObj[objKey]) {
        map.data.remove(currentObj[objKey][k]);
    }
    currentObj[objKey] = false;
    $('#btn' + objKey).remove();
}

function btnObjClick() {
    var objKey = $(this).attr('data-id');
    for (k in currentObj[objKey]) {
        map.data.remove(currentObj[objKey][k]);
    }
    currentObj[objKey] = false;
    $(this).remove();
}