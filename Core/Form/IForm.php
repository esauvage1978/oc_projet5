<?php

namespace ES\Core\Form;

/**
 * IForm short summary.
 *
 * IForm description.
 *
 * @version 1.0
 * @author ragus
 */
interface IForm
{
    public function __construct($datas=[]);
    public function check():bool;
}
