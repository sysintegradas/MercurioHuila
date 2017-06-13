<?php

class CreditosController extends ApplicationController {

    public function indexAction(){
      $this->setResponse("ajax");
      echo parent::showTitle('Bienvenidos a Mercurio');
    }
    
    public function simulador_viewAction(){
      $this->setResponse("ajax");
      echo parent::showHelp('<p>La proyección realizada con este simulador es una aproximación, los valores pueden variar según el comportamiento de las tasas de interés. Para usar el simulador de créditos puede seguir los pasos descritos a continuación en el orden que usted prefiera:</p>');
      $result = parent::webService("lineasCredito", array());
      foreach($result as $mlineas){
        $lineas[$mlineas['tipcre']] = $mlineas['detalle'];
      }
      $this->setParamToView("lineas",$lineas);
      Tag::displayTo("codcat", Session::getDATA('codcat'));
      echo parent::showTitle('Simulador Créditos');
    }
    
    public function simuladorAction(){
      $this->setResponse("ajax");
      $tipcre = $this->getPostParam("tipcre");
      $codcat = $this->getPostParam("codcat");
      $valsim = $this->getPostParam("valsim");
      $cuotas = $this->getPostParam("cuotas");
      $mvalsim = $valsim;
      $response = "";
      $response .="<div class='title-result'>Simulación</div>";
      $response .="<div class='resultado-prin'>";
      $response .="<table class='resultado-sec'  border=1>";
      $response .="<tr class='tr-result' cellspacing='10'>";
      $response .="<td>Cuota</td>";
      $response .="<td>Vencimiento</td>";
      $response .="<td>Valor Crédito</td>";
      $response .="<td>Valor Interés</td>";
      $response .="<td>Total</td>";
      $response .="<td>Saldo</td>";
      $response .="</tr>";
      $total_cap=0;
      $total_int=0;
      $total=0;
      $result = parent::webService("simulador", array("tipcre"=>$tipcre,"codcat"=>$codcat,"valsim"=>$valsim,"cuotas"=>$cuotas));
      if($result!=false){
        foreach($result as $mresult){
          $response .="<tr>";
          $response .="<td>{$mresult['cuota']}</td>";
          $response .="<td>{$mresult['vencimiento']}</td>";
          $response .="<td>$ ".number_format($mresult['valcre'],0,".",".")."</td>";
          $response .="<td>$ ".number_format($mresult['valint'],0,".",".")."</td>";
          $response .="<td>$ ".number_format($mresult['total'],0,".",".")."</td>";
          $response .="<td>$ ".number_format($valsim-$mresult['valcre'],0,".",".")."</td>";
          $response .="</tr>";
          $valsim -=$mresult['valcre'];
          $total_cap +=$mresult['valcre'];
          $total_int +=$mresult['valint'];
          $total +=$mresult['total'];
        }
      }
      $response .="<tr>";
      $response .="<td colspan=2>Total</td>";
      $response .="<td>$ ".number_format($total_cap,0,".",".")."</td>";
      $response .="<td>$ ".number_format($total_int,0,".",".")."</td>";
      $response .="<td>$ ".number_format($total,0,".",".")."</td>";
      $response .="</tr>";
      $response .="</table>";
      $response .="</div>";
      Session::setData('nota_audit',"Tipo de Credito $tipcre con valor de ".number_format($mvalsim,0,".",".")." y cuotas de $cuotas");
      $log_id = parent::registroOpcion(true);
      $asunto = "Nueva Simulacion de Creditos ".Session::getData("nombre");
      $msg = "Cordial Saludos<br><br>acaban de simular un credito por un valor de $ ".number_format($mvalsim,0,".",".")." a $cuotas cuotas del sr(a) ".Session::getData("nombre")." identificado con el n&uacute;mero ".Session::getData("documento")." <br><br>Atentamente,<br><br>MERCURIO";
      $ruta_file = "";
      $controller = Router::getController();
      $action = Router::getAction();
      $mercurio11 = $this->Mercurio11->findFirst("tipo = '".Session::getDATA('tipo')."' and url = '{$controller}/{$action}_view'");
      $mercurio10 = $this->Mercurio10->findFirst("codcaj = '".Session::getDATA('codcaj')."' and codare = '{$mercurio11->getCodare()}' AND codope = '{$mercurio11->getCodope()}'");
      parent::enviarCorreo("Simulacion de Creditos",Session::getData("nombre"), $mercurio10->getEmail(), $asunto, $msg, $ruta_file);
      return $this->renderText(json_encode($response));
    }
    
    public function reporteSimuladorAction(){
      $tipcre = $this->getPostParam("tipcre");
      $codcat = $this->getPostParam("codcat");
      $valsim = $this->getPostParam("valsim");
      $mvalsim = $valsim;
      $cuotas = $this->getPostParam("cuotas");
      $today = new Date();
      $title = "Simulacion de Credito";
      $report = new UserReportPdf($title,array(),"P","A4");
      $report->startReport();
      $report->Ln();
      $report->SetFont("Arial", "", "10");
      $report->__Cell(20,6,"Cedula: ",0,0,"R",0,0);
      $report->__Cell(20,6,Session::getDATA('documento'),0,0,"L",0,0);
      $report->__Cell(20,6,"Nombre: ",0,0,"R",0,0);
      $report->__Cell(70,6,Session::getDATA('nombre'),0,1,"L",0,0);
      $report->__Cell(20,6,"Categoria: ",0,0,"R",0,0);
      $report->__Cell(20,6,$codcat,0,0,"L",0,0);
      $report->__Cell(20,6,"Cuotas: ",0,0,"R",0,0);
      $report->__Cell(20,6,$cuotas,0,1,"L",0,0);
      $report->__Cell(20,6,"Valor: ",0,0,"R",0,0);
      $report->__Cell(20,6,"$ ".number_format($valsim,0,".","."),0,0,"L",0,0);
      $report->Ln();
      $report->Ln();
      $report->Ln();
      $report->SetFillColor(70,130,180);
      $report->SetTextColor(255);
      $report->SetFont("Arial", "B", "9");
      $report->__Cell(15,6,"Cuota",1,0,"C",1,0);
      $report->__Cell(35,6,"Fecha Vencimiento",1,0,"C",1,0);
      $report->__Cell(35,6,"Valor Capital",1,0,"C",1,0);
      $report->__Cell(35,6,"Valor Interes",1,0,"C",1,0);
      $report->__Cell(35,6,"Total",1,0,"C",1,0);
      $report->__Cell(35,6,"Saldo",1,1,"C",1,0);
      $report->SetFont("Arial", "", "8");
      $report->SetTextColor(0);
      $result = parent::webService("simulador", array("tipcre"=>$tipcre,"codcat"=>$codcat,"valsim"=>$valsim,"cuotas"=>$cuotas));
      $total = 0;
      $total_cap = 0;
      $total_int = 0;
      if($result!=false){
        $reg = 0;
        foreach($result as $mresult){
          
          if($reg%2==0)
            $report->SetFillColor(245,255,255);
          else
            $report->SetFillColor(255);
          $valsim -=$mresult['valcre'];
          $report->__Cell(15,4,$mresult['cuota'],1,0,"C",1,0);
          $report->__Cell(35,4,$mresult['vencimiento'],1,0,"C",1,0);
          $report->__Cell(35,4,"$ ".number_format($mresult['valcre'],0,".","."),1,0,"R",1,0);
          $report->__Cell(35,4,"$ ".number_format($mresult['valint'],0,".","."),1,0,"R",1,0);
          $report->__Cell(35,4,"$ ".number_format($mresult['total'],0,".","."),1,0,"R",1,0);
          $report->__Cell(35,4,"$ ".number_format($valsim,0,".","."),1,1,"R",1,0);
          $total_cap +=$mresult['valcre'];
          $total_int +=$mresult['valint'];
          $total +=$mresult['total'];
          $reg++;
        }
      }
      $report->SetFont("Arial", "B", "9");
      $report->SetFillColor(70,130,180);
      $report->SetTextColor(255);
      $report->__Cell(50,6,"Total",1,0,"R",1,0);
      $report->SetTextColor(0);
      $report->__Cell(35,6,"$ ".number_format($total_cap,0,".","."),1,0,"R",0,0);
      $report->__Cell(35,6,"$ ".number_format($total_int,0,".","."),1,0,"R",0,0);
      $report->__Cell(35,6,"$ ".number_format($total,0,".","."),1,0,"R",0,0);
      $report->__Cell(35,6,"",1,1,"R",0,0);
      $report->Ln();
      $report->Ln();
      $report->SetFont("Arial", "", "10");
      $report->__Cell(0,4,"Esta simulacion se expide a solicitud del interesado el dia {$today->getDay()} de {$today->getMonthName()} de {$today->getYear()} en el portal Mercurio. ",0,0,"L",0,0);
      $file = "public/temp/reportes";
      
      $asunto = "Nuevo Reporte de Simulacion de Creditos ".Session::getData("nombre");
      $msg = "Cordial Saludos<br><br>acaban de generar el reporte de simular un credito por un valor de $ ".number_format($mvalsim,0,".",".")." a $cuotas cuotas del sr(a) ".Session::getData("nombre")." identificado con el n&uacute;mero ".Session::getData("documento")." <br><br>Atentamente,<br><br>MERCURIO";
      $ruta_file = "";
      $controller = Router::getController();
      $action = Router::getAction();
      $mercurio11 = $this->Mercurio11->findFirst("tipo = '".Session::getDATA('tipo')."' and url = '{$controller}/simulador_view'");
      $mercurio10 = $this->Mercurio10->findFirst("codcaj = '".Session::getDATA('codcaj')."' and codare = '{$mercurio11->getCodare()}' AND codope = '{$mercurio11->getCodope()}'");
      parent::enviarCorreo("Simulacion de Creditos",Session::getData("nombre"), $mercurio10->getEmail(), $asunto, $msg, $ruta_file);
      echo $report->FinishReport($file);
      $this->setResponse('view');
      Session::setData('nota_audit',"Tipo de Credito $tipcre con valor de ".number_format($mvalsim,0,".",".")." y cuotas de $cuotas");
      $log_id = parent::registroOpcion(true);
      
      
    }
    
    public function concre_viewAction(){
      $estado = array('C'=>'Contabilizado','X'=>'Anulado','G'=>'Generado');
      $this->setResponse("ajax");
      echo parent::showTitle('Consulta de Creditos');
      echo parent::showHelp('Realice una revisión al historial de créditos que usted ha realizado');
      $result = parent::webService("concre", array("numdoc"=>"40941274")); //Session::getDATA('documento')
      //$documento=Session::getDATA('documento');
      //$result = parent::webService("concre", array("numdoc"=>$documento)); //Session::getDATA('documento')
      $response = "";
      $response = "<br>";
      $response .="<div class='resultado-prin'>";
      $response .="<table class='resultado-sec' border=1>";
      $response .="<tr class='tr-result' cellspacing='10'>";
      $response .="<td>Crédito</td>";
      $response .="<td>Fecha</td>";
      $response .="<td>Cuotas</td>";
      $response .="<td>Valor Crédito</td>";
      $response .="<td>Valor Capital</td>";
      $response .="<td>Valor Interés</td>";
      $response .="<td>Saldo Capital</td>";
      $response .="<td>Saldo Interés</td>";
      $response .="<td>Saldo Total</td>";
      $response .="<td>Estado</td>";
      $response .="<td>&nbsp;</td>";
      $response .="</tr>";
      if($result!=false){
        foreach($result as $mresult){
          $response .="<tr>";
          $response .="<td>{$mresult['documento']}</td>";
          $response .="<td>{$mresult['fecha']}</td>";
          $response .="<td>{$mresult['cuotas']}</td>";
          $response .="<td>$ ".number_format($mresult['valcre'],0,".",".")."</td>";
          $response .="<td>$ ".number_format($mresult['pagcap'],0,".",".")."</td>";
          $response .="<td>$ ".number_format($mresult['pagint'],0,".",".")."</td>";
          $response .="<td>$ ".number_format($mresult['salcap'],0,".",".")."</td>";
          $response .="<td>$ ".number_format($mresult['salint'],0,".",".")."</td>";
          $response .="<td>$ ".number_format($mresult['saldo'],0,".",".")."</td>";
          $response .="<td>".$estado[$mresult['estado']]."</td>";
          $response .="<td onclick=\"mostrar_pagos('{$mresult['documento']}')\"><span class='link-vermas'>Ver Pagos</span></td>";
          $response .="</tr>";
        }
      }else{
        $response .= "<tr>";
        $response .= "<td colspan='11'>No hay información de créditos para mostrar</td>";
        $response .= "</tr>";
      }
      $response .="</table>";
      echo $response;
      $log_id = parent::registroOpcion(true);
    }

    public function detalleCreditoAction(){
    	$this->setResponse("ajax");
    	$doccre = $this->getPostParam("credito");
    	$pagos = parent::webService("concrepag", array("numdoc"=>"85082928"/*Session::getDATA('documento')*/,"documento"=>"{$doccre}"));
  		$response ="";
  		$response .="<table class='resultado-sec window-emer' border=1>";
  		$response .="<tr class='tr-result' cellspacing='10'>";
  		$response .="<td>Fecha</td>";
  		$response .="<td>Abono Capital</td>";
  		$response .="<td>Abono Interes</td>";
  		$response .="</tr>";
  		if($pagos!=false){
  			$totalcap = 0;
  			$totalint = 0;
  			
  			foreach($pagos as $mpagos){
  			  $response .="<tr>";
  			  $response .="<td>{$mpagos['fecha']}</td>";
  			  $response .="<td>$ ".number_format($mpagos['valcap'],0,".",".")."</td>";
  			  $response .="<td>$ ".number_format($mpagos['valint'],0,".",".")."</td>";
  			  $response .="</tr>";
  			  $totalcap +=$mpagos['valcap'];
  			  $totalint +=$mpagos['valint'];
  			}
  			
  			$response .="<tr>";
  			$response .="<td><b>Total</b></td>";
  			$response .="<td>$ ".number_format($totalcap,0,".",".")."</td>";
  			$response .="<td>$ ".number_format($totalint,0,".",".")."</td>";
  			$response .="</tr>";
  		}
  		$response .="</table>";
  		return $this->renderText(json_encode($response));
    }
    
    public function reporteConcreAction(){
      $today = new Date();
      $title = "Consulta de Credito";
      $report = new UserReportPdf($title,array(),"P","A4");
      $report->startReport();
      $report->Ln();
      $report->SetFont("Arial", "", "10");
      $report->__Cell(20,6,"Cedula: ",0,0,"R",0,0);
      $report->__Cell(20,6,Session::getDATA('documento'),0,0,"L",0,0);
      $report->__Cell(20,6,"Nombre: ",0,0,"R",0,0);
      $report->__Cell(70,6,Session::getDATA('nombre'),0,1,"L",0,0);
      $report->Ln();
      $report->SetFillColor(70,130,180);
      $report->SetTextColor(255);
      $report->SetFont("Arial", "B", "9");
      $report->__Cell(15,6,"Credito",1,0,"C",1,0);
      $report->__Cell(18,6,"Fecha",1,0,"C",1,0);
      $report->__Cell(13,6,"Cuotas",1,0,"C",1,0);
      $report->__Cell(25,6,"Valor Creditos",1,0,"C",1,0);
      $report->__Cell(25,6,"Valor Capital",1,0,"C",1,0);
      $report->__Cell(25,6,"Valor Interes",1,0,"C",1,0);
      $report->__Cell(25,6,"Saldo Capital",1,0,"C",1,0);
      $report->__Cell(25,6,"Saldo Interes",1,0,"C",1,0);
      //$report->__Cell(25,6,"Saldo Total",1,0,"C",1,0);
      $report->__Cell(18,6,"Estado",1,1,"C",1,0);
      
      $report->SetFont("Arial", "", "8");
      $report->SetTextColor(0);
      $result = parent::webService("concre", array("numdoc"=>"85082928"));//Session::getDATA('documento')
      if($result!=false){
        $reg = 0;
        foreach($result as $mresult){
          if($reg%2==0)
            $report->SetFillColor(245,255,255);
          else
            $report->SetFillColor(255);
          $report->__Cell(15,4,$mresult['documento'],1,0,"C",1,0);
          $report->__Cell(18,4,$mresult['fecha'],1,0,"C",1,0);
          $report->__Cell(13,4,$mresult['cuotas'],1,0,"C",1,0);
          $report->__Cell(25,4,"$ ".number_format($mresult['valcre'],0,".","."),1,0,"R",1,0);
          $report->__Cell(25,4,"$ ".number_format($mresult['pagcap'],0,".","."),1,0,"R",1,0);
          $report->__Cell(25,4,"$ ".number_format($mresult['pagint'],0,".","."),1,0,"R",1,0);
          $report->__Cell(25,4,"$ ".number_format($mresult['salcap'],0,".","."),1,0,"R",1,0);
          $report->__Cell(25,4,"$ ".number_format($mresult['salint'],0,".","."),1,0,"R",1,0);
          //$report->__Cell(25,4,"$ ".number_format($mresult['saldo'],0,".","."),1,0,"R",1,0);
          $report->__Cell(18,4,$mresult['estado'],1,1,"R",1,0);
          $reg++;
        }
      }
      $report->Ln();
      $report->SetFont("Arial", "", "10");
      $report->__Cell(0,4,"Esta Consulta se expide a solicitud del interesado el dia {$today->getDay()} de {$today->getMonthName()} de {$today->getYear()} en el portal Mercurio. ",0,0,"L",0,0);
      $file = "public/temp/reportes";
      echo $report->FinishReport($file);
      $this->setResponse('view');
      $log_id = parent::registroOpcion(true);
    }
    
    public function formulario_viewAction(){
      $this->setResponse("ajax");
      echo parent::showTitle('Descargar Formulario');
      
    }
    
}
?>
