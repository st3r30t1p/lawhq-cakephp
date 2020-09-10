<?php $this->assign('title', ' Edit License'); ?>
<h2 class="title">Edit License</h2>

<div style="max-width:800px">
    <?= $this->Form->create($license, ['templates' => ['inputContainer' => '{{content}}']]);
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
    <div class="flex">
        <?= $this->Form->button('Save', ['class' => 'button is-green is-small']) ?>
        <?= $this->Html->link(__('Delete License'), ['action' => 'deleteLicense', $license->id], ['confirm' => __('Are you sure you want to delete this license?'), 'class' => 'error-text']) ?>
    </div>
    <?= $this->Form->end() ?>
</div>