




<div class="row" id="articlelistadmintop">
    <div class="col-sm-12">
        <div class="title-box text-center">
            <h3 class="title-a">
                Les articles
            </h3>
            <p class="subtitle-a">
                <?= $filtre?'<a href="##INDEX##blog.article.listadmin">Afficher la liste complète</a>':''; ?>
            </p>
            <div class="line-mf"></div>
        </div>
    </div>
</div>
<div id="category.list" class="row">
    <div class="col-md-12">
        <div class="post-box">
            <table class="table table-striped table-hover table-sm" style="width:100%" id="articleListAdmin">
                <thead>

                    <tr>
                        <th></th>
                        <th></th>
                        <th>Catégorie</th>
                        <th>Titre</th>
                        <th>Date d'émission</th>
                        <th>Auteur</th>
                        <th>Date de modification</th>
                        <th>Auteur</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($list as  $articleComposer): ?>
                    
                    <tr>
                        <td class="text-center">
                            <a href="##INDEX##blog/article/modify/<?= $articleComposer->article->getId(); ?>">
                                <i class="ion-edit"></i>
                            </a>
                        </td>
                        <td>
                            <select class="dropdown-item color-a" name="state.<?= $articleComposer->article->getId(); ?>" 
                                    id="state.<?= $articleComposer->article->getId(); ?>">
                                <option value="<?= ES_BLOG_ARTICLE_STATE_BROUILLON; ?>" 
                                    <?= (ES_BLOG_ARTICLE_STATE_BROUILLON==$articleComposer->article->getState()?' selected':'');?> >
                                    <?= ES_BLOG_ARTICLE_STATE[ES_BLOG_ARTICLE_STATE_BROUILLON] ?>
                                </option>
                                <option value="<?= ES_BLOG_ARTICLE_STATE_ACTIF;?>" 
                                    <?= (ES_BLOG_ARTICLE_STATE_ACTIF==$articleComposer->article->getState()?' selected':'');?> >
                                    <?= ES_BLOG_ARTICLE_STATE[ES_BLOG_ARTICLE_STATE_ACTIF] ?>
                                </option>
                                <option value="<?= ES_BLOG_ARTICLE_STATE_ARCHIVE;?>" 
                                    <?= (ES_BLOG_ARTICLE_STATE_ARCHIVE==$articleComposer->article->getState()?' selected':'');?> >
                                    <?= ES_BLOG_ARTICLE_STATE[ES_BLOG_ARTICLE_STATE_ARCHIVE] ?>
                                </option>
                                <option value="<?= ES_BLOG_ARTICLE_STATE_CORBEILLE;?>" 
                                    <?= (ES_BLOG_ARTICLE_STATE_CORBEILLE==$articleComposer->article->getState()?' selected':'');?> >
                                    <?= ES_BLOG_ARTICLE_STATE[ES_BLOG_ARTICLE_STATE_CORBEILLE] ?>
                                </option>
                            </select>
                            <div id="retourcommentaire<?= $articleComposer->article->getId(); ?>"></div>
                        </td>
                        <td>
                            <?= $articleComposer->category->getTitle(); ?>
                        </td>
                        <td>
                            <?= $articleComposer->article->getTitle(); ?>
                        </td>
                        <td>
                            <?= $articleComposer->article->getCreateDate(); ?>
                        </td>
                        <td>
                            <?= $articleComposer->createUser->getidentifiant(); ?>
                        </td>
                        <td>
                            <?= isset($articleComposer->modifyUser)?$articleComposer->article->getModifyDate():''; ?>

                        </td>
                        <td>
                            <?= isset($articleComposer->modifyUser)? $articleComposer->modifyUser->getidentifiant():''; ?>

                        </td>

                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
               
    $('.dropdown-item').change(function () {
        var res = $(this).attr("id").split(".");
        var id = res[1];
        var value = $(this).val();
        var r = confirm('êtes vous sûr de vouloir changer le statut de l\'article ?');
        if (r == true) {
            $.ajax({
                type: 'POST',
                dataType : 'json',
                url: '##INDEX##blog/article/changestatut',
                data: 'id=' + id + '&value=' + value,
                success: function (data, statut) {
                    $('#retourcommentaire' + id).text(data.message);        
                },
                error: function (resultat, statut, erreur) {
                alert('error');
                }

            });
        } 
    });

</script>