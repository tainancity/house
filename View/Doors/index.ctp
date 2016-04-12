<div id="DoorsIndex">
    <h2><?php echo __('門牌', true); ?></h2>
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
    <table class="table table-bordered" id="DoorsIndexTable">
        <thead>
            <tr>

                <th><?php echo $this->Paginator->sort('Door.area_code', '區碼', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Door.area', '區域', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Door.cunli', '村里', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Door.lin', '鄰', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Door.road', '路段', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Door.place', '地名', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Door.lane', '巷', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Door.alley', '弄', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Door.number', '號', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Door.floor', '樓層', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Door.longitude', '經度', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Door.latitude', '緯度', array('url' => $url)); ?></th>
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
                    echo $item['Door']['area_code'];
                    ?></td>
                    <td><?php
                    echo $item['Door']['area'];
                    ?></td>
                    <td><?php
                    echo $item['Door']['cunli'];
                    ?></td>
                    <td><?php
                    echo $item['Door']['lin'];
                    ?></td>
                    <td><?php
                    echo $item['Door']['road'];
                    ?></td>
                    <td><?php
                    echo $item['Door']['place'];
                    ?></td>
                    <td><?php
                    echo $item['Door']['lane'];
                    ?></td>
                    <td><?php
                    echo $item['Door']['alley'];
                    ?></td>
                    <td><?php
                    echo $item['Door']['number'];
                    ?></td>
                    <td><?php
                    echo $item['Door']['floor'];
                    ?></td>
                    <td><?php
                    echo $item['Door']['longitude'];
                    ?></td>
                    <td><?php
                    echo $item['Door']['latitude'];
                    ?></td>
                    <td class="actions">
                        <?php echo $this->Html->link(__('View', true), array('action' => 'view', $item['Door']['id']), array('class' => 'DoorsIndexControl')); ?>
                    </td>
                </tr>
            <?php }; // End of foreach ($items as $item) {  ?>
        </tbody>
    </table>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <div id="DoorsIndexPanel"></div>
    <script type="text/javascript">
        //<![CDATA[
        $(function() {
            $('#DoorsIndexTable th a, div.paging a, a.DoorsIndexControl').click(function() {
                $('#DoorsIndex').parent().load(this.href);
                return false;
            });
        });
        //]]>
    </script>
</div>