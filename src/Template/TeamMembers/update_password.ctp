<?php $this->assign('title', 'Update Password'); ?>
<div class="box">
	<div class="has-text-centered">
		<?= $this->Html->image('logo.svg', ['class' => 'logo']); ?>
	</div>
	<?php echo $this->Form->create(null, ['templates' => ['inputContainer' => '<div class="control">{{content}}</div>',]]); ?>
	<div class="field mt-10">
		<label class="label">New Password</label>
		<?php echo $this->Form->text('password', ['class' => 'input', 'type' => 'password', 'required' => 'required', 'label' => false]); ?>
	</div>
	<div class="field">
		<label class="label">Confirm New Password</label>
		<?php echo $this->Form->text('confirm_password', ['class' => 'input', 'type' => 'password', 'required' => 'required', 'label' => false]); ?>
	</div>

	<div>
		<div>
			<?php echo $this->Form->button('Save New Password', ['class' => 'button is-green']); ?>
		</div>
	</div>
	<?php echo $this->Form->end(); ?>
</div>