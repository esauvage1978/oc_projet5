<?php  if(isset($flash) && $flash->hasFlash()): ?>


    <div class="col-md-12">
        <p>
            <?php foreach($flash->read() as $type=>$message): ?>
            <div class="alert <?= $type;?> alert-dismissible fade show" role="alert">
                <?= isset($message)?$message:''; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <?php endforeach;?>
        </p>
    </div>


<?php endif;?>
