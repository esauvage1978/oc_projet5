
<script>

</script>

<div id="user.list" class="row">
    <div class="col-md-8">
        <div class="post-box">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-sm" style="width:100%" id="userlist">
                    <thead>
                        <tr>
                            <th>Identifiant</th>
                            <th>Accréditation</th>
                            <th>Compte validé</th>
                            <th>Compte actif</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($form as $data): ?>
                        <tr>
                            <td>
                                <small>
                                    <a href="##INDEX##user.modify/<?= $data->getId();?>">
                                        <?= $data->getIdentifiant(); ?>
                                    </a>
                                </small>
                            </td>
                            <td>
                                <small>
                                    <?= $data->getAccreditationLabel(); ?>
                                </small>
                            </td>
                            <td>
                                <small>
                                    <?= $data->getValidAccountDate(); ?>
                                </small>
                            </td>
                            <td>
                                <small>
                                    <?= $data->getActifLabel(); ?>
                                </small>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <?php require ES_ROOT_PATH_FAT_MODULES .'User/View/Partial/WidgetUserConnectPartialView.php' ?>

        <div class="widget-sidebar widget-tags">
            <h5 class="sidebar-title">Habilitation</h5>
            <div class="sidebar-content">
                <ul>
                    <li>
                        <a href="##INDEX##user.list#topsection">
                            Tous
                        </a>
                    </li>
                    <li>
                        <a href="##INDEX##user.list/accreditation/<?= ES_VISITEUR; ?>#topsection">
                            <?= ES_ACCREDITATION[ES_VISITEUR];?>
                        </a>
                    </li>
                    <li>
                        <a href="##INDEX##user.list/accreditation/<?= ES_REDACTEUR; ?>#topsection">
                            <?= ES_ACCREDITATION[ES_REDACTEUR];?>
                        </a>
                    </li>
                    <li>
                        <a href="##INDEX##user.list/accreditation/<?= ES_MODERATEUR; ?>#topsection">
                            <?= ES_ACCREDITATION[ES_MODERATEUR];?>
                        </a>
                    </li>
                    <li>
                        <a href="##INDEX##user.list/accreditation/<?= ES_GESTIONNAIRE; ?>#topsection">
                            <?= ES_ACCREDITATION[ES_GESTIONNAIRE];?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="widget-sidebar widget-tags">
            <h5 class="sidebar-title">Compte actif</h5>
            <div class="sidebar-content">
                <ul>
                    <li>
                        <a href="##INDEX##user.list#topsection">
                            Tous
                        </a>
                    </li>
                    <li>
                        <a href="##INDEX##user.list/actif/1#topsection">
                            Oui
                        </a>
                    </li>
                    <li>
                        <a href="##INDEX##user.list/actif/0#topsection">
                            Non
                        </a>
                    </li>
                </ul>
            </div>
        </div>





    </div>
</div>