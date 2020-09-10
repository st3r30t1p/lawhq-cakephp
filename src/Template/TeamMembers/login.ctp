<?php
	$this->assign('title', 'Login');
?>
<div class="box">
	<div class="has-text-centered">
		<?= $this->Html->image('logo.svg', ['class' => 'logo']); ?>
	</div>
	<?php echo $this->Form->create(null, ['templates' => ['inputContainer' => '<div class="control">{{content}}</div>',]]); ?>
	<div class="field mt-10">
		<label class="label">Username</label>
		<?php echo $this->Form->text('username', ['class' => 'input', 'required' => 'required', 'label' => false]); ?>
	</div>
	<div class="field">
		<label class="label">Password</label>
		<?php echo $this->Form->text('password', ['class' => 'input', 'type' => 'password', 'required' => 'required', 'label' => false]); ?>
	</div>

	<div>
		<div>
			<?php echo $this->Form->button('Login', ['class' => 'button is-green']); ?>
		</div>
	</div>
	<?php echo $this->Form->end(); ?>
</div>