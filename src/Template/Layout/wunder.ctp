<!doctype html>
<html lang="en">

<head>
    <?= $this->Html->charset() ?>
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png" />
    <link rel="icon" type="image/png" href="../assets/img/favicon.png" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title><?php echo $this->fetch('title') ?> - Law HQ</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
    <!-- Bootstrap core CSS     -->
    <link href="<?php echo $this->Url->build('/themes/wunder/'); ?>css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo $this->Url->build('/themes/wunder/'); ?>vendors/material.min.css" rel="stylesheet" />
    <!--  Material Dashboard CSS    -->
    <link href="<?php echo $this->Url->build('/themes/wunder/'); ?>css/wunder.css" rel="stylesheet" />
    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="<?php echo $this->Url->build('/themes/wunder/'); ?>css/demo.css" rel="stylesheet" />
    <!--     Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons" />
    <link href="<?php echo $this->Url->build('/themes/wunder/'); ?>vendors/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">

</head>

<body>
    <div class="wrapper">
        <nav class="sidebar" data-background-color="white">
            <div class="logo">
                <a href="#" class="simple-text">
                    Law HQ
                </a>
            </div>
            <div class="logo logo-mini">
                <a href="#" class="simple-text">
                    L-HQ
                </a>
            </div>
            <div class="sidebar-wrapper">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?php echo $this->Url->build('/messages/threads'); ?>">
                            <i class="material-icons">view_list</i>
                            <p>
                                Threads
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?php echo $this->Url->build('/messages'); ?>">
                            <i class="material-icons">textsms</i>
                            <p>
                                All Messages
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?php echo $this->Url->build('/domains'); ?>">
                            <i class="material-icons">textsms</i>
                            <p>
                                Domains
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?php echo $this->Url->build('/users/acccount'); ?>">
                            <i class="material-icons">account_circle</i>
                            <p>
                                Account
                            </p>
                        </a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="material-icons">library_books</i>
                            <p>
                                Documentation
                            </p>
                        </a>
                    </li> -->
                </ul>
            </div>
        </nav>
        <div class="main-panel">
            <nav class="navbar navbar-toggleable-md navbar-default navbar-absolute navbar-inverse" data-topbar-color="blue">
                <!-- <div class="navbar-minimize">
                    <button id="minimizeSidebar" class="btn btn-round btn-white btn-fill btn-just-icon">
					              <i class="material-icons visible-on-sidebar-regular f-26">keyboard_arrow_left</i>
                        <i class="material-icons visible-on-sidebar-mini f-26">keyboard_arrow_right</i>
                    </button>
                </div> -->
                <div class="navbar-header d-flex">
                    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                      <span class="navbar-toggler-icon"></span>
                    </button>
                    <a class="navbar-brand breadcrumb-item" href="#"><?= $this->get('wunder_title', 'Unknown') ?></a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown">
                            <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                                <i class="material-icons">notifications</i>
                                <!-- <span class="notification">0</span> -->
                                <p class="hidden-lg-up">
                                    Notifications
                                    <i class="material-icons">arrow_drop_down</i>
                                </p>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#">No notifications.</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link" data-toggle="dropdown">
                                <i class="material-icons">person</i>
                                <p class="hidden-lg-up">
                                    Account
                                    <i class="material-icons">arrow_drop_down</i>
                                </p>
                            </a>
                            <div class="dropdown-menu">
                                <!-- <a class="dropdown-item" href="#">Account Details</a> -->
                                <?php echo $this->Html->link('Account Info', '/portal/account', ['class' => 'dropdown-item']) ?>
                                <?php echo $this->Html->link('Logout', '/portal/login', ['class' => 'dropdown-item']) ?>
                            </div>
                        </li>
                        <li class="separator hidden-lg-up"></li>
                    </ul>
                </div>
            </nav>

            <div class="content">
                <div class="container-fluid">
                    <?= $this->fetch('content') ?>
                </div>
            </div>
            <footer class="footer">
                <div class="container ">
                    <nav class="float-left ">
                        <ul>
                            <li><?php echo $this->Html->link('Home', '/'); ?></li>
                            <!-- <li><?php echo $this->Html->link('About', '/about'); ?></li> -->
                            <li><?php echo $this->Html->link('Products', '/products'); ?></li>
                            <!-- <li><?php echo $this->Html->link('For Therapists', '/therapist-information'); ?></li> -->
                            <li><?php echo $this->Html->link('Contact', '/contact'); ?></li>
                            <li><?php echo $this->Html->link('Privacy Policy', '/privacy'); ?></li>
                        </ul>
                    </nav>
                    <p class="copyright float-right ">
                        Copyright &copy; Law HQ 2019, All Rights Reserved
                    </p>
                </div>
            </footer>
        </div>
        <!-- <div class="fixed-plugin ">
            <div class="dropdown show-dropdown">
                <a href="#" data-toggle="dropdown" aria-expanded="false" class="dropdown-toggle">
                    <i class="fa fa-cog fa-2x "> </i>
                </a>
                <div class="dropdown-menu">

                        <a href="javascript:void(0) " class="adjustments-line dropdown-item switch-trigger active-color ">
                          <h6 class="header-title dropdown-header"> Topbar Filters</h6>

                            <div class="badge-colors text-center ">
                                <span class="badge filter badge-default " data-color="default"></span>
                                <span class="badge filter badge-blue" data-color="blue"></span>
                                <span class="badge filter badge-green" data-color="green"></span>
                                <span class="badge filter badge-yellow" data-color="yellow"></span>
                                <span class="badge filter badge-red" data-color="red"></span>
                                <span class="badge filter badge-white" data-color="white"></span>
                            </div>
                            <div class="clearfix "></div>
                        </a>
                        <a href="javascript:void(0)" class="adjustments-line dropdown-item switch-trigger background-color">
                          <h6 class="header-title dropdown-header">Sidebar Background</h6>

                            <div class="text-center">
                                <span class="badge filter badge-gray" data-color="gray"></span>
                                <span class="badge filter badge-white active" data-color="default"></span>
                            </div>
                            <div class="clearfix "></div>
                        </a>
                        <a href="javascript:void(0) " class="adjustments-line switch-trigger">
                            <p>Sidebar Mini</p>
                            <div class="togglebutton switch-sidebar-mini">
                                <label>
									                  <input type="checkbox" unchecked>
                                    <span class="toggle"></span>
								                </label>
                            </div>
                            <div class="clearfix "></div>
                        </a>
                </div>
            </div>
        </div> -->
    </div>

<!--   Core JS Files   -->
<script src="<?php echo $this->Url->build('/themes/wunder/'); ?>vendors/jquery-3.1.1.min.js" type="text/javascript"></script>
<script src="<?php echo $this->Url->build('/themes/wunder/'); ?>vendors/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?php echo $this->Url->build('/themes/wunder/'); ?>vendors/tether.min.js" type="text/javascript"></script>
<script src="<?php echo $this->Url->build('/themes/wunder/'); ?>vendors/bootstrap.min.js " type="text/javascript "></script>
<script src="<?php echo $this->Url->build('/themes/wunder/'); ?>vendors/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>
<!-- Forms Validations Plugin -->
<script src="<?php echo $this->Url->build('/themes/wunder/'); ?>vendors/jquery.validate.min.js"></script>
<!--  Plugin for Date Time Picker and Full Calendar Plugin-->
<script src="<?php echo $this->Url->build('/themes/wunder/'); ?>vendors/moment.min.js"></script>
<!--  Charts Plugin -->
<script src="<?php echo $this->Url->build('/themes/wunder/'); ?>vendors/charts/flot/jquery.flot.js"></script>
<script src="<?php echo $this->Url->build('/themes/wunder/'); ?>vendors/charts/flot/jquery.flot.resize.js"></script>
<script src="<?php echo $this->Url->build('/themes/wunder/'); ?>vendors/charts/flot/jquery.flot.pie.js"></script>
<script src="<?php echo $this->Url->build('/themes/wunder/'); ?>vendors/charts/flot/jquery.flot.stack.js"></script>
<script src="<?php echo $this->Url->build('/themes/wunder/'); ?>vendors/charts/flot/jquery.flot.categories.js"></script>
<script src="<?php echo $this->Url->build('/themes/wunder/'); ?>vendors/charts/chartjs/Chart.min.js" type="text/javascript"></script>

<!--  Plugin for the Wizard -->
<script src="<?php echo $this->Url->build('/themes/wunder/'); ?>vendors/jquery.bootstrap-wizard.js"></script>
<!--  Notifications Plugin    -->
<script src="<?php echo $this->Url->build('/themes/wunder/'); ?>vendors/bootstrap-notify.js"></script>
<!-- DateTimePicker Plugin -->
<script src="<?php echo $this->Url->build('/themes/wunder/'); ?>vendors/bootstrap-datetimepicker.js"></script>
<!-- Vector Map plugin -->
<script src="<?php echo $this->Url->build('/themes/wunder/'); ?>vendors/jquery-jvectormap.js"></script>
<!-- Sliders Plugin -->
<script src="<?php echo $this->Url->build('/themes/wunder/'); ?>vendors/nouislider.min.js"></script>
<!--  Google Maps Plugin    -->
<!-- <script src="https://maps.googleapis.com/maps/api/js"></script> -->
<!-- Select Plugin -->
<script src="<?php echo $this->Url->build('/themes/wunder/'); ?>vendors/jquery.select-bootstrap.js"></script>
<!-- Sweet Alert 2 plugin -->
<script src="<?php echo $this->Url->build('/themes/wunder/'); ?>vendors/sweetalert/sweetalert2.min.js"></script>
<!--	Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
<script src="<?php echo $this->Url->build('/themes/wunder/'); ?>vendors/jasny-bootstrap.min.js"></script>
<!--  Full Calendar Plugin    -->
<script src="<?php echo $this->Url->build('/themes/wunder/'); ?>vendors/fullcalendar.min.js"></script>
<!-- TagsInput Plugin -->
<script src="<?php echo $this->Url->build('/themes/wunder/'); ?>vendors/jquery.tagsinput.js"></script>
<!-- Material Dashboard javascript methods -->
<script src="<?php echo $this->Url->build('/themes/wunder/'); ?>js/wunder.min.js"></script>
<!-- Material Dashboard DEMO methods, don't include it in your project! -->
<!-- <script src="<?php echo $this->Url->build('/themes/wunder/'); ?>js/demo.min.js"></script> -->
<!-- <script src="<?php echo $this->Url->build('/themes/wunder/'); ?>js/charts/flot-charts.min.js"></script> -->
<!-- <script src="<?php echo $this->Url->build('/themes/wunder/'); ?>js/charts/chartjs-charts.min.js"></script> -->
<?php echo $this->fetch('chartScripts'); ?>

</body>
</html>
