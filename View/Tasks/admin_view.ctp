<div id="TasksAdminView">
    <h3><?php echo __('View 專案任務', true); ?></h3><hr />
    <div class="col-md-12">

        <div class="col-md-2">標題</div>
        <div class="col-md-9">&nbsp;<?php
            if ($this->data['Task']['title']) {

                echo $this->data['Task']['title'];
            }
?>&nbsp;
        </div>
        <div class="col-md-2">描述</div>
        <div class="col-md-9">&nbsp;<?php
            if ($this->data['Task']['description']) {

                echo $this->data['Task']['description'];
            }
?>&nbsp;
        </div>
        <div class="col-md-2">建立時間</div>
        <div class="col-md-9">&nbsp;<?php
            if ($this->data['Task']['created']) {

                echo $this->data['Task']['created'];
            }
?>&nbsp;
        </div>
    </div>
    <hr />
    <div class="actions">
        <ul>
            <li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Task.id')), null, __('Delete the item, sure?', true)); ?></li>
            <li><?php echo $this->Html->link(__('專案任務 List', true), array('action' => 'index')); ?> </li>
            <li><?php echo $this->Html->link(__('View Related 房屋', true), array('controller' => 'houses', 'action' => 'index', 'Task', $this->data['Task']['id']), array('class' => 'TasksAdminViewControl')); ?></li>
            <li><?php echo $this->Html->link(__('View Related 群組', true), array('controller' => 'groups', 'action' => 'index', 'Task', $this->data['Task']['id']), array('class' => 'TasksAdminViewControl')); ?></li>
            <li><?php echo $this->Html->link(__('Set Related 群組', true), array('controller' => 'groups', 'action' => 'index', 'Task', $this->data['Task']['id'], 'set'), array('class' => 'TasksAdminViewControl')); ?></li>
        </ul>
    </div>
    <div id="TasksAdminViewPanel"></div>
<?php
echo $this->Html->scriptBlock('

');
?>
    <script type="text/javascript">
        //<![CDATA[
        $(function() {
            $('a.TasksAdminViewControl').click(function() {
                $('#TasksAdminViewPanel').parent().load(this.href);
                return false;
            });
        });
        //]]>
    </script>
</div>