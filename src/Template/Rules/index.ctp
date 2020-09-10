<ul class="flex even-spacing">
	<li style="margin-right: auto; flex:1">
		<h1 class="title is-4">Rules</h1>
	</li>

	<li>	
		 <div class="is-inline-block">
		 	<?= $this->Form->create(null); ?>
		 	<div class="select">
		 	  <select name="type" onchange="this.form.submit()">
		 	  	<option value="all" <?= ($type == 'all') ? 'selected' : '' ?>>All rules</option>
		 	    <option value="user" <?= ($type == 'user') ? 'selected' : '' ?>>User rules</option>
		 	    <option value="system" <?= ($type == 'system') ? 'selected' : '' ?>>System rules</option>
		 	  </select>
		 	</div>
		 	<?= $this->Form->end() ?>
		 </div>
		<div class="is-inline-block">
			<a class="button is-green" href="<?= $this->Url->build(['action' => 'add']) ?>">
			   <span class="icon is-small">
			     <i class="fas fa-plus"></i>
			   </span>
			   <span>New Rule</span>
			 </a>
		</div>
	</li>
</ul>

<?= $this->element('rules_menu') ?>

<div class="card table-container">
	<table class="table" style="font-size:15px">
	<thead>
		<tr>
			<th>ID</th>
			<th style="width: 325px;">Assign To</th>
			<th>Rules</th>
			<td style="width:50px"></td>
			<th style="width:100px">Count</th>
			<th style="width:100px">Conflicts</th>
			<th style="width:50px"></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($rules as $rule) { ?>
			<tr class="no-border">
				<td><?= $rule->rule->id  ?></td>
				<td class="assign-to">
					<?= $this->Html->link($rule->rule->contact->id . ' - ' . $rule->rule->contact->nameWithState, ['controller' => 'Contacts', 'action' => 'spam', '?' => ['contact-id' => $rule->rule->contact_id]]) ?>
					<?= ($rule->rule->type == 'system') ? '&nbsp; <i class="fas fa-database has-text-grey-light"></i>' : '' ?>
				</td>
				<td>
					<?php foreach ($rule->rule->rule_condition_sets as $count => $set) { ?> 

						<?php if ($count == 6) { echo '<div class="more-rule-sets">...</div>'; } ?>

						<div class="<?= ($count >= 6) ? 'hidden-sets' : '' ?>">
							<?php foreach ($set->rule_conditions as $key => $condition) { ?>
								<div class="rule-condition">
									<div style="margin-right:10px;width: 20.2px;"><?= $condition->getAbbrev() ?></div>
									<div title="<?= $condition->search_for ?>" style="flex-grow: 1;"><?= $condition->search_for ?></div>
								</div>
							<?php } ?>
							<?= ($count + 1 < sizeof($rule->rule->rule_condition_sets)) ? '<br>' : '' ?>
						</div>
					<?php } ?>
				</td>
				<td>
					<span style="color: #e8e8e8;cursor:pointer" class="toggle-reason">&#9660;</span>
				</td>
				<td>
					<?= $this->Html->link($rule->rule->ruleAppliedCount(), ['action' => 'assignments', '?' => ['rule' => $rule->rule->id]]) ?></td>
				<td><?= $rule->rule->ruleConflictsCount() ?></td>
				<td><a href="<?= $this->Url->build(['action' => 'edit', 'id' => $rule->rule->id]) ?>"><i class="fas fa-edit is-clickable"></i></a></td>
			</tr>
			<tr style="display:none">
				<td></td>
				<td></td>
				<td>
					<?= ($rule->rule->reason) ? nl2br(h($rule->rule->reason)) : '' ?>
					<?= $rule->systemReason ?>
					<?php if (!empty($rule->rule->system_generated_rules)) {
						foreach ($rule->rule->system_generated_rules as $system_rule) {
							if (empty($system_rule->based_off_rule)) {
								//echo $system_rule->generateReason();
								//echo $system_rule->findUserRulesThatDomainRedirectsWith();
							} else {
								//echo $system_rule->generateRecursiveReason();
							}
						}
					} ?>
				</td>
			</tr>
		<?php } ?>
	</tbody>
	</table>
</div>
<?= $this->element('pagination'); ?>

<script type="text/javascript">
$('.toggle-reason').on('click', function() {
	$(this).closest('tr').next().toggle();
	$(this).html($(this).text() == '▼' ? '▲' : '▼');
	$('.hidden-sets').toggle();
	$('.more-rule-sets').toggle();
});
</script>

<style type="text/css">


.assign-to {
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
	font-weight: 600;
}

.rules-no-border td {
	border-bottom: none;
}

.no-border td {
	border-top: 1px solid #dbdbdb;
	border-bottom: none;
}

.last-rule td {
	padding-bottom: 15px !important;
}

.hidden-sets {
	display: none;
}
</style>