<?php

use \ES\App\Modules\Shared\Services\Routeur;
use \ES\Core\Autoloader\Autoloader;
Use ES\Core\Toolbox\Request;
Use ES\Core\Toolbox\Flash;
use ES\App\Modules\User\Model\UserConnect;

require_once '../Config/config.php';
require_once '../Config/constantes.php';
require_once '../Config/i18n.php';
require_once '../Core/Autoloader/Autoloader.php';
require '../App/Modules/Shared/Services/Routeur.php';


session_start();

Autoloader::register();

$request=new Request($_GET,$_POST,$_COOKIE);
$UserConnect=new UserConnect($request);
$flash=new Flash();
$routeur= new Routeur($UserConnect,$request,$flash);
$routeur->run();


