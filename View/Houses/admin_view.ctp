<div id="HousesAdminView">
    <h3><?php echo __('View 房屋', true); ?></h3><hr />
    <div class="col-md-12">
        <div class="col-md-2">門牌</div>
        <div class="col-md-9">&nbsp;<?php
            if (empty($this->data['Door']['id'])) {
                echo '--';
            } else {
                echo $this->Html->link($this->data['Door']['id'], array(
                    'controller' => 'doors',
                    'action' => 'view',
                    $this->data['Door']['id']
                ));
            }
            ?></div>
        <div class="col-md-2">群組</div>
        <div class="col-md-9">&nbsp;<?php
            if (empty($this->data['Group']['id'])) {
                echo '--';
            } else {
                echo $this->Html->link($this->data['Group']['id'], array(
                    'controller' => 'groups',
                    'action' => 'view',
                    $this->data['Group']['id']
                ));
            }
            ?></div>
        <div class="col-md-2">專案任務</div>
        <div class="col-md-9">&nbsp;<?php
            if (empty($this->data['Task']['id'])) {
                echo '--';
            } else {
                echo $this->Html->link($this->data['Task']['id'], array(
                    'controller' => 'tasks',
                    'action' => 'view',
                    $this->data['Task']['id']
                ));
            }
            ?></div>

        <div class="col-md-2">門牌</div>
        <div class="col-md-9">&nbsp;<?php
            if ($this->data['House']['door_id']) {

                echo $this->data['House']['door_id'];
            }
            ?>&nbsp;
        </div>
        <div class="col-md-2">群組</div>
        <div class="col-md-9">&nbsp;<?php
            if ($this->data['House']['group_id']) {

                echo $this->data['House']['group_id'];
            }
            ?>&nbsp;
        </div>
        <div class="col-md-2">任務</div>
        <div class="col-md-9">&nbsp;<?php
            if ($this->data['House']['task_id']) {

                echo $this->data['House']['task_id'];
            }
            ?>&nbsp;
        </div>
        <div class="col-md-2">名稱</div>
        <div class="col-md-9">&nbsp;<?php
            if ($this->data['House']['title']) {

                echo $this->data['House']['title'];
            }
            ?>&nbsp;
        </div>
        <div class="col-md-2">緯度</div>
        <div class="col-md-9">&nbsp;<?php
            if ($this->data['House']['latitude']) {

                echo $this->data['House']['latitude'];
            }
            ?>&nbsp;
        </div>
        <div class="col-md-2">經度</div>
        <div class="col-md-9">&nbsp;<?php
            if ($this->data['House']['longitude']) {

                echo $this->data['House']['longitude'];
            }
            ?>&nbsp;
        </div>
        <div class="col-md-2">狀態</div>
        <div class="col-md-9">&nbsp;<?php
            if ($this->data['House']['status']) {

                echo $this->data['House']['status'];
            }
            ?>&nbsp;
        </div>
        <div class="col-md-2">建立時間</div>
        <div class="col-md-9">&nbsp;<?php
            if ($this->data['House']['created']) {

                echo $this->data['House']['created'];
            }
            ?>&nbsp;
        </div>
        <div class="col-md-2">建立人</div>
        <div class="col-md-9">&nbsp;<?php
            if ($this->data['House']['created_by']) {

                echo $this->data['House']['created_by'];
            }
            ?>&nbsp;
        </div>
        <div class="col-md-2">更新時間</div>
        <div class="col-md-9">&nbsp;<?php
            if ($this->data['House']['modified']) {

                echo $this->data['House']['modified'];
            }
            ?>&nbsp;
        </div>
        <div class="col-md-2">更新人</div>
        <div class="col-md-9">&nbsp;<?php
            if ($this->data['House']['modified_by']) {

                echo $this->data['House']['modified_by'];
            }
            ?>&nbsp;
        </div>
    </div>
    <hr />
    <div class="actions">
        <ul>
            <li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('House.id')), null, __('Delete the item, sure?', true)); ?></li>
            <li><?php echo $this->Html->link(__('房屋 List', true), array('action' => 'index')); ?> </li>
            <li><?php echo $this->Html->link(__('View Related 房屋記錄', true), array('controller' => 'house_logs', 'action' => 'index', 'House', $this->data['House']['id']), array('class' => 'HousesAdminViewControl')); ?></li>
        </ul>
    </div>
    <div id="HousesAdminViewPanel"></div>
    <?php
    echo $this->Html->scriptBlock('

');
    ?>
    <script type="text/javascript">
        //<![CDATA[
        $(function () {
            $('a.HousesAdminViewControl').click(function () {
                $('#HousesAdminViewPanel').parent().load(this.href);
                return false;
            });
        });
        //]]>
    </script>
</div>