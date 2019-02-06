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

//USER ROLE
define('ES_USER_ROLE',
    ['0'=>'Non connecté',
     '1'=>'Visiteur',
     '2'=>'Rédacteur',
     '3'=>'Modérateur',
     '4'=>'Gestionnaire']);
define('ES_USER_ROLE_NOT_CONNECTED',0);
define('ES_USER_ROLE_GESTIONNAIRE',4);
define('ES_USER_ROLE_MODERATEUR',3);
define('ES_USER_ROLE_REDACTEUR',2);
define('ES_USER_ROLE_VISITEUR',1);

//BLOG ARTICLE STATUT
define('ES_BLOG_ARTICLE_STATE',
    ['1'=>'Brouillon',
     '2'=>'Publié',
     '3'=>'Archive',
     '4'=>'Corbeille'
     ]);
define('ES_BLOG_ARTICLE_STATE_CORBEILLE',4);
define('ES_BLOG_ARTICLE_STATE_ARCHIVE',3);
define('ES_BLOG_ARTICLE_STATE_ACTIF',2);
define('ES_BLOG_ARTICLE_STATE_BROUILLON',1);

//BLOG COMMENT STATUT
define('ES_BLOG_COMMENT_STATE',
    ['0'=>'A modérer',
     '1'=>'Rejeté',
     '2'=>'Approuvé'
     ]);
define('ES_BLOG_COMMENT_STATE_APPROVE',2);
define('ES_BLOG_COMMENT_STATE_REJECT',1);
define('ES_BLOG_COMMENT_STATE_WAIT',0);


//PHPMAILER
define('SMTP_HOST','pro1.mail.ovh.net');
define('SMTP_PORT','587');
define('SMTP_USER_MAIL','emmanuel.sauvage@mylostuniver.com');
define('SMTP_USER_NAME','Emmanuel SAUVAGE');
define('SMTP_USER_PASSWORD','Fckgwrhqq101');

//TOKEN
define('ES_TOKEN','token');