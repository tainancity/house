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

    $('input#doorHelper').autocomplete({
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
            $('#HouseLatitude').val(ui.item.latitude);
            $('#HouseLongitude').val(ui.item.longitude);
            $('#HouseTitle').val(ui.item.label);
            $('#HouseDoorId').val(ui.item.id);
            marker.setPosition(point);
            map.setCenter(point);
        },
        minLength: 2
    });
});

function markerDrag(e) {
    $('#HouseLatitude').val(e.latLng.lat());
    $('#HouseLongitude').val(e.latLng.lng());
    $('#HouseDoorId').val('');
}