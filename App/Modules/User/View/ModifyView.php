<div id="usuTop" class="col-sm-6 col-sm-push-3">
    <h2 class="center">Modification des informations</h2>
    <hr />
    <form method="POST" action="##INDEX##user.modify">
        <div class="form-group">
            <?= $form->idHidden();?>
        </div>

        <div class="form-group">
            <?= $form->identifiant();?>
        </div>
        <div class="form-group">
            <?= $form->mail();?>
        </div>
        <?= $form->submit_primary('modify','Modifier');?>
    </form>
    <hr />
    <div>
        
        <a href="##INDEX##user.pwdchange">Changer mon mot de passe ?</a>
    </div>
</div>