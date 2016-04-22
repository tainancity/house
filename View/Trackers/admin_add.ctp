<div id="TrackersAdminAdd">
    <h2><?php
        echo implode(' > ', array(
            $this->Html->link('專案', array('controller' => 'projects')),
            $this->Html->link($project['Project']['title'], array('action' => 'index', $project['Project']['id'])),
            '新增追蹤項目',
        ));
        ?></h2>
    <?php
    echo $this->Form->create('Tracker');
    ?>
    <div class="Trackers form">
        <?php
        echo $this->Form->input('Tracker.title', array(
            'label' => '名稱(住址)',
            'div' => 'form-group',
            'class' => 'form-control',
        ));
        ?>
    </div>
</div>
<?php
echo $this->Form->end('送出');
?>
<script>
    var queryUrl = '<?php echo $this->Html->url('/admin/places/q/'); ?>';
</script>
<?php
$this->Html->script('c/trackers/add', array('inline' => false));
