<div class="widget-sidebar">
    <h5 class="sidebar-title">Les derniers articles</h5>
    <div class="sidebar-content">
        <ul class="list-sidebar" id="listarticles"  style="display:none ">
            <li>
                <a href="#">Problème d'affichage des derniers articles.</a>
            </li>
        </ul>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $.ajax({
            type : 'POST',
            url : '##INDEX##blog.lastarticles',
            dataType: 'json',
            success: function (data) {
                var txt='';
                for(var index=0; index<data.length;index++) {
                    txt += '<li><a href="##INDEX##blog.show/' + data[index].ba_id + '">' + data[index].ba_title +'</a></li>';
                }
                $('#listarticles').html(txt);
                $('#listarticles').fadeIn("slow");
            }
        });
        return false;
    });
</script>