<?php
if (!isset($url)) {
    $url = array();
}
?>
<div id="TrackersAdminUser">
    <h2>追蹤項目</h2>
    <div class="btn-group">
        <?php echo $this->Html->link('未完成', '/admin/trackers/user', array('class' => 'btn btn-default')); ?>
        <?php echo $this->Html->link('已完成', '/admin/trackers/user/completed', array('class' => 'btn btn-default')); ?>
    </div>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <table class="table table-bordered" id="TrackersAdminUserTable">
        <thead>
            <tr>
                <th>專案</th>
                <th>項目</th>
                <th><?php echo $this->Paginator->sort('Tracker.created', '建立時間', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Tracker.completed', '完成時間', array('url' => $url)); ?></th>
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
                    <td><?php
                        echo $groups[$item['Tracker']['group_id']];
                        ?></td>
                    <td><?php
                        echo isset($item['Place']['title']) ? $this->Html->link($item['Place']['title'], '/admin/places/view/' . $item['Place']['id'], array('target' => '_blank')) : '--';
                        ?></td>
                    <td><?php
                        echo $item['Tracker']['created'];
                        ?></td>
                    <td><?php
                        echo empty($item['Tracker']['completed']) ? '--' : $item['Tracker']['completed'];
                        ?></td>
                </tr>
            <?php } // End of foreach ($items as $item) {   ?>
        </tbody>
    </table>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <div id="TrackersAdminUserPanel"></div>
</div>