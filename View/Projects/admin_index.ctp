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
            echo $this->Html->link('新增', array('action' => 'add'), array('class' => 'btn btn-default dialogControl'));
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
                <th><?php echo $this->Paginator->sort('Project.title', '標題', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Project.description', '描述', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Project.created', '建立時間', array('url' => $url)); ?></th>
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
                    <td><?php echo $item['Project']['description']; ?></td>
                    <td><?php echo $item['Project']['created']; ?></td>
                    <td class="actions">
                        <div class="btn-group">
                            <?php echo $this->Html->link('相關房屋', array('controller' => 'places', 'action' => 'index', 'Door', 'Project', $item['Project']['id']), array('class' => 'btn btn-default')); ?>
                            <?php echo $this->Html->link('相關土地', array('controller' => 'places', 'action' => 'index', 'Land', 'Project', $item['Project']['id']), array('class' => 'btn btn-default')); ?>
                            <?php
                            if ($loginMember['group_id'] == 1) {
                                echo $this->Html->link('編輯', array('action' => 'edit', $item['Project']['id']), array('class' => 'dialogControl btn btn-default'));
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