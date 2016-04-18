var map, marker;
$(function () {
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
});

function markerDrag(e) {
    $('#PlaceLatitude').val(e.latLng.lat());
    $('#PlaceLongitude').val(e.latLng.lng());
}