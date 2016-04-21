<div id="ProjectsAdminEdit">
    <?php echo $this->Form->create('Project', array('type' => 'file')); ?>
    <div class="Projects form">
        <fieldset>
            <legend>編輯專案任務</legend>
            <?php
            echo $this->Form->input('Project.id');
            echo $this->Form->input('Project.title', array(
                'label' => '標題',
                'div' => 'form-group',
                'class' => 'form-control',
            ));
            echo $this->Form->input('Project.description', array(
                'type' => 'textarea',
                'label' => '描述',
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