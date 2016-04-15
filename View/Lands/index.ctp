<div id="LandsIndex">
    <div class="input">
        <input type="text" class="col-md-12" id="landInput" />
        <div id="mapCanvas" class="col-md-12" style="height: 600px;"></div>
    </div>
    <script type="text/javascript">
        var queryUrl = '<?php echo $this->Html->url('/lands/q/'); ?>';
        var jsonBaseUrl = '<?php echo $this->Html->url(Configure::read('jsonBaseUrl')); ?>';
    </script>
</div>
<?php
$this->Html->script('http://maps.google.com/maps/api/js?sensor=false', array('inline' => false));
$this->Html->script('c/lands/index', array('inline' => false));
