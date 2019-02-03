
      <div class="row">
        <div class="col-md-8">
          <div class="post-box">
            <div class="post-thumb">
                <img src="##IMGBLOG##/<?= $articleFactory->article->getId(); ?>.jpg" class="img-fluid" alt="" />
            </div>
            <div class="post-meta">
              <h1 class="article-title"><?= $articleFactory->article->getTitle(); ?></h1>
              <ul>
                <li>
                  <span class="ion-ios-person"></span>
                    <a href="##INDEX##blog.list/user/<?= $articleFactory->userCreate->getId(); ?>">
                        <?= $articleFactory->userCreate->getIdentifiant(); ?>
                    </a>
                </li>
                <li>
                  <span class="ion-pricetag"></span>
                    <a href="##INDEX##blog.list/category/<?= $articleFactory->category->getId(); ?>">
                        <?= $articleFactory->category->getTitle(); ?>
                    </a>
                </li>
                <li>
                  <span class="ion-chatbox"></span>
                    <a href="#comments">
                        <?= $articleFactory->commentNbr; ?>
                    </a>
                </li>
              </ul>
            </div>
            <div class="article-content">
            <blockquote class="blockquote">
                <p class="mb-0"><?= $articleFactory->article->getChapo(); ?></p>
            </blockquote>

                <?= $articleFactory->article->getContent(); ?>
            </div>
          </div>
          <div class="box-comments" id="comments">
            <div class="title-box-2">
              <h4 class="title-comments title-left">Commentaires (<?= $articleFactory->commentNbr; ?>)</h4>
            </div>
            <ul class="list-comments">
                <?php foreach($articleFactory->comments as $comment): ?>
              <li>
                <div class="comment-avatar">
                    <img src="##IMGAVATAR##/<?= $comment->userCreate->getId() ?>.png" alt="" />
                </div>
                <div class="comment-details">
                  <h4 class="comment-author"><?= $comment->userCreate->getIdentifiant() ?></h4>
                  <span><?=  \date(ES_DATE_FR,strtotime( $comment->comment->getCreateDate())); ?></span>
                  <p>
                      <?= $comment->comment->getContent(); ?>
                  </p>
                 
                </div>
              </li>
                <?php endforeach; ?>
            </ul>
          </div>
            <div class="form-comments" id="commentadd">
                <div class="title-box-2">
                    <h3 class="title-left">
                        Laisser un commentaire
                    </h3>
                </div>
                <?= $formComment->Render() ?>
            </div>
        </div>
        <div class="col-md-4">
          <div class="widget-sidebar sidebar-search">
            <h5 class="sidebar-title">Recherche</h5>
            <div class="sidebar-content">
              <form action="##INDEX##blog.find" method="post">
                <div class="input-group">
                  <input type="text" name="recherche" class="form-control" placeholder="Recherche..." aria-label="recherche...">
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
