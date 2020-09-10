<div class="card table-container">
	<table class="table" style="font-size:14px">
	<thead>
		<tr>
			<th>ID</th>
			<th>Name</th>
			<th>Phone Number</th>
			<th>Address</th>
			<th>State</th>
			<th>Messages</th>
			<th>Joined</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($users as $user) { ?>
			<tr>
				<td><?= $user->id ?></td>
				<td><?= $user->fullName ?></td>
				<td><?= $user->phoneNumber ?></td>
				<td><?= $user->address ?></td>
				<td><?= $user->address_state ?></td>
				<td><?= $user->messages ?></td>
				<td><?= date('m-d-Y', strtotime($user->created)) ?></td>
			</tr>
		<?php } ?>
	</tbody>
	</table>
</div>

<!-- Sort Functionality -->