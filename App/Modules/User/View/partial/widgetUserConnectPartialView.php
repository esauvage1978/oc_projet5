        <div class="widget-sidebar">
            <h5 class="sidebar-title">Vos Informations</h5>
            <div class="sidebar-content">
                <p>
                    <a href="<?= $userConnect->urlModify();?>">
                        <?= $userConnect->getIdentifiant(); ?>
                    </a>
                    [<?= $userConnect->getUserRoleLabel(); ?>]
                </p>
            </div>
        </div>