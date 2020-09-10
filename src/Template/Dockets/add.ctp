<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Docket $docket
 */
?>
<div style="margin-bottom: 50px"></div>
<div class="dockets form large-9 medium-8 columns content">
        <div class="box">
            <h1 class="title"><?= __('Add Docket') ?></h1>
            <div class="field field-custom field-custom-sections">
                <div class="showCourtsSystem">
                    <p class="subtitle"><i><?= __('What court system do you want to search?') ?></i></p>
                    <div class="control">
                        <label class="radio">
                            <input type="radio" id="federal" name="selectCourts">
                            <?= __('Federal Courts') ?>
                        </label>
                    </div>
                    <div class="control">
                        <label class="radio">
                            <input type="radio" id="state" name="selectCourts">
                            <?= __('State Courts') ?>
                        </label>
                    </div>
                </div>
                <div class="showForState">
                    <p><?= __('Now searching state cases.') ?> <a class="stateLink" href=""><?= __('Search federal cases instead.') ?></a></p>
                    <p class="subtitle"><i><?= __('State dockets are not yet integrated.') ?></i></p>
                </div>
                <div class="showForFederal">
                    <p><?= __('Now searching federal cases.') ?> <a class="federalLink" href=""><?= __('Search state cases instead.') ?></a></p>
                    <p class="subtitle"><i><?= __('How do you want to search?') ?></i></p>
                    <div class="control">
                        <label class="radio">
                            <input type="radio" id="case" name="selectCourts">
                            <?= __('by case number') ?>
                        </label>
                    </div>
                    <div class="control">
                        <label class="radio">
                            <input type="radio" id="party" name="selectCourts">
                            <?= __('by party') ?>
                        </label>
                    </div>
                    <div class="control">
                        <label class="radio">
                            <input type="radio" id="attorney" name="selectCourts">
                            <?= __('by attorney') ?>
                        </label>
                    </div>
                    <div class="pre-loader" style="width: 35%; height: 30%; background: no-repeat">
                        <span class="element-loader" style="height: 40%"></span>
                    </div>
                    <?= $this->Form->create(null, [
                        'url' => [
                            'controller' => 'Api',
                            'action' => 'fetchDocketCourt'
                        ],
                        'id' => 'newCourtForm'

                    ]); ?>
                    <div class="showByCaseNumber">
                        <div class="field">
                            <?php echo $this->Form->text('case_number', ['class' => 'input', 'placeholder' => 'Case number', 'required' => 'required']); ?>
                        </div>
                        <div class="field">
                            <div class="select-beast">
                                <select name="court_id" style="width:350px" class="select-beast">
                                    <option value="">( Select court )</option>
                                    <?php foreach ($courts as $court): ?>
                                        <?php if ($court->type == 'bankruptcy') : ?>
                                            <option value="<?= $court->id ?>"><?= $court->name . ' (' . ucfirst($court->type). ')' ?></option>
                                        <?php else: ?>
                                            <option value="<?= $court->id ?>"><?= $court->name ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <?= $this->Form->button(__('Submit'), ['class' => 'button is-green is-small mt-10', 'style' => 'float:right', 'id' => 'receiveMatter']) ?>
                    </div>
                    <?= $this->Form->end() ?>

                    <?= $this->Form->create($docket); ?>
                    <div class="showMatter">
                        <div class="parseText" style="font-size:smaller;"></div>
                        <input type="hidden" name="case_name">
                        <input type="hidden" name="case_number">
                        <input type="hidden" name="court_id">
                        <input type="hidden" name="court_fed_abbr">
                        <input type="hidden" name="fed_case_number_judges">
                        <?= $this->Form->button(__('Add to Docket'), ['class' => 'button is-green is-small mt-10', 'style' => 'float:left']) ?>
                    </div>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
</div>
<?= $this->Html->script('dockets/index'); ?>
