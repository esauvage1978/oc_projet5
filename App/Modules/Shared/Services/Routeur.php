<?php
/**
 * User: esauvage1978
 * 10/01/2019
 */

namespace ES\App\Modules\Shared\Services;

use ES\Core\Autoloader\Autoloader;


require '../Config/constantes.php';
require '../Core/Autoloader/Autoloader.php';

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
    private function __construct()
    {
        Autoloader::register();
    }


    /**
     * Fonction de routage
     */
    public function exec()
    {
        if ($this->initialiseVaiable()==true)
        {
            if( $this->createModuleInstance()==false)
                return $this->showDefaultPage ();
            if($this->createModuleFunction()==false)
                return $this->showDefaultPage ();
            if($this->callModuleFunction()==false)
                return $this->showDefaultPage ();
        }
        else
        {
            return $this->showDefaultPage ();
        }
        return true;

    }

    private function createModuleInstance() : bool
    {
        if($this->check_module()==false )
        {
            return false;
        }

        $modulesClass='\\ES\\App\\Modules\\' . ucfirst($this->_module) . '\\Controler\\' . ucfirst($this->_module) . 'Controler' ;

        if(class_exists($modulesClass ) )
        {
            $this->_moduleInstance=new $modulesClass();
            return true;
        }
        else
        {
            return false;
        }
    }

    private function createModuleFunction():bool
    {

        if(isset($this->_action))
            $this->_moduleFunction=$this->_section .ucfirst($this->_action);
        else
            $this->_moduleFunction=$this->_section;

        if(!method_exists ($this->_moduleInstance , $this->_moduleFunction ))
            return false;
        else
            return true;
    }



    private function callModuleFunction():bool
    {
        try
        {
            $mi=$this->_moduleInstance;
            $mf=$this->_moduleFunction;
            if(isset($this->parametre_p))
            {
                $mi->$mf($this->parametre_p);
            }
            else if(isset($this->parametre_mot))
            {
                $mi->$mf($this->parametre_mot);
            }
            else
            {
                $mi->$mf();
            }
            return true;
        }
        catch (Exception $ex)
        {
            return false;
        }
    }

    private function initialiseVaiable() :bool
    {

        $page=null;
        $pageExplode=null;

        if(isset($_GET['page']) && !empty($_GET['page']))
        {
            $page = strtolower(filter_input(INPUT_GET,'page',FILTER_SANITIZE_STRING));

            $pageExplode= explode('.',$page);

            if(count($pageExplode )>4 )
                return false;

            if($pageExplode[0]=='admin' )
            {
                $this->_admin='Admin';
                $this->_module=$pageExplode[1];
                if(isset($pageExplode[2]))
                    $this->_section=$pageExplode[2];
                if(isset($pageExplode[3]))
                    $this->_action=$pageExplode[3];
            }
            else
            {
                $this->_module=$pageExplode[0];
                if(isset($pageExplode[1]))
                    $this->_section=$pageExplode[1];
                if(isset($pageExplode[2]))
                    $this->_action=$pageExplode[2];
            }

        }
        else
        {
            return false;
        }

        if (isset($_GET['p']))
        {
            $this->_parametre_p = filter_input(INPUT_GET,'p',FILTER_SANITIZE_NUMBER_INT);
            if(empty($this->_parametre_p)) $$this->_parametre_p =null;
        }
        else
        {
            $this->_parametre_p = null;
        }

        if (isset($_GET['word']))
        {
            $this->_parametre_mot  = filter_input(INPUT_GET,'word',FILTER_SANITIZE_STRING);
            if(empty($this->_parametre_mot)) $this->_parametre_mot=null;
        }
        else
        {
            $this->_parametre_mot = null;
        }

        return true;
    }
    private function showDefaultPage() :bool
    {
        $this->_admin=self::ADMIN_DEFAULT_VALUE;
        $this->_module=self::MODULE_DEFAULT_VALUE;
        $this->_section=self::SECTION_DEFAULT_VALUE;
        $this->_action=self::ACTION_DEFAULT_VALUE;

        if($this->createModuleInstance()==false)
        {
            var_dump('erreur : createModuleInstance');
            return false;
        }

        if($this->createModuleFunction()==false)
        {
            var_dump('erreur : createModuleFunction');
            return false;
        }
        if($this->callModuleFunction()==false)
        {
            var_dump('erreur : callModuleFunction');
            return false;
        }

        return true;
    }

    private function check_module():bool
    {
        $module=ucfirst($this->_module);
        $file=ES_ROOT_PATH_FAT_MODULES . $module . '\\Controler\\' . $module . 'Controler.php';
        if(!file_exists($file))
        {
            return false;
        }
        return true;
    }
}
