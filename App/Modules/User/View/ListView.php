
<div class="row">
    <div class="col-md-8">
        <div class="post-box">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-sm">
                    <thead>
                        <tr>
                            <th>Identifiant</th>
                            <th>Accréditation</th>
                            <th>Compte validé</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($form as $data): ?>
                        <tr>
                            <td>
                                <a href="##INDEX##user.modify/<?= $data->getId();?>">
                                    <?= $data->getIdentifiant(); ?>
                                </a>
                            </td>
                            <td>
                                <?= $data->getAccreditationLabel(); ?>
                            </td>
                            <td>
                                <?= $data->getValidAccountDate(); ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <?php require ES_ROOT_PATH_FAT_MODULES .'User\\View\\Partial\\WidgetUserConnectPartialView.php' ?>
    </div>
</div>