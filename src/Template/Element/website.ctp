<div class="info-block" data-contactWebsites="<?= $key ?>">
  <div class="field is-horizontal">
    <div class="field-label is-small">
      <label class="label">Website</label>
    </div>
    <div class="field-body">
    	<?= $this->Form->hidden("contact_websites.{$key}.id" , ['class' => 'table-id', 'data-table' => 'contactWebsites']); ?>
    	<?= $this->Form->control("contact_websites.{$key}.website", ['type' => 'website', 'label' => false, 'class' => 'input is-small', 'placeholder' => 'Website']); ?>

      <div class="field type-select">
        <div class="select is-small">
          <?php echo $this->Form->select("contact_websites.{$key}.type", [
          'home' => 'Home',
          'work' => 'Work',
          'billing' => 'Billing',
          'subpoena' => 'Subpoena',
          'ppob' => 'PPOB',
          'other' => 'Other'
          ], ['class' => 'select is-small', 'empty' => '(Type)']); ?>
        </div>
      </div>

      <div class="field primary-checkbox">
        <label class="checkbox">
          <?= $this->Form->checkbox("contact_websites.{$key}.is_primary", ['label' => 'Primary', 'class' => 'primary-selection', 'data-name' => 'website', ($isContactNew && $key == 0) ? 'checked' : '']); ?>
          Primary
        </label>
      </div>

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