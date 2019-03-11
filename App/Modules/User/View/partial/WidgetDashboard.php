
<div class="col-lg-4 col-md-6 col-sm-12">
    <div class="widget-sidebar  box-shadow-full">
        <h5 class="sidebar-title">Utilisateurs</h5>
        <div class="sidebar-content">
            <div class="list-group">
                <?php foreach($users as $user):?>
                <a href="##INDEX##<?= $user[ES_DASHBOARD_LINK]; ?>"
                    class="list-group-item list-group-item-action 
                   <?= array_key_exists(ES_DASHBOARD_COLOR,$user)?$user[ES_DASHBOARD_COLOR]:''; ?>
                   ">


                    <div class="d-flex w-100 justify-content-between  align-items-end">
                        <div>
                            <h5 class="mb-1  align-text-middle">
                                <i class="<?= $user[ES_DASHBOARD_ICONE]; ?> "></i>
                                <?= $user[ES_DASHBOARD_TITLE]; ?>
                            </h5>
                            <small>
                                <?= $user[ES_DASHBOARD_CONTENT]; ?>
                            </small>
                        </div>
                        <div class="counter-num">
                            <p class="counter">
                                <?= $user[ES_DASHBOARD_NUMBER]; ?>
                            </p>
                        </div>

                    </div>
                </a>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</div>

