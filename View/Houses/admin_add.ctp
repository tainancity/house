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
            foreach ($belongsToModels AS $key => $model) {
                echo $this->Form->input('House.' . $model['foreignKey'], array(
                    'type' => 'select',
                    'label' => $model['label'],
                    'options' => $$key,
                    'div' => 'form-group',
                    'class' => 'form-control',
                ));
            }
            echo $this->Form->input('House.door_id', array(
                'label' => '門牌',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('House.group_id', array(
                'label' => '群組',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('House.task_id', array(
                'label' => '任務',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
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
            echo $this->Form->input('House.created', array(
                'label' => '建立時間',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('House.created_by', array(
                'label' => '建立人',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('House.modified', array(
                'label' => '更新時間',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('House.modified_by', array(
                'label' => '更新人',
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