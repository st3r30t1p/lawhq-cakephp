<?php if ($foreignEntities->count() == 0) { ?>
	<tr data-domestic-id="<?= $domesticId ?>">
		<td class="foreign-table-margin" colspan="6">No Results</td>
	</tr>
<?php } else { foreach ($foreignEntities as $entity) { ?>
	<tr data-domestic-id="<?= $domesticId ?>">
		<td class="foreign-table-margin"><?= $this->Html->link($entity->contact->name . ' ' . $entity->contact->getCompanyIncIn(), ['controller' => 'Contacts', 'action' => 'view', 'id' => $entity->contact->id]) ?></td>
		<td><?= ucfirst($entity->contact->type); ?></td>
		<td><?= $entity->contact->getAddress(); ?></td>
		<td><?= $entity->contact->getEmail(); ?></td>
		<td><?= $entity->contact->getPhoneNumber(); ?></td>
		<td><a href="<?= $this->Url->build(['controller' => 'Contacts', 'action' => 'edit', 'id' => $entity->contact['id']]) ?>" target="_blank"><i class="fas fa-edit is-clickable"></i></a></td>
	</tr>
<?php }} ?>