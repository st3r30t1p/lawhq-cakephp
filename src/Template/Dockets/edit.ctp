<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Docket $docket
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Dockets'), ['action' => 'index']) ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $docket->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $docket->id)]
            )
        ?></li>
    </ul>
</nav>
<div class="dockets form large-9 medium-8 columns content">
    <?= $this->Form->create($docket) ?>
    <legend><?= __('Edit Docket') ?></legend>
    <div class="box">
        <div class="field field-custom field-custom-sections">
            <div class="control">
                <div class="field">
                    <label class="label">Case Name:</label>
                    <?php echo $this->Form->text('case_name', ['class' => 'input', 'required' => 'required', 'value' => $docket->case_name]); ?>
                </div>
                <div class="field">
                    <label class="label">Case number:</label>
                    <?php echo $this->Form->text('case_number', ['class' => 'input', 'required' => 'required', 'value' => $docket->case_number]); ?>
                </div>
                <div class="field">
                    <label class="label">Case Number Judge:</label>
                    <?php echo $this->Form->text('fed_case_number_judges', ['class' => 'input', 'required' => 'required', 'value' => $docket->fed_case_number_judges]); ?>
                </div>
                <div class="field">
                    <label class="label">Court Fed Abbr:</label>
                    <?php echo $this->Form->text('court_fed_abbr', ['class' => 'input', 'required' => 'required', 'value' => $docket->court_fed_abbr]); ?>
                </div>
                <div class="field">
                    <label class="label">Court:</label>
                    <div class="select">
                        <?php echo $this->Form->select('court_id', $courts, ['empty' => 'choose', 'class' => 'select select-sections', 'default' => $docket->court_id]); ?>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Matter:</label>
                    <div class="select">
                        <?php echo $this->Form->select('matter_id', $matters, ['empty' => 'choose', 'class' => 'select select-sections', 'default' => $docket->matter_id]); ?>
                    </div>
                </div>
            </div>
        </div>
        <?= $this->Form->button(__('Submit'), ['class' => 'button is-green is-small mt-10', 'style' => 'float:right']) ?>
        <div style="clear: both;"></div>
        <?= $this->Form->end() ?>
    </div>
</div>
