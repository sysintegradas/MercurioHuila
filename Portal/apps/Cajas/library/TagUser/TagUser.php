<?php

abstract class TagUser {

	static public function Calendar($name){
		$value = Tag::getValueFromAction($name);
		$numParams = func_num_args();
		$name = Utils::getParams(func_get_args(),$numParams);
		$config = CoreConfig::readFromActiveApplication('config.ini');
		if(!$name[0]) {
			$name[0] = $name['id'];
		}
		if(!isset($name['name'])||!$name['name']) {
			$name['name'] = $name[0];
		}
		if(($value!='')||(isset($name['value'])&&!empty($name['value']))) {
            if($value!=''){
                $name['value'] = $value;
            }
            if(is_array($name['value'])){
                $xx = $name['value'][0];
			    $fecha = new Date($xx);
            }else{
                $xx = $name['value'];
			    $fecha = new Date($xx);
            }
			$format = strtolower($config->application->dbdate);
			//$format = ereg_replace("yyyy","Y",$format);
			$format = preg_replace("/mm/","m",$format);
			$format = preg_replace("/dd/","dd",$format);
			$fecha = $fecha->getUsingFormat($format);
			$value = $fecha;
		}
		if(!isset($name['onblur'])){
			$name['onblur'] = "dateFormat(this,\"".strtolower($config->application->dbdate)."\");";
		} else {
			$name['onblur']="dateFormat(this,\"".strtolower($config->application->dbdate)."\");".$name['onblur'];
		}
		if(!isset($name['onchange'])){
			$name['onchange'] = "dateFormat(this,\"".strtolower($config->application->dbdate)."\");";
		} else {
			$name['onchange']="dateFormat(this,\"".strtolower($config->application->dbdate)."\");".$name['onchange'];
		}
		$code = "<input type='text' id='{$name[0]}' value='$value' ";
		foreach($name as $key => $value){
			if(!is_numeric($key)){
				$code.="$key='$value' ";
			}
		}
		$code.=" />\r\n";
		if(preg_match("/readonly.*true|disabled.*disabled/",$code)){
			$code.=Tag::image("calendar.gif","style: position: relative;top: 2px;cursor:pointer;")."\r\n";
		} else {
			$code.=Tag::image("calendar.gif","onclick: displayCalendar($('".$name[0]."'),'".strtolower($config->application->dbdate)."',this) ",
					"style: position: relative;top: 2px;cursor:pointer;")."\r\n";
		}
		return $code;
	}
		
}

