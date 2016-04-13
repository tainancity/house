<?php
if (!isset($url)) {
    $url = array();
}

if (!empty($foreignId) && !empty($foreignModel)) {
    $url = array($foreignModel, $foreignId);
}
?>
<div id="HouseLogsAdminIndex">
    <h2><?php echo __('房屋記錄', true); ?></h2>
    <div class="btn-group">
        <?php echo $this->Html->link(__('Add', true), array_merge($url, array('action' => 'add')), array('class' => 'btn dialogControl')); ?>
    </div>
    <div><?php
        echo $this->Paginator->counter(array(
            'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
        ));
        ?></div>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <table class="table table-bordered" id="HouseLogsAdminIndexTable">
        <thead>
            <tr>
                <?php if (empty($scope['HouseLog.House_id'])): ?>
                    <th><?php echo $this->Paginator->sort('HouseLog.House_id', '房屋', array('url' => $url)); ?></th>
                <?php endif; ?>

                <th><?php echo $this->Paginator->sort('HouseLog.house_id', '房屋', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('HouseLog.status', '狀態', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('HouseLog.date_visited', '訪視日期', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('HouseLog.created', '建立時間', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('HouseLog.created_by', '建立人', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('HouseLog.note', '備註', array('url' => $url)); ?></th>
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
                    <?php if (empty($scope['HouseLog.House_id'])): ?>
                        <td><?php
                if (empty($item['House']['id'])) {
                    echo '--';
                } else {
                    echo $this->Html->link($item['House']['id'], array(
                        'controller' => 'houses',
                        'action' => 'view',
                        $item['House']['id']
                    ));
                }
                        ?></td>
                    <?php endif; ?>

                    <td><?php
                    echo $item['HouseLog']['house_id'];
                    ?></td>
                    <td><?php
                    echo $item['HouseLog']['status'];
                    ?></td>
                    <td><?php
                    echo $item['HouseLog']['date_visited'];
                    ?></td>
                    <td><?php
                    echo $item['HouseLog']['created'];
                    ?></td>
                    <td><?php
                    echo $item['HouseLog']['created_by'];
                    ?></td>
                    <td><?php
                    echo $item['HouseLog']['note'];
                    ?></td>
                    <td class="actions">
                        <?php echo $this->Html->link(__('View', true), array('action' => 'view', $item['HouseLog']['id']), array('class' => 'dialogControl')); ?>
                        <?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $item['HouseLog']['id']), array('class' => 'dialogControl')); ?>
                        <?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $item['HouseLog']['id']), null, __('Delete the item, sure?', true)); ?>
                    </td>
                </tr>
            <?php } // End of foreach ($items as $item) {  ?>
        </tbody>
    </table>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <div id="HouseLogsAdminIndexPanel"></div>
    <script type="text/javascript">
        //<![CDATA[
        $(function() {
            $('#HouseLogsAdminIndexTable th a, #HouseLogsAdminIndex div.paging a').click(function() {
                $('#HouseLogsAdminIndex').parent().load(this.href);
                return false;
            });
    });
    //]]>
    </script>
</div>