<tr class="<?= (isset($relationship) && $relationship->hasErrors()) ? 'error-background' : '' ?>" data-contactRelationships="<?= $key ?>">
	<?= $this->Form->hidden("contact_relationships.{$key}.id", ['class' => 'table-id', 'data-table' => 'contactRelationships', 'data-name' => 'id']); ?>
	<?= $this->Form->hidden("contact_relationships.{$key}.side", ['value' => 'contactRelationships']); ?>
	<?php if (!isset($relationship)) {
		echo $this->Form->hidden("contact_relationships.{$key}.contact_id", ['value' => (isset($contactId)) ? $contactId : '']);
	} else {
		echo $this->Form->hidden("contact_relationships.{$key}.contact_id");
	} ?>
	<td style="text-align:right" title="<?= (isset($contact)) ? $contact->name . ' ' . $contact->getPersonStateOrCompanyIncIn() : '' ?>">
			<span class="current-contact-highlight rsw">
			<?php if (isset($contact)) {
				echo $contact->name . ' ' . $contact->getPersonStateOrCompanyIncIn();
			} else {
				echo 'Current Contact';
			}
			?>
		</span>
	</td>
	<td style="width: 45px;">is the</td>
	<td style="width: 156px;">
		<div class="select is-small">
			<?php echo $this->Form->select("contact_relationships.{$key}.relationship", [
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
	<td title="<?= (isset($relationship['contact_id_target'])) ? $contactsTable->getContactName($relationship['contact_id_target']) : '' ?>">
		<span class="rsw">
			<?php if (!isset($relationship) || empty($relationship['contact_id_target']) || $relationship->isNew()) {
				echo $this->Form->text("contact_relationships.{$key}.contact_id_target", ['type' => 'select', 'empty' => 'Select Contact', 'class' => 'select is-small shorten', 'label' => false, 'options' => $contacts]);
			} else {
				echo $contactsTable->getContactName($relationship['contact_id_target']);
				echo $this->Form->hidden("contact_relationships.{$key}.contact_id_target");
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