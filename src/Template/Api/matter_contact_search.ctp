<?php if ($searchresults->count() == 0) { ?>
	<ul class="flex" style="padding:10px">
		<li>No Results</li>
		<?php if ($table == 'Contacts') { ?>
			<li>
				<?= $this->Html->link('<i class="fas fa-plus-circle"></i> Add', ['controller' => 'Contacts', 'action' => 'add'], ['class' => 'api-add-contact', 'target' => '_blank', 'escape' => false]) ?>
			</li>
		<?php } ?>
	</ul>

	<div class="refresh-list-link" style="text-align:center; padding:10px; display:none">
		<a class="matters-search-refresh"><i class="fas fa-redo-alt"></i> Refresh</a>
	</div>
<?php } else { ?>
	<ul class="">
		<?php foreach ($searchresults as $result) { ?>
			<li class="contact-relationship matter-contact" data-id="<?= $result->id ?>" data-name="<?= ($table == 'Contacts') ? $result->name : $result->full_name ?>">
				<span><?= ($table == 'Contacts') ? $result->name : $result->full_name ?></span>
				<?php if (!empty($result->phoneNumber)) { ?>
					<span> - <?= $result->phoneNumber ?></span>
				<?php } ?>
			</li>
		<?php } ?>
	</ul>
<?php } ?>