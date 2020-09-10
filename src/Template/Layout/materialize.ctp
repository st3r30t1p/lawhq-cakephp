<!doctype html>
<html lang="en">

<head>
    <?= $this->Html->charset() ?>
    <?= $this->Html->meta('icon', $this->Url->build('/favicon-32x32.png')); ?>
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title><?php echo $this->fetch('title') ?> - LawHQ</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
    <link href="<?php echo $this->Url->build('/themes/bulma/'); ?>bulma.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.11.0/css/selectize.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.11.0/js/standalone/selectize.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <?= $this->Html->script('sortable') ?>
    <?= $this->Html->css('lawhq.0.1.11') ?>
    <?= $this->Html->script('lawhq.0.1.8') ?>
</head>
<body>
    <header>
        <div style="display:inline-block">
            <?php echo $this->Html->image('logo.svg', ['class' => 'logo']); ?>
        </div>
        <div class="toggle-menu">
            <i class="fas fa-bars"></i>
        </div>
<!--         <div style="float: right; padding: 5px 0;">
            <div class="field" style="width: 350px; display: inline-block;position:relative">
                <input class="header-search-input" type="text" placeholder="Search">
                <select class="ui compact selection dropdown header-dropdown">
                  <option selected="" value="threads">Threads</option>
                  <option value="contacts">Contacts</option>
                  <option value="matters">Matters</option>
                </select>
            </div>
        </div> -->
        <div style="float:right">
            <div class="dropdown is-hoverable is-right">
              <div class="dropdown-trigger">
                <button class="button" aria-haspopup="true" aria-controls="dropdown-menu" style="font-size: 14px; border: none;padding-top: 10px;">
                  <span>History</span>
                  <span class="icon is-small">
                    <i class="fas fa-angle-down" aria-hidden="true"></i>
                  </span>
                </button>
              </div>
              <div class="dropdown-menu" id="dropdown-menu" role="menu" style="z-index: 100;">
                <div class="dropdown-content">
                    <?php if (empty($history)) { ?>
                        <p style="font-size: 13px; padding-left: 20px;">Empty</p>
                    <?php } ?>
                    <?php foreach ($history as $h) { ?>
                        <a href="<?= $h['url'] ?>" class="dropdown-item">
                          <?= $h['page_name'] ?> <?= $h['id'] ?>
                        </a>
                    <?php } ?>
                </div>
              </div>
            </div>
        </div>

    </header>
    <ul id="slide-out" class="sidenav sidenav-fixed">
        <aside class="menu">
<!--             <p class="menu-label">
                <i class="fas fa-building large"></i>
                Comany
            </p>
            <ul class="menu-list">
                <li>
                    <ul>
                        <li><a>Team</a></li>
                        <li><a>Blog</a></li>
                        <li><a>Conflict Check</a></li>
                    </ul>
                </li>
            </ul> -->

            <ul class="menu-list">
                <li><a href="<?= $this->Url->build('/templates'); ?>" class="<?= $this->Link->isActivePage('/templates') ?>">
                        Templates
                    </a>
                    <ul>
                        <li><a href="<?= $this->Url->build('/section-templates'); ?>" class="<?= $this->Link->isActivePage('/section-templates') ?>">Section Templates</a></li>
                    </ul>
                </li>
            </ul>

            <ul class="menu-list non-tabbed-list">
                <!-- <li><a>Calendar</a></li> -->
                <!-- <li><a>Tasks</a></li> -->
                <li><a href="<?= $this->Url->build('/team-members'); ?>" class="<?= $this->Link->isActivePage('/team-members') ?>">Team Members</a></li>
                <li><a href="<?= $this->Url->build(['controller' => 'conferences']); ?>" class="<?= $this->Link->isActivePage('/conferences') ?>">Conference Rooms</a></li>
                <li><a href="<?= $this->Url->build('/blog-posts'); ?>" class="<?= $this->Link->isActivePage('/blog-posts') ?>">Blog</a></li>
                <li><a href="<?= $this->Url->build(['controller' => 'matters']); ?>" class="<?= $this->Link->isActivePage('/matters') ?>">Matters</a></li>
                <li><a href="<?= $this->Url->build(['controller' => 'old_matters']); ?>" class="<?= $this->Link->isActivePage('/old-matters') ?>">Old Matters</a></li>
                <li><a href="<?= $this->Url->build(['controller' => 'importedUsers']); ?>" class="<?= $this->Link->isActivePage('/imported-users') ?>">App Users</a></li>
                <li><a href="<?= $this->Url->build(['controller' => 'contacts']); ?>" class="<?= $this->Link->isActivePage('/contacts') ?>">Contacts</a></li>
                <li><a href="<?= $this->Url->build(['controller' => 'dockets']); ?>" class="<?= $this->Link->isActivePage('/dockets') ?>">Dockets</a></li>
            </ul>

            <p class="menu-label">
                <i class="fas fa-phone"></i>
                TCPA
            </p>
            <ul class="menu-list">
                <li>
                    <ul>
                        <li><a href="<?= $this->Url->build('/messages'); ?>" class="<?= $this->Link->isActivePage('/messages') ?>">Messages</a></li>
                        <li><a href="<?= $this->Url->build('/threads'); ?>" class="<?= $this->Link->isActivePage('/threads') ?>">Threads</a></li>
                        <li><a href="<?= $this->Url->build(['controller' => 'thread_groups']); ?>" class="<?= $this->Link->isActivePage('/thread-groups') ?>">Groups</a></li>
                        <li><a href="<?= $this->Url->build('/domains'); ?>" class="<?= $this->Link->isActivePage('/domains') ?>">Domains</a></li>
                        <!-- <li><a>IPs</a></li> -->
                        <li><a href="<?= $this->Url->build('/rules'); ?>" class="<?= $this->Link->isActivePage('/rules') ?>">Rules</a></li>
                        <li><a href="<?= $this->Url->build('/threads/search'); ?>" class="<?= $this->Link->isActivePage('/threads/search') ?>">Search</a></li>
                        <!-- <li><a>Rules</a></li> -->
                        <!-- <li><a>DNCR</a></li> -->
                    </ul>
                </li>
            </ul>

            <ul class="menu-list non-tabbed-list">
                <li><a href="<?= $this->Url->build('/team-members/account'); ?>" class="<?= $this->Link->isActivePage('/team-members/account') ?>">My Account</a></li>
                <li><a href="<?= $this->Url->build('/team-members/login'); ?>">Log Out</a></li>
            </ul>
        </aside>
    </ul>
    </div>

    <div class="wrapper">
        <div style="background-color:#eeeeee">
            <div style="padding:1em 1.5em">
                <div class="container-fluid">
                    <div id="delete-note-url" data-url="<?php echo $this->Url->build('/api/delete-note'); ?>"></div>
                    <div id="edit-note-url" data-url="<?php echo $this->Url->build('/api/edit-note'); ?>"></div>
                    <div id="add-note-url" data-url="<?php echo $this->Url->build('/api/add-note'); ?>"></div>
                    <div id="add-relationship" data-url="<?php echo $this->Url->build('/api/add-relationship'); ?>"></div>

                    <?= $this->Flash->render() ?>
                    <?= $this->fetch('content') ?>
                </div>
            </div>
        </div>

        <footer class="footer" style="text-align:center">
            <div class="container ">
                <p style="font-size:12px;">
                    Copyright &copy; LawHQ <?= date('Y', strtotime('now')) ?>, All Rights Reserved
                </p>
            </div>
        </footer>
    </div>

    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->Url->build('/js/timezonejs/'); ?>timezones.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.4/clipboard.min.js"></script>
</body>
</html>
