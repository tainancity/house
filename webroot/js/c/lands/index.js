var map, marker;
$(function () {
    map = new google.maps.Map(document.getElementById('mapCanvas'), {
        zoom: 14,
        center: {lat: 23, lng: 121},
        scaleControl: true,
        navigationControl: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });
});