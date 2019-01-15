<?php if($flash->isPresent()): ?>
<section class="white"> 
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php foreach($flash->read() as $type=>$message): ?>
                    <div class="alert <?= $type;?>" role="alert">
                        <div><?= $message ?></div>
                    </div>
                <?php endforeach;?>
            </div>
        </div>
    </div>
</section>
<?php endif;?>