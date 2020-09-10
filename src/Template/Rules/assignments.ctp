<div style="margin-bottom:20px">
	<ul class="flex">
		<li style="margin-left:5px">
			<h1 class="title is-4"><?= $rule->contact->name . ' ' . $rule->contact->getPersonStateOrCompanyIncIn() ?></h1>
		</li>
	</ul>
</div>

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
				<th>Rule</th>
				<th>Group</th>
				<th>Matter</th>
			</tr>
		</thead>
		<tbody style="font-size:smaller;">
			<?php foreach ($rule->rule_condition_sets as $set) {
				foreach ($set->rule_assignments as $message) { ?>
					<tr>
						<td><?= $message->imported_message->id ?></td>
						<td><?= $this->Html->link($message->imported_message->thread_id, ['controller' => 'threads', 'action' => 'view', 'id' => $message->imported_message->thread_id]); ?></td>
						<td><?= $message->imported_message->received_time ?></td>
						<td><?= $this->Html->link($this->Phone->format($message->imported_message->thread->from_phone), ['?' => ['from' => $message->imported_message->thread->from_phone]]); ?></td>
						<td><?= $message->imported_message->thread->imported_user->name_firstName; ?>&nbsp;<?= $message->imported_message->thread->imported_user->name_lastName; ?><br>(<?= $this->Html->link($this->Phone->format($message->imported_message->thread->to_phone), ['?' => ['to' => $message->imported_message->thread->to_phone]]); ?>)</td>
						</td>
						<td><?= $message->imported_message->formattedBody() ?></td>
						<td><?= (isset($conflicts[$message->imported_message_id])) ? implode(', ', $conflicts[$message->imported_message_id]) : $rule->id ?></td>
						<td><?= (isset($message->imported_message->thread->thread_group)) ? $message->imported_message->thread->thread_group->name : '-' ?></td>
						<td></td>
					</tr>
				<?php }
			} ?>
		</tbody>
	</table>
</div>