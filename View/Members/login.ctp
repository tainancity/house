<div id=login>
<?php echo $this->Form->create('Member', array('action' => 'login'));?>
<?php echo $this->Form->input('username',array('label' => '帳號'));?>
<?php echo $this->Form->input('password',array('label' => '密碼'));?>
<?php echo $this->Form->end(__('登入', true));?>
</div>