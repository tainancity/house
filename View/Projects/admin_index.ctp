<?php
if (!isset($url)) {
    $url = array();
}
?>
<div id="ProjectsAdminIndex">
    <h2>專案</h2>
    <div class="btn-group">
        <?php
        if ($loginMember['group_id'] == 1) {
            echo $this->Html->link('新增', array('action' => 'add'), array('class' => 'btn btn-default'));
        }
        ?>
    </div>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <table class="table table-bordered" id="ProjectsAdminIndexTable">
        <thead>
            <tr>
                <?php
                if (!empty($op)) {
                    echo '<th>&nbsp;</th>';
                }
                ?>
                <th>標題</th>
                <th><?php echo $this->Paginator->sort('Project.date_begin', '期間', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Project.count_created', '案件數', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Project.modified', '更新時間', array('url' => $url)); ?></th>
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
                        $options = array('value' => $item['Project']['id'], 'class' => 'habtmSet');
                        if ($item['option'] == 1) {
                            $options['checked'] = 'checked';
                        }
                        echo $this->Form->checkbox('Set.' . $item['Project']['id'], $options);
                        echo '<div id="messageSet' . $item['Project']['id'] . '"></div></td>';
                    }
                    ?>

                    <td><?php echo $item['Project']['title']; ?></td>
                    <td><?php echo $item['Project']['date_begin']; ?> ~ <?php echo $item['Project']['date_end']; ?></td>
                    <td><?php echo $item['Project']['count_completed']; ?> / <?php echo $item['Project']['count_created']; ?></td>
                    <td><?php echo $item['Project']['modified']; ?></td>
                    <td class="actions">
                        <div class="btn-group">
                            <?php
                            if ($loginMember['group_id'] == 1) {
                                echo $this->Html->link('編輯', array('action' => 'edit', $item['Project']['id']), array('class' => 'btn btn-default'));
                                echo $this->Html->link('刪除', array('action' => 'delete', $item['Project']['id']), array('class' => 'btn btn-default'), '確定要刪除？');
                            }
                            ?>
                        </div>
                    </td>
                </tr>
            <?php } // End of foreach ($items as $item) {   ?>
        </tbody>
    </table>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
</div>