<div id="PlacesAdminImportLand">
    <h2>匯入土地</h2>
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
        'label' => '檔案',
        'type' => 'file',
        'div' => 'form-group',
        'class' => 'form-control',
    ));
    echo $this->Form->end('上傳');
    ?>
</div>