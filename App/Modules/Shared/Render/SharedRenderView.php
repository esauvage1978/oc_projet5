<?php


namespace ES\App\Modules\Shared\Render;
use \ES\Core\Render\RenderView;

/**
 * BlogRenderView short summary.
 *
 * BlogRenderView description.
 *
 * @version 1.0
 * @author ragus
 */
class SharedRenderView  extends RenderView
{
    public function __construct()
    {
        parent::__construct ('Shared');
    }

    public function show($view, $data,$moduleViewTemplate=false)
    {
        parent::show($view,$data,$moduleViewTemplate);
    }
}