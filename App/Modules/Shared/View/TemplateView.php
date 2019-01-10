<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Emmanuel SAUVAGE | <?= $title; ?></title>
        <link href="<?= ES_ROOT_PATH_WEB_VENDOR; ?>bootstrap/bootstrap.min.css" rel="stylesheet">
        <link href="<?= ES_ROOT_PATH_WEB_VENDOR; ?>font-awesome/font-awesome.min.css" rel="stylesheet">
        <link href="<?= ES_ROOT_PATH_WEB_PUBLIC; ?>css/pe-icons.css" rel="stylesheet">
        <link href="<?= ES_ROOT_PATH_WEB_PUBLIC; ?>css/prettyPhoto.css" rel="stylesheet">
        <link href="<?= ES_ROOT_PATH_WEB_PUBLIC; ?>css/animate.css" rel="stylesheet">
        <link href="<?= ES_ROOT_PATH_WEB_PUBLIC; ?>css/style.css" rel="stylesheet">
        <!--[if lt IE 9]>
<script src="js/html5shiv.js"></script>
<script src="js/respond.min.js"></script>
<![endif]-->       
        <script src="<?= ES_ROOT_PATH_WEB_VENDOR; ?>impact/jquery.js"></script>
        <link rel="shortcut icon" href="<?= ES_ROOT_PATH_WEB_PUBLIC; ?>images/ico/favicon.ico">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" 
              href="<?= ES_ROOT_PATH_WEB_PUBLIC; ?>images/ico/apple-touch-icon-144x144.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" 
              href="<?= ES_ROOT_PATH_WEB_PUBLIC; ?>images/ico/apple-touch-icon-114x114.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" 
              href="<?= ES_ROOT_PATH_WEB_PUBLIC; ?>images/ico/images/ico/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon-precomposed" 
              href="<?= ES_ROOT_PATH_WEB_PUBLIC; ?>images/ico/apple-touch-icon-57x57.png">

        <script type="text/javascript">
            jQuery(document).ready(function($){
                'use strict';
                jQuery('body').backstretch([
                    "<?= ES_ROOT_PATH_WEB_PUBLIC; ?>images/bg/bg1.jpg",
                    "<?= ES_ROOT_PATH_WEB_PUBLIC; ?>images/bg/bg2.jpg",
                    "<?= ES_ROOT_PATH_WEB_PUBLIC; ?>images/bg/bg3.jpg",
                    "<?= ES_ROOT_PATH_WEB_PUBLIC; ?>images/bg/bg4.jpg",
                    "<?= ES_ROOT_PATH_WEB_PUBLIC; ?>images/bg/bg5.jpg",
                    "<?= ES_ROOT_PATH_WEB_PUBLIC; ?>images/bg/bg6.jpg",
                    "<?= ES_ROOT_PATH_WEB_PUBLIC; ?>images/bg/bg7.jpg"
                ], {duration: 5000, fade: 500});

                $("#mapwrapper").gMap({ controls: false,
                                       scrollwheel: false,
                                       markers: [{     
                                           latitude:40.7566,
                                           longitude: -73.9863,
                                           icon: { image: "<?= ES_ROOT_PATH_WEB_PUBLIC; ?>images/marker.png",
                                                  iconsize: [44,44],
                                                  iconanchor: [12,46],
                                                  infowindowanchor: [12, 0] } }],
                                       icon: { 
                                           image: "<?= ES_ROOT_PATH_WEB_PUBLIC; ?>images/marker.png", 
                                           iconsize: [26, 46],
                                           iconanchor: [12, 46],
                                           infowindowanchor: [12, 0] },
                                       latitude:40.7566,
                                       longitude: -73.9863,
                                       zoom: 14 });
            });
        </script>
    </head><!--/head-->
<body>
    <div id="preloader"></div>
    <header class="navbar navbar-inverse navbar-fixed-top opaqued" role="banner">
        <div id="search-wrapper">
            <div class="container">
                <input id="search-box" placeholder="Search" />
            </div>
        </div>

        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="<?= ES_ROOT_PATH_WEB_INDEX; ?>">
                    <h1>
                        <span class="pe-7s-gleam bounce-in"></span> <?= ES_APPLICATION_NOM;?>
                    </h1>
                </a>
            </div>
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="<?= ES_ROOT_PATH_WEB_INDEX; ?>">Home</a>
                    </li>
                    <li>
                        <a href="<?= ES_ROOT_PATH_WEB_INDEX; ?>#about">A propos</a>
                    </li>
                </ul>
            </div>
        </div>
    </header><!--/header-->

    <section id="single-page-slider" class="no-margin">
        <div class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <div class="item active">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="center gap fade-down section-heading">
                                    <h2 class="main-title">Emmanuel SAUVAGE</h2>
                                    <hr />
                                    <p>Développeur Full Stack - PHP / Symfony / Dotnet</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!--/.item-->
            </div><!--/.carousel-inner-->
        </div><!--/.carousel-->
    </section><!--/#main-slider-->
    <div id="content-wrapper">
        <?php if(\ES\Core\Toolbox\Alert::isPresent()): ?>
        <section class="white">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <?= \ES\Core\Toolbox\Alert::read() ?>
                    </div>
                </div>
            </div>
        </section>
        <?php endif;?>
        <?= isset($content)?$content:'Erreur, pas de contenu !!!'; ?>
        </div>
        <div id="footer-wrapper">
            <section id="bottom" class="">
                <div class="container">
                    <div class="row">

                    </div>
                </div>
            </section><!--/#bottom-->

            <footer id="footer" class="">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6">
                            &copy; 2019
                            Emmanuel SAUVAGE.
                            Tout droit réservé.
                        </div>
                        <div class="col-sm-6">
                            <ul class="pull-right">
                                <li>
                                    <a id="gototop" class="gototop" href="#">
                                        <i class="fa fa-chevron-up"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer><!--/#footer-->
        </div>

    <script src="<?= ES_ROOT_PATH_WEB_VENDOR; ?>bootstrap/bootstrap.min.js"></script>
    <script src="<?= ES_ROOT_PATH_WEB_VENDOR; ?>impact/jquery.prettyPhoto.js"></script>
    <script src="<?= ES_ROOT_PATH_WEB_VENDOR; ?>impact/plugins.js"></script>
    <script src="<?= ES_ROOT_PATH_WEB_VENDOR; ?>impact/init.js"></script>
</body>
</html>