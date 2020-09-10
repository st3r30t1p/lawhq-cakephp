<?php
$this->assign('title', 'Add Template');
$this->set('wunder_title', 'Add Template');
?>

<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Template'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="sectionTemplates index large-9 medium-8 content">
    <h3><?= __('Add new Template') ?></h3>
    <?php echo $this->Form->create($template); ?>
    <div class="box">
        <div class="field">
            <?php echo $this->Form->text('name', ['class' => 'input', 'placeholder' => 'Name', 'required' => 'required']); ?>
        </div>
        <div class="field">
            <?php echo $this->Form->text('google_doc_id', ['class' => 'input', 'placeholder' => 'Google Doc ID', 'required' => 'required']); ?>
        </div>
<!--        <div class="field-wrapper mb-10" data-wrap-name="parameters">-->
<!--            <span><b>Parameters:</b></span>-->
<!--            <div class="field field-custom">-->
<!--                --><?php //echo $this->Form->text('parameters[0][name]', ['class' => 'input input-custom', 'placeholder' => 'Parameter name', 'required' => 'required']); ?>
<!--                --><?php //echo $this->Form->select('parameters[0][type]', ['string' => 'String', 'array' => 'List'], ['empty' => '(choose type)', 'class' => 'select', 'required' => 'required']); ?>
<!--                <a href="javascript:void(0);" class="add-button"-->
<!--                   title="Add field">--><?php //echo $this->Html->image('add-icon.png', ['alt' => 'add-icon']); ?><!--</a>-->
<!--            </div>-->
<!--        </div>-->
        <div class="field-wrapper" data-wrap-name="sections">
            <span><b>Section Templates:</b></span>
            <div class="field field-custom field-custom-sections">
                <div class="control">
                    <div class="select">
                        <?php echo $this->Form->select('sectionTemplates[]', $sectionTemplates, ['empty' => '(choose section)', 'class' => 'select select-sections', 'required' => 'required']); ?>

                    </div>
                    <a href="javascript:void(0);" class="add-button"
                       title="Add field"><?php echo $this->Html->image('add-icon.png', ['alt' => 'add-icon']); ?></a>
                </div>
            </div>
        </div>

        <div class="field">
            <?php echo $this->Form->button('Add', ['class' => 'button is-primary mt-10']); ?>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->Html->script('templates/index'); ?>
