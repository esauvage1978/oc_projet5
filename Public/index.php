<?php

use \ES\App\Modules\Shared\Services\Routeur;

require '../App/Modules/Shared/Services/Routeur.php';

session_start();

$routeur=Routeur::getInstance();
$routeur->exec();


