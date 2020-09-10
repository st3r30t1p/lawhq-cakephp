<?php $this->assign('title', 'Edit ' . $matter->name); ?>

<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Matters'), ['action' => 'index']) ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $matter->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $matter->id)]
            )
            ?></li>
    </ul>
</nav>


<div class="sectionTemplates index large-9 medium-8 content">
    <h3><?= __('Edit Matter') ?></h3>
    <div class="box">

        <?= $this->Form->create($matter, ['style' => 'max-width:875px']);
            $this->Form->setTemplates([
                'inputContainer' => '<div class="field"><div class="control">{{content}}</div></div>',
                'select' => '<div class="field"><div {{attrs}}><select name="{{name}}">{{content}}</select></div></div>',
                'inputContainerError' => '<div class="field"><div class="control is-danger">{{content}}</div><p class="help is-danger">{{error}}</p></div>',
                'error' => '{{content}}',
                'input' => '<input type="{{type}}" autocomplete="false" name="{{name}}"{{attrs}}/>'
            ]);
         ?>

        <h1 class="title is-6">Details</h1>

        <div class="field is-horizontal">
            <div class="field-label is-small">
                <label class="label">Name</label>
            </div>
            <div class="field-body">
                <div class="field">
                    <div class="control">
                        <?= $this->Form->text('name', ['class' => 'input is-small', 'required' => 'required']); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="field is-horizontal">
            <div class="field-label is-small">
                <label class="label">Practice Area</label>
            </div>
            <div class="field-body">
                <div class="field">
                    <div class="control">
                        <div class="select is-fullwidth is-small">
                            <?= $this->Form->select('practice_area', ['tcpa' => 'TCPA']); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="field is-horizontal">
            <div class="field-label is-small">
                <label class="label">Status</label>
            </div>
            <div class="field-body">
                <div class="field">
                    <div class="control">
                        <div class="select is-fullwidth is-small">
                            <?= $this->Form->select('status', ['pending' => 'Pending', 'open' => 'Open', 'closed' => 'Closed']); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h1 class="title is-6">Courts</h1>
        <?php if (sizeof($matter->matter_courts)) {
            foreach ($matter->matter_courts as $key => $court) { ?>
                <?= $this->Form->hidden("matter_courts.{$key}.id") ?>

                <div class="field is-horizontal">
                    <div class="field-label is-small">
                        <label class="label"></label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <?= $this->Form->control("matter_courts.{$key}.court_id", ['class' => 'select required is-fullwidth is-small', 'type' => 'select', 'label' => false, 'options' => $courtList]); ?>
                        </div>
                        <div class="field">
                            <?= $this->Form->text("matter_courts.{$key}.case_number", ['class' => 'input is-small', 'placeholder' => 'Case Number', 'label' => false, 'required' => 'required']) ?>
                        </div>
                    </div>
                </div>

            <?php }
        } ?>
        <button class="button is-green is-small add-court">Add Court</button>

        <hr>
        <?= $this->Form->hidden('matter_id', ['value' => $matter->id]) ?>
        <?= $this->Form->button('Save', ['class' => 'button is-green is-small', 'style' => 'float:right']); ?>
        <div style="clear: both"></div>
        <?= $this->Form->end(); ?>

    </div>
</div>

<!-- Add new court modal -->
<div id="modal" class="modal">
  <div class="modal-background"></div>
  <div class="modal-content">
    <div class="box" style="min-height:320px">
        <div class="pre-loader" style="background: url('/img/loader.gif') no-repeat 50%; width: 50%; height: 80%; position: fixed"></div>
        <?= $this->Form->create(null, [
            'url' => [
                'controller' => 'Api',
                'action' => 'fetchDocketCourt'
            ],
            'id' => 'newCourtForm'

        ]);
        $this->Form->setTemplates([
            'select' => '<div class="field"><div {{attrs}}><select required name="{{name}}">{{content}}</select></div></div>',
        ]);
        ?>
        <div class="field">
            <label class="label">Court</label>
            <div class="field-body">
                <div class="select" style="width: 100%">
                    <select name="court_id" style="width:100%" class="select-beast required">
                        <option value="">Select Court...</option>
                        <?php foreach ($courts as $court): ?>
                            <option value="<?= $court->id ?>" fed_abbr="<?= $court->fed_abbr ?>" court_type="<?= $court->type ?>"><?= $court->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?= $this->Form->hidden('court_fed_abbr', ['value' => '']); ?>
                <?= $this->Form->hidden('court_type', ['value' => '']); ?>
            </div>
        </div>

        <div class="field">
            <label class="label">Case Number</label>
            <div class="field-body">
               <?= $this->Form->text('case_number', ['class' => 'input is-normal', 'placeholder' => 'Case Number', 'label' => false, 'required' => 'required']) ?>
            </div>
        </div>
        <?= $this->Form->button('Verify data', ['class' => 'button is-green is-small', 'style' => 'float:right', 'id' => 'receiveMatter']); ?>
        <?= $this->Form->end(); ?>

        <?= $this->Form->create(null, ['templates' => ['inputContainer' => '{{content}}'], 'url' => ['controller' => 'MatterCourts', 'action' => 'add']]); ?>
        <?= $this->Form->hidden('matter_id', ['value' => $matter->id]) ?>
        <input type="hidden" name="case_number">
        <input type="hidden" name="court_id">
        <?= $this->Form->button('Add Court', ['class' => 'button is-green is-small', 'style' => 'float:right', 'id' => 'addCourt']); ?>
        <?= $this->Form->end(); ?>
    </div>
  </div>
  <button class="modal-close is-large" aria-label="close"></button>
</div>
<?= $this->Html->script('matters/index'); ?>

<style type="text/css">
.selectize-input {
    padding: 10px 8px;
    border-color: #dbdbdb;
}
</style>
