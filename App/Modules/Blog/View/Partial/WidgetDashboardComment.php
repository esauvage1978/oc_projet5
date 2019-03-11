<div class="col-lg-4 col-md-6 col-sm-12">
    <div class="widget-sidebar  box-shadow-full">
        <h5 class="sidebar-title">Commentaires</h5>
        <div class="sidebar-content">
            <div class="list-group">
                <?php foreach($commentaires as $commentaire):?>
                <a href="##INDEX##<?= $commentaire[ES_DASHBOARD_LINK]; ?>"
                    class="list-group-item list-group-item-action 
                   <?= array_key_exists(ES_DASHBOARD_COLOR,$commentaire)?$commentaire[ES_DASHBOARD_COLOR]:''; ?>
                   ">


                    <div class="d-flex w-100 justify-content-between  align-items-end">
                        <div>
                            <h5 class="mb-1  align-text-middle">
                                <i class="<?= $commentaire[ES_DASHBOARD_ICONE]; ?> "></i>
                                <?= $commentaire[ES_DASHBOARD_TITLE]; ?>
                            </h5>
                            <small>
                                <?= $commentaire[ES_DASHBOARD_CONTENT]; ?>
                            </small>
                        </div>
                        <div class="counter-num">
                            <p class="counter">
                                <?= $commentaire[ES_DASHBOARD_NUMBER]; ?>
                            </p>
                        </div>

                    </div>
                </a>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</div>

