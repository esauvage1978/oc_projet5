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
trait Valid
{    /**
     * message affiché lorsque la saisie est valide ou non
     * @var mixed
     */
    private $_validMessage;
    /**
     * SaisieValid
     * @var bool
     */
    private $_showIsValid=false;
    /**
     * Saisie Invalide
     * @var bool
     */
    private $_showIsInvalid=false;

    protected static $buildParamsValid='valid';

    public function setIsInvalid($message)
    {
        $this->_validMessage=$message;
        $this->_showIsValid =false;
        $this->_showIsInvalid =true;
        $this->addCssClass ('is-invalid');
    }

    public function setIsValid($message)
    {
        $this->_validMessage=$message;
        $this->_showIsValid =true;
        $this->_showIsInvalid =false;
        $this->addCssClass ('is-valid');
    }
}
