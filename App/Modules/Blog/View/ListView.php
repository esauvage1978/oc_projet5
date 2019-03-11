
<div class="row">
    <div class="col-sm-12">
        <div class="title-box text-center">
            <h3 class="title-a">
                Les articles
            </h3>
            <p class="subtitle-a">
                <?= isset($filtre)?'<a href="##INDEX##blog/article/list">Afficher la liste complète</a>':''; ?>
            </p>
            <div class="line-mf"></div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-lg-9">
        <div class="row">
            <?php foreach($list as $data): ?>
            <div class=" col-md-12 col-lg-4">
                <div class="card card-blog">
                    <div class="card-img">
                        <a href="<?= $data->article->urlRead(); ?>">
                            <img src="##IMGBLOG##<?= $data->article->getId(); ?>.jpg" alt="" class="img-fluid" />
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="card-category-box">
                            <a href="<?= $data->article->urlReadCategory(); ?>">
                                <div class="card-category">
                                    <h6 class="category">
                                        <?= $data->category->getTitle(); ?>

                                    </h6>
                                </div>
                            </a>
                        </div>
                        <h3 class="card-title">


                            <?= (isset($redacteur)?
                                '<a href="' .$data->article->urlModify() . '">
                                <i class="fa fa-edit"></i></a> ':''); ?>
                            <a href="<?= $data->article->urlRead(); ?>">
                                <?= $data->article->getTitle(); ?>
                            </a>
                        </h3>
                        <p class="card-description">
                            <?= $data->article->getChapo(); ?>
                        </p>
                    </div>
                    <div class="card-footer">
                        <div class="post-author">
                            <a href="##INDEX##blog/article/list/user/<?= $data->createUser->getId(); ?>">
                                <img src="##IMGAVATAR##<?= $data->createUser->getId(); ?>.png" alt="" class="avatar rounded-circle" />
                                <span class="author">
                                    <?= $data->createUser->getIdentifiant() ?>
                                </span>
                            </a>
                        </div>
                        <div class="post-date">
                            <span class="ion-ios-clock-outline"></span><?= is_null($data->article->getModifyDate()) ?
                                                                       \date(ES_DATE_FR,strtotime ( $data->article->getCreateDate()))
                                                                       :\date(ES_DATE_FR,strtotime ( $data->article->getModifyDate())); ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if(isset($message)): ?>
            <div class="col-lg-12">
                <div class="jumbotron">
                    <?= $message; ?>
                </div>
            </div>
            <?php endif?>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="widget-sidebar sidebar-search">
            <h5 class="sidebar-title">Recherche</h5>
            <div class="sidebar-content">
                <form action="##INDEX##blog/article/find" method="post">
                    <div class="input-group">
                        <input type="text" name="recherche" class="form-control" placeholder="Recherche..." aria-label="recherche..." />
                        <span class="input-group-btn">
                            <button class="btn btn-secondary btn-search" type="submit">
                                <span class="ion-android-search"></span>
                            </button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
        <?php require ES_ROOT_PATH_FAT_MODULES .'Blog/View/Partial/WidgetRecentPost.php' ?>
        <?php require ES_ROOT_PATH_FAT_MODULES .'Blog/View/Partial/WidgetCategoryNotEmpty.php' ?>

    </div>

</div>
