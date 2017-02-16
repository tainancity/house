<?php
if (!isset($url)) {
    $url = array();
}

if (!empty($foreignId) && !empty($foreignModel)) {
    $url = array($typeModel, $foreignModel, $foreignId);
}
?>

<div id="PlacesAdminIndex">
    <h2><?php
        if (!empty($foreignInfo['title'])) {
            switch ($foreignModel) {
                case 'Task':
                    echo implode(' > ', array(
                        $this->Html->link('任務', array('controller' => 'tasks')),
                        $foreignInfo['title'] . '相關' . (($typeModel === 'Door') ? '房屋' : '土地'),
                    ));
                    break;
                case 'Group':
                    echo implode(' > ', array(
                        $this->Html->link('群組', array('controller' => 'groups')),
                        $foreignInfo['title'] . '空屋',
                    ));
                    break;
            }
        }
        ?></h2>

	<div class="paging"><?php echo $this->element('paginator'); ?></div>
	<table class="table table-bordered">
            <tr>
				<th>任務</th>
				<th>名稱</th>
                <th>建檔時間</th>
                <th>訪視日期</th>
                <th>狀態</th>
                <th>操作人</th>
                <th>照片</th>
                <th>備註</th>
				<th>記錄</th>
				<?php if ($loginMember['group_id'] == 1) {?>
				<th>操作</th>
				<?php } ?>
            </tr>
            <?php
			//print_r($item);
            foreach ($item AS $k=> $log_a) {
				$log=$log_a['PlaceLog'];
                ?><tr>
					<td><?php echo $item[$k]['Place']['Task']['title']; ?></td>
					<td><?php echo $item[$k]['Place']['title']; ?></td>
                    <td><?php echo $log['created']; ?></td>
                    <td><?php echo $log['date_visited']; ?></td>
                    <td><?php echo $this->Olc->status[$log['status']]; ?></td>
                    <td><?php echo $item[$k]['Creator']['username']; ?></td>
                    <td><?php
                        if (!empty($log['basename'])) {
                            echo '<a href="' . $this->Media->url("original/{$log['dirname']}/{$log['basename']}") . '" target="_blank">';
                            echo $this->Media->embed("m/{$log['dirname']}/{$log['basename']}") . '</a>';
                        }
                        ?></td>
                    <td><?php echo nl2br($log['note']); ?></td>
					<td><?php echo nl2br($log['update_log']); ?></td>
					<?php if ($loginMember['group_id'] == 1) {?>
					<td>
					<?php
					if ($loginMember['group_id'] == 1) {
						echo $this->Html->link('刪除記錄(含照片)', array('controller' => 'PlaceLogs','action' => 'delete', $log['id'],'?' => array('place_id' => $item[$k]['Place']['id'])), array('class' => 'btn btn-default'), '確定要刪除？');
					}
					?>					
					</td>
					<?php } ?>
					
                </tr>
                <?php
            }
            ?>
        </table>
		<div class="paging"><?php echo $this->element('paginator'); ?></div>
</div>