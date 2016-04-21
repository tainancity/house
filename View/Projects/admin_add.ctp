<div id="ProjectsAdminAdd">
    <h2><?php
        echo implode(' > ', array(
            $this->Html->link('專案', array('controller' => 'projects')),
            '新增專案',
        ));
        ?></h2>
    <?php echo $this->Form->create('Project', array('type' => 'file')); ?>
    <div class="Projects form">
        <?php
        echo $this->Form->input('Project.title', array(
            'label' => '標題',
            'div' => 'form-group',
            'class' => 'form-control',
        ));
        echo $this->Form->input('Project.date_begin', array(
            'type' => 'text',
            'label' => '開始日期',
            'div' => 'form-group',
            'class' => 'form-control',
        ));
        echo $this->Form->input('Project.date_end', array(
            'type' => 'text',
            'label' => '結束日期',
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
    </div>
    <?php
    echo $this->Form->end('送出');
    ?>
</div>
<?php
$this->Html->script('c/projects/add', array('inline' => false));
