<div style="width:500px">
	<?= $this->Form->create($file) ?>
	<?php $this->Form->setTemplates([
		'select' => '<div {{attrs}}><select name="{{name}}" required>{{content}}</select></div>'
	]); ?>

	<div class="field">
		<label class="label">Title</label>
		<?= $this->Form->text('title', ['class' => 'input is-small', 'placeholder' => 'Title', 'required' => 'required']) ?>
	</div>

	<div class="field">
		<label class="label">Type</label>
		<?= $this->Form->select('type', [
	    	'screenshot' => 'Screenshot',
	    	'pagevault' => 'PageVault',
	    	'video' => 'Video'
		], ['class' => 'select is-small is-fullwidth', 'empty' => 'Type']); ?>
	</div>

	<div class="field">
		<label class="label">Date Captured</label>
		<?= $this->Form->text('date_captured', ['class' => 'input is-small', 'placeholder' => 'Date Captured', 'required' => 'required', 'value' => date('m/d/Y', strtotime($file->date_captured))]) ?>
	</div>

	<div class="even-spacing">
		<?= $this->Form->submit('Save', ['class' => 'button is-green is-small']) ?>
		<?= $this->Form->end() ?>
	</div>
</div>

<iframe src="<?= $this->Url->build(['controller' => 'files', 'action' => 'get', $file->file->sha1]) ?>" style="width:100%;height:500px;"></iframe>

<script type="text/javascript">
	$(function() {
	  $('input[name="date_captured"]').daterangepicker({
	    singleDatePicker: true,
	    showDropdowns: true,
	  }, function(start, end, label) {
	  	$('input[name="date_captured"]').val(start.format('MM/DD/YYYY'));
	  });
	});

</script>