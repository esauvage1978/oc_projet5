<?php

namespace ES\Core\Render;
Use ES\Core\Toolbox\Flash;
Use ES\Core\Toolbox\Request;

abstract class AbstractRenderView
{
    /**
     * Titre utilisé dans le template
     * @var mixed
     */
    private $_title;
    /**
     * contenu des templates
     * @var mixed
     */
    private $_content;
    protected $flash;
    protected $_request;
    protected static $module='';
    protected static $modulesViewTemplate=false;

    public function __construct()
    {
        $this->flash=new Flash();
        $this->_request=new Request(
            ['get'=>$_GET,
            'post'=>$_POST,
            'session'=>$_SESSION,
            'cookie'=>$_COOKIE]);
    }

    protected function render($view, $data)
    {

        $this->_content= array('content'=> $this->genererFichier(
            $view,
            $data
        ));

        if(static::$modulesViewTemplate)
        {
            $this->_content = array('content'=>$this->genererFichier(
                ES_ROOT_PATH_FAT_MODULES . '\\'. static::$module .'\\View\\' . static::$module .'TemplateView.php',
                 $data
                ));
        }
        $data['flash']=$this->flash;
        $this->_title=$data['title'];
        echo $this->genererFichier(
            ES_ROOT_PATH_FAT_MODULES . 'Shared\\View\\TemplateView.php',
            $data
        );
    }

    protected function genererFichier($fichier, $data)
    {
        $strFind=array(
            '##DIR_VENDOR##',
            '##DIR_PUBLIC##',
            '##INDEX##',
            '##TITLE##'
            );
        $strReplace=array(
            ES_ROOT_PATH_WEB_VENDOR,
            ES_ROOT_PATH_WEB_PUBLIC,
            ES_ROOT_PATH_WEB_INDEX,
            $this->_title
            );


        try
        {
            if (file_exists($fichier))
            {

                if(isset($this->_content))
                {
                    extract ($this->_content);
                }
                isset($data)?extract($data):'';

                ob_start();
                require $fichier;
                $fileContent= ob_get_clean();
                return str_replace($strFind,$strReplace, $fileContent);
            }
            throw new \ErrorException("Fichier ' . $fichier . ' introuvable");
        }
        catch (exception $e)
        {
            throw new \ErrorException('Erreur dans la classe RenderView');
        }

    }

}
