<div id="PlacesAdminImportLand">
    <h2><?php
        echo implode(' > ', array(
            $this->Html->link('任務', array('controller' => 'tasks')),
            $this->Html->link($task['Task']['title'], array('action' => 'index', 'Door', 'Task', $task['Task']['id'])),
            '匯入空屋',
        ));
        ?></h2>
    <br />
    <?php
    echo $this->Form->create('Place', array('url' => array($taskId), 'type' => 'file'));
    if (!empty($groups)) {
        echo $this->Form->input('Place.group_id', array(
            'label' => '群組',
            'type' => 'select',
            'options' => $groups,
            'div' => 'form-group',
            'class' => 'form-control',
        ));
    }
    echo $this->Form->input('Place.file', array(
        'label' => '檔案 (' . $this->Html->link('匯入範例', '/pub/sample_door.csv', array('target' => '_blank')) . ')',
        'type' => 'file',
        'div' => 'form-group',
        'class' => 'form-control',
    ));
    echo $this->Form->end('上傳');
    ?>
</div>