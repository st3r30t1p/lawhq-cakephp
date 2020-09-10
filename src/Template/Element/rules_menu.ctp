<nav class="navbar" role="navigation" aria-label="main navigation" style="margin-bottom:20px">
    <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false">
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
    </a>
  <div id="navbarBasicExample" class="navbar-menu">
    <div class="navbar-start">
      <?= $this->Html->link('Rules', ['action' => 'index'], ['class' => 'navbar-item ' . $this->Link->isActivePage('/rules', true)]) ?>
      <?= $this->Html->link('Rules Approve', ['action' => 'approve'], ['class' => 'navbar-item ' . $this->Link->isActivePage('/rules/approve', true)]) ?>
    </div>
  </div>
</nav>