
<form action="##INDEX##blog/article/modify/<?= $articleComposer->article->getId(); ?>#articlemodify" 
      method="post" enctype="multipart/form-data">
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
                                <a href="##INDEX##blog/article/user/<?= $articleComposer->createUser->getId(); ?>">
                                    <?= $articleComposer->createUser->getIdentifiant(); ?>
                                </a>
                                <span class="ion-ios-clock-outline"></span>
                                <?= \date(ES_DATE_FR,strtotime ( $articleComposer->article->getCreateDate())); ?>
                            </b>

                        </li>
                        <?php if(isset($articleComposer->modifyUser)): ?>

                        <li>
                            Modifié par :
                            <b>
                                <span class="ion-ios-person"></span>
                                <a href="##INDEX##blog/article/user/<?= $articleComposer->modifyUser->getId(); ?>">
                                    <?= $articleComposer->modifyUser->getIdentifiant(); ?>
                                </a>
                            </b>
                            le
                            <b>
                                <span class="ion-ios-clock-outline"></span>
                                <?= \date(ES_DATE_FR,strtotime ( $articleComposer->article->getModifyDate())); ?>
                            </b>
                        </li>
                        <?php endif ?>

                    </ul>
                </div>
            </div>

        </div>
    </div>
</form>
<script>

        tinymce.init({
    selector: 'textarea#ArticleModifyForm_articleContent',
     language: 'fr_FR',
     plugins: 'print preview fullpage searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern help',
  toolbar: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',
  image_advtab: true,
  content_css: [
    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
    '//www.tiny.cloud/css/codepen.min.css'
  ],
  link_list: [
    { title: 'My page 1', value: 'http://www.tinymce.com' },
    { title: 'My page 2', value: 'http://www.moxiecode.com' }
  ],
  image_list: [
    { title: 'My page 1', value: 'http://www.tinymce.com' },
    { title: 'My page 2', value: 'http://www.moxiecode.com' }
  ],
  image_class_list: [
    { title: 'None', value: '' },
    { title: 'Some class', value: 'class-name' }
  ],
  importcss_append: true,
  file_picker_callback: function (callback, value, meta) {
    /* Provide file and text for the link dialog */
    if (meta.filetype === 'file') {
      callback('https://www.google.com/logos/google.jpg', { text: 'My text' });
    }

    /* Provide image and alt text for the image dialog */
    if (meta.filetype === 'image') {
      callback('https://www.google.com/logos/google.jpg', { alt: 'My alt text' });
    }

    /* Provide alternative source and posted for the media dialog */
    if (meta.filetype === 'media') {
      callback('movie.mp4', { source2: 'alt.ogg', poster: 'https://www.google.com/logos/google.jpg' });
    }
  },
  templates: [
    { title: 'Some title 1', description: 'Some desc 1', content: 'My content' },
    { title: 'Some title 2', description: 'Some desc 2', content: '<div class="mceTmpl"><span class="cdate">cdate</span><span class="mdate">mdate</span>My content2</div>' }
  ],
  template_cdate_format: '[CDATE: %m/%d/%Y : %H:%M:%S]',
  template_mdate_format: '[MDATE: %m/%d/%Y : %H:%M:%S]',
  image_caption: true,

  spellchecker_dialog: true,
  spellchecker_whitelist: ['Ephox', 'Moxiecode']
 });

</script>
