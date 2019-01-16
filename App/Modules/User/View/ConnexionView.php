
<div class="col-sm-4 col-sm-push-4">
    <h2 class="center">Connexion</h2>
    <hr/>
    <form method="POST" action="##INDEX##?page=user.connexion">
        <div class="form-group">
            <?= $form->login();?>
        </div>
        <div class="form-group">
            <?= $form->password(); ?>
        </div>
        <?= $form->submit_primary('connexion','Connexion');?>
        <a href="##INDEX##?page=user.pwdforget"> Mot de passe oublié ?</a>

    </form>
    <hr />
    <div>
        Vous n'avez pas de compte ?
        <a href="##INDEX##?page=user.signup">Inscrivez-vous</a>
    </div>
</div>