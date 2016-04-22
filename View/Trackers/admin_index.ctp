<?php
if (!isset($url)) {
    $url = array();
}

if (!empty($foreignId) && !empty($foreignModel)) {
    $url = array($typeModel, $foreignModel, $foreignId);
}
?>
<div id="TrackersAdminIndex">
    <h2><?php
        echo implode(' > ', array(
            $this->Html->link('專案', array('controller' => 'projects')),
            '追蹤項目',
        ));
        ?></h2>
    <div class="btn-group">
        <a id="addTracker" href="#" class="btn btn-default">新增</a>
        <?php echo $this->Html->link('匯入', array('action' => 'import', $projectId), array('class' => 'btn btn-default')); ?>
    </div>
    <div class="form-group" id="trackerForm" style="display: none;">
        <input id="placeQuery" type="text" class="form-control" />
    </div>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <table class="table table-bordered" id="TrackersAdminIndexTable">
        <thead>
            <tr>
                <th><?php echo $this->Paginator->sort('Tracker.group_id', '群組', array('url' => $url)); ?></th>
                <th>項目</th>
                <th><?php echo $this->Paginator->sort('Tracker.created', '建立時間', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Tracker.completed', '完成時間', array('url' => $url)); ?></th>
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
                    <td><?php
                        echo $groups[$item['Tracker']['group_id']];
                        ?></td>
                    <td><?php
                        echo isset($item['Place']['title']) ? $item['Place']['title'] : '--';
                        ?></td>
                    <td><?php
                        echo $item['Tracker']['created'];
                        ?></td>
                    <td><?php
                        echo empty($item['Tracker']['completed']) ? '--' : $item['Tracker']['completed'];
                        ?></td>
                    <td>
                        <div class="btn-group">
                            <?php echo $this->Html->link('檢視', array('action' => 'view', $item['Tracker']['id']), array('class' => 'btn btn-default')); ?>
                            <?php echo $this->Html->link('編輯', array('action' => 'edit', $item['Tracker']['id']), array('class' => 'btn btn-default')); ?>
                            <?php echo $this->Html->link('刪除', array('action' => 'delete', $item['Tracker']['id']), array('class' => 'btn btn-default'), '確定要刪除？'); ?>
                        </div>
                    </td>
                </tr>
            <?php } // End of foreach ($items as $item) {   ?>
        </tbody>
    </table>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <div id="TrackersAdminIndexPanel"></div>
</div>
<script>
    var queryUrl = '<?php echo $this->Html->url('/admin/places/q/'); ?>';
    var addUrl = '<?php echo $this->Html->url('/admin/trackers/add/' . $projectId); ?>';
    var indexUrl = '<?php echo $this->Html->url('/admin/trackers/index/' . $projectId); ?>';
</script>
<?php
echo $this->Html->script('c/trackers/index');