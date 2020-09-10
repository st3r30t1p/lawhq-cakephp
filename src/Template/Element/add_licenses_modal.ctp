<div id="add-license" class="modal">
  <div class="modal-background"></div>
  <div class="modal-content">
    <div class="box">
        <div class="content">
          <?= $this->Form->create(null, ['url' => ['action' => 'addLicense'], 'templates' => ['inputContainer' => '{{content}}']]);
              $this->Form->setTemplates([
                  'select' => '<div class="field"><div {{attrs}}><select required name="{{name}}">{{content}}</select></div></div>',
              ]);
           ?>
           <div class="field">
               <label class="label">License Type</label>
               <div class="field-body">
                  <?= $this->Form->select('type', ['bar' => 'Bar'], ['class' => 'select is-fullwidth', 'label' => false,]); ?>
               </div>
           </div>

          <div class="field">
              <label class="label">State</label>
              <div class="field-body">
                <?= $this->Form->control('state_id', ['class' => 'select is-fullwidth', 'empty' => 'State', 'label' => false, 'required' => 'required', 'options' => $states]); ?>
              </div>
          </div>

          <div class="field">
              <label class="label">License Number</label>
              <div class="field-body">
                 <?= $this->Form->text('number', ['class' => 'input is-normal', 'placeholder' => 'License Number', 'label' => false, 'required' => 'required']) ?>
              </div>
          </div>
          <div class="field">
              <label class="label">Status</label>
              <div class="field-body">
                 <?= $this->Form->select('status', ['active' => 'Active', 'inactive' => 'Inactive'], ['class' => 'select is-fullwidth', 'label' => false,]); ?>
              </div>
          </div>
          <?= $this->Form->hidden('team_member_id', ['value' => $teamMember->id]) ?>
          <?= $this->Form->button('Add', ['class' => 'button is-green is-small']) ?>
          <?= $this->Form->end() ?>
        </div>
    </div>
  </div>
  <button class="modal-close is-large" aria-label="close"></button>
</div>
