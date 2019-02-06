
<div class="col-lg-4 col-md-6 col-sm-12">
    <div class="widget-sidebar  box-shadow-full">
        <h5 class="sidebar-title">Utilisateurs</h5>
        <div class="sidebar-content">
            <div class="list-group">
                <?php foreach($users as $user):?>
                <a href="##INDEX##<?= $user['link']; ?>"
                    class="list-group-item list-group-item-action 
                   <?= array_key_exists('color',$user)?$user['color']:''; ?>
                   ">


                    <div class="d-flex w-100 justify-content-between  align-items-end">
                        <div>
                            <h5 class="mb-1  align-text-middle">
                                <i class="<?= $user['icone']; ?> "></i>
                                <?= $user['title']; ?>
                            </h5>
                            <small>
                                <?= $user['content']; ?>
                            </small>
                        </div>
                        <div class="counter-num">
                            <p class="counter">
                                <?= $user['number']; ?>
                            </p>
                        </div>

                    </div>
                </a>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</div>
