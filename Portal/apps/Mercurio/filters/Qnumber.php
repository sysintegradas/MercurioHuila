<?php

class QnumberFilter implements FilterInterface {
	
	/**
	 * Recibe un parametro por nombre, para devolverlo con la forma "campo >|<|=|>=|<= valor"
	 * segun corresponda.
	 *
	 * @param string $query
	 * @return string
	 */
	public function execute($query){
		if(eregi("^[a-zA-Z0-9_]+:[ ]*(>|<|=|>=|<=)?[ ]*[0-9]+([.,][0-9]+)?$",$query)){
        	if(eregi("=|>|<",$query)){
        		$query = ereg_replace(":","",$query);
        	}else{
        		$query=ereg_replace(":"," =",$query);
        	}
        }else{
        	if(eregi("^[a-zA-Z0-9_]+[ ]*(>|<|=|>=|<=)[ ]*[0-9]+([.,][0-9]+)?$",$query)){
    	    	return $query;
	        }
			$query = "";
        }
        return $query;
	}
	
}

