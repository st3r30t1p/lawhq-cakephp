<?php $this->assign('title', ' Edit Account'); ?>
<h2 class="title">Edit Account</h2>

<div style="max-width:800px">
	<?php echo $this->Form->create($account, ['templates' => ['inputContainer' => '{{content}}']]);
	    $this->Form->setTemplates([
	        'select' => '<div class="field"><div {{attrs}}><select name="{{name}}">{{content}}</select></div></div>',
	    ]);
	 ?>
	<div class="field">
	    <label class="label">Account Type</label>
	    <div class="field-body">
	       <?= $this->Form->select('account', ['pacer' => 'Pacer'], ['empty' => 'Account', 'class' => 'select is-fullwidth account-type', 'label' => false,]); ?>
	    </div>
	</div>
	<div class="field account-state" style="<?= ($account->account == 'pacer') ? 'display:none' : '' ?>">
	    <label class="label">Location</label>
	    <div class="field-body">
	      <?= $this->Form->control('state_id', ['class' => 'select is-fullwidth', 'empty' => 'State', 'label' => false, 'options' => $states]); ?>
	    </div>
	</div>
	<div class="field">
	    <label class="label">Username</label>
	    <div class="field-body">
	       <?= $this->Form->text('un', ['class' => 'input is-normal', 'placeholder' => 'Username', 'label' => false, 'required' => 'required', 'value' => $account->username]) ?>
	    </div>
	</div>
	<div class="field">
	    <label class="label">Password</label>
	    <div class="field-body">
	       <?= $this->Form->text('pw', ['class' => 'input is-normal', 'placeholder' => 'Password', 'type' => 'password', 'label' => false, 'required' => 'required']) ?>
	    </div>
	</div>
	<div class="flex">
		<?= $this->Form->button('Save', ['class' => 'button is-green is-small']) ?>
		<?= $this->Html->link(__('Delete Account'), ['action' => 'deleteAccount', $account->id], ['confirm' => __('Are you sure you want to delete this account?'), 'class' => 'error-text']) ?>
	</div>
	<?= $this->Form->end() ?>
</div>

<script type="text/javascript">
$('.account-type').on('change', function() {
  var account = $('option:selected', this).val();
  if (account == 'pacer') {
    $('.account-state').hide();
    $('#state-id select').prop('required',false);
  } else {
    $('.account-state').show();
    $('#state-id select').prop('required',true);
  }
});
</script>