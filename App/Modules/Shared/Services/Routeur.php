<?php

namespace ES\App\Modules\Shared\Services;

Use ES\Core\Toolbox\Request;
use ES\App\Modules\User\Model\UserConnect;

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
    private $_userConnect;

    const MODULE_DEFAULT_VALUE='Shared';
    const SECTION_DEFAULT_VALUE='Shared';
    const ACTION_DEFAULT_VALUE='show';

    /**
     * Instanciation de la class Routeur
     * @return Routeur
     */
    public static function getInstance(UserConnect $userConnect,Request $request) : Routeur
    {
        //design pattern SINGLETON
        if (!isset(self::$_instance)){
            self::$_instance = new Routeur($userConnect,$request);
        }
        return self::$_instance;
    }

    /**
     * Constructeur privé
     */
    public function __construct(UserConnect $userConnect,Request $request)
    {
        $this->_request=$request;
        $this->_userConnect=$userConnect;
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
                . '\\Controller\\' . ucfirst($this->_section) . 'Controller' ;

            if(class_exists($modulesClass )) {
                $this->_moduleInstance=new $modulesClass(new UserConnect($this->_request),$this->_request);
                $retour= true;
            }
        }

        return $retour;
    }
    private function checkModule():bool
    {

        $module=ucfirst($this->_module);
        $section=ucfirst($this->_section);
        $file=ES_ROOT_PATH_FAT_MODULES . $module . '/Controller/' . $section . 'Controller.php';

        return file_exists($file);
    }

    private function createModuleFunction():bool
    {
        $this->_moduleFunction=$this->_action;
        return method_exists ($this->_moduleInstance , $this->_moduleFunction );
    }



    private function callModuleFunction()
    {
        $moduleinstance=$this->_moduleInstance;
        $modulefonction=$this->_moduleFunction;


        if(isset($this->_paramWord)  && isset($this->_paramP) ) {
            $moduleinstance->$modulefonction($this->_paramWord,$this->_paramP);
        } else if(isset($this->_paramWord)  && !isset($this->_paramP) ) {
            $moduleinstance->$modulefonction($this->_paramWord);
        } else if(! isset($this->_paramWord)  && isset($this->_paramP) ) {
            $moduleinstance->$modulefonction($this->_paramP);
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
        $this->_paramP = $this->_request->getGetValue('p',Request::TYPE_INT);
        $this->_paramWord = $this->_request->getGetValue('word');

        //vérification des droits d'accès aux pages
        $restrict=new ACL($this->_userConnect );
        if(!$restrict ->valideAccessPage ($page)) {
            var_dump($page);
            //header('LocationACL_ROOT_PATH_WEB . 'shared.accessdenied');
            //exit;

        }

        if(!empty($page)) {
            $pageExplode= explode('.',strtolower($page));
            if(\count($pageExplode)==3){
                $this->_module=$pageExplode[0];
                $this->_section= $pageExplode[1];
                $this->_action= $pageExplode[2];
            }elseif(\count($pageExplode)==2) {
                $this->_module=$pageExplode[0];
                $this->_section= $this->_module;
                $this->_action= $pageExplode[1];
            } else {
                $this->_module=$pageExplode[0];
                $this->_section=$this->_module;
                $this->_action='show';
            }

            $retour=true;
        }

        return $retour;
    }



}
