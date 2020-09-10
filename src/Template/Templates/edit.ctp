<?php
$this->assign('title', 'Edit Template');
$this->set('wunder_title', 'Edit Template');
?>

<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Templates'), ['action' => 'index']) ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $template->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $template->id)]
            )
            ?></li>
    </ul>
</nav>

<div class="sectionTemplates index large-9 medium-8 content">
    <h3><?= __('Edit Template') ?></h3>
    <div class="box">
        <?php echo $this->Form->create($template, ['url' => [
            'controller' => 'Templates',
            'action' => 'update'
        ]]); ?>
        <div class="field">
            <?php echo $this->Form->text('name', ['class' => 'input', 'placeholder' => 'Name', 'required' => 'required', 'value' => $template->name]); ?>
        </div>
        <div class="field">
            <?php echo $this->Form->text('google_doc_id', ['class' => 'input', 'placeholder' => 'Google Doc ID', 'required' => 'required', 'value' => $template->google_doc_id]); ?>
        </div>
<!--        <div class="field-wrapper mb-10" data-wrap-name="parameters">-->
<!--            <span><b>Parameters:</b></span>-->
<!--            --><?php //$index = 0;
//            foreach ($template->parameters as $name => $type) : ?>
<!--            <div class="field field-custom">-->
<!--                    --><?php //echo $this->Form->text('parameters[' . $index . '][name]', ['class' => 'input input-custom', 'placeholder' => 'Parameter name', 'required' => 'required', 'value' => $name]); ?>
<!--                    --><?php //echo $this->Form->select('parameters[' . $index . '][type]', ['string' => 'String', 'array' => 'List'], ['empty' => '(choose type)', 'class' => 'select', 'required' => 'required', 'default' => $type]); ?>
<!--                    --><?php //if ($index == 0) : ?>
<!--                        <a href="javascript:void(0);" class="add-button"-->
<!--                           title="Add field">--><?php //echo $this->Html->image('add-icon.png', ['alt' => 'add-icon']); ?><!--</a>-->
<!--                    --><?php //else: ?>
<!--                        <a href="javascript:void(0);" class="remove-button"-->
<!--                           title="Remove field">--><?php //echo $this->Html->image('remove-icon.png', ['alt' => 'remove-icon']); ?><!--</a>-->
<!--                    --><?php //endif; ?>
<!--            </div>-->
<!--            --><?php //$index++; endforeach; ?>
<!--        </div>-->
        <div class="field-wrapper" data-wrap-name="sections">
            <span><b>Section Templates:</b></span>
            <?php if (empty($template->section_templates)) : ?>
                <div class="field field-custom field-custom-sections">
                    <div class="control">
                        <div class="select">
                            <?php echo $this->Form->select('sectionTemplates[]', $sectionTemplates, ['empty' => '(choose type)', 'class' => 'select select-sections', 'required' => 'required']); ?>
                        </div>
                            <a href="javascript:void(0);" class="add-button"
                       title="Add field"><?php echo $this->Html->image('add-icon.png', ['alt' => 'add-icon']); ?></a>
                    </div>
                </div>
            <?php else :
                $index = 0;
                foreach ($template->section_templates as $key => $sTemplate) : ?>
                    <div class="field field-custom field-custom-sections">
                        <div class="control">
                            <div class="select">
                                <?php echo $this->Form->select('sectionTemplates[]', $sectionTemplates, ['empty' => '(choose section)', 'class' => 'select select-sections', 'required' => 'required', 'default' => $sTemplate->id]); ?>
                            </div>
                                <?php if ($index == 0) : ?>
                            <a href="javascript:void(0);" class="add-button"
                               title="Add field"><?php echo $this->Html->image('add-icon.png', ['alt' => 'add-icon']); ?></a>
                        <?php else: ?>
                            <a href="javascript:void(0);" class="remove-button"
                               title="Remove field"><?php echo $this->Html->image('remove-icon.png', ['alt' => 'remove-icon']); ?></a>
                        <?php endif; ?>
                        </div>
                    </div>
                    <?php $index++; endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="field">
            <?php echo $this->Form->button('Edit', ['class' => 'button is-primary mt-10']); ?>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>

<?php echo $this->Html->script('templates/index'); ?>

