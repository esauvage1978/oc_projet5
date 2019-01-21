
<div class="row justify-content-md-center">
    <div class="col-md-6">
        <div class="widget-sidebar">
            <div class="sidebar-content">

                <form method="POST" action="##INDEX##user.connexion">
                    <div class="form-group">
                        <?= $form->render($form::LOGIN);?>
                    </div>
                    <div class="form-group">
                        <?= $form->render($form::SECRET);?>
                    </div>
                    <div class="btn-toolbar justify-content-between align-items-center" role="toolbar" aria-label="Toolbar with button groups">
                        <?= $form->render($form::BUTTON);?>
                        <a href="##INDEX##user.pwdforget"> Mot de passe oublié ?</a>
                    </div>
                </form>
                <hr />
                <div>
                    
                    Vous n'avez pas de compte ?
                    <a href="##INDEX##user.signup">Inscrivez-vous</a>
                </div>
            </div>
        </div>
    </div>
</div>