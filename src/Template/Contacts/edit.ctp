<?php $this->assign('title', 'Edit ' . $contact->name); ?>

<div>
	 <?= $this->Html->link(__('<i class="fas fa-trash-alt"></i>&nbsp;&nbsp;Delete'), ['action' => 'delete', $contact->id], 
	 	['class' => 'button is-light is-small', 'escape' => false, 'style' => 'display: inline;float:right', 'confirm' => __('Are you sure you want to delete {0}?', $contact->name)]) ?>

	<h1 class="title is-6">General Info</h1>

	<div class="form-section">
		<?= $this->Form->create($contact, ['id' => 'contact-form', 'data-contactId' => $contact->id]);
			$this->Form->setTemplates([
			    'inputContainer' => '<div class="field"><div class="control">{{content}}</div></div>',
			    'select' => '<div class="field"><div {{attrs}}><select name="{{name}}" data-name="relationship">{{content}}</select></div></div>',
			    'inputContainerError' => '<div class="field"><div class="control is-danger">{{content}}</div><p class="help is-danger">{{error}}</p></div>',
			    'error' => '{{content}}',
			    'input' => '<input type="{{type}}" autocomplete="false" name="{{name}}"{{attrs}}/>',
			    'submitContainer' => '{{content}}'
			]);
		?>

		<?= $this->Form->hidden('id'); ?>
		<div id="contacts-search-url" data-url="<?php echo $this->Url->build('/api/contacts-search'); ?>"></div>
		<div id="add-contact-info" data-url="<?php echo $this->Url->build('/api/add-contact-info'); ?>"></div>
		<div id="remove-contact-info" data-url="<?php echo $this->Url->build('/api/remove-contact-info'); ?>"></div>
		<div id="is-contact-new" data-check="<?php echo ($contact->isNew()) ? $contact->isNew() : $contact->id; ?>"></div>

		<div class="field is-horizontal">
		  <div class="field-label is-small">
		    <label class="label">Type</label>
		  </div>
		  <div class="field-body">
		    <div class="field">
		    	<div class="control">
			    	<?php echo $this->Form->radio('type',
					    [
					        ['value' => 'person', 'text' => 'Person', 'checked' => 'checked'],
					        ['value' => 'company', 'text' => 'Company'],
					    ]
					); ?>
		    	</div>
		    </div>
		  </div>
		</div>

		<div class="person-inputs">
			<div class="field is-horizontal">
			  <div class="field-label is-small">
			    <label class="label">First Name</label>
			  </div>
			  <div class="field-body">
			  	<?= $this->Form->control('person_first_name', ['type' => 'text', 'label' => false, 'class' => 'input is-small', 'placeholder' => 'First Name']); ?>
			  </div>
			</div>

			<div class="field is-horizontal">
			  <div class="field-label is-small">
			    <label class="label">Middle Name</label>
			  </div>
			  <div class="field-body">
			  	<?= $this->Form->control('person_middle_name', ['type' => 'text', 'label' => false, 'class' => 'input is-small', 'placeholder' => 'Middle Name']); ?>
			  </div>
			</div>

			<div class="field is-horizontal">
			  <div class="field-label is-small">
			    <label class="label">Last Name</label>
			  </div>
			  <div class="field-body">
			  	<?= $this->Form->control('person_last_name', ['type' => 'text', 'label' => false, 'class' => 'input is-small', 'placeholder' => 'Last Name']); ?>
			  </div>
			</div>

			<div class="field is-horizontal person-dob">
			  <div class="field-label is-small">
			    <label class="label">DOB</label>
			  </div>
			  	<?= $this->Form->text('person_dob', ['type' => 'date', 'label' => false, 'class' => 'input is-small birthday', 'placeholder' => 'YYYY-MM-DD', 'data-date-split-input' => 'true']); ?>
			</div>

			<div class="field is-horizontal">
			  <div class="field-label is-small">
			    <label class="label">SSN</label>
			  </div>
			  <div class="field-body">
			  	<?= $this->Form->control('ssn', ['type' => 'text', 'label' => false, 'class' => 'input is-small', 'placeholder' => 'SSN']); ?>
			  </div>
			</div>
		</div>

		<div class="company-inputs">
			<div class="field is-horizontal">
			  <div class="field-label is-small">
			    <label class="label">Company Name</label>
			  </div>
			  <div class="field-body">
			  	<?= $this->Form->control('company_name', ['type' => 'text', 'label' => false, 'class' => 'input is-small', 'placeholder' => 'Company Name']); ?>
			  </div>
			</div>

			<div class="field is-horizontal">
			  <div class="field-label is-small">
			    <label class="label">Incorporated In</label>
			  </div>
			  <div class="field-body">
		  	  	<?php echo $this->Form->control('Contacts.company_incorporated_in', ['class' => 'select is-fullwidth is-small incorporated-in', 'empty' => true, 'label' => false, 'options' => $states]); ?>
			  </div>
			</div>

			<div id="domestic-foreign" class="field is-horizontal" style="<?= ($contact->company_incorporated_in) ? '' : 'display:none' ?>">
			  <div class="field-label is-small">
			    <label class="label">Domestic/Foreign</label>
			  </div>
			  <div class="field-body">
		      	<?php echo $this->Form->select('company_domestic_foreign', [
		    	    'domestic' => 'Domestic',
		    	    'foreign' => 'Foreign'
		    	], ['class' => 'select is-fullwidth is-small domestic-foreign', 'empty' => true]); ?>
			  </div>
			</div>

			<div class="field is-horizontal">
			  <div class="field-label is-small">
			    <label class="label">Company Number</label>
			  </div>
			  <div class="field-body">
			  	<?= $this->Form->control('company_registration_number', ['type' => 'text', 'label' => false, 'class' => 'input is-small', 'placeholder' => 'Company Number']); ?>
			  </div>
			</div>

			<div class="field is-horizontal">
			  <div class="field-label is-small">
			    <label class="label">EIN</label>
			  </div>
			  <div class="field-body">
			  	<?= $this->Form->control('fein', ['type' => 'text', 'label' => false, 'class' => 'input is-small', 'placeholder' => 'EIN']); ?>
			  </div>
			</div>
		</div>

		<div class="field is-horizontal">
		  <div class="field-label is-small">
		    <!-- Left empty for spacing -->
		  </div>
		</div>

		<hr>
		<h1 class="title is-6">Address</h1>
		<?php foreach ($contact->contact_addresses as $key => $address) {
			echo $this->element('address', ['key' => $key, 'address' => $address]);
		} ?>
		<a class="add-new" data-table="contactAddresses">
			<i class="fas fa-plus-circle"></i> Add Address
		</a>

		<div class="foreign-toggle" style="<?= ($contact->company_domestic_foreign == 'foreign') ? 'display:none' : '' ?>">
			<hr>

			<h1 class="title is-6">Phone Numbers</h1>
			<?php foreach ($contact->contact_phone_numbers as $key => $number) {
				echo $this->element('phone_number', ['key' => $key]);
			} ?>
			<a class="add-new" data-table="contactPhoneNumbers">
				<i class="fas fa-plus-circle"></i> Add Phone Number
			</a>

			<hr>

			<h1 class="title is-6">Emails</h1>
			<?php foreach ($contact->contact_emails as $key => $email) {
				echo $this->element('email', ['key' => $key]);
			} ?>
			<a class="add-new" data-table="contactEmails">
				<i class="fas fa-plus-circle"></i> Add Email
			</a>

			<hr>

			<h1 class="title is-6">Websites</h1>
			<?php foreach ($contact->contact_websites as $key => $website) {
				echo $this->element('website', ['key' => $key]);
			} ?>
			<a class="add-new" data-table="contactWebsites">
				<i class="fas fa-plus-circle"></i> Add Website
			</a>

			<hr>
			<h1 class="title is-6">DBA</h1>
			<?php if (count($contact->contact_dbas)) {
				foreach ($contact->contact_dbas as $key => $dba) {
					echo $this->element('dba', ['key' => $key]);
				}
			} ?>
			<a class="add-new" data-table="contactDbas">
				<i class="fas fa-plus-circle"></i> Add DBA
			</a>
		</div>

		<hr>
		<h1 class="title is-6" id="relationships">Relationships</h1>
		<div class="table-container" style="margin-bottom:0px">
			<table class="table is-narrow" data-table="contactRelationships">
				<tbody>
					<?php foreach ($contact->contact_relationships as $key => $relationship) {
						echo $this->element('relationship', ['key' => $key, 'contact' => $contact, 'relationship' => $relationship]);
					} ?>
					<tr>
						<td colspan="4"></td>
						<td style="padding: 15px 0;">
							<a class="add-new" data-table="contactRelationships">
								<i class="fas fa-plus-circle"></i> Add Relationship
							</a>
						</td>
						<td></td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="table-container" style="margin-bottom:0px">
			<table class="table is-narrow" data-table="targetRelationships" style="margin-bottom:0px">
				<tbody>
					<?php foreach ($contact->target_relationships as $key => $relationship) {
						echo $this->element('target_relationship', ['key' => $key, 'contact' => $contact, 'relationship' => $relationship]);
					} ?>
					<tr>
						<td style="padding: 15px 0;">
							<a class="add-new" data-table="targetRelationships">
								<i class="fas fa-plus-circle"></i> Add Relationship
							</a>
						</td>
						<td colspan="5"></td>
					</tr>
				</tbody>
			</table>
		</div>
		<hr>

		<div class="even-spacing">
			<?= $this->Form->submit('Save & View', ['name' => 'view', 'class' => 'button is-green is-small']); ?>
			<?= $this->Form->submit('Save', ['class' => 'button is-green is-small', 'style' => 'float:right']); ?>
		</div>
	<?php echo $this->Form->end(); ?>
	</div>
</div>

<style type="text/css">
.table {
	background-color: transparent;
}
.table td {
	border: none;
	font-weight: 600;
	font-size: 12px;
}
.form-error select {
	border:1px solid #f14668;
}

.shorten select {
	width: 370px;
}
</style>

<script type="text/javascript">
$(document).on('click', '.remove-relationship', function() {
	var tr = $(this).closest('tr');
	var nextTr = $(tr).next();
	var table = $(tr).find('.table-id').attr('data-table');
	var tableId = $(tr).find('.table-id').val();

	if (tableId == '') { $(tr).remove(); if($(tr).hasClass('error-background')) { $(nextTr).remove(); } return };

	if (confirm('Are you sure you would like to remove this contact info?')) {
		$.get("<?php echo $this->Url->build('/api/remove-contact-info'); ?>" + '?table=' + table + '&table-id=' + tableId, function(data) {
			$(tr).remove();
			if($(tr).hasClass('error-background')) { $(nextTr).remove(); }
		});
	}
});
</script>