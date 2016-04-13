<div id="HouseLogsAdminAdd">
    <?php
    $url = array();
    if (!empty($foreignId) && !empty($foreignModel)) {
        $url = array('action' => 'add', $foreignModel, $foreignId);
    } else {
        $url = array('action' => 'add');
        $foreignModel = '';
    }
    echo $this->Form->create('HouseLog', array('type' => 'file', 'url' => $url));
    ?>
    <div class="HouseLogs form">
        <fieldset>
            <legend><?php
                echo __('Add 房屋記錄', true);
                ?></legend>
            <?php
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