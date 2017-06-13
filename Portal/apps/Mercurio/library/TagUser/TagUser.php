<?php

abstract class TagUser {
/*
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
*/

    static public function calendar($name){
        $value = Tag::getValueFromAction($name);
        $numParams = func_num_args();
        $name = Utils::getParams(func_get_args(),$numParams);
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
            //$format = strtolower($config->application->dbdate);
            //$format = ereg_replace("yyyy","Y",$format);
            //$format = ereg_replace("mm","m",$format);
            //$format = ereg_replace("dd","dd",$format);
            //$fecha = $fecha->getUsingFormat("yyyy-mm-dd");
            $fecha = $fecha->getUsingFormatDefault();
            $value = $fecha;
        }
        //$code = "<input type='date' name='{$name[0]}' id='{$name[0]}' value='$value' ";
        $code = "<input type='text' name='{$name[0]}' id='{$name[0]}' value='$value' date='date'";
        foreach($name as $key => $value){
            if(!is_numeric($key)){
                $code.="$key='$value' ";
            }
        }
        $code.=" />\r\n";
        return $code;
    }


    
    static public function olvido(){
        $html = '';
        $html .= '<div class="modal fade " id="olvido-modal" tabindex="-1" role="dialog" aria-hidden="true">';
        $html .= '<div class="modal-dialog ">';
        $html .= '<div class="modal-content">';
        $html .= '<div class="modal-header">';
        $html .= '<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>';
        $html .= '<h4 class="modal-title">Crear Filtro</h4>';
        $html .= '</div>';
        $html .= '<div class="modal-body">';
        $html .= '<div class="form-inline">';
        $html .= Tag::selectStatic("condi-filtro",array("igual"=>"igual","mayor"=>"Mayor","mayorigual"=>"Mayor Igual","menorigual"=>"Menor Igual","menor"=>"Menor","diferente"=>"Diferente"),"class: form-control");
        $html .= Tag::textUpperField("value-filtro","class: form-control","placeholder: Valor Filtro");
        $html .= Tag::button("Adicionar","class: btn btn-success btn-xs","onclick: addFiltro();");
        $html .= '</div>';
        $html .= '<div id="filtro_add">';
        $html .= '<table class="table table-striped">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>Campo</th>';
        $html .= '<th>Condicion</th>';
        $html .= '<th>Valor</th>';
        $html .= '<th>&nbsp;</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="modal-footer">';
        $html .= Tag::button("Aplicar","class: btn btn-primary","onclick: aplicarFiltro();");
        $html .= '<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

		
}

