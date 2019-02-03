
<script type="text/javascript">


$(document).ready(function () {
    $('#commentlist').DataTable(
        {
            "columns": [
                null,
                null,
                null,
                null,
                { "orderable": false },
                { "orderable": false }
            ],
            "order": [[2, "DESC"]],
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
                        <th>Article</th>
                        <th>Emetteur</th>
                        <th>Date d'émission</th>
                        <th>Contenu</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($list as  $data): ?>
                    <?php $row=array_values($data); ?>
                    <tr>
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
                            <i class="ion-thumbsdown text-danger" title="Rejeté le commentaire" id="<?= $row[0]; ?>"></i>
                        </td>
                        <td class="text-center">
                            <i class="ion-thumbsup text-success" title="Validé le commentaire" id="<?= $row[0]; ?>"></i>
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
        <?= $formcomment;?>
    </div>
</div>
<script type="text/javascript">
               

            $('.ion-thumbsdown').click(function(){
                $('#<?=$formcomment[$formcomment::IDHIDDEN]->getName();?>').val($(this).attr("id"));
                $('#<?=$formcomment[$formcomment::STATUS]->getName();?>').val('1');
                var r = confirm('êtes vous sûr de vouloir rejeter ce commentaire ?');
                if (r == true) {$('#CommentModifyStatusForm').submit();}                
            });
            $('.ion-thumbsup').click(function(){
                $('#<?=$formcomment[$formcomment::IDHIDDEN]->getName();?>').val($(this).attr("id"));
                $('#<?=$formcomment[$formcomment::STATUS]->getName();?>').val('2');
                var r = confirm('êtes vous sûr de vouloir valider ce commentaire ?');
                    if (r == true) {$('#CommentModifyStatusForm').submit();}
            });
</script>