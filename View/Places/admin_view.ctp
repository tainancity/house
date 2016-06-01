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
    <div class="col-md-6">
        <div class="col-md-3">群組</div>
        <div class="col-md-9">&nbsp;<?php
            echo $item['Group']['name'];
            ?></div>
        <div class="col-md-3">名稱</div>
        <div class="col-md-9">&nbsp;<?php
            echo $item['Place']['title'];
            ?>&nbsp;
        </div>
        <div class="col-md-3">位址描述</div>
        <div class="col-md-9">&nbsp;<?php
            echo $item['Place']['description'];
            ?>&nbsp;
        </div>
        <?php if ($item['Place']['model'] === 'Land') { ?>
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
        <div class="col-md-3">緯度,經度</div>
        <div class="col-md-9">&nbsp;<?php
            echo $item['Place']['latitude'] . ',' . $item['Place']['longitude'];
            ?>&nbsp;
        </div>
        <div class="col-md-3">是否為認養地？</div>
        <div class="col-md-9">&nbsp;<?php
            echo ($item['Place']['is_adopt'] == 1) ? '是' : '否';
            ?>&nbsp;
        </div>
        <div class="col-md-3">狀態</div>
        <div class="col-md-9">&nbsp;<?php
            echo $this->Olc->status[$item['Place']['status']];
            ?>&nbsp;
        </div>
        <div class="col-md-3">待改善情形</div>
        <div class="col-md-9">&nbsp;<?php
            echo $this->Olc->issue[$item['Place']['issue']];
            ?>&nbsp;
        </div>
        <div class="col-md-3">稽查單位</div>
        <div class="col-md-9">&nbsp;<?php
            echo $item['Place']['inspect'];
            ?>&nbsp;
        </div>
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
<?php if (!empty($item['Place']['latitude'])) { ?>
        pointLatLng = new google.maps.LatLng(<?php echo $item['Place']['latitude']; ?>, <?php echo $item['Place']['longitude']; ?>);
<?php } ?>
    var place = <?php echo json_encode($place); ?>;
    var jsonBaseUrl = '<?php echo $this->Html->url(Configure::read('jsonBaseUrl')); ?>';
</script>
<?php
$this->Html->script('https://maps.google.com/maps/api/js?language=zh-tw', array('inline' => false));
switch ($item['Place']['model']) {
    case 'Door':
        $this->Html->script('c/places/view', array('inline' => false));
        break;
    case 'Land':
        $this->Html->script('c/places/view_land', array('inline' => false));
        break;
}

