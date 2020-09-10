<?php
$this->assign('title', 'Templates');
$this->set('wunder_title', 'Templates');
?>

<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li>
            <a class="button is-green is-small" href="<?= $this->Url->build(['action' => 'add']) ?>">
                <span class="icon is-small">
		            <i class="fas fa-plus"></i>
		        </span>
                <span><?= __('New Template') ?></span>
            </a>
        </li>
    </ul>
</nav>
<div class="sectionTemplates index large-9 medium-8 content">
    <h3><?= __('Templates') ?></h3>
    <div class="row">
        <table class="table is-striped">
            <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('google_doc_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
                <th scope="col" class="actions"></th>
            </tr>
            </thead>
            <tbody style="font-size:smaller">
            <?php foreach ($templates as $template) : ?>
                <tr>
                    <td><?= $template->id; ?></td>
                    <td><?= $template->name; ?></td>
                    <td><?= $template->google_doc_id; ?></td>
                    <td><?= $template->created; ?></td>
                    <td><?= $template->modified; ?></td>
<!--                    <td>-->
<!--                        <a class="btn" href="--><?php //$this->Url->build(['action' => 'generate', 'id' => $template->id]); ?><!--">Generate document</a>-->
<!--                    </td>-->
                    <td>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $template->id]) ?>
                    </td>
                    <td>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $template->id], ['confirm' => __('Are you sure you want to delete # {0}?', $template->id)]) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->element('pagination'); ?>
