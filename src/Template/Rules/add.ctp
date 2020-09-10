<div style="max-width:1000px; margin:0 auto">
	<?= $this->Form->create($rule); 
		$this->Form->setTemplates([
		    'inputContainer' => '<div class="field"><div class="control">{{content}}</div></div>',
		    'select' => '<div class="field"><div {{attrs}}><select name="{{name}}">{{content}}</select></div></div>',
		    'inputContainerError' => '<div class="field"><div class="control is-danger">{{content}}</div><p class="help is-danger">{{error}}</p></div>',
		    'error' => '{{content}}',
		    'input' => '<input type="{{type}}" autocomplete="false" name="{{name}}"{{attrs}}/>'
		]);
	?>

	<div class="field">
		<label class="label">Assign To</label>
		<div class="field-body">
			<?= $this->Form->control("contact_id", ['class' => 'select-beast', 'type' => 'select', 'empty' => ' ', 'label' => false, 'options' => $contacts]); ?>
		</div>
	</div>

	<?php if (isset($rule->rule_condition_sets)) { foreach ($rule->rule_condition_sets as $count => $set) { ?>
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

					<?= ($count != 0 || $count == 0 && $key > 0) ? '<div class="remove-rule"><i class="fas fa-minus-circle"></i></div>' : '<div style="width: 16px;">&nbsp;</div>'; ?>
				</div>	
			<?php } ?>

			<div class="add-condition">
				<a><i class="fas fa-plus-circle"></i> Add Rule</a>
			</div>
		</div>
	<?php }} else { ?>
		<div class="field rule-set" data-count="1" data-index="0">
			<label class="label">Rule Set 1</label>
			<div class="field-body" data-rule-id="0">
				<?= $this->Form->select('rule_condition_sets.0.rule_conditions.0.type', [
				    'domain' => 'Domain',
				    'message_text' => 'Message Text',
				    'phone_number' => 'Phone Number',
				    'ips' => 'IP Address',
				    'reg_email' => 'Domain Registrant Email',
				    'reg_name' => 'Domain Registrant Name'
				], ['class'=> 'select']); ?>

				<?= $this->Form->select('rule_condition_sets.0.rule_conditions.0.search_type', [
				    'contains' => 'Contains'
				], ['class'=> 'select']); ?>

				<?= $this->Form->control('rule_condition_sets.0.rule_conditions.0.search_for', ['type' => 'text', 'label' => false, 'class' => 'input rule-sf', 'placeholder' => 'Value...']); ?>

				<div class="remove-rule rr-0"><i class="fas fa-minus-circle rr-0"></i></div>
			</div>

			<div class="add-condition">
				<a><i class="fas fa-plus-circle"></i> Add Rule</a>
			</div>
		</div>
	<?php } ?>
	
	<div class="field">
		<button class="button is-small is-green add-rule-set">Add Rule Set</button>
	</div>

	<div class="field">
		<label class="label">Reason</label>
		<div class="field-body">
			<?= $this->Form->textarea('reason', ['label' => false, 'class' => 'textarea', 'placeholder' => 'Reason...']); ?>
		</div>
	</div>

	<div class="even-spacing">
		<?= $this->Form->submit('Save', ['class' => 'button is-green is-small']); ?>
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
.remove-rule:not(.rr-0) {
	display: flex;
	align-items: center;
	cursor: pointer;
}
.rr-0 {
	visibility: hidden;
}
</style>

<?php echo $this->Html->script('rules/index'); ?>

<script type="text/javascript">
	$('.select-beast select').selectize({
		placeholder: 'Select Contact...'
	});
</script>