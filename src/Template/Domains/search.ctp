<?php
	$this->assign('title', 'Domain Search');
	$this->set('wunder_title', 'Domain Search');
?>

<?php
	echo '<div class="row">';
	echo "<h4>Filter:<br>";
	echo "{$key} = $value<br>";
	echo "</h4></div>\n";
?>

<div class="row">



<table class="table table-hover" style="width:auto">
<thead class="text-primary">
<tr>
	<th>Domains</th>
	<th>Actions</th>
</tr>
</thead>
<tbody>
<?php foreach ($domains as $domain) {?>
<tr>
	<td><?= $domain; ?></td>
	<td>
		<a class="btn" href="<?= $this->Url->build(['controller' => 'messages', 'action' => 'index', '?' => ['domain' => $domain]]); ?>">Messages</a>
		<a class="btn" href="<?= $this->Url->build(['action' => 'view', 'id' => $domain]); ?>">Whois</a>
	</td>
</tr>
<?php } ?>
</tbody>
</table>

</div>