<?php

namespace ES\Core\Form\WebControls;

/**
 * Valid short summary.
 *
 * Valid description.
 *
 * @version 1.0
 * @author ragus
 */
class WebControlsParamText  extends WebControls
{

    /**
     * valeur de l'input
     * @var mixed
     */
    private $_text;

    const CONTROLE_NOTHING ='0';
    const CONTROLE_MAIL ='1';
    const CONTROLE_INT ='2';
    const CONTROLE_BOOLEAN='3';
    const CONTROLE_HTMLENTITY='5';

    public $defaultControl;

    public function setText(string $value)
    {
        switch($this->defaultControl)
        {
            case self::CONTROLE_NOTHING:
                $this->_text=$value;
                break;
            case self::CONTROLE_INT:
                $this->_text=filter_var($value,FILTER_VALIDATE_INT );
                break;
            case self::CONTROLE_MAIL:
                $this->_text=filter_var($value,FILTER_VALIDATE_EMAIL );
                break;
            case self::CONTROLE_BOOLEAN:
                $this->_text=filter_var($value,FILTER_VALIDATE_BOOLEAN );
                break;
            default:
                $this->_text=htmlentities($value);
                break;
        }
    }
    public function getText()
    {
        return $this->_text;
    }

    protected function paramBuildsText()
    {
        if(isset($this->_text)) {
            return 'value="'. $this->getText() . '"';
        }
        return '';
    }

    public function check():bool
    {
        $retour=true;
        $value=$this->getText();

        if( empty($value)) {
            $this->setIsInvalid(MSG_FORM_NOT_GOOD);
            $retour=false;
        }

        return $retour;
    }


    public function checkLenght($mini=null,$maxi=null):bool
    {
        $retour=true;
        $value=$this->getText();
        if( isset($mini) && strlen($value)<$mini ) {

            $this->setIsInvalid(MSG_FORM_LENGHT_NOT_GOOD . ' (plus de ' . $mini . ' caractères).');
            $retour=false;
        } else if( isset($maxi) && strlen($value)>$maxi ) {

            $this->setIsInvalid(MSG_FORM_LENGHT_NOT_GOOD . ' (moins de ' . $maxi . ' caractères).');
            $retour=false;
        }
        return $retour;
    }
}
