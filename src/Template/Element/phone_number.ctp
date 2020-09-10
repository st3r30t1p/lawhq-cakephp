<div class="info-block" data-contactPhoneNumbers="<?= $key ?>">
	<div class="field is-horizontal">
	  <div class="field-label is-small">
	    <label class="label">Phone Number</label>
	  </div>
	  <div class="field-body">
	  	<?= $this->Form->hidden("contact_phone_numbers.{$key}.id" , ['class' => 'table-id', 'data-table' => 'contactPhoneNumbers']); ?>
	  	<?= $this->Form->control("contact_phone_numbers.{$key}.phone_number", ['type' => 'text', 'label' => false, 'class' => 'input is-small', 'placeholder' => 'Phone Number']); ?>

	  	<div class="field type-select">
	  		<div class="select is-small">
			  	<?php echo $this->Form->select("contact_phone_numbers.{$key}.type", [
				    'home' => 'Home',
				    'work' => 'Work',
				    'billing' => 'Billing',
				    'subpoena' => 'Subpoena',
				    'ppob' => 'PPOB',
				    'other' => 'Other'
				], ['class' => 'select is-small', 'empty' => '(Type)']); ?>
			</div>
		</div>

		<div class="field primary-checkbox">
			<label class="checkbox">
				<?= $this->Form->checkbox("contact_phone_numbers.{$key}.is_primary", ['label' => 'Primary', 'class' => 'primary-selection', 'data-name' => 'phone_number', ($isContactNew && $key == 0) ? 'checked' : '']); ?>
				Primary
			</label>
		</div>

		<div class="field info-remove is-clickable">
			Remove
		</div>
		
	  </div>
	</div>

	<div class="field is-horizontal">
	  <div class="field-label is-small">
	    <!-- Left empty for spacing -->
	  </div>
	</div>
</div>