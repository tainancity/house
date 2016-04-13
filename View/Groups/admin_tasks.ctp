<?php
if (!isset($url)) {
    $url = array();
}
?>
<div id="GroupsAdminGroups">
    <h2>群組</h2>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <table class="table table-bordered" id="GroupsAdminGroupsTable">
        <thead>
            <tr>
                <?php
                if (!empty($op)) {
                    echo '<th>&nbsp;</th>';
                }
                ?>
                <th><?php echo $this->Paginator->sort('Group.name', '名稱', array('url' => $url)); ?></th>
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
                        $options = array('value' => $item['Group']['id'], 'class' => 'habtmSet');
                        if ($item['option'] == 1) {
                            $options['checked'] = 'checked';
                        }
                        echo $this->Form->checkbox('Set.' . $item['Group']['id'], $options);
                        echo '<div id="messageSet' . $item['Group']['id'] . '"></div></td>';
                    }
                    ?>

                    <td><?php echo $item['Group']['name']; ?></td>
                    <td class="actions">
                        <?php echo $this->Html->link('檢視', array('action' => 'view', $item['Group']['id'])); ?>
                        <?php echo $this->Html->link('編輯', array('action' => 'edit', $item['Group']['id']), array('class' => 'dialogControl')); ?>
                        <?php echo $this->Html->link('刪除', array('action' => 'delete', $item['Group']['id']), null, '確定要刪除？'); ?>
                    </td>
                </tr>
            <?php } // End of foreach ($items as $item) {  ?>
        </tbody>
    </table>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <div id="GroupsAdminGroupsPanel"></div>
    <script type="text/javascript">
        //<![CDATA[
        $(function () {
            $('#GroupsAdminGroupsTable th a, #GroupsAdminGroups div.paging a').click(function () {
                $('#GroupsAdminGroups').parent().load(this.href);
                return false;
            });
<?php
if (!empty($op)) {
    $remoteUrl = $this->Html->url(array('action' => 'habtmSet', $foreignModel, $foreignId));
    ?>
                $('#GroupsAdminGroupsTable input.habtmSet').click(function () {
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