$(document).ready(function() {
	var nextSetId = parseInt($('.rule-set:last').attr('data-count')) + 1;
	if (isNaN(nextSetId)) {
		nextSetId = 1;
	};

	$(document).on('click', '.add-condition', function() {
		var setIndex = $(this).parent().attr('data-index');
		var nextId = parseInt($(this).parent().find('.field-body:last').attr('data-rule-id')) + 1;

		$(this).before( newRule(setIndex, nextId) );
	});

	$('.add-rule-set').on('click', function(e){
		e.preventDefault();
		zeroBasedNextId = nextSetId - 1;

		var newSet = '<div class="field rule-set" data-count="'+nextSetId+'" data-index="'+zeroBasedNextId+'">' + 
		'<label class="label">Rule Set '+nextSetId+'</label>' +
		newRule(zeroBasedNextId, 0) +
		'<div class="add-condition">' +
			'<a><i class="fas fa-plus-circle"></i> Add Rule</a>' +
		'</div>'+
		'</div>';

		$(this).parent().before(newSet);

		nextSetId++;
	});

	function newRule(setId = 0, conditionId = 0) {
		var newRule = '<div class="field-body" data-rule-id="'+conditionId+'">' +
			'<div class="field">' +
				'<div class="select">' +
					'<select name="rule_condition_sets['+setId+'][rule_conditions]['+conditionId+'][type]">' +
						'<option value="domain" selected="selected">Domain</option>' +
						'<option value="message_text">Message Text</option>' +
						'<option value="phone_number">Phone Number</option>' +
						'<option value="ips">IP Address</option>' +
						'<option value="reg_email">Domain Registrant Email</option>' +
						'<option value="reg_name">Domain Registrant Name</option>' +
					'</select>' +
				'</div>'+
			'</div>' +
			'<div class="field">' +
				'<div class="select">' +
					'<select name="rule_condition_sets['+setId+'][rule_conditions]['+conditionId+'][search_type]">' +
						'<option value="contains" selected="selected">Contains</option>' +
					'</select>' +
				'</div>' +
			'</div>' +
			'<div class="field">' +
				'<div class="control">' +
					'<input type="text" autocomplete="no" name="rule_condition_sets['+setId+'][rule_conditions]['+conditionId+'][search_for]" class="input rule-sf" placeholder="Value..." required="required" maxlength="255" value="">' +
				'</div>' +
			'</div>' +
			'<div class="remove-rule"><i class="fas fa-minus-circle"></i></div>' +
		'</div>';
		return newRule;
	}

	$(document).on('click', '.remove-rule', function() {
		var currentRule = $(this);
		var ruleSet = $(currentRule).parent().parent();

		if(confirm("Are you sure you want to remove this rule?")) {
			$(currentRule).parent().remove();

			// Remove rule set if there are no rules in it
			if ($(ruleSet).find('.field-body').length == 0) {
				$(ruleSet).remove();
			};
		}
	});
});