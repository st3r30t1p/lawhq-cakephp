<?php
	$this->assign('title', 'Conference Calls');
	$this->set('wunder_title', 'Conference Calls');
?>

<ul class="flex even-spacing">
	<li style="margin-right: auto; flex:1">
		<h2 class="title is-4">Call: <?= getenv('CONFERENCE_INBOUND_NUMBER') ?></h2>
	</li>
    <li>
		<a class="button is-green is-small" href="<?= $this->Url->build(['action' => 'add']) ?>">
		   <span class="icon is-small">
		     <i class="fas fa-plus"></i>
		   </span>
		   <span>New Conference Room</span>
		 </a>
	</li>
</ul>

<div class="card table-container">
	<table class="table is-hoverable">
		<thead>
			<tr>
				<th>Name</th>
				<th>Host Code </th>
				<th>Participant Code</th>
				<th>Listener Code</th>
				<th>Date</th>
				<th>Created By</th>
				<th></th>
			</tr>
		</thead>
		<tbody style="font-size:smaller;">
		<?php foreach ($conferences as $conference) { ?>
			<tr data-id=<?= $conference->id ?>>
				<td><a href="<?= $this->Url->build(['action' => 'edit', 'id' => $conference->id]); ?>"><?= $conference->name ?></a></td>
				<td><?= $conference->hac ?></td>
				<td><?= $conference->pac ?></td>
				<td><?= $conference->lac ?></td>
				<td><?= $conference->schedule_date ?></td>
				<td><?= $conference->team_member->first_name." ".$conference->team_member->last_name ?></td>
				<td>
					<a class="copy-btn" style="color: black;" pac-data="<?= $conference->pac?>"><i class="far fa-fw fa-copy is-clickable"></i></a>
				</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
</div>
<script>
	$(function(){
		var clipboard = new ClipboardJS('.copy-btn', {
			text: function(trigger){
				var text = 'To access the conference room, call ' + '<?= getenv('CONFERENCE_INBOUND_NUMBER') ?>' + '. Then use access code ' + trigger.getAttribute('pac-data') + '.';
				return text;
			}	
		});
	});
</script>