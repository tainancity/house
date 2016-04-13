<div class="groups form">
    <?php echo $this->Form->create('Group', array('url' => array($parentId))); ?>
    <fieldset>
        <legend><?php echo __('Add group', true); ?></legend>
        <?php
        echo $this->Form->input('name', array('label' => __('Name', true)));
        ?>
    </fieldset>
    <?php echo $this->Form->end('送出'); ?>
</div>
<div class="actions">
    <ul>
        <li><?php echo $this->Html->link(__('List', true), array('action' => 'index')); ?></li>
    </ul>
</div>
