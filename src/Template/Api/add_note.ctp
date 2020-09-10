<div class="note" data-note-id="<?= $note->id ?>">
	<div class="note-postedby">
		<?= '<span class="name">' . $note->team_member->first_name . ' ' . $note->team_member->last_name . '</span>  <span class="date">' .  $note->created . '</span>' ?>
		<?= ($note->edit_note_id) ? '  <span class="date"><i class="fas fa-pen" style="font-size: 10px;"></i> ' . $note->modified . '</span>' : ''; ?>
	</div>
	<div class="note-text"><?= $this->Link->findUrlsInText(nl2br(h($note->note))) ?></div>

	<?php if ($note->user_id == $appUser->id) { ?>
		<div class="note-options" style="display:none">
			<button class="edit-note button is-smaller is-info is-light">Edit</button>
			<button class="delete-note button is-smaller is-danger is-light is-hidden">Delete</button>
			<button class="cancel-edit-note button is-smaller is-warning  is-light is-hidden">Cancel</button>
			<button class="save-note button is-smaller is-success is-light is-hidden">Save</button>
		</div>
	<?php } ?>
</div>