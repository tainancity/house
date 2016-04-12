<div id="DoorsIndex">
    <div class="input">
        <input type="text" class="col-md-12" id="doorInput" />
        <textarea class="col-md-12" id="doorList" rows="20">住址,緯度,經度</textarea>
    </div>
    <script type="text/javascript">
        //<![CDATA[
        $(function () {
            $('input#doorInput').autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: "<?php echo $this->Html->url('/doors/q/'); ?>" + request.term,
                        dataType: "json",
                        data: {},
                        success: function (data) {
                            response(data);
                        }
                    });
                },
                select: function (event, ui) {
                    $('textarea#doorList').append("\n" + ui.item.label + ',' + ui.item.latitude + ',' + ui.item.longitude);
                },
                minLength: 2
            });
        });
        //]]>
    </script>
</div>