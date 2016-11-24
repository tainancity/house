<?php
if (!isset($url)) {
    $url = array();
}
?>
<div id="TasksAdminIndex">
    <h2>空地空屋列管資料</h2>
    <div class="btn-group">
        <?php
        if ($loginMember['group_id'] == 1) {
            echo $this->Html->link('新增', array('action' => 'add'), array('class' => 'btn btn-default dialogControl'));
        }
        ?>
    </div>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <table class="table table-bordered" id="TasksAdminIndexTable">
        <thead>
            <tr>
                <?php
                if (!empty($op)) {
                    echo '<th>&nbsp;</th>';
                }
                ?>
                <th><?php echo $this->Paginator->sort('Task.title', '標題', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Task.description', '描述', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Task.created', '建立時間', array('url' => $url)); ?></th>
                <th class="actions">操作</th>
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
                    <?php
                    if (!empty($op)) {
                        echo '<td>';
                        $options = array('value' => $item['Task']['id'], 'class' => 'habtmSet');
                        if ($item['option'] == 1) {
                            $options['checked'] = 'checked';
                        }
                        echo $this->Form->checkbox('Set.' . $item['Task']['id'], $options);
                        echo '<div id="messageSet' . $item['Task']['id'] . '"></div></td>';
                    }
                    ?>

                    <td><?php echo $item['Task']['title']; ?></td>
                    <td><?php echo $item['Task']['description']; ?></td>
                    <td><?php echo $item['Task']['created']; ?></td>
                    <td class="actions">
                        <div class="btn-group">
                            <?php echo $this->Html->link('空地', array('controller' => 'places', 'action' => 'index', 'Land', 'Task', $item['Task']['id']), array('class' => 'btn btn-default')); ?>
                            <?php echo $this->Html->link('空屋', array('controller' => 'places', 'action' => 'index', 'Door', 'Task', $item['Task']['id']), array('class' => 'btn btn-default')); ?>
                            <?php echo $this->Html->link('地圖', array('action' => 'map', $item['Task']['id']), array('class' => 'btn btn-default'));?>
  
							<?php
                            if ($loginMember['group_id'] == 1) {
                                echo $this->Html->link('設定群組', array('controller' => 'groups', 'action' => 'tasks', 'Task', $item['Task']['id'], 'set'), array('class' => 'dialogControl btn btn-default'));
                                echo $this->Html->link('空地報表', array('action' => 'report', $item['Task']['id'], 'Land'), array('class' => 'btn btn-default', 'target' => '_blank'));
                                echo $this->Html->link('空屋報表', array('action' => 'report', $item['Task']['id'], 'Door'), array('class' => 'btn btn-default', 'target' => '_blank'));
								echo $this->Html->link('列管明細表', array('action' => 'report_list', $item['Task']['id']), array('class' => 'btn btn-default', 'target' => '_blank'));
                                echo $this->Html->link('編輯', array('action' => 'edit', $item['Task']['id']), array('class' => 'dialogControl btn btn-default'));
                                echo $this->Html->link('刪除', array('action' => 'delete', $item['Task']['id']), array('class' => 'btn btn-default'), '確定要刪除？');
                            }
                            ?>
                        </div>
                    </td>
                </tr>
            <?php } // End of foreach ($items as $item) {   ?>
        </tbody>
    </table>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <div id="TasksAdminIndexPanel"></div>
    <script type="text/javascript">
        //<![CDATA[
        $(function () {
            $('#TasksAdminIndexTable th a, #TasksAdminIndex div.paging a').click(function () {
                $('#TasksAdminIndex').parent().load(this.href);
                return false;
            });
<?php
if (!empty($op)) {
    $remoteUrl = $this->Html->url(array('action' => 'habtmSet', $foreignModel, $foreignId));
    ?>
                $('#TasksAdminIndexTable input.habtmSet').click(function () {
                    var remoteUrl = '<?php echo $remoteUrl; ?>/' + this.value + '/';
                    if (this.checked == true) {
                        remoteUrl = remoteUrl + 'on';
                    } else {
                        remoteUrl = remoteUrl + 'off';
                    }
                    $('div#messageSet' + this.value).load(remoteUrl);
                });
    <?php
}
?>
        });
        //]]>
    </script>
</div>