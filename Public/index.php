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

ini_set('session.cookie_secure',1);
ini_set('session.cookie_httponly',1);
ini_set('session.use_only_cookies',1);
session_start();

Autoloader::register();

$request=new Request($_GET,$_POST,$_COOKIE);
$UserConnect=new UserConnect($request);
$flash=new Flash();
$routeur= new Routeur($UserConnect,$request,$flash);
$routeur->run();


