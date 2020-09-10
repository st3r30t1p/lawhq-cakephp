<?php $this->assign('title', $contact->name); ?>
<?= $this->element('contacts_view_menu') ?>

<div class="columns">
	<div class="column">
		<div class="columns">

			<div class="column"> 
				<div class="box">
					<div class="contact-info-header"><i class="fas fa-user-tag"></i> General Info</div>
					<table class="contact-information-table">
						<?php if ($contact->type == 'person') { ?>
							<tr>
								<td>Middle Name</td>
								<td><?= ($contact->person_middle_name) ? $contact->person_middle_name : '-'; ?></td>
							</tr>
							<tr>
								<td>DOB</td>
								<td><?= ($contact->person_dob) ? $contact->person_dob : '-'; ?></td>
							</tr>
							<tr>
								<td>SSN</td>
								<td><?= ($contact->ssn) ? $contact->ssn : '-'; ?></td>
							</tr>
						<?php } else { ?>
							<tr>
								<td>Incorporated</td>
								<td><?= ($contact->company_incorporated_in) ? $contact->States['state'] : '-'; ?></td>
							</tr>
							<tr>
								<td>Company #</td>
								<td><?= ($contact->company_registration_number) ? $contact->company_registration_number : '-'; ?></td>
							</tr>
							<tr>
								<td>EIN</td>
								<td><?= ($contact->fein) ? $contact->fein : '-'; ?></td>
							</tr>
						<?php } ?>
						<tr>
							<td>Phone</td>
							<td>
								<?php if (count($contact->contact_phone_numbers)) { foreach ($contact->contact_phone_numbers as $key => $phone) { ?>
									<div><?= $phone->phone_number ?></div>
								<?php }} else echo '-'; ?>
							</td>
						</tr>
						<tr>
							<td>Email</td>
							<td>
								<?php if (count($contact->contact_emails)) { foreach ($contact->contact_emails as $key => $email) { ?>
									<div><?= $email->email ?></div>
								<?php }} else echo '-'; ?>
							</td>
						</tr>
						<tr>
							<td>Website</td>
							<td>	
								<?php if (count($contact->contact_websites)) { foreach ($contact->contact_websites as $key => $website) { ?>
									<div><a href="<?= $website->addHttp($website->website) ?>" target="_blank"><?= $website->website ?></a></div>
								<?php }} else echo '-'; ?>
							</td>
						</tr>
						<tr>
							<td>Address</td>
							<td style="display: flex; flex-wrap: wrap;">
								<?php if (count($contact->contact_addresses)) { foreach ($contact->contact_addresses as $key => $address) { ?>
									<div class="contact-address">
										<div class="contact-type"><?= $address->formattedType() ?></div>
										<div><?= $address->address_1 ?></div>
										<div><?= $address->address_2 ?></div>
										<div><?= $address->city . ', ' . $address->state->code . ' ' . $address->zip ?></div>
									</div>
								<?php }} else echo '-'; ?>
							</td>
						</tr>
						<?php if ($contact->type == 'company') { ?>
							<tr>
								<td>Can Sue In</td>
								<td><?= $contactInfo->canSueIn() ?></td>
							</tr>
							<tr>
								<td>Present In</td>
								<td><?= $contactInfo->presentIn() ?></td>
							</tr>
						<?php } ?>
					</table>
				</div>

				<div class="box">
					<div class="contact-info-header"><i class="fas fa-envelope"></i> Where to Serve</div>

					<?php foreach ($contactInfo->where_to_serve as $name => $where) { if (count($contactInfo->where_to_serve[$name])) { ?>
					<div class="serve-address">
						<p style="font-weight:500"><?= $name ?></p>
						<hr class="low-margin">
						<table class="contact-information-table">
							<?php foreach ($contactInfo->where_to_serve[$name] as $key => $address) { if (isset($address->state)) { ?>
								<tr>
									<td><?= $address->state->state ?></td>
									<td>	
										<div><?= $address->name ?></div>
										<div><?= $address->address_1 ?></div>
										<div><?= $address->address_2 ?></div>
										<div><?= $address->city . ', ' . $address->state->code . ' ' . $address->zip ?></div>
									</td>
								</tr>
							<?php }} ?>
						</table>
					</div>
					<?php }} ?>
				</div>

			</div>
		</div>
	</div>

	<div class="column">
		<div class="box">
			<div class="contact-info-header"><i class="fas fa-user-friends"></i> Relationships</div>
			
			<?php foreach ($contactInfo->relationshipGroups as $key => $group) {
				if (!empty($contactInfo->relationshipGroups[$key])) { 
					if ($key != 'individual') {
						echo '<p style="font-weight:500">' . ucwords(str_replace('_', ' ', $key)) . '</p>';
					} ?>
					<ul style="margin-bottom:10px">
						<?php foreach ($contactInfo->relationshipGroups[$key] as $relationship) { ?>
							<li>
								<?php if ($key == 'individual' || $key == 'other') { ?>
									<span class="other-relationship"><?= $relationship['type'] ?></span>
								<?php } ?>

								<?php if (!empty($relationship['incorporated_in']) && $key == 'foreign_entities') { ?>
									<span class="relationship-incorporated"><?= $relationship['incorporated_in'] ?></span>
								<?php } ?>

								<a href="<?= $relationship['contact_url'] ?>"><?= $relationship['contact_name'] ?></a>
							</li>
						<?php } ?>
					</ul>
			<?php }} ?>
		</div>

		<div class="box">
			<div class="contact-info-header"><i class="fas fa-user-secret"></i> DBA</div>
			<?php foreach ($contact->contact_dbas as $dba) { ?>
				<div><?= $dba->name ?></div>
			<?php } ?>
		</div>
	</div>

	<?php if ($contact->company_domestic_foreign != 'foreign') { ?>
		<div class="column">
			<div class="box">
				<div class="contact-info-header"><i class="fas fa-comment-alt"></i> Notes</div>
				<div class="this-id" data-id="<?= $contact->id ?>"></div>
				<div class="notes-table" data-table="contactNotes"></div>
				<div class="save-to" data-field="contact_id"></div>
				<?= $this->element('notes', ['notes' => $contact->contact_notes]) ?>
			</div>
		</div>
	<?php } ?>
</div>