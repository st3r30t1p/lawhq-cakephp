<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SectionTemplate $sectionTemplate
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Section Template'), ['action' => 'edit', $sectionTemplate->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Section Template'), ['action' => 'delete', $sectionTemplate->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sectionTemplate->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Section Templates'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Section Template'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="sectionTemplates view large-9 medium-8 columns content">
    <h3><?= h($sectionTemplate->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($sectionTemplate->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Google Doc Id') ?></th>
            <td><?= h($sectionTemplate->google_doc_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($sectionTemplate->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($sectionTemplate->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($sectionTemplate->modified) ?></td>
        </tr>
    </table>
</div>
