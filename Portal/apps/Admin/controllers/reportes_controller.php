<?php

class ReportesController extends ApplicationController {

    public function initialize(){
        $this->setTemplateAfter('escritorio');
        $this->setPersistance(true);
    }

    public function indexAction(){
      $this->setParamToView("titulo","Reportes");
    }
    
    public function cobroMovimiento_viewAction(){
      $this->setParamToView("titulo","Reportes de Cobro");
    }

    public function cobroMovimientoAction(){
      $today = new Date();
      $title = "Cobro";
      $fecini = $this->getPostParam("fecini");
      $fecfin = $this->getPostParam("fecfin");
      $report = new UserReportPdf($title,array(),"P","A4");
      $report->startReport();
      $report->setX(15);
      $report->Ln();
      $report->Ln();
      $report->setX(15);
      $report->__Cell(90,4,"Opcion",1,0,"C",0,0);
      $report->__Cell(50,4,"Cantidad",1,0,"C",0,0);
      $report->__Cell(50,4,"Valor",1,1,"C",0,0);
      $mercurio20 = $this->Mercurio20->findAllBySql("SELECT mercurio20.controlador,mercurio20.accion,count(*) as id FROM mercurio20,mercurio25 WHERE mercurio25.controlador=mercurio20.controlador AND mercurio25.accion=mercurio20.accion AND mercurio20.fecha>='$fecini' AND mercurio20.fecha<='$fecfin' GROUP BY mercurio20.controlador,mercurio20.accion");
      foreach($mercurio20 as $mmercurio20){
        $mmercurio25 = $this->Mercurio25->findFirst("controlador = '{$mmercurio20->getControlador()}' AND accion='{$mmercurio20->getAccion()}'");
        $report->setX(15);
        $report->__Cell(90,4,$mmercurio20->getControlador()." ".$mmercurio20->getAccion(),0,0,"C",0,0);
        $report->__Cell(50,4,$mmercurio20->getId(),0,0,"C",0,0);
        $report->__Cell(50,4,number_format($mmercurio20->getId()*$mmercurio25->getValor(),2,",","."),0,1,"C",0,0);
        
      }
      $file = "public/temp/reportes";
      ob_end_clean();
      echo $report->FinishReport($file);
      $this->setResponse('view');
    }
    

}
?>
