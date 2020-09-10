<?php
	$this->assign('title', 'Add Matter');
	$this->set('wunder_title', 'Add Matter');
?>

<div class="even-spacing">
	<h1 class="title is-4">Add Matter</h1>
</div>

<?php echo $this->Form->create($matter); ?>
<div class="box">
	<div class="field">
		<?php echo $this->Form->text('name', ['class' => 'input', 'placeholder' => 'Name', 'required' => 'required']); ?>
	</div>

	<div>
		<?php echo $this->Form->button('Add', ['class' => 'button is-green']); ?>
	</div>
</div>
<?php echo $this->Form->end(); ?>