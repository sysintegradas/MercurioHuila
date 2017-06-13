<?php

class QcharFilter implements FilterInterface {
	
	/**
	 * Recibe un parametro por nombre, para devolverlo con la forma "campo like '%texto%'"
	 * segun corresponda.
	 *
	 * @param string $query
	 * @return string
	 */
	public function execute($query){
		if(eregi("^[a-z0-9_-]+:[ ]*\\*?[a-z0-9_-][ a-z0-9_-]*\\*?$",$query)){
			if(eregi("\\*",$query)){
				$query = ereg_replace("\\*","%",$query);
				$query = ereg_replace(":[ ]*"," like '",$query);
			}else{
				$query = ereg_replace(":[ ]*"," = '",$query);
			}
			$query = ereg_replace("$","'",$query);
		}else{
			if(eregi("^[a-z0-9_-]+[ ]+(like|=)[ ]+'%?[ a-z0-9_-]+%?'$",$query)){
				return $query;
			}else{
				$query = "";
			}
		}
        return $query;
	}
	
}

