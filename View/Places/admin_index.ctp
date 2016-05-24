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
        <?php echo $this->Html->link('新增', array_merge($url, array('action' => 'add')), array('class' => 'btn btn-default')); ?>
        <?php echo $this->Html->link('匯入', array('action' => 'import', $typeModel, $foreignId), array('class' => 'btn btn-default')); ?>
    </div>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <table class="table table-bordered" id="PlacesAdminIndexTable">
        <thead>
            <tr>
                <?php if ($foreignModel !== 'Group') { ?>
                    <th><?php echo $this->Paginator->sort('Place.group_id', '群組', array('url' => $url)); ?></th>
                <?php } ?>
                <?php if ($foreignModel !== 'Task') { ?>
                    <th><?php echo $this->Paginator->sort('Place.task_id', '任務', array('url' => $url)); ?></th>
                <?php } ?>
                <th>名稱</th>
                <th><?php echo $this->Paginator->sort('Place.status', '狀態', array('url' => $url)); ?></th>
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
                ?>
                <tr<?php echo $class; ?>>
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
                            <?php echo $this->Html->link('檢視', array('action' => 'view', $item['Place']['id']), array('class' => 'btn btn-default')); ?>
                            <?php echo $this->Html->link('編輯', array('action' => 'edit', $item['Place']['id']), array('class' => 'btn btn-default')); ?>
                            <?php echo $this->Html->link('刪除', array('action' => 'delete', $item['Place']['id']), array('class' => 'btn btn-default'), '確定要刪除？'); ?>
                        </div>
                    </td>
                </tr>
            <?php } // End of foreach ($items as $item) {   ?>
        </tbody>
    </table>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <div id="PlacesAdminIndexPanel"></div>
</div>