<div class="info-block" data-contactEmails="<?= $key ?>">
  <div class="field is-horizontal">
    <div class="field-label is-small">
      <label class="label">Email</label>
    </div>
    <div class="field-body">
    	<?= $this->Form->hidden("contact_emails.{$key}.id" , ['class' => 'table-id', 'data-table' => 'contactEmails']); ?>
    	<?= $this->Form->control("contact_emails.{$key}.email", ['type' => 'text', 'label' => false, 'class' => 'input is-small', 'placeholder' => 'Email']); ?>
      
      <div class="field type-select">
        <div class="select is-small">
          <?php echo $this->Form->select("contact_emails.{$key}.type", [
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
          <?= $this->Form->checkbox("contact_emails.{$key}.is_primary", ['label' => 'Primary', 'class' => 'primary-selection', 'data-name' => 'email', ($isContactNew && $key == 0) ? 'checked' : '']); ?>
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