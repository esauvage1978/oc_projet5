          <div class="widget-sidebar widget-tags">
            <h5 class="sidebar-title">Catégories</h5>
            <div class="sidebar-content" >
              <ul id="listCategory" style="display:none " >
                <li>
                  Erreur...
                </li>

              </ul>
            </div>
          </div>

<script type="text/javascript">
    $(document).ready(function () {
        $.ajax({
            type : 'POST',
            url : '##INDEX##blog/category/listnotempty',
            dataType: 'json',
            success: function (data) {
                var txt='';
                for(var index=0; index<data.length;index++) {
                    txt += '<li><a href="##INDEX##blog/article/list/category/' + data[index].bc_id + '">' + data[index].bc_title +'</a></li>';
                }
                $('#listCategory').html(txt);
                $('#listCategory').fadeIn("slow");
            }
        });
        return false;
    });
</script>