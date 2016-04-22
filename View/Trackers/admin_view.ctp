<div id="TrackersAdminView">
    <h2><?php
        echo implode(' > ', array(
            $this->Html->link('任務', array('controller' => 'tasks')),
            $this->Html->link($item['Task']['title'], array('action' => 'index', $item['Tracker']['model'], 'Task', $item['Tracker']['task_id'])),
            $item['Tracker']['title']
        ));
        $tracker = $item;
        $tracker['Tracker']['foreign_id'] = bin2hex($item['Tracker']['foreign_id']);
        if (isset($item['Land']['id'])) {
            $tracker['Land']['id'] = bin2hex($item['Land']['id']);
        }
        unset($tracker['TrackerLog']);
        ?></h2>
    <hr />
    <div class="col-md-12">
        <div class="btn-group pull-right">
            <?php echo $this->Html->link('修改', array('action' => 'edit', $item['Tracker']['id']), array('class' => 'btn btn-primary')); ?>
        </div>
    </div>
    <div class="col-md-6">
        <div id="mapCanvas" class="col-md-12" style="height: 400px;"></div>
    </div>
    <div class="col-md-6">
        <div class="col-md-3">群組</div>
        <div class="col-md-9">&nbsp;<?php
            echo $item['Group']['name'];
            ?></div>
        <div class="col-md-3">名稱</div>
        <div class="col-md-9">&nbsp;<?php
            echo $item['Tracker']['title'];
            ?>&nbsp;
        </div>
        <div class="col-md-3">緯度,經度</div>
        <div class="col-md-9">&nbsp;<?php
            echo $item['Tracker']['latitude'] . ',' . $item['Tracker']['longitude'];
            ?>&nbsp;
        </div>
        <div class="col-md-3">狀態</div>
        <div class="col-md-9">&nbsp;<?php
            echo $this->Olc->status[$item['Tracker']['status']];
            ?>&nbsp;
        </div>
        <div class="col-md-3">建立人/建立時間</div>
        <div class="col-md-9">&nbsp;<?php
            echo $item['Creator']['username'] . ' / ' . $item['Tracker']['created'];
            ?>&nbsp;
        </div>
        <div class="col-md-3">更新人/更新時間</div>
        <div class="col-md-9">&nbsp;<?php
            echo $item['Modifier']['username'] . ' / ' . $item['Tracker']['modified'];
            ?>&nbsp;
        </div>
    </div>
    <div class="col-md-12">
        <hr />
        <table class="table table-bordered">
            <tr>
                <th>建檔時間</th>
                <th>訪視日期</th>
                <th>狀態</th>
                <th>操作人</th>
                <th>照片</th>
                <th>備註</th>
            </tr>
            <?php
            foreach ($item['TrackerLog'] AS $log) {
                ?><tr>
                    <td><?php echo $log['created']; ?></td>
                    <td><?php echo $log['date_visited']; ?></td>
                    <td><?php echo $this->Olc->status[$log['status']]; ?></td>
                    <td><?php echo $log['Creator']['username']; ?></td>
                    <td><?php
                        if (!empty($log['basename'])) {
                            echo '<a href="' . $this->Media->url("original/{$log['dirname']}/{$log['basename']}") . '" target="_blank">';
                            echo $this->Media->embed("m/{$log['dirname']}/{$log['basename']}") . '</a>';
                        }
                        ?></td>
                    <td><?php echo $log['note']; ?></td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
</div>
<script>
    var pointLatLng = false;
<?php if (!empty($item['Tracker']['latitude'])) { ?>
        pointLatLng = new google.maps.LatLng(<?php echo $item['Tracker']['latitude']; ?>, <?php echo $item['Tracker']['longitude']; ?>);
<?php } ?>
    var tracker = <?php echo json_encode($tracker); ?>;
    var jsonBaseUrl = '<?php echo $this->Html->url(Configure::read('jsonBaseUrl')); ?>';
</script>
<?php
$this->Html->script('http://maps.google.com/maps/api/js?sensor=false', array('inline' => false));
switch ($item['Tracker']['model']) {
    case 'Door':
        $this->Html->script('c/trackers/view', array('inline' => false));
        break;
    case 'Land':
        $this->Html->script('c/trackers/view_land', array('inline' => false));
        break;
}

