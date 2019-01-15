<?php


namespace ES\App\Modules\Shared\Render;
use \ES\Core\Render\AbstractRenderView;

/**
 * BlogRenderView short summary.
 *
 * BlogRenderView description.
 *
 * @version 1.0
 * @author ragus
 */
class SharedRenderView  extends AbstractRenderView
{

    protected static $module='Shared';

    public function render($view,$data)
    {
        parent::show($view,$data,false);
    }
}