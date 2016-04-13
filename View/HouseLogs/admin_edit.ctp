<div id="HouseLogsAdminEdit">
    <?php echo $this->Form->create('HouseLog', array('type' => 'file')); ?>
    <div class="HouseLogs form">
        <fieldset>
            <legend><?php
                echo __('Edit 房屋記錄', true);
                ?></legend>
            <?php
            echo $this->Form->input('HouseLog.id');
            foreach ($belongsToModels AS $key => $model) {
                echo $this->Form->input('HouseLog.' . $model['foreignKey'], array(
                    'type' => 'select',
                    'label' => $model['label'],
                    'options' => $$key,
                    'div' => 'form-group',
                    'class' => 'form-control',
                ));
            }
            echo $this->Form->input('HouseLog.house_id', array(
                'label' => '房屋',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('HouseLog.status', array(
                'label' => '狀態',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('HouseLog.date_visited', array(
                'label' => '訪視日期',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('HouseLog.created', array(
                'label' => '建立時間',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('HouseLog.created_by', array(
                'label' => '建立人',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('HouseLog.note', array(
                'label' => '備註',
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