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
        echo $this->Form->input('Place.description', array(
            'label' => '位址描述',
            'div' => 'form-group',
            'class' => 'form-control',
        ));
        ?><div class="col-md-6">
            <?php
			switch ($typeModel) {
				case 'Door':
					echo '
					<div class="btn-group">
						<a href="#" id="geoInput" class="btn btn-default">定位目前位置</a>
						<a href="#" id="geoGoogle" class="btn btn-default">輸入地址查找座標</a>
					</div>
					';
					break;
				case 'Land':
					break;
			}
			
			?>
            <input type="text" class="col-md-12" id="mapHelper" placeholder="搜尋格式：[台南]保安段00140000 (地號可動態新增)" />
            <div class="clearfix"></div>
            <div id="mapCanvas" class="col-md-12" style="height: 400px;"></div>
            <br />&nbsp;<br />&nbsp;
            <div id="mapItems"></div>
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
            echo $this->Form->input('Place.area', array(
                'type' => 'text',
                'label' => '面積(平方公尺)',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('Place.is_adopt', array(
                'type' => 'checkbox',
                'label' => '是否為認養地？',
                'div' => 'form-group',
                'class' => false,
            ));
            echo $this->Form->input('Place.adopt_type', array(
                'label' => '認養類型',
                'type' => 'select',
                'options' => $this->Olc->adopt_types,
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
            echo $this->Form->input('Place.issue', array(
                'label' => '待改善情形',
                'type' => 'select',
                'options' => $this->Olc->issue,
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('Place.inspect', array(
                'type' => 'text',
                'label' => '稽查單位',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('Place.ownership', array(
                'type' => 'text',
                'label' => '土地/房屋權屬',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('Place.owner', array(
                'type' => 'text',
                'label' => '土地/房屋所有權人',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('Place.date_begin', array(
                'type' => 'text',
                'label' => '開始列管日期',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('Place.is_rule_area', array(
                'type' => 'checkbox',
                'label' => '是否位於空地空屋管理自治條例公告實施範圍',
                'div' => 'form-group',
                'class' => false,
            ));
            echo $this->Form->input('Place.adopt_begin', array(
                'type' => 'text',
                'label' => '認養契約簽訂起始日',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('Place.adopt_end', array(
                'type' => 'text',
                'label' => '契約期限',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('Place.adopt_closed', array(
                'type' => 'text',
                'label' => '解除認養日期',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('Place.adopt_by', array(
                'type' => 'text',
                'label' => '認養維護單位',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('Place.note', array(
                'type' => 'textarea',
                'label' => '備註',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('PlaceLog.file', array(
                'label' => '照片',
                'type' => 'file',
                'accept' => 'image/*',
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
                'label' => '訪視記錄',
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
$this->Html->script('https://maps.google.com/maps/api/js?key=AIzaSyCWiufrXVQT6To9D8ZrE2dC1yuVPOaTG4I&language=zh-tw', array('inline' => false));
switch ($typeModel) {
    case 'Door':
        $this->Html->script('c/places/add', array('inline' => false));
        break;
    case 'Land':
        $this->Html->script('c/places/add_land', array('inline' => false));
        break;
}