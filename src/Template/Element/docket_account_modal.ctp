<div id="add-account" class="modal">
  <div class="modal-background"></div>
  <div class="modal-content">
    <div class="box">
        <div class="content">
          <?= $this->Form->create(null, ['url' => ['action' => 'addAccount'], 'templates' => ['inputContainer' => '{{content}}']]);
              $this->Form->setTemplates([
                  'select' => '<div class="field"><div {{attrs}}><select name="{{name}}">{{content}}</select></div></div>',
              ]);
           ?>
          <div class="field">
              <label class="label">Account Type</label>
              <div class="field-body">
                 <?= $this->Form->select('account', ['pacer' => 'Pacer'], ['empty' => 'Account', 'class' => 'select is-fullwidth account-type', 'label' => false,]); ?>
              </div>
          </div>
          <div id="account-fields" style="display:none">
            <div class="field account-state">
                <label class="label">Location</label>
                <div class="field-body">
                  <?= $this->Form->control('state_id', ['class' => 'select is-fullwidth', 'empty' => 'State', 'label' => false, 'options' => $states]); ?>
                </div>
            </div>
            <div class="field">
                <label class="label">Username</label>
                <div class="field-body">
                   <?= $this->Form->text('un', ['class' => 'input is-normal', 'placeholder' => 'Username', 'label' => false, 'required' => 'required', 'autocomplete' => 'false']) ?>
                </div>
            </div>
            <div class="field">
                <label class="label">Password</label>
                <div class="field-body">
                   <?= $this->Form->text('pw', ['class' => 'input is-normal', 'placeholder' => 'Password', 'type' => 'password', 'label' => false, 'required' => 'required', 'autocomplete' => 'false']) ?>
                </div>
            </div>
          </div>
          <?= $this->Form->hidden('team_member_id', ['value' => $teamMember->id]) ?>
          <?= $this->Form->button('Add', ['class' => 'button is-green is-small mt-10']) ?>
          <?= $this->Form->end() ?>
        </div>
    </div>
  </div>
  <button class="modal-close is-large" aria-label="close"></button>
</div>