
<div class="row justify-content-md-center">
    <div class="col-md-6">
        <div class="widget-sidebar">
            <div class="sidebar-content">

                <form method="POST" action="##INDEX##user.pwdforget">
                    <div class="form-group">
                        <?= $form->RenderLogin();?>
                    </div>
                    <?= $form->submit_primary('connexion','Récupérer');?>
                </form>

            </div>
        </div>
    </div>
</div>
