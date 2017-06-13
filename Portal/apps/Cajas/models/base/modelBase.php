<?php

/**
 * ActiveRecord
 *
 * Esta clase es la clase padre de todos los modelos
 * de la aplicacion
 *
 * @category Kumbia
 * @package Db
 * @subpackage ActiveRecord
 */
abstract class ActiveRecord extends ActiveRecordBase {

    public function getArray(){                                                                          
        $array = array();                                                                                
        foreach($this->getAttributes() as $atr){                                                         
            $array[$atr] = $this->readAttribute($atr);                                                   
        }                                                                                                
        return $array;                                                                                   
    }

}
