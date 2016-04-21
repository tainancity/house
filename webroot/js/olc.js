function dialogFull(linkObject, title) {
    if ($('#dialogFull').length == 0) {
        $('body').append('<div id="dialogFull"></div>');
        $('#dialogFull').dialog({
            autoOpen: false,
            width: 950,
            close: function (event, ui) {
                document.location.href = document.location.href;
            }
        });
    }
    if (typeof title == 'undefined') {
        if (typeof linkObject.rel == 'undefined') {
            title = '--';
        } else {
            title = linkObject.rel;
        }
    }
    $('#dialogFull').load(linkObject.href, null, function () {
        $(this).dialog('option', 'title', title).dialog('open');
    });
}

function dialogMessage(message) {
    if ($('#dialogMessage').length == 0) {
        $('body').append('<div id="dialogMessage"></div>');
        $('#dialogMessage').html(message).dialog({
            title: 'Message',
            autoOpen: true,
            width: 300
        });
    } else {
        $('#dialogMessage').html(message).dialog('open');
    }
}

function getLocation(hookMethod) {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(hookMethod);
    } else {
        alert("目前使用的瀏覽器不支援衛星定位功能");
    }
}