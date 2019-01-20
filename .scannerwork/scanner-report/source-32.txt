
<div class="row justify-content-md-center">
    <div class="col-md-8">
        <div class="widget-sidebar">
            <div class="sidebar-content">

                <form method="POST" action="##INDEX##user.pwdforgetchange">
                    <div class="form-group">
                        <?= $form->RenderHash();?>
                        <?= $form->RenderSecretNew();?>
                        <?= $form->RenderSecretConfirm();?>
                    </div>
                    <?= $form->submit_primary('connexion','Modifier');?>
                </form>
            </div>
        </div>
    </div>
</div>
