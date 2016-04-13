<?php
if (!isset($url)) {
    $url = array();
}

if (!empty($foreignId) && !empty($foreignModel)) {
    $url = array($foreignModel, $foreignId);
}
?>
<div id="HousesAdminIndex">
    <h2><?php
        if (!empty($foreignInfo['title'])) {
            switch ($foreignModel) {
                case 'Task':
                    echo implode(' > ', array(
                        $this->Html->link('任務', array('controller' => 'tasks')),
                        $foreignInfo['title'] . '相關房屋',
                    ));
                    break;
                case 'Door':
                    echo implode(' > ', array(
                        $this->Html->link('門牌', array('controller' => 'doors')),
                        $foreignInfo['title'] . '相關房屋',
                    ));
                    break;
                case 'Group':
                    echo implode(' > ', array(
                        $this->Html->link('群組', array('controller' => 'groups')),
                        $foreignInfo['title'] . '相關房屋',
                    ));
                    break;
            }
        }
        ?></h2>
    <div class="btn-group">
        <?php echo $this->Html->link('新增', array_merge($url, array('action' => 'add')), array('class' => 'btn btn-default')); ?>
    </div>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <table class="table table-bordered" id="HousesAdminIndexTable">
        <thead>
            <tr>
                <?php if ($foreignModel !== 'Group') { ?>
                    <th><?php echo $this->Paginator->sort('House.group_id', '群組', array('url' => $url)); ?></th>
                <?php } ?>
                <?php if ($foreignModel !== 'Task') { ?>
                    <th><?php echo $this->Paginator->sort('House.task_id', '任務', array('url' => $url)); ?></th>
                <?php } ?>
                <th>名稱</th>
                <th><?php echo $this->Paginator->sort('House.status', '狀態', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('House.modified', '更新時間', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('House.modified_by', '更新人', array('url' => $url)); ?></th>
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
                            echo $groups[$item['House']['group_id']];
                            ?></td>
                    <?php } ?>
                    <?php if ($foreignModel !== 'Task') { ?>
                        <td><?php
                            echo isset($tasks[$item['House']['task_id']]) ? $tasks[$item['House']['task_id']] : '--';
                            ?></td>
                    <?php } ?>
                    <td><?php
                        echo $item['House']['title'];
                        ?></td>
                    <td><?php
                        echo $this->Olc->status[$item['House']['status']];
                        ?></td>
                    <td><?php
                        echo $item['House']['modified'];
                        ?></td>
                    <td><?php
                        echo $item['Modifier']['username'];
                        ?></td>
                    <td class="actions">
                        <?php echo $this->Html->link('檢視', array('action' => 'view', $item['House']['id'])); ?>
                        <?php echo $this->Html->link('編輯', array('action' => 'edit', $item['House']['id'])); ?>
                        <?php echo $this->Html->link('刪除', array('action' => 'delete', $item['House']['id']), null, '確定要刪除？'); ?>
                    </td>
                </tr>
            <?php } // End of foreach ($items as $item) {   ?>
        </tbody>
    </table>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <div id="HousesAdminIndexPanel"></div>
</div>