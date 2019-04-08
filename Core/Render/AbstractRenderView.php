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
    protected $request;
    protected $userConnect;
    protected static $module='';
    protected static $modulesViewTemplate=false;

    public function __construct($userConnect,Request $request,Flash $flash)
    {
        $this->request=$request;
        $this->userConnect =$userConnect;
        $this->flash=$flash;
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
                ES_ROOT_PATH_FAT_MODULES . '/'. static::$module .'/View/' . static::$module .'TemplateView.php',
                 $data
                ));
        }
        $data['flash']=$this->flash;
        $this->_title=$data['title'];
        $data['home']=stripos($view, 'home');

        echo $this->genererFichier(
            ES_ROOT_PATH_FAT_MODULES . 'Shared/View/TemplateView.php',
            $data
        );
    }

    public function genererFichier($fichier, $data)
    {
        $strFind=array(
            '##DIR_COMPOSER##',
            '##DIR_VENDOR##',
            '##DIR_PUBLIC##',
            '##DIR##',
            '##INDEX##',
            '##TITLE##',
            '##IMGBLOG##',
            '##IMGAVATAR##',
            '##DATAS##'
            );
        $strReplace=array(
            ES_ROOT_PATH_WEB_COMPOSER,
            ES_ROOT_PATH_WEB_VENDOR,
            ES_ROOT_PATH_WEB_PUBLIC,
            ES_ROOT_PATH_WEB,
            ES_ROOT_PATH_WEB_INDEX,
            $this->_title,
            ES_ROOT_PATH_WEB_IMGBLOG,
            ES_ROOT_PATH_WEB_IMGAVATAR,
            ES_ROOT_PATH_WEB_DATAS
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
            throw new \InvalidArgumentException("Fichier ' . $fichier . ' introuvable");
        }
        catch (exception $e)
        {
            throw new \InvalidArgumentException('Erreur dans la classe RenderView');
        }

    }

}
