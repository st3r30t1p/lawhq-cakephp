<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Docket[]|\Cake\Collection\CollectionInterface $dockets
 */
?>

<ul class="flex even-spacing">
    <li style="margin-right: auto; flex:1">
        <div class="field has-addons">
            <div class="control" style="width: 100%; max-width: 400px;">
                <?= $this->Form->create(null, ['type' => 'GET']); ?>
                <?= $this->Form->text('q', ['class' => 'input is-small', 'placeholder' => 'Search...', 'style' => 'width:100%', 'value' => (isset($query['q'])) ? $query['q'] : '']); ?>
            </div>
            <div class="control">
                <?= $this->Form->submit('Search', ['class' => 'button is-inverted is-small']); ?>
                <?= $this->Form->end(); ?>
            </div>
        </div>
    </li>

    <div class="break" style="display:none"></div>

    <li>
        <a class="button is-green is-small" href="<?= $this->Url->build(['action' => 'add']) ?>">
		   <span class="icon is-small">
		     <i class="fas fa-plus"></i>
		   </span>
            <span>New Docket</span>
        </a>
    </li>
</ul>

<div class="card table-container">
    <table class="table is-hoverable">
        <thead>
        <tr>
            <th scope="col"><?= $this->Paginator->sort('case_name') ?></th>
            <th scope="col"><?= $this->Paginator->sort('case_number') ?></th>
            <th scope="col"><?= $this->Paginator->sort('court_id') ?></th>
            <th scope="col"><?= $this->Paginator->sort('system') ?></th>
            <th scope="col"><?= $this->Paginator->sort('matter_id') ?></th>
        </tr>
        </thead>
        <tbody style="font-size:smaller;">

        <?php foreach ($dockets as $docket): ?>
            <tr data-id=<?= $docket->id ?>>
                <td><?= $this->Html->link($docket->case_name, ['controller' => 'Dockets', 'action' => 'view', $docket->id]) ?></td>
                <td><?= h($docket->getCaseNumber()) ?></td>
                <td><?= $docket->has('court') ? $docket->court->name : '-' ?></td>
                <td><?= $docket->has('court') ? $docket->court->system : '-' ?></td>
                <td><?= $docket->has('matter') ? $this->Html->link($docket->matter->id, ['controller' => 'Matters', 'action' => 'view', $docket->matter->id]) : '-' ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->element('pagination'); ?>
