<?php $this->assign('title', $matter->name); ?>

<?= $this->element('matters_view_menu') ?>

<div class="columns">
	<div class="column">
		<div class="box">
			<div class="contact-info-header"><i class="fas fa-file-medical-alt"></i> Details</div>
			<table class="matter-information-table">
				<tr>
					<td>Name</td>
					<td><?= h($matter->name) ?></td>
				</tr>
				<tr>
					<td>Practice Area</td>
					<td><?= strtoupper($matter->practice_area) ?></td>
				</tr>
				<tr>
					<td>Responsible Attorney</td>
					<td><?= (isset($matter->responsible_attorney)) ? $matter->responsible_attorney->team_member->full_name : '' ?></td>
				</tr>
				<tr>
					<td>Responsible Paralegal</td>
					<td><?= (isset($matter->responsible_paralegal)) ? $matter->responsible_paralegal->team_member->full_name : '' ?></td>
				</tr>
				<tr>
					<td>Created</td>
					<td><?= date('M j Y', strtotime($matter->created)) ?></td>
				</tr>
				<tr>
					<td>Status</td>
					<td><?= ucfirst($matter->status) ?></td>
				</tr>
				<?php foreach ($matter->matter_courts as $key => $court) { ?>
					<tr>
						<td><?= ($key == 0) ? 'Courts' : '' ?></td>
						<td><?= $court->court->name ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $court->case_number ?></td>
					</tr>
				<?php } ?>
			</table>
		</div>
	</div>

	<div class="column">
		<div class="box">
			<div class="contact-info-header"><i class="fas fa-tasks"></i> Tasks</div>
		</div>
	</div>
</div>


<div class="columns">
	<div class="column">
		<div class="box">
			<div class="contact-info-header"><i class="fas fa-stream"></i> Activity</div>
		</div>
	</div>

	<div class="column">
		<div class="box">
			<div class="contact-info-header"><i class="fas fa-comment-alt"></i> Notes</div>
			<div class="this-id" data-id="<?= $matter->id ?>"></div>
			<div class="notes-table" data-table="matterNotes"></div>
			<div class="save-to" data-field="matter_id"></div>
			<?= $this->element('notes', ['notes' => $matter->matter_notes]) ?>
		</div>
	</div>
</div>