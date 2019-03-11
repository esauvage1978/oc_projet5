<?php

namespace ES\Core\Upload;

class JpgUpload
{
    private $_domain;
    public $redim=true;
    public $redim_height=600;
    public $redim_width=1000;


    public function __construct($domain)
    {
        $this->_domain =$domain;
        if (!is_dir(ES_ROOT_PATH_WEB_DATAS . 'images/' . $this->_domain) ) {
            mkdir(ES_ROOT_PATH_WEB_DATAS . 'images/' . $this->_domain);
        }
    }


    public function createMiniature($key,$name)
    {
        if(!isset($_FILES[$key]['name'])) {
            return '';
        } elseif($_FILES[$key]['error']=='1') {
            return MSG_FORM_FILE_GENERAL_ERROR;
        } elseif (isset($_FILES[$key]['tmp_name'])) {

            if($_FILES[$key]['type']=='image/jpeg') {
                try {


                    $destination=ES_ROOT_PATH_FAT_DATAS . 'images/'. $this->_domain . '/' . $name . '.jpg';
                    //suppression de la photo si elle est déjà présente
                    if(\file_exists($destination) ){
                        \unlink($destination);
                    }

                    $taille = getimagesize($_FILES[$key]['tmp_name']);
                    $largeur = $taille[0];
                    $hauteur = $taille[1];
                    $largeur_miniature = $this->redim_width;
                    $hauteur_miniature = $hauteur / $largeur * $this->redim_width;

                    $im = \imagecreatefromjpeg($_FILES[$key]['tmp_name']);
                    $im_miniature = \imagecreatetruecolor($largeur_miniature
                    , $hauteur_miniature);

                    if(!\imagecopyresampled($im_miniature, $im, 0, 0, 0, 0, $largeur_miniature, $hauteur_miniature, $largeur, $hauteur)) {
                        return MSG_FORM_FILE_ERROR_CREATE_MINIATURE  . ' (imagecopyresampled ' . $destination .')';
                    }
                    if(!\imagejpeg($im_miniature, $destination, 90)) {
                        return MSG_FORM_FILE_ERROR_CREATE_FILE . ' (imagejpeg '  . $destination . ')';

                    }
                }
                catch (\InvalidArgumentException $ex) {
                    return MSG_FORM_FILE_EXCEPTION  . $ex->getMessage ();
                }

            }
        }

        return '';
    }

    private function checkContenu($file)
    {
        $handle = fopen($file, 'r');
        $erreur=0;
        if ($handle) {

            while (!feof($handle) AND $erreur == 0) {

                $buffer = fgets($handle);

                switch (true) {
                    case strstr($buffer,'<'):
                        $erreur += 1;
                        break;

                    case strstr($buffer,'>'):
                        $erreur += 1;
                        break;

                    case strstr($buffer,';'):
                        $erreur += 1;
                        break;

                    case strstr($buffer,'&'):
                        $erreur += 1;
                        break;

                    case strstr($buffer,'?'):
                        $erreur += 1;
                        break;
                }


                fclose($handle);
            }
        }
    }

}
