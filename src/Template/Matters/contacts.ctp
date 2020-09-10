<?php $this->assign('title', $matter->name); ?>

<?= $this->element('matters_view_menu') ?>

<div class="matter-contact-search-url" data-url="<?php echo $this->Url->build('/api/matter-contact-search'); ?>"></div>

<div class="table-container">
	<table class="table is-hoverable">
		<thead>
			<tr>
				<th>Type</th>
				<th>Name</th>
				<th>Title</th>
				<th>Address</th>
				<th>Phone</th>
				<th>Email</th>
				<th></th>
			</tr>
		</thead>
		<tbody style="font-size:smaller;">
			<?php foreach ($matter->matter_contacts as $matter_contact) { ?>
				<tr data-id="<?= $matter_contact->id ?>">
					<td><?= $matter_contact->formatType() ?></td>
					<td class="mc-name">
						<?php if (isset($matter_contact->contact)) {
							echo $this->Html->link($matter_contact->contact->name, ['controller' => 'Contacts', 'action' => 'view', 'id' => $matter_contact->contact->id]);
						} else { echo $matter_contact->getName(); } ?>
					</td>
					<td><?= ($matter_contact->title) ? $matter_contact->title : '-'; ?></td>
					<td><?= $matter_contact->getAddress() ?></td>
					<td><?= $matter_contact->getPhone() ?></td>
					<td><?= $matter_contact->getEmail() ?></td>
					<td class="delete-matter-contact is-clickable"><i class="fas fa-user-minus"></i></td>
				</tr>
			<?php }  ?>
		</tbody>
	</table>
</div>

<style type="text/css">
.expanded {
	width:300px !important;
}
</style>

<script type="text/javascript">
var matterTypingTimer;          //timer identifier
var doneTypingIntervalM = 500;  //time in ms, 5 second for example
var searchResultElement = $('.search-results');

//on keyup, start the countdown
$(document).on('keyup', '.matter-contact-name', function () {
	clearTimeout(matterTypingTimer);

	if ($('.matter-contact-name').val().length > 1) {
		matterTypingTimer = setTimeout(matterSearchDoneTyping, doneTypingIntervalM);
	} else {
		$('.search-results').html("");
		$('.search-results').hide();
	}
});

//on keydown, clear the countdown 
$(document).on('keydown', '.matter-contact-name', function (event) {
  	clearTimeout(matterTypingTimer);
});

//user is "finished typing," do something
function matterSearchDoneTyping () {
	var matterContactSearchUrl = $('.matter-contact-search-url').attr('data-url');

	if ($(searchResultElement).length) {
		$(searchResultElement).addClass('loading');
		$(searchResultElement).show();
	} else {
		$('.matter-contact-name').after('<div class="search-results loading"></div>');
		searchResultElement = $('.search-results');
	}

	$(searchResultElement).html('<i class="fas fa-spinner fa-spin"></i>');
	
	$.get(matterContactSearchUrl + '?q=' + encodeURIComponent($('.matter-contact-name').val()) + '&type=' + $('.matter-contact-type option:selected').val(), function(data) {
		$(searchResultElement).removeClass('loading');
		$(searchResultElement).html("");
		$(searchResultElement).append(data);
	});
}

$('.matter-contact-name').click(function(event) {
	$('.matter-contact-name').addClass('expanded');
});

$(document).on('click', '.matters-search-refresh', function(){
	matterSearchDoneTyping();
})
$('.matter-contact-type').change(function(){
	if ($('.matter-contact-name').val().length > 1) {
		matterSearchDoneTyping();
	};
});

$(document).on('click', '.matter-contact', function() {
	var contact = $(this);
	var type = $('.matter-contact-type option:selected').val();

	var inputToSaveTo = 'contact_id';

	if (type == 'attorney_for_plaintiff' || type == 'paralegal_for_plaintiff') {
		inputToSaveTo = 'team_member_id';
	} else if (type == 'plaintiff') {
		inputToSaveTo = 'imported_user_id';
	}
	
	// Clear any values that may have been set
	$("input[name='contact_id'], input[name='team_member_id'], input[name='imported_user_id']").val("");
	// Set input val to selected contact name
	$('.matter-contact-name').val( $(contact).attr('data-name') );
	// Set value of contact onto hidden input
	$('input[name='+inputToSaveTo+']').val( $(contact).attr('data-id') );
	// Hide and clear search results
	$('.search-results').html("");
	$('.search-results').hide();
});


$('.delete-matter-contact').on('click', function() {
	var baseURL = window.location.origin;
	var row = $(this).parent();
	var matterContactId = $(row).attr('data-id');
	var contactName = $(this).parent().find('.mc-name').text().trim();

	if (confirm("Are you sure you want to remove " + contactName + "?")) {
		$.get(baseURL + '/matters/contact-delete?id=' + matterContactId, function(data) {
			$(row).remove();
		});
	};

});
</script>