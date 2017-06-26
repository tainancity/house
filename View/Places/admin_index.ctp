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
    <div class="btn-group">
		<form action="" method="post" class="form-inline">	
        <?php echo $this->Html->link('新增', array_merge($url, array('action' => 'add')), array('class' => 'btn btn-default')); ?>
        <?php echo $this->Html->link('匯入', array('action' => 'import', $typeModel, $foreignId), array('class' => 'btn btn-default'));
        if($typeModel === 'Land') {
            echo $this->Html->link('匯入空地', array('action' => 'import_land', $foreignId), array('class' => 'btn btn-default'));
        } else {
            echo $this->Html->link('匯入空屋', array('action' => 'import_door', $foreignId), array('class' => 'btn btn-default'));
        }
        ?>
		
		<div class="form-group"> 搜尋:
			<label for="srch_title">路段名稱</label> 
			<?php echo $this->Form->text('srch_title', ['class' => 'form-control','value' => $GET_title,'size'=>10]); ?>
			
			<label for="srch_title">| 地段</label> 
			<?php echo $this->Form->text('srch_section', ['class' => 'form-control','value' => $GET_section,'placeholder'=>'格式：[安平]石門段','size'=>12]); ?>
			<label for="srch_title">&地號</label> 
			<?php echo $this->Form->text('srch_code', ['class' => 'form-control','value' => $GET_code,'placeholder'=>'格式：00140000','size'=>12]); ?>
			(地段號皆需填寫)
		
		</div>
		<input type="submit" name="btn" id="btn" value="搜尋" class="btn btn-default" />
		</form>
    </div>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
	
	<?php echo $this->Form->create(null, array('url' => array('controller' => 'Places', 'action' => 'delete_place_batch', $foreignId))); ?>
	<input type="submit" name="btn_del" id="btn_del" value="批次刪除" class="btn btn-default" />
    <table class="table table-bordered" id="PlacesAdminIndexTable">
        <thead>
            <tr>
				<th><input type="checkbox" name="all" onclick="check_all(this,'check_place_id[]')" /></th>
                <?php if ($foreignModel !== 'Group') { ?>
                    <th><?php echo $this->Paginator->sort('Place.group_id', '群組', array('url' => $url)); ?></th>
                <?php } ?>
                <?php if ($foreignModel !== 'Task') { ?>
                    <th><?php echo $this->Paginator->sort('Place.task_id', '任務', array('url' => $url)); ?></th>
                <?php } ?>
                <th>名稱</th>
                <th>狀態</th>
                <th>待改善情形</th>
                <th><?php echo $this->Paginator->sort('Place.modified', '更新時間', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Place.modified_by', '更新人', array('url' => $url)); ?></th>
                <th class="actions">操作</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            foreach ($items as $item) {
                $class = null;
                if ($i++ % 2 == 0) {
                    $class = ' class="altrow"';
                }
				if($item['Place']['latitude']==null)
				{
					$no_lat_lng_alert='style="background:#FFCFCF"';
				}
				else
				{
					$no_lat_lng_alert='';
				}
                ?>
                <tr<?php echo $class; ?> <?php echo $no_lat_lng_alert; ?>>
					<td>
					<input type="checkbox" name ="check_place_id[]" value="<?php echo  $item['Place']['id'];  ?>">
					</td>
                    <?php if ($foreignModel !== 'Group') { ?>
                        <td><?php
                            echo $groups[$item['Place']['group_id']];
                            ?></td>
                    <?php } ?>
                    <?php if ($foreignModel !== 'Task') { ?>
                        <td><?php
                            echo isset($tasks[$item['Place']['task_id']]) ? $tasks[$item['Place']['task_id']] : '--';
                            ?></td>
                    <?php } ?>
                    <td><?php
                        echo $item['Place']['title'];
                        ?></td>
                    <td><?php
                        echo $this->Olc->status[$item['Place']['status']];
                        ?></td>
                    <td><?php
                        echo $this->Olc->issue[$item['Place']['issue']];
                        ?></td>
                    <td><?php
                        echo $item['Place']['modified'];
                        ?></td>
                    <td><?php
                        echo $item['Modifier']['username'];
                        ?></td>
                    <td>
                        <div class="btn-group">
                            <?php echo $this->Html->link('檢視', array('action' => 'view', $item['Place']['id']), array('class' => 'btn btn-default','target'=>'_blank')); ?>
                            <?php echo $this->Html->link('編輯', array('action' => 'edit', $item['Place']['id']), array('class' => 'btn btn-default','target'=>'_blank')); ?>
                            <?php echo $this->Html->link('刪除', array('action' => 'delete', $item['Place']['id'],'?' => array('taskId' => $item['Place']['task_id'])), array('class' => 'btn btn-default'), '確定要刪除？'); ?>
                        </div>
                    </td>
                </tr>
            <?php } // End of foreach ($items as $item) {   ?>
        </tbody>
    </table>
	<?php   echo $this->Form->end(); ?>
	
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <div id="PlacesAdminIndexPanel"></div>
</div>