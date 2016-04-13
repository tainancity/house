<?php
if (!isset($url)) {
    $url = array();
}

if (!empty($foreignId) && !empty($foreignModel)) {
    $url = array($foreignModel, $foreignId);
}
?>
<div id="HousesAdminIndex">
    <h2><?php echo __('房屋', true); ?></h2>
    <div class="btn-group">
        <?php echo $this->Html->link('新增', array_merge($url, array('action' => 'add')), array('class' => 'btn dialogControl')); ?>
    </div>
    <div><?php
        echo $this->Paginator->counter(array(
            'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
        ));
        ?></div>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <table class="table table-bordered" id="HousesAdminIndexTable">
        <thead>
            <tr>
                <th><?php echo $this->Paginator->sort('House.door_id', '門牌', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('House.group_id', '群組', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('House.task_id', '任務', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('House.title', '名稱', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('House.latitude', '緯度', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('House.longitude', '經度', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('House.status', '狀態', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('House.created', '建立時間', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('House.created_by', '建立人', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('House.modified', '更新時間', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('House.modified_by', '更新人', array('url' => $url)); ?></th>
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
                    <td><?php
                        echo $item['House']['door_id'];
                        ?></td>
                    <td><?php
                        echo $item['House']['group_id'];
                        ?></td>
                    <td><?php
                        echo $item['House']['task_id'];
                        ?></td>
                    <td><?php
                        echo $item['House']['title'];
                        ?></td>
                    <td><?php
                        echo $item['House']['latitude'];
                        ?></td>
                    <td><?php
                        echo $item['House']['longitude'];
                        ?></td>
                    <td><?php
                        echo $item['House']['status'];
                        ?></td>
                    <td><?php
                        echo $item['House']['created'];
                        ?></td>
                    <td><?php
                        echo $item['House']['created_by'];
                        ?></td>
                    <td><?php
                        echo $item['House']['modified'];
                        ?></td>
                    <td><?php
                        echo $item['House']['modified_by'];
                        ?></td>
                    <td class="actions">
                        <?php echo $this->Html->link('檢視', array('action' => 'view', $item['House']['id']), array('class' => 'dialogControl')); ?>
                        <?php echo $this->Html->link('編輯', array('action' => 'edit', $item['House']['id']), array('class' => 'dialogControl')); ?>
                        <?php echo $this->Html->link('刪除', array('action' => 'delete', $item['House']['id']), null, '確定要刪除？'); ?>
                    </td>
                </tr>
            <?php } // End of foreach ($items as $item) {  ?>
        </tbody>
    </table>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <div id="HousesAdminIndexPanel"></div>
    <script type="text/javascript">
        //<![CDATA[
        $(function () {
            $('#HousesAdminIndexTable th a, #HousesAdminIndex div.paging a').click(function () {
                $('#HousesAdminIndex').parent().load(this.href);
                return false;
            });
        });
        //]]>
    </script>
</div>