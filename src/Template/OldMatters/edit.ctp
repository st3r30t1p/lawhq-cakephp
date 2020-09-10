<?php
	$this->assign('title', 'Add Matter');
	$this->set('wunder_title', 'Add Matter');
?>


<?php echo $this->Form->create($matter); ?>
<div class="row">
	<div class="input-field col s6">
		<?php echo $this->Form->control('name'); ?>
	</div>
</div>
<div class="row">
	<div class="input-field col s6">
		<?php echo $this->Form->select('status', ['Open' => 'Open', 'Complaint' => 'Complaint', 'Filed' => 'Filed', 'Closed' => 'Closed']); ?>
	</div>
</div>
<div class="row">
	<div class="input-field col s6">
		<?php echo $this->Form->button('Save', ['class' => 'btn']); ?>
	</div>
</div>
<?php echo $this->Form->end(); ?>

</div>