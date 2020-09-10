<div style="margin-bottom:15px">
  <ul class="flex">
    <li>
      <h1 class="title is-4"><?= $matter->id . ' - ' . h($matter->name) ?></h1>
    </li>
    <li>
      <?php if ($this->request->getAttribute('params')['action'] == 'contacts') { ?>
        <?= $this->Form->create(null, ['templates' => ['inputContainer' => '{{content}}']]); ?>
        <?= $this->Form->hidden('matter_id', ['value' => $matter->id]); ?>
        <?= $this->Form->hidden('contact_id'); ?>
        <?= $this->Form->hidden('imported_user_id'); ?>
        <?= $this->Form->hidden('team_member_id'); ?>

        <div class="field is-horizontal">
          <div class="field-body">
            <div class="field">
              <div class="select is-small">
                <?php echo $this->Form->select('type', [
                'plaintiff' => 'Plaintiff',
                'defendant' => 'Defendant',
                'attorney_for_plaintiff' => 'Attorney for Plaintiff',
                'paralegal_for_plaintiff' => 'Paralegal for Plaintiff',
                'attorney_for_defendant' => 'Attorney for Defendant',
                'paralegal_for_defendant' => 'Paralegal for Defendant',
                'judge' => 'Judge',
                'court' => 'Court',
                'court_clerk' => 'Court Clerk',
                'third_party' => 'Third Party'
            ], ['required' => 'required', 'default' => 'plaintiff', 'class' => 'matter-contact-type']); ?>
              </div>
            </div>
            <div class="field">
              <p class="control is-expanded">
                <?= $this->Form->text('name', ['class' => 'input is-small matter-contact-name', 'required' => 'required', 'placeholder' => 'Name', 'style' => 'width: 163px;transition: all 0.7s ease 0s; -webkit-transition: all 0.7s ease 0s;']) ?>
              </p>
            </div>
            <div class="field">
              <p class="control is-expanded">
                <?= $this->Form->text('title', ['class' => 'input is-small', 'placeholder' => 'Title']) ?>
              </p>
            </div>
            <div class="field is-grouped">
              <p class="control">
                <label class="checkbox" style="padding-top: 7px; font-size: 13px;">
                  <?= $this->Form->checkbox('is_primary'); ?>
                  Primary
                </label>
              </p>

              <?= $this->Form->submit('Add', ['class' => 'button is-small is-green add-matter-contact']) ?>
            </div>
          </div>
        </div>
        <?= $this->Form->end(); ?>
      <?php } else if ($this->request->getAttribute('params')['action'] == 'documents') { ?>
        <a class="button is-green is-small" href="<?= $this->Url->build("/documents/add?mat_id={$matter->id}"); ?>">
           <span class="icon is-small">
             <i class="fas fa-plus"></i>
           </span>
            <span>New Document</span>
        </a>
      <?php } else { ?>
        <a class="button is-green is-small" href="<?= $this->Url->build(['action' => 'edit', 'id' => $matter->id]) ?>">
          <span class="icon is-small">
            <i class="fas fa-pen"></i>
          </span>
          <span>Edit</span>
        </a>
      <?php } ?>
    </li>
  </ul>
</div>

<nav class="navbar" role="navigation" aria-label="main navigation" style="margin-bottom:20px">
    <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false">
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
    </a>
  <div id="navbarBasicExample" class="navbar-menu">
    <div class="navbar-start">
      <?= $this->Html->link('Dashboard', ['action' => 'view', 'id' => $matter->id], ['class' => 'navbar-item ' . $this->Link->isActivePage('/matters/view/' . $matter->id, true)]) ?>
      <?= $this->Html->link('Contacts', ['action' => 'contacts', 'id' => $matter->id], ['class' => 'navbar-item ' . $this->Link->isActivePage('/matters/contacts/' . $matter->id, true)]) ?>
      <?= $this->Html->link('Documents', ['action' => 'documents', 'id' => $matter->id], ['class' => 'navbar-item ' . $this->Link->isActivePage('/matters/documents/' . $matter->id, true)]) ?>
      <?= $this->Html->link('Docket', ['action' => 'docket', 'id' => $matter->id], ['class' => 'navbar-item ' . $this->Link->isActivePage('/matters/docket/' . $matter->id, true)]) ?>
    </div>
  </div>
</nav>