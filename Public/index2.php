<?php
session_start();

use ES\Core\Autoloader\Autoloader;
Use ES\Core\Toolbox\Request;

require_once '../Config/constantes.php';
require_once '../Core/Autoloader/Autoloader.php';

use ES\App\Modules\User\Model\UserManager;
use ES\App\Modules\User\Model\UserTable;
use ES\App\Modules\User\Controler\UserControler;

Autoloader::register();
$request=new Request(array('get'=>$_GET,'post'=>$_POST,'session'=>$_SESSION,'cookie'=>$_COOKIE));
$userManager=new UserManager();
$userControler=new UserControler();
$user=$userManager->findUserByLogin('Manuso');
$userManager->connect ($user,$request);
var_dump($user);
//$userControler->valideAccessPageOwnerOrAdmin($user,'9');
$userManager->identifiantExist('Manuso','9');
$userManager->mailExist('emmanuel.sauvage@live.fr','9');

