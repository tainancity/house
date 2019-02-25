var map, marker, theBounds;
var loadedJson = {}, loadingFile = '', currentObj = {}, currentItem = {};
$(function () {
	
	var timeout;
	$(window).scroll(function() {
	  clearTimeout(timeout);  
	  timeout = setTimeout(function() {
		  var error="";
		  if($( "[name='data[Place][latitude]']" ).val()>90 || $( "[name='data[Place][latitude]']" ).val()<-90 )
		  {
			  error="緯度須在-90~90度內.";
		  }
		  if($( "[name='data[Place][longitude]']" ).val()>180 || $( "[name='data[Place][longitude]']" ).val()<-180 )
		  {
			  error+="經度須在-180~180度內.";
		  }
		  if(error!="")
		  {
			  alert("注意!錯誤:"+error);
		  }
		}, 50);
	 
	});
	
    map = new google.maps.Map(document.getElementById('mapCanvas'), {
        zoom: 14,
        scaleControl: true,
        navigationControl: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });
    if (!pointLatLng) {
        pointLatLng = new google.maps.LatLng(22.989992, 120.184843);
    }
    theBounds = new google.maps.LatLngBounds;
    marker = new google.maps.Marker({
        position: pointLatLng,
        draggable: true,
        map: map,
        title: '土地'
    });
    marker.addListener('dragend', markerDrag);
    map.data.addListener('click', mapObjClick);
    map.setCenter(pointLatLng);
    if (place.Land) {
        $.getJSON(jsonBaseUrl + place.Land.file, {}, function (r) {
            loadedJson[loadingFile] = r;
            for (k in r.features) {
                if (place.Land.code == r.features[k].properties.AA49) {
                    if ($('#PlaceLatitude').val() === '') {
                        showJson(r.features[k]);
                    } else {
                        showJson(r.features[k], false);
                    }
                }
            }
        });
    }
    if (place.PlaceLink) {
        $.ajaxSetup({
            async: false
        });
        for (k in place.PlaceLink) {
            currentItem = {
                id: place.PlaceLink[k].Land.id,
                label: place.PlaceLink[k].Section.name + place.PlaceLink[k].Land.code
            };
			if(place.PlaceLink[k].Land.file)
			{
				if (!loadedJson[place.PlaceLink[k].Land.file]) {
					loadingFile = place.PlaceLink[k].Land.file;
					$.getJSON(jsonBaseUrl + place.PlaceLink[k].Land.file, {}, function (r) {
						loadedJson[loadingFile] = r;
						for (i in r.features) {
							if (place.PlaceLink[k].Land.code == r.features[i].properties.AA49) {
								showJson(r.features[i]);
							}
						}
					});
				}
				else {
					for (i in loadedJson[place.PlaceLink[k].Land.file].features) {
						if (place.PlaceLink[k].Land.code == loadedJson[place.PlaceLink[k].Land.file].features[i].properties.AA49) {
							showJson(loadedJson[place.PlaceLink[k].Land.file].features[i]);
						}
					}
				}
			}
			else 
			{//no-match-in-json's land code					
				showAddress(currentItem.value);
			}
        }
        map.fitBounds(theBounds);
        if (!pointLatLng) {
            pointLatLng = theBounds.getCenter();
        }
        marker = new google.maps.Marker({
            position: pointLatLng,
            map: map,
            title: '土地'
        });
        map.setCenter(pointLatLng);
    }

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
            currentItem = ui.item;
            if (ui.item.file) {
                if (!loadedJson[ui.item.file]) {
                    loadingFile = ui.item.file;
                    $.getJSON(jsonBaseUrl + ui.item.file, {}, function (r) {
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
				if($('#PlaceTitle').val()=="")
				{
					$('#PlaceTitle').val(ui.item.label);
				}
                $('#PlaceForeignId').val(ui.item.id);
            }
			else if(currentItem.value.length>12)
			{//12 is key-in address length (7 section name+5 land code)
				//console.log(currentItem.value+"@"+currentItem.value.length);
				showAddress(currentItem.value);
				
			}
        },
        minLength: 1
    });
    $('#PlaceLogDateVisited').datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $('#PlaceDateBegin').datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $('#PlaceAdoptBegin').datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $('#PlaceAdoptEnd').datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $('#PlaceAdoptClosed').datepicker({
        dateFormat: 'yy-mm-dd'
    });
});
function markerDrag(e) {
    $('#PlaceLatitude').val(e.latLng.lat());
    $('#PlaceLongitude').val(e.latLng.lng());
}

function showJson(obj, fillForm) {
    var objKey = obj.properties.UNIT + obj.properties.AA48 + obj.properties.AA49;
    if (!currentObj[objKey] || currentObj[objKey] === false) {
        currentObj[objKey] = map.data.addGeoJson(obj);
        for (k in obj.geometry.coordinates) {
            for (j in obj.geometry.coordinates[k]) {
                theBounds.extend(new google.maps.LatLng(obj.geometry.coordinates[k][j][1], obj.geometry.coordinates[k][j][0]));
            }
        }
        map.fitBounds(theBounds);
        var centerPoint = theBounds.getCenter();
        marker.setPosition(centerPoint);
        if (false !== fillForm) {
            $('#PlaceLatitude').val(centerPoint.lat());
            $('#PlaceLongitude').val(centerPoint.lng());
            if ($('#PlaceTitle').val() === '' && currentItem.label) {
                $('#PlaceTitle').val(currentItem.label);
            }
        }
        var objItem = $('<a class="btnMapItem btn btn-default" id="btn' + objKey + '" data-id="' + objKey + '">' + currentItem.label + '</a>');
        objItem.click(btnObjClick);
        objItem.append('<input type="hidden" name="data[PlaceLink][]" value="' + currentItem.id + '" />');
        $('#mapItems').append(objItem);
    }
}

function showAddress(addr) {
    var objItem = $('<a class="btnMapItem btn btn-default" id="btn' + currentItem.btn_id + '" data-id="' + currentItem.btn_id  + '">' + currentItem.label + '</a>');
	objItem.click(btnObjClick);
	objItem.append('<input type="hidden" name="data[PlaceLink][]" value="' + currentItem.id + '" />');
	$('#mapItems').append(objItem);
}

function mapObjClick(e) {
    var objKey = e.feature.getProperty('UNIT') + e.feature.getProperty('AA48') + e.feature.getProperty('AA49');
    for (k in currentObj[objKey]) {
        map.data.remove(currentObj[objKey][k]);
    }
    currentObj[objKey] = false;
    $('#btn' + objKey).remove();
}

function btnObjClick() {
    var objKey = $(this).attr('data-id');
    for (k in currentObj[objKey]) {
        map.data.remove(currentObj[objKey][k]);
    }
    currentObj[objKey] = false;
    $(this).remove();
}