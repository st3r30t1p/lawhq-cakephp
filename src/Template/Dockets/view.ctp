<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Docket $docket
 */
?>
<div class="dockets view large-9 medium-8 columns">
    <table>
        <tr>
            <td><span class="subtitle"><?= $docket->case_name ?></span></td>
        </tr>
        <tr>
            <td><?= h($docket->case_number) . h($docket->fed_case_number_judges) ?> </td>
        </tr>
        <tr>
            <td><?= $docket->has('court') ? '<span style="text-transform: capitalize;">'.$docket->court->system.'</span>' . ' - ' . $docket->court->name : '' ?></td>
        </tr>
        <tr>
            <td>
                <?= $docket->has('matter') ? $this->Html->link(__('Matter') . ' ' . $docket->matter->id, ['controller' => 'Matters', 'action' => 'view', $docket->matter->id], ['target' => '_blank']) . ' <i class="fas fa-external-link-alt"></i>' : '' ?>
            </td>
        </tr>
    </table>
</div>

<div class="docket-content">
    <div class="pre-loader">
        <span class="element-loader"></span>
    </div>

    <?= $this->Form->create(null, [
        'url' => [
            'controller' => 'Api',
            'action' => 'fetchNewDoc'
        ],
        'id' => 'newDocForm'

    ]); ?>
    <div class="field is-grouped mt-30">
        <?php if ($court->type != 'appellate'): ?>
            <div class="control">
                <?= $this->Form->text('documents_numbered_from_', ['class' => 'input', 'placeholder' => 'Docket # From']); ?>
            </div>
            <div class="control">
                <?= $this->Form->text('documents_numbered_to_', ['class' => 'input', 'placeholder' => 'Docket # To']); ?>
            </div>
        <?php else: ?>
            <div class="control" style="width: 250px">
                <input type="text" name="documents_date_from_" class="input" onfocus="(this.type='date')" onblur="(this.type='text')" placeholder="Docket Date From">
            </div>
            <div class="control" style="width: 250px">
                <input type="text" name="documents_date_to_" class="input" onfocus="(this.type='date')" onblur="(this.type='text')" placeholder="Docket Date To">
            </div>
        <?php endif; ?>
        <div class="control">
            <?= $this->Form->button('Get', ['class' => 'button is-green', 'id' => 'getNewDocBtn']); ?>
        </div>
    </div>

    <?= $this->Form->end() ?>
    <div class="table-docket-block mt-30">
        <table class="matter-courts-table">
            <thead>
            <tr>
                <td>#</td>
                <td>Date</td>
                <td>Description</td>
            </tr>
            </thead>
            <tbody style="font-size:smaller;">

            </tbody>
        </table>
    </div>

</div>
<?= $this->Html->script('dockets/index'); ?>
<script>

    window.getCurrentData = function() {

        return {
            courtName: '<?= $court->name; ?>',
            courtType: '<?= $court->type; ?>',
            fedAbbr: '<?= $docket->court_fed_abbr; ?>',
            caseNumber: '<?= $docket->case_number; ?>',
            caseNumberJudge: '<?= $docket->fed_case_number_judges; ?>',
            docketID: '<?= $docket->id; ?>',
            courtID: '<?= $docket->court_id; ?>'
        }
    }

</script>
