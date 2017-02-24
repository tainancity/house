<div id="LandsIndex">
    <div class="input">
        輸入地段號(格式：[中西]保安段00140000)<input type="text" class="col-md-12" id="landInput" placeholder="搜尋格式：[中西]保安段00140000" />
		<div style="margin:10px" class="col-md-12">
		經緯度:<input type="text"  id="lnglat" placeholder="自動產生經緯度" /><br>
		緯經度:<input type="text"  id="latlng" placeholder="自動產生緯經度" /><br>
		經度:<input type="text"  id="lng" placeholder="自動產生經度" />
		緯度:<input type="text"  id="lat" placeholder="自動產生緯度" />
			
		</div>
        <div id="mapCanvas" class="col-md-12" style="height: 600px;"></div>
    </div>
    <script type="text/javascript">
        var queryUrl = '<?php echo $this->Html->url('/lands/q/'); ?>';
        var jsonBaseUrl = '<?php echo $this->Html->url(Configure::read('jsonBaseUrl')); ?>';
    </script>
</div>
<?php
$this->Html->script('https://maps.google.com/maps/api/js?key=AIzaSyCWiufrXVQT6To9D8ZrE2dC1yuVPOaTG4I&language=zh-tw', array('inline' => false));
$this->Html->script('c/lands/index', array('inline' => false));
