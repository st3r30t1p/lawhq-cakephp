<?= $this->Form->create(); ?>

<div class="row">
    <div class="columns large-9">
		<?= $this->Form->input('domain'); ?>
	</div>
    <div class="columns large-3">
		<?= $this->Form->button('Submit'); ?>
    </div>
</div>
<?= $this->Form->end(); ?>