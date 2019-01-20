<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Site de présentation" />
    <meta name="author" content="Emmanuel SAUVAGE" />
    <title>Emmanuel SAUVAGE | ##TITLE##</title>

    <!-- Favicons -->
    <link href="##DIR_VENDOR##devfolio-master/img/favicon.png" rel="icon" />
    <link href="##DIR_VENDOR##devfolio-master/img/apple-touch-icon.png" rel="apple-touch-icon" />

    <!-- Bootstrap CSS File -->
    <link href="##DIR_VENDOR##devfolio-master/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Libraries CSS Files -->
    <link href="##DIR_VENDOR##devfolio-master/lib/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link href="##DIR_VENDOR##devfolio-master/lib/animate/animate.min.css" rel="stylesheet" />
    <link href="##DIR_VENDOR##devfolio-master/lib/ionicons/css/ionicons.min.css" rel="stylesheet" />
    <link href="##DIR_VENDOR##devfolio-master/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet" />
    <link href="##DIR_VENDOR##devfolio-master/lib/lightbox/css/lightbox.min.css" rel="stylesheet" />

    <!-- Main Stylesheet File -->
    <link href="##DIR_VENDOR##devfolio-master/css/style.css" rel="stylesheet" />

</head><!--/head-->
<body id="page-top">
    <!--/ Nav Star /-->
    <nav class="navbar navbar-b navbar-trans navbar-expand-md fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand js-scroll" href="#page-top">
                <?= ES_APPLICATION_NOM;?>
            </a>
            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarDefault"
                aria-controls="navbarDefault" aria-expanded="false" aria-label="Toggle navigation">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <div class="navbar-collapse collapse justify-content-end" id="navbarDefault">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link js-scroll" href="##INDEX###about">Home</a>
                    </li>
                    <?= isset($menuUser)?$menuUser:'';?>
                </ul>
            </div>
        </div>
    </nav>
    <!--/ Nav End /-->

    <!--/ Intro Skew Star /-->
    <div class="intro intro-single route bg-image" style="background-image: url(##DIR_VENDOR##devfolio-master/img/bg1.jpg)">
        <div class="overlay-mf"></div>
        <div class="intro-content display-table">
            <div class="table-cell">
                <div class="container">
                    <h2 class="intro-title mb-4">
                        <?= isset($title)?$title:''; ?>
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <!--/ Intro Skew End /-->

    <section id="service" class="services-mf route">
        <div class="container">
            <div class="row">
                <?php require ES_ROOT_PATH_FAT_MODULES .'Shared\\View\\Partial\\FlashPartialView.php';?>
            </div>
            <?= $content??'Erreur, pas de contenu !!!'; ?>

        </div>
    </section>



    <!--/ Section Contact-Footer Star /-->
    <section class="paralax-mf footer-paralax bg-image sect-mt4 route" style="background-image: url(##DIR_VENDOR##devfolio-master/img/bg1.jpg)">
        <div class="overlay-mf"></div>
        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="copyright-box">
                            <p class="copyright">
                                &copy; Copyright
                                <strong>DevFolio</strong>. All Rights Reserved
                            </p>
                            <div class="credits">
                                <!--
                  All the links in the footer should remain intact.
                  You can delete the links only if you purchased the pro version.
                  Licensing information: https://bootstrapmade.com/license/
                  Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/buy/?theme=DevFolio
                -->
                                Designed by
                                <a href="https://bootstrapmade.com/">BootstrapMade</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </section>
    <!--/ Section Contact-footer End /-->

    <a href="#" class="back-to-top">
        <i class="fa fa-chevron-up"></i>
    </a>
    <div id="preloader"></div>



    <!-- JavaScript Libraries -->
    <script src="##DIR_VENDOR##devfolio-master/lib/jquery/jquery.min.js"></script>
    <script src="##DIR_VENDOR##devfolio-master/lib/jquery/jquery-migrate.min.js"></script>
    <script src="##DIR_VENDOR##devfolio-master/lib/popper/popper.min.js"></script>
    <script src="##DIR_VENDOR##devfolio-master/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="##DIR_VENDOR##devfolio-master/lib/easing/easing.min.js"></script>
    <script src="##DIR_VENDOR##devfolio-master/lib/counterup/jquery.waypoints.min.js"></script>
    <script src="##DIR_VENDOR##devfolio-master/lib/counterup/jquery.counterup.js"></script>
    <script src="##DIR_VENDOR##devfolio-master/lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="##DIR_VENDOR##devfolio-master/lib/lightbox/js/lightbox.min.js"></script>
    <script src="##DIR_VENDOR##devfolio-master/lib/typed/typed.min.js"></script>


    <!-- Template Main Javascript File -->
    <script src="##DIR_VENDOR##devfolio-master/js/main.js"></script>
</body>
</html>