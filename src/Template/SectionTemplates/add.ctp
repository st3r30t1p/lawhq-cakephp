<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SectionTemplate $sectionTemplate
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Section Templates'), ['action' => 'index']) ?></li>
    </ul>
</nav>

<div class="sectionTemplates form large-9 medium-8 content">
    <h3><?= __('Add New Section Template') ?></h3>
    <?= $this->Form->create($sectionTemplate) ?>
    <div class="box">
        <div class="field">
            <?= $this->Form->text('name', ['class' => 'input', 'placeholder' => 'Name', 'required' => 'required']); ?>
        </div>
        <div class="field">
            <?= $this->Form->text('google_doc_id', ['class' => 'input', 'placeholder' => 'Google Doc ID', 'required' => 'required']); ?>
        </div>
        <div class="field">
            <?php echo $this->Form->button('Add', ['class' => 'button is-primary mt-10']); ?>
        </div>
    </div>
    <?= $this->Form->end() ?>
</div>
