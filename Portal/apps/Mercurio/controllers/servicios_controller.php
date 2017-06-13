<?php

class ServiciosController extends ApplicationController {

    public function indexAction(){
      $this->setResponse("ajax");
      echo parent::showTitle('Bienvenidos a Mercurio');
    }
    
    public function conser_viewAction(){
      $this->setResponse("ajax");
      echo parent::showTitle('Consulta de Servicios');
      $response = "";
      $response .= "<br>";
      $response .="<div class='resultado-prin'>";
      $response .="<table class='resultado-sec' border=1>";
      $response .="<tr class='tr-result' cellspacing='10'>";
      $response .="<td>Nombre</td>";
      $response .="<td>Direccion</td>";
      $response .="<td>Telefono</td>";
      $response .="<td>Email</td>";
      $response .="<td>Horario</td>";
      $response .="<td>&nbsp;</td>";
      $response .="</tr>";
      $response .="<tr>";
      $result = parent::webService("conparques",array());
      if($result!=false){
        foreach($result as $mresult){
          $response .="<tr>";
          $response .="<td>{$mresult['nombre']}</td>";
          $response .="<td>{$mresult['direccion']}</td>";
          $response .="<td>{$mresult['telefono']}</td>";
          $response .="<td>{$mresult['email']}</td>";
          $response .="<td>{$mresult['horario']}</td>";
          $response .="<td onclick=\"mostrar_servicios('{$mresult['codigo']}')\"><span class='link-vermas'>Ver Servicios</span></td>";
          $response .="</tr>";
        }
      }
      $response .="</tr>";
      $response .="</table>";
      $response .="</div>";
      echo $response;
    }

    public function detalleServicioAction(){
      $this->setResponse("ajax");
      $codigo = $this->getPostParam("codigo");
      $response ="";
      $response .="<table class='resultado-sec' border='1' style='width: 80%; margin: auto;'>";
      $response .="<tr class='tr-result' cellspacing='10'>";
      $response .="<td>Servicios</td>";
      $response .="</tr>";
      $servicios = parent::webService("servicios",array("codpar"=>"{$codigo}"));
      if($servicios!=false){
        foreach($servicios as $mservicios){
          $response .="<tr>";
          $response .="<td>{$mservicios['servicios']}</td>";
          $response .="</tr>";
        }
      }
      $response .="</table>";
      return $this->renderText(json_encode($response));
    }
    
}
?>
