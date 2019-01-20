<?php

namespace ES\Core\Model;

/**
 * AbstractUnitClass short summary.
 *
 * AbstractUnitClass description.
 *
 * @version 1.0
 * @author esauvage1978
 */
abstract class AbstractTable
{

    protected static $_prefixe='';

    /**
     * Summary of __construct
     * @param array $donnees
     */
    public function __construct(array $donnees)
    {
        $this->hydrate($donnees);
    }

    /**
     * @param array $donnees
     */
    private function hydrate(array $datas)
    {
        foreach ($datas as $key => $value) {
            if(strpos( $key, static::$_prefixe) === 0) {

                $method = 'set' . str_replace(
                    ' ',
                    '',
                    ucwords(
                        str_replace(
                                '_',
                                ' ',
                                substr($key,strlen( static::$_prefixe)))));

                if (method_exists($this, $method)) {
                    $this->$method($value);
                }
            }
        }
    }
}