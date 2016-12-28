<?php
if (!isset($url)) {
    $url = array();
}
?>
<div id="TasksAdminIndex">
    <h2>臺南市空地空屋列管資料</h2>
    <div class="btn-group">
    </div>
	
    <div>
	<?php
	$i = 0;
	foreach ($items as $item) {
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
		if( strpos( $item['Task']['title'],'test' ) == false ) {
		?>
		<div <?php echo $class; ?> style='float:left;width:24%;padding:0.5%;margin:0.5%;border:1px solid #ccc'>

			<div style='text-align:center;font-size:32px;padding:8% 1%;background:#999;color:#fff'><?php echo $item['Task']['title']; ?></div>
			
		
			<div style='text-align:center;font-size:16px;padding:1% 1%;'><?php echo $this->Html->link('地圖', array('action' => 'map', $item['Task']['id']), array('class' => 'btn btn-default'));?></div>
				<div style='text-align:center;font-size:12px;padding:1% 1%;color:#333'>更新日期：<?php echo $item['Task']['created']; ?></div>

		</div>
	<?php 
		}
	}	// End of foreach ($items as $item) {   
	?>
    </div>
	<div style='clear:both'></div>
</div>