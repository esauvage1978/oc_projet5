<?php

use ES\App\Modules\User\Model\UserManager;
use ES\Core\Autoloader\Autoloader;
Use ES\Core\Toolbox\Request;
use ES\Core\ToolBox\Alert;
use ES\Core\ToolBox\Session;

require_once '../Config/constantes.php';
require_once '../Core/Autoloader/Autoloader.php';


Autoloader::register();
$session=Session::getInstance();
$session->setFlash ('error','Cet e-mail est déjà utilisé.1');
$session->setFlash ('error','Cet e-mail est déjà utilisé.2');
$session->setFlash ('info','Cet e-mail est déjà utilisé.3');
$session->setFlash ('error','Cet e-mail est déjà utilisé.4');

foreach ($session->getFlashes() as $key=>$value)
{
    echo $key . ' ' . $value . '<br/>';
}


