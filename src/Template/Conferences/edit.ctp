<?php $this->assign('title', 'Edit ' . $conference->name); ?>

<div class="sectionTemplates index large-9 medium-8 content">
    <h3><?= __('Edit Conference') ?></h3>
    <?php echo $this->Form->create($conference); ?>
    <div class="box">
        <div class="field">
            <?php echo $this->Form->text('name', ['class' => 'input', 'placeholder' => 'Name', 'required' => 'required']); ?>
        </div>
        <div class="field">
            <?= $this->Form->text('schedule_date', ['class' => 'input', 'placeholder' => 'Schedule Date', 'required' => 'required']) ?>
        </div>
        <?php echo $this->Form->hidden('meeting_number'); ?>
        <div>
            <?php echo $this->Form->button('Save', ['class' => 'button is-green']); ?>
            <a 
                href="javascript:;"
                onclick="if (confirm('Are you sure you want to delete this Conference Room?')) { document.delete_room_frm.submit(); } event.returnValue = false; return false;"
                style="float: right;padding: calc(.5em - 1px);color:red;"
            >Delete</a>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
    <form name="delete_room_frm" style="display:none;" method="post" action="/conferences/delete/<?= $conference->id?>"><input type="hidden" name="_method" value="POST"></form>

</div>
<script>
    function generate_accesscode(){
        return Math.floor(100000 + Math.random() * 900000);
    }
    $(function() {
	  $('input[name="schedule_date"]').daterangepicker({
	  	drops: 'down',
	    singleDatePicker: true,
	    showDropdowns: true,
        startDate: "<?= $conference->schedule_date ?>",
        locale: {
            format: 'MM/DD/YYYY'
        }
	  });
    //   $('select[name="schedule_timezone"]').timezones();
    //   $('select[name="schedule_timezone"] > option').each(function(){
    //     if($(this).val() == "<?= $conference->schedule_timezone ?>") {
    //         $(this).prop('selected', true);
    //     }else{
    //         $(this).prop('selected', false);
    //     }
    //   });
	});
</script>