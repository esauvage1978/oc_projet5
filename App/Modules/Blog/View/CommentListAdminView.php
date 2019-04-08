
<div class="row" id="commentlisttop">
    <div class="col-sm-12">
        <div class="title-box text-center">
            <h3 class="title-a">
                Modération des commentaires
            </h3>
            <div class="line-mf"></div>
        </div>
    </div>
</div>
<div id="category.list" class="row">
    <div class="col-md-12">
        <div class="post-box">
            <table class="table table-striped table-hover table-sm" style="width:100%" id="commentlist">
                <thead>
                    
                    <tr>
                        <th>Action</th>
                        <th>Article</th>
                        <th>Emetteur</th>
                        <th>Date d'émission</th>
                        <th>Contenu</th>
                        <th>Par</th>
                        <th>Le</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($list as  $data): ?>
                    <?php $row=array_values($data); ?>
                    <tr>
                        <td style="width:150px;">
                            <select class="dropdown-item color-a" name="state.<?= $row[0]; ?>" 
                                    id="state.<?= $row[0]; ?>">
                                <option value="<?= ES_BLOG_COMMENT_STATE_WAIT; ?>" 
                                    <?= (ES_BLOG_COMMENT_STATE_WAIT==$row[5]?' selected':'');?> >
                                    <?= ES_BLOG_COMMENT_STATE[ES_BLOG_COMMENT_STATE_WAIT] ?>
                                </option>
                                <option value="<?= ES_BLOG_COMMENT_STATE_REJECT;?>" 
                                    <?= (ES_BLOG_COMMENT_STATE_REJECT==$row[5]?' selected':'');?> >
                                    <?= ES_BLOG_COMMENT_STATE[ES_BLOG_COMMENT_STATE_REJECT] ?>
                                </option>
                                <option value="<?= ES_BLOG_COMMENT_STATE_APPROVE;?>" 
                                    <?= (ES_BLOG_COMMENT_STATE_APPROVE==$row[5]?' selected':'');?> >
                                    <?= ES_BLOG_COMMENT_STATE[ES_BLOG_COMMENT_STATE_APPROVE] ?>
                                </option>
                            </select>
                            <div id="retourcommentaire<?= $row[0]; ?>"></div>

                        </td>
                        <td>
                            <?= $row[1]; ?>
                        </td>
                        <td>
                            <?= $row[2]; ?>
                        </td>
                        <td>
                            <?= $row[3]; ?>
                        </td>
                        <td>
                            <?= $row[4]; ?>
                        </td>
                        <td>
                            <?= array_key_exists(6,$row)?$row[6]:''; ?>
                        </td>
                        <td>
                            <?= array_key_exists(7,$row)?$row[7]:''; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="widget-sidebar" style="display :none" >
    <h5 class="sidebar-title">Changer le status</h5>
    <div class="sidebar-content">
        <?= isset($formcomment)?$formcomment:'';?>
    </div>
</div>

<script type="text/javascript">
               
    $('.dropdown-item').change(function () {
        var res = $(this).attr("id").split(".");
        var id = res[1];
        var value = $(this).val();
        var r = confirm('êtes vous sûr de vouloir changer le statut du commentaire ?');
        if (r == true) {
            $.ajax({
                type: 'POST',
                dataType : 'json',
                url: '##INDEX##blog/comment/changemoderatorstate',
                data: 'id=' + id + '&value=' + value,
                success: function (code_html, statut) {
                    $('#retourcommentaire' + id).text(code_html.message);  
                },
                error : function(resultat, statut, erreur){
                    alert('error' + resultat);
                }
            });
        } 
    });

</script>