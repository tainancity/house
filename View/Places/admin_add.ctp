<div id="PlacesAdminAdd">
    <h2><?php
        echo implode(' > ', array(
            $this->Html->link('任務', array('controller' => 'tasks')),
            $this->Html->link($task['Task']['title'], array('action' => 'index', $typeModel, 'Task', $foreignId)),
            ($typeModel === 'Door') ? '新增房屋' : '新增土地',
        ));
        ?></h2>
    <?php
    $url = array();
    if (!empty($foreignId) && !empty($foreignModel)) {
        $url = array('action' => 'add', $typeModel, $foreignModel, $foreignId);
    } else {
        $url = array('action' => 'add');
        $foreignModel = '';
    }
    echo $this->Form->create('Place', array('type' => 'file', 'url' => $url));
    ?>
    <div class="Places form">
        <?php
        echo $this->Form->hidden('Place.foreign_id');
        echo $this->Form->input('Place.title', array(
            'label' => '名稱(住址)',
            'div' => 'form-group',
            'class' => 'form-control',
        ));
        ?><div class="col-md-6">
            <div class="btn-group">
                <a href="#" id="geoInput" class="btn btn-default">衛星定位</a>
                <a href="#" id="geoGoogle" class="btn btn-default">Google搜尋</a>
            </div>
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
    var jsonBaseUrl = '<?php echo $this->Html->url(Configure::read('jsonBaseUrl')); ?>';
</script>
<?php
$this->Html->script('http://maps.google.com/maps/api/js?language=zh-tw', array('inline' => false));
switch ($typeModel) {
    case 'Door':
        $this->Html->script('c/places/add', array('inline' => false));
        break;
    case 'Land':
        $this->Html->script('c/places/add_land', array('inline' => false));
        break;
}