<?php

class TarjetasController extends ApplicationController {

    public function indexAction(){
      $this->setResponse("ajax");
      echo parent::showTitle('Bienvenidos a Mercurio');
    }
    
    public function tarmov_viewAction(){
      $this->setResponse("ajax");
      echo parent::showTitle('Consulta de Movimientos de Tarjetas');
    }
    
    public function tarsal_viewAction(){
      $this->setResponse("ajax");
      echo parent::showTitle('Consulta de Saldo Tarjetas');
    }
    
    public function tarest_viewAction(){
      $this->setResponse("ajax");
      echo parent::showTitle('Consulta de Estados de Tajetas');
    }

    public function prueba_huilaAction(){
      //$this->setTemplateAfter('escritorio');
      echo parent::showTitle('Consulta de Estados de Tajetas');
      $response = "";
      $response .="<div class='resultado-prin'>";
      $response .="<table class='resultado-sec' border=1>";
      $response .="<tr class='tr-result' cellspacing='10'>";
      $response .="<td>Fecha</td>";
      $response .="<td>Detalle</td>";
      $response .="<td>Valor</td>";
      $response .="</tr>";
      $total=0;
      $param = array("appId"=>"5664c5165b1e11bc56416d9e0260eae4","appPwd"=>"fec1de72b37939cb649179a046647b7c","tipoDocumento"=>"1","identificacion"=>"7729494");
      $result = parent::webService2("BuscarTarjetas", $param);
      if($result!=false){
          $numtar = $result->BuscarTarjetasResult->Tarjeta->NumeroTarjeta;
        $param = array("appId"=>"5664c5165b1e11bc56416d9e0260eae4","appPwd"=>"fec1de72b37939cb649179a046647b7c","NumeroTarjeta"=>"$numtar","FechaInicial"=>"2015-01-01","FechaFinal"=>"2015-05-01");
          $result = parent::webService2("BuscarTransaccionesTarjetas", $param);
          if($result!=false){
            $movimientos = $result->BuscarTransaccionesTarjetasResult->MovimientoTarjeta;
            foreach($movimientos as $mmovimientos){
                $response .="<tr >";
                $response .="<td>{$mmovimientos->FechaTransaccion}</td>";
                $response .="<td>{$mmovimientos->NumeroDispositivo->Detalle}</td>";
                $response .="<td>".number_format($mmovimientos->ValorMovimiento,0,".",".")."</td>";
                $total+=$mmovimientos->ValorMovimiento;
                $response .="</tr>";
            }
          }
      }
    $response .="<tr >";
    $response .="<td colspan=2>Total</td>";
    $response .="<td>".number_format($total,0,".",".")."</td>";
    $response .="</tr>";
      $response .="</table>";

      $response .= "</div>";
      echo $response;
    }
    
}
?>
