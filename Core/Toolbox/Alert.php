<?php


namespace ES\Core\Toolbox;


class Alert
{

    const ERREUR="alert-danger";
    const SUCCES="alert-success";
    const INFO="alert-info";
    const WARNING="alert-warning";

    private static $_instance;

    public static function getInstance() : Alert
    {
        //design pattern SINGLETON
        if (!isset(self::$_instance))
        {
            self::$_instance = new Alert();
        }
        return self::$_instance;
    }

    public function __construct()
    {

    }
    public function isPresent()
    {
        return isset($_SESSION['flash']);
    }
    private function getAlert()
    {
        return $_SESSION['flash'];
    }
    private function unsetAlert()
    {
        unset($_SESSION['flash']);
    }
    private function setAlert($data)
    {
        if (self::isPresent())
            array_push( $_SESSION['flash'], $data);
        else
            $_SESSION['flash']=array($data);
    }

    /**
     * Ecriture du message
     * @param mixed $type
     * @param mixed $message
     */
    private function write($type, $message)
    {
        $this->setAlert(array($type,self::getIcone($type ),$message));
    }

    public function writeError($message)
    {
        $this->write(self::ERREUR,$message);
    }
    public function writeInfo($message)
    {
        $this->write(self::INFO,$message);
    }
    public function writeSucces($message)
    {
        $this->write(self::SUCCES,$message);
    }
    public function writeWarning($message)
    {
        $this->write(self::WARNING,$message);
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


    /**
     * Affichage des messages
     * @return string
     */
    public function show()
    {
        $message_retour="";
        if (self::isPresent())
        {
            //Debug::var_dump ($_SESSION['flash']);
            foreach ($this->getAlert() as $message)
            {
                $message_retour .= '<div class="alert ' .$message[0] . '" role="alert"><div class="row vertical-align">';
                $message_retour .= '<div class="col-xs-1 text-center">' . $message[1];
                $message_retour .= '</div><div class="col-xs-11">' . $message[2] . '</div></div></div>';
            }
            $this->unsetAlert ();
        }
        return $message_retour;
    }

}