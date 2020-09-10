<?php $this->assign('title', ' My Account'); ?>
<div class="columns">
  <div class="column">
    <div class="box">
      <div class="contact-info-header">Your LawHQ Information</div>
        <?= $this->Form->create($appUser, ['templates' => [
            'inputContainer' => '{{content}}',
            'select' => '<div class="field"><div {{attrs}}><select name="{{name}}">{{content}}</select></div></div>'
          ]]); ?>
        <?php if (!$teamMember->phone_number) { ?>
          
        <?php } ?>

        <div class="field is-horizontal">
          <div class="field-label">
            <label class="label">Phone Number</label>
          </div>
          <div class="field-body">
            <p>385-285-1090 Ext: <?= $teamMember->extension ?></p>
          </div>
        </div>
        <div class="field is-horizontal">
          <div class="field-label">
            <label class="label">Username</label>
          </div>
          <div class="field-body">
            <p><?= $teamMember->username ?> &nbsp; <?= $this->Html->link(__('Update Password'), ['action' => 'updatePassword'], ['style' => 'font-size:11px']) ?></p>
          </div>
        </div>
        <div class="field is-horizontal">
          <div class="field-label">
            <label class="label">Joined</label>
          </div>
          <div class="field-body">
            <p><?= date('F j, Y', strtotime($teamMember->created)) ?></p>
          </div>
        </div>
    </div>

    <div class="box">
        <div class="flex">
          <div class="contact-info-header mt-10">Personal Information</div>
          <?= $this->Html->link(__('<i class="fas fa-edit is-clickable"></i>'), ['action' => 'editInfo'], ['escape' => false, 'style' => 'float:right']) ?>
        </div>
        <div class="field is-horizontal">
          <div class="field-label">
            <label class="label">First Name</label>
          </div>
          <div class="field-body">
            <div class="field">
              <?= $teamMember->first_name ?>
            </div>
          </div>
        </div>
        <div class="field is-horizontal">
          <div class="field-label">
            <label class="label">Last Name</label>
          </div>
          <div class="field-body">
            <div class="field">
              <?= $teamMember->last_name ?>
            </div>
          </div>
        </div>
        <div class="field is-horizontal">
          <div class="field-label">
            <label class="label">Personal Email</label>
          </div>
          <div class="field-body">
            <div class="field">
              <?= ($teamMember->personal_email) ? $teamMember->personal_email : '-' ?>
            </div>
          </div>
        </div>
        <div class="field is-horizontal">
          <div class="field-label">
            <label class="label">Phone Number</label>
          </div>
          <div class="field-body">
            <div class="field">
              <?= ($teamMember->phone_number) ? $teamMember->formattedNumber() : "<div class='error field'>Your extension {$teamMember->extension} will not call you until you enter a phone number.</div>" ?>
            </div>
          </div>
        </div>
        <div class="field is-horizontal">
          <div class="field-label">
            <label class="label">Address</label>
          </div>
          <div class="field-body">
            <div class="field">
              <?= ($teamMember->address_1) ? $teamMember->address_1 : '-' ?>
            </div>
          </div>
        </div>
        <div class="field is-horizontal">
          <div class="field-label">
            <label class="label">Address 2</label>
          </div>
          <div class="field-body">
            <div class="field">
              <?= ($teamMember->address_2) ? $teamMember->address_2 : '-' ?>
            </div>
          </div>
        </div>
        <div class="field is-horizontal">
          <div class="field-label">
            <label class="label">City</label>
          </div>
          <div class="field-body">
            <div class="field">
              <?= ($teamMember->city) ? $teamMember->city : '-' ?>
            </div>
          </div>
        </div>
        <div class="field is-horizontal">
            <div class="field-label">
              <label class="label">State</label>
            </div>
            <div class="field-body">
              <?= (isset($teamMember->state)) ? $teamMember->state->state : '-' ?>
            </div>
        </div>
        <div class="field is-horizontal">
            <div class="field-label">
              <label class="label">Zip</label>
            </div>
            <div class="field-body">
              <div class="field">
                <?= ($teamMember->zip) ? $teamMember->zip : '-' ?>
              </div>
            </div>
        </div>
        <?= $this->Form->end() ?>
    </div>

  </div>

  <div class="column">
    <?= $this->element('licenses_accounts') ?>
  </div>
</div>

<?= $this->element('add_licenses_modal') ?>
<?= $this->element('docket_account_modal') ?>