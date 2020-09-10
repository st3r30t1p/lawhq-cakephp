<?= $this->element('contacts_view_menu') ?>

<div class="card table-container">
	<table class="table is-hoverable">
		<thead>
			<tr>
				<th>Message</th>
				<th>Thread</th>
				<th>Recevied</th>
				<th style="width:115px">From</th>
				<th style="width:125px">To</th>
				<th>Message</th>
				<th style="width:100px">Rule</th>
				<th>Matter</th>
			</tr>
		</thead>
		<tbody style="font-size:smaller;">
			<?php foreach ($groupedMessages as $message) { ?>
				<tr>
					<td><?= $message['message_id'] ?></td>
					<td><?= $this->Html->link($message['thread_id'], ['controller' => 'threads', 'action' => 'view', 'id' => $message['thread_id']]); ?></td>
					<td><?= $message['received_time'] ?></td>
					<td><?= $this->Html->link($this->Phone->format($message['from_phone']), ['?' => ['from' => $message['from_phone']]]); ?></td>
					<td><?= $message['user_name'] ?><br>(<?= $this->Html->link($this->Phone->format($message['to_phone']), ['?' => ['to' => $message['to_phone']]]); ?>)</td>
					<td><?= $message['body'] ?></td>
					<td><?= implode(', ', $message['rules']) ?></td>
					<td></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</div>