$(document).ready(function(){
/* -----------------------------------------------------------------
    -  Mobile Menu
----------------------------------------------------------------- */
	$('.toggle-menu').click(function(){
		$('.sidenav').animate({width:'toggle'}, 50);
		$(this).find('svg').toggleClass('fa-bars');
		$(this).find('svg').toggleClass('fa-times');
	});

	$(window).scroll(function(e){
	  var $el = $('.sidenav');
	  var isPositionFixed = ($el.css('position') == 'fixed');
	  if ($(this).scrollTop() > 53 && !isPositionFixed){
	    $el.css({'position': 'fixed', 'top': '0px'});
	  }
	  if ($(this).scrollTop() < 53 && isPositionFixed){
	    $el.css({'position': 'absolute', 'top': '52px'});
	  }
	});
/* -----------------------------------------------------------------
    -  Contacts Index
----------------------------------------------------------------- */
	$('.lookup-foreign-entities').on('click', function() {
		var contactRow = $(this).closest('tr');
		var contactId = $(contactRow).attr('data-id');
		var lookupForeignEntitiesUrl = $('#find-foreign-entities-url').attr('data-url');
		$(this).html('<i class="fas fa-minus-circle"></i>');

		if ( $(this).hasClass('lookup-complete') ) {
			toggleList(contactId);
			return false;
		}

		$(contactRow).after('<tr class="loading-results"><td colspan="6"><i class="fas fa-spinner fa-spin"></i></td></tr>');

		$(this).addClass('lookup-complete');
		$.get(lookupForeignEntitiesUrl + '?id=' + contactId, function(data) {
			$(contactRow).after(data);
			$('.loading-results').remove();
		});
	});

	function toggleList(id) {
		var toggleButton = $('tr[data-id="'+id+'"]').find('.lookup-foreign-entities');

		if ($('[data-domestic-id="'+id+'"]:visible').length) {
			$('[data-domestic-id="'+id+'"]').hide();
			$(toggleButton).html('<i class="fas fa-plus-circle"></i>');
		} else {
			$('[data-domestic-id="'+id+'"]').show();
			$(toggleButton).html('<i class="fas fa-minus-circle"></i>');
		}
	}
/* -----------------------------------------------------------------
    -  Contact Add/Edit
----------------------------------------------------------------- */
	$('.incorporated-in').change(function() {
		$('#domestic-foreign').hide();

		if ( $('.incorporated-in option:selected').val() ) {
			$('#domestic-foreign').show();
		}
	});

	$('.domestic-foreign').change(function() {
		var val = $('.domestic-foreign option:selected').val();

		if (val == 'foreign')
			$('.foreign-toggle').hide();
		else
			$('.foreign-toggle').show();
	});

	$('input[type=radio][name=type]').change(function() {
		updateRadioSelection();
	});

	$('[data-name="relationship"]').change(function() {
		$(this).closest('.info-block').addClass('relationship-add');
	});

	function updateRadioSelection() {
		$('.person-inputs, .company-inputs').hide();

	    if ($('input[type=radio][name=type]:checked').val() == 'company') {
	    	$('.company-inputs').show();
	    } else {
	    	$('.person-inputs').show();
	    }
	}

	updateRadioSelection();

	var typingTimer;                //timer identifier
	var doneTypingInterval = 500;  //time in ms, 5 second for example
	var $input = $('.ajax-r');
	var specificInput;
	var rid; //Relactionship array id
	var searchResultElement;
	var contactsSearchUrl = $('#contacts-search-url').attr('data-url');
	var addContactInfoUrl = $('#add-contact-info').attr('data-url');
	var removeContactInfoUrl = $('#remove-contact-info').attr('data-url');
	var addRetionshipUrl = $('#add-relationship').attr('data-url');

	//on keyup, start the countdown
	$(document).on('keyup', '.ajax-r', function () {
		specificInput = $(this);
		rid = $(this).data('rid');

		clearTimeout(typingTimer);
		typingTimer = setTimeout(doneTyping, doneTypingInterval);
	});

	//on keydown, clear the countdown
	$(document).on('keydown', '.ajax-r', function () {
	  clearTimeout(typingTimer);
	});

	//user is "finished typing," do something
	function doneTyping () {
		var searchResultElement = $("[data-srid="+rid+"]");

		if ($(searchResultElement).length) {
			$(searchResultElement).addClass('loading');
			$(searchResultElement).show();
		} else {
			$(specificInput).after('<div class="search-results loading" data-srid="'+rid+'"></div>');
			searchResultElement = $("[data-srid="+rid+"]");
		}

		$(searchResultElement).html('<i class="fas fa-spinner fa-spin"></i>');

		if ($(specificInput).val().length > 1) {
			$.get(contactsSearchUrl + '?q=' + encodeURIComponent($(specificInput).val()), function(data) {
				$(searchResultElement).removeClass('loading');
				$(searchResultElement).html("");
				$(searchResultElement).append(data);
			});
		} else {
			$(searchResultElement).html("");
			$(searchResultElement).hide();
		}
	}

	$(document).on('click', '.contact-relationship', function() {
		var table = $(this).closest('table').data('table');
		// If there is a value set in the contact_id input we will set value as target
		if ($("." +table+ "_contact_id_" + rid).val()) {
			$("." +table+ "_contact_id_target_" + rid).val($(this).attr('data-id'));
		} else {
			$("." +table+ "_contact_id_" + rid).val($(this).attr('data-id'));
		}
		// $(".contact_target_entity_type_" + rid).val($(this).attr('data-domesticOrForeign'));
		$(specificInput).val($(this).attr('data-name'));
		$("[data-srid="+rid+"]").hide();
	});

	$(document).on('click', '.api-add-contact', function(){
		$(this).parent().parent().hide();
		$('.refresh-list-link').show();
	});

	$(document).on('click', '.api-refresh', function(){
		doneTyping();
	});

	// $(document).on('click', '.swap', function(){
	// 	var switchId = $(this).data('id');

	// 	var main = $("[data-nameId='"+ switchId +"']");
	// 	var target = $("[data-targetId='"+ switchId + "']")

	// 	if ($(main).hasClass('ajax-r')) {
	// 		main = $("[data-nameId='"+ switchId + "']").parent().parent();
	// 	}

	// 	if ($(target).hasClass('ajax-r')) {
	// 		target = $("[data-targetId='"+ switchId + "']").parent().parent()
	// 	}

	// 	$(main).swapWith( $(target) );

	// 	// swap value of contact_id and contact_id_target
	// 	var contact = $('.contact_id_'+switchId);
	// 	var contactTarget = $('.contact_id_target_'+switchId);
	// 	var conId = contact.val();
	// 	var tarId = contactTarget.val();

	// 	contact.val(tarId);
	// 	contactTarget.val(conId);

	// 	$(this).closest('.info-block').addClass('relationship-add');

	// 	// Can delete if ajax works
	// 	var swapInput = $('.swap_' + switchId);
	// 	swapInput.val(swapInput.val() == 1 ? 0 : 1);
	// });

	// jQuery.fn.swapWith = function(to) {
	//     return this.each(function() {
	//         var copy_to = $(to).clone(true);
	//         var copy_from = $(this).clone(true);
	//         $(to).replaceWith(copy_from);
	//         $(this).replaceWith(copy_to);
	//     });
	// };

	// On contact form submit add relationships via ajax first
	// $('#contact-form').submit(function(e){
	// 	var submit = false;
	// 	var update = {};

	// 	if ($('.relationship-add').length) {
	// 		$('.relationship-add').each(function(index, value) {
	// 			var key = index;
	// 			update[key] = {};

	// 			$(this).find('input, select').each(function(index, value) {
	// 				var inputName = $(this).attr('data-name');
	// 				if (inputName !== undefined) {
	// 			    	update[key][inputName] = this.value
	// 				}
	// 			});
	// 			// Pass entity type to see if contact is foreign entity
	// 			update[key]['contact_entity_type'] = $('.domestic-foreign option:selected').val();
	// 		});

	// 		$.get(addRetionshipUrl + '?json=' + encodeURI(JSON.stringify(update)), function(data) {
	// 			var data = JSON.parse(data);
	// 			var fix = data.fix;

	// 			// Remove class from all that we just tried saving
	// 			$('.relationship-add').removeClass('relationship-add');
	// 			$('.error-message').html('');

	// 			for (var key in fix) {
	// 			    if (fix.hasOwnProperty(key)) {
	// 			        $("[data-contactrelationships='"+key+"']").addClass('relationship-add error');
	// 			        $("[data-contactrelationships='"+key+"']").find('.error-message').html(fix[key]);
	// 			    }
	// 			}

	// 			if (data.errors) {
	// 				submit = false;
	// 			} else {
	// 				submit = true;
	// 			}
	// 		});
	// 	} else {
	// 		return true;
	// 	}

	// 	setTimeout(function(){
	// 		// wait 1 second
	// 	 }, 1000);

	// 	 if(!submit)
	// 	 	e.preventDefault();
	// });

	$('.add-new').on('click', function(event) {
		event.preventDefault();
		var thisButton = $(this);
		var table = $(this).attr('data-table');

		if (table == 'contactAddresses' && $('.domestic-foreign option:selected').val() == 'foreign') {
			confirmModal(table, thisButton);
		} else {
			addNewElement(table, thisButton);
		}

	});

	function addNewElement(table, thisButton){
		var isContactNew = $('#is-contact-new').attr('data-check');
		var nextArrayIndex = ($("[data-"+table+"]").last().attr('data-' + table) == undefined)
			? 0 : parseInt($("[data-"+table+"]").last().attr('data-' + table)) + 1;

		$.get(addContactInfoUrl + '?array-index=' + nextArrayIndex + '&form=' + table + '&is-contact-new=' + isContactNew, function(data) {
			if (table == 'contactRelationships' || table == 'targetRelationships') {
				$('[data-table='+table+'] tbody tr:last').before(data);
			} else {
				$(thisButton).before(data);
			}

			// if (table == 'contactRelationships') {
			// 	if ($("[data-contactId]").length) {
			// 		$('.relationship-add').last().find('.contact_id').val( $("[data-contactId]").attr('data-contactId') );
			// 	}
			// }
		});
	}

	function confirmModal(table, thisButton) {
		Swal.fire({
		  title: ' Does this contact act as a registered agent for others?',
		  text: "",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Yes',
		  cancelButtonText: 'No'
		}).then((result) => {
		  if (!result.value) {
		    Swal.fire({
		      icon: 'warning',
		      title: 'This is a foreign entity',
		      text: 'Any addresses should be added on the domestic contact. The only exception is when a foreign entity also acts a registered agent for others.'
		    })
		  } else {
		  	addNewElement(table, thisButton);
		  }
		})
	}

	$(document).on('click', '.primary-selection', function(event) {
		var typedPrimaryButtons = $(this).attr('data-name');

		$('[data-name='+typedPrimaryButtons+']').prop('checked', false);
		$(this).prop('checked', true);
	});

	$(document).on('click', '.info-remove', function() {
		var table = $(this).closest('.field-body').find('.table-id').attr('data-table');
		var tableId = $(this).closest('.field-body').find('.table-id').val();
		var infoBlock = $(this).closest('.info-block');

		if (tableId == '') { $(infoBlock).remove(); return };

		if (confirm('Are you sure you would like to remove this contact info?')) {
			$.get(removeContactInfoUrl + '?table=' + table + '&table-id=' + tableId, function(data) {
				$(infoBlock).remove();
			});
		}
	});

	/* -----------------------------------------------------------------
	    -  Notes
	----------------------------------------------------------------- */
	var noteText;
	var noteTextOrig;

	$('.add-note').on('click', function(event){
		event.preventDefault();
		var noteTextarea = $(this).parent().prev();
		var table = $('.notes-table').attr('data-table');
		var saveField = $('.save-to').attr('data-field');
		var url = $('#add-note-url').attr('data-url');
		var id = $('.this-id').attr('data-id');

		if (!noteTextarea.val().length) { return false; }

		$.get(url + '?table=' + table + '&field=' + saveField + '&id=' + id + '&note=' + encodeURIComponent(noteTextarea.val()), function(data) {
			$('.note-textarea').prepend(data);
			$(noteTextarea).val('');
		});
	});

	$(document).on('click', '.edit-note', function() {
		if ($('.editing-note').length) { return false; };

		var note = $(this).closest('.note');
		noteText = $(note).find('.note-text').text();
		noteTextOrig = $(note).find('.note-text').html()

		$(note).addClass('editing-note');
		$(note).find('.note-text').html('<textarea class="textarea is-small is-focused">' + noteText + '</textarea>');

		$(this).closest('.note').find('.note-options').show();
		$(this).closest('.note').find('.note-options .is-hidden').removeClass('is-hidden');
		$(this).closest('.note').find('.note-options .edit-note').addClass('is-hidden');
	});

	$(document).on('click', '.cancel-edit-note', function() {
		var note = $(this).closest('.note');
		$(note).find('.note-text').html(noteTextOrig);

		$(this).closest('.note-options').hide();
		$(this).closest('.note-options').find('.button').addClass('is-hidden');
		$(this).closest('.note-options').find('.edit-note').removeClass('is-hidden');
		$(note).removeClass('editing-note');
	});

	$(document).on('click', '.delete-note', function(){
		var url = $('#delete-note-url').attr('data-url');
		var table = $('.notes-table').attr('data-table');
		var note = $(this).closest('.note');

		if (confirm("Are you sure you want to DELETE this note?")) {
			$.get(url + '?note-id=' + $(note).data('note-id') + '&table=' + table, function(data) {
				$(note).remove();
			});
		}
	});

	$(document).on('click', '.save-note', function(){
		var thisButton = $(this);
		var url = $('#edit-note-url').attr('data-url');
		var table = $('.notes-table').attr('data-table');
		var saveField = $('.save-to').attr('data-field');
		var note = $(this).closest('.note');
		var noteText = $(note).find('textarea').val();

		$.get(url + '?note-id=' + $(note).data('note-id') + '&field=' + saveField + '&table=' + table + '&note=' + encodeURIComponent(noteText), function(data) {
			$(note).find('.note-text').html(data);
			$(thisButton).closest('.note-options').hide();
			$(thisButton).closest('.note-options').find('.button').addClass('is-hidden');
			$(thisButton).closest('.note-options').find('.edit-note').removeClass('is-hidden');
			$(note).removeClass('editing-note');
		});
	});

    // Check for click events on the navbar burger icon
    $(".navbar-burger").click(function() {
        // Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
        $(".navbar-burger").toggleClass("is-active");
        $(".navbar-menu").toggleClass("is-active");

    });

    /* -----------------------------------------------------------------
        -  Account / User edit page
    ----------------------------------------------------------------- */
    $('.open-modal').on('click', function(e){
      e.preventDefault();
      $('#' + $(this).data('modal-name')).addClass('is-active');
    });

    $('.modal-close').on('click', function(e){
         e.preventDefault();
       $('.modal').removeClass('is-active');
    });


    $('.account-type').on('change', function() {
      $('#account-fields').show();
      var account = $('option:selected', this).val();
      if (account == 'pacer') {
        $('.account-state').hide();
        $('#state-id select').prop('required',false);
      } else {
        $('.account-state').show();
        $('#state-id select').prop('required',true);
      }
    });

    $('#phone').keyup(function(){
        $(this).val($(this).val().replace(/(\d{3})\-?(\d{3})\-?(\d{4})/,'$1-$2-$3'))
    });

    $('#phone').keyup();


    window.dynamicsort = function(property, order) {
        var sort_order = 1;
        if (order === "desc") {
            sort_order = -1;
        }
        return function (a, b) {
            // a should come before b in the sorted order
            let firstEl = $(a[property]).is('a') ? $(a[property]).text() : a[property];
            let secondEl = $(b[property]).is('a') ? $(b[property]).text() : b[property];

            if (firstEl < secondEl) {
                return -1 * sort_order;
                // a should come after b in the sorted order
            } else if (firstEl > secondEl) {
                return 1 * sort_order;
                // a and b are the same
            } else {
                return 0 * sort_order;
            }
        }
    }

});
