<div id="TrackersAdminView">
    <h2><?php
        echo implode(' > ', array(
            $this->Html->link('專案', array('controller' => 'projects')),
            $this->Html->link($item['Project']['title'], array('action' => 'index', $item['Tracker']['project_id'])),
            $item['Place']['title']
        ));
        ?></h2>
    <hr />
    <div class="col-md-12">
        <div class="col-md-3">專案</div>
        <div class="col-md-9">&nbsp;<?php echo $item['Project']['title']; ?></div>
        <div class="col-md-3">群組</div>
        <div class="col-md-9">&nbsp;<?php echo $item['Group']['name']; ?></div>
        <div class="col-md-3">項目</div>
        <div class="col-md-9">&nbsp;<?php
            echo $this->Html->link($item['Place']['title'], '/admin/places/view/' . $item['Tracker']['place_id'], array('target' => '_blank'));
            ?>&nbsp;
        </div>
        <div class="col-md-3">建立人/建立時間</div>
        <div class="col-md-9">&nbsp;<?php
            echo $item['Creator']['username'] . ' / ' . $item['Tracker']['created'];
            ?>&nbsp;
        </div>
        <div class="col-md-3">完成人/完成時間</div>
        <div class="col-md-9">&nbsp;<?php
            if (empty($item['Completer']['username'])) {
                echo '--';
            } else {
                echo $item['Completer']['username'] . ' / ' . $item['Tracker']['completed'];
            }
            ?>&nbsp;
        </div>
    </div>
</div>