<?php
	$this->assign('title', 'Add User');
	$this->set('wunder_title', 'Add User');
?>

<div class="sectionTemplates index large-9 medium-8 content">
    <h3><?= __('Add User') ?></h3>
    <div class="box">
        <?php echo $this->Form->create($user, ['templates' => [
            'inputContainer' => '<div class="field"><div class="control">{{content}}</div></div>',
            'select' => '<div class="field"><div {{attrs}}><select name="{{name}}">{{content}}</select></div></div>'
        ]]); ?>
        <div class="row">
            <div class="field">
                <label class="label">Username</label>
                <div class="control">
                    <?php echo $this->Form->text('un', ['class' => 'input', 'required' => 'required']); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="field mt-10">
                <label class="label">Password</label>
                <div class="control">
                    <?php echo $this->Form->password('pw', ['class' => 'input', 'required' => 'required']); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="field mt-10">
                <label class="label">First name</label>
                <div class="control">
                    <?php echo $this->Form->text('fn', ['class' => 'input', 'required' => 'required']); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="field mt-10">
                <label class="label">Last name</label>
                <div class="control">
                    <?php echo $this->Form->text('ln', ['class' => 'input', 'required' => 'required']); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="field mt-10">
                <label class="label">Personal Email</label>
                <div class="control">
                    <?php echo $this->Form->text('pe', ['class' => 'input']); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="field mt-10">
                <label class="label">Phone Number</label>
                <div class="control">
                    <?php echo $this->Form->text('pn', ['class' => 'input', 'id' => 'phone', 'maxlength' => 12]); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="field mt-10">
                <label class="label">Address</label>
                <div class="control">
                    <?php echo $this->Form->text('address_1', ['class' => 'input', 'id' => 'phone']); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="field mt-10">
                <label class="label">Address 2</label>
                <div class="control">
                    <?php echo $this->Form->text('address_2', ['class' => 'input', 'id' => 'phone']); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="field mt-10">
                <label class="label">City</label>
                <div class="control">
                    <?php echo $this->Form->text('city', ['class' => 'input', 'id' => 'phone']); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="field mt-10">
                <label class="label">State</label>
                <div class="control">
                     <?php echo $this->Form->control('state_id', ['class' => 'select is-fullwidth', 'empty' => 'State', 'label' => false, 'options' => $states]); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="field mt-10">
                <label class="label">Zip</label>
                <div class="control">
                    <?php echo $this->Form->text('zip', ['class' => 'input', 'id' => 'phone']); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s6">
                <?php echo $this->Form->button('Add', ['class' => 'button is-green mt-10']); ?>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>
