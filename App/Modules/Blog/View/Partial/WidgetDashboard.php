
<section class="blog-wrapper sect-pt4" id="topsection">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="title-box text-center">
                    <h3 class="title-a">
                        Commentaires
                    </h3>
                    <p class="subtitle-a">
                        Action à réaliser sur les commentaires
                    </p>
                    <div class="line-mf"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="section-counter paralax-mf bg-image" style="background-image: url(img/counters-bg.jpg)">
        <div class="overlay-mf"></div>
        <div class="container">
            <div class="row">
                <div class="col-sm-3 col-lg-3">
                        <div class="counter-box">
                            <div class="counter-ico">
                                <span class="ico-circle">
                                    <i class="ion-chatbubbles"></i>
                                </span>
                            </div>
                            <div class="counter-num">
                                <p class="counter">
                                    <?= isset($numberTotal)?$numberTotal:''; ?>
                                </p>
                                <span class="counter-text">TOTAL</span>
                            </div>
                        </div>
                </div>
                <div class="col-sm-3 col-lg-3">
                    <a href="##INDEX##blog.commentlist">
                        <div class="counter-box pt-4 pt-md-0">
                            <div class="counter-ico">
                                <span class="ico-circle">
                                    <i class="ion-eye"></i>
                                </span>
                            </div>
                            <div class="counter-num">
                                <p class="counter">
                                    <?= isset($numberModerator)?$numberModerator:''; ?>
                                </p>
                                <span class="counter-text">A MODERER</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-3 col-lg-3">

                        <div class="counter-box pt-4 pt-md-0">
                            <div class="counter-ico">
                                <span class="ico-circle">
                                    <i class="ion-thumbsdown"></i>
                                </span>
                            </div>
                            <div class="counter-num">
                                <p class="counter">
                                    <?= isset($numberModerateorKO)?$numberModerateorKO:''; ?>
                                </p>
                                <span class="counter-text">REJETE</span>
                            </div>
                        </div>
                </div>
                <div class="col-sm-3 col-lg-3">

                        <div class="counter-box pt-4 pt-md-0">
                            <div class="counter-ico">
                                <span class="ico-circle">
                                    <i class="ion-thumbsup"></i>
                                </span>
                            </div>
                            <div class="counter-num">
                                <p class="counter">
                                    <?= isset($numberModeratorOK)?$numberModeratorOK:''; ?>
                                </p>
                                <span class="counter-text">VALIDE</span>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</section>