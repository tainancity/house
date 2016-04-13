<div id="HousesIndex">
    <h2><?php echo __('房屋', true); ?></h2>
    <div class="clear actions">
        <ul>
        </ul>
    </div>
    <p>
        <?php
        $url = array();

        if (!empty($foreignId) && !empty($foreignModel)) {
            $url = array($foreignModel, $foreignId);
        }

        echo $this->Paginator->counter(array(
            'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
        ));
        ?></p>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <table class="table table-bordered" id="HousesIndexTable">
        <thead>
            <tr>
                <?php if (empty($scope['House.Door_id'])): ?>
                    <th><?php echo $this->Paginator->sort('House.Door_id', '門牌', array('url' => $url)); ?></th>
                <?php endif; ?>
                <?php if (empty($scope['House.Group_id'])): ?>
                    <th><?php echo $this->Paginator->sort('House.Group_id', '群組', array('url' => $url)); ?></th>
                <?php endif; ?>
                <?php if (empty($scope['House.Task_id'])): ?>
                    <th><?php echo $this->Paginator->sort('House.Task_id', '專案任務', array('url' => $url)); ?></th>
                <?php endif; ?>

                <th><?php echo $this->Paginator->sort('House.door_id', '門牌', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('House.group_id', '群組', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('House.task_id', '任務', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('House.title', '名稱', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('House.latitude', '緯度', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('House.longitude', '經度', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('House.status', '狀態', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('House.created', '建立時間', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('House.created_by', '建立人', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('House.modified', '更新時間', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('House.modified_by', '更新人', array('url' => $url)); ?></th>
                <th class="actions"><?php echo __('Action', true); ?></th>
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
                    <?php if (empty($scope['House.Door_id'])): ?>
                        <td><?php
                if (empty($item['Door']['id'])) {
                    echo '--';
                } else {
                    echo $this->Html->link($item['Door']['id'], array(
                        'controller' => 'doors',
                        'action' => 'view',
                        $item['Door']['id']
                    ));
                }
                        ?></td>
                    <?php endif; ?>
                    <?php if (empty($scope['House.Group_id'])): ?>
                        <td><?php
                if (empty($item['Group']['id'])) {
                    echo '--';
                } else {
                    echo $this->Html->link($item['Group']['id'], array(
                        'controller' => 'groups',
                        'action' => 'view',
                        $item['Group']['id']
                    ));
                }
                        ?></td>
                    <?php endif; ?>
                    <?php if (empty($scope['House.Task_id'])): ?>
                        <td><?php
                if (empty($item['Task']['id'])) {
                    echo '--';
                } else {
                    echo $this->Html->link($item['Task']['id'], array(
                        'controller' => 'tasks',
                        'action' => 'view',
                        $item['Task']['id']
                    ));
                }
                        ?></td>
                    <?php endif; ?>

                    <td><?php
                    echo $item['House']['door_id'];
                    ?></td>
                    <td><?php
                    echo $item['House']['group_id'];
                    ?></td>
                    <td><?php
                    echo $item['House']['task_id'];
                    ?></td>
                    <td><?php
                    echo $item['House']['title'];
                    ?></td>
                    <td><?php
                    echo $item['House']['latitude'];
                    ?></td>
                    <td><?php
                    echo $item['House']['longitude'];
                    ?></td>
                    <td><?php
                    echo $item['House']['status'];
                    ?></td>
                    <td><?php
                    echo $item['House']['created'];
                    ?></td>
                    <td><?php
                    echo $item['House']['created_by'];
                    ?></td>
                    <td><?php
                    echo $item['House']['modified'];
                    ?></td>
                    <td><?php
                    echo $item['House']['modified_by'];
                    ?></td>
                    <td class="actions">
                        <?php echo $this->Html->link(__('View', true), array('action' => 'view', $item['House']['id']), array('class' => 'HousesIndexControl')); ?>
                    </td>
                </tr>
            <?php }; // End of foreach ($items as $item) {  ?>
        </tbody>
    </table>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <div id="HousesIndexPanel"></div>
    <script type="text/javascript">
        //<![CDATA[
        $(function() {
            $('#HousesIndexTable th a, div.paging a, a.HousesIndexControl').click(function() {
                $('#HousesIndex').parent().load(this.href);
                return false;
            });
        });
        //]]>
    </script>
</div>