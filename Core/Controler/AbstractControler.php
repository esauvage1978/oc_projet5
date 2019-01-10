<?php

namespace ES\Core\Controler;

use ES\Core\Toolbox\Alert;
/**
 * Controler short summary.
 *
 * Controler description.
 *
 * @version 1.0
 * @author ragus
 */
class AbstractControler
{
    protected $_alert;

    protected function __construct()
    {
        $this->_alert=Alert::getInstance();
    }


}