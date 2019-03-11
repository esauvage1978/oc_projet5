<?php

namespace ES\Core\Form\WebControls;

/**
 * WebControls short summary.
 *
 * WebControls description.
 *
 * @version 1.0
 * @author ragus
 */
class WebControls
{
    /**
     * Nom du contrôle
     * @var mixed
     */
    public $name='name';
    /**
     * Prefixe du formulaire pour compléter la variable name
     * @var mixed
     */
    public $prefixeFormName;
    /**
     * id du contrôle = variable name si absent
     * @var mixed
     */
    public $id;
    /**
     * Label affiché pour le contrôle
     * @var mixed
     */
    public $label;
    /**
     * Affichage d'un commentaire d'aide à la saisie
     * @var mixed
     */
    public $helpBlock;
    /**
     * Permet de définir si le champ est
     * @var bool
     */
    public $disabled=false;

    public $isWritable=true;
    /**
     * liste des feuilles de style du contrôle
     * @var mixed
     */
    private $_css;
    /**
     * Type de contrôle
     * @var mixed
     */
    public $type;

    protected static $buildParamsName='name';
    protected static $buildParamsType='type';
    protected static $buildParamsCSS='css';
    protected static $buildParamsHelBlock='helpBlock';
    protected static $buildParamsId='id';
    protected static $buildParamsLabel='label';
    protected static $buildParamsDisabled='disabled';

    public function __construct($prefixeFormName)
    {
        $this->prefixeFormName=$prefixeFormName;
    }

    protected function buildParams($keys=[]):string
    {
        $params='';
        $param='';
        foreach($keys as $key)
        {
            switch ($key)
            {
                case self::$buildParamsName:
                    $param='name="'. $this->getName() . '"';
                    break;
                case self::$buildParamsType:
                    $param='type="'. $this->type . '"';
                    break;
                case self::$buildParamsCSS:
                    $param='class="' . $this->_css . '" ';
                    break;
                case self::$buildParamsDisabled:
                    if ($this->disabled) {
                        $param='	disabled';
                    }
                    break;
                case self::$buildParamsLabel:
                    $param=$this->label;
                    break;
                case self::$buildParamsHelBlock:
                    if(isset($this->helpBlock)) {
                        $param='<small class="form-text text-muted">'. $this->helpBlock . '</small>';
                    }
                    break;
                case self::$buildParamsId:
                    if(isset($this->id)) {
                        $param='id="'.$this->id . '" ';
                    } else {
                        $param='id="'. $this->getName()  . '" ';
                    }
                    break;
                default:
                    $param=MSG_FORM_PARAMS_NOT_FOUND;
                    break;
            }

            if($param!=MSG_FORM_PARAMS_NOT_FOUND && !empty($param)) {
                $param .=' ';
            }
            $params .= $param;
        }
        return $params;
    }

    #region CSS
    public function addCssClass($value)
    {
        $this->_css.= $value . ' ';
    }
    #endregion

    #region NAME
    public function getName()
    {
        return (isset($this->prefixeFormName)?$this->prefixeFormName . '_':'') . $this->name;
    }
    #endregion

}