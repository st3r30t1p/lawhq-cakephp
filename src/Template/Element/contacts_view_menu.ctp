<div style="margin-bottom:20px">
	<ul class="flex">
		<li style="margin-left:5px">
			<h1 class="title is-4"><?= $contact->name . ' ' . $contact->getPersonStateOrCompanyIncIn() ?></h1>
		</li>
		<li>
			<a class="button is-green is-small" href="<?= $this->Url->build(['action' => 'edit', 'id' => $contact->id]) ?>">
			   <span class="icon is-small">
			     <i class="fas fa-pencil-alt"></i>
			   </span>
			   <span>Edit</span>
			 </a>
		</li>
	</ul>
</div>

<nav class="navbar" role="navigation" aria-label="main navigation" style="margin-bottom:20px">
  <div id="navbarBasicExample" class="navbar-menu">
    <div class="navbar-start">
      <?= $this->Html->link('Dashboard', ['action' => 'view', 'id' => $contact->id], ['class' => 'navbar-item ' . $this->Link->isActivePage('/contacts/view/' . $contact->id, true)]) ?>
      <?= $this->Html->link('Spam', ['action' => 'spam', '?' => ['contact-id' => $contact->id]], ['class' => 'navbar-item ' . $this->Link->isActivePage('/contacts/spam', true)]) ?>
    </div>
  </div>
</nav>