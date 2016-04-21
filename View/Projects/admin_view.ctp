<div id="ProjectsAdminView">
    <h3>專案</h3><hr />
    <div class="col-md-12">

        <div class="col-md-2">標題</div>
        <div class="col-md-9">&nbsp;<?php
            if ($this->data['Project']['title']) {

                echo $this->data['Project']['title'];
            }
            ?>&nbsp;
        </div>
        <div class="col-md-2">描述</div>
        <div class="col-md-9">&nbsp;<?php
            if ($this->data['Project']['description']) {

                echo $this->data['Project']['description'];
            }
            ?>&nbsp;
        </div>
        <div class="col-md-2">建立時間</div>
        <div class="col-md-9">&nbsp;<?php
            if ($this->data['Project']['created']) {

                echo $this->data['Project']['created'];
            }
            ?>&nbsp;
        </div>
    </div>
</div>