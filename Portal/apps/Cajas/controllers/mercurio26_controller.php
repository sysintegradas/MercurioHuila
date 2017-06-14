<?php
class Mercurio26Controller extends ApplicationController{
    public function initialize(){
        $this->setTemplateAfter('escritorio');
        $this->setPersistance(true);
    }

    public function indexAction(){
        $this->setParamToview('titulo', "Publicación de Banners");
    }

    public function uploadimgAction(){
        $numero = $this->Mercurio26->maximum("numero")+1;
        $path = "public/img/portal/";
        $this->uploadFile("inputimg",getcwd()."/$path/");
        $mercurio26 = new Mercurio26();
        $mercurio26->setNumero($numero);
        $mercurio26->setTipo("2");
        $mercurio26->setEstado("A");
        $mercurio26->setCodcaj("CCF032");
        $mercurio26->setNomimg($_FILES["inputimg"]["name"]);
        if(!$mercurio26->save()){
            parent::setLogger($mercurio26->getMessages());
            Message::error("No se guardo la imagen");
        }else
            Message::success("La imagen fue guardada correctamente");
            return $this->routeTo("action: index");

    }
    
    public function gestionBanners_viewAction(){
		$this->setParamToview('titulo', "Gestión de Notícias");
		$estado = array('A' => 'Activo', 'I' => 'Inactivo');
		$html = "";
		$html .= "<table class='resultado-consul' border=1>";
		$html .= "<thead>";
		$html .= "<th>No. Banner</th>";
		$html .= "<th>Tipo</th>";
		$html .= "<th>Estado</th>";
		$html .= "<th>Click para ver</th>";
		$html .= "<th>Publicar</th>";
		$html .= "<th>No publicar</th>";
		$html .= "</thead>";
		$html .= "<tbody>";
		$mercurio26 = $this->Mercurio26->find();

		foreach($mercurio26 as $mmercurio26){
		  $html .= "<tr>";
		  $html .= "<td>{$mmercurio26->getNumero()}</td>";
		  $html .= "<td>{$mmercurio26->getTipo()}</td>";
		  $html .= "<td>{$estado[$mmercurio26->getEstado()]}</td>";
		  $html .= "<td style='cursor: pointer' onclick=\"verBanimg('{$mmercurio26->getNumero()}','{$mmercurio26->getNomimg()}')\">{$mmercurio26->getNomimg()}</td>";
          if($mmercurio26->getEstado()=="I")
		    $html .= "<td onclick=\"publicar('{$mmercurio26->getNumero()}','A');\">".Tag::image("desktop/security-high.png", "width: 25px", "height: 25px", "style: cursor: pointer;")."</td>";
          else
		    $html .= "<td>&nbsp;</td>";
          if($mmercurio26->getEstado()=="A")
		    $html .= "<td onclick=\"publicar('{$mmercurio26->getNumero()}','I');\">".Tag::image("desktop/security-low.png", "width: 25px", "height: 25px", "style: cursor: pointer;")."</td>";
          else
		    $html .= "<td>&nbsp;</td>";
		  $html .= "</tr>";
		}

		$html .= "</tbody>";
		$html .= "</table>";
		$this->setParamToView("html", $html);
	}

    public function verbannersAction(){
        $this->setResponse("ajax");
        $ruta = $this->getPostParam("ruta");
        $lista = "";
        $lista .= "<img width='100%' border='1' src='../../public/img/portal/{$ruta}'/>";
        return $this->renderText(json_encode($lista));
    }

    
    public function publicarAction(){
		$this->setResponse("ajax");
        $id = $this->getPostParam("banner");
        $estado = $this->getPostParam("estado");
		$this->Mercurio26->updateAll("estado='$estado'","conditions: numero = '$id'");
		return $this->renderText(json_encode(true));
	}

}
?>
