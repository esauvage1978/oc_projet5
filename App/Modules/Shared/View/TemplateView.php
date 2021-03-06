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
    <link href="##DIR_VENDOR##devfolio-master/lib/font-awesome/css/all.min.css" rel="stylesheet" />
    <link href="##DIR_VENDOR##devfolio-master/lib/animate/animate.min.css" rel="stylesheet" />
    <link href="##DIR_VENDOR##devfolio-master/lib/ionicons/css/ionicons.min.css" rel="stylesheet" />
    <link href="##DIR_VENDOR##devfolio-master/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet" />
    <link href="##DIR_VENDOR##devfolio-master/lib/lightbox/css/lightbox.min.css" rel="stylesheet" />
    <link href="##DIR_VENDOR##DataTables/datatables.min.css" rel="stylesheet" type="text/css" />
    <!-- Main Stylesheet File -->
    <link href="##DIR_VENDOR##devfolio-master/css/style.css" rel="stylesheet" />

    <script src="##DIR_VENDOR##devfolio-master/lib/jquery/jquery.min.js"></script>
    <script src="##DIR_VENDOR##DataTables/datatables.min.js"></script>

    <!--CKEDITOR-->
    <script src="##DIR_VENDOR##tinymce/js/tinymce/tinymce.min.js"></script>

    <script src="##DIR_PUBLIC##js/liste.js"></script>


</head><!--/head-->
<body id="page-top">
    <!--/ Nav Star /-->
    <nav class="navbar navbar-b navbar-trans navbar-expand-md fixed-top" id="mainNav">
        <div class="container-fluid">
            <a class="navbar-brand js-scroll" href="##INDEX##">
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
                    <?= isset($menuUser)?$menuUser:'';?>
                </ul>
                <form class="form-inline" action="##INDEX##blog/article/find" method="post">
                    <input class="form-control  form-control-sm" type="search" name="recherche" placeholder="Recherche" aria-label="Recherche" />
                    <span class="input-group-btn">
                        <button class="btn btn-secondary btn-sm" type="submit">
                            <span class="fa fa-search  fa-2"></span>
                        </button>
                    </span>
                </form>
            </div>
        </div>
    </nav>
    <!--/ Nav End /-->

    <!--/ Intro Skew Star /-->
    <?php if(!$home):?>
    <div class="intro intro-single  bg-image" style="background-image: url(##DIR_VENDOR##devfolio-master/img/bg.jpg)">

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
    <?php endif ?>
    <?php if($home):?>
    <!--/ Intro Skew End /-->
    <div id="home" class="intro route bg-image" style="background-image: url(##DIR_VENDOR##devfolio-master/img/bg.jpg)">
        <div class="overlay-itro"></div>
        <div class="intro-content display-table">
            <div class="table-cell">
                <div class="container">
                    <!--<p class="display-6 color-d">Hello, world!</p>-->
                    <h1 class="intro-title mb-4">Je suis Emmanuel SAUVAGE</h1>
                    <p class="intro-subtitle">
                        <span class="text-slider-items">Développeur Full Stack,Dotnet,PHP / Symfony</span>
                        <strong class="text-slider"></strong>
                    </p>
                    <div id="topContent"></div>
                    <!-- <p class="pt-3"><a class="btn btn-primary btn js-scroll px-4" href="#about" role="button">Learn More</a></p> -->
                </div>
            </div>

        </div>
    </div>
    <?php endif ?>

    <div class="container">
        <div class="row">
            <?php require ES_ROOT_PATH_FAT_MODULES .'Shared/View/Partial/FlashPartialView.php';?>
        </div>
    </div>
    <?= $content??'Erreur, pas de contenu !!!'; ?>




    <!--/ Section Contact-Footer Star /-->
    <section id="contact" class="paralax-mf footer-paralax bg-image sect-mt4 route" style="background-image: url(##DIR_VENDOR##devfolio-master/img/bg.jpg)">
        <div class="overlay-mf"></div>
        <?php if($home):?>
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="contact-mf">
                        <div class="box-shadow-full">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="title-box-2">
                                        <h5 class="title-left">
                                            Contactez-moi
                                        </h5>
                                    </div>
                                    <div>
                                        <?php if(isset($mailSend)) :?>
                                        <div class="alert alert-info" role="alert">
                                            <?= $mailSend; ?>
                                        </div>
                                        <?php endif ?>
                                        <?= isset($form)?$form:''; ?>
                                        <script src="https://www.google.com/recaptcha/api.js?render=<?= ES_RECAPTCHA_SECRET_FRONT;?>"></script>
                                        <script>
                                            grecaptcha.ready(function () {
                                                grecaptcha.execute('<?= ES_RECAPTCHA_SECRET_FRONT;?>', { action: 'contact' }).then(function (token) {
                                                    var recaptchaResponse = document.getElementById('<?= isset($form)?$form[$form::RECAPTCHA]->getName():''; ?>');
                                                    recaptchaResponse.value = token;
                                                });
                                            });
                                        </script>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="title-box-2 pt-4 pt-md-0">
                                        <h5 class="title-left">
                                            Prenez contact
                                        </h5>
                                    </div>
                                    <div class="more-info">
                                        <p class="lead">
                                            Vous avez des questions sur mon parcours, ma formation, sur ce site...
                                            <br />N'hésitez pas à me contacter, je vous répondrai au plus vite.
                                            <br />
                                            <br />
                                        </p>
                                        <ul class="list-ico">
                                            <li>
                                                <span class="ion-ios-location"></span> Erquinghem-lys - Nord - France
                                            </li>
                                            <li>
                                                <span class="ion-ios-telephone"></span> 06.09.92.49.45
                                            </li>
                                            <li>
                                                <span class="ion-email"></span>
                                                <a href="mailto:contact@mylostuniver.com">contact@mylostuniver.com</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="socials">
                                        <ul>
                                            <li>
                                                <a href="##DATAS##cv/cv.pdf" target="_blank"
                                                    title="Téléchargez mon CV détaillé au format PDF">
                                                    <span class=" ico-circle">
                                                        <i class="fas fa-address-card"></i>
                                                    </span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="https://www.linkedin.com/in/emmanuelsauvage1978/5" target="_blank"
                                                    title="Rendez-vous sur mon profil Linkedin">
                                                    <span class=" ico-circle">
                                                        <i class="fab fa-linkedin-in"></i>
                                                    </span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="https://github.com/esauvage1978" target="_blank"
                                                    title="Retrouvez mes projets sur GITHUB">
                                                    <span class=" ico-circle">
                                                        <i class="fab fa-github"></i>
                                                    </span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="https://twitter.com/esauvage1978" target="_blank"
                                                    title="Retrouvez-moi sur twitter">
                                                    <span class="ico-circle">
                                                        <i class="fab fa-twitter"></i>
                                                    </span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif ?>
        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-lg-3">
                        <ul class="nav flex-column" style="color:white; border-radius: 10px;background-color:rgba(255, 255, 255, 0.7); ">
                            <?= isset($menuFooter)?$menuFooter:'';?>
                        </ul>
                    </div>
                    <div class="col-md-6 col-lg-6">
                        <div class="copyright-box">
                            <p class="copyright">
                                &copy; Copyright
                                <strong>Emmanuel SAUVAGE</strong>. Tous droit réservé.
                            </p>
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