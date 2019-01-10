<?php

namespace ES\Core\Database;

/**
 * AbstractUnitClass short summary.
 *
 * AbstractUnitClass description.
 *
 * @version 1.0
 * @author esauvage1978
 */
class AbstractUnitClass
{

    

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
    private function hydrate(array $donnees)
    {
        foreach ($donnees as $key => $value)
        {
            if(strpos( $key, static::$prefixe) === 0)
            {
                
                $method = 'set' . ucfirst( substr($key,strlen( static::$prefixe)));

                if (method_exists($this, $method))
                {
                    $this->$method($value);
                }
            }
        }
    }
}