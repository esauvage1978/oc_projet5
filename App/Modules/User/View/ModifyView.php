<div class="row">
    <div class="col-md-8">
        <div class="post-box">
            <?= isset($form)?$form:''; ?>
            <hr />
            <div>
                <a href="##INDEX##user.pwdchange">Changer mon mot de passe ?</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <?php require ES_ROOT_PATH_FAT_MODULES .'User/View/Partial/WidgetUserConnectPartialView.php' ?>
    </div>
</div>