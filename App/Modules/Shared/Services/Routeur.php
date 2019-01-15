<?php
/**
 * User: esauvage1978
 * 10/01/2019
 */

namespace ES\App\Modules\Shared\Services;

use ES\Core\Autoloader\Autoloader;
Use ES\Core\Toolbox\Request;

require_once '../Config/constantes.php';
require_once '../Core/Autoloader/Autoloader.php';

/**
 * Routeur de la structure MVC
 */
class Routeur
{
    /**
     * Variable qui stockera l'instance de routeur
     * @var mixed
     */
    private static $_instance;
    private $_module;
    private $_section;
    private $_action;
    private $_admin;

    private $_parametre_p=null;
    private $_parametre_mot=null;

    private $_moduleInstance;
    private $_moduleFunction;

    private $_request;

    const MODULE_DEFAULT_VALUE='Shared';
    const SECTION_DEFAULT_VALUE='home';
    const ACTION_DEFAULT_VALUE='Show';
    const ADMIN_DEFAULT_VALUE=false;

    /**
     * Instanciation de la class Routeur
     * @return Routeur
     */
    public static function getInstance() : Routeur
    {
        //design pattern SINGLETON
        if (!isset(self::$_instance))
        {
            self::$_instance = new Routeur();
        }
        return self::$_instance;
    }

    /**
     * Constructeur privé
     */
    public function __construct()
    {
        Autoloader::register();
        $this->_request=new Request(array('get'=>$_GET,'post'=>$_POST,'session'=>$_SESSION,'cookie'=>$_COOKIE));

    }


    /**
     * Fonction de routage
     */
    public function run()
    {
        $this->initialiseVariable() || $this->showDefault();
        $this->createModuleInstance() || $this->showDefault();
        $this->createModuleFunction() || $this->showDefault();
        $this->callModuleFunction();
    }

    private function showDefault()
    {
        $this->_admin=self::ADMIN_DEFAULT_VALUE;
        $this->_module=self::MODULE_DEFAULT_VALUE;
        $this->_section=self::SECTION_DEFAULT_VALUE;
        $this->_action=self::ACTION_DEFAULT_VALUE;



        $this->createModuleInstance();
        $this->createModuleFunction();
        $this->callModuleFunction();
        exit;
    }

    private function createModuleInstance() : bool
    {
        $retour=false;
        if($this->check_module() )
        {
            $modulesClass='\\ES\\App\\Modules\\' . ucfirst($this->_module) . '\\Controler\\' . ucfirst($this->_module) . 'Controler' ;

            if(class_exists($modulesClass ) )
            {
                $this->_moduleInstance=new $modulesClass();
                $retour= true;
            }
        }

        return $retour;
    }

    private function createModuleFunction():bool
    {
        $this->_moduleFunction=$this->_section;


        if(isset($this->_action))
        {
            $this->_moduleFunction .= ucfirst($this->_action);
        }

        return method_exists ($this->_moduleInstance , $this->_moduleFunction );
    }



    private function callModuleFunction()
    {
        $moduleinstance=$this->_moduleInstance;
        $modulefonction=$this->_moduleFunction;
        $parametre=null;

        if(isset($this->parametre_p))
        {
            $parametre=$this->parametre_p;
        }
        else if(isset($this->parametre_mot))
        {
            $parametre=$this->parametre_mot;
        }

        if(isset($parametre))
        {
            $moduleinstance->$modulefonction($parametre);
        }
        else
        {
            $moduleinstance->$modulefonction();
        }

    }

    private function initialiseVariable() :bool
    {
        $page=null;
        $pageExplode=null;
        $retour=false;

        $page=$this->_request->getGetValue('page');
        $this->_parametre_p = $this->_request->getGetValue('p');
        $this->_parametre_mot = $this->_request->getGetValue('word');

        if(isset($page) && !empty($page))
        {
            $page = strtolower($page);
            $pageExplode= explode('.',$page);

            if($pageExplode[0]=='admin' )
            {
                $this->_admin='Admin';
                $this->_module=$pageExplode[1];
                if(isset($pageExplode[2]))
                {
                    $this->_section=$pageExplode[2];
                }
                if(isset($pageExplode[3]))
                {
                    $this->_action=$pageExplode[3];
                }
            }
            else
            {
                $this->_module=$pageExplode[0];
                if(isset($pageExplode[1]))
                {
                    $this->_section=$pageExplode[1];
                }
                if(isset($pageExplode[2]))
                {
                    $this->_action=$pageExplode[2];
                }
            }
            $retour=true;
        }

        return $retour;
    }


    private function check_module():bool
    {

        $module=ucfirst($this->_module);
        $file=ES_ROOT_PATH_FAT_MODULES . $module . '\\Controler\\' . $module . 'Controler.php';
        return file_exists($file);
    }
}
