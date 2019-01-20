<?php

namespace ES\Core\Controller;

Use ES\Core\Toolbox\Flash;
Use ES\Core\Toolbox\Request;


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
    protected $flash;
    protected $_request;
    protected $renderView;
    protected static $module='';

    public function __construct()
    {
        $this->_request=new Request($_GET,$_POST,$_COOKIE);
        $this->flash=new Flash();
        $caller = '\\ES\\App\\Modules\\' . static::$module . '\\Render\\'. static::$module . 'RenderView';
        $this->renderView=new $caller();
    }


    protected function view($page,$data)
    {
        $this->renderView->render(
            ES_ROOT_PATH_FAT_MODULES . static::$module .  '\\View\\'. $page .'.php',
            $data);
    }
}