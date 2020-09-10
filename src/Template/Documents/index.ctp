<?php
$this->assign('title', 'Documents');
$this->set('wunder_title', 'Documents');
?>

<ul class="flex even-spacing">
    <li>
        <h1 class="title is-4">Documents</h1>
    </li>
    <li>
        <a class="button is-green is-small" href="<?= $this->Url->build("/documents/add"); ?>">
		   <span class="icon is-small">
		     <i class="fas fa-plus"></i>
		   </span>
            <span>Create</span>
        </a>
    </li>
</ul>

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
