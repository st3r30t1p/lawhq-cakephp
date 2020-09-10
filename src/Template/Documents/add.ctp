<?php
$this->assign('title', 'Create New Document');
$this->set('wunder_title', 'Create New Document');
?>

<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Document'), '/matters/documents/'.$matter->id) ?></li>
    </ul>
</nav>

<div class="box">
    <?php echo $this->Form->create($document, [
        'templates' => ['inputContainer' => '<div class="field"><div class="control">{{content}}</div></div>'],
        'name' => 'documents'
    ]); ?>
    <div class="row">
        <div class="field">
            <label class="label">Matter ID:</label>
            <div class="control">
                <?php echo $this->Form->text('matter id', [
                    'class' => 'input',
                    'required' => 'required',
                    'value' => $matter->id,
                    'readonly' => $matter->id === null || $matter->id === '' ? false : 'readonly',
                ]); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="input-field col s6 mt-10">
            <div class="field mt-10">
                <label class="label">Template:</label>
                <div class="control">
                    <div class="select">
                        <?php echo $this->Form->select('template', $formTemplates, [
                            'required' => 'required', 'onchange' => "reload()", 'id' => 'selectTemplate',
                            'value' => $template['google_doc_id'],
                            'empty' => '(choose template)'
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="select is-multiple mt-10">
            <label class="label">Predefined keys:</label>
            <?php if (!empty($predFields)) : ?>
            <select multiple size="5">
                <?php foreach ($predFields as $key => $type) : ?>
                    <option><?= $key; ?></option>
                <?php endforeach; ?>
            </select>
            <?php else : ?>
                <span>Predefined keys not set.</span>
            <?php endif; ?>
        </div>
    </div>
    <div class="row">
        <div class="even-spacing mt-10">
            <?php echo $this->Form->button('Generate document', ['class' => 'button is-green']); ?>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</div>

<?php echo $this->Html->script('documents/add'); ?>
