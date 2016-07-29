var map, marker;
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
        title: '房屋'
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
            var point = new google.maps.LatLng(ui.item.latitude, ui.item.longitude);
            $('#PlaceLatitude').val(ui.item.latitude);
            $('#PlaceLongitude').val(ui.item.longitude);
            $('#PlaceTitle').val(ui.item.label);
            $('#PlaceForeignId').val(ui.item.id);
            marker.setPosition(point);
            map.setCenter(point);
        },
        minLength: 2
    });
    $('#PlaceLogDateVisited').datepicker({
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
    $('#PlaceForeignId').val('');
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