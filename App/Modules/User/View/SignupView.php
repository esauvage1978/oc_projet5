
<div class="row justify-content-md-center">
    <div class="col-md-8">
        <div class="widget-sidebar">
            <div class="sidebar-content">
                <form method="POST" action="##INDEX##user.signup">
                    <div class="form-group">
                        <?= $form->RenderIdentifiant();?>
                    </div>
                    <div class="form-group">
                        <?= $form->RenderMail();?>
                    </div>
                    <div class="form-group">
                        <?= $form->RenderSecretNew(); ?>
                    </div>
                    <div class="form-group">
                        <?= $form->RenderSecretConfirm(); ?>
                    </div>
                    <?= $form->submit_primary('connexion','S\'inscrire');?>
                </form>
            </div>
        </div>
    </div>
</div>
