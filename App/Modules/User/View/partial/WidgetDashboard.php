
<section class="blog-wrapper sect-pt4" id="topsection">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="title-box text-center">
                    <h3 class="title-a">
                        Utilisateur
                    </h3>
                    <p class="subtitle-a">
                        Vision globale des utilisateurs du site : Nombre total d'utilisateur
                    </p>
                    <div class="line-mf"></div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Section Services End /-->

    <div class="section-counter paralax-mf bg-image" style="background-image: url(img/counters-bg.jpg)">
        <div class="overlay-mf"></div>
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-3">
                    <a href="##INDEX##user.list">
                        <div class="counter-box">
                            <div class="counter-ico">
                                <span class="ico-circle">
                                    <i class="ion-checkmark-round"></i>
                                </span>
                            </div>
                            <div class="counter-num">
                                <p class="counter"><?= $numberTotal; ?></p>
                                <span class="counter-text">TOTAL</span>
                                <p>
                                    <small>Nombre total d'inscrit</small>
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-3">
                    <a href="##INDEX##user.list/validaccount/0">
                        <div class="counter-box pt-4 pt-md-0">
                            <div class="counter-ico">
                                <span class="ico-circle">
                                    <i class="ion-ios-calendar-outline"></i>
                                </span>
                            </div>
                            <div class="counter-num">
                                <p class="counter"><?= $numberNotActive; ?></p>
                                <span class="counter-text">NON ACTIVE</span>
                                <p>
                                    <small>L'utilisateur n'a pas activé le compte</small>
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-3">
                    <a href="##INDEX##user.list/actif/0">
                        <div class="counter-box pt-4 pt-md-0">
                            <div class="counter-ico">
                                <span class="ico-circle">
                                    <i class="ion-ios-people"></i>
                                </span>
                            </div>
                            <div class="counter-num">
                                <p class="counter"><?= $numberSuspendu; ?></p>
                                <span class="counter-text">SUSPENDU</span>
                                <p>
                                    <small>Utilisateur suspendu par un gestionnaire</small>
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-3">
                    <a href="##INDEX##user.list/accreditation/<?= ES_GESTIONNAIRE; ?>">
                        <div class="counter-box pt-4 pt-md-0">
                            <div class="counter-ico">
                                <span class="ico-circle">
                                    <i class="ion-ribbon-a"></i>
                                </span>
                            </div>
                            <div class="counter-num">
                                <p class="counter"><?= $numberGestionnaire; ?></p>
                                <span class="counter-text"><?= ES_ACCREDITATION[ES_GESTIONNAIRE]; ?></span>
                                <p>
                                    <small>Gestionnaire du site</small>
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>