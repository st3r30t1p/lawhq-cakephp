<?php
	$this->assign('title', 'Threads');
	$this->set('wunder_title', 'Threads');
?>

<div class="even-spacing">
	<h1 class="title is-4">Threads</h1>
</div>

<div class="card table-container">
	<table class="table is-hoverable" style="width:100%">
		<thead>
			<tr>
				<th>ID</th>
				<th>From</th>
				<th>To</th>
				<th>Msg Count</th>
				<th>Last Msg Imported</th>
				<th>Group</th>
				<th>Matter</th>
				<th style="width: 225px;">Matter Old</th>
				<th>Rule</th>
				<th></th>
			</tr>
		</thead>
		<tbody style="font-size:smaller;">
			<?php foreach ($threads as $thread) { ?>
			<tr>
				<td><?= $thread->id  ?></td>
				<td><?= $this->Html->link($this->Phone->format($thread->from_phone), ['?' => ['from' => $thread->from_phone]]); ?></td>
				<td>
					<?= $this->Html->link("{$thread->imported_user->name_firstName} {$thread->imported_user->name_lastName}", ['?' => ['userId' => $thread->imported_user_id]]) ?>
					(<?= $this->Html->link($this->Phone->format($thread->to_phone), ['?' => ['to' => $thread->to_phone]]); ?>)</td>
				<td style="text-align:center"><?= $thread->imported_msg_rcvd_count; ?></td>
				<td><?= $thread->modified; ?></td>
				<td><?= $thread->thread_group->name ?? ''; ?></td>
				<td><?= $thread->matter->name ?? ''; ?></td>
				<td><?= $thread->old_matter->name ?? '' ?></td>
				<td><?= $thread->ruleIds() ?></td>
				<td>
					<a class="button is-green is-small" href="<?= $this->Url->build(['action' => 'view', 'id' => $thread->id]); ?>">Messages</a>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
<?= $this->element('pagination'); ?>