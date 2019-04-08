<?php

namespace ES\Core\Upload;

class JpgUpload
{
    private $_domain;
    public $redim=true;
    public $redim_height=600;
    public $redim_width=1000;

    const DIR_TEMPORARY='tmp_name';

    public function __construct($domain)
    {
        $this->_domain =$domain;
        if (!is_dir(ES_ROOT_PATH_FAT_DATAS_IMG .  $this->_domain) ) {
            mkdir(ES_ROOT_PATH_FAT_DATAS_IMG .  $this->_domain);
        }
    }


    public function createMiniature($key,$name)
    {
        $retour='';
        if(isset($_FILES[$key]['name'])) {
            if($_FILES[$key]['error']=='1') {
                $retour= MSG_FORM_FILE_GENERAL_ERROR;
            } elseif (isset($_FILES[$key][self::DIR_TEMPORARY]) && $_FILES[$key]['type']=='image/jpeg') {
                try {


                    $destination=ES_ROOT_PATH_FAT_DATAS_IMG .  $this->_domain . '/' . $name . '.jpg';
                    //suppression de la photo si elle est déjà présente
                    if(\file_exists($destination) ){
                        \unlink($destination);
                    }

                    $taille = getimagesize($_FILES[$key][self::DIR_TEMPORARY]);
                    $largeur = $taille[0];
                    $hauteur = $taille[1];
                    $largeur_miniature = $this->redim_width;
                    $hauteur_miniature = $hauteur / $largeur * $this->redim_width;

                    $im = \imagecreatefromjpeg($_FILES[$key][self::DIR_TEMPORARY]);
                    $im_miniature = \imagecreatetruecolor($largeur_miniature
                    , $hauteur_miniature);

                    if(!\imagecopyresampled($im_miniature, $im, 0, 0, 0, 0, $largeur_miniature, $hauteur_miniature, $largeur, $hauteur)) {
                        $retour= MSG_FORM_FILE_ERROR_CREATE_MINIATURE  . ' (imagecopyresampled ' . $destination .')';
                    } elseif(!\imagejpeg($im_miniature, $destination, 90)) {
                        $retour= MSG_FORM_FILE_ERROR_CREATE_FILE . ' (imagejpeg '  . $destination . ')';

                    }
                }
                catch (\InvalidArgumentException $ex) {
                    $retour= MSG_FORM_FILE_EXCEPTION  . $ex->getMessage ();
                }

            }
        }

        return $retour;
    }

}
