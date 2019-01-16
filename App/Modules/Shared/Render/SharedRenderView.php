<?php


namespace ES\App\Modules\Shared\Render;
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
        $data['menuUser']=$this->menuUser();
        parent::render($view,$data);
    }

    private function menuUser(): string
    {
        $fichier='UserNotConnected';
        if($this->_request->hasSessionValue('user'))
        {
            $fichier = 'UserConnected';
        }
        return $this->genererFichier(ES_ROOT_PATH_FAT_MODULES. 'Shared\\View\\Partial\\' . $fichier . 'PartialView.php' ,null);
    }
}