<?php
	$this->assign('title', 'Add Matter');
	$this->set('wunder_title', 'Add Matter');
?>

<div class="even-spacing">
	<h1 class="title is-4">Add Matter</h1>
</div>

<?php echo $this->Form->create($matter); ?>
<div class="box">
	<div class="field">
		<?php echo $this->Form->text('name', ['class' => 'input', 'placeholder' => 'Name', 'required' => 'required']); ?>
	</div>
    <div class="row">
        <div class="field mt-10">
            <label class="label">Status:</label>
            <div class="control">
                <div class="select">
                    <?php echo $this->Form->select('status', ['Open' => 'Open', 'Complaint' => 'Complaint', 'Filed' => 'Filed', 'Closed' => 'Closed'], ['class' => 'select']);
                    ?>
                </div>
            </div>
        </div>
    </div>
	<div class="mt-10">

	<div class="field">
        <div class="select">
		<?php echo $this->Form->select('practice_area', ['tcpa' => 'TCPA']); ?>
        </div>
	</div>

	<div>
		<?php echo $this->Form->button('Add', ['class' => 'button is-green']); ?>
	</div>
</div>
<?php echo $this->Form->end(); ?>
