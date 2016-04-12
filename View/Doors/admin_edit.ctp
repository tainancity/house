<div id="DoorsAdminEdit">
    <?php echo $this->Form->create('Door', array('type' => 'file')); ?>
    <div class="Doors form">
        <fieldset>
            <legend><?php
                echo __('Edit 門牌', true);
                ?></legend>
            <?php
            echo $this->Form->input('Door.id');
            echo $this->Form->input('Door.area_code', array(
                'label' => '區碼',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('Door.area', array(
                'label' => '區域',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('Door.cunli', array(
                'label' => '村里',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('Door.lin', array(
                'label' => '鄰',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('Door.road', array(
                'label' => '路段',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('Door.place', array(
                'label' => '地名',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('Door.lane', array(
                'label' => '巷',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('Door.alley', array(
                'label' => '弄',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('Door.number', array(
                'label' => '號',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('Door.floor', array(
                'label' => '樓層',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('Door.longitude', array(
                'label' => '經度',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('Door.latitude', array(
                'label' => '緯度',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            ?>
        </fieldset>
    </div>
            <?php
            echo $this->Form->end(__('Submit', true));
            ?>
</div>