<tr class="<?= (isset($relationship) && $relationship->hasErrors()) ? 'error-background' : '' ?>" data-targetRelationships="<?= $key ?>">
	<?= $this->Form->hidden("target_relationships.{$key}.id", ['class' => 'table-id', 'data-table' => 'contactRelationships', 'data-name' => 'id']); ?>
	<?= $this->Form->hidden("target_relationships.{$key}.side", ['value' => 'targetRelationships']); ?>
	<?php if (!isset($relationship)) {
		echo $this->Form->hidden("target_relationships.{$key}.contact_id_target", ['value' => (isset($contactId)) ? $contactId : '']);
	} else {
		echo $this->Form->hidden("target_relationships.{$key}.contact_id_target");
	} ?>
	<td style="text-align:right" title="<?= (isset($relationship['contact_id'])) ? $contactsTable->getContactName($relationship['contact_id']) : '' ?>">
		<span class="rsw">
			<?php if (!isset($relationship) || empty($relationship['contact_id']) || $relationship->isNew()) { 
				echo $this->Form->text("target_relationships.{$key}.contact_id", ['type' => 'select', 'empty' => 'Select Contact', 'class' => 'select is-small shorten', 'label' => false, 'options' => $contacts]);
			} else {
				echo $contactsTable->getContactName($relationship['contact_id']);
				echo $this->Form->hidden("target_relationships.{$key}.contact_id");
			} ?>
		</span>
	</td>
	<td style="width: 45px;">is the</td>
	<td style="width: 156px;">
		<div class="select is-small">
			<?php echo $this->Form->select("target_relationships.{$key}.relationship",
			[
				'ceo' => 'CEO',
				'cfo' => 'CFO',
				'cmo' => 'CMO',
				'coo' => 'COO',
				'co-founder' => 'Co-Founder',
				'founder' => 'Founder',
				'foreign_entity' => 'Foreign Entity',
				'general_counsel' => 'General Counsel',
				'manager' => 'Manager',
				'marketing_director' => 'Marketing Director',
				'member' => 'Member',
				'owner' => 'Owner',
				'president' => 'President',
				'registered_agent' => 'Registered Agent',
				'related_entity' => 'Related Entity',
				'subsidiary' => 'Subsidiary',
				'stockholder' => 'Stockholder',
				'vice_president' => 'Vice President',
				'unsure' => 'Unsure'
			], ['empty' => '(Type)', 'required' => 'required', 'label' => false]); ?>
		</div>
	</td>
	<td style="width:10px">of</td>
	<td title="<?= (isset($contact)) ? $contact->name . ' ' . $contact->getPersonStateOrCompanyIncIn() : '' ?>">
		<span class="current-contact-highlight rsw">
			<?php if (isset($contact)) {
				echo $contact->name . ' ' . $contact->getPersonStateOrCompanyIncIn();
			} else {
				echo 'Current Contact';
			} ?>
		</span>
	</td>
	<td>
		<span class="remove-relationship is-clickable">Remove</span>
	</td>
</tr>
<?php if (isset($relationship) && $relationship->hasErrors()) { ?>
<tr>	
	<td class="error-message error" colspan="6">
		<?= $relationship->showErrors(); ?>
	</td>
</tr>
<?php } ?>