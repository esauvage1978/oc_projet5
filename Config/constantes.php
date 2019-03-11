<?php
/**
  * User: esauvage1978
  * 10/01/2019
 */

define('ES_APPLICATION_NOM','MY LOST UNIVER');

define('ES_ROOT_PATH_FAT', dirname(__DIR__). '/');

define('ES_ROOT_PATH_FAT_INDEX', ES_ROOT_PATH_FAT. 'Public/index.php');
define('ES_ROOT_PATH_WEB_INDEX', ES_ROOT_PATH_WEB. '');

define('ES_ROOT_PATH_FAT_MODULES', ES_ROOT_PATH_FAT. 'App/Modules/');
define('ES_ROOT_PATH_FAT_DATAS', ES_ROOT_PATH_FAT .'Datas/');

define('ES_ROOT_PATH_WEB_PUBLIC', ES_ROOT_PATH_WEB .'Public/');
define('ES_ROOT_PATH_WEB_DATAS', ES_ROOT_PATH_WEB .'Datas/');
define('ES_ROOT_PATH_WEB_IMGBLOG', ES_ROOT_PATH_WEB .'Datas/images/blog/');
define('ES_ROOT_PATH_WEB_IMGAVATAR', ES_ROOT_PATH_WEB .'Datas/images/avatar/');
define('ES_ROOT_PATH_WEB_VENDOR', ES_ROOT_PATH_WEB .'Public/vendor/');
define('ES_ROOT_PATH_WEB_COMPOSER', ES_ROOT_PATH_WEB .'vendor/');

define('ES_ROOT_PATH_REL_MODULES', '../App/Modules/');


define('ES_NOW','Y-m-d H:i:s');
define('ES_NOW_SHORT','Y-m-d');
define('ES_DATE_FR','d/m/Y à H:i'); //strtotime

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
    ['10'=>'A modérer',
     '20'=>'Rejeté',
     '30'=>'Approuvé'
     ]);
define('ES_BLOG_COMMENT_STATE_APPROVE',30);
define('ES_BLOG_COMMENT_STATE_REJECT',20);
define('ES_BLOG_COMMENT_STATE_WAIT',10);




//TOKEN
define('ES_TOKEN','token');

//DASHBOARD
define('ES_DASHBOARD_TITRE','title');
define('ES_DASHBOARD_ICONE','icone');
define('ES_DASHBOARD_NUMBER','number');
define('ES_DASHBOARD_CONTENT','content');
define('ES_DASHBOARD_LINK','link');
define('ES_DASHBOARD_COLOR','color');

//IP
define('ES_IP','REMOTE_ADDR');