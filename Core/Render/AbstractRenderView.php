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

        $this->title=$data['title'];
        echo $this->genererFichier(
            ES_ROOT_PATH_FAT_MODULES . 'Shared\\View\\TemplateView.php',
            array(
                'title'=>$this->_title,
                'alert'=>$this->_alert
            )
        );
    }

    private function genererFichier($fichier, $data)
    {
        try
        {
            if (file_exists($fichier))
            {

                if(isset($this->_content))
                    extract ($this->_content);
                extract($data);
                ob_start();
                require $fichier;
                return ob_get_clean();
            }
            throw new \Exception("Fichier ' . $fichier . ' introuvable");
        }
        catch (exception $e)
        {
            throw new \Exception('Erreur dans la classe RenderView');
        }

    }
}
