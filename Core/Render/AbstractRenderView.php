<?php

namespace ES\Core\Render;

use ES\Core\Toolbox\Alert;

class AbstractRenderView
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
    /**
     * template de module
     * @var mixed
     */
    private $_module;

    /**
     * Summary of $_alert
     * @var mixed
     */
    private $_alert;

    protected function __construct($module)
    {
        $this->_module=ucfirst(strtolower($module));
        $this->_alert=Alert::getInstance();
    }

    protected function show($view, $data, $modulesViewTemplate=false)
    {


        $this->_content= array('content'=> $this->genererFichier(
            $view,
            $data
        ));

        if($modulesViewTemplate==true)
        {
            $this->_content = array('content'=>$this->genererFichier(
                ES_ROOT_PATH_FAT_MODULES . '\\'.$this->_module.'\\View\\' .$this->_module.'TemplateView.php',
                 $data
                ));
        }

        $this->_title=$data['title'];
        echo $this->genererFichier(
            ES_ROOT_PATH_FAT_MODULES . 'Shared\\View\\TemplateView.php',
            array(
                'alert'=>$this->_alert
            )
        );
    }

    private function genererFichier($fichier, $data)
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
                    extract ($this->_content);
                extract($data);
                ob_start();
                require $fichier;
                $fileContent= ob_get_clean();
                return str_replace($strFind,$strReplace, $fileContent);
            }
            throw new \Exception("Fichier ' . $fichier . ' introuvable");
        }
        catch (exception $e)
        {
            throw new \Exception('Erreur dans la classe RenderView');
        }

    }

}
