<?php
	$this->assign('title', $matter->name);
	$this->set('wunder_title', $matter->name);
?>

<div class="even-spacing">
	<a class="button is-green is-small" href="<?= $this->Url->build(['action' => 'report', 'id' => $matter->id]) ?>">Report</a>
</div>

<div class="columns">
	<div class="column">
		<div class="card table-container">

			<table class="table is-striped">
				<thead>
					<tr>
						<th>From</th>
						<th>To</th>
						<th>Last Msg</th>
						<th>Msg Count</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody style="font-size:smaller;">
					<?php foreach ($matter->threads as $thread) {?>
					<tr>
						<td><?= $this->Html->link($this->Phone->format($thread->from_phone), ['?' => ['from' => $thread->from_phone]]); ?></td>
						<td>
							<?= $this->Html->link("{$thread->imported_user->name_firstName} {$thread->imported_user->name_lastName}", ['?' => ['userId' => $thread->imported_user_id]]) ?>
							(<?= $this->Html->link($this->Phone->format($thread->to_phone), ['?' => ['to' => $thread->to_phone]]); ?>)</td>
						<td><?= $thread->last_message_received; ?></td>
						<td><?= $thread->imported_msg_rcvd_count; ?></td>
						<td>
							<a class="button is-green is-small" href="<?= $this->Url->build(['controller' => 'threads', 'action' => 'view', 'id' => $thread->id]); ?>">Messages</a>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>

		</div>
	</div>

	<div class="column">
		<iframe name="embed_readwrite" src="http://manage.lawhq.com:9001/p/matter-<?= $matter->id ?>?userName=<?= rawurlencode("{$appUser->first_name} {$appUser->last_name}"); ?>" style="width:100%; height:600px"></iframe>
	</div>
</div>