
<div class="row">
    <div class="col-sm-12">
        <div class="title-box text-center">
            <h3 class="title-a">
                Blog
            </h3>
            <p class="subtitle-a">
                Liste des articles du sites My Lost Univer.
                <?= isset($filtre)?'<a href="##INDEX##blog.list">Enlever le filtre</a>':''; ?>
            </p>
            <div class="line-mf"></div>
        </div>
    </div>
</div>
<div class="row">
    <?php foreach($form as $data): ?>
    <div class="col-md-4">
        <div class="card card-blog">
            <div class="card-img">
                <a href="##INDEX##blog.show/<?= $data->article->getId(); ?>">
                    <img src="##IMGBLOG##<?= $data->article->getId(); ?>.jpg" alt="" class="img-fluid" />
                </a>
            </div>
            <div class="card-body">
                <div class="card-category-box">
                    <a href="##INDEX##blog.list/category/<?= $data->article->getCategoryRef(); ?>">
                        <div class="card-category">
                            <h6 class="category"><?= $data->category->getTitle(); ?></h6>
                        </div>
                    </a>
                </div>
                <h3 class="card-title">
                    <a href="##INDEX##blog.show/<?= $data->article->getId(); ?>">
                        <?= $data->article->getTitle(); ?>
                    </a>
                </h3>
                <p class="card-description">
                    <?= $data->article->getChapo(); ?>
                </p>
            </div>
            <div class="card-footer">
                <div class="post-author">
                    <a href="##INDEX##blog.list/user/<?= $data->userCreate->getId(); ?>">
                        <img src="##IMGAVATAR##<?= $data->userCreate->getId(); ?>.png" alt="" class="avatar rounded-circle" />
                        <span class="author"><?= $data->userCreate->getIdentifiant() ?></span>
                    </a>
                </div>
                <div class="post-date">
                    <span class="ion-ios-clock-outline"></span> <?= $data->article->getCreateDate(); ?>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
