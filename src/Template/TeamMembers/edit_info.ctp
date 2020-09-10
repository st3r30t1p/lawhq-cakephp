<div style="max-width:800px">
  <?= $this->Form->create($appUser, ['templates' => [
      'inputContainer' => '{{content}}',
      'select' => '<div class="field"><div {{attrs}}><select required name="{{name}}">{{content}}</select></div></div>'
  ]]); ?>

  <div class="contact-info-header mt-10">Edit Personal Info</div>
  <div class="field is-horizontal">
    <div class="field-label">
      <label class="label">First Name</label>
    </div>
    <div class="field-body">
      <div class="field">
        <?= $this->Form->text('first_name', ['class' => 'input is-small', 'required' => 'required']); ?>
      </div>
    </div>
  </div>
  <div class="field is-horizontal">
    <div class="field-label">
      <label class="label">Last Name</label>
    </div>
    <div class="field-body">
      <div class="field">
        <?= $this->Form->text('last_name', ['class' => 'input is-small', 'required' => 'required']); ?>
      </div>
    </div>
  </div>
  <div class="field is-horizontal">
    <div class="field-label">
      <label class="label">Personal Email</label>
    </div>
    <div class="field-body">
      <div class="field">
        <?= $this->Form->text('personal_email', ['class' => 'input is-small', 'required' => 'required']); ?>
      </div>
    </div>
  </div>
  <div class="field is-horizontal">
    <div class="field-label">
      <label class="label">Phone Number</label>
    </div>
    <div class="field-body">
      <div class="field">
        <?= $this->Form->text('phone_number', ['class' => 'input is-small', 'id' => 'phone', 'maxlength' => 12, 'required' => 'required']); ?>
      </div>
    </div>
  </div>
  <div class="field is-horizontal">
    <div class="field-label">
      <label class="label">Address</label>
    </div>
    <div class="field-body">
      <div class="field">
        <?= $this->Form->text('address_1', ['class' => 'input is-small', 'required' => 'required']); ?>
      </div>
    </div>
  </div>
  <div class="field is-horizontal">
    <div class="field-label">
      <label class="label">Address 2</label>
    </div>
    <div class="field-body">
      <div class="field">
        <?= $this->Form->text('address_2', ['class' => 'input is-small']); ?>
      </div>
    </div>
  </div>
  <div class="field is-horizontal">
    <div class="field-label">
      <label class="label">City</label>
    </div>
    <div class="field-body">
      <div class="field">
        <?= $this->Form->text('city', ['class' => 'input is-small', 'required' => 'required']); ?>
      </div>
    </div>
  </div>
  <div class="field is-horizontal">
      <div class="field-label">
        <label class="label">State</label>
      </div>
      <div class="field-body">
        <?= $this->Form->control('state_id', ['class' => 'select is-fullwidth is-small', 'empty' => 'State', 'label' => false, 'options' => $states]); ?>
      </div>
  </div>
  <div class="field is-horizontal">
      <div class="field-label">
        <label class="label">Zip</label>
      </div>
      <div class="field-body">
        <div class="field">
          <?= $this->Form->text('zip', ['class' => 'input is-small', 'required' => 'required']); ?>
        </div>
      </div>
  </div>
  <?= $this->Form->submit('Save', ['class' => 'button is-green']) ?>
  <?= $this->Form->end() ?>
</div>