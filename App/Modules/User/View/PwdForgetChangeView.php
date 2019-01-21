
<div class="row justify-content-md-center">
    <div class="col-md-8">
        <div class="widget-sidebar">
            <div class="sidebar-content">

                <form method="POST" action="##INDEX##user.pwdforgetchange">
                    <div class="form-group">
                        <?= $form->render($form::HASH);?>
                        <?= $form->render($form::SECRET_NEW);?>
                        <?= $form->render($form::SECRET_CONFIRM);?>
                    </div>
                    <?= $form->render($form::BUTTON);?>
                </form>
            </div>
        </div>
    </div>
</div>
