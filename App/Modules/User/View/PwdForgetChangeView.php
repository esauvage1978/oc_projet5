
<div class="col-sm-4 col-sm-push-4">
    <h2 class="center">Modification du mot de passe</h2>
    <hr />
    <form method="POST" action="##INDEX##user.pwdforgetchange">
        <div class="form-group">
            <?= $form->hash();?>
            <?= $form->password();?>
            <?= $form->passwordConfirmation();?>
        </div>
        <?= $form->submit_primary('connexion','Modifier');?>
    </form>
</div>