<h2><?php echo $this->Html->link('任務', '/admin/tasks');?></h2>
<style>#map {height: 700px;;width:100%;}</style>
<img src='http://maps.google.com/mapfiles/ms/icons/green-dot.png'>空屋
<img src='http://maps.google.com/mapfiles/ms/icons/red-dot.png'>空地
<div id="map"></div>
<script>
var places = [
  <?php
  $i=0;
  foreach ($places as $place) {
	if($place["Place"]['latitude']!=""&&$place["Place"]['longitude']!="")
	{
		$model=$place["Place"]["model"]=="Door"?"空屋":"空地";
		$icon=$place["Place"]["model"]=="Door"?"http://maps.google.com/mapfiles/ms/icons/green-dot.png":"http://maps.google.com/mapfiles/ms/icons/red-dot.png";
		$place_detail='<h4>'.$place["Place"]['title'].'</h4>
		<div class="place_title">類型: </div><div class="place_content">'.$model.'</div><br>
		<div class="place_title">擁有人: </div><div class="place_content">'.$place["Place"]['owner'].'</div><br>
		<div class="place_title">擁有權: </div><div class="place_content">'.$place["Place"]['ownership'].'</div><br>
		<div class="place_title">面積: </div><div class="place_content">'.$place["Place"]['area'].'(平方公尺)</div><br>
		<div class="place_title">稽查單位: </div><div class="place_content">'.$place["Place"]['inspect'].'</div><br>
		<div class="place_title">開始列管日期: </div><div class="place_content">'.$place["Place"]['date_begin'].'</div><br>
		<div class="place_title">備註: </div><div class="place_content">'.$place["Place"]['note'].'</div><br>
		';
		$place_title=$place["Place"]['title'];
		$place_detail=preg_replace('/\s+/', '', $place_detail);
		$place_detail=str_replace("'", '', $place_detail);
		echo "['".$place_title."', ".$place["Place"]['latitude'].", ".$place["Place"]['longitude'].", '".$place_detail."','".$icon."'],";
	}
	$i++;
  }
  ?>
];

<?php if($i==0){echo 'alert("抱歉！本區域皆無設定任何地理座標");';}?>
function initMap() {
  var tainan = {lat: 23.1124639, lng: 120.1412117};
  var bounds = new google.maps.LatLngBounds();
  var map = new google.maps.Map(document.getElementById('map'), {
    zoom: 14,
    center: tainan
  });
  
  for (var i = 0; i < places.length; i++) {
	var place = places[i];
    var marker = new google.maps.Marker({
      position: {lat: place[1], lng: place[2]},
      map: map,
      title: place[0],
	  placedetail: place[3],
	  icon: place[4]
    });

	bounds.extend(marker.position);
	var infowindow = new google.maps.InfoWindow({
            content: "No data"
        });
	google.maps.event.addListener(marker,'click', function(){
          infowindow.setContent(this.placedetail);
          infowindow.open(map,this);
	});
  }
  map.fitBounds(bounds);

 
}

</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCWiufrXVQT6To9D8ZrE2dC1yuVPOaTG4I&signed_in=true&callback=initMap"></script>
