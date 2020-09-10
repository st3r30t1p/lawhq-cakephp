<?php
	$this->assign('title', 'Users');
	$this->set('wunder_title', 'Users');
?>
<?php if ($permissionManageUsers) { ?>
	<div class="even-spacing">
		<a class="button is-green is-small" href="<?= $this->Url->build(['action' => 'add']) ?>">
		   <span class="icon is-small">
		     <i class="fas fa-plus"></i>
		   </span>
		   <span>New User</span>
		 </a>
	</div>
<?php } ?>

<div class="box">
	<table class="table is-hoverable sortable" >
	<thead>
		<tr>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Username</th>
			<th>Extension</th>
			<th>City</th>
			<th>State</th>
			<?php if ($permissionManageUsers) { ?>
				<th>Manage Users</th>
				<th></th>
			<?php } ?>
		</tr>
	</thead>
	<tbody style="font-size:smaller">
		<?php foreach ($users as $user) { ?>
		<tr>
			<td><?= ucfirst($user->first_name); ?></td>
			<td><?= ucfirst($user->last_name); ?></td>
			<td><?= $user->username; ?></td>
			<td><?= $user->extension ?></td>
			<td><?= ($user->city) ? $user->city : '-' ?></td>
			<td><?= ($user->States['state']) ? $user->States['state'] : '-' ?></td>
			<?php if ($permissionManageUsers) { ?>
				<td><?= $user->manage_users ? 'X' : ''; ?></td>
				<td><a class="btn" href="<?= $this->Url->build(['action' => 'edit', 'id' => $user->id]); ?>"><i class="fas fa-edit is-clickable"></i></a></td>
			<?php } ?>
		</tr>
		<?php } ?>
	</tbody>
	</table>
</div>