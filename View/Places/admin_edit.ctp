<div id="PlacesAdminEdit">
    <h2><?php
        $typeModel = $this->data['Place']['model'];
        echo implode(' > ', array(
            $this->Html->link('任務', array('controller' => 'tasks')),
            $this->Html->link($task['Task']['title'], array('action' => 'index', $typeModel, 'Task', $task['Task']['id'])),
            ($typeModel === 'Door') ? '編輯房屋' : '編輯土地',
        ));
        ?></h2>
    <?php echo $this->Form->create('Place', array('type' => 'file')); ?>
    <div class="Places form">
        <?php
        echo $this->Form->hidden('Place.foreign_id');
        echo $this->Form->input('Place.title', array(
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
                echo $this->Form->input('Place.group_id', array(
                    'label' => '群組',
                    'type' => 'select',
                    'options' => $groups,
                    'div' => 'form-group',
                    'class' => 'form-control',
                ));
            }
            echo $this->Form->input('Place.latitude', array(
                'type' => 'text',
                'label' => '緯度',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('Place.longitude', array(
                'type' => 'text',
                'label' => '經度',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('Place.status', array(
                'label' => '狀態',
                'type' => 'select',
                'options' => $this->Olc->status,
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('PlaceLog.file', array(
                'label' => '照片',
                'type' => 'file',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('PlaceLog.date_visited', array(
                'label' => '訪視日期',
                'type' => 'text',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('PlaceLog.note', array(
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
    $place = $this->data;
    $place['Place']['id'] = bin2hex($this->data['Place']['id']);
    $place['Place']['foreign_id'] = bin2hex($this->data['Place']['foreign_id']);
    if (isset($this->data['Land']['id'])) {
        $place['Land']['id'] = bin2hex($this->data['Land']['id']);
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
    var place = <?php echo json_encode($place); ?>;
    var pointLatLng = false;
<?php if (!empty($this->data['Place']['latitude'])) { ?>
        pointLatLng = new google.maps.LatLng(<?php echo $this->data['Place']['latitude']; ?>, <?php echo $this->data['Place']['longitude']; ?>);
<?php } ?>
    var jsonBaseUrl = '<?php echo $this->Html->url(Configure::read('jsonBaseUrl')); ?>';
</script>
<?php
$this->Html->script('http://maps.google.com/maps/api/js?sensor=false', array('inline' => false));
switch ($typeModel) {
    case 'Door':
        $this->Html->script('c/places/edit', array('inline' => false));
        break;
    case 'Land':
        $this->Html->script('c/places/edit_land', array('inline' => false));
        break;
}