<?php
	$this->assign('title', 'Matters');
	$this->set('wunder_title', 'Matters');
?>
<ul class="flex even-spacing">
	<li style="margin-right: auto; flex:1">
		<div class="field has-addons">
			<div class="control" style="width: 100%; max-width: 400px;">
				<?= $this->Form->create(null, ['type' => 'GET']); ?>
				<?= $this->Form->text('name', ['class' => 'input is-small', 'placeholder' => 'Search...', 'style' => 'width:100%', 'value' => (isset($search['name'])) ? $search['name'] : '']); ?>
			</div>
			<div class="control">
				<div class="select is-small">
					<?= $this->Form->select('status', ['open' => 'Open', 'closed' => 'Closed', 'pending' => 'Pending'], ['empty' => 'Status', 'value' => (isset($search['status'])) ? $search['status'] : '']) ?>
				</div>
			</div>
			<div class="control">
			  <?= $this->Form->submit('Search', ['class' => 'button is-inverted is-small']); ?>
			  <?= $this->Form->end(); ?>
			</div>
		</div>
	</li>
	<li>
		<a class="button is-green is-small" href="<?= $this->Url->build(['action' => 'add']) ?>">
		   <span class="icon is-small">
		     <i class="fas fa-plus"></i>
		   </span>
		   <span>New Matter</span>
		 </a>
	</li>
</ul>

<div class="table-container">
	<table class="table is-hoverable is-narrow">
	<thead>
	<tr>
		<th>Name</th>
		<th>Status</th>
		<th>Users</th>
		<th>Threads</th>
		<th>Messages</th>
		<th>Last Message</th>
		<th></th>
	</tr>
	</thead>
	<tbody style="font-size:smaller;">
	<?php foreach ($matters as $matter) {?>
	<tr>
		<td><?= $this->Html->link(h($matter->id .' - '. $matter->name), ['action' => 'view', 'id' => $matter->id]); ?></td>
		<td><?= ucfirst($matter->status) ?></td>
		<?php if (isset($tgStats[$matter->id])) { ?>
			<td style="text-align:right"><?= $tgStats[$matter->id]->user_count ?></td>
			<td style="text-align:right"><?= $tgStats[$matter->id]->thread_count ?></td>
			<td style="text-align:right"><?= $tgStats[$matter->id]->msg_count ?></td>
			<td style="text-align:right"><?= $tgStats[$matter->id]->last_message ?></td>
		<?php } else { ?>
			<td style="text-align:right">-</td>
			<td style="text-align:right">-</td>
			<td style="text-align:right">-</td>
			<td style="text-align:right">-</td>
		<?php } ?>
		<td>
			<a class="button is-green is-small" href="<?= $this->Url->build(['action' => 'view', 'id' => $matter->id]); ?>">View</a>
			<a class="button is-green is-small" href="<?= $this->Url->build(['action' => 'edit', 'id' => $matter->id]); ?>">Edit</a>
            <a class="button is-green is-small" href="<?= $this->Url->build(['action' => 'documents', 'id' => $matter->id]); ?>">Documents</a>
		</td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
</div>
<?= $this->element('pagination'); ?>
