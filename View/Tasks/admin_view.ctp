<div id="TasksAdminView">
    <h3><?php echo __('View 專案任務', true); ?></h3><hr />
    <div class="col-md-12">

        <div class="col-md-2">標題</div>
        <div class="col-md-9">&nbsp;<?php
            if ($this->data['Task']['title']) {

                echo $this->data['Task']['title'];
            }
            ?>&nbsp;
        </div>
        <div class="col-md-2">描述</div>
        <div class="col-md-9">&nbsp;<?php
            if ($this->data['Task']['description']) {

                echo $this->data['Task']['description'];
            }
            ?>&nbsp;
        </div>
        <div class="col-md-2">建立時間</div>
        <div class="col-md-9">&nbsp;<?php
            if ($this->data['Task']['created']) {

                echo $this->data['Task']['created'];
            }
            ?>&nbsp;
        </div>
    </div>
</div>