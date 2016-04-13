<div id="HousesAdminEdit">
    <?php echo $this->Form->create('House', array('type' => 'file')); ?>
    <div class="Houses form">
        <fieldset>
            <legend><?php
                echo __('Edit 房屋', true);
                ?></legend>
            <?php
            echo $this->Form->input('House.id');
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