<div class="info-block" data-contactDbas="<?= $key ?>">
  <div class="field is-horizontal">
    <div class="field-label is-small">
      <label class="label">DBA</label>
    </div>
    <div class="field-body">
    	<?= $this->Form->hidden("contact_dbas.{$key}.id" , ['class' => 'table-id', 'data-table' => 'contactDbas']); ?>
    	<?= $this->Form->control("contact_dbas.{$key}.name", ['type' => 'text', 'label' => false, 'class' => 'input is-small', 'placeholder' => 'DBA']); ?>

      <div class="field info-remove is-clickable">
        Remove
      </div>

    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-small">
      <!-- Left empty for spacing -->
    </div>
  </div>
</div>