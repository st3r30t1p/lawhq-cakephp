<?php
	$this->assign('title', 'Thread Messages');
	$this->set('wunder_title', 'Thread Messages');

	echo '<div class="row">';
	echo "<h4 class='subtitle is-3'>";
	echo "Thread Id: {$thread->id}<br>";
	echo "User: {$thread->imported_user->name_firstName} {$thread->imported_user->name_lastName}<br>";
	echo "From: " . $this->Phone->format($thread->from_phone) . "<br>";
	echo "To: " . $this->Phone->format($thread->to_phone) . "<br>";
	echo "</h4></div>\n";
?>

<!-- <div class="field is-horizontal">
	<?php //echo $this->Form->create($thread, ['inputContainer' => '{{content}}']); ?>
	<div class="field">
		<?php //echo $this->Form->control('thread_group_id', ['class' => 'control', 'empty' => true, 'onchange' => 'this.form.submit()']); ?>
	</div>
	<div class="field">
		<?php //echo $this->Form->control('matter_id', ['class' => 'control', 'empty' => true, 'onchange' => 'this.form.submit()']); ?>
	</div>
	<?php //echo $this->Form->end(); ?>
</div>
 -->

<div class="even-spacing" style="display: flex;">
	<?php echo $this->Form->create($thread, ['templates' => ['inputContainer' => '<div class="control"><div class="select">{{content}}</div></div>']]); ?>
	<div class="field is-horizontal">
		<div class="field-body">
			<div class="field">
			  <label class="label is-small">Thread Group</label>
			  <?php echo $this->Form->control('thread_group_id', ['class' => 'control', 'empty' => true, 'label' => false, 'onchange' => 'this.form.submit()']); ?>
			</div>

			<div class="field">
			  <label class="label is-small">Matter</label>
			  <?php echo $this->Form->control('matter_id', ['class' => 'control', 'empty' => true, 'label' => false, 'onchange' => 'this.form.submit()']); ?>
			</div>
		</div>
	</div>

	<div class="field is-horizontal">
		<div class="field-body">
			<div class="field">
			  <label class="label is-small">Old Matter</label>
			  <?php echo $this->Form->control('old_matter_id', ['class' => 'control', 'empty' => true, 'label' => false, 'onchange' => 'this.form.submit()']); ?>
			</div>
		</div>
	</div>

	
	<?php echo $this->Form->end(); ?>
</div>

<div class="columns">
	<div class="column is-half">
		<div class="card table-container">
			<table class="table is-striped" style="width:auto">
			<thead>
			<tr>
				<th>Id</th>
				<th>Recevied</th>
				<th>Direction</th>
				<th>Body</th>
			</tr>
			</thead>
			<tbody style="font-size:smaller">
			<?php foreach ($thread->imported_messages as $message) { ?>
			<tr>
				<td><?= $message->id; ?></td>
				<td style="white-space: nowrap"><?= $message->received_time; ?></td>
				<td><?= $message->direction; ?></td>
				<td><?= nl2br($message->formattedBody()); ?></td>
			</tr>
			<?php } ?>
			</tbody>
			</table>
		</div>
	</div>
	<div class="column is-half">
		<div class="card">
			<iframe name="embed_readwrite" src="http://manage.lawhq.com:9001/p/thread-<?= $thread->id ?>?userName=<?= rawurlencode("{$appUser->first_name} {$appUser->last_name}"); ?>" style="width:100%; height:600px;"></iframe>
		</div>
	</div>
</div>

<div class="columns">
	<div class="column is-half">
		<div class="box">
			<div class="contact-info-header"><i class="fas fa-comment-alt"></i> Notes</div>
			<div class="this-id" data-id="<?= $thread->id ?>"></div>
			<div class="notes-table" data-table="threadNotes"></div>
			<div class="save-to" data-field="thread_id"></div>
			<?= $this->element('notes', ['notes' => $thread->thread_notes]) ?>
		</div>
	</div>
</div>
