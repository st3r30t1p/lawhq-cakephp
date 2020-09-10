<div class="box">
  <div class="contact-info-header flex">
    Licenses
    <button class="button is-small is-green open-modal" data-modal-name="add-license">
      <span class="icon is-small">
        <i class="fas fa-plus"></i>
      </span>
    </button>
  </div>

  <div class="table-container">
    <table class="table">
      <thead>
        <tr>
          <th>Type</th>
          <th>State</th>
          <th>Number</th>
          <th>Status</th>
          <th style="width:100px"></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($teamMember->team_member_licenses as $license) { ?>
          <tr>  
            <td><?= ucfirst($license->type) ?></td>
            <td><?= $license->state->state ?></td>
            <td><?= $license->number ?></td>
            <td><?= ucfirst($license->status)  ?></td>
            <td>
              <?= $this->Html->link(__('<i class="fas fa-edit is-clickable"></i>'), ['action' => 'editLicense', $license->id], ['escape' => false]) ?>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>

<div class="box">
  <div class="contact-info-header flex">
    Docket / E-Filing Accounts
    <button class="button is-small is-green open-modal" data-modal-name="add-account">
      <span class="icon is-small">
        <i class="fas fa-plus"></i>
      </span>
    </button>
  </div>

  <div class="table-container">
    <table class="table">
      <thead>
        <tr>
          <th>Type</th>
          <th>Location</th>
          <th>Username</th>
          <th style="width:100px"></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($teamMember->team_member_accounts as $account) { ?>
          <tr>  
            <td><?= ucfirst($account->getFormattedAccount()) ?></td>
            <td><?= $account->getState() ?></td>
            <td><?= $account->username ?></td>
            <td>
              <?= $this->Html->link(__('<i class="fas fa-edit is-clickable"></i>'), ['action' => 'editAccount', $account->id], ['escape' => false]) ?>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>