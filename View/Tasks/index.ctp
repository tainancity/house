<div id="TasksIndex">
    <h2><?php echo __('專案任務', true); ?></h2>
    <div class="clear actions">
        <ul>
        </ul>
    </div>
    <p>
        <?php
        $url = array();

        echo $this->Paginator->counter(array(
            'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
        ));
        ?></p>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <table class="table table-bordered" id="TasksIndexTable">
        <thead>
            <tr>

                <th><?php echo $this->Paginator->sort('Task.title', '標題', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Task.description', '描述', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Task.created', '建立時間', array('url' => $url)); ?></th>
                <th class="actions"><?php echo __('Action', true); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            foreach ($items as $item) {
                $class = null;
                if ($i++ % 2 == 0) {
                    $class = ' class="altrow"';
                }
                ?>
                <tr<?php echo $class; ?>>

                    <td><?php
                    echo $item['Task']['title'];
                    ?></td>
                    <td><?php
                    echo $item['Task']['description'];
                    ?></td>
                    <td><?php
                    echo $item['Task']['created'];
                    ?></td>
                    <td class="actions">
                        <?php echo $this->Html->link(__('View', true), array('action' => 'view', $item['Task']['id']), array('class' => 'TasksIndexControl')); ?>
                    </td>
                </tr>
            <?php }; // End of foreach ($items as $item) {  ?>
        </tbody>
    </table>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <div id="TasksIndexPanel"></div>
    <script type="text/javascript">
        //<![CDATA[
        $(function() {
            $('#TasksIndexTable th a, div.paging a, a.TasksIndexControl').click(function() {
                $('#TasksIndex').parent().load(this.href);
                return false;
            });
        });
        //]]>
    </script>
</div>