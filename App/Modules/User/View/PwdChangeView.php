
<div class="row justify-content-md-center">
    <div class="col-md-8">
        <div class="widget-sidebar">
            <div class="sidebar-content">
                <form method="POST" action="##INDEX##user.pwdchange">
                    <div class="form-group">
                        <?= $form->RenderSecretOld();?>
                        <?= $form->RenderSecretNew();?>
                        <?= $form->RenderSecretConfirm();?>
                    </div>
                    <?= $form->submit_primary('connexion','Modifier');?>
                </form>
            </div>
        </div>
    </div>
</div>
