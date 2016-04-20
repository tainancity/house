<div id="PlacesAdminImport">
    <h2><?php
        echo implode(' > ', array(
            $this->Html->link('任務', array('controller' => 'tasks')),
            $this->Html->link($task['Task']['title'], array('action' => 'index', $typeModel, 'Task', $task['Task']['id'])),
            ($typeModel === 'Door') ? '批次新增房屋' : '批次新增土地',
        ));
        ?></h2>
    <br />
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
    ?>
    <div class="input">
        批次處理：(一行輸入一筆資料)
        <textarea class="col-md-12" id="doorImport" rows="20"></textarea>
    </div>
    <a href="#" class="btn btn-primary" id="btnImport">開始匯入</a>
    <div class="ajax-loader" style="display: none;">匯入中 - <span id="importStatus"></span></div>
    <div id="importResult"></div>
</div>
<script>
<?php
switch ($typeModel) {
    case 'Door':
        echo "var queryUrl = '" . $this->Html->url('/doors/q/') . "';";

        break;
    case 'Land':
        echo "var queryUrl = '" . $this->Html->url('/lands/q/') . "';";
        break;
}
?>
    var jsonBaseUrl = '<?php echo $this->Html->url(Configure::read('jsonBaseUrl')); ?>';
    var addUrl = '<?php echo $this->Html->url(array('action' => 'add', $typeModel, 'Task', $task['Task']['id'])); ?>';
    var viewUrl = '<?php echo $this->Html->url(array('action' => 'view')); ?>/';
</script>
<?php
switch ($typeModel) {
    case 'Door':
        $this->Html->script('c/places/import', array('inline' => false));
        break;
    case 'Land':
        $this->Html->script('c/places/import_land', array('inline' => false));
        break;
}

