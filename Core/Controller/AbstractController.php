<?php

namespace ES\Core\Controller;

Use ES\Core\Toolbox\Flash;
Use ES\Core\Toolbox\Request;
use ES\App\Modules\User\Model\UserConnect;
use ES\App\Modules\User\Controller\RestrictController;

/**
 * Controller short summary.
 *
 * Controller description.
 *
 * @version 1.0
 * @author ragus
 */
class AbstractController
{
    use RestrictController;

    protected $flash;
    protected $request;
    protected $renderView;
    protected static $module='';
    protected $userConnect=null;

    public function __construct(UserConnect $userConnect,Request $Request,Flash $flash, $renderView )
    {
        $this->userConnect =$userConnect ;
        $this->request=$Request;
        $this->flash=$flash;
        $this->renderView=$renderView;
    }


    protected function view($page,$data)
    {
        $this->renderView->render(
            ES_ROOT_PATH_FAT_MODULES . static::$module .  '/View/'. $page .'.php',
            $data);
    }
}