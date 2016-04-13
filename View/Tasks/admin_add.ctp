<div id="TasksAdminAdd">
    <?php echo $this->Form->create('Task', array('type' => 'file')); ?>
    <div class="Tasks form">
        <fieldset>
            <legend><?php
                echo __('Add 專案任務', true);
                ?></legend>
            <?php
            echo $this->Form->input('Task.title', array(
                'label' => '標題',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('Task.description', array(
                'label' => '描述',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('Task.created', array(
                'label' => '建立時間',
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