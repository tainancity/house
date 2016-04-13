<div id="HousesAdminAdd">
    <?php
    $url = array();
    if (!empty($foreignId) && !empty($foreignModel)) {
        $url = array('action' => 'add', $foreignModel, $foreignId);
    } else {
        $url = array('action' => 'add');
        $foreignModel = '';
    }
    echo $this->Form->create('House', array('type' => 'file', 'url' => $url));
    ?>
    <div class="Houses form">
        <fieldset>
            <legend><?php
                echo __('Add 房屋', true);
                ?></legend>
            <?php
            echo $this->Form->input('House.title', array(
                'label' => '名稱',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('House.latitude', array(
                'label' => '緯度',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('House.longitude', array(
                'label' => '經度',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('House.status', array(
                'label' => '狀態',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            ?>
        </fieldset>
    </div>
    <?php
    echo $this->Form->end('送出');
    ?>
</div>