var map, marker, autocomplete;
var pointLatLng = new google.maps.LatLng(22.96467282, 120.23338614);
$(function () {
    $('#addTracker').click(function () {
        $('div#trackerForm').show();
        $('div#mapForm').hide();
        return false;
    });
    $('#mapTracker').click(function () {
        $('div#trackerForm').hide();
        $('div#mapForm').show();
        mapInit();
        return false;
    });
    $('#placeQuery').autocomplete({
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
            var data = {
                data: {
                    Tracker: {
                        place_id: ui.item.id,
                        group_id: ui.item.group_id
                    }
                }
            };
            $.post(addUrl, data, function () {
                $('div#TrackersAdminIndex').parent().load(indexUrl);
            });
        },
        minLength: 1
    });
    $('a#markerPush').click(function () {
        var pos = marker.getPosition();
        var meters = $('input#rectMeters').val();
        $.getJSON(importUrl + '/' + meters + '/' + pos.lat() + '/' + pos.lng(), {}, function () {
            $('div#TrackersAdminIndex').parent().load(indexUrl);
        });
        return false;
    });
})

function mapInit() {
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
        map: map
    });

    autocomplete = new google.maps.places.Autocomplete(
            document.getElementById('mapQuery'), {
        componentRestrictions: {country: 'tw'}
    });
    places = new google.maps.places.PlacesService(map);
    autocomplete.addListener('place_changed', onPlaceChanged);
}

function onPlaceChanged() {
    var place = autocomplete.getPlace();
    if (place.geometry) {
        marker.setPosition(place.geometry.location);
        map.panTo(place.geometry.location);
        map.setZoom(15);
    }
}
