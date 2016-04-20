var map, marker;
$(function () {
    if (pointLatLng) {
        map = new google.maps.Map(document.getElementById('mapCanvas'), {
            zoom: 14,
            center: pointLatLng,
            scaleControl: true,
            navigationControl: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        marker = new google.maps.Marker({
            position: pointLatLng,
            map: map,
            title: '房屋'
        });
    }
});