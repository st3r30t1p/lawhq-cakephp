<?php
	$this->assign('title', 'Add Conference Room');
	$this->set('wunder_title', 'Add Conference Room');
?>

<div class="even-spacing">
	<h1 class="title is-4">Add Conference Room</h1>
</div>

<?php echo $this->Form->create($conference); ?>
<div class="box">
	<div class="field">
		<?php echo $this->Form->text('name', ['class' => 'input', 'placeholder' => 'Name', 'required' => 'required']); ?>
	</div>
    <div class="field">
        <?= $this->Form->text('schedule_date', ['class' => 'input', 'placeholder' => 'Schedule Date', 'required' => 'required']) ?>
    </div>
	<div>
		<?php echo $this->Form->button('Add', ['class' => 'button is-green']); ?>
	</div>
</div>
<?php echo $this->Form->end(); ?>
<script>
    $(function() {
	  $('input[name="schedule_date"]').daterangepicker({
	  	drops: 'down',
	    singleDatePicker: true,
	    showDropdowns: true,
        locale: {
            format: 'MM/DD/YYYY'
        }
	  });
    //   $('select[name="schedule_timezone"]').timezones();
    //   $('select[name="schedule_timezone"] > option').each(function(){
    //     if($(this).val() == 'EST') {
    //         $(this).prop('selected', true);
    //     }else{
    //         $(this).prop('selected', false);
    //     }
    //   });
	});
</script>
