<div id="TrackersAdminEdit">
    <h2><?php
        $typeModel = $this->data['Tracker']['model'];
        echo implode(' > ', array(
            $this->Html->link('任務', array('controller' => 'tasks')),
            $this->Html->link($task['Task']['title'], array('action' => 'index', $typeModel, 'Task', $task['Task']['id'])),
            ($typeModel === 'Door') ? '編輯房屋' : '編輯土地',
        ));
        ?></h2>
    <?php echo $this->Form->create('Tracker', array('type' => 'file')); ?>
    <div class="Trackers form">
        <?php
        echo $this->Form->hidden('Tracker.foreign_id');
        echo $this->Form->input('Tracker.title', array(
            'label' => '名稱(住址)',
            'div' => 'form-group',
            'class' => 'form-control',
        ));
        ?><div class="col-md-6">
            <input type="text" class="col-md-12" id="mapHelper" />
            <div class="clearfix"></div>
            <div id="mapCanvas" class="col-md-12" style="height: 400px;"></div>
        </div>
        <div class="col-md-6">
            <?php
            if (!empty($groups)) {
                echo $this->Form->input('Tracker.group_id', array(
                    'label' => '群組',
                    'type' => 'select',
                    'options' => $groups,
                    'div' => 'form-group',
                    'class' => 'form-control',
                ));
            }
            echo $this->Form->input('Tracker.latitude', array(
                'type' => 'text',
                'label' => '緯度',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('Tracker.longitude', array(
                'type' => 'text',
                'label' => '經度',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('Tracker.status', array(
                'label' => '狀態',
                'type' => 'select',
                'options' => $this->Olc->status,
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('TrackerLog.file', array(
                'label' => '照片',
                'type' => 'file',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('TrackerLog.dirname', array('type' => 'hidden'));
            echo $this->Form->input('TrackerLog.basename', array('type' => 'hidden'));
            echo $this->Form->input('TrackerLog.checksum', array('type' => 'hidden'));
            echo $this->Form->input('TrackerLog.date_visited', array(
                'label' => '訪視日期',
                'type' => 'text',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('TrackerLog.note', array(
                'label' => '備註',
                'type' => 'textarea',
                'rows' => 5,
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            ?>
        </div>
    </div>
    <?php
    echo $this->Form->end('送出');
    $tracker = $this->data;
    $tracker['Tracker']['id'] = bin2hex($this->data['Tracker']['id']);
    $tracker['Tracker']['foreign_id'] = bin2hex($this->data['Tracker']['foreign_id']);
    if (isset($this->data['Land']['id'])) {
        $tracker['Land']['id'] = bin2hex($this->data['Land']['id']);
    }
    ?>
</div>
<script>
    var queryUrl = '<?php
    switch ($typeModel) {
        case 'Door':
            echo $this->Html->url('/doors/q/');
            break;
        case 'Land':
            echo $this->Html->url('/lands/q/');
            break;
    }
    ?>';
    var tracker = <?php echo json_encode($tracker); ?>;
    var pointLatLng = false;
<?php if (!empty($this->data['Tracker']['latitude'])) { ?>
        pointLatLng = new google.maps.LatLng(<?php echo $this->data['Tracker']['latitude']; ?>, <?php echo $this->data['Tracker']['longitude']; ?>);
<?php } ?>
    var jsonBaseUrl = '<?php echo $this->Html->url(Configure::read('jsonBaseUrl')); ?>';
</script>
<?php
$this->Html->script('http://maps.google.com/maps/api/js?sensor=false', array('inline' => false));
switch ($typeModel) {
    case 'Door':
        $this->Html->script('c/trackers/edit', array('inline' => false));
        break;
    case 'Land':
        $this->Html->script('c/trackers/edit_land', array('inline' => false));
        break;
}