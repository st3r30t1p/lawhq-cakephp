<?php if ($contacts->count() == 0) { ?>
	<ul class="flex" style="padding:10px">
		<li>No Results</li>
		<li>
			<?= $this->Html->link('<i class="fas fa-plus-circle"></i> Add', ['controller' => 'Contacts', 'action' => 'add'], ['class' => 'api-add-contact', 'target' => '_blank', 'escape' => false]) ?>
		</li>
	</ul>

	<div class="refresh-list-link" style="text-align:center; padding:10px; display:none">
		<a class="api-refresh"><i class="fas fa-redo-alt"></i> Refresh</a>
	</div>
<?php } else
foreach ($contacts as $contact) { ?>
	<ul class="flex contact-relationship" data-id="<?= $contact->id ?>" data-domesticOrForeign="<?= $contact->company_domestic_foreign ?>" data-name="<?= $contact->name . ' ' . $contact->getPersonStateOrCompanyIncIn() ?>">
		<li><?= $contact->name; ?></li>
		<li><?= $contact->getPersonStateOrCompanyIncIn() ?></li>
	</ul>
<?php } ?>