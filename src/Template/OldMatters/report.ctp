<?php
	$this->assign('title', $matter->name);
	$this->set('wunder_title', $matter->name);
?>


<?php
	foreach ($groupedData as $group) {
		$hasSent = false;
?>
<h4><?= h($group['user']->name_firstName) ?> <?= h($group['user']->name_lastName) ?></h4>
<ol style="font-size:smaller">
<?php foreach ($group['threads'] as $thread) {?>
<?php foreach ($thread->imported_messages as $i => $message) { ?>
<?php
	if ($message->direction != 'received') {
		$hasSent = true;
		continue;
	}
?>
	<li>From <?= $this->Phone->format($thread->from_phone) ?>, To <?= $this->Phone->format($thread->to_phone) ?>, On <?= $message->received_time; ?>, "<?= $message->formattedBodyNoLinks(); ?>"
	</li>
<?php } ?>
<?php } ?>
</ol>

<?php if ($hasSent) { ?>
<p><b>Sent:</b></p>
<ol style="font-size:smaller">
<?php foreach ($group['threads'] as $thread) {?>
<?php foreach ($thread->imported_messages as $i => $message) { ?>
<?php if ($message->direction != 'sent') continue; ?>
	<li>From <?= $this->Phone->format($thread->to_phone) ?>, To <?= $this->Phone->format($thread->from_phone) ?>, On <?= $message->received_time; ?>, "<?= $message->formattedBodyNoLinks(); ?>"
	</li>
<?php } ?>
<?php } ?>
</ol>
<?php } ?>
<?php } ?>