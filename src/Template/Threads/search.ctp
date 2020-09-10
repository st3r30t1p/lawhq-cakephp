<?php
	$this->assign('title', 'Search Threads Notes');
	$this->set('wunder_title', 'Search Threads Notes');
?>

<?php echo $this->Form->create(null, ['style' => 'max-width:700px']); ?>

  <div class="field is-grouped even-spacing">
    <div class="control is-expanded">
      <input class="input" type="text" required="required" name="note" placeholder="Search Notes">
    </div>
    <div class="control">
      <?php echo $this->Form->submit('Search', ['class' => 'button is-green']); ?>
    </div>
  </div>
<?php echo $this->Form->end(); ?>


<?php if (!empty($notes)) { ?>
<table class="table is-hoverable" style="width:auto">
	<thead>
		<tr>
			<th>Note</th>
      <th></th>
		</tr>
	</thead>
	<tbody style="font-size:smaller;">
	<?php foreach ($notes as $note) { ?>
		<tr>
			<td>
				<p class="wbreak">
					<?= $note->note; ?>
				</p>
			</td>
      		<td><?= $this->Html->link('View', ['action' => 'view', 'id' => $note->thread_id], ['target' => '_blank', 'class' => 'button is-green is-small']); ?></td>
		</tr>
	<?php } ?>
	</tbody>
</table>
<?php } ?>

<style type="text/css">
.wbreak {
	overflow-wrap: break-word;
	word-wrap: break-word;
	word-break: break-word;
	-ms-hyphens: auto;
	-moz-hyphens: auto;
	-webkit-hyphens: auto;
	hyphens: auto;
}
</style>