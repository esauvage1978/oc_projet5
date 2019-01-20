<?php


namespace ES\App\Modules\Shared\Render;
use \ES\Core\Render\AbstractRenderView;
use \ES\App\Modules\User\Model\UserManager;

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
        $menu='';
        if($this->_request->hasSessionValue('user'))
        {
            $fichier = 'UserConnected';
            $userManager= new UserManager();
            $user=$userManager->findById ($this->_request->getSessionValue('user') ) ;

            $menu.=$this->genererFichier(ES_ROOT_PATH_FAT_MODULES. 'Shared\\View\\Partial\\' .$user->getAccreditation() . 'PartialView.php' ,null);
        }


        $menu.=$this->genererFichier(ES_ROOT_PATH_FAT_MODULES. 'Shared\\View\\Partial\\' . $fichier . 'PartialView.php' ,null);
        return $menu;
    }
}