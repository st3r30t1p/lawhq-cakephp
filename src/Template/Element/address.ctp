<div class="info-block" data-contactAddresses="<?= $key ?>">
	<?php if ($key > 0) { ?>
		<hr>
	<?php } ?>
	
	<div class="field is-horizontal">
	  <div class="field-label is-small">
	    <label class="label">Address 1</label>
	  </div>
	  <div class="field-body">
    	<?= $this->Form->hidden("contact_addresses.{$key}.id", ['class' => 'table-id', 'data-table' => 'contactAddresses']); ?>
    	<?= $this->Form->control("contact_addresses.{$key}.address_1", ['type' => 'text', 'label' => false, 'class' => 'input is-small', 'placeholder' => 'Address 1']); ?>
		
	    <div class="field type-select">
	    	<div class="select is-small">
		      	<?= $this->Form->select("contact_addresses.{$key}.type", [
		    	    'home' => 'Home',
		    	    'work' => 'Work',
		    	    'billing' => 'Billing',
		    	    'mailing' => 'Mailing',
		    	    'subpoena' => 'Subpoena',
		    	    'ppob' => 'PPOB',
		    	    'other' => 'Other'
		    	], ['empty' => '(Type)']); ?>
		    </div>
		</div>

		<div class="field primary-checkbox">
			<label class="checkbox">
				<?= $this->Form->checkbox("contact_addresses.{$key}.is_primary", ['label' => 'Primary', 'class' => 'primary-selection', 'data-name' => 'address', ($isContactNew && $key == 0) ? 'checked' : '']); ?>
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
	    <label class="label">Address 2</label>
	  </div>
	  <div class="field-body">
	  	<div class="field"><div class="control">
	  		<?= $this->Form->text("contact_addresses.{$key}.address_2", ['type' => 'text', 'label' => false, 'class' => 'input is-small', 'placeholder' => 'Address 2']); ?>
	  	</div></div>
	  </div>
	</div>
	
	<div class="field is-horizontal">
	  <div class="field-label is-small">
	    <label class="label">City</label>
	  </div>
	  <div class="field-body">
	  	<div class="field"><div class="control">
	  		<?= $this->Form->text("contact_addresses.{$key}.city", ['type' => 'text', 'label' => false, 'class' => 'input is-small', 'placeholder' => 'City']); ?>
	  	</div></div>
	  </div>
	</div>

	<div class="field is-horizontal">
	  <div class="field-label is-small">
	    <label class="label">State</label>
	  </div>
	  <div class="field-body">
  	  	<div class="field">
  	  		<div class="select is-fullwidth is-small">
  	  			<?= $this->Form->text("contact_addresses.{$key}.state", ['type' => 'select', 'empty' => true, 'label' => false, 'options' => $states]); ?>
	  		</div>
	  	</div>
	  </div>
	</div>

	<div class="field is-horizontal">
	  <div class="field-label is-small">
	    <label class="label">Zip</label>
	  </div>
	  <div class="field-body">
	  	<div class="field"><div class="control">
	  		<?= $this->Form->text("contact_addresses.{$key}.zip", ['type' => 'text', 'label' => false, 'class' => 'input is-small', 'placeholder' => 'Zip']); ?>
	  	</div></div>
	  </div>
	</div>

	<div class="field is-horizontal">
	  <div class="field-label is-small">
	    <label class="label">Country</label>
	  </div>
	  <div class="field-body">
	  	<div class="field">
	  		<div class="select is-fullwidth is-small">
  	  			<?= $this->Form->text("contact_addresses.{$key}.country", ['type' => 'select', 'default' => 'US', 'label' => false, 'options' => $countries]); ?>
	  		</div>
	  	</div>
	  </div>
	</div>

	<div class="field is-horizontal">
	  <div class="field-label is-small">
	    <!-- Left empty for spacing -->
	  </div>
	</div>
</div>