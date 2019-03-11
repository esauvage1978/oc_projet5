<div class="row" id="categorycrud">
    <div class="col-sm-12">
        <div class="title-box text-center">
            <h3 class="title-a">
                Gestion des catégories
            </h3>
            <div class="line-mf"></div>
        </div>
    </div>
</div>
<div id="category.list" class="row">
    <div class="col-md-8">
        <div class="post-box">
            <table class="table table-striped table-hover table-sm" style="width:100%" id="categorylist">
                <thead>
                    
                    <tr>
                        <th ></th>
                        <th></th>
                        <th>titre</th>
                        <th class="text-center">Nombre d'articles</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($list as  $data): ?>
                    <?php $row=array_values($data); ?>
                    <tr>
                        <td>
                            <?php if($row[2]==0): ?>                            
                            <i class="fa fa-trash" id="<?= $row[0]; ?>.<?= $row[1]; ?>" style="cursor: pointer;"></i>
                            <?php endif ?>
                        </td>
                        <td>
                            <i class="fa fa-edit" id="<?= $row[0]; ?>.<?= $row[1]; ?>" style="cursor: pointer;"></i>
                        </td>
                           <td>
                            
                            
                            <?= $row[1]; ?>
                            
                            
                            
                        </td>
                        <td class="text-center">
                            <?php if($row[2]!=0): ?>
                                <a href="##INDEX##blog/article/listadmin/category/<?= $row[0]; ?>">
                                    <?= $row[2]; ?>
                                </a>
                            <?php endif ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-4">



        <div class="widget-sidebar widget-tags">
            <h5 class="sidebar-title">Ajouter</h5>
            <div class="sidebar-content">
                <?= isset($formAdd)?$formAdd:'';?>
            </div>
        </div>

        <div class="widget-sidebar ">
            <h5 class="sidebar-title">Modifier</h5>
            <div class="sidebar-content">
                <?= isset($formModify)?$formModify:'';?>
            </div>
        </div>
        <!-- style="visibility:hidden"-->
        <div class="widget-sidebar" >
            <h5 class="sidebar-title">Supprimer</h5>
            <div class="sidebar-content">
                <?= isset($formDelete)?$formDelete:'';?>
            </div>
        </div>
        <script type="text/javascript">

            $('.fa-trash').click(function(){
                var res = $(this).attr("id").split(".");
                var id = res[0];
                var title = res[1];
                var r = confirm('êtes vous sûr de vouloir supprimer la catégorie ?');
                if (r == true) {
                    $('#<?=$formDelete[$formDelete::IDHIDDEN]->getName();?>').val(res[0]);
                    $('#<?=$formDelete[$formDelete::CATEGORY]->getName();?>').val(res[1]);
                        $.ajax({
                        type: 'post',
                        url: '##INDEX##blog/category/delete',
                        data: $('#CategoryDeleteForm').serialize(),
                        success: function () {
                                      document.location.href="##INDEX##blog/category/list#categorycrud";
                        }
                      });


                }
            });

            $('.fa-edit').click(function(){
                var res = $(this).attr("id").split(".");
                var id = res[0];
                var title = res[1];

                $('#<?=$formModify[$formModify::IDHIDDEN]->getName();?>').val(res[0]);
                $('#<?=$formModify[$formModify::CATEGORY]->getName();?>').val(res[1]);
            });
        </script>


    </div>
</div>

