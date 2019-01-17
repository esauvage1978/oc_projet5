<?php

namespace ES\Core\Controler;

Use ES\Core\Toolbox\Flash;
Use ES\Core\Toolbox\Request;


/**
 * Controler short summary.
 *
 * Controler description.
 *
 * @version 1.0
 * @author ragus
 */
class AbstractControler
{
    protected $flash;
    protected $_request;
    protected $renderView;
    protected static $module='';

    public function __construct()
    {
        $this->_request=new Request(array('get'=>$_GET,'post'=>$_POST,'session'=>$_SESSION,'cookie'=>$_COOKIE));
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