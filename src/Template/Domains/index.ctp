<?php $this->assign('title', 'Domains'); ?>

<ul class="flex even-spacing">
	<li style="margin-right: auto; flex:1">
		<h1 class="title is-4">Domains</h1>
	</li>
</ul>

<div class="table-container">
	<table class="table is-hoverable" style="width:auto">
		<thead>
			<tr>
				<th>Domain</th>
				<th>Msg Count</th>
				<th colspan="2">&nbsp;</th>
			</tr>
			<tr>
				<?= $this->Form->create(null, ['templates' => ['submitContainer' => '{{content}}']]) ?>
				<th>
					<?= $this->Form->text('domain', ['class' => 'input is-small', 'placeholder' => 'Domain Name']) ?>
				</th>
				<th>
					<div class="select is-small">
						<select name="sort">
							<option value='{"sort":"message_frequency","direction":"DESC"}' <?= ($sort['sort'] == 'message_frequency' && $sort['direction'] == 'DESC') ? 'selected' : '' ?>>Msg Count Desc</option>
							<option value='{"sort":"message_frequency","direction":"ASC"}' <?= ($sort['sort'] == 'message_frequency' && $sort['direction'] == 'ASC') ? 'selected' : '' ?>>Msg Count Asc</option>
							<option value='{"sort":"domain","direction":"DESC"}' <?= ($sort['sort'] == 'domain' && $sort['direction'] == 'DESC') ? 'selected' : '' ?>>Domain Name Desc</option>
							<option value='{"sort":"domain","direction":"ASC"}' <?= ($sort['sort'] == 'domain' && $sort['direction'] == 'ASC') ? 'selected' : '' ?>>Domain Name Asc</option>
						</select>
					</div>
				</th>
				<th>
					<div class="select is-small" style="display:inline-block">
						<select name="limit">
							<option value="250" <?= ($limit == 250) ? 'selected' : '' ?>>Limit: 250</option>
							<option value="500" <?= ($limit == 500) ? 'selected' : '' ?>>Limit: 500</option>
							<option value="1000" <?= ($limit == 1000) ? 'selected' : '' ?>>Limit: 1000</option>
							<option value="all" <?= ($limit == 'all') ? 'selected' : '' ?>>Limit: All</option>
						</select>
					</div>
					<th>
						<?= $this->Form->submit('Seach', ['class' => 'button is-light is-small', 'style' => 'display: inline-block; float: right;']) ?>
					</th>
				</th>

				<?= $this->Form->end() ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($domains as $domain) {?>
			<tr>
				<td><?= $domain->domain; ?></td>
				<td><?= $domain->message_frequency; ?></td>
				<td colspan="2" style="min-width:268px">
					<a class="button is-green is-small" href="<?= $this->Url->build("/messages"); ?>?domain=<?= $domain->domain ?>">Messages</a>
					<a class="button is-green is-small" href="<?= $this->Url->build(['action' => 'view', 'id' => "{$domain->domain}"]); ?>">Whois</a>
					<a class="button is-green is-small" href="<?= $this->Url->build(['action' => 'relationships', 'id' => "{$domain->domain}"]); ?>">Relationships</a>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
<?php if ($limit != 'all') {
	echo $this->element('pagination');
} ?>