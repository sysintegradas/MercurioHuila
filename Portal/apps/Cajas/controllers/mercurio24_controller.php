<?php
class Mercurio24Controller extends ApplicationController{

	private $title = "Clasificados";

	public function initialize(){
		$this->setTemplateAfter('escritorio');
	}

	public function indexAction(){
		$this->setParamToview('titulo', $this->title);

		$html = "";
		$html .= "<table class='resultado-consul' border=1>";
		$html .= "<thead>";
		$html .= "<th>Documento</th>";
		$html .= "<th>Nombre</th>";
		$html .= "<th>Categoría</th>";
		$html .= "<th>Título</th>";
		$html .= "<th>Descripción</th>";
		$html .= "<th>Aprobar</th>";
		$html .= "<th>Rechazar</th>";
		$html .= "</thead>";
		$html .= "<tbody>";
		$mercurio24 = $this->Mercurio24->find("estado = 'P'");

		foreach($mercurio24 as $mmercurio24){
		  $result = parent::webService('servidor','autenticar',array("tipo"=>$mmercurio24->getTipo(),"cedtra"=>$mmercurio24->getDocumento(), 'coddoc'=>Session::getData('coddoc')));
		  $mercurio23 = $this->Mercurio23->findFirst("codcat = '{$mmercurio24->getCodcat()}'");
		  $html .= "<tr>";
		  $html .= "<td>{$mmercurio24->getDocumento()}</td>";
		  $html .= "<td>".$result[0]['nombre']."</td>";
		  $html .= "<td>".$mercurio23->getDetalle()."</td>";
		  $html .= "<td>{$mmercurio24->getTitulo()}</td>";
		  $html .= "<td>{$mmercurio24->getDescripcion()}</td>";
		  $html .= "<td onclick=\"aprobar('{$mmercurio24->getId()}',this);\">".Tag::image("desktop/security-high.png", "width: 25px", "height: 25px", "style: cursor: pointer;")."</td>";
		  $html .= "<td onclick=\"rechazar('{$mmercurio24->getId()}',this);\">".Tag::image("desktop/security-low.png", "width: 25px", "height: 25px", "style: cursor: pointer;")."</td>";
		  $html .= "</tr>";
		}

		$html .= "</tbody>";
		$html .= "</table>";
		$this->setParamToView("html", $html);
	}

	public function aprobarAction(){
		$this->setResponse("ajax");
		$response = array('flag' => false, 'msg' => 'No pudo ser aprobado');
		$id = $this->getPostParam("id");
		if($this->Mercurio24->updateAll("estado = 'A'", "conditions: id = $id")){
			$response['flag']= true;
			$response['msg'] = 'El clasificado fue aprobado correctamente';

		}
		$this->renderText($this->jsonEncode($response));

	}

	public function rechazarAction(){
		$this->setResponse("ajax");
		$response = array('flag' => false, 'msg' => 'No pudo ser aprobado');
		$motivo = $this->getPostParam("motivo");
		$id = $this->getPostParam("id");
		if($this->Mercurio24->updateAll("estado = 'X', motivo = '$motivo'", "conditions: id = $id")){
			$response['flag']= true;
			$response['msg'] = 'El clasificado fue rechazado correctamente';

		}
		$this->renderText($this->jsonEncode($response));
	}
}
?>
