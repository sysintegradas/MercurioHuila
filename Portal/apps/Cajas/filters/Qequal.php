<?php

class QequalFilter implements FilterInterface {
	
	/**
	 * Recibe un parametro por nombre, para devolverlo con la forma "campo = 'valor'".
	 *
	 * @param string $query
	 * @return string
	 */
	public function execute($query){
		if(eregi("^[a-z0-9_]+:[ ]*[a-z0-9_][ a-z0-9_]*$",$query)){
        	$query = ereg_replace(":[ ]*"," = '",$query);
        	$query = ereg_replace("$","'",$query);
        }else{
        	if(eregi("^[a-zA-Z0-9_]+[ ]*=[ ]*'[a-z0-9_][ a-z0-9_]*'$",$query)){
    	    	return $query;
	        }
			$query = "";
        }
        return $query;
	}
	
}

