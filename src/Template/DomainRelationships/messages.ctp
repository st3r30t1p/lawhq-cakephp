<?php
	$this->assign('title', 'Domain Relationship Messages');
?>

<div class="even-spacing">
	<p class="title is-3">Relationship Messages</p>
	<p class="subtitle is-5"><?= $relationship->domain->domain ?> &nbsp;<i class="fas fa-arrows-alt-h"></i>&nbsp; <?= $relationship->domains_link->domain ?></p>
</div>

<div>
	<div class="card table-container">
		<table class="table is-striped">
		<thead>
		<tr>
			<th>Id</th>
			<th>Thread</th>
			<th>Recevied</th>
			<th>Direction</th>
			<th>Body</th>
		</tr>
		</thead>
		<tbody style="font-size:smaller">
		<?php foreach ($messages as $message) { ?>
		<tr>
			<td><?= $message->imported_message->id; ?></td>
			<td><?= $this->Html->link($message->imported_message->thread_id, ['controller' => 'threads', 'action' => 'view', 'id' => $message->imported_message->thread_id]); ?></td>
			<td style="white-space: nowrap"><?= $message->imported_message->received_time; ?></td>
			<td><?= $message->imported_message->direction; ?></td>
			<td><?= nl2br($message->imported_message->formattedBody()); ?></td>
		</tr>
		<?php } ?>
		</tbody>
		</table>
	</div>
</div>
