<div id="HousesAdminView">
    <h2><?php
        echo implode(' > ', array(
            $this->Html->link('任務', array('controller' => 'tasks')),
            $this->Html->link($item['Task']['title'], array('action' => 'index', 'Task', $item['House']['task_id'])),
            $item['House']['title']
        ));
        ?></h2><hr />
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
            echo $item['House']['title'];
            ?>&nbsp;
        </div>
        <div class="col-md-3">緯度,經度</div>
        <div class="col-md-9">&nbsp;<?php
            echo $item['House']['latitude'] . ',' . $item['House']['longitude'];
            ?>&nbsp;
        </div>
        <div class="col-md-3">狀態</div>
        <div class="col-md-9">&nbsp;<?php
            echo $this->Olc->status[$item['House']['status']];
            ?>&nbsp;
        </div>
        <div class="col-md-3">建立人/建立時間</div>
        <div class="col-md-9">&nbsp;<?php
            echo $item['Creator']['username'] . ' / ' . $item['House']['created'];
            ?>&nbsp;
        </div>
        <div class="col-md-3">更新人/更新時間</div>
        <div class="col-md-9">&nbsp;<?php
            echo $item['Modifier']['username'] . ' / ' . $item['House']['modified'];
            ?>&nbsp;
        </div>
    </div>
</div>
<script>
    var pointLatLng = new google.maps.LatLng(<?php echo $item['House']['latitude']; ?>, <?php echo $item['House']['longitude']; ?>);
</script>
<?php
$this->Html->script('http://maps.google.com/maps/api/js?sensor=false', array('inline' => false));
$this->Html->script('c/houses/view', array('inline' => false));
