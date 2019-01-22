
<div class="row justify-content-md-center">
    <div class="col-md-8">
        <div class="widget-sidebar">
            <div class="sidebar-content">
                <form method="POST" action="##INDEX##user.pwdchange">
                    <div class="form-group">
                        <?= $form->render($form::SECRET_OLD);?>
                        <?= $form->render($form::SECRET_NEW);?>
                        <?= $form->render($form::SECRET_CONFIRM);?>
                    </div>
                    <?= $form->render($form::BUTTON);?>
                    <a href="##INDEX##user.modify" class="btn btn-secondary">Retour</a>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <?php require ES_ROOT_PATH_FAT_MODULES .'User/View/Partial/WidgetUserConnectPartialView.php' ?>
    </div>
</div>
