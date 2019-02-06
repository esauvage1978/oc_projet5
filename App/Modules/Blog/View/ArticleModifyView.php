
<form action="##INDEX##blog.article.modify/<?= $articleComposer->article->getId(); ?>#articlemodify" method="post">
    <div class="row justify-content-md-center" id="articlemodify">
        <div class="col-md-8">
            <div class="widget-sidebar">
                <div class="sidebar-content">
                    <?= isset($formModify)?$formModify:''; ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="widget-sidebar">
                <div class="sidebar-content">
                    <?= isset($formModifyState)?$formModifyState:''; ?>
                </div>
            </div>

            <div class="widget-sidebar">
                <div class="sidebar-content">
                    <ul class="list-sidebar">
                        <li>
                            Création
                            <b>
                                <span class="ion-ios-person"></span>
                                <a href="##INDEX##blog.list/user/<?= $articleComposer->createUser->getId(); ?>">
                                    <?= $articleComposer->createUser->getIdentifiant(); ?>
                                </a>
                                <span class="ion-ios-clock-outline"></span>
                                <?= \date(ES_DATE_FR,strtotime ( $articleComposer->article->getCreateDate())); ?>
                            </b>

                        </li>
                        <?php if(isset($articleFactory->Modifyuser)): ?>

                        <li>
                            Modifié par :
                            <b>
                                <span class="ion-ios-person"></span>
                                <a href="##INDEX##blog.list/user/<?= $articleFactory->Modifyuser->getId(); ?>">
                                    <?= $articleFactory->Modifyuser->getIdentifiant(); ?>
                                </a>
                            </b>
                            le
                            <b>
                                <span class="ion-ios-clock-outline"></span>
                                <?= is_null($articleComposer->article->getModifyDate()) ?
                                    \date(ES_DATE_FR,strtotime ( $articleComposer->article->getCreateDate()))
                                    :''; ?>
                            </b>
                        </li>
                        <?php endif ?>

                    </ul>
                </div>
            </div>

        </div>
    </div>
</form>