<?php
	$this->assign('title', 'Domains');
?>

<div class="even-spacing">
	<p class="title is-3 is-spaced">Relationships</p>
	<p class="subtitle is-5"><?= $domain->domain ?></p>
</div>

<div class="table-container">
	<table class="table is-hoverable" style="width:auto">
		<thead>
			<tr>
				<th>Domain</th>
				<th>Count</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($domainRelationships as $domainRelationship) {
				$name = $domainsTable->getName($domainRelationship->getOtherDomainName($domain->id));
			?>
			<tr>
				<td><?= $this->Html->link($name, ['action' => 'relationships', 'id' => $name]) ?></td>
				<td style="text-align:right"><?= $this->Html->link($domainRelationship->count, ['controller' => 'DomainRelationships', 'action' => 'messages', $domainRelationship->id]); ?></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
