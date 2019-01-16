<?php

use ES\Core\Toolbox\Auth;
require '../Core/Toolbox/Auth.php';
require_once '../Config/constantes.php';

$pwd=Auth::password_crypt('a');
$pwd2='$2y$10$GlxsQCzAXWd7axLBORvi7.2YjWxl6EjVhjqCtYlsFr51BEIdGM8VO';
var_dump($pwd);
var_dump($pwd2);
var_dump(Auth::password_compare('a',$pwd));
var_dump(Auth::password_compare('a',$pwd2));


