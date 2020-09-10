<ul class="flex even-spacing">
	<li style="margin-right: auto; flex:1">
		<h1 class="title is-4">Rules Approve</h1>
	</li>
	<li>	
		<?= $this->Form->create(null); ?>
		<div class="select">
		  <select name="status" onchange="this.form.submit()">
		    <option value="pending" <?= ($status == 'pending') ? 'selected' : '' ?>>Pending</option>
		    <option value="approved" <?= ($status == 'approved') ? 'selected' : '' ?>>Approved</option>
		    <option value="ignore" <?= ($status == 'ignore') ? 'selected' : '' ?>>Ignored</option>
		    <option value="all" <?= ($status == 'all') ? 'selected' : '' ?>>All</option>
		  </select>
		</div>
		<?= $this->Form->end() ?>
	</li>
</ul>
<?= $this->element('rules_menu') ?>

<div class="card table-container">
	<table class="table" style="font-size:15px">
	<thead>
		<tr>
			<th style="width: 325px;">Will Assign To</th>
			<th>Depth</th>
			<th>Rule</th>
			<th>Approve</th>
			<th>Ignore</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($systemGeneratedRules as $rule) { ?>
			<tr class="no-border">
				<td class="assign-to">
					<?= $rule->rule->getContactName() ?>
				</td>
				<td><?= $rule->depth;  ?></td>
				<td>
					<div>
						<div class="rule-condition" style="padding-bottom:5px">
							<div style="margin-right:10px;width: 20.2px;">D</div>
							<div title="<?= $rule->rule->domain->domain ?>" style="flex-grow: 1;"><?= $rule->rule->domain->domain ?></div>
						</div>
	
						<p>
						<?php if (!empty($rule->rule->rule_condition_set)) { ?>
							<?= $rule->rule->generateReason() ?>
						<?php } else if (!empty($rule->rule->based_off_rule)) { ?>
							<?= $rule->rule->generateAltReason() ?>
						<?php } ?>
						</p>
					</div>
				</td>
				<?php if ($rule->rule->status == 'pending') { ?>
					<td>
						<?= $this->Form->postLink(__('Approve'), 
							['controller' => 'SystemGeneratedRules', 'action' => 'approve', $rule->rule->id, '?' => ['contact_id' => $rule->rule->getContactId(), 'page' => $page]], 
							['confirm' => __('Approve the rule {0} for {1}?', [$rule->rule->domain->domain, $rule->rule->getContactName()]), 'class' => 'button is-green is-small']) 
						?>
					</td>
					<td>
						<?= $this->Html->link('Ignore', ['controller' => 'Domains', 'action' => 'edit', $rule->rule->domain->domain, '?' => ['ignore' => '1']], ['class' => 'button is-light is-small']) ?>
					</td>
				<?php } else { ?>
					<td>
						<?php if ($rule->rule->status == 'approved') { ?>
							<i class="fas fa-check"></i>
						<?php } ?>
					</td>
					<td>
						<?php if ($rule->rule->domain->ignore_on_system_generated_rules) { ?>
							<i class="fas fa-check"></i>
						<?php } ?>
					</td>
				<?php } ?>
			</tr>
		<?php } ?>
	</tbody>
	</table>
</div>
<?= $this->element('pagination'); ?>