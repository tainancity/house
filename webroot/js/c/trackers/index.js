$(function () {
    $('#addTracker').click(function () {
        $('div#trackerForm').show();
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
})