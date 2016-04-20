var map, marker;
$(function () {
    var resultCount = 0;
    $('a#btnImport').click(function () {
        arrayOfLines = $('textarea#doorImport').val().match(/[^\r\n]+/g);
        $('textarea#doorImport').val('');
        $('div#importResult').html('處理結果：<ul id="importResultLines"></ul>');
        $('div.ajax-loader').show();
        for (k in arrayOfLines) {
            if (arrayOfLines[k] === '') {
                continue;
            }
            $.getJSON(queryUrl + arrayOfLines[k], {}, function (r) {
                if (++resultCount >= arrayOfLines.length) {
                    $('div.ajax-loader').hide();
                } else {
                    $('span#importStatus').html(resultCount + '/' + arrayOfLines.length);
                }
                var data = {data: {
                        Place: {
                            model: 'Land',
                            title: r.queryString,
                            group_id: $('#PlaceGroupId').val(),
                            status: 1
                        }
                    }};
                if (r.result.length && r.result[0].code) {
                    data.data.Place.title = r.result[0].label;
                    data.data.Place.foreign_id = r.result[0].id;
                    data.data.Place.latitude = r.result[0].latitude;
                    data.data.Place.longitude = r.result[0].longitude;
                }
                $.post(addUrl, data, function (place) {
                    $('ul#importResultLines').append('<li><a href="' + viewUrl + place.id + '" target="_blank">' + place.title + '</a></li>');
                }, 'json');
            });
        }
        return false;
    });
});