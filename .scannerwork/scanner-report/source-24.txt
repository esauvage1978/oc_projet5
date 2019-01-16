<?php

namespace ES\App\Modules\User\Render;
use \ES\Core\Render\AbstractRenderView;
/**
 * UserRenderView short summary.
 *
 * UserRenderView description.
 *
 * @version 1.0
 * @author ragus
 */
class UserRenderView extends AbstractRenderView
{
    protected static $module='User';


    public function render($view,$data)
    {
        parent::show($view,$data,true);
    }
}
