<?php
	$this->assign('title', 'Messages');
	$this->set('wunder_title', 'Messages');
?>

<?php if ($filter) {
	echo '<div class="row">';
	echo "<h4>Filter:<br>";
	foreach ($filter as $key => $value) {
		echo "{$key} = $value<br>";
	}
	echo "</h4></div>\n";
} ?>

<div class="even-spacing">
	<h1 class="title is-4">Messages</h1>
</div>

<div class="table-container">
<table class="table is-hoverable" style="table-layout:fixed; width: 100%;">
	<thead>
		<tr>
			<th style="width:55px">Id</th>
			<th style="width:75px">Thread</th>
			<th>Recevied</th>
			<th>From</th>
			<th>To</th>
			<?php if ($showDirection) { ?><th>Direction</th><?php } ?>
			<th style="width:57%">Msg</th>
			<th>Rule</th>
			<th>Matter</th>
		</tr>
	</thead>
	<tbody style="font-size:smaller">
	<?php foreach ($messages as $message) { ?>
		<tr>
			<td style="text-align:center"><?= $message->id; ?></td>
			<td style="text-align:center"><?= $this->Html->link($message->thread->id, ['controller' => 'threads', 'action' => 'view', 'id' => $message->thread->id]); ?></td>
			<td style="white-space: nowrap"><?= $message->received_time; ?></td>
			<td style="white-space: nowrap"><?= $this->Html->link($this->Phone->format($message->thread->from_phone), ['?' => ['from' => $message->thread->from_phone]]); ?></td>
			<td style="white-space: nowrap"><?= $message->thread->imported_user->name_firstName; ?>&nbsp;<?= $message->thread->imported_user->name_lastName; ?><br>(<?= $this->Html->link($this->Phone->format($message->thread->to_phone), ['?' => ['to' => $message->thread->to_phone]]); ?>)</td>
			<?php if ($showDirection) { ?><td><?= $message->direction; ?></td><?php } ?>
			<td style="word-wrap:break-word"><?= $message->formattedBody(); ?></td>
			<td><?= $message->rule_assignment['rule_id'] ?></td>
			<td><?= $message->thread->matter_id ?></td>
		</tr>
	<?php } ?>
	</tbody>
</table>
</div>
<?= $this->element('pagination'); ?>