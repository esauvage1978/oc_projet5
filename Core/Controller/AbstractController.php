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
    protected $_request;
    protected $renderView;
    protected static $module='';
    protected $_userConnect=null;

    public function __construct(UserConnect $userConnect,Request $Request)
    {
        $this->_userConnect =$userConnect ;
        $this->_request=$Request;
        $this->flash=new Flash();
        $caller = '\\ES\\App\\Modules\\' . static::$module . '\\Render\\'. static::$module . 'RenderView';
        $this->renderView=new $caller($userConnect,$Request);
    }


    protected function view($page,$data)
    {
        $this->renderView->render(
            ES_ROOT_PATH_FAT_MODULES . static::$module .  '/View/'. $page .'.php',
            $data);
    }
}