<div id="PlacesAdminEdit">
    <?php echo $this->Form->create('Place', array('type' => 'file')); ?>
    <div class="Places form">
        <h3>新增房屋</h3>
        <?php
        echo $this->Form->hidden('Place.door_id');
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
    var queryUrl = '<?php echo $this->Html->url('/doors/q/'); ?>';
    var pointLatLng = new google.maps.LatLng(<?php echo $this->data['Place']['latitude']; ?>, <?php echo $this->data['Place']['longitude']; ?>);
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