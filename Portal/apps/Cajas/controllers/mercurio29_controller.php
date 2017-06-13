<?php

class Mercurio29Controller extends ApplicationController{
	
	private $title = "Noticias";

	public function initialize(){
		$this->setTemplateAfter('escritorio');
		$this->setPersistance(true);
	}

	public function indexAction(){
		$this->setParamToview('titulo', $this->title);
		unset($_POST);
	}

	public function noticiasAction(){
		
		$mercurio29 = new Mercurio29();
		$codcaj = Session::getDATA('codcaj');
		$titulo = $this->getPostparam('titulo');
		$descripcion = $this->getPostParam('mensaje');
		$mercurio29->setNumero(0);
		$mercurio29->setCodcaj($codcaj);
		$mercurio29->setTitulo($titulo);
		$mercurio29->setDescripcion($descripcion);
		
		if($mercurio29->save()){
			Message::success('Noticia guardada exitosamente');
		}else{
			Message::warning('No se pudo guardar la noticia');
		}

		return $this->routeTo("action: index");
	}

	public function gestionNoticias_viewAction(){
		$this->setParamToview('titulo', "Gestión de Notícias");
		$estado = array('A' => 'Activo', 'I' => 'Inactivo');
		$html = "";
		$html .= "<table class='resultado-consul' border=1>";
		$html .= "<thead>";
		$html .= "<th>Notícia</th>";
		$html .= "<th>Título</th>";
		$html .= "<th>Descripción</th>";
		$html .= "<th>Ver Noticia</th>";
		$html .= "<th>Estado</th>";
		$html .= "<th>Publicar</th>";
		$html .= "<th>No publicar</th>";
		$html .= "</thead>";
		$html .= "<tbody>";
		$mercurio29 = $this->Mercurio29->find();

		foreach($mercurio29 as $mmercurio29){
		  $html .= "<tr>";
		  $html .= "<td>{$mmercurio29->getNumero()}</td>";
		  $html .= "<td>{$mmercurio29->getTitulo()}</td>";
		  $html .= "<td>".substr($mmercurio29->getDescripcion(),0,70)."</td>";
		  $html .= "<td onclick=\"vernoticia('{$mmercurio29->getNumero()}','{$mmercurio29->getTitulo()}','{$mmercurio29->getDescripcion()}');\">".Tag::image("desktop/search.png", "width: 25px", "height: 25px", "style: cursor: pointer;")."</td>";
		  $html .= "<td>{$estado[$mmercurio29->getEstado()]}</td>";
          if($mmercurio29->getEstado()=="I")
		    $html .= "<td onclick=\"publicar('{$mmercurio29->getNumero()}','A');\">".Tag::image("desktop/security-high.png", "width: 25px", "height: 25px", "style: cursor: pointer;")."</td>";
          else
		    $html .= "<td>&nbsp;</td>";
          if($mmercurio29->getEstado()=="A")
		    $html .= "<td onclick=\"publicar('{$mmercurio29->getNumero()}','I');\">".Tag::image("desktop/security-low.png", "width: 25px", "height: 25px", "style: cursor: pointer;")."</td>";
          else
		    $html .= "<td>&nbsp;</td>";
		  $html .= "</tr>";
		}

		$html .= "</tbody>";
		$html .= "</table>";
		$this->setParamToView("html", $html);
	}

	public function vent_noticiaAction(){
		$this->setResponse("ajax");
		$noticia = $this->getPostParam("noticia");
		$titulo = $this->getPostParam("titulo");
		$descripcion = $this->getPostParam("descripcion");
		$response = "";
		$response .= "<h4>Noticia No.".$noticia."</h4>";
		$response .= "<h3>".$titulo."</h3>";
		$response .= "<p style='text-align: justify;'>".$descripcion."</p>";
		return $this->renderText(json_encode($response));
	}

	public function publicarAction(){
		$this->setResponse("ajax");
        $id = $this->getPostParam("noticia");
        $estado = $this->getPostParam("estado");
		$this->Mercurio29->updateAll("estado='$estado'","conditions: numero = '$id'");
		return $this->renderText(json_encode(true));
	}

}

?>
