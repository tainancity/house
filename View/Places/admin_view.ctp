<style>
    div.viewDetail > div.col-md-3, div.viewDetail > div.col-md-9 {
        margin-top: 5px;
        border-top: 1px solid grey;
    }
</style>
<div id="PlacesAdminView">
    <h2><?php
        echo implode(' > ', array(
            $this->Html->link('任務', array('controller' => 'tasks')),
            $this->Html->link($item['Task']['title'], array('action' => 'index', $item['Place']['model'], 'Task', $item['Place']['task_id'])),
            $item['Place']['title']
        ));
        $place = $item;
        unset($place['PlaceLog']);
        ?></h2>
    <hr />
    <div class="col-md-12">
        <div class="btn-group pull-right">
            <?php echo $this->Html->link('修改', array('action' => 'edit', $item['Place']['id']), array('class' => 'btn btn-primary')); ?>
        </div>
    </div>
    <div class="col-md-6">
        <div id="mapCanvas" class="col-md-12" style="height: 400px;"></div>
    </div>
    <div class="col-md-6 viewDetail">
        <div class="col-md-3">群組</div>
        <div class="col-md-9">&nbsp;<?php
            echo $item['Group']['name'];
            ?></div>
        <div class="clearfix"></div>
        <div class="col-md-3">名稱</div>
        <div class="col-md-9">&nbsp;<?php
            echo $item['Place']['title'];
            ?>&nbsp;
        </div>
        <div class="clearfix"></div>
        <div class="col-md-3">位址描述</div>
        <div class="col-md-9">&nbsp;<?php
            echo $item['Place']['description'];
            ?>&nbsp;
        </div>
        <?php if ($item['Place']['model'] === 'Land') { ?>
            <div class="clearfix"></div>
            <div class="col-md-3">地號</div>
            <div class="col-md-9"><ul>&nbsp;<?php
                    if (!empty($item['PlaceLink'])) {
                        foreach ($item['PlaceLink'] AS $land) {
                            echo "<li>{$land['Section']['name']}{$land['Land']['code']}</li>";
                        }
                    }
                    ?></ul>&nbsp;
            </div>
        <?php } ?>
        <div class="clearfix"></div>
        <div class="col-md-3">緯度,經度</div>
        <div class="col-md-9">&nbsp;<?php
            echo $item['Place']['latitude'] . ',' . $item['Place']['longitude'];
            ?>&nbsp;
        </div>
		
        <div class="clearfix"></div>
        <div class="col-md-3"><?php if($item['Place']['model']=="Land"){ echo "列管地";}?>面積</div>
        <div class="col-md-9">&nbsp;<?php
            echo $item['Place']['area'];
            ?>&nbsp;(平方公尺)
        </div>
        <div class="clearfix"></div>
        <div class="col-md-3">是否為認養地？</div>
        <div class="col-md-9">&nbsp;<?php
            echo ($item['Place']['is_adopt'] == 1) ? '是' : '否';
            ?>&nbsp;
        </div>
		<?php if($item['Place']['model']=="Land"){ ?>
		<div class="clearfix"></div>
        <div class="col-md-3">認養地面積</div>
        <div class="col-md-9">&nbsp;<?php
            echo $item['Place']['adopt_area'];
            ?>&nbsp;(平方公尺)
        </div>
		<?php }?>
        <div class="clearfix"></div>
        <div class="col-md-3">認養地類型</div>
        <div class="col-md-9">&nbsp;<?php
            echo isset($this->Olc->adopt_types[$item['Place']['adopt_type']]) ? $this->Olc->adopt_types[$item['Place']['adopt_type']] : $item['Place']['adopt_type'];
            ?>&nbsp;
        </div>
        <div class="clearfix"></div>
        <div class="col-md-3">狀態</div>
        <div class="col-md-9">&nbsp;<?php
            echo $this->Olc->status[$item['Place']['status']];
            ?>&nbsp;
        </div>
        <div class="clearfix"></div>
        <div class="col-md-3">待改善情形</div>
        <div class="col-md-9">&nbsp;<?php
            echo $this->Olc->issue[$item['Place']['issue']];
            ?>&nbsp;
        </div>
        <div class="clearfix"></div>
        <div class="col-md-3">稽查單位</div>
        <div class="col-md-9">&nbsp;<?php
            echo $item['Place']['inspect'];
            ?>&nbsp;
        </div>
        <div class="clearfix"></div>
        <div class="col-md-3">權屬</div>
        <div class="col-md-9">&nbsp;<?php
            echo $item['Place']['ownership'];
            ?>&nbsp;
        </div>
        <div class="clearfix"></div>
        <div class="col-md-3">所有權人</div>
        <div class="col-md-9">&nbsp;<?php
            echo $item['Place']['owner'];
            ?>&nbsp;
        </div>
		
		<div class="clearfix"></div>
		<div class="col-md-3">個別所有權人</div>
		<div class="col-md-9">
		<?php
		
		if($item['Place']['model']=="Land")
		{
			$area_detail_a=json_decode($item['Place']['area_detail'],true);
			$i=0;
			if(is_array($item['PlaceLink']))
			{
				foreach($item['PlaceLink']as $key=>$val)
				{
					if($item['PlaceLink'][$key]['Section']['name']!="")
					{
						$EDIT_section_code=$item['PlaceLink'][$key]['Section']['name'].$item['PlaceLink'][$key]['Land']['code'];
						$EDIT_area="";
						$EDIT_adopt_area="";
						$EDIT_owner="";
						if(!empty($area_detail_a))
						{
							foreach($area_detail_a as $area_detail_key => $area_detail_val)
							{
								if($EDIT_section_code==$area_detail_val['section_code'])
								{
									$EDIT_section_code=$item['PlaceLink'][$key]['Section']['name'].$item['PlaceLink'][$key]['Land']['code'];
									$EDIT_area=$area_detail_val['area'];
									$EDIT_adopt_area=$area_detail_val['adopt_area'];
									$EDIT_owner=$area_detail_val['owner'];
									break;
								}
							}
						}		
						echo "地號:".$EDIT_section_code." ";
						if($EDIT_owner!="")
						{
						echo "列管地面積:".$EDIT_area." ";
						echo "認養地面積:".$EDIT_adopt_area." ";
						echo "擁有人:".$EDIT_owner." ";
						}
						echo"<br>";
						$i++;

					}
				}
			}
			}
			
		?>
		</div>
        <div class="clearfix"></div>
        <div class="col-md-3">開始列管日期</div>
        <div class="col-md-9">&nbsp;<?php
            echo $item['Place']['date_begin'];
            ?>&nbsp;
        </div>
        <div class="clearfix"></div>
        <div class="col-md-3">是否位於空地空屋管理自治條例公告實施範圍</div>
        <div class="col-md-9">&nbsp;<?php
            echo!empty($item['Place']['is_rule_area']) ? '是' : '否';
            ?>&nbsp;
        </div>
        <div class="clearfix"></div>
        <div class="col-md-3">認養契約簽訂起始日</div>
        <div class="col-md-9">&nbsp;<?php
            echo $item['Place']['adopt_begin'];
            ?>&nbsp;
        </div>
        <div class="clearfix"></div>
        <div class="col-md-3">契約期限</div>
        <div class="col-md-9">&nbsp;<?php
            echo $item['Place']['adopt_end'];
            ?>&nbsp;
        </div>
        <div class="clearfix"></div>
        <div class="col-md-3">解除認養日期</div>
        <div class="col-md-9">&nbsp;<?php
            echo $item['Place']['adopt_closed'];
            ?>&nbsp;
        </div>
        <div class="clearfix"></div>
        <div class="col-md-3">認養維護單位</div>
        <div class="col-md-9">&nbsp;<?php
            echo $item['Place']['adopt_by'];
            ?>&nbsp;
        </div>
        <div class="clearfix"></div>
        <div class="col-md-3">備註</div>
        <div class="col-md-9">&nbsp;<?php
            echo nl2br($item['Place']['note']);
            ?>&nbsp;
        </div>
        <div class="clearfix"></div>
        <div class="col-md-3">建立人/建立時間</div>
        <div class="col-md-9">&nbsp;<?php
            echo $item['Creator']['username'] . ' / ' . $item['Place']['created'];
            ?>&nbsp;
        </div>
        <div class="col-md-3">更新人/更新時間</div>
        <div class="col-md-9">&nbsp;<?php
            echo $item['Modifier']['username'] . ' / ' . $item['Place']['modified'];
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
				<th>記錄</th>
				<?php if ($loginMember['group_id'] == 1) {?>
				<th>操作</th>
				<?php } ?>
            </tr>
            <?php
            foreach ($item['PlaceLog'] AS $log) {
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
                    <td><?php echo nl2br($log['note']); ?></td>
					<td><?php echo nl2br($log['update_log']); ?></td>
					<?php if ($loginMember['group_id'] == 1) {?>
					<td>
					<?php
					if ($loginMember['group_id'] == 1) {
						echo $this->Html->link('刪除記錄(含照片)', array('controller' => 'PlaceLogs','action' => 'delete', $log['id'],'?' => array('place_id' => $item['Place']['id'])), array('class' => 'btn btn-default'), '確定要刪除？');
					}
					?>					
					</td>
					<?php } ?>
					
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
</div>
<script>
    var pointLatLng = false;
<?php if (!empty($item['Place']['latitude'])) { ?>
        pointLatLng = new google.maps.LatLng(<?php echo $item['Place']['latitude']; ?>, <?php echo $item['Place']['longitude']; ?>);
<?php } ?>
    var place = <?php echo json_encode($place); ?>;
    var jsonBaseUrl = '<?php echo $this->Html->url(Configure::read('jsonBaseUrl')); ?>';
</script>
<?php
$this->Html->script('https://maps.google.com/maps/api/js?key=AIzaSyCWiufrXVQT6To9D8ZrE2dC1yuVPOaTG4I&language=zh-tw', array('inline' => false));
switch ($item['Place']['model']) {
    case 'Door':
        $this->Html->script('c/places/view', array('inline' => false));
        break;
    case 'Land':
        $this->Html->script('c/places/view_land', array('inline' => false));
        break;
}

