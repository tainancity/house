<div id="LandsIndex">
    <div class="input">
        <input type="text" class="col-md-12" id="landInput" />
        <div id="mapCanvas" class="col-md-12" style="height: 600px;"></div>
    </div>
    <script type="text/javascript">
        //<![CDATA[
        var queryUrl = '<?php echo $this->Html->url('/lands/q/'); ?>';
        var loadedJson = {}, loadingFile = '', currentObj = false;
        $(function () {
            var currentTerm, resultCount = 0;
            $('input#landInput').autocomplete({
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
                    if (ui.item.file) {
                        if (!loadedJson[ui.item.file]) {
                            loadingFile = ui.item.file;
                            $.getJSON('http://localhost/~kiang/tainan_shp/json/' + ui.item.file, {}, function (r) {
                                loadedJson[loadingFile] = r;
                                for (k in r.features) {
                                    if (ui.item.code == r.features[k].properties.AA49) {
                                        showJson(r.features[k]);
                                    }
                                }
                            });
                        } else {
                            for (k in loadedJson[ui.item.file].features) {
                                if (ui.item.code == loadedJson[ui.item.file].features[k].properties.AA49) {
                                    showJson(loadedJson[ui.item.file].features[k]);
                                }
                            }
                        }

                    }
                },
                minLength: 1
            });
        });
        function showJson(obj) {
            if (currentObj) {
                for (k in currentObj) {
                    map.data.remove(currentObj[k]);
                }
            }
            var newBounds = new google.maps.LatLngBounds;
            currentObj = map.data.addGeoJson(obj);
            for (k in obj.geometry.coordinates) {
                for (j in obj.geometry.coordinates[k]) {
                    newBounds.extend(new google.maps.LatLng(obj.geometry.coordinates[k][j][1], obj.geometry.coordinates[k][j][0]));
                }
            }
            map.fitBounds(newBounds);
        }
        //]]>
    </script>
</div>
<?php
$this->Html->script('http://maps.google.com/maps/api/js?sensor=false', array('inline' => false));
$this->Html->script('c/lands/index', array('inline' => false));
