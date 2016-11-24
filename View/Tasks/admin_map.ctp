<h2><?php echo $this->Html->link('任務', '/admin/tasks');?></h2>
<style>#map {height: 700px;;width:100%;}</style>
<div id="map"></div>
<script>
var places = [
  <?php
  $i=0;
  foreach ($places as $place) {
	if($place["Place"]['latitude']!=""&&$place["Place"]['longitude']!="")
	{
		$model=$place["Place"]["model"]=="Door"?"空屋":"空地";
		$place_detail='<h4>'.$place["Place"]['title'].'</h4>
		<div class="place_title">類型</div><div class="place_content">'.$model.'</div><br>
		<div class="place_title">擁有人</div><div class="place_content">'.$place["Place"]['owner'].'</div><br>
		<div class="place_title">擁有權</div><div class="place_content">'.$place["Place"]['ownership'].'</div><br>
		<div class="place_title">備註</div><div class="place_content">'.$place["Place"]['note'].'</div><br>
		';
		$place_detail=preg_replace('/\s+/', '', $place_detail);
		echo "['".$place["Place"]['title']."', ".$place["Place"]['latitude'].", ".$place["Place"]['longitude'].", '".$place_detail."'],";
	}
	$i++;
  }
  ?>
];
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
