<?php

use \ES\App\Modules\Shared\Services\Routeur;

require '../App/Modules/Shared/Services/Routeur.php';

session_start();

$routeur=new Routeur();
$routeur->run();


