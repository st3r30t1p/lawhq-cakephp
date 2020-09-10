<?php
	$this->assign('title', 'Edit User');
	$this->set('wunder_title', 'Edit User');
?>

<div class="columns">
    <div class="column">
        <div class="box">
            <div class="contact-info-header">Edit <?= $teamMember->fullName ?></div>
            <?php echo $this->Form->create($teamMember, ['templates' => [
                'inputContainer' => '{{content}}',
                'select' => '<div class="field"><div {{attrs}}><select name="{{name}}" "autocomplete" => "false">{{content}}</select></div></div>'
            ]]); ?>
            <div class="field is-horizontal">
              <div class="field-label">
                <label class="label">Status</label>
              </div>
              <div class="field-body">
                <div class="field">
                    <?= $this->Form->select('active', ['1' => 'Active', '0' => 'Inactive'], ['class' => 'select is-fullwidth', 'label' => false,]); ?>
                </div>
              </div>
            </div>
            <div class="field is-horizontal">
              <div class="field-label">
                <label class="label">Username</label>
              </div>
              <div class="field-body">
                <div class="field">
                    <?php echo $this->Form->text('un', ['class' => 'input', 'required' => 'required', 'value' => $teamMember->username, 'autocomplete' => 'false']); ?>
                </div>
              </div>
            </div>
            <div class="field is-horizontal">
              <div class="field-label">
                <label class="label">New Password</label>
              </div>
              <div class="field-body">
                <div class="field">
                    <?php echo $this->Form->password('new_password', ['class' => 'input', 'autocomplete' => 'false']); ?>
                </div>
              </div>
            </div>
            <div class="field is-horizontal">
              <div class="field-label">
                <label class="label">First Name</label>
              </div>
              <div class="field-body">
                <div class="field">
                    <?php echo $this->Form->text('fn', ['class' => 'input', 'required' => 'required', 'value' => $teamMember->first_name, 'autocomplete' => 'false']); ?>
                </div>
              </div>
            </div>
            <div class="field is-horizontal">
              <div class="field-label">
                <label class="label">Last Name</label>
              </div>
              <div class="field-body">
                <div class="field">
                    <?php echo $this->Form->text('ln', ['class' => 'input', 'required' => 'required', 'value' => $teamMember->last_name, 'autocomplete' => 'false']); ?>
                </div>
              </div>
            </div>
            <div class="field is-horizontal">
              <div class="field-label">
                <label class="label">Personal Email</label>
              </div>
              <div class="field-body">
                <div class="field">
                    <?php echo $this->Form->text('pe', ['class' => 'input', 'value' => $teamMember->personal_email, 'autocomplete' => 'false']); ?>
                </div>
              </div>
            </div>
            <div class="field is-horizontal">
              <div class="field-label">
                <label class="label">Phone Number</label>
              </div>
              <div class="field-body">
                <div class="field">
                    <?php echo $this->Form->text('pn', ['class' => 'input', 'id' => 'phone', 'maxlength' => 12, 'value' => $teamMember->phone_number, 'autocomplete' => 'false']); ?>
                </div>
              </div>
            </div>
            <div class="field is-horizontal">
              <div class="field-label">
                <label class="label">Address</label>
              </div>
              <div class="field-body">
                <div class="field">
                    <?php echo $this->Form->text('address_1', ['class' => 'input', 'id' => 'phone', 'value' => $teamMember->address_1, 'autocomplete' => 'false']); ?>
                </div>
              </div>
            </div>
            <div class="field is-horizontal">
              <div class="field-label">
                <label class="label">Address 2</label>
              </div>
              <div class="field-body">
                <div class="field">
                    <?php echo $this->Form->text('address_2', ['class' => 'input', 'id' => 'phone', 'value' => $teamMember->address_2, 'autocomplete' => 'false']); ?>
                </div>
              </div>
            </div>
            <div class="field is-horizontal">
              <div class="field-label">
                <label class="label">City</label>
              </div>
              <div class="field-body">
                <div class="field">
                    <?php echo $this->Form->text('city', ['class' => 'input', 'id' => 'phone', 'value' => $teamMember->city, 'autocomplete' => 'false']); ?>
                </div>
              </div>
            </div>
            <div class="field is-horizontal">
              <div class="field-label">
                <label class="label">State</label>
              </div>
              <div class="field-body">
                <div class="field">
                    <?php echo $this->Form->control('state_id', ['class' => 'select is-fullwidth', 'empty' => 'State', 'label' => false, 'options' => $states]); ?>
                </div>
              </div>
            </div>
            <div class="field is-horizontal">
              <div class="field-label">
                <label class="label">Zip</label>
              </div>
              <div class="field-body">
                <div class="field">
                    <?php echo $this->Form->text('zip', ['class' => 'input', 'id' => 'phone', 'value' => $teamMember->zip, 'autocomplete' => 'false']); ?>
                </div>
              </div>
            </div>

            <div class="row ">
                <div class="input-field col s6 is-pulled-right">
                    <?php echo $this->Form->button('Save', ['class' => 'button is-green mt-10']); ?>
                </div>
            </div>
            <div style="clear:both"></div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>

    <div class="column">
      <?= $this->element('licenses_accounts') ?>
    </div>
</div>

<?= $this->element('add_licenses_modal') ?>
<?= $this->element('docket_account_modal') ?>
