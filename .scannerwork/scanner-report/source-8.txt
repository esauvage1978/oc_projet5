<?php
/**
 * User: esauvage1978
 * 10/01/2019
 */

namespace ES\App\Modules\Shared\Services;

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

    private $_paramP=null;
    private $_paramWord=null;

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
    public static function getInstance($request) : Routeur
    {
        //design pattern SINGLETON
        if (!isset(self::$_instance)){
            self::$_instance = new Routeur($request);
        }
        return self::$_instance;
    }

    /**
     * Constructeur privé
     */
    public function __construct($request)
    {
        $this->_request=$request;
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
        if($this->checkModule()) {
            $modulesClass='\\ES\\App\\Modules\\' . ucfirst($this->_module) 
                . '\\Controller\\' . ucfirst($this->_module) . 'Controller' ;
            if(class_exists($modulesClass )) {
                $this->_moduleInstance=new $modulesClass();
                $retour= true;
            }
        }
        return $retour;
    }

    private function createModuleFunction():bool
    {
        $this->_moduleFunction=$this->_section;

        if(isset($this->_action)) {
            $this->_moduleFunction .= ucfirst($this->_action);
        }

        return method_exists ($this->_moduleInstance , $this->_moduleFunction );
    }



    private function callModuleFunction()
    {
        $moduleinstance=$this->_moduleInstance;
        $modulefonction=$this->_moduleFunction;
        $parametre=null;

        if(isset($this->paramP)) {
            $parametre=$this->paramP;
        } else if(isset($this->paramWord)) {
            $parametre=$this->paramWord;
        }

        if(isset($parametre)) {
            $moduleinstance->$modulefonction($parametre);
        } else {
            $moduleinstance->$modulefonction();
        }

    }

    private function initialiseVariable() :bool
    {
        $page=null;
        $pageExplode=null;
        $retour=false;

        $page=$this->_request->getGetValue('page');
        $this->_paramP = $this->_request->getGetValue('p');
        $this->_paramWord = $this->_request->getGetValue('word');

        if(!empty($page)) {
            $pageExplode= explode('.',strtolower($page));
            $key=0;
            if($pageExplode[0] =='admin' ) {
                $this->_admin='Admin';
                $key++;
            }

            $this->_module=$pageExplode[$key];
            $this->_section= (isset($pageExplode[$key+1]))?$pageExplode[$key+1]:null;
            $this->_action= (isset($pageExplode[$key+2]))?$pageExplode[$key+2]:null;

            $retour=true;
        }

        return $retour;
    }


    private function checkModule():bool
    {
        $module=ucfirst($this->_module);
        $file=ES_ROOT_PATH_FAT_MODULES . $module . '\\Controller\\' . $module . 'Controller.php';
        return file_exists($file);
    }
}
