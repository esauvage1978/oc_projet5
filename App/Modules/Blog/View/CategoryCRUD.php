
<script>
    $(document).ready(function () {
        $('#categorylist').DataTable(
            {
                "columns": [
                    { "orderable": false },
                    { "orderable": false },
                    null,
                    null
                ],
                "order": [[ 2, "asc" ]],
                responsive: false,
                stateSave: true,
                "scrollX": true,
                "searching": true,
                "paging": false,
                "info": false,
                "language": {
                    buttons: {
                        copyTitle: 'Ajouté au presse-papiers',
                        copyKeys: 'Appuyez sur <i>ctrl</i> ou <i>\u2318</i> + <i>C</i> pour copier les données du tableau à votre presse-papiers. <br><br>Pour annuler, cliquez sur ce message ou appuyez sur Echap.',
                        copySuccess: {
                            _: '%d lignes copiées',
                            1: '1 ligne copiée'
                        }
                    },
                    "sProcessing": "Traitement en cours...",
                    "sSearch": "Rechercher&nbsp;:",
                    "sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
                    "sInfo": "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
                    "sInfoEmpty": "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
                    "sInfoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
                    "sInfoPostFix": "",
                    "sLoadingRecords": "Chargement en cours...",
                    "sZeroRecords": "Aucun &eacute;l&eacute;ment &agrave; afficher",
                    "sEmptyTable": "Aucune donn&eacute;e disponible dans le tableau",
                    "oPaginate": {
                        "sFirst": "Premier",
                        "sPrevious": "Pr&eacute;c&eacute;dent",
                        "sNext": "Suivant",
                        "sLast": "Dernier"
                    },
                    "oAria": {
                        "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                        "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
                    },

                }
            }
        );
    });

</script>
      <div class="row">
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
                                <i class="fa fa-trash" id="<?= $row[0]; ?>"></i>
                            <?php endif ?>
                        </td>
                        <td>
                            <i class="fa fa-edit" id="<?= $row[0]; ?>.<?= $row[1]; ?>"></i>
                        </td>
                           <td>
                            
                            
                            <?= $row[1]; ?>
                            
                            
                            
                        </td>
                        <td class="text-center">
                            <?php if($row[2]!=0): ?>
                                <a href="##INDEX##blog.list/category/<?= $row[0]; ?>">
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

        <div class="widget-sidebar ">
            <h5 class="sidebar-title">Modifier</h5>
            <div class="sidebar-content">
                <form method="POST" action="##INDEX##blog.categorymodify">
                    <?= $formModify->render($formModify::IDHIDDEN);?>
                    <div class="form-group">
                        <?= $formModify->render($formModify::CATEGORY);?>
                    </div>
                    <?= $formModify->render($formModify::BUTTON);?>
                </form>
            </div>
        </div>

        <div class="widget-sidebar widget-tags">
            <h5 class="sidebar-title">Ajouter</h5>
            <div class="sidebar-content">
                <form method="POST" action="##INDEX##blog.categoryadd">
                    <div class="form-group">
                        <?= $formAdd->render($formAdd::CATEGORY);?>
                    </div>
                    <?= $formAdd->render($formAdd::BUTTON);?>
                </form>
            </div>
        </div>


        <script>

            $('.fa-trash').click(function(){
                var adresse = '##INDEX##blog.categorydelete/';
                var id = $(this).attr("id");
                var r = confirm('êtes vous sûr de vouloir supprimer la catégorie ?');
                if (r == true) {
                    window.location.replace(adresse + id); // $(this) représente le paragraphe courant
                }
            });

            $('.fa-edit').click(function(){
                                            var res = $(this).attr("id").split(".");
                var id = res[0];
                var title = res[1];

                $('#<?=$formModify::$name_idhidden;?>').val(res[0]); 
                $('#<?=$formModify::$name_category;?>').val(res[1]); 
            });
        </script>


    </div>
</div>

