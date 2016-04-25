<?php
if (!isset($url)) {
    $url = array();
}

?>
<div id="TrackersAdminIndex">
    <h2><?php
        echo implode(' > ', array(
            $this->Html->link('專案', array('controller' => 'projects')),
            $project['Project']['title'] . '追蹤項目',
        ));
        ?></h2>
    <div class="btn-group">
        <a id="addTracker" href="#" class="btn btn-default">新增</a>
        <a id="mapTracker" href="#" class="btn btn-default">地圖新增</a>
    </div>
    <div class="form-group" id="trackerForm" style="display: none;">
        <input id="placeQuery" type="text" class="form-control" />
    </div>
    <div class="form-group" id="mapForm" style="display: none;">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-addon">住址搜尋</span>
                <input id="mapQuery" type="text" class="form-control" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-addon">範圍</span>
                <input id="rectMeters" type="text" value="100" class="form-control" />
                <span class="input-group-addon">公尺</span>
            </div>
        </div>
        <div class="col-md-12">
            <a href="#" id="markerPush" class="btn btn-primary">新增</a>
        </div>
        <div id="mapCanvas" class="col-md-12" style="height: 400px;"></div>
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
                        echo isset($item['Place']['title']) ? $this->Html->link($item['Place']['title'], '/admin/places/view/' . bin2hex($item['Place']['id']), array('target' => '_blank')) : '--';
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
    var indexUrl = '<?php echo $this->Html->url('/admin/trackers/index/' . $project['Project']['id']); ?>';
    var addUrl = '<?php echo $this->Html->url('/admin/trackers/add/' . $project['Project']['id']); ?>';
    var importUrl = '<?php echo $this->Html->url('/admin/trackers/import/' . $project['Project']['id']); ?>';
</script>
<?php
$this->Html->script('https://maps.google.com/maps/api/js?language=zh-tw&libraries=places', array('inline' => false));
echo $this->Html->script('c/trackers/index');
