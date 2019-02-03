
<div class="row justify-content-md-center">
    <div class="col-md-8">
        <div class="widget-sidebar">
            <div class="sidebar-content">
                <?= isset($form)?$form:''; ?>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <?php require ES_ROOT_PATH_FAT_MODULES .'User/View/Partial/WidgetUserConnectPartialView.php' ?>
    </div>
</div>
