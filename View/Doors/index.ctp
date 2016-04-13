<div id="DoorsIndex">
    <div class="input">
        <input type="text" class="col-md-12" id="doorInput" />
        <textarea class="col-md-12" id="doorList" rows="20">輸入住址,傳回住址,緯度,經度</textarea>
    </div>
    <div class="clearfix"></div>
    <br />
    <div class="input">
        批次處理：(一行輸入一個住址)
        <a href="#" class="btn btn-primary pull-right" id="btnImport">開始匯入</a>
        <textarea class="col-md-12" id="doorImport" rows="20"></textarea>
    </div>
    <div class="ajax-loader" style="display: none;">匯入中 - <span id="importStatus"></span></div>
    <script type="text/javascript">
        //<![CDATA[
        var queryUrl = '<?php echo $this->Html->url('/doors/q/'); ?>';
        $(function () {
            var currentTerm, resultCount = 0;
            $('input#doorInput').autocomplete({
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
                    $('textarea#doorList').append("\n" + currentTerm + ',' + ui.item.label + ',' + ui.item.latitude + ',' + ui.item.longitude);
                },
                minLength: 2
            });
            $('a#btnImport').click(function () {
                arrayOfLines = $('textarea#doorImport').val().match(/[^\r\n]+/g);
                $('textarea#doorImport').val('');
                $('div.ajax-loader').show();
                for (k in arrayOfLines) {
                    $.getJSON(queryUrl + arrayOfLines[k], {}, function (r) {
                        if (++resultCount >= arrayOfLines.length) {
                            $('div.ajax-loader').hide();
                        } else {
                            $('span#importStatus').html(resultCount + '/' + arrayOfLines.length);
                        }
                        if (r.result.length) {
                            $('textarea#doorList').append("\n" + r.queryString + ',' + r.result[0].label + ',' + r.result[0].latitude + ',' + r.result[0].longitude);
                        } else {
                            $('textarea#doorList').append("\n" + r.queryString + ',,,');
                        }
                    });
                }
                return false;
            });
        });
        //]]>
    </script>
</div>