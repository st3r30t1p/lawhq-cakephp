<div>
	<h2 class="title is-3">Edit <?= $domain->domain ?></h2>
</div>
<br>
<?= $this->Form->create($domain, ['style' => 'max-width:800px']); ?>
<label class="checkbox">
	<?= $this->Form->checkbox('ignore_on_system_generated_rules', ['checked' => $ignore, 'id' => 'ignore_rule']); ?>
  Ignore on System Generated Rules
</label>

<div class="field ignore-reason" style="<?= ($ignore) ? '' : 'display:none' ?>">
	<br>
	<label class="label">Ignore Reason</label>
	<div class="field-body">
		<?= $this->Form->textarea('ignore_on_system_generated_rules_reason', ['label' => false, 'class' => 'textarea', 'required' => 'required']); ?>
	</div>
</div>
<br>
<br>
<?= $this->Form->submit('Save Domain', ['class' => 'button is-green is-small']) ?>
<?= $this->Form->end() ?>

<script type="text/javascript">
$('#ignore_rule').on('change', function() {
	if ($(this).is(":checked")) {
		$('.ignore-reason').show();
	} else {
		$('.ignore-reason').hide();
	}
});
</script>