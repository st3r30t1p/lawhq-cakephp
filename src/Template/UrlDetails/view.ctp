<?php
	$this->assign('title', 'Url Details');
	$this->set('wunder_title', 'Url Details');
?>

<div class="row">
<h1>Url: <?php echo h($this->request->getQuery('url')); ?></h1>
</div>

<div class="row">
<div><?php echo $this->Html->link('Re-Crawl', ['?' => ['url' => $this->request->getQuery('url'), 'refresh' => 1]], ['class' => 'btn']); ?></div>
</div>

<div class="row">

<?php foreach ($sessions as $sessionNum => $urlDetails) {?>
<h4>
Session: <?= $sessionNum ?><br>
Crawled: <?= $urlDetails[0]->created; ?>
</h4>
<table class="table table-hover">
<thead class="text-primary">
<tr>
	<th>Req&nbsp;#</th>
	<th>Url</th>
	<th>Response Code</th>
	<th>&nbsp;</th>
</tr>
</thead>
<tbody>
<?php foreach ($urlDetails as $urlDetail) {?>
<tr>
	<td><?= $urlDetail->req_num; ?></td>
	<td style="word-break:break-word;"><?= h($urlDetail->url); ?></td>
	<td style="text-align:center;"><?= $urlDetail->res_code; ?></td>
	<td style="white-space:nowrap">
		<a class="btn" href="<?= $this->Url->build("/messages"); ?>?domain=<?= $urlDetail->domain ?>&tld=<?= $urlDetail->tld ?>">Messages</a>
		<a class="btn" href="<?= $this->Url->build(['controller' => 'domains', 'action' => 'view', 'id' => "{$urlDetail->domain}.{$urlDetail->tld}"]); ?>">Whois</a>
	</td>
</tr>
<?php if ($urlDetail->file) { ?>
<tr>
	<td colspan="3">
		<div style="width:420px;height:600px;overflow:scroll;">
			<img src="<?= $urlDetail->file->getUrl(); ?>" style="width:400px">
		</div>
	</td>
</tr>
<?php } ?>
<?php } ?>
</tbody>
</table>
<?php } ?>

</div>