<?php
$this->Form->setTemplates([
    'inputContainer' => '<div class="field"><div class="control">{{content}}</div></div>',
    'select' => '<div class="field"><div {{attrs}}><select name="{{name}}" data-name="relationship">{{content}}</select></div></div>',
    'inputContainerError' => '<div class="field"><div class="control is-danger">{{content}}</div><p class="help is-danger">{{error}}</p></div>',
    'input' => '<input type="{{type}}" autocomplete="false" name="{{name}}"{{attrs}}/>',
]);

if ($form == 'contactAddresses') {
	echo $this->element('address');
} else if($form == 'contactPhoneNumbers') {
	echo $this->element('phone_number');
} else if($form == 'contactEmails') {
	echo $this->element('email');
} else if($form == 'contactWebsites') {
	echo $this->element('website');
} else if($form == 'contactDbas') {
	echo $this->element('dba');
} else if($form == 'contactRelationships') {
	echo $this->element('relationship', ['contactId' => $isContactNew]);
} else if($form == 'targetRelationships') {
	echo $this->element('target_relationship', ['contactId' => $isContactNew]);
}
?>