<div id="HouseLogsView">
    <h3><?php echo __('View 房屋記錄', true); ?></h3><hr />
    <div class="col-md-12">
        <div class="col-md-2">房屋</div>
        <div class="col-md-9"><?php
            if (empty($this->data['House']['id'])) {
                echo '--';
            } else {
                echo $this->Html->link($this->data['House']['id'], array(
                    'controller' => 'houses',
                    'action' => 'view',
                    $this->data['House']['id']
                ));
            }
            ?></div>

        <div class="col-md-2">房屋</div>
        <div class="col-md-9"><?php
            if ($this->data['HouseLog']['house_id']) {

                echo $this->data['HouseLog']['house_id'];
            }
            ?>&nbsp;
        </div>
        <div class="col-md-2">狀態</div>
        <div class="col-md-9"><?php
            if ($this->data['HouseLog']['status']) {

                echo $this->data['HouseLog']['status'];
            }
            ?>&nbsp;
        </div>
        <div class="col-md-2">訪視日期</div>
        <div class="col-md-9"><?php
            if ($this->data['HouseLog']['date_visited']) {

                echo $this->data['HouseLog']['date_visited'];
            }
            ?>&nbsp;
        </div>
        <div class="col-md-2">建立時間</div>
        <div class="col-md-9"><?php
            if ($this->data['HouseLog']['created']) {

                echo $this->data['HouseLog']['created'];
            }
            ?>&nbsp;
        </div>
        <div class="col-md-2">建立人</div>
        <div class="col-md-9"><?php
            if ($this->data['HouseLog']['created_by']) {

                echo $this->data['HouseLog']['created_by'];
            }
            ?>&nbsp;
        </div>
        <div class="col-md-2">備註</div>
        <div class="col-md-9"><?php
            if ($this->data['HouseLog']['note']) {

                echo $this->data['HouseLog']['note'];
            }
            ?>&nbsp;
        </div>
    </div>
    <div class="actions">
        <ul>
            <li><?php echo $this->Html->link(__('房屋記錄 List', true), array('action' => 'index')); ?> </li>
        </ul>
    </div>
    <div id="HouseLogsViewPanel"></div>
    <script type="text/javascript">
        //<![CDATA[
        $(function () {
            $('a.HouseLogsViewControl').click(function () {
                $('#HouseLogsViewPanel').parent().load(this.href);
                return false;
            });
        });
        //]]>
    </script>
</div>