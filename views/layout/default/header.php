﻿<!DOCTYPE html>

<html lang="es">



<head>

    <meta charset="utf-8">

    <title>PayRoll | <?php if (isset($this->title_)) {
                            echo $this->title_;
                        } ?></title>



    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">



    <link rel="shortcut icon" href="/public/img/favicon.ico">

    <link rel="stylesheet" href="/public/font/iconsmind-s/css/iconsminds.css" />

    <link rel="stylesheet" href="/public/font/simple-line-icons/css/simple-line-icons.css" />



    <link rel="stylesheet" href="/public/css/vendor/bootstrap.min.css" />

    <link rel="stylesheet" href="/public/css/vendor/bootstrap.rtl.only.min.css" />

    <link rel="stylesheet" href="/public/css/vendor/fullcalendar.min.css">

    <link rel="stylesheet" href="/public/css/vendor/dataTables.bootstrap4.min.css" />

    <link rel="stylesheet" href="/public/css/vendor/datatables.responsive.bootstrap4.min.css" />

    <link rel="stylesheet" href="/public/css/vendor/select2.min.css" />

    <link rel="stylesheet" href="/public/css/vendor/select2-bootstrap.min.css" />

    <link rel="stylesheet" href="/public/css/vendor/perfect-scrollbar.css">

    <link rel="stylesheet" href="/public/css/vendor/smart_wizard.min.css">

    <link rel="stylesheet" href="/public/css/vendor/bootstrap-stars.css" />

    <link rel="stylesheet" href="/public/css/vendor/bootstrap-tagsinput.css" />

    <link rel="stylesheet" href="/public/css/vendor/bootstrap-datepicker3.min.css" />

    <link rel="stylesheet" href="/public/css/vendor/component-custom-switch.min.css">

    <link rel="stylesheet" href="/public/css/vendor/bootstrap-float-label.min.css" />

    <link rel="stylesheet" href="/public/css/vendor/cropper.min.css">

    <link rel="stylesheet" href="/public/js/firma/demoButtons.css">

    <link rel="stylesheet" href="/public/css/main.css" />

</head>



<body id="app-container" class="show-spinner menu-sub-hidden">



    <nav class="navbar fixed-top" style="opacity: 1;">



        <div class="d-flex align-items-center navbar-left">



            <a href="#" class="menu-button d-none d-md-block">

                <svg class="main" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 17">

                    <rect hidden x="0.48" y="0.5" width="7" height="1"></rect>

                    <rect hidden x="0.48" y="7.5" width="7" height="1"></rect>

                    <rect hidden x="0.48" y="15.5" width="7" height="1"></rect>

                </svg>

                <svg class="sub" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 17">

                    <rect hidden x="1.56" y="0.5" width="16" height="1"></rect>

                    <rect hidden x="1.56" y="7.5" width="16" height="1"></rect>

                    <rect hidden x="1.56" y="15.5" width="16" height="1"></rect>

                </svg>

            </a>



            <a href="#" class="menu-button-mobile d-xs-block d-sm-block d-md-none">

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 26 17">

                    <rect hidden x="0.5" y="0.5" width="25" height="1"></rect>

                    <rect hidden x="0.5" y="7.5" width="25" height="1"></rect>

                    <rect hidden x="0.5" y="15.5" width="25" height="1"></rect>

                </svg>

            </a>



        </div>



        <a class="navbar-logo" href="/">

            <span class="logo d-none d-sm-block"></span>

            <span class="logo-mobile d-block d-sm-none"></span>

        </a>



        <div class="navbar-right">



            <div class="header-icons d-inline-block align-middle">




                <!-- <div class="position-relative d-none d-sm-inline-block">

                        <button class="header-icon btn btn-empty" type="button" id="iconMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                            <i class="simple-icon-grid"></i>

                        </button>

                        <div class="dropdown-menu dropdown-menu-right mt-3  position-absolute" id="iconMenuDropdown">

                            <a href="#" class="icon-menu-item">

                                <i class="iconsminds-equalizer d-block"></i>

                                <span>Settings</span>

                            </a>



                            <a href="#" class="icon-menu-item">

                                <i class="iconsminds-male-female d-block"></i>

                                <span>Users</span>

                            </a>



                            <a href="#" class="icon-menu-item">

                                <i class="iconsminds-puzzle d-block"></i>

                                <span>Components</span>

                            </a>



                            <a href="#" class="icon-menu-item">

                                <i class="iconsminds-bar-chart-4 d-block"></i>

                                <span>Profits</span>

                            </a>



                            <a href="#" class="icon-menu-item">

                                <i class="iconsminds-file d-block"></i>

                                <span>Surveys</span>

                            </a>



                            <a href="#" class="icon-menu-item">

                                <i class="iconsminds-suitcase d-block"></i>

                                <span>Tasks</span>

                            </a>



                        </div>

                    </div> -->



                <!-- <div class="position-relative d-inline-block">

                        <button class="header-icon btn btn-empty" type="button" id="notificationButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                            <i class="simple-icon-bell"></i>

                            <span class="count">3</span>

                        </button>

                        <div class="dropdown-menu dropdown-menu-right mt-3 position-absolute" id="notificationDropdown">

                            <div class="scroll ps">

                                <div class="d-flex flex-row mb-3 pb-3 border-bottom">

                                    <a href="#">

                                        <img src="img/profile-pic-l-2.jpg" alt="Notification Image" class="img-thumbnail list-thumbnail xsmall border-0 rounded-circle">

                                    </a>

                                    <div class="pl-3">

                                        <a href="#">

                                            <p class="font-weight-medium mb-1">Joisse Kaycee just sent a new comment!</p>

                                            <p class="text-muted mb-0 text-small">09.04.2018 - 12:45</p>

                                        </a>

                                    </div>

                                </div>

                                <div class="d-flex flex-row mb-3 pb-3 border-bottom">

                                    <a href="#">

                                        <img src="img/notification-thumb.jpg" alt="Notification Image" class="img-thumbnail list-thumbnail xsmall border-0 rounded-circle">

                                    </a>

                                    <div class="pl-3">

                                        <a href="#">

                                            <p class="font-weight-medium mb-1">1 item is out of stock!</p>

                                            <p class="text-muted mb-0 text-small">09.04.2018 - 12:45</p>

                                        </a>

                                    </div>

                                </div>

                                <div class="d-flex flex-row mb-3 pb-3 border-bottom">

                                    <a href="#">

                                        <img src="img/notification-thumb-2.jpg" alt="Notification Image" class="img-thumbnail list-thumbnail xsmall border-0 rounded-circle">

                                    </a>

                                    <div class="pl-3">

                                        <a href="#">

                                            <p class="font-weight-medium mb-1">New order received! It is total $147,20.</p>

                                            <p class="text-muted mb-0 text-small">09.04.2018 - 12:45</p>

                                        </a>

                                    </div>

                                </div>

                                <div class="d-flex flex-row mb-3 pb-3 ">

                                    <a href="#">

                                        <img src="img/notification-thumb-3.jpg" alt="Notification Image" class="img-thumbnail list-thumbnail xsmall border-0 rounded-circle">

                                    </a>

                                    <div class="pl-3">

                                        <a href="#">

                                            <p class="font-weight-medium mb-1">3 items just added to wish list by a user!

                                            </p>

                                            <p class="text-muted mb-0 text-small">09.04.2018 - 12:45</p>

                                        </a>

                                    </div>

                                </div>

                            <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></div>

                        </div>

                    </div> -->



                <button class="header-icon btn btn-empty d-none d-sm-inline-block mr-3" type="button" id="fullScreenButton">

                    <i class="simple-icon-size-fullscreen"></i>

                    <i class="simple-icon-size-actual"></i>

                </button>



            </div>



            <div class="user d-inline-block">



                <button class="btn btn-empty p-0" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                    <span class="name"><?php echo Session::get("accountName"); ?></span>

                    <span>

                        

                    </span>

                </button>



                <div class="dropdown-menu dropdown-menu-right mt-3">

                    <!-- <a class="dropdown-item" href="#">Account</a>

                        <a class="dropdown-item" href="#">Features</a>

                        <a class="dropdown-item" href="#">History</a>

                        <a class="dropdown-item" href="#">Support</a> -->

                    <a class="dropdown-item close-session-link" href="#">Logout</a>

                </div>



            </div>



        </div>



    </nav>



    <div class="menu" hidden style="opacity: 1;">



        <div class="main-menu default-transition">

            <div class="scroll ps ps--active-y">



                <ul class="list-unstyled">

 
                    <li>

                        <a href="/cashier" id="main-menu-service">

                            <i class="glyph-icon iconsminds-cash-register-2"></i>
                            <p style="text-align:center;">Cashier</p>

                        </a>

                    </li>


                    <?php
                    if (Session::get("RoleId") == 1) {
                        echo ' <li><a href="/company" id="main-menu-company">

                                <i class="iconsminds-building"></i> Companies

                            </a> </li>';

                        echo ' <li><a href="/user" id="main-menu-company">

                                <i class="simple-icon-people"></i> Platform Users

                            </a> </li>';
                        
                            echo ' <li>

                            <a href="/masterpayment" id="main-menu-service">
    
                                <i class="glyph-icon iconsminds-money-bag"></i>
                                <p style="text-align:center;">Master<br> payments</p>
    
                            </a>
    
                        </li>';

                        echo ' <li>

                        <a href="/employee" id="main-menu-service">

                            <i class="glyph-icon iconsminds-engineering"></i> Payable

                        </a>

                    </li>';

                    echo ' <li>

                    <a href="/payment" id="main-menu-service">

                        <i class="glyph-icon iconsminds-credit-card-3"></i>
                        <p style="text-align:center;">Payment Orders</p>

                    </a>

                </li>';

                    }
                    else if(Session::get("RoleId") == 2)
                    {
                        

                echo ' <li><a href="/user" id="main-menu-company">

                        <i class="simple-icon-people"></i> Platform Users

                    </a> </li>';
                
                    echo ' <li>

                    <a href="/masterpayment" id="main-menu-service">

                        <i class="glyph-icon iconsminds-money-bag"></i>
                        <p style="text-align:center;">Master<br> payments</p>

                    </a>

                </li>';

                echo ' <li>

                <a href="/employee" id="main-menu-service">

                    <i class="glyph-icon iconsminds-engineering"></i> Payable

                </a>

            </li>';

            echo ' <li>

            <a href="/payment" id="main-menu-service">

                <i class="glyph-icon iconsminds-credit-card-3"></i>
                <p style="text-align:center;">Payment Orders</p>

            </a>

        </li>';
                    }
                    ?>

                </ul>



                <div class="ps__rail-x" style="left: 0px; bottom: 0px;">

                    <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>

                </div>



                <div class="ps__rail-y" style="top: 0px; height: 525px; right: 0px;">

                    <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 344px;"></div>

                </div>



            </div>

        </div>



        <div class="sub-menu default-transition">

            <div class="scroll ps">

                <ul class="list-unstyled" data-link="dashboard">

                    <li>

                        <a href="Dashboard.Default.html">

                            <i class="simple-icon-rocket"></i> <span class="d-inline-block">Default</span>

                        </a>

                    </li>

                    <li>

                        <a href="Dashboard.Analytics.html">

                            <i class="simple-icon-pie-chart"></i> <span class="d-inline-block">Analytics</span>

                        </a>

                    </li>

                    <li>

                        <a href="Dashboard.Ecommerce.html">

                            <i class="simple-icon-basket-loaded"></i> <span class="d-inline-block">Ecommerce</span>

                        </a>

                    </li>

                    <li>

                        <a href="Dashboard.Content.html">

                            <i class="simple-icon-doc"></i> <span class="d-inline-block">Content</span>

                        </a>

                    </li>

                </ul>

                <ul class="list-unstyled" data-link="layouts" id="layouts">

                    <li>

                        <a href="#" data-toggle="collapse" data-target="#collapseAuthorization" aria-expanded="true" aria-controls="collapseAuthorization" class="rotate-arrow-icon opacity-50">

                            <i class="simple-icon-arrow-down"></i> <span class="d-inline-block">Authorization</span>

                        </a>

                        <div id="collapseAuthorization" class="collapse show">

                            <ul class="list-unstyled inner-level-menu" style="display: none;">

                                <li>

                                    <a href="Pages.Auth.Login.html">

                                        <i class="simple-icon-user-following"></i> <span class="d-inline-block">Login</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="Pages.Auth.Register.html">

                                        <i class="simple-icon-user-follow"></i> <span class="d-inline-block">Register</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="Pages.Auth.ForgotPassword.html">

                                        <i class="simple-icon-user-unfollow"></i> <span class="d-inline-block">Forgot

                                            Password</span>

                                    </a>

                                </li>

                            </ul>

                        </div>

                    </li>

                    <li>

                        <a href="#" data-toggle="collapse" data-target="#collapseProduct" aria-expanded="true" aria-controls="collapseProduct" class="rotate-arrow-icon opacity-50">

                            <i class="simple-icon-arrow-down"></i> <span class="d-inline-block">Product</span>

                        </a>

                        <div id="collapseProduct" class="collapse show">

                            <ul class="list-unstyled inner-level-menu" style="display: none;">

                                <li>

                                    <a href="Pages.Product.List.html">

                                        <i class="simple-icon-credit-card"></i> <span class="d-inline-block">Data

                                            List</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="Pages.Product.Thumbs.html">

                                        <i class="simple-icon-list"></i> <span class="d-inline-block">Thumb

                                            List</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="Pages.Product.Images.html">

                                        <i class="simple-icon-grid"></i> <span class="d-inline-block">Image

                                            List</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="Pages.Product.Detail.html">

                                        <i class="simple-icon-book-open"></i> <span class="d-inline-block">Detail</span>

                                    </a>

                                </li>

                            </ul>

                        </div>

                    </li>

                    <li>

                        <a href="#" data-toggle="collapse" data-target="#collapseProfile" aria-expanded="true" aria-controls="collapseProfile" class="rotate-arrow-icon opacity-50">

                            <i class="simple-icon-arrow-down"></i> <span class="d-inline-block">Profile</span>

                        </a>

                        <div id="collapseProfile" class="collapse show">

                            <ul class="list-unstyled inner-level-menu" style="display: none;">

                                <li>

                                    <a href="Pages.Profile.Social.html">

                                        <i class="simple-icon-share"></i> <span class="d-inline-block">Social</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="Pages.Profile.Portfolio.html">

                                        <i class="simple-icon-link"></i> <span class="d-inline-block">Portfolio</span>

                                    </a>

                                </li>

                            </ul>

                        </div>

                    </li>

                    <li>

                        <a href="#" data-toggle="collapse" data-target="#collapseBlog" aria-expanded="true" aria-controls="collapseBlog" class="rotate-arrow-icon opacity-50">

                            <i class="simple-icon-arrow-down"></i> <span class="d-inline-block">Blog</span>

                        </a>

                        <div id="collapseBlog" class="collapse show">

                            <ul class="list-unstyled inner-level-menu" style="display: none;">

                                <li>

                                    <a href="Pages.Blog.html">

                                        <i class="simple-icon-list"></i> <span class="d-inline-block">List</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="Pages.Blog.Detail.html">

                                        <i class="simple-icon-book-open"></i> <span class="d-inline-block">Detail</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="Pages.Blog.Detail.Alt.html">

                                        <i class="simple-icon-picture"></i> <span class="d-inline-block">Detail

                                            Alt</span>

                                    </a>

                                </li>

                            </ul>

                        </div>

                    </li>

                    <li>

                        <a href="#" data-toggle="collapse" data-target="#collapseMisc" aria-expanded="true" aria-controls="collapseMisc" class="rotate-arrow-icon opacity-50">

                            <i class="simple-icon-arrow-down"></i> <span class="d-inline-block">Miscellaneous</span>

                        </a>

                        <div id="collapseMisc" class="collapse show">

                            <ul class="list-unstyled inner-level-menu" style="display: none;">

                                <li>

                                    <a href="Pages.Misc.Coming.Soon.html">

                                        <i class="simple-icon-hourglass"></i> <span class="d-inline-block">Coming

                                            Soon</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="Pages.Misc.Error.html">

                                        <i class="simple-icon-exclamation"></i> <span class="d-inline-block">Error</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="Pages.Misc.Faq.html">

                                        <i class="simple-icon-question"></i> <span class="d-inline-block">Faq</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="Pages.Misc.Invoice.html">

                                        <i class="simple-icon-bag"></i> <span class="d-inline-block">Invoice</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="Pages.Misc.Knowledge.Base.html">

                                        <i class="simple-icon-graduation"></i> <span class="d-inline-block">Knowledge

                                            Base</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="Pages.Misc.Mailing.html">

                                        <i class="simple-icon-envelope-open"></i> <span class="d-inline-block">Mailing</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="Pages.Misc.Pricing.html">

                                        <i class="simple-icon-diamond"></i> <span class="d-inline-block">Pricing</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="Pages.Misc.Search.html">

                                        <i class="simple-icon-magnifier"></i> <span class="d-inline-block">Search</span>

                                    </a>

                                </li>



                            </ul>

                        </div>

                    </li>

                </ul>

                <ul class="list-unstyled" data-link="applications">

                    <li>

                        <a href="Apps.MediaLibrary.html">

                            <i class="simple-icon-picture"></i> <span class="d-inline-block">Library</span>

                        </a>

                    </li>

                    <li>

                        <a href="Apps.Todo.List.html">

                            <i class="simple-icon-check"></i> <span class="d-inline-block">Todo</span>

                        </a>

                    </li>

                    <li>

                        <a href="Apps.Survey.List.html">

                            <i class="simple-icon-calculator"></i> <span class="d-inline-block">Survey</span>

                        </a>

                    </li>

                    <li>

                        <a href="Apps.Chat.html">

                            <i class="simple-icon-bubbles"></i> <span class="d-inline-block">Chat</span>

                        </a>

                    </li>

                </ul>

                <ul class="list-unstyled" data-link="ui" style="display: block;">

                    <li>

                        <a href="#" data-toggle="collapse" data-target="#collapseForms" aria-expanded="true" aria-controls="collapseForms" class="rotate-arrow-icon opacity-50">

                            <i class="simple-icon-arrow-down"></i> <span class="d-inline-block">Forms</span>

                        </a>

                        <div id="collapseForms" class="collapse show">

                            <ul class="list-unstyled inner-level-menu" style="display: none;">

                                <li>

                                    <a href="Ui.Forms.Components.html">

                                        <i class="simple-icon-event"></i> <span class="d-inline-block">Components</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="Ui.Forms.Layouts.html">

                                        <i class="simple-icon-doc"></i> <span class="d-inline-block">Layouts</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="Ui.Forms.Validation.html">

                                        <i class="simple-icon-check"></i> <span class="d-inline-block">Validation</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="Ui.Forms.Wizard.html">

                                        <i class="simple-icon-magic-wand"></i> <span class="d-inline-block">Wizard</span>

                                    </a>

                                </li>

                            </ul>

                        </div>

                    </li>

                    <li>

                        <a href="#" data-toggle="collapse" data-target="#collapseDataTables" aria-expanded="true" aria-controls="collapseDataTables" class="rotate-arrow-icon opacity-50">

                            <i class="simple-icon-arrow-down"></i> <span class="d-inline-block">Datatables</span>

                        </a>

                        <div id="collapseDataTables" class="collapse show">

                            <ul class="list-unstyled inner-level-menu" style="display: none;">

                                <li>

                                    <a href="Ui.Datatables.Rows.html">

                                        <i class="simple-icon-screen-desktop"></i> <span class="d-inline-block">Full

                                            Page UI</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="Ui.Datatables.Scroll.html">

                                        <i class="simple-icon-mouse"></i> <span class="d-inline-block">Scrollable</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="Ui.Datatables.Pagination.html">

                                        <i class="simple-icon-notebook"></i> <span class="d-inline-block">Pagination</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="Ui.Datatables.Default.html">

                                        <i class="simple-icon-grid"></i> <span class="d-inline-block">Default</span>

                                    </a>

                                </li>

                            </ul>

                        </div>

                    </li>

                    <li>

                        <a href="#" data-toggle="collapse" data-target="#collapseComponents" aria-expanded="true" aria-controls="collapseComponents" class="rotate-arrow-icon opacity-50">

                            <i class="simple-icon-arrow-down"></i> <span class="d-inline-block">Components</span>

                        </a>

                        <div id="collapseComponents" class="collapse show">

                            <ul class="list-unstyled inner-level-menu" style="display: none;">

                                <li>

                                    <a href="Ui.Components.Alerts.html">

                                        <i class="simple-icon-bell"></i> <span class="d-inline-block">Alerts</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="Ui.Components.Badges.html">

                                        <i class="simple-icon-badge"></i> <span class="d-inline-block">Badges</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="Ui.Components.Buttons.html">

                                        <i class="simple-icon-control-play"></i> <span class="d-inline-block">Buttons</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="Ui.Components.Cards.html">

                                        <i class="simple-icon-layers"></i> <span class="d-inline-block">Cards</span>

                                    </a>

                                </li>



                                <li>

                                    <a href="Ui.Components.Carousel.html">

                                        <i class="simple-icon-picture"></i> <span class="d-inline-block">Carousel</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="Ui.Components.Charts.html">

                                        <i class="simple-icon-chart"></i> <span class="d-inline-block">Charts</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="Ui.Components.Collapse.html">

                                        <i class="simple-icon-arrow-up"></i> <span class="d-inline-block">Collapse</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="Ui.Components.Dropdowns.html">

                                        <i class="simple-icon-arrow-down"></i> <span class="d-inline-block">Dropdowns</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="Ui.Components.Editors.html">

                                        <i class="simple-icon-book-open"></i> <span class="d-inline-block">Editors</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="Ui.Components.Icons.html">

                                        <i class="simple-icon-star"></i> <span class="d-inline-block">Icons</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="Ui.Components.InputGroups.html">

                                        <i class="simple-icon-note"></i> <span class="d-inline-block">Input

                                            Groups</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="Ui.Components.Jumbotron.html">

                                        <i class="simple-icon-screen-desktop"></i> <span class="d-inline-block">Jumbotron</span>

                                    </a>

                                </li>

                                <li class="active">

                                    <a href="Ui.Components.Modal.html">

                                        <i class="simple-icon-docs"></i> <span class="d-inline-block">Modal</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="Ui.Components.Navigation.html">

                                        <i class="simple-icon-cursor"></i> <span class="d-inline-block">Navigation</span>

                                    </a>

                                </li>



                                <li>

                                    <a href="Ui.Components.PopoverandTooltip.html">

                                        <i class="simple-icon-pin"></i> <span class="d-inline-block">Popover &amp;

                                            Tooltip</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="Ui.Components.Sortable.html">

                                        <i class="simple-icon-shuffle"></i> <span class="d-inline-block">Sortable</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="Ui.Components.Tables.html">

                                        <i class="simple-icon-grid"></i> <span class="d-inline-block">Tables</span>

                                    </a>

                                </li>

                            </ul>

                        </div>

                    </li>



                </ul>



                <ul class="list-unstyled" data-link="menu" id="menuTypes">

                    <li>

                        <a href="#" data-toggle="collapse" data-target="#collapseMenuTypes" aria-expanded="true" aria-controls="collapseMenuTypes" class="rotate-arrow-icon">

                            <i class="simple-icon-arrow-down"></i> <span class="d-inline-block">Menu Types</span>

                        </a>

                        <div id="collapseMenuTypes" class="collapse show" data-parent="#menuTypes">

                            <ul class="list-unstyled inner-level-menu" style="display: none;">

                                <li>

                                    <a href="Menu.Default.html">

                                        <i class="simple-icon-control-pause"></i> <span class="d-inline-block">Default</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="Menu.Subhidden.html">

                                        <i class="simple-icon-arrow-left mi-subhidden"></i> <span class="d-inline-block">Subhidden</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="Menu.Hidden.html">

                                        <i class="simple-icon-control-start mi-hidden"></i> <span class="d-inline-block">Hidden</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="Menu.Mainhidden.html">

                                        <i class="simple-icon-control-rewind mi-hidden"></i> <span class="d-inline-block">Mainhidden</span>

                                    </a>

                                </li>

                            </ul>

                        </div>

                    </li>

                    <li>

                        <a href="#" data-toggle="collapse" data-target="#collapseMenuLevel" aria-expanded="true" aria-controls="collapseMenuLevel" class="rotate-arrow-icon collapsed">

                            <i class="simple-icon-arrow-down"></i> <span class="d-inline-block">Menu Levels</span>

                        </a>

                        <div id="collapseMenuLevel" class="collapse" data-parent="#menuTypes">

                            <ul class="list-unstyled inner-level-menu" style="display: none;">

                                <li>

                                    <a href="#">

                                        <i class="simple-icon-layers"></i> <span class="d-inline-block">Sub

                                            Level</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="#" data-toggle="collapse" data-target="#collapseMenuLevel2" aria-expanded="true" aria-controls="collapseMenuLevel2" class="rotate-arrow-icon collapsed">

                                        <i class="simple-icon-arrow-down"></i> <span class="d-inline-block">Another

                                            Level</span>

                                    </a>

                                    <div id="collapseMenuLevel2" class="collapse">

                                        <ul class="list-unstyled inner-level-menu" style="display: none;">

                                            <li>

                                                <a href="#">

                                                    <i class="simple-icon-layers"></i> <span class="d-inline-block">Sub

                                                        Level</span>

                                                </a>

                                            </li>

                                        </ul>

                                    </div>

                                </li>

                            </ul>

                        </div>

                    </li>

                    <li>

                        <a href="#" data-toggle="collapse" data-target="#collapseMenuDetached" aria-expanded="true" aria-controls="collapseMenuDetached" class="rotate-arrow-icon collapsed">

                            <i class="simple-icon-arrow-down"></i> <span class="d-inline-block">Detached</span>

                        </a>

                        <div id="collapseMenuDetached" class="collapse">

                            <ul class="list-unstyled inner-level-menu" style="display: none;">

                                <li>

                                    <a href="#">

                                        <i class="simple-icon-layers"></i> <span class="d-inline-block">Sub

                                            Level</span>

                                    </a>

                                </li>

                            </ul>

                        </div>

                    </li>

                </ul>



                <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
                    <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                </div>
                <div class="ps__rail-y" style="top: 0px; right: 0px;">
                    <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div>
                </div>
            </div>

        </div>



    </div>