<?php

namespace ES\App\Modules\Shared\Render;

use ES\App\Modules\Shared\Render\MenuRender;
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
    protected static $modulesViewTemplate=false;

    public function render($view,$data)
    {
        $menu=new menuRender($this->userConnect, $this->request);
        $data['menuUser']=$menu->render();
        $data['menuFooter']=$menu->renderFooter();
        parent::render($view,$data);
    }


}