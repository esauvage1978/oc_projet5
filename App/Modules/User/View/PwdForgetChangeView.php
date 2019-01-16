
<div class="col-sm-4 col-sm-push-4">
    <h2 class="center">Modification du mot de passe</h2>
    <hr />
    <form method="POST" action="##INDEX##?page=user.pwdforgetchange">
        <div class="form-group">
            <?= $form->passwordForget();?>
            <?= $form->password();?>
            <?= $form->passwordConfirmation();?>
        </div>
        <?= $form->submit_primary('connexion','Modifier');?>
    </form>
    <hr />
    <div>
        Vous n'avez pas de compte ?
        <a href="##INDEX##?page=user.signup">Inscrivez-vous</a>
    </div>
</div>