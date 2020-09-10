<?php
	$this->assign('title', 'Groups');
	$this->set('wunder_title', 'Groups');
?>
<ul class="flex even-spacing" style="justify-content: start">
	<li style="margin-right:20px">
		<h1 class="title is-4">Groups</h1>
	</li>
	<li>	
		<a class="button is-small is-green" href="<?= $this->Url->build(['action' => 'add']) ?>">
		   <span class="icon is-small">
		     <i class="fas fa-plus"></i>
		   </span>
		   <span>New Group</span>
		 </a>
	</li>
</ul>

<div class="table-container">
	<table class="table is-hoverable" style="width:auto">
	<thead>
	<tr>
		<th>Name</th>
		<th>Users</th>
		<th>Threads</th>
		<th>Messages</th>
		<th>Last Message</th>
		<th></th>
	</tr>
	</thead>
	<tbody style="font-size:smaller;">
	<?php foreach ($threadGroups as $group) {?>
	<tr>
		<td><?= h($group->name); ?></td>
		<?php if (isset($tgStats[$group->id])) { ?>
		<td style="text-align:right"><?= $tgStats[$group->id]->user_count ?></td>
			<td style="text-align:right"><?= $tgStats[$group->id]->thread_count ?></td>
			<td style="text-align:right"><?= $tgStats[$group->id]->msg_count ?></td>
			<td style="text-align:right"><?= $tgStats[$group->id]->last_message ?></td>
		<?php } else { ?>
			<td style="text-align:right">-</td>
			<td style="text-align:right">-</td>
			<td style="text-align:right">-</td>
			<td style="text-align:right">-</td>
		<?php } ?>
		<td>
			<a class="button is-green is-small" href="<?= $this->Url->build(['action' => 'view', 'id' => $group->id]); ?>">View</a>
		</td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
</div>