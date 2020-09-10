<div style="max-width:1000px; margin:0 auto">
	<?= $this->Form->create($rule->rule); 
		$this->Form->setTemplates([
		    'inputContainer' => '<div class="field"><div class="control">{{content}}</div></div>',
		    'select' => '<div class="field"><div {{attrs}}><select name="{{name}}">{{content}}</select></div></div>',
		    'inputContainerError' => '<div class="field"><div class="control is-danger">{{content}}</div><p class="help is-danger">{{error}}</p></div>',
		    'error' => '{{content}}',
		    'input' => '<input type="{{type}}" autocomplete="false" name="{{name}}"{{attrs}}/>'
		]);
	?>
	<h2 class="title is-3">Edit <?= ($rule->rule->type == 'system') ? 'System Generated Rule' : '' ?></h2>
	<?= $this->Form->hidden('id') ?>
	<fieldset <?= ($rule->rule->type == 'system') ? 'disabled' : '' ?>>
		<div class="field">
			<label class="label">Assign To</label>
			<div class="field-body">
				<?= $this->Form->control("contact_id", ['class' => 'select-beast', 'type' => 'select', 'empty' => ' ', 'label' => false, 'options' => $contacts]); ?>
			</div>
		</div>
	</fieldset>

	<?php if ($rule->rule->type == 'system') { ?>
		<label class="label">Rules</label>
		<?php foreach ($rule->rule->rule_condition_sets as $count => $set) { ?>
			<div>
				<?= $this->Form->hidden("rule_condition_sets.{$count}.id") ?>
				<?php foreach ($set->rule_conditions as $key => $condition) { ?>
					<div>
						<span>Domain</span>
						<span>contains</span>
						<span><?= $condition->search_for ?></span>
					</div>
				<?php } ?>
			</div>
		<?php } ?>
	<?php } else { 
	foreach ($rule->rule->rule_condition_sets as $count => $set) { ?>
		<div class="field rule-set" data-count="<?= $count + 1 ?>" data-index="<?= $count ?>">
			<label class="label">Rule Set <?= $count + 1 ?></label>
			<?= $this->Form->hidden("rule_condition_sets.{$count}.id") ?>
			<?php foreach ($set->rule_conditions as $key => $condition) { ?>
				<div class="field-body" data-rule-id="<?= $key ?>">
					<?= $this->Form->hidden("rule_condition_sets.{$count}.rule_conditions.{$key}.id") ?>
					<?= $this->Form->select("rule_condition_sets.{$count}.rule_conditions.{$key}.type", [
					    'domain' => 'Domain',
					    'message_text' => 'Message Text',
					    'phone_number' => 'Phone Number',
					    'ips' => 'IP Address',
					    'reg_email' => 'Domain Registrant Email',
					    'reg_name' => 'Domain Registrant Name'

					], ['class'=> 'select']); ?>

					<?= $this->Form->select("rule_condition_sets.{$count}.rule_conditions.{$key}.search_type", [
					    'contains' => 'Contains'
					], ['class'=> 'select']); ?>

					<?= $this->Form->control("rule_condition_sets.{$count}.rule_conditions.{$key}.search_for", ['type' => 'text', 'label' => false, 'class' => 'input rule-sf', 'placeholder' => 'Value...']); ?>
					<div class="delete-rule">
						<?= $this->Html->link(__('<i class="fas fa-minus-circle"></i>'), ['controller' => 'RuleConditions', 'action' => 'delete', $condition->id], ['escape' => false, 'confirm' => __('Are you sure you want to delete this rule? ({0})', $condition->search_for)]) ?>
					</div>
				</div>	
			<?php } ?>

			<div class="add-condition">
				<a><i class="fas fa-plus-circle"></i> Add Rule</a>
			</div>
		</div>
	<?php } ?>

	<div class="field">
		<button class="button is-small is-green add-rule-set">Add Rule Set</button>
	</div>
	<?php } ?>

	<br>
	<?php if ($rule->rule->type == 'system') { ?>
		<label class="label">System Reason</label>
		<?= $rule->systemReason ?>
		<br>
	<?php }  ?>

	<div class="field">
		<label class="label">Reason</label>
		<div class="field-body">
			<?= $this->Form->textarea('reason', ['label' => false, 'class' => 'textarea', 'placeholder' => 'Reason...', ($rule->rule->type == 'system') ? '' : 'required']) ?>
		</div>
	</div>

	<br>
	<?php if ($rule->rule->type == 'system') { ?>
		<label class="checkbox">
			<?= $this->Form->checkbox('ignore_rule', ['id' => 'ignore_rule']); ?>
		  Ignore
		</label>
	<?php } ?>

	<div class="field ignore-reason" style="<?= ($rule->rule->ignore_rule) ? '' : 'display:none' ?>">
		<br>
		<label class="label">Ignore Reason</label>
		<div class="field-body">
			<?= $this->Form->textarea('ignore_reason', ['label' => false, 'class' => 'textarea', 'placeholder' => 'Ignore Reason...']); ?>
		</div>
	</div>

	<div class="even-spacing">
		<?= $this->Form->submit('Save & View', ['class' => 'button is-green is-small', 'name' => 'view']); ?>
		<?= $this->Form->submit('Save', ['class' => 'button is-green is-small', 'style' => 'float:right']); ?>
		<?= $this->Form->end() ?>
	</div>

	<br>
	<h2 class="title is-4">Files</h2>
	<div class="table-container">
		<table class="table" style="font-size:smaller;">
			<thead>
				<tr>
					<th>Title</th>
					<th>Type</th>
					<th>Captured</th>
					<th style="width:100px"></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($rule->rule->rule_files as $rule_file) { ?>
					<tr>
						<td><?= $this->Html->link($rule_file->title, ['controller' => 'files', 'action' => 'get', $rule_file->file->sha1], ['target' => '_blank']) ?></td>
						<td><?= ucwords($rule_file->type) ?></td>
						<td><?= date('m-d-Y', strtotime($rule_file->date_captured)) ?></td>
						<td>
							<?= $this->Html->link('Edit', ['controller' => 'RuleFiles', 'action' => 'edit', $rule_file->id]) ?>
							&nbsp;
							<?= $this->Form->postLink(__('Delete'), ['controller' => 'ruleFiles', 'action' => 'delete', $rule_file->id], ['confirm' => __('Are you sure you want to delete {0}?', $rule_file->title)]) ?>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>

	<?= $this->Form->create(null, ['type' => 'file']) ?>
	<?php $this->Form->setTemplates([
		    'select' => '<div class="field"><div {{attrs}}><select name="{{name}}" required>{{content}}</select></div></div>'
	]); ?>

	<div class="field is-horizontal">
	  <div class="field-body">
	    <div class="field">
	    	<?= $this->Form->text('title', ['class' => 'input is-small', 'placeholder' => 'Title', 'required' => 'required']) ?>
	    </div>
		<?= $this->Form->select('type', [
	    	'screenshot' => 'Screenshot',
	    	'pagevault' => 'PageVault',
	    	'video' => 'Video'
		], ['class' => 'select is-small', 'empty' => 'Type']); ?>
	    <div class="field">
	    	<?= $this->Form->text('date_captured', ['class' => 'input is-small', 'placeholder' => 'Date Captured', 'required' => 'required']) ?>
	    </div>
	    <div class="field">
	    	<?= $this->Form->text('file', ['type' => 'file', 'label' => false, 'required' => 'required']) ?>
	    </div>
	  </div>
	</div>
	<div class="field">
		<div class="field-body">
			<?= $this->Form->submit('Upload File', ['class' => 'button is-green is-small']) ?>
			<?= $this->Form->end() ?>
		</div>
	</div>
</div>

<style type="text/css">
select, .select {
	width: 100%;
}
.add-condition {
	font-size: 13px;
	margin-top:10px;
	text-align:right
}
.field-body {
	margin-bottom: 5px;
}
.remove-rule, .delete-rule {
	display: flex;
	align-items: center;
	cursor: pointer;
}

.delete-rule a {
	color: rgb(74, 74, 74) !important;
}
</style>

<?php echo $this->Html->script('rules/index'); ?>

<script type="text/javascript">
	$('.select-beast select').selectize({
		placeholder: 'Select Contact...'
	});
	// $('.disabled select').attr('disabled', true);

	$(function() {
	  $('input[name="date_captured"]').daterangepicker({
	  	drops: 'up',
	    singleDatePicker: true,
	    showDropdowns: true,
	  }, function(start, end, label) {
	  	$('input[name="date_captured"]').val(start.format('MM/DD/YYYY'));
	  });
	});

	$('#ignore_rule').on('change', function() {
		if ($(this).is(":checked")) {
			$('.ignore-reason').show();
			$('[name="ignore_reason"]').prop('required', true);
		} else {
			$('.ignore-reason').hide();
			$('[name="ignore_reason"]').prop('required', false);
		}
	});

</script>