<?php


namespace ES\Core\Toolbox;


class Alert
{

    const ERREUR="alert-danger";
    const SUCCES="alert-success";
    const INFO="alert-info";
    const ATTENTION="alert-warning";

    /**
     * Ecriture du message
     * @param mixed $type
     * @param mixed $message
     */
    public static function write($type, $message)
    {
        $data=array($type,self::getIcone($type ),$message);
        if (self::isPresent())
            array_push( $_SESSION['flash'], $data);
        else
            $_SESSION['flash']=array($data);
    }

    /**
     * Création de l'icône du message
     * @param mixed $type 
     * @return string
     */
    private static function getIcone($type)
    {
        switch ($type)
        {
            case self::ERREUR:
                return '<i class="fa fa-times fa-2x"></i>';
            case self::SUCCES:
                return '<i class="fa fa-check fa-2x"></i>';
            case self::INFO:
                return '<i class="fa fa-info fa-2x"></i>';
            case self::ATTENTION:
                return '<i class="fa fa-exclamation-triangle fa-2x"></i>';
            default:
                return '<i class="fa fa-info fa-2x"></i>';
        }
    }

    /**
     * Vérification de la présence de message Flash
     * @return boolean
     */
    public static function isPresent()
    {
        return isset($_SESSION['flash']);
    }

    /**
     * Affichage des messages
     * @return string
     */
    public static function read()
    {
        $message_retour="";
        if (self::isPresent())
        {
            //Debug::var_dump ($_SESSION['flash']);
            foreach ($_SESSION['flash'] as $message)
            {
                $message_retour .= '<div class="alert ' .$message[0] . '" role="alert"><div class="row vertical-align">';
                $message_retour .= '<div class="col-xs-1 text-center">' . $message[1];
                $message_retour .= '</div><div class="col-xs-11">' . $message[2] . '</div></div></div>';
            }
            unset($_SESSION['flash']);
        }
        return $message_retour;
    }

}