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
					 <input type="text" class="col-md-12" id="mapHelper" placeholder="搜尋格式：請輸入地址" />
					';
					break;
				case 'Land':
					echo '
					 <input type="text" class="col-md-12" id="mapHelper" placeholder="搜尋格式：[台南]保安段00140000 (地號可動態新增)" />
					';
					break;
			}
			?>
           
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
			if($typeModel=="Land")
			{
				echo $this->Form->input('Place.area', array(
					'type' => 'text',
					'label' => '列管地面積(平方公尺)',
					'div' => 'form-group',
					'class' => 'form-control',
				));
			}
			else
			{
				echo $this->Form->input('Place.area', array(
					'type' => 'text',
					'label' => '面積(平方公尺)',
					'div' => 'form-group',
					'class' => 'form-control',
				));
			}
            echo $this->Form->input('Place.is_adopt', array(
                'type' => 'checkbox',
                'label' => '是否為認養地？',
                'div' => 'form-group',
                'class' => false,
            ));
			if($typeModel=="Land")
			{
				echo $this->Form->input('Place.adopt_area', array(
					'type' => 'text',
					'label' => '認養地面積(平方公尺)',
					'div' => 'form-group',
					'class' => 'form-control',
				));
			}
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
			
			if($typeModel=="Land")
			{
			echo '<style>.area_detail_div{float:left;}</style><h4>個別所有權人</h4>';
			$area_detail_a=json_decode($this->data['Place']['area_detail'],true);
			$i=0;
			if(is_array($this->data['PlaceLink']))
			{
				foreach($this->data['PlaceLink']as $key=>$val)
				{
					if($this->data['PlaceLink'][$key]['Section']['name']!="")
					{
						$EDIT_section_code=$this->data['PlaceLink'][$key]['Section']['name'].$this->data['PlaceLink'][$key]['Land']['code'];
						$EDIT_area="";
						$EDIT_adopt_area="";
						$EDIT_owner="";
						if(!empty($area_detail_a))
						{
							foreach($area_detail_a as $area_detail_key => $area_detail_val)
							{
								if($EDIT_section_code==$area_detail_val['section_code'])
								{
									$EDIT_section_code=$this->data['PlaceLink'][$key]['Section']['name'].$this->data['PlaceLink'][$key]['Land']['code'];
									$EDIT_area=$area_detail_val['area'];
									if(isset($area_detail_val['adopt_area']))
									{
									$EDIT_adopt_area=$area_detail_val['adopt_area'];
									}
									$EDIT_owner=$area_detail_val['owner'];
									break;
								}
							}
						}
						echo $this->Form->input('PlaceArea_Detail.'.$i.'.section_code', array(
							'label' => '地號',
							'type' => 'text',
							'div' => 'area_detail_div',
							'value' => $EDIT_section_code,
							'size' => 25
						));
						echo $this->Form->input('PlaceArea_Detail.'.$i.'.area', array(
							'label' => '列管地面積',
							'type' => 'text',
							'div' => 'area_detail_div',
							'value' =>$EDIT_area,
							'placeholder'=>'(平方公尺)',
							'size' => 5
						));
						echo $this->Form->input('PlaceArea_Detail.'.$i.'.adopt_area', array(
							'label' => '認養地面積',
							'type' => 'text',
							'div' => 'area_detail_div',
							'value' =>$EDIT_adopt_area,
							'placeholder'=>'(平方公尺)',
							'size' => 5
						));
						echo $this->Form->input('PlaceArea_Detail.'.$i.'.owner', array(
							'label' => '擁有人',
							'type' => 'text',
							'div' => 'area_detail_div',
							'value' =>$EDIT_owner,
							'size' => 10
						));
					echo "<div style='clear:both'><hr></div>";
					$i++;

					}
				}
			}
			echo "<hr>";
			}
			
			
            echo $this->Form->input('Place.date_begin', array(
                'type' => 'text',
                'label' => '開始列管日期',
                'div' => 'form-group',
                'class' => 'form-control datecheck',
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
                'class' => 'form-control datecheck',
            ));
            echo $this->Form->input('Place.adopt_end', array(
                'type' => 'text',
                'label' => '契約期限',
                'div' => 'form-group',
                'class' => 'form-control datecheck',
            ));
            echo $this->Form->input('Place.adopt_closed', array(
                'type' => 'text',
                'label' => '解除認養日期',
                'div' => 'form-group',
                'class' => 'form-control datecheck',
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
                'class' => 'form-control datecheck',
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
    $place = $this->data;
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
$this->Html->script('https://maps.google.com/maps/api/js?key=AIzaSyCWiufrXVQT6To9D8ZrE2dC1yuVPOaTG4I&language=zh-tw', array('inline' => false));
switch ($typeModel) {
    case 'Door':
        $this->Html->script('c/places/edit', array('inline' => false));
        break;
    case 'Land':
        $this->Html->script('c/places/edit_land', array('inline' => false));
        break;
}