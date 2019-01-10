<?php

session_start();


use \ES\App\Modules\Shared\Services\Routeur;
require '../App/Modules/Shared/Services/Routeur.php';
$routeur=Routeur::getInstance();

$routeur->exec();


