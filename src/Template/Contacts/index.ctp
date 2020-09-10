<?php
	$this->assign('title', 'Contacts');
	$this->set('wunder_title', 'Contacts');
?>
<!-- <ul class="flex">
	<li>
		<h1 class="title is-4">Contacts</h1>
	</li>
</ul> -->
<div id="find-foreign-entities-url" data-url="<?php echo $this->Url->build('/api/lookup-foreign-entities'); ?>"></div>

<ul class="flex even-spacing">
	<li style="margin-right: auto; flex:1">
		<div class="field has-addons">
			<div class="control" style="width: 100%; max-width: 400px;">
				<?= $this->Form->create(null, ['type' => 'GET']); ?>
				<?= $this->Form->text('q', ['class' => 'input is-small', 'placeholder' => 'Search...', 'style' => 'width:100%', 'value' => (isset($query['q'])) ? $query['q'] : '']); ?>
			</div>
			<div class="control">
				<div class="select is-small">
					<?= $this->Form->select('type', ['person' => 'Person', 'company' => 'Company'], ['empty' => 'Type', 'value' => (isset($query['type'])) ? $query['type'] : '']) ?>
				</div>
			</div>
			<div class="control">
			  <?= $this->Form->submit('Search', ['class' => 'button is-inverted is-small']); ?>
			  <?= $this->Form->end(); ?>
			</div>
		</div>
	</li>

	<div class="break" style="display:none"></div>

	<li>
		<a class="button is-green is-small" href="<?= $this->Url->build(['action' => 'add']) ?>">
		   <span class="icon is-small">
		     <i class="fas fa-plus"></i>
		   </span>
		   <span>New Contact</span>
		 </a>
	</li>
</ul>

<div class="card table-container">
	<table class="table is-hoverable">
	<thead>
		<tr>
			<th>Name</th>
			<th>Type</th>
			<th>Address</th>
			<th>Email</th>
			<th>Phone</th>
			<th>Edit</th>
		</tr>
	</thead>
	<tbody style="font-size:smaller;">
		<?php foreach ($contacts as $contact) { ?>
			<tr data-id=<?= $contact->id ?>>
				<td>
					<?= ( $contact->isDomestic() ) ? '<span class="lookup-foreign-entities is-clickable"><i class="fas fa-plus-circle"></i></span>' : ''; ?>
					<?= $this->Html->link($contact->name . ' ' . $contact->getPersonStateOrCompanyIncIn(), ['action' => 'view', 'id' => $contact->id]) ?>
				</td>
				<td><?= ucfirst($contact->type); ?></td>
				<td><?= $contact->getAddress(); ?></td>
				<td><?= $contact->getEmail(); ?></td>
				<td><?= $contact->getPhoneNumber(); ?></td>
				<td><a href="<?= $this->Url->build(['action' => 'edit', 'id' => $contact->id]) ?>" target="_blank"><i class="fas fa-edit is-clickable"></i></a></td>
			</tr>
		<?php } ?>
	</tbody>
	</table>
</div>
<?= $this->element('pagination'); ?>
