<?php

namespace ES\Core\Render;

class RenderView
{
    /**
     * Titre utilisé dans le template
     * @var mixed
     */
    private $title;
    /**
     * contenu des templates
     * @var mixed
     */
    private $_content;
    /**
     * template de module
     * @var mixed
     */
    private $module;

    protected function __construct($module)
    {
        $this->module=ucfirst(strtolower($module));
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
                ES_ROOT_PATH_FAT_MODULES . '\\'.$this->module.'\\View\\' .$this->module.'TemplateView.php',
                 $data
                ));
        }

        $this->title=$data['title'];
        echo $this->genererFichier(
            ES_ROOT_PATH_FAT_MODULES . 'Shared\\View\\TemplateView.php',
            array(
                'title'=>$this->title
            )
        );
    }

    private function genererFichier($fichier, $data)
    {
        try {

            if (file_exists($fichier)) {

                if(isset($this->_content))
                    extract ($this->_content);
                extract($data);
                ob_start();
                require $fichier;
                return ob_get_clean();
            } else {
                throw new \Exception("Fichier ' . $fichier . ' introuvable");
            }
        } catch (exception $e)
        {
            var_dump ($e);
            die();
        }

    }
}
