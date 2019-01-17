
<div class="col-sm-4 col-sm-push-4">
    <h2 class="center">Mot de passe oublié ?</h2>
    <hr />
    <form method="POST" action="##INDEX##user.pwdforget">
        <div class="form-group">
            <?= $form->login();?>
        </div>
        <?= $form->submit_primary('connexion','Récupérer');?>
    </form>

</div>