<?php
$this->assign('title', 'Matters - Documents');
$this->set('wunder_title', 'Matters - Documents');
?>

<?= $this->element('matters_view_menu') ?>

<div class="table-container">
    <table class="table is-striped is-hoverable">
        <thead>
        <tr>
            <th>Id</th>
            <th>Matter_id</th>
            <th>Link</th>
            <th></th>
        </tr>
        </thead>
        <tbody style="font-size:smaller;">
        <?php foreach ($documents as $document) {?>
            <tr>
                <td><?= h($document->id); ?></td>
                <td><?= $document->matter_id ?></td>
                <td><?= $document->link ?></td>
                <td>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?= $this->element('pagination'); ?>
