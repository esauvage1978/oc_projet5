<div id="usuTop" class="col-sm-4 col-sm-push-4">
    <h2 class="center">Inscrivez-vous</h2>
    <hr />
    <form method="POST" action="##INDEX##user.signup">
        <div class="form-group">
            <?= $form->identifiant();?>
        </div>
        <div class="form-group">
            <?= $form->mail();?>
        </div>
        <div class="form-group">
            <?= $form->password(); ?>
        </div>
        <div class="form-group">
            <?= $form->passwordConfirmation(); ?>
        </div>
        <?= $form->submit_primary('connexion','S\'inscrire');?>
    </form>
    <hr />
</div>