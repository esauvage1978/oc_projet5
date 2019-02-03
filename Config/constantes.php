<?php
/**
  * User: esauvage1978
  * 10/01/2019
 */

define('ES_APPLICATION_NOM','MY LOST UNIVER');


define('ES_ROOT_PATH_FAT', dirname(__DIR__). '/');
define('ES_ROOT_PATH_WEB', 'http://localhost/oc_projet5/');

define('ES_ROOT_PATH_FAT_INDEX', ES_ROOT_PATH_FAT. 'Public/index.php');
define('ES_ROOT_PATH_WEB_INDEX', ES_ROOT_PATH_WEB. '');

define('ES_ROOT_PATH_FAT_MODULES', ES_ROOT_PATH_FAT. 'App/Modules/');


define('ES_ROOT_PATH_WEB_PUBLIC', ES_ROOT_PATH_WEB .'Public/');
define('ES_ROOT_PATH_WEB_IMGBLOG', ES_ROOT_PATH_WEB .'Public/images/blog/');
define('ES_ROOT_PATH_WEB_IMGAVATAR', ES_ROOT_PATH_WEB .'Public/images/avatar/');
define('ES_ROOT_PATH_WEB_VENDOR', ES_ROOT_PATH_WEB .'Public/vendor/');
define('ES_ROOT_PATH_WEB_COMPOSER', ES_ROOT_PATH_WEB .'vendor/');

define('ES_ROOT_PATH_REL_MODULES', '../App/Modules/');



define('ES_NOW','Y-m-d H:i:s');
define('ES_DATE_FR','d/m/Y H:i'); //strtotime

define('ES_ACCREDITATION',
    ['0'=>'Non connecté',
     '1'=>'Visiteur',
     '2'=>'Rédacteur',
     '3'=>'Modérateur',
     '4'=>'Gestionnaire']);
define('ES_NOT_CONNECTED',0);
define('ES_GESTIONNAIRE',4);
define('ES_MODERATEUR',3);
define('ES_REDACTEUR',2);
define('ES_VISITEUR',1);


//PHPMAILER
define('SMTP_HOST','pro1.mail.ovh.net');
define('SMTP_PORT','587');
define('SMTP_USER_MAIL','emmanuel.sauvage@mylostuniver.com');
define('SMTP_USER_NAME','Emmanuel SAUVAGE');
define('SMTP_USER_PASSWORD','Fckgwrhqq101');

//TOKEN
define('ES_TOKEN','token');