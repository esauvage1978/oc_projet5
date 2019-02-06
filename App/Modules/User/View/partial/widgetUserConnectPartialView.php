        <div class="widget-sidebar">
            <h5 class="sidebar-title">Vos Informations</h5>
            <div class="sidebar-content">
                <p>
                    <a href="##INDEX##user.modify/<?= $userConnect->getId();?>">
                        <?= $userConnect->getIdentifiant(); ?>
                    </a>
                    [<?= $userConnect->getUserRoleLabel(); ?>]
                </p>
            </div>
        </div>