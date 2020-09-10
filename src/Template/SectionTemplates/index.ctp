<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SectionTemplate[]|\Cake\Collection\CollectionInterface $sectionTemplates
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li>
            <a class="button is-green is-small" href="<?= $this->Url->build(['action' => 'add']) ?>">
                <span class="icon is-small">
		            <i class="fas fa-plus"></i>
		        </span>
                <span><?= __('New Section Template') ?></span>
            </a>
        </li>
    </ul>
</nav>

<div class="sectionTemplates index large-9 medium-8 content">
    <h3><?= __('Section Templates') ?></h3>
    <div class="row">
    <table cellpadding="0" cellspacing="0" class="table is-striped">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('google_doc_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody style="font-size:smaller;">
            <?php foreach ($sectionTemplates as $sectionTemplate): ?>
            <tr>
                <td><?= $this->Number->format($sectionTemplate->id) ?></td>
                <td><?= h($sectionTemplate->name) ?></td>
                <td><?= h($sectionTemplate->google_doc_id) ?></td>
                <td><?= h($sectionTemplate->created) ?></td>
                <td><?= h($sectionTemplate->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $sectionTemplate->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $sectionTemplate->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sectionTemplate->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>
<?= $this->element('pagination'); ?>
