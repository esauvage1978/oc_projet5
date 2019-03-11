<div class="row">
    <div class="col-md-8">
        <div class="post-box">
            <?= isset($form)?$form:''; ?>
        </div>
    </div>
    <div class="col-md-4">
        <?php require ES_ROOT_PATH_FAT_MODULES .'User/View/Partial/WidgetUserConnectPartialView.php' ?>
    </div>
</div>