<div class="row">
    <div class="col-md-8">
        <div class="post-box">
            <form method="POST" action="##INDEX##user.modify/<?= $form->controls[$form::ID_HIDDEN]->text();?>">
                <?= $form->render($form::ID_HIDDEN);?>
                <div class="form-group">
                    <?= $form->render($form::IDENTIFIANT);?>
                </div>
                <div class="form-group">
                    <?= $form->render($form::MAIL);?>
                </div>
                <div class="form-group">
                    <?= $form->render($form::ACCREDITATION);?>
                </div>
                <div class="form-group">
                    <?= $form->render($form::ACTIF);?>
                </div>

                <?= $form->render($form::BUTTON);?>
            </form>
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