<?php

class SubsidioController extends ApplicationController {

    public function indexAction(){
      $this->setResponse("ajax");
    }
//DATOS EMPRESAS    
    public function certificadoAfiliacionEmpresa_viewAction(){
        $this->setResponse("ajax");
        echo parent::showTitle('Certificado de Afiliación');
    }
// Certificado de afiliacion de empresas
    public function certificadoAfiliacionEmpresaAction(){
        $this->setResponse("ajax");
        if(Session::getData('estado') == "I" AND Session::getData('codest') == "4118"){ 
            $response = parent::errorFunc("Las empresas expulsada no tiene permiso de descargar algun certificado");
            return $this->renderText(json_encode($response));
        }
        $perini = $this->getPostParam("perini","addslaches","alpha","extraspaces","striptags");
        $perfin = $this->getPostParam("perfin","addslaches","alpha","extraspaces","striptags");
        $fecha = new Date();
        if($perini>$fecha->getYear().$fecha->getMonth()){
            $formu = parent::errorFunc("El periodo inicial no puede ser futuro");
            return $this->renderText(json_encode($formu)); 
        }
        $formu = new FPDF('P','mm','A4');
        $formu->AddPage();
        $formu->AddFont('Calibri','','Calibri.php');
        $formu->AddFont('Calibri','BI','Calibri Bold Italic.php');
        $formu->AddFont('Calibri','I','Calibri Italic.php');
        $formu->SetFillColor(236,248,240); 
        $formu->SetFont('Calibri','I','12');
        $formu->SetMargins(15,20,15);
        $formu->Cell(15,4,"",0,1,"L",0,0);
        $formu->Image('public/img/comfamiliar-logo.jpg',5,5,77,20);
        $idmer20 = $this->Mercurio20->findBySql("SELECT MAX(id) as id FROM mercurio20");
        $mercurio20 = $this->Mercurio20->findBySql("SELECT * FROM mercurio20 WHERE id='{$idmer20->getId()}'");
        $fecha20 = $mercurio20->getFecha();
        $hora20 = $mercurio20->getHora();
        $codbar = $idmer20->getId()."-".$mercurio20->getDocumento();
        $formu->Codabar(120,10,$codbar);
        $formu->SetFont('Calibri','BI','12');
        $formu->Cell(0,24,html_entity_decode(""),0,1,"C",0,0);
        $formu->Ln();
        $formu->SetFont('Calibri','IB','12');
        $formu->Cell(190,4,html_entity_decode("LA COORDINADORA DE RECAUDO DE APORTES"),0,1,"C",0,0);
        $formu->Cell(190,4,"DE COMFAMILIAR HUILA",0,0,"C",0,0);
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Cell(190,4,"CERTIFICA QUE",0,0,"C",0,0);
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->SetFont('Calibri','','11');
        if(Session::getDATA('estado')=='A'){
            $estado= "Activo";
        }else{
            $estado= "Inactivo";
        }
        $maxper=0;
        $minper=0;
        $valapo=0;
        $porapo=0;
        $result = parent::webService("Aporte",array('nit'=>Session::getDATA('documento'),'perini'=>$perini,'perfin'=>$perfin));
        if($result != false){
            foreach($result as $mresult){
                $porapo =  $mresult['porapo']; 
                if($minper==0) $minper = $mresult['periodo']; 
                $per = $mresult['periodo'];
                $valapo = round($mresult['valapo']);
                if($maxper <= $per){
                    $maxper = $per;
                    $valapo = round($mresult['valapo']);
                }
            }
        }else{
            //$formu = parent::errorFunc("No presenta aportes en este rango de periodos");
            //return $this->renderText(json_encode($formu)); 
        }
        //$formu->MultiCell(190,4,html_entity_decode("La empresa ".Session::getDATA('nombre')."  identificada con Nit. No ".number_format(Session::getDATA('documento'),0,'.','.').", se encuentra en estado ".$estado." en nuestro sistema y cancel&oacute; Aportes Parafiscales del (".number_format($porapo,0,'.','.')."%) ".Session::getDATA('nomcaj')." por los periodos de ".substr($minper,0,4)."-".substr($minper,4,2)." hasta ".substr($maxper,0,4)."-".substr($maxper,4,2)),0,"J",0);
        if($result != false){
        $formu->MultiCell(0,4,html_entity_decode("La empresa ".Session::getDATA('nombre')."  identificada con Nit. ".number_format(Session::getDATA('documento'),0,'.','.').", se encuentra en estado ".$estado." en nuestro sistema y cancel&oacute; Aportes Parafiscales por los periodos de $minper a $maxper ".Session::getDATA('nomcaj').", as&iacute;:"),0,"J",0);
        }else{
            if($minper == "0"){
                $minper = "$perini";
            }
            if($maxper == "0"){
                $maxper = "$perfin";
            }
            $formu->MultiCell(0,4,html_entity_decode("La empresa ".Session::getDATA('nombre')."  identificada con Nit. No ".number_format(Session::getDATA('documento'),0,'.','.').", se encuentra en estado ".$estado." en nuestro sistema y no cancel&oacute; Aportes Parafiscales  por los periodos de $minper a $maxper ".Session::getDATA('nomcaj').""),0,"J",0);
        }
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->SetFont('Calibri','I','9');
        if($result != false){
            $formu->Cell(0,4,"Anexo: Kardex de aportes",0,1,"L",0,0);
        }
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->MultiCell(0,4,html_entity_decode('"La expedici&oacute;n de este documento no impide que Comfamiliar Huila pueda realizar visitas de verificaci&oacute;n de la correcta y oportuna liquidaci&oacute;n de los aportes, bases de liquidaci&oacute;n y el pago oportuno de los mismos. (Decreto 562/90).'),0,1,"L",0,0);
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Cell(0,4,html_entity_decode('"ESTE CERTIFICADO NO ES VALIDO PARA EFECTOS DE AFILIACION A OTRA CAJA DE COMPENSACI&Oacute;N."'),0,1,"L",0,0);
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Cell(0,4,"La presente se expide a los {$fecha->getDay()} dias de {$fecha->getMonthName()} de {$fecha->getYear()} ",0,1,"L",0,0);
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $y = $formu->getY(); 
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->SetFont('Calibri','BI','9');
        $formu->Image('public/img/firmas/firmaMariaIsabelMariaIsabel.png',85,$y+5,35,15);
        $formu->Cell(0,4,"MARIA ISABEL DIAZ GARZON",0,1,"C",0,0);
        $formu->Cell(0,4,"COORDINADORA RECAUDO DE APORTES",0,1,"C",0,0);
        $formu->Image('public/img/portal/piepaginaCartas.png',0,255,210,42);
        $formu->Ln();

        $formu->AddPage();
        $formu->SetFillColor(236,248,240); 
        $formu->SetFont('Calibri','I','12');
        $formu->SetMargins(15,20,15);
        $formu->Cell(15,4,"",0,1,"L",0,0);
        $formu->Image('public/img/comfamiliar-logo.jpg',5,5,77,20);
        $idmer20 = $this->Mercurio20->findBySql("SELECT MAX(id) as id FROM mercurio20");
        $mercurio20 = $this->Mercurio20->findBySql("SELECT * FROM mercurio20 WHERE id='{$idmer20->getId()}'");
        $fecha20 = $mercurio20->getFecha();
        $hora20 = $mercurio20->getHora();
        $codbar = $idmer20->getId()."-".$mercurio20->getDocumento();
        $formu->Codabar(120,10,$codbar);
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->SetFont('Calibri','I','12');
        $formu->Cell(0,4,html_entity_decode("LA COORDINADORA DE RECAUDO DE APORTES"),0,1,"C",0,0);
        $formu->Cell(0,4,"DE COMFAMILIAR HUILA",0,0,"C",0,0);
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Cell(0,4,"KARDEX DE APORTES",0,0,"C",0,0);
        $formu->Ln();
        $formu->Ln();
        $formu->setX(10);
        $formu->SetFont('Calibri','I','10');
        $formu->Cell(5,7,"#",1,0,"C",0,0);
        $formu->Cell(24,7,"Comprobante",1,0,"C",0,0);
        $formu->Cell(17,7,"Recibo",1,0,"C",0,0);
        $formu->Cell(17,7,"Periodo",1,0,"C",0,0);
        $formu->Cell(25,7,"Nomina",1,0,"C",0,0);
        $formu->Cell(25,7,"Aporte",1,0,"C",0,0);
        $formu->Cell(21,7,"Mora",1,0,"C",0,0);
        $formu->Cell(17,7,"Indice",1,0,"C",0,0);
        $formu->Cell(20,7,"Empleados",1,0,"C",0,0);
        $formu->Cell(20,7,"Fecha Pago",1,1,"C",0,0);
        if($result != false){
            $orden = 1;
            $l = 1;
            foreach($result as $mresult){
                if($mresult['porapo'] == ""){
                    $porapo = "0";
                }else{
                    $porapo = $mresult['porapo'];
                }
                $fecpag = date("Y-m-d",strtotime($mresult['fecpag']));
                if($mresult['valapo']=="")$mresult['valapo']=0;
                if($mresult['valint']=="")$mresult['valint']=0;
                if($mresult['valnom']=="")$mresult['valnom']=0;
                $formu->setX(10);
                $formu->SetFont('Calibri','I','8');
                $l++;
                $formu->Cell(5,7,$orden++,1,0,"C",0,0);
                $formu->Cell(24,7,$mresult['comprobante'],1,0,"C",0,0);
                //$formu->Cell(17,7,"",1,0,"C",0,0);
                $formu->Cell(17,7,$mresult['numrec'],1,0,"C",0,0);
                $formu->Cell(17,7,$mresult['periodo'],1,0,"C",0,0);
                $formu->Cell(25,7,"$ ".number_format($mresult['valnom'],0,'.','.'),1,0,"R",0,0);
                $formu->Cell(25,7,"$ ".number_format($mresult['valapo'],0,'.','.'),1,0,"R",0,0);
                $formu->Cell(21,7,"$ ".number_format($mresult['valint'],0,'.','.'),1,0,"R",0,0);
                $formu->Cell(17,7,number_format($porapo,0,'.','.'),1,0,"C",0,0);
                $formu->Cell(20,7,$mresult['traapo'],1,0,"C",0,0);
                $formu->Cell(20,7,$fecpag,1,1,"C",0,0);
                if($l>20){
                    $l=0;
                    $formu->AddPage();
                    $formu->SetFillColor(236,248,240); 
                    $formu->SetFont('Calibri','I','12');
        	        $formu->SetMargins(15,20,15);
                    $formu->Cell(15,4,"",0,1,"L",0,0);
                    $formu->Image('public/img/comfamiliar-logo.jpg',5,5,77,20);
                    $idmer20 = $this->Mercurio20->findBySql("SELECT MAX(id) as id FROM mercurio20");
                    $mercurio20 = $this->Mercurio20->findBySql("SELECT * FROM mercurio20 WHERE id='{$idmer20->getId()}'");
                    $fecha20 = $mercurio20->getFecha();
                    $hora20 = $mercurio20->getHora();
                    $codbar = $idmer20->getId()."-".$mercurio20->getDocumento();
                    $formu->Codabar(120,10,$codbar);
                    $formu->Ln();
                    $formu->Ln();
                    $formu->Ln();
                    $formu->Ln();
                    $formu->Ln();
                    $formu->Ln();
                    $formu->Ln();
                    $formu->SetFont('Calibri','I','12');
                    $formu->Cell(190,4,html_entity_decode("LA COORDINADORA DE GESTI&Oacute;N DE RECAUDO DE APORTES"),0,1,"C",0,0);
                    $formu->Cell(190,4,"DE COMFAMILIAR HUILA",0,0,"C",0,0);
                    $formu->Ln();
                    $formu->Ln();
                    $formu->Ln();
                    $formu->Ln();
                    $formu->Cell(190,4,"KARDEX DE APORTES",0,0,"C",0,0);
                    $formu->Ln();
                    $formu->Ln();
                    $formu->Ln();
                    $formu->setX(10);
                    $formu->SetFont('Calibri','I','10');
                    $formu->Cell(5,7,"#",1,0,"C",0,0);
                    $formu->Cell(24,7,"Comprobante",1,0,"C",0,0);
                    $formu->Cell(17,7,"Recibo",1,0,"C",0,0);
                    $formu->Cell(17,7,"Periodo",1,0,"C",0,0);
                    $formu->Cell(25,7,"Nomina",1,0,"C",0,0);
                    $formu->Cell(25,7,"Aporte",1,0,"C",0,0);
                    $formu->Cell(21,7,"Mora",1,0,"C",0,0);
                    $formu->Cell(17,7,"Indice",1,0,"C",0,0);
                    $formu->Cell(20,7,"Empleados",1,0,"C",0,0);
                    $formu->Cell(20,7,"Fecha Pago",1,1,"C",0,0);
                }
            }
        }
        $formu->Ln();
        $formu->Ln();
        $y = $formu->getY(); 
        $formu->Ln();
        $formu->Ln();
        $formu->SetFont('Calibri','I','9');
        $formu->Image('public/img/firmas/firmaMariaIsabelMariaIsabel.png',87,$y+2,35,15);
        $formu->Cell(180,4,"MARIA ISABEL DIAZ GARZON",0,1,"C",0,0);
        $formu->Cell(180,4,"COORDINADORA RECAUDO DE APORTES",0,1,"C",0,0);
        $formu->Image('public/img/portal/piepaginaCartas.png',0,268,210,30);
        $formu->Ln();


        $this->setResponse('view');
        $file = "public/temp/reportes/empresa_e".Session::getDATA('documento').".pdf";
        ob_clean();
        $formu->Output( $file,"F");
        $formu = parent::successFunc("Genera Formulario",$file);
        $this->renderText(json_encode($formu)); 
    }
    //Consulta de trabajadores
    public function conemptra_viewAction(){
        $this->setResponse("ajax");
        echo parent::showTitle('Consulta de Trabajadores de la Empresa');
    }
    public function conemptraAction(){
        try{
            $this->setResponse("ajax");
            $estad = $this->getPostParam('estado');
            $param = array("nit"=>Session::getDATA('documento'),"tipo"=>$estad);
            $response = '';
            $response .='<div style="margin-top: 4%">';
            $response .="<table class='table table-bordered'>";
            $response .="<tr>";
            $response .="<td > Los trabajadores con estado pendientes por legalizar afiliaci&oacute;n (P). Son trabajadores que deben presentar su afiliaci&oacute;n a esta Caja de Compensaci&oacute;n. </td>";
            $response .="</tr>";
            $response .="</table>";
            $response .="</br>";
            $response .="<table class='table table-bordered'>";
            $response .="<head>";
            $response .="<tr class='info' cellspacing='10'>";
            $response .="<td> # </td>";
            $response .="<td> C&eacute;dula </td>";
            $response .="<td>Nombre</td>";
            $response .="<td>Salario</td>";
            $response .="<td>Fecha Ingreso</td>";
            $response .="<td>Estado</td>";
            $response .="<td>Fecha Retiro</td>";
            if($estad != 'I')$response .="<td>Categoria</td>";
            $response .="</tr>";
            $response .="</head>";
            $response .="<tbody>";
            $result = parent::webService("Conemptra", $param);
            $total=1;
            if($result!=false){
                foreach($result as $mresult){
                    if($mresult['estado'] == $estad || $estad == 't'){
                        $salario = number_format($mresult['salario'],0,".",".");
                        $fecnac = date("Y-m-d",strtotime($mresult['fecnac']));
                        $fecafi = date("Y-m-d",strtotime($mresult['fecafi']));
                        if($mresult['estado']=="A")$estado='ACTIVO';
                        if($mresult['estado']=="I")$estado='INACTIVO';
                        if($mresult['estado']=="P")$estado='PENDIENTE LEGALIZAR';
                        $fecest="";
                        if($mresult['fecest']!="")
                            $fecest = date("Y-m-d",strtotime($mresult['fecest']));
                        $response .="<tr style='text-align: left;'>";
                        $response .="<td align='right'>".($total++)."</td>";
                        $response .="<td align='right'>{$mresult['cedtra']}</td>";
                        $response .="<td>{$mresult['nombre']}</td>";
                        $response .="<td align='right'>$ {$salario}</td>";
                        $response .="<td align='right'>{$fecafi}</td>";
                        $response .="<td style='text-align: left;'>{$estado}</td>";
                        $response .="<td>{$fecest}</td>";
                        if($estado=="ACTIVO")
                            $response .="<td align='left'>{$mresult['codcat']}</td>";
                        else
                            if($estado=="PENDIENTE LEGALIZAR")$response .="<td>&nbsp;</td>";
                        $response .="</tr>";
                    }
                }
            }
            $response .="</tbody>";
            $response .="</table>";
            $response .='</div>';
            return $this->renderText(json_encode($response));
        } catch(DbException $e){
            parent::setLogger($e->getMessage());
            $response = parent::errorFunc("No se encontr&oacute; el Postulante");
            return $this->renderText(json_encode($response));
        }
    }

    //Reporte en excel de trabajadores 
    public function conemptra_repAction($estado){
        $fecha = new Date();
        $this->setResponse('view');
        $file = "public/temp/"."reporte_trab".$fecha->getUsingFormatDefault().".xls";
        require_once "Library/Excel/Main.php";
        $excels = new Spreadsheet_Excel_Writer($file);
        $excel = $excels->addWorksheet();
        $column_title = $excels->addFormat(array('fontfamily' => 'Verdana',
                    'size' => 12,
                    'fgcolor' => 50,
                    'border' => 1,
                    'bordercolor' => 'black',
                    "halign" => 'center'
                    ));
        $title = $excels->addFormat(array(   'fontfamily' => 'Verdana',
                    'size' => 13,
                    'border' => 0,
                    'bordercolor' => 'black',
                    "halign" => 'center'
                    ));
        $column_style = $excels->addFormat(array(   'fontfamily' => 'Verdana',
                    'size' => 11,
                    'border' => 1,
                    'bordercolor' => 'black',
                    ));
        $excel->setMerge(0,0,0,6);

        if($estado=="A")$est='ACTIVO';
        if($estado=="I")$est='INACTIVO';
        if($estado=="P")$est='PENDIENTE';
        if($estado=="t")$est='TODOS';
        $excel->write(0,0,'Reporte De Trabajadores '.$est.' ',$title);
        if($estado == 'I')
            $columns = array('#','Cedula','Nombre','Salario','Fecha Ingreso','Estado','Fecha Retiro');
        else
            $columns = array('#','Cedula','Nombre','Salario','Fecha Ingreso','Estado','Fecha Retiro','Categoria');
        $excel->setColumn(0,0,10);
        $excel->setColumn(1,1,15);
        $excel->setColumn(2,2,45);
        $excel->setColumn(3,6,20);
        $i = 0;
        $j = 2;
        foreach($columns as $column){
            $value = ucfirst($column);
            $excel->write($j, $i++, $value, $column_title);
        }
        $j++;
        $param = array("nit"=>Session::getDATA('documento'),"tipo"=>$estado);
        $result = parent::webService("Conemptra", $param);
        if($result!=false){
            $total=1;
            foreach($result as $mresult){
                
                if($mresult['estado'] == $estado || $mresult['estado'] == 'P' || $estado == 't'){
                    $i = 0;
                    $salario = $mresult['salario'];
                    $fecafi = date("Y-m-d",strtotime($mresult['fecafi']));
                    $esta="";
                    if($mresult['estado']=="A")$esta='ACTIVO';
                    if($mresult['estado']=="I")$esta='INACTIVO';
                    if($mresult['estado']=="P")$esta='PENDIENTE LEGALIZAR';
                    $fecest="";
                    if($mresult['fecest']!="")
                        $fecest = date("Y-m-d",strtotime($mresult['fecest']));
                    $excel->write($j, $i++,$total++, $column_style);
                    $excel->write($j, $i++,$mresult['cedtra'], $column_style);
                    $excel->write($j, $i++,$mresult['nombre'], $column_style);
                    $excel->writeString($j, $i++,number_format($salario,0,'.','.'), $column_style);
                    $excel->write($j, $i++,$fecafi, $column_style);
                    $excel->write($j, $i++,$esta, $column_style);
                    $excel->write($j, $i++,$fecest, $column_style);
                    if($estado=="A")
                        $excel->write($j, $i++,$mresult['codcat'], $column_style);
                    //else
                        //$excel->write($j, $i++,"", $column_style);
                    $j++;
                }
/*
                else{
                    Debug::addVariable("a",$mresult['cedtra']);
                    Debug::addVariable("b",$estado);
                    Debug::addVariable("c",$mresult['estado']);
                    throw new DebugException(0);    
                }
*/
            }
        }
        $fecha = new Date();
        $excels->close();
        header("location: ".Core::getInstancePath()."/{$file}");
    }
    //Consulta de mora empresas
    public function moremp_viewAction(){

        $this->setResponse("ajax");
        echo parent::showTitle('Consulta Mora Real');
        $nit = Session::getDATA('documento');
        $response = "";
        $response .="<div class='col-sm-12'>";
        $response .="<div class='col-sm-6'>".Tag::Button("Generar PDF",'class: btn btn-success','onclick: downpdf()')."</div>";
        $response .="<div class='col-sm-6'>".Tag::Button("Generar Excel",'class: btn btn-success','onclick: downxls()')."</div>";
        $response .="</div>";
        $response .="<br>";
        $response .="<br>";
        $response .="<div>";
        $response .="<table class='table table-bordered' align='center'>";
        $response .="<thead>";
        $response .="<tr class='info' cellspacing='10'>";
        $response .="<th style='text-align:center;'>N&deg;. De visita </th>";
        $response .="<th style='text-align:center;'>N&deg;  De liquidaci&oacute;n </th>";
        $response .="<th style='text-align:center;'>Periodo</th>";
        $response .="<th style='text-align:center;'>Valor</th>";
        //$response .="<th style='text-align:center;'>Valor inicial</th>";
        $response .="<th style='text-align:center;'>Abonos</td>";
        $response .="<th style='text-align:center;'>Saldo a Total</th>";
        $response .="</tr>";
        $response .="</head>";
        $response .="<tbody>";
        $morosa = parent::webService("Morosa",array("nit"=>$nit));
        Debug::addVariable("a",$morosa);
        //throw new DebugException(0);    
        $totabo = 0;
        $totsal = 0;
        $totval = 0;
        if($morosa !=false){
            foreach($morosa as $mresult){
                $totval  = $totval + ($mresult['valor']) ;
                $totabo  = $totabo + ($mresult['abonos']) ;
                $saltot = $mresult['valor']-$mresult['abonos'];
                $totsal  = $totsal + $saltot ;
                //$mresult = preg_split("/-/",$mresult['periodo']);
                $response .="<tr>";
                $response .="<td>".$mresult['visita']."</td>";
                $response .="<td>".$mresult['liquidacion']."</td>";
                $response .="<td align='right'>{$mresult['periodo']}</td>";
                $response .="<td align='riht'>$ ".number_format($mresult['valor'],0,'.','.')."</td>";
                $response .="<td>$ ".number_format($mresult['abonos'],0,'.','.')."</td>";
                $response .="<td>$ ".number_format($saltot,0,'.','.')."</td>";
                $response .="</tr>";
            }
        }else{
                $response .="<tr>";
                $response .="<td colspan='4'> La Empresa No Presenta Mora</td>";
                $response .="</tr>";
        }
        $response .="<tr>";
        $response .="<th>Total</th>";
        $response .="<th></th>";
        $response .="<th></th>";
        $response .="<th style='text-align:center;'>$ ".number_format($totval,0,'.','.')."</th>";
        $response .="<th style='text-align:center;'>$ ".number_format($totabo,0,'.','.')."</th>";
        $response .="<th style='text-align:center;'>$ ".number_format($totsal,0,'.','.')."</th>";
        $response .="</tr>";
        $response .="</tbody>";
        $response .="</table>";
        echo $response;
    }
    public function moremp_reportAction(){
        $this->setResponse("ajax");
        $formu = new FPDF('P','mm','A4');
        $formu->AddPage();
        $formu->SetFillColor(236,248,240); 
        $formu->AddFont('Calibri','','Calibri.php');
        $formu->AddFont('Calibri','BI','Calibri Bold Italic.php');
        $formu->AddFont('Calibri','I','Calibri Italic.php');
        //$formu->SetFont('Arial','','12');
        //$formu->SetMargins(10,20,10);
        $formu->Cell(15,4,"",0,1,"L",0,0);
        $formu->Image('public/img/comfamiliar-logo.jpg',5,5,45,25);
        $formu->Image('public/img/portal/piepaginaCartas.png',0,268,210,30);
        $nit = Session::getDATA('documento');
        $formu->Cell(190,12,"",0,1,"C",0,0);
        $formu->Ln();
        $formu->Ln();
        $formu->SetFont('Calibri','BI','14');
        $formu->Cell(190,6,"CONSULTA DE MORA REAL",0,1,"C",0,0);
        $formu->Ln();
        $formu->Ln();
        $formu->SetFont('Calibri','BI','12');
        $formu->Cell(31,5,"N. de visita ",1,0,"C",0,0);
        $formu->Cell(36,5,"N. de liquidacion ",1,0,"C",0,0);
        $formu->Cell(26,5,"Periodos",1,0,"C",0,0);
        $formu->Cell(31,5,"Valor total",1,0,"C",0,0);
        $formu->Cell(31,5,"Abonos",1,0,"C",0,0);
        $formu->Cell(31,5,"Saldo",1,1,"C",0,0);
        $result = parent::webService("Morosa",array("nit"=>$nit));
        $formu->SetFont('Calibri','I','11');
        if($result != FALSE){
            $l=0;
            foreach($result as $mresult){
                $formu->Cell(31,5," ".$mresult['visita'],1,0,"C",0,0);
                $formu->Cell(36,5," ".$mresult['liquidacion'],1,0,"C",0,0);
                $formu->Cell(26,5,$mresult['periodo'],1,0,"C",0,0);
                $formu->Cell(31,5,"$ ".number_format($mresult['valor'],0,'.','.'),1,0,"C",0,0);
                $formu->Cell(31,5,"$ ".number_format($mresult['abonos'],0,'.','.'),1,0,"C",0,0);
                $saltot = $mresult['valor']-$mresult['abonos'];
                $formu->Cell(31,5,"$ ".number_format($saltot,0,'.','.'),1,1,"C",0,0);
                $l++;
                if($l>41){
                    $l=0;
                    $formu->AddPage();
                    $formu->SetFillColor(236,248,240); 
                    $formu->Cell(15,4,"",0,1,"L",0,0);
                    $formu->Image('public/img/comfamiliar-logo.jpg',5,5,45,25);
                    $formu->Image('public/img/portal/piepaginaCartas.png',0,268,210,30);
                    $nit = Session::getDATA('documento');
                    $formu->Cell(190,12,"",0,1,"C",0,0);
                    $formu->Ln();
                    $formu->Ln();
                    $formu->SetFont('Calibri','BI','14');
                    $formu->Cell(190,6,"CONSULTA DE MORA REAL",0,1,"C",0,0);
                    $formu->Ln();
                    $formu->Ln();
                    $formu->SetFont('Calibri','BI','12');
                    $formu->Cell(31,5,"N. de visita ",1,0,"C",0,0);
                    $formu->Cell(36,5,"N. de liquidacion ",1,0,"C",0,0);
                    $formu->Cell(26,5,"Periodos",1,0,"C",0,0);
                    $formu->Cell(31,5,"Valor total",1,0,"C",0,0);
                    $formu->Cell(31,5,"Abonos",1,0,"C",0,0);
                    $formu->Cell(31,5,"Saldo",1,1,"C",0,0);
                    $formu->SetFont('Calibri','I','11');
                }
            }
        }
        $formu->Ln();

        $file = "public/temp/reportes/moremp-".Session::getDATA('documento').".pdf";
        ob_clean();
        $formu->Output( $file,"F");
        $formu = parent::successFunc("Genera Formulario",$file);
        $this->renderText(json_encode($formu)); 
    }
    public function moremp_repxlsAction(){
        $fecha = new Date();
        $this->setResponse('view');
        $file = "public/temp/"."moremp_report".Session::getDATA('documento').".xls";
        require_once "Library/Excel/Main.php";
        $excels = new Spreadsheet_Excel_Writer($file);
        $excel = $excels->addWorksheet();
        $column_title = $excels->addFormat(array('fontfamily' => 'Verdana',
                    'size' => 12,
                    'fgcolor' => 50,
                    'border' => 1,
                    'bordercolor' => 'black',
                    "halign" => 'center'
                    ));
        $title = $excels->addFormat(array(   'fontfamily' => 'Verdana',
                    'size' => 13,
                    'border' => 0,
                    'bordercolor' => 'black',
                    "halign" => 'center'
                    ));
        $column_style = $excels->addFormat(array(   'fontfamily' => 'Verdana',
                    'size' => 11,
                    'border' => 1,
                    'bordercolor' => 'black',
                    ));
        $excel->setMerge(0,0,0,3);

        $excel->write(0,0,'Consulta de Mora Real',$title);
        $columns = array('#','N. de visita','N.de liquidacion','Periodos','Valor Total','Abonos','Saldo Total');
        $excel->setColumn(0,0,10);
        $excel->setColumn(1,6,20);
        $i = 0;
        $j = 2;
        foreach($columns as $column){
            $value = ucfirst($column);
            $excel->write($j, $i++, $value, $column_title);
        }
        $j++;
        $nit = Session::getDATA('documento');
        $result = parent::webService("Morosa",array("nit"=>$nit));
        if($result!=false){
            $total=1;
            foreach($result as $mresult){
                    $i = 0;
                    $excel->write($j, $i++,$total++, $column_style);
                    //$excel->writeString($j, $i++,number_format($salario,0,'.','.'), $column_style);
                    $excel->write($j, $i++,$mresult['visita'], $column_style);
                    $excel->write($j, $i++,$mresult['liquidacion'], $column_style);
                    $excel->write($j, $i++,$mresult['periodo'], $column_style);
                    $excel->write($j, $i++,"$ ".number_format($mresult['valor'],0,'.','.'), $column_style);
                    $excel->write($j, $i++,"$ ".number_format($mresult['abonos'],0,'.','.'), $column_style);
                    $saltot = $mresult['valor']-$mresult['abonos'];
                    $excel->write($j, $i++,"$ ".number_format($saltot,0,'.','.'), $column_style);
                    $j++;
            }
        }
        $fecha = new Date();
        $excels->close();
        header("location: ".Core::getInstancePath()."/{$file}");
    }
    public function morapresunta_viewAction(){
        $this->setResponse("ajax");
        echo parent::showTitle('Consulta Mora Presunta');
        $nit = Session::getDATA('documento');
        $response = "";
        $response .="<div class='col-sm-12'>";
        $response .="<div class='col-sm-6'>".Tag::Button("Generar PDF",'class: btn btn-success','onclick: downpdf()')."</div>";
        $response .="<div class='col-sm-6'>".Tag::Button("Generar Excel",'class: btn btn-success','onclick: downxls()')."</div>";
        $response .="</div>";
        $response .="<br>";
        $response .="<br>";
        $response .="<br>";
        $response .="<div>";
        $response .="<table class='table table-bordered'>";
        $response .="<thead>";
        $response .="<tr class='info' cellspacing='10'>";
        $response .="<td>Periodo</td>";
        $response .="<td>Motivo</td>";
        $response .="</tr>";
        $response .="</head>";
        $response .="<tbody>";
        $morosa = parent::webService("MoraPresunta",array("nit"=>$nit));
        if($morosa !=false){
            foreach($morosa as $mresult){
                $response .="<tr style='text-align:left;'>";
                $anno = substr($mresult['periodo'],0,4);
                $mes = substr($mresult['periodo'],4,6);
                $periodo = $anno."-".$mes;
                $response .="<td>$periodo</td>";
                $response .="<td>{$mresult['tipomorosidad']}</td>";
                $response .="</tr>";
            }
        }else{
                $response .="<tr>";
			$response .="<td colspan='2'> La Empresa No Presenta Mora Presunta</td>";              
			$response .="</tr>";
			$response .="</tr>";
		}
		$response .="</tbody>";
		$response .="</table>";
		echo $response;
	    }

    public function morepresunta_reportAction(){
        $this->setResponse("ajax");
        $formu = new FPDF('P','mm','A4');
        $formu->AddPage();
        $formu->SetFillColor(236,248,240); 
        $formu->AddFont('Calibri','','Calibri.php');
        $formu->AddFont('Calibri','BI','Calibri Bold Italic.php');
        $formu->AddFont('Calibri','I','Calibri Italic.php');
        //$formu->SetFont('Arial','','12');
        //$formu->SetMargins(10,20,10);
        $formu->Cell(15,4,"",0,1,"L",0,0);
        $formu->Image('public/img/comfamiliar-logo.jpg',5,5,45,25);
        $formu->Image('public/img/portal/piepaginaCartas.png',0,268,210,30);
        $nit = Session::getDATA('documento');
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->SetFont('Calibri','BI','14');
        $formu->Cell(190,6,"CONSULTA DE MORA PRESUNTA",0,1,"C",0,0);
        $formu->Ln();
        $formu->Ln();
        $formu->SetFont('Calibri','BI','12');
        $formu->Cell(60,5,"Periodo",1,0,"C",0,0);
        $formu->Cell(130,5,"Motivo",1,1,"C",0,0);
        $result = parent::webService("MoraPresunta",array("nit"=>$nit));
        $formu->SetFont('Calibri','I','11');
        if($result != FALSE){
            $l = 0;
            foreach($result as $mresult){
                $formu->Cell(60,5,$mresult['periodo'],1,0,"C",0,0);
                $formu->Cell(130,5,$mresult['tipomorosidad'],1,1,"C",0,0);
                $l++;
                if($l>41){
                    $l=0;
                    $formu->AddPage();
                    $formu->SetFillColor(236,248,240); 
                    $formu->Cell(15,4,"",0,1,"L",0,0);
                    $formu->Image('public/img/comfamiliar-logo.jpg',5,5,45,25);
                    $formu->Image('public/img/portal/piepaginaCartas.png',0,268,210,30);
                    $nit = Session::getDATA('documento');
                    $formu->Ln();
                    $formu->Ln();
                    $formu->Ln();
                    $formu->SetFont('Calibri','BI','14');
                    $formu->Cell(190,6,"CONSULTA DE MORA PRESUNTA",0,1,"C",0,0);
                    $formu->Ln();
                    $formu->Ln();
                    $formu->SetFont('Calibri','BI','12');
                    $formu->Cell(60,5,"Periodo",1,0,"C",0,0);
                    $formu->Cell(130,5,"Motivo",1,1,"C",0,0);
                    $formu->SetFont('Calibri','I','11');
                }
            }
        }
        $formu->Ln();

        $file = "public/temp/reportes/morepresunta-".Session::getDATA('documento').".pdf";
        ob_clean();
        $formu->Output( $file,"F");
        $formu = parent::successFunc("Genera Formulario",$file);
        $this->renderText(json_encode($formu)); 
    }

    public function morepresunta_repxlsAction(){
        $fecha = new Date();
        $this->setResponse('view');
        $file = "public/temp/"."morepresunta_report".Session::getDATA('documento').".xls";
        require_once "Library/Excel/Main.php";
        $excels = new Spreadsheet_Excel_Writer($file);
        $excel = $excels->addWorksheet();
        $column_title = $excels->addFormat(array('fontfamily' => 'Verdana',
                    'size' => 12,
                    'fgcolor' => 50,
                    'border' => 1,
                    'bordercolor' => 'black',
                    "halign" => 'center'
                    ));
        $title = $excels->addFormat(array(   'fontfamily' => 'Verdana',
                    'size' => 13,
                    'border' => 0,
                    'bordercolor' => 'black',
                    "halign" => 'center'
                    ));
        $column_style = $excels->addFormat(array(   'fontfamily' => 'Verdana',
                    'size' => 11,
                    'border' => 1,
                    'bordercolor' => 'black',
                    ));
        $excel->setMerge(0,0,0,3);

        $excel->write(0,0,'Consulta de Mora Presunta',$title);
        //$columns = array('#','Periodos','Motivo');
        $columns = array('Periodos','Motivo');
        $excel->setColumn(0,0,20);
        $excel->setColumn(1,1,90);
        //$excel->setColumn(2,2,90);
        $i = 0;
        $j = 2;
        foreach($columns as $column){
            $value = ucfirst($column);
            $excel->write($j, $i++, $value, $column_title);
        }
        $j++;
        $nit = Session::getDATA('documento');
        $result = parent::webService("MoraPresunta",array("nit"=>$nit));
        if($result!=false){
            $total=1;
            foreach($result as $mresult){
                    $i = 0;
                    //$excel->write($j, $i++,$total++, $column_style);
                    //$excel->writeString($j, $i++,number_format($salario,0,'.','.'), $column_style);
                    $excel->write($j, $i++,$mresult['periodo'], $column_style);
                    $excel->write($j, $i++,$mresult['tipomorosidad'], $column_style);
                    $j++;
            }
        }
        $fecha = new Date();
        $excels->close();
        header("location: ".Core::getInstancePath()."/{$file}");
    }
	    //Consulta de kit escolar empresas
	    public function kitEscolarEmp_viewAction() {
		$this->setResponse("ajax");
        echo parent::showTitle('Consulta de Subsidio Escolar Empresa');
    }
// asi hiba a quedar pero alfredo dijo que no sabia como sacer los trabajadores en mora 
/*
    public function morapresunta_viewAction(){
        $this->setResponse("ajax");
        echo parent::showTitle('Consulta Mora Presunta');
        //$nit = Session::getDATA('documento');
        $nit = '29228385';
        $response = "";
        $response .="<br>";
        $response .="<div class='container' >";
        $response .="<ul class='nav nav-tabs' >";
        $response .="<li class='active'><a href='#periodo' data-toggle='tab'>Periodos</a></li>";
        $response .="<li><a href='#trabajadores' data-toggle='tab' >Trabajadores</a></li>";
        $response .="</ul>";
        $response .="</div>";
        //contenido pestanna periodos
        $response .="<div class='tab-content' >";
        $response .="<div class='tab-pane fade in active' id='periodo' >";
        $response .="<br>";
        $response .="<div class='container'>";
        $response .="<table class='table table-bordered'>";
        $response .="<thead>";
        $response .="<tr class='info' cellspacing='10'>";
        $response .="<td>Periodo</td>";
        $response .="<td>Motivo</td>";
        $response .="</tr>";
        $response .="</head>";
        $response .="<tbody>";
        $morosa = parent::webService("MoraPresunta",array("nit"=>$nit));
        if($morosa !=false){
            foreach($morosa as $mresult){
                $response .="<tr style='text-align:left;'>";
                $response .="<td>{$mresult['periodo']}</td>";
                $response .="<td>{$mresult['tipomorosidad']}</td>";
                $response .="</tr>";
            }
        }else{
            $response .="<tr>";
            $response .="<td colspan='2'> La Empresa No Presenta Mora Presunta</td>";
            $response .="</tr>";
            $response .="</tr>";
        }
        $response .="</tbody>";
        $response .="</table>";
        $response .="</div>";
        $response .="</div>";
        //contenido pestanna Trabajadores 
        $response .="<div class='tab-pane fade' id='trabajadores' >";
        $response .="<h4>Trabajadores</h4>";

        $response .="</div>";
        $response .="</div>";
        echo $response;
    }
*/



    public function kitEscolarEmpAction() {
        $this->setResponse("ajax");
        $ano = $this->getPostParam("ano");
        $param = array("nit"=>Session::getDATA('documento'),"anno"=>$ano);
        $response = "";
        $response .="<div>";
        $response .="<table class='resultado-sec' border=1>";
        $response .="<tr class='tr-result' cellspacing='10'>";
        $response .="<td>A&ntilde;o</td>";
        $response .="<td>Fecha Entrega</td>";
        $response .="<td>Entregado</td>";
        $response .="</tr>";
        $estado = "NO";
        $result = parent::webService("KitescolarEmp", $param);
        if($result!=false){
            foreach($result as $mresult){
                $fecest = str_replace("12:00:00 AM","",$mresult['fecest']);
                if($mresult['estado']=="S")$estado="SI";
                $response .="<tr>";
                $response .="<td>{$mresult['ano']}</td>";
                $response .="<td>{$fecest}</td>";
                $response .="<td>{$estado}</td>";
                $response .="</tr>";
            }
        }
        $response .="</table>";
        $response .="</div>";
        return $this->renderText(json_encode($response));
    }
    //Consulta de giro emoresas
    public function giroEmp_viewAction(){
        if(SESSION::getDATA('documento')!=""){
            //Debug::addVariable("a","dd");
            //throw new DebugException(0);
        }
        $this->setResponse("ajax");
        echo parent::showTitle('Consulta de Giro Empresa');
    }
    public function giroEmpAction(){
        $this->setResponse("ajax");
        $periodo = $this->getPostParam("periodo");
        $response = "";
        $response .="<div align='center'>";
        $response .="<table class='table table-bordered' border=1 style='width: 90%;'>";
        $response .="<head>";
        $response .="<tr class='info' cellspacing='10'>";
        $response .="<td>Periodo Girado</td>";
        $response .="<td>Cedula Trabajador</td>";
        $response .="<td>Nombre Tabajador</td>";
        $response .="<td>Valor</td>";
        $response .="</tr>";
        $response .="</head>";
        $response .="<tbody>";
        $param = array("nit"=>Session::getDATA('documento'),"periodo"=>$periodo);
        $result = parent::webService("Giroemp", $param);
        $total = 0;
        if($result!=false){
            foreach($result as $mresult){
                //$cedcon = $mresult['cedcon'];
                //$paramcon =  array("documento"=>$cedcon);
                $valor = "$ ".number_format($mresult['valor'],0,".",".");
                //$valcre = number_format($mresult['valcre'],0,".",".");
                $response .="<tr>";
                $response .="<td>{$mresult['pergir']}</td>";
                $response .="<td>{$mresult['cedtra']}</td>";
                //$response .="<td>{$mresult['periodo']}</td>";
                $response .="<td align='left'>{$mresult['nombre']}</td>";
                $response .="<td align='right'>{$valor}</td>";
                $total = $total + $mresult['valor'];
                $response .="</tr>";
            }
            $response .="</tr>";
            $response .="<td colspan='3' align='right'> Total : </td>";
            $response .="<td align='right'>$ ".number_format($total,0,'.','.')."</td>";
            $response .="</tr>";
        } 
        $response .="</tbody>";
        $response .="</table>";
        $response .="</div>";
        return $this->renderText(json_encode($response));
    }
    //Actializacion de datos empresa-trabajador
    public function actdat_viewAction(){
        $this->setResponse("ajax");
        echo parent::showTitle('Actualización Datos Básicos');
        $mercurio28 = $this->Mercurio28->find("tipo = '".Session::getDATA('tipo')."' AND campo <>'idciuresidencia' AND campo <>'idciudad'","order: orden ASC");
        //$mercurio28 = $this->Mercurio28->find("tipo = '".Session::getDATA('tipo')."' AND campo <>'idciuresidencia' AND campo <>'idzona'","order: orden ASC");
        //$mercurio28 = $this->Mercurio28->find("tipo = '".Session::getDATA('tipo')."' AND campo <>'idciuresidencia' AND campo <>'idbarriocorresp'","order: orden ASC");
        foreach($mercurio28 as $mmercurio28){
            $campos[$mmercurio28->getCampo()] = $mmercurio28->getDetalle();
        }
        if(Session::getDATA('tipo')=="T")$titulo = "Datos del Trabajador";
        if(Session::getDATA('tipo')=="E")$titulo = "Datos de la Empresa";
        if(Session::getDATA('tipo')=="C")$titulo = "Datos del Conyuge";
        $result = parent::webService('Infobasica',array("tipo"=>Session::getDATA('tipo'),"documento"=>SESSION::getDATA('documento')));
        //$result[0]['fax'] = "";
        $ciu = $result[0]['codciu'];
        $dep = substr($ciu,0,2);
        $ciucor = $result[0]['idciucorresp'];
        $depcor = substr($ciucor,0,2);
        $barr= $result[0]['idbarrio'];
        $barrcor= $result[0]['idbarriocorresp'];
        //Debug::addVariable("a",);
        //throw new DebugException(0);

        $barrios = $this->Migra087->findAllBySql("SELECT distinct codbar, detalle FROM migra087 INNER JOIN migra089 ON migra087.codzon = migra089.codzon WHERE migra089.codciu= $ciu order by detalle");
        $_barrios = array();
        //$barrios = parent::webService("BarrioFiltrados",array("codciu"=>$ciu));
        if(count($barrios) > 0){
            foreach($barrios as $mbarrios ){
                $_barrios[$mbarrios->getCodbar()] = $mbarrios->getDetalle();
            }
        }else{
            $_barrios = '@';
        }
        
        $codciu = $this->Migra089->findAllBySql("SELECT distinct codciu,detciu FROM migra089");
        foreach($codciu as $mcodciu){
            if(substr($mcodciu->getCodciu(),0,2) == $dep)
                $_codciu[$mcodciu->getCodciu()] = utf8_decode($mcodciu->getDetciu());
            if(substr($mcodciu->getCodciu(),0,2) == $depcor)
                $_codciucor[$mcodciu->getCodciu()] = utf8_decode($mcodciu->getDetciu());
        }
        $barrioscor = $this->Migra087->findAllBySql("SELECT distinct codbar, detalle FROM migra087 INNER JOIN migra089 ON migra087.codzon = migra089.codzon WHERE migra089.codciu= $ciucor order by detalle");
        //$barrioscor = parent::webService("BarrioFiltrados",array("codciu"=>$ciucor));
        if(count($barrioscor) > 0){
            foreach($barrioscor as $mbarrioscor ){
                $_barrioscor[$mbarrioscor->getCodbar()] = $mbarrioscor->getDetalle();
            }
        }else{
            $_barrioscor = '@';
        }
       // $depart = parent::webService('departamento',array());
        $depart = $this->Migra089->findAllBySql("SELECT distinct coddep, detdep FROM migra089 order by detdep");
        foreach($depart as $mdepart ){
            $_depart[$mdepart->getCoddep()] = $mdepart->getDetdep();
        }
        $tipo = Session::getDATA('tipo');
        $html  = "";
        $html .= "<tr>";
        $html .="<td colspan=6><strong>$titulo</strong></td>";
        $html .= "</tr>";
        $html .= "<tr>";
        foreach($campos as $key => $value){
            $html .= "<tr>";
            if($key!='codciu' && $key!='idciucorresp')
                $html .= "<td style='text-align:right;'><label style=' margin-top: 2%'>{$value}</label></td>";
            $value = "value: ";
            if($result!=false){
                $value = "value: {$result[0][$key]}";
            }
            if($tipo=='E'){
                if($key == 'codciu'){
                    $html .= "<td style='text-align:right;'><b style=' margin-top: 2%'>Departamento</b></td>";

                    $html .= "<td>".Tag::selectStatic("coddep",$_depart,"style: width: 174px","useDummy: true","dummyValue: ","value: $dep","onchange: traerCiudad()","class: form-control","style: margin-top: 2%")."</td>";
                    $html .= "<tr>";
                    $html .= "</tr>";
                    $html .= "<td style='text-align:right;'><b style=' margin-top: 2%'>Ciudad</b></td>";
                    $html .= "<td>".Tag::selectStatic("codciu",$_codciu,"style: width: 174px","useDummy: true","dummyValue: ","value: $ciu","class: form-control","style: margin-top: 2%","onchange: traerBarrio();")."</td>";
                }
                if($key == 'idciucorresp'){
                    $html .= "<td style='text-align:right;'><b>Departamento Correspondencia</b></td>";
                    $html .= "<td>".Tag::selectStatic("iddepcorresp",$_depart,"useDummy: true","dummyValue: ","value: $depcor","onchange: traerCiudad2()","class: form-control","style: margin-top: 2%; ")."</td>";
                    $html .= "<tr>";
                    $html .= "</tr>";
                    $html .= "<td style='text-align:right;'><b>Ciudad Correspondencia</b></td>";
                    $html .= "<td>".Tag::selectStatic("idciucorresp",$_codciucor,"useDummy: true","dummyValue: ","value: $ciucor","class: form-control","style: margin-top: 2%; ","onchange: traerBarrio2();")."</td>";
                }
                if($key == 'direccion'){
                    $html .= "<td style='text-align:right;'> ".Tag::numericField($key,$value,"readonly: true","class: form-control","style: margin-top: 2%")."</td>";
                    $html .="<td>".Tag::Button("Cambiar",'class: btn btn-primary',"onclick: cambiarDireccion('direccion');","style: margin-top: 2%")."</td>";
                }
                if($key == 'direcorresp'){
                    $html .= "<td style='text-align:right;'> ".Tag::numericField($key,$value,"readonly: true","style: width: 300px; margin-left: 0px; ","class: form-control","style: margin-top: 2%; ")."</td>";
                    $html .="<td>".Tag::Button("Cambiar",'class: btn btn-primary',"onclick: cambiarDireccion('direcorresp');","style: margin-top: 2%; ")."</td>";
                }
                if($key == 'idbarriocorresp'){
                    $html .= "<td>".Tag::selectStatic("idbarriocorresp",$_barrioscor,"style: width: 174px","useDummy: true","dummyValue: ","value: $barrcor","class: form-control","style: margin-top: 2%; ")."</td>";
                }
                if($key == 'idzona'){
                    $html .= "<td>".Tag::select($key,$this->Migra089->find("codciu = {$result[0]['codciu']}"),"using: codzon,detzon",$value,"style: width: 174px","useDummy: true","dummyValue: ","class: form-control","style: margin-top: 2%; ")."</td>";
                }
                if($key == 'idbarrio'){
                    $html .= "<td>".Tag::selectStatic("idbarrio",$_barrios,"style: width: 174px","useDummy: true","dummyValue: ","value: $barr","class: form-control","style: margin-top: 2%; ")."</td>";
                }

                if($key == 'telefono' || $key=="fax"){
                    $html .= "<td>".Tag::numericField($key,$value," maxlength: 10","class: form-control","style: margin-top: 2%; ")."</td>";
                }
                if($key=="email"){
                    $html .= "<td colspan='7'>".Tag::textField($key,$value,"onblur: validaemail();","style: width: 400px;","class: form-control","style: margin-top: 2%; ")."</td>";
                }
            }
            if($tipo=='T'){
                if($key == 'codciu'){
                    $html .= "<td style='text-align:right;'><b style=' margin-top: 2%'>Departamento</b></td>";
                    $html .= "<td>".Tag::selectStatic("coddep",$_depart,"style: width: 174px","useDummy: true","dummyValue: ","value: $dep","onchange: traerCiudad()","class: form-control","style: margin-top: 2%")."</td>";
                    $html .= "<td style='text-align:right;'><b style=' margin-top: 2%;'>Ciudad</b></td>";
                    $html .= "<td>".Tag::selectStatic("codciu",$_codciu,"style: width: 174px","useDummy: true","dummyValue: ","value: $ciu","class: form-control","style: margin-top: 2%;","onchange: traerBarrio();")."</td>";
                }

                if($key == 'direccion'){
                    $html .= "<td colspan='2'> ".Tag::numericField($key,$value,"readonly: true","style: width: 331px; margin-left: 0px; margin-top: 2%;","class: form-control")."</td>";
                    $html .="<td>".Tag::Button("Cambiar",'class: btn btn-primary',"onclick: cambiarDireccion('direccion');","style: margin-top: 2%;")."</td>";
                }
                if($key == 'telefono'){
                    $html .= "<td>".Tag::numericField($key,$value," maxlength: 10","class: form-control","style: margin-top: 2%;")."</td>";
                }
                if($key == 'idbarrio'){
                    $html .= "<td>".Tag::selectStatic($key,$_barrios,$value,"style: width: 174px","useDummy: true","dummyValue: ","value: $barr","class: form-control","style: margin-top: 2%; ")."</td>";
                }
                if($key == 'idzona'){
                    $html .= "<td>".Tag::select($key,$this->Migra089->find("codciu = '{$result[0]['codciu']}'"),"using: codzon,detzon",$value,"style: width: 174px","useDummy: true","dummyValue: ","class: form-control","style: margin-top: 2%; ")."</td>";
                }
                if($key=="email"){
                    if($value == 'value: false') $value = "value: ''";
                    $html .= "<td colspan='7'>".Tag::textField($key,$value,"onblur: validaemail();","style: width: 331px; margin-top: 2%; ","class: form-control")."</td>";
                }
                if($key=='celular'){
                    $html .= "<td style=' margin-top: 10%'>".Tag::numericField($key,$value," maxlength: 10","class: form-control","style: margin-top: 2%;")."</td>";
                }
            }
            $html .= "</tr>";
        }
        $html .= "";
        $this->setParamToView("html", $html);
        $this->setParamToView("barr",$barr);
        $this->setParamToView("barrio",$_barrios);
        $this->setParamToView("dep",$dep);
        $this->setParamToView("coddep",$_depart);
        $this->setParamToView("ciu",$ciu);
        $this->setParamToView("codciu",$_codciu);
        $this->setParamToView("campos",$campos);
        $this->setParamToView("tipo",$tipo);
    }

    public function cambiarDireccionAction(){
        $this->setResponse("ajax");
        $campo = $this->getPostParam("campo");
        $html  ="";
        $html .="<table class='resultado-sec'>";
        $html .="<tr style='width: 100%' >";
        $html .= "<td>".Tag::selectStatic("prim",array("CALLE"=>"CALLE","CARRERA"=>"CARRERA","AVD"=>"AVENIDA","AUT"=>"AUTOPISTA","TRANSVERSAL"=>"TRANSVERSAL","DIAGONAL"=>"DIAGONAL"),"onchange: uneDireccion()","class: form-control")."</td>";
        $html .= "<td >".Tag::numericField("segu"," maxlength: 3","style: width: 55px;margin-left: 8px;","onchange: uneDireccion()","class: form-control")."</td>";
        $abc[""] = "";
        $abc["A"] = "A";
        $abc["B"] = "B";
        $abc["C"] = "C";
        $abc["D"] = "D";
        $abc["E"] = "E";
        $abc["F"] = "F";
        $abc["G"] = "G";
        $abc["H"] = "H";
        $abc["I"] = "I";
        $abc["J"] = "J";
        $abc["K"] = "K";
        $abc["L"] = "L";
        $abc["M"] = "M";
        $abc["N"] = "N";
        $abc["O"] = "O";
        $abc["P"] = "P";
        $abc["Q"] = "Q";
        $abc["R"] = "R";
        $abc["S"] = "S";
        $abc["T"] = "T";
        $abc["U"] = "U";
        $abc["V"] = "V";
        $abc["W"] = "W";
        $abc["X"] = "X";
        $abc["Y"] = "Y";
        $abc["Z"] = "Z";
        $html .= "<td>".Tag::selectStatic("ter",$abc,"onchange: uneDireccion()","style: width: 55px;margin-left: 8px;","class: form-control")."</td>";
        $html .= "<td>".Tag::selectStatic("cuar",array(""=>"","BIS"=>"BIS"),"onchange: uneDireccion()","class: form-control","style: width: 55px;margin-left: 8px;")."</td>";
        $html .= "<td>".Tag::selectStatic("quin",array(""=>"","WEST"=>"WEST","SUR"=>"SUR"),"onchange: uneDireccion()","class: form-control","style: width: 55px;margin-left: 8px;")."</td>";
        $html .= "<td><label> No. :</label></td>";
        $html .= "<td>".Tag::numericField("seis"," maxlength: 3","onchange: uneDireccion()","class: form-control","style: width: 55px;margin-left: 8px;")."</td>";
        $html .= "<td>".Tag::selectStatic("siete",$abc,"onchange: uneDireccion()","class: form-control","style: width: 55px;margin-left: 8px;")."</td>";
        $html .= "<td>".Tag::selectStatic("ocho",array(""=>"","BIS"=>"BIS"),"onchange: uneDireccion()","class: form-control","style: width: 55px;margin-left: 8px;")."</td>";
        $html .= "<td><label> - </label></td>";
        $html .= "<td>".Tag::selectStatic("nue",array(""=>"","WEST"=>"WEST","SUR"=>"SUR"),"onchange: uneDireccion()","class: form-control","style: width: 55px;margin-left: 8px;")."</td>";
        $html .= "<td>".Tag::numericField("diez"," maxlength: 3","onchange: uneDireccion()","class: form-control","style: width: 55px;margin-left: 8px;")."</td>";
        $html .="</tr>";
        $html .="<tr>";
        $html .= "<td colspan=13 > <b style='margin-top: 1%'>Datos Extras de la direccion:</b> ".Tag::textField("once","class: form-control","style: margin-left: 8px;","onchange: uneDireccion()",'maxlength: 20')."</td>";
        $html .="</tr>";
        $html .="<tr>";
        $html .= "<td colspan=13 > <b style='margin-top: 1%'>Resumen Direccion:</b> ".Tag::numericField("direccion_online","readonly: true","class: form-control","style: margin-left: 8px;")."</td>";
        $html .="</tr>";
        $html .="<tr>";
        $html .="<td colspan=14 align='center'>".Tag::Button("Confirmar Direccion ",'class: btn btn-primary',"onclick: confirmarDireccion('$campo');","style: margin-top: 2%")."</td>";
        $html .="</tr>";
        $html .="</table>";
        return $this->renderText(json_encode($html));
    }

    //Novedad de retiro    
    public function novret_viewAction(){
        $this->setResponse("ajax");
        $codare = $this->getPostParam("codare");
        $codope = $this->getPostParam("codope");
        echo parent::showTitle('Novedades de Retiro Trabajador');
        $codest = parent::webService("CodigoEstado",array());
        foreach($codest as $mcodest){
            $_codest[$mcodest['coddoc']] = $mcodest['detalle'];
        }
        $this->setParamToView("codest", $_codest);

        $this->setParamToView("codare", $codare);
        $this->setParamToView("codope", $codope);
        $this->setParamToView("coddoc", array('5'=>'NIT','2'=>'T.I','1'=>'C.C','4'=>'C.E','3'=>'P.S'));
    }
    public function novretAction(){
        $cedtra = $this->getPostParam("cedtra");
        $coddoc = $this->getPostParam("coddoc");
        $nit = Session::getDATA('documento');
        $this->setResponse("ajax");
        $response = "";
        $response .="<table class='table table-bordered'>";
        $response .="<thead>";
        $response .="<tr class='info'>";
        $response .="<th>C&eacute;dula</th>";
        $response .="<th>Nombre</th>";
        $response .="<th>Salario</th>";
        $response .="<th>Sexo</th>";
        $response .="<th>Fecha Nacimiento</th>";
        $response .="<th>Direccion</th>";
        $response .="<th>Telefono</th>";
        $response .="<th>Fecha Ingreso</th>";
        $response .="</tr>";
        $response .="</thead>";
        $response .="<tbody>";
        $result = parent::webService("DatosTrabajador",array("nit"=>$nit,"cedtra"=> $cedtra,"coddoc"=>$coddoc));
        if($result!=false){
            foreach($result as $mresult){
                $fecafi = date("Y-m-d",strtotime($mresult['fecing']));
                $fecnac = date("Y-m-d",strtotime($mresult['fecnac']));
                $response .="<tr>";
                $response .="<td>{$mresult['cedtra']}</td>";
                $response .="<td>{$mresult['nombre']}</td>";
                $response .="<td>".number_format($mresult['salario'],0,".",".")."</td>";
                $response .="<td>{$mresult['sexo']}</td>";
                $response .="<td>{$fecnac}</td>";
                $response .="<td>{$mresult['direccion']}</td>";
                $response .="<td>{$mresult['telefono']}</td>";
                $response .="<td>{$fecafi}</td>";
                $response .="</tr>";
            }
        }else{
            $response = parent::errorFunc("El trabajador no esta afiliado con la empresa o ya se encuentra retirado");
        //Debug::addVariable("a",$result);
        //throw new DebugException(0);
            return $this->renderText(json_encode($response));
        }
        $response .="</tbody>";
        $response .="</table>";
        $res['response'] = $response;
        $res['nombre'] = $mresult['nombre'];
        $res['fecafi'] = $fecafi;
        $response = parent::successFunc("bien",$res);
        return $this->renderText(json_encode($response));
    }
    //Consulta de nomina
    public function nomina_viewAction(){
        $this->setResponse("ajax");
        echo parent::showTitle('Consulta de Nomina');
    }
    public function nominaAction(){
        $this->setResponse("ajax");
        $periodo  = $this->getPostParam('periodo');
        $response = "";
        $response .="<script>";
        $response .="$(document).ready(function(){ ";
        $response .="$('[data-toggle=";
        $response .='"tooltip"';
        $response .= "]').tooltip();";
        $response .="});";
        $response .="</script>";
        $response .="<br>";
        $response .="<div>";
        $response .="<table class='table table-bordered'>";
        $response .="<thead>";
        $response .="<tr class='info' cellspacing='10'>";
        $response .="<td>#</td>";
        $response .="<td>C&eacute;dula</td>";
        $response .="<td>Nombre</td>";
        $response .="<td>Porcentaje Aporte</td>";
        $response .="<td>Dias Trabajados</td>";
        $response .="<td>Salario</td>";
        $response .="<td>IBC</span></td>";
        $response .="<td>  Valor Aporte   </td>";
        $response .="<td><span aria-hidden='true' data-toggle='tooltip' title='INGRESO'>ING</span></td>";
        $response .="<td><span aria-hidden='true' data-toggle='tooltip' title='RETIRO'>RET<span></td>";
        $response .="<td><span aria-hidden='true' data-toggle='tooltip' title='VARIACION SALARIAL TEMPORAL'>VST<span></td>";
        $response .="<td><span aria-hidden='true' data-toggle='tooltip' title='VARIACION SALARIAL PERMANENTE'>VSP<span></td>";
        $response .="<td><span aria-hidden='true' data-toggle='tooltip' title='SUSPENCION TEMPORAL CONTRATO'>STC<span></td>";
        $response .="<td><span aria-hidden='true' data-toggle='tooltip' title='INCAPACIDAD TEMPORAL ENFERMEDAD'>ITE<span></td>";
        $response .="<td><span aria-hidden='true' data-toggle='tooltip' title='LICENCIA MATERNIDAD'>LM <span></td>";
        $response .="<td><span aria-hidden='true' data-toggle='tooltip' title='VACACIONES'>VAC<span></td>";
        $response .="<td><span aria-hidden='true' data-toggle='tooltip' title='INCAPACIDAD TEMPORAL ACCIDENTE DE TRABAJO'>ITAT<span></td>";
        $response .="</tr>";                                                               
        $response .="</thead>";
        $response .="<tbody>";
        $total = 0;
        $result = parent::webService("Nomina",array("nit"=>Session::getDATA('documento'),"periodo"=>$periodo));
        if($result!=false){
            $total = 0;
            $totalapo= "";
            $totalibc="";
            foreach($result as $mresult){
                $total++;
                $novedad = "";
                $salario = number_format($mresult['salario'],0,".",".");
                $ibc = number_format($mresult['ibc'],0,".",".");
                $valapo  = number_format($mresult['valapo'],0,".",".");
                $totalapo  = $totalapo + ($mresult['valapo']) ;
                //$totalibc = $totalibc + ($mresult ['icbf']);
                $totalibc = $totalibc + ($mresult ['ibc']);
                $totalapor  = number_format($totalapo,0,".",".");
                $totalibc1  = number_format($totalibc,0,".",".");
                $response .="<tr style='text-align: left;'>";
                $response .="<td align='right'>{$total}</td>";
                $response .="<td>{$mresult['cedtra']}</td>";
                $response .="<td>{$mresult['nombre']}</td>";
                $response .="<td align='right'>".number_format($mresult['porapor'],1) ." %</td>";
                $response .="<td align='right'>{$mresult['diacot']}</td>";
                $response .="<td align='right'>$".$salario."</td>";
                $response .="<td align='right'>$".$ibc."</td>";
                $response .="<td align='right'>$".$valapo."</td>";
                $response .="<td><span aria-hidden='true' data-toggle='tooltip' title='INGRESO'>{$mresult['noving']}</span></td>";
                $response .="<td><span aria-hidden='true' data-toggle='tooltip' title='RETIRO'>{$mresult['novret']}</span></td>";
                $response .="<td><span aria-hidden='true' data-toggle='tooltip' title='VARIACION SALARIAL TEMPORAL'>{$mresult['novtrasa']}</span></td>";
                $response .="<td><span aria-hidden='true' data-toggle='tooltip' title='VARIACION SALARIAL PERMANENTE'>{$mresult['novpersal']}</span></td>";
                $response .="<td><span aria-hidden='true' data-toggle='tooltip' title='SUSPENCION TEMPORAL CONTRATO'>{$mresult['novsuscon']}</span></td>";
                $response .="<td><span aria-hidden='true' data-toggle='tooltip' title='INCAPACIDAD TEMPORAL ENFERMEDAD'>{$mresult['novinc']}</span></td>";
                $response .="<td><span aria-hidden='true' data-toggle='tooltip' title='LICENCIA MATERNIDAD'>{$mresult['novmat']}</span></td>";
                $response .="<td><span aria-hidden='true' data-toggle='tooltip' title='VACACIONES'>{$mresult['novvac']}</span></td>";
                $response .="<td><span aria-hidden='true' data-toggle='tooltip' title='INCAPACIDAD TEMPORAL ACCIDENTE DE TRABAJO'>{$mresult['novtemtra']}</span></td>";
                $response .="</tr>";
            }	
            $response .="<tr>";
            $response .="<td colspan='1'><b> Total<b></td>";
            $response .="<td colspan='1'> </td>";
            $response .="<td colspan='1'> </td>";
            $response .="<td colspan='1'> </td>";
            $response .="<td colspan='1'> </td>";
            $response .="<td colspan='1'> </td>";
            $response .="<td colspan='1'><span aria-hidden='true' data-toggle='tooltip' title='TOTAL IBC' align='right'>$totalibc1 </span> </td>";
            $response .="<td colspan='1'> <span aria-hidden='true' data-toggle='tooltip' title='TOTAL APORTES'align='right' > $$totalapor </span></td>";
            $response .="<td colspan='1'> </td>";
            $response .="<td colspan='1'> </td>";
            $response .="<td colspan='1'> </td>";
            $response .="<td colspan='1'> </td>";
            $response .="<td colspan='1'> </td>";
            $response .="<td colspan='1'> </td>";
            $response .="<td colspan='1'> </td>";
            $response .="<td colspan='1'> </td>";
            $response .="<td colspan='1'> </td>";
            $response .="</tr>";
        }else{
            $response .="<tr>";
            $response .="<td colspan='17'>No presenta registros</td>";
            $response .="</tr>";
        }
        $response .="</tbody>";
        $response .="</table>";
        $response .="</div>";
        /*
           $response .="<br>";
           $response .="<table class='resultado-sec' border=1>";
           $response .="<tr cellspacing='10'>";
           $response .="<td colspan=17>
           <table>
           <tr>
           <td align='left'><b>ING</b>=>INGRESO</td>
           <td align='left'><b>RET</b>=>RETIRO</td>
           <td align='left'><b>VST</b>=>VARIACION SALARIAL TEMPORAL </td>
           </tr>
           <tr>
           <td align='left'><b>VSP</b>=>VARIACION SALARIAL PERMANENTE </td>
           <td align='left'><b>STC</b>=>SUSPENCION TEMPORAL CONTRATO</td>
           <td align='left'><b>ITE</b>=>INCAPACIDAD TEMPORAL ENFERMEDAD</td>
           </tr>
           <tr>
           <td align='left'><b>LM</b>=>LICENCIA MATERNIDAD </td>
           <td align='left'><b>VAC</b>=>VACACIONES </td>
           <td align='left'><b>ITAT</b>=>INCAPACIDAD TEMPORAL ACCIDENTE DE TRABAJO</td>
           </tr>
           </table>
           </td>";
           $response .="</tr'>";
           $response .="<tr class='tr-result' cellspacing='10'>";
           $response .="<td>#</td>";
           $response .="<td>C&eacute;dula</td>";
           $response .="<td>Nombre</td>";
           $response .="<td>Porcentaje Aporte</td>";
           $response .="<td>Dias Trabajados</td>";
           $response .="<td>Salario</td>";
           $response .="<td>Valor Aporte</td>";
           $response .="<td>IBC</td>";
           $response .="<td>ING</td>";
           $response .="<td>RET</td>";
           $response .="<td>VST</td>";
           $response .="<td>VSP</td>";
           $response .="<td>STC</td>";
           $response .="<td>ITE</td>";
           $response .="<td>LM</td>";
           $response .="<td>VAC</td>";
           $response .="<td>ITAT</td>";
           $response .="</tr>";
           $total = 0;
           $result = parent::webService("Nomina",array("nit"=>Session::getDATA('documento'),"periodo"=>$periodo));
           if($result!=false){
           $total = 0;
           foreach($result as $mresult){
           $total++;
           $novedad = "";
           $salario = number_format($mresult['salario'],0,".",".");
           $valapo  = number_format($mresult['valapo'],0,".",".");
           $response .="<tr>";
           $response .="<td>{$total}</td>";
           $response .="<td>{$mresult['cedtra']}</td>";
           $response .="<td>{$mresult['nombre']}</td>";
           $response .="<td>{$mresult['porapor']}</td>";
           $response .="<td>{$mresult['diacot']}</td>";
           $response .="<td>{$salario}</td>";
           $response .="<td>{$valapo}</td>";
           $response .="<td>{$mresult['icbf']}</td>";
           $response .="<td>{$mresult['noving']}</td>";
           $response .="<td>{$mresult['novret']}</td>";
           $response .="<td>{$mresult['novtrasa']}</td>";
           $response .="<td>{$mresult['novpersal']}</td>";
           $response .="<td>{$mresult['novsuscon']}</td>";
           $response .="<td>{$mresult['novinc']}</td>";
           $response .="<td>{$mresult['novmat']}</td>";
           $response .="<td>{$mresult['novvac']}</td>";
           $response .="<td>{$mresult['novtemtra']}</td>";
           $response .="</tr>";
    }
    }
    $response .="</table>";
    */
        return $this->renderText(json_encode($response));
    }
    //Consulta de aportes
    public function aportes_viewAction(){
        $this->setResponse("ajax");
        echo parent::showTitle('Consulta de Aporte');     
    }
    public function aportesAction(){
        $this->setResponse("ajax");
        $perini = $this->getPostParam("perini","addslaches","alpha","extraspaces","striptags");
        $perfin = $this->getPostParam("perfin","addslaches","alpha","extraspaces","striptags");
        $response = "";
        $response .="<div style='margin-top: 3%'>";
        $response .="<table class='table table-bordered'>";
        $response .="<tr class='info' cellspacing='10'>";
        $response .="<td>Recibo</td>";
        $response .="<td>Periodo</td>";
        $response .="<td>Valor N&oacute;mina</td>";
        $response .="<td>Valor Aporte</td>";
        $response .="<td>Valor Interes</td>";
        $response .="<td>Valor Total</td>";
        //$response .="<td>Indice</td>";
        $response .="<td>Devoluci&oacute;n</td>";
        $response .="<td>Trabajadores</td>";
        $response .="<td>Fecha Pago</td>";
        $response .="</tr>";
        $result = parent::webService("Aporte",array('nit'=>Session::getDATA('documento'),'perini'=>$perini,'perfin'=>$perfin));
        if($result!=false){
            foreach($result as $mresult){
                $tota=0;
                $total=0;
                if($mresult['devolucion'] == ""){
                    $devolucion = "0";
                }else{
                    $devolucion = $mresult['devolucion'];
                }
                if($mresult['porapo'] == ""){
                    $porapo = "0";
                }else{
                    $porapo = $mresult['porapo'];
                }
                $razsoc = session::getData('nombre');
                $fecpag = date("Y-m-d",strtotime($mresult['fecpag']));
                $valnom = number_format($mresult['valnom'],0,".",".");
                $valapo = number_format($mresult['valapo'],0,".",".");
                $valint = number_format(round($mresult['valint']),0,".",".");
                $response .="<tr style='text-align: right;'>";
                $response .="<td>{$mresult['numrec']}</td>";
                $response .="<td>{$mresult['periodo']}</td>";
                $response .="<td>$ {$valnom}</td>";
                $response .="<td>$ {$valapo}</td>";
                $response .="<td>$ {$valint}</td>";
                $tota= $mresult['valapo']+$mresult['valint'];
                $total = number_format(round($tota),0,".",".");
                $response .="<td>$ {$total}</td>";
                //$response .="<td>".number_format($porapo,0,".",".")."%</td>";
                $response .="<td>".number_format($devolucion,0,".",".")."%</td>";
                $response .="<td>{$mresult['traapo']}</td>";
                $response .="<td>{$fecpag}</td>";
                $response .="</tr>";
            }
        }
        $response .="</table>";
        $response .="</div>";
        return $this->renderText(json_encode($response));
    }

// DATOS TRABAJADOR
    public function nucfam_viewAction(){
      $this->setResponse("ajax");
      echo parent::showTitle('Consulta de Núcleo Familiar');
      $param = array("cedtra"=>Session::getData('documento'));
      $result = parent::webService("AfiliadoConsultar", array('tipoDocumento'=>Session::getData('coddoc'),"identificacion"=>Session::getData('documento')));
      $fecret = "";
      foreach($result['AfiliadoConsultarResult']['Afiliaciones'] as $key => $value){
          foreach($value as $mvalue){
              if($result['AfiliadoConsultarResult']['EstadoAfiliacionPrincipal'] != 'A'){
                  $fecret = $mvalue['FechaRetiro'];
              }
          }
      }
      $response = "";
      if($result != false ){
          $trafecnac = date("Y-m-d",strtotime($result['AfiliadoConsultarResult']['FechaNacimiento']));
          $trafecafi = date("Y-m-d",strtotime($result['AfiliadoConsultarResult']['FechaAfiliacionPrincipal']));
          if($fecret != ''){
            $trafecret = date("Y-m-d",strtotime($fecret));
          }
          $salario = number_format($result['AfiliadoConsultarResult']['Salario'],0,".",".");
          $estad = "Activo";
          if($result['AfiliadoConsultarResult']['EstadoAfiliacionPrincipal']!="A")$estad = "Inactivo";
          $response .= "<div>";
          $response .= "<table class='table table-bordered' style='width: 100%; margin-top: 20px;'>";
          $response .= "<tr>";
          $response .= "<td class='info' style='text-align: center;'><strong >C&eacute;dula</strong></td><td>{$result['AfiliadoConsultarResult']['Identificacion']}</td>";
            $nombre = $result['AfiliadoConsultarResult']['PrimerNombre']." ".$result['AfiliadoConsultarResult']['SegundoNombre']." ".$result['AfiliadoConsultarResult']['PrimerApellido']." ".$result['AfiliadoConsultarResult']['SegundoApellido'];
          $response .= "<td  class='info' style='text-align: center;'><strong>Nombre</strong></td><td>{$nombre}</td>";
          $response .= "<td  class='info' style='text-align: center;'><strong>Estado</strong></td><td>{$estad}</td>";
          $response .= "</tr>";
          $response .= "<tr>";
          $response .= "<td  class='info' style='text-align: center;'><strong>Fecha Nacimiento</strong></td><td>{$trafecnac}</td>";
          $response .= "<td  class='info' style='text-align: center;'><strong>Email</strong></td><td> {$result['AfiliadoConsultarResult']['Email']}</td>";
          $response .= "</tr>";
          $response .= "<tr>";
          $response .= "<td  class='info' style='text-align: center;'><strong>Celular</strong></td><td>{$result['AfiliadoConsultarResult']['Celular']}</td>";
          $response .= "<td  class='info' style='text-align: center;'><strong>Direcci&oacute;n</strong></td><td>{$result['AfiliadoConsultarResult']['Direccion']}</td>";
          $response .= "<td  class='info' style='text-align: center;'><strong>Tel&eacute;fono</strong></td><td>{$result['AfiliadoConsultarResult']['Telefono']}</td>";
          $response .= "</tr>";
          $response .= "</table>";
          $response .= "</div>";
          if($result!=false){
              $response .= "<div><hr width='100%'><strong>Beneficiario(s)</strong><hr width='100%'></div>";
              $response .= "<div>";
              $response .= "<table class='table table-bordered' border='1' cellpadding='5' style='border-collapse: collapse; width: 100%;'>";
              $response .= "<thead>";
              $response .= "<tr class='info'>";
              $response .= "<th>Documento</th>";
              $response .= "<th>Nombre</th>";
              $response .= "<th>Fecha Afiliacion</th>";
              $response .= "<th>Parentesco</th>";
              $response .= "<th>Fecha Nacimiento</th>";
              $response .= "<th>Capacidad de Trabajado</th>";
              $response .= "<th>Estado</th>";
              $response .= "</tr>";
              $response .= "</thead>";
              foreach($result['AfiliadoConsultarResult']['Beneficiarios'] as $key => $value){
                  if(isset($value["Celular"])){
                      $value[0] = $value;
                  }
                  foreach($value as $mvalue){
                    if(!isset($mvalue['Parentesco']['Id']) || !isset($mvalue['Conviven'])) continue;
                      if($mvalue['Parentesco']['Id'] == '34' && $mvalue['Conviven'] == 'N'){
                          continue;
                      }
                      $fecafi = date("Y-m-d",strtotime($mvalue['FechaAfiliacion']));
                      $fecnac = date("Y-m-d",strtotime($mvalue['FechaNacimiento']));
                      $fecret = "";
                      $nombre = $mvalue['PrimerNombre']." ".$mvalue['SegundoNombre']." ".$mvalue['PrimerApellido']." ".$mvalue['SegundoApellido'];
                      $response .= "<tr style='text-align: left;'>";
                      $response .= "<td>{$mvalue['Identificacion']}</td>";
                      $response .= "<td>{$nombre}</td>";
                      $response .= "<td>{$fecafi}</td>";
                      $response .= "<td>{$mvalue['Parentesco']['Detalle']}</td>";
                      //Debug::addVariable("a",$mvalue['CapacidadDeTrabajo']);
                      //Debug::addVariable("b",$result);
                      //throw new DebugException(0);
                      $response .= "<td>{$fecnac}</td>";
                      $response .= "<td style='text-align: center;'>{$mvalue['CapacidadDeTrabajo']}</td>";
                      if($mvalue['Estado'] == 'A'){
                          $estado = 'ACTIVO';
                      }else{
                          $estado = 'INACTIVO';
                      }
                      $response .= "<td style='text-align: center;'>{$estado}</td>";
                      $response .= "</tr>";
                      $response .= "</tbody>";
                  }
              }
              $response .= "</table>";
              $response .= "</div>";
          }
      }else{
          $response .= "<div>";
          $response .= "<h3>No hay Nucleo Familiar</h3>";
          $response .= "<hr>";
          $response .= "</div>";
      }
      echo $response;
    }
    //Consulta de kitescolar        
    public function kitEscolar_viewAction(){
        $this->setResponse("ajax");
        echo parent::showTitle('Consulta de Bono Escolar');
        $param = array("cedtra"=>Session::getDATA('documento'));
        $response = "";
        $response .="<br>";
        $response .="<div>";
        $response .="<table class='table table-bordered'>";
        $response .="<thead>";
        $response .="<tr class='info' cellspacing='10'>";
        $response .="<td><b>A&ntilde;o</b></td>";
        $response .="<td><b>Nombre</b></td>";
        $response .="<td><b>Fecha Entrega</b></td>";
        $response .="<td><b>Entregado</b></td>";
        $response .="<td><b>Tipo</b></td>";
        $response .="<td><b>Observacion</b></td>";
        $response .="</tr>";
        $response .="</thead>";
        $response .="<tbody";
        $result = parent::webService("KitescolarTra", $param);
        if($result!=false){
            foreach($result as $mresult){
                $fecest ="";
                $tipo = "";
                $estado="No";
                if($mresult['estado'] == "S"){
                    $estado="SI";
                    $fecest = date("Y-m-d",strtotime($mresult['fecest']));
                }
                if($mresult['ano'] >= '2016'){
                    $tipo = "Bono Escolar";
                }else{
                    $tipo = "Kit Escolar";
                }
                $response .="<tr>";
                $response .="<td style='text-align: left;'>{$mresult['ano']}</td>";
                $response .="<td style='text-align: left;'>{$mresult['beneficiario']}</td>";
                $response .="<td style='text-align: left;'>{$fecest}</td>";
                $response .="<td style='text-align: left;'>{$estado}</td>";
                $response .="<td style='text-align: left;'>{$tipo}</td>";
                $response .="<td style='text-align: left;'>{$mresult['observacion']}</td>";
                $response .="</tr>";
            }
        }else{
            $response .="<tr>";
            $response .="<td colspan='6'> No Presentan Registros de Bono Escolar </td>";
            $response .="</tr>";
        }
        $response .="</tr>";
        $response .="</table>";
        $response .="</div>";
        echo $response;
    }
    //Consulta de giro

    public function giro_viewAction(){
        $this->setResponse("ajax");
        echo parent::showTitle('Consulta de Giros');     
    }

    public function giroAction(){
        $this->setResponse("ajax");
        $perini = $this->getPostParam('perini');
        $perfin = $this->getPostParam('perfin');
        $param = array("cedtra"=>Session::getData('documento'),"anno"=>$perini,"perfin"=>$perfin);
        //$param = array("cedtra"=>Session::getData('documento'),"anno"=>$perini);
        $response = "";
        $response .="<br>";
        $response .="<div>";
        $response .="<table class='table table-bordered'>";
        $response .="<thead>";
        $response .="<tr class='info' cellspacing='10'>";
        $response .="<td><b>Periodo Girado</b></td>";
        $response .="<td><b>Documento</b></td>";
        $response .="<td><b>Nombre Beneficiario</b></td>";
        $response .="<td><b>Parentesco</b></td>";
        $response .="<td><b>Razon Social Empresa</b></td>";
        $response .="<td><b>Valor Neto</b></td>";
        $response .="<td><b>Valor Descuentos</b></td>";
        $response .="<td><b>Valor Pagado</b></td>";
        $response .="</tr>";
        $response .="</thead>";
        $response .="<tbody>";
        $total=0;
        $totcre=0;
        $totpag=0;
        $result = parent::webService("Giro", $param);
        if($result!=false){
            foreach($result as $mresult){
                $benef = $mresult['codben'];
                $tippag = $mresult['tippag'];
                $parent = preg_split("/-/",$tippag);
                if(!isset($parent[1]))$parent[1]="";
                $parent = $parent[1];
                switch(trim($parent)){
                    case 34:
                        $parent="CONYUGE";
                        break;
                    case 35:
                        $parent="HIJO(A)";
                        break;
                    case 36:
                        $parent="PADRE/MADRE";
                        break;
                    case 37:
                        $parent="HERMANO(A)";
                        break;
                    case 38:
                        $parent="HIJASTRO(A)";
                        break;
                }
                //$datben = parent::webService("Datosfamiliar",array("documento"=>$benef));      
                $valor = number_format($mresult['valor'],0,".",".");
                $valcre = number_format($mresult['valcre'],0,".",".");
                $response .="<tr style='text-align: left;'>";
                $response .="<td align='right'>{$mresult['periodo']}</td>";
                $response .="<td align='right'>{$mresult['documento']}</td>";
                $response .="<td align='left'>{$mresult['nomben']}</td>";
                $response .="<td align='left'>{$parent}</td>";
                $response .="<td align='left'>{$mresult['nit']}</td>";
                $response .="<td align='right'>$ {$valor}</td>";
                $response .="<td align='right'>$ {$valcre}</td>";
                $valorpag = ($mresult['valor'])-($mresult['valcre']);
                $valorpago = number_format($valorpag,0,".",".");
                $response .="<td align='right'>$ {$valorpago}</td>";
                $response .="</tr>";
                $total = $total + $mresult['valor'];
                $totcre = $totcre + $mresult['valcre'];
                $totpag = $totpag + ($mresult['valor']-$mresult['valcre']);
            }
        }else{
            $response .="<tr>";
            $response .="<td colspan= '8'> No presenta periodos girados </td>";
            $response .="</tr>";
        }
        $response .="<tr>";
        $response .="<td colspan='5'><b>Total :</b></td>";
        $total = number_format($total,0,".",".");
        $totcre = number_format($totcre,0,".",".");
        $totpag = number_format($totpag,0,".",".");
        $response .="<td align='right'><b>$ $total </b></td>";
        $response .="<td align='right' ><b>$ $totcre </b></td>";
        $response .="<td align='right' ><b>$ $totpag </b></td>";
        $response .="</tr>";
        $response .="</tbody>";
        $response .="</table>";
        $response .="</div>";
        return $this->renderText(json_encode($response));
    }

    //Consulta de no giro
    public function nogiro_viewAction(){
        $this->setResponse("ajax");
        echo parent::showTitle('Consulta de No Giros');     
    }

    public function nogiroAction(){
        $this->setResponse("ajax");
        $perini = $this->getPostParam('perini');
        $perfin = $this->getPostParam('perfin');
        $param = array("cedtra"=>Session::getDATA('documento'),"perini"=>"$perini","perfin"=>"$perfin");
        //$param = array("cedtra"=>Session::getDATA('documento'));
        $response = "";
        $response .="<br>";
        $response .="<div>";
        $response .="<table class='table table-bordered'>";
        $response .="<thead>";
        $response .="<tr class='info' cellspacing='10'>";
        $response .="<td><b>Periodo Girado</b></td>";
        $response .="<td><b>Razon Social</b></td>";
        $response .="<td><b>Doc. Beneficiario</b></td>";
        $response .="<td><b>Nombre Beneficiario</b></td>";
        $response .="<td><b>Motivo</b></td>";
        $response .="</tr>";
        $response .="</thead>";
        $response .="<tbody>";
        $result = parent::webService("Nogiro", $param);
        Debug::addVariable("a",$result);
        Debug::addVariable("b",$param);
        //throw new DebugException(0);

        if($result!=false){
            foreach($result as $mresult){
                $benef = $mresult['codben'];
                $datben = parent::webService("Datosfamiliar",array("documento"=>$benef,"cedtra"=>Session::getDATA('documento')));      
                $response .="<tr style='text-align: left;'>";
                $response .="<td align='right'>{$mresult['pergir']}</td>";
                $response .="<td align='left'>{$mresult['periodo']}</td>";
                $response .="<td align=right>{$datben[0]['documento']}</td>";
                $response .="<td align='left'>{$datben[0]['nombre']}</td>";
                $response .="<td>{$mresult['detalle']}</td>";
                $response .="</tr>";
            }
        }else{
            $response .="<tr>";
            $response .="<td colspan= '5'>No presenta periodos no girados </td>";
            $response .="</tr>";
        }
        $response .="</tbody>";
        $response .="</table>";
        $response .="</div>";
        return $this->renderText(json_encode($response));
    }
    //Consulta Mivimiento tarjetas
    public function saldoTarjeta_viewAction(){
        $this->setResponse("ajax");
        echo parent::showTitle('Consulta de Movimientos');     
    }
    public function saldoTarjetaAction(){
        $this->setResponse("ajax");
        $estado = "Inactivo"; 
        $fecini = $this->getPostParam("fecini");
        $fecfin = $this->getPostParam("fecfin");
        $response = "";
        $response .="<br>";
        $response .="<div>";
        $response .="<table class='table table-bordered'>";
        $response .="<thead>";
        $response .="<tr class='info' cellspacing='10'>";
        $response .="<td><b>No. Tarjeta</b></td>";
        $response .="<td><b>Transacci&oacute;n</b></td>";
        $response .="<td><b>Valor</b></td>";
        $response .="<td><b>Fecha Movimiento </b></td>";
        $response .="</tr>";
        $response .="</thead>";
        $response .="<tbody>";
        $param = array("cedtra"=>Session::getDATA('documento'),"fecini"=>$fecini,"fecfin"=>$fecfin);
        $result = parent::webService("ConsultaTarjeta", $param);
        $histar = parent::webService("HistoricoTarjeta", array("cedtra"=>Session::getDATA('documento')));
        $saldo = number_format($histar[0]['saldo'],0,".",".");
        if($result!=false){
            foreach($result as $mresult){
                $fecha = date("Y-m-d",strtotime($mresult['fecha']));
                $valor = number_format($mresult['valor'],0,".",".");
                $response .="<tr style='text-align: left;'>";
                $response .="<td>{$mresult['tarjeta']}</td>";
                $response .="<td>{$mresult['detalle']}</td>";
                $response .="<td>$ {$valor}</td>";
                $response .="<td>{$fecha}</td>";
                $response .="</tr>";
            }        
        }else{
            $response .="<tr>";
            $response .="<td colspan='4'> No Presentan Movimientos de Tarjetas</td>";
            $response .="</tr>";
        }
        $response .="<tr>";
        $response .="<td colspan='2'><b> Saldo :</b></td>";
        $response .="<td><b>$ $saldo </b></td>";
        $response .="<td></td>";
        $response .="</tr>";

        $response .="</tbody>";
        $response .="</table>";
        $response .="</div>";
        return $this->renderText(json_encode($response));
    }
//Historico de tarjetas
    public function historicoTarjeta_viewAction(){
        $this->setResponse("ajax");
        parent::showTitle('Consulta de Histórico de Tarjetas');     
        //$estado = "Inactivo"; 
        $entr="No";
        $response = "";
        $response .="<h3>Tarjetas Activas</h3>";
        $response .="<div>";
        $response .="<table class='table table-bordered'>";
        $response .="<thead>";
        $response .="<tr class='info' cellspacing='10'>";
        $response .="<td><b>N&uacute;mero Tarjeta</b></td>";
        $response .="<td><b>Fecha Expedici&oacute;n</b></td>";
        $response .="<td><b>Fecha Solicitud</b></td>";
        $response .="<td><b>Fecha Entrega</b></td>";
        $response .="<td><b>Entregada</b></td>";
        $response .="<td><b>Estado</b></td>";
        $response .="<td><b>Saldo</b></td>";
        $response .="</tr>";
        $response .="</thead>";
        $response .="<tbody>";
        $param = array("cedtra"=>Session::getDATA('documento'));
        $result = parent::webService("HistoricoTarjeta", $param);
        //Debug::addVariable("a",print_r($result,true));
        //throw new DebugException(0);    
        $a=0;
        if($result!=false){
            foreach($result as $mresult){
                if($mresult['estado'] == "A"){
                    $a++;
                    $fecexp = date("Y-m-d",strtotime($mresult['fecexp']));
                    $fecsol = date("Y-m-d",strtotime($mresult['fecsol']));
                    $fecent = date("Y-m-d",strtotime($mresult['fecent']));
                    $fecven = date("Y-m-d",strtotime($mresult['fecven']));
                    $saldo = number_format($mresult['saldo'],0,".",".");
                    if($mresult['estado'] == "A")$estado="Activo";
                    if($mresult['entregada'] == "S")$entr="Si";
                    $response .="<tr style='text-align: left;'>";
                    $response .="<td align='right'>{$mresult['numtar']}</td>";
                    $response .="<td align='right'>{$fecexp}</td>";
                    $response .="<td align='right'>{$fecsol}</td>";
                    $response .="<td align='right'>{$fecent}</td>";
                    $response .="<td>{$entr}</td>";
                    $response .="<td style='text-align: left'>{$estado}</td>";
                    $response .="<td align='right'>$ {$saldo}</td>";
                    $response .="</tr>";
                }   
            }
        }        
        if($a == 0){
            $response .="<tr>";
            $response .="<td  colspan='7'> No Presentan Historico de Tarjetas</td>";
            $response .="</tr>";
        }

        $response .="</tbody>";
        $response .="</table>";
        $response .="</div>";
        $response .="<br>";
        $response .="<h3>Tarjetas Inactivas</h3>";
        $response .="<div>";
        $response .="<table class='table table-bordered'>";
        $response .="<thead>";
        $response .="<tr class='info' cellspacing='10'>";
        $response .="<td><b>N&uacute;mero Tarjeta</b></td>";
        $response .="<td><b>Fecha Expedici&oacute;n</b></td>";
        $response .="<td><b>Fecha Solicitud</b></td>";
        $response .="<td><b>Fecha Entrega</b></td>";
        $response .="<td><b>Estado</b></td>";
        $response .="</tr>";
        $response .="</thead>";
        $response .="<tbody>";
        $b=0;
        if($result!=false){
            foreach($result as $mresult){
                if($mresult['estado'] != "A"){
                    $b++;
                    $fecexp = "";
                    $fecsol = "";
                    $fecent = "";
                    $fecven = "";
                    if($mresult['fecexp'] !="")$fecexp = date("Y-m-d",strtotime($mresult['fecexp']));
                    if($mresult['fecsol'] !="")$fecsol = date("Y-m-d",strtotime($mresult['fecsol']));
                    if($mresult['fecent'] !="") $fecent = date("Y-m-d",strtotime($mresult['fecent']));
                    if($mresult['fecven'] !="") $fecven = date("Y-m-d",strtotime($mresult['fecven']));
                    $saldo = number_format($mresult['saldo'],0,".",".");
                    if($mresult['estado'] == "I")$estado="Inactivo";
                    if($mresult['estado'] == "B")$estado="Bloqueada";
                    $response .="<tr style='text-align: left;'>";
                    $response .="<td align='right'>{$mresult['numtar']}</td>";
                    $response .="<td align='right'>{$fecexp}</td>";
                    $response .="<td align='right'>{$fecsol}</td>";
                    $response .="<td>{$fecent}</td>";
                    $response .="<td style='text-align: center;'>{$estado}</td>";
                    $response .="</tr>";
                } 
            }
        }
        if($b==0){
            $response .="<tr>";
            $response .="<td  colspan='7'> No Presentan Historico de Tarjetas</td>";
            $response .="</tr>";
        }

        $response .="</tbody>";
        $response .="</table>";
        $response .="</div>";
        echo $response;
    }
    //Consulta de planilla trabajador
    public function planillaTra_viewAction(){
        $this->setResponse("ajax");
        echo parent::showTitle('Consulta de Planilla');     
    }

    public function planillaTraAction(){
        $this->setResponse("ajax");
        $perini = $this->getPostParam('perini');
        $perfin = $this->getPostParam('perfin');
        $param = array("cedtra"=>Session::getData('documento'),"perini"=>$perini,"perfin"=>$perfin);
        $response = "";
        $response .="<br>";
        $response .="<div>";
        $response .="<table class='table table-bordered'>";
        $response .="<thead>";
        $response .="<tr class='info' cellspacing='10'>";
        $response .="<td><b>Nit</td>";
        $response .="<td><b>Raz&oacute;n Social</td>";
        $response .="<td><b>Periodo</b></td>";
        $response .="<td><b>Valor Aporte</b></td>";
        //$response .="<td>Valor Consignada</td>";
        $response .="<td><b>Indice de aporte</b></td>";
        $response .="<td><b>Valor de Devolucion</b></td>";
        $response .="<td><b>Fecha Pago</b></td>";
        $response .="</tr>";
        $response .="</thead>";
        $response .="<tbody>";
        $result = parent::webService("PlanillaTra", $param);
        if($result!=false){
            foreach($result as $mresult){
                $fecha = date("Y-m-d",strtotime($mresult['fecpag']));
                $valnom = number_format(round($mresult['valnom']));
                $valdev = number_format(round($mresult['devolucion']));
                $response .="<tr style='text-align: left;'>";
                $response .="<td>{$mresult['nit']}</td>";
                $response .="<td>{$mresult['razsoc']}</td>";
                $response .="<td align='right'>{$mresult['perapo']}</td>";
                $response .="<td align='right'>$ {$valnom}</td>";
                $response .="<td align='right'> {$mresult['indapo']}</td>";
                $response .="<td align='right'>$ {$valdev}</td>";
                $response .="<td align='right'>{$fecha}</td>";
                $response .="</tr>";
            }        
        }else{
                $response .="<tr style='text-align: left;'>";
                $response .="<td colspan='7' align='center'> La consulta no presenta registros en este rango de periodos</td>";
                $response .="</tr>";
        }
        $response .="</tbody>";
        $response .="</table>";
        $response .="</div>";

        return $this->renderText(json_encode($response));
    }

//Certificados Trabajador
    public function certificadoAfiliacion_viewAction(){
      $this->setResponse("ajax");
      echo parent::showTitle('Certificados de Afiliaci&oacute;n');
      $param  = array("cedtra"=>Session::getData('documento'));
      $benefic = parent::webService("Nucfambeneficiarios", $param);      
      $this->setParamToView("benefic",$benefic);
    }
    public function generarCertificadosAction(){
        $this->setResponse("ajax");
        $tipfor = $this->getPostParam("tipfor");
        $param  = array("cedtra"=>Session::getData('documento'));
        $result = parent::webService("Nucfamtrabajador", $param);
        $codcat = $result[0]['tracodcat'];
        $fecaf = date("Y-m-d",strtotime($result[0]['trafecafi']));
        $fecafi = new Date($fecaf);
        $fecha  = new Date();
        $fecre = date("Y-m-d",strtotime($result[0]['trafecret']));
        //$fecre = date("Y-m-d",strtotime($result[0]['fecafi'])); // Linea a decomentarear despues de actualizar los Webservices
        $fecret = new Date($fecre);
        $texto2="";
        $direccion = $result[0]['tradireccion'];
        $telefono = $result[0]['tratelefono'];
        $codciu = $result[0]['codciu'];
        $_estado = $result[0]['traestado'];
        $coddep = $result[0]['coddep'];
        //Debug::addVariable("a",$result);
        //throw new DebugException(0);
        switch($tipfor){
            case"a":
                $datemp = parent::webService("DatosEmpTra", $param);
                $modalidad = parent::webService("Modalidad", $param);
                $texto = array();
                for($a = 0; $a < count($datemp); $a++){
                    if($result[$a]['traestado'] == 'P'){
                        //$response = parent::errorFunc("El trabajador se encuentra en estado Pendiente");
                        //return $this->renderText(json_encode($response));
                        continue;
                    }
                    $fecaf = date("Y-m-d",strtotime($result[$a]['trafecafi']));
                    $fecafi = new Date($fecaf);
                    $texto[$a] = '';
                    $texto[$a] = utf8_decode("Me permito certificar que el(la) se&ntilde;or(a)  ".SESSION::getDATA('nombre')."  identificado(a) con c&eacute;dula de ciudadan&iacute;a No.  ".SESSION::getDATA('documento').",  se encuentra afiliado(a) a nuestra Entidad como ".$result[$a]['tipafi']." en la modalidad de ".$modalidad[$a]['detalle']." por intermedio de la empresa ".$datemp[$a]['razsoc']." Nit  ".$result[$a]['tranit']." con Fecha de Ingreso ".$fecafi->getDay()." ".$fecafi->getMonthName()." de ".$fecafi->getYear().".");
                }
                //texto carta afiliacion
                //$texto = "Me permito certificar que el(la) se&ntilde;or(a)  ".SESSION::getDATA('nombre')."  identificado(a) con c&eacute;dula de ciudadan&iacute;a No.  ".SESSION::getDATA('documento').",  se encuentra afiliado(a) a nuestra Entidad desde el ".$fecret->getDay()." ".$fecret->getMonthName()." de ".$fecret->getYear()." como ".$result[0]['tipafi']." en la modalidad de ".$modalidad[0]['detalle']."  con Fecha de Ingreso ".$fecafi->getDay()." ".$fecafi->getMonthName()." de ".$fecafi->getYear().".";
                $texto2="";
                break;
            case"b":
                //texto carta afiliado inactivo
                $result = parent::webService("Nucfamtrabajadorina", $param);
                //$fecre = date("Y-m-d",strtotime($result[0]['trafecret']));
                foreach($result as $x){
                    $fec = $x['fecret'];
                }
                $fecre = date("Y-m-d",strtotime($fec));
                $direccion = $result[0]['tradireccion'];
                $codciu = $result[0]['codciu'];
                $coddep = $result[0]['coddep'];
                $telefono = $result[0]['tratelefono'];
                $fecret = new Date($fecre);
                //$texto = "Me permito certificar que el(la) se&ntilde;or(a) ".$result[0]['tranombre']." identificado(a) con la c&eacute;dula de Ciudadan&iacute;a No. ".SESSION::getData('documento').", se encuentra INACTIVO(A) en nuestra entidad desde el ({$fecret->getDay()}) de ({$fecret->getMonthName()}) de ({$fecret->getYear()}) y su categor&iacute;a tarifaria se establece como Categor&iacute;a ".SESSION::getData('codcat').".";
                $texto = "Me permito certificar que el(la) se&ntilde;or(a) ".$result[0]['tranombre']." identificado(a) con la c&eacute;dula de Ciudadan&iacute;a No. ".SESSION::getData('documento').", se encuentra INACTIVO(A) en nuestra entidad desde el ({$fecret->getDay()}) de ({$fecret->getMonthName()}) de ({$fecret->getYear()}).";
                $texto2="";
                break;
            case"c":
                $datemp = parent::webService("DatosEmpTra", $param);
                $modalidad = parent::webService("Modalidad", $param);
                $texto = array();
                for($a = 0; $a < count($datemp); $a++){
                    $texto[$a] = '';
                    if($result[$a]['traestado'] == 'P'){
                        //$response = parent::errorFunc("El trabajador se encuentra en estado Pendiente");
                        //return $this->renderText(json_encode($response));
                        continue;
                    }
                    $fecaf = date("Y-m-d",strtotime($result[$a]['trafecafi']));
                    $fecafi = new Date($fecaf);
                    $fecre = date("Y-m-d",strtotime($result[$a]['trafecret']));
                    $fecret = new Date($fecre);
                    //texto carta afiliado con categoria
                    //$texto[$a] = utf8_decode("Me permito certificar que el(la) se&ntilde;or(a) ".SESSION::getDATA('nombre')." identificado(a) con c&eacute;dula de ciudadan&iacute;a No. ".SESSION::getDATA('documento').", se encuentra afiliado(a) a nuestra Entidad desde el  ".$fecret->getDay()." ".$fecret->getMonthName()." de ".$fecret->getYear() ." en la modalidad de ".$modalidad[0]['detalle']." por intermedio de la empresa ".$datemp[$a]['razsoc']."  Nit No. ".$datemp[$a]['nit'].", con Fecha de ingreso el ".$fecafi->getDay()." ".$fecafi->getMonthName()." de ".$fecafi->getYear() .".  "."y su categor&iacute;a tarifaria se establece como Categor&iacute;a ".SESSION::getData('codcat'));
                    $texto[$a] = utf8_decode("Me permito certificar que el(la) se&ntilde;or(a) ".SESSION::getDATA('nombre')." identificado(a) con c&eacute;dula de ciudadan&iacute;a No. ".SESSION::getDATA('documento').", se encuentra afiliado(a) a nuestra Entidad como ".$result[$a]['tipafi']." en la modalidad de ".$modalidad[0]['detalle']." por intermedio de la empresa ".$datemp[$a]['razsoc']."  Nit No. ".$datemp[$a]['nit'].", con Fecha de ingreso el ".$fecafi->getDay()." ".$fecafi->getMonthName()." de ".$fecafi->getYear() .".  "."y su categor&iacute;a tarifaria se establece como Categor&iacute;a ".SESSION::getData('codcat'));
                }
                for($a = 0; $a < count($datemp); $a++){
                    if($texto[$a] != ''){
                        continue;
                    }else{
                        $response = parent::errorFunc("El trabajador se encuentra en estado Pendiente");
                        return $this->renderText(json_encode($response));
                    }
                }
                break;
                //$m=("y su categor&iacute;a tarifaria se establece como Categor&iacute;a ".SESSION::getData('codcat').");
            case"d":
                $datemp = parent::webService("DatosEmpTra", $param);
                $modalidad = parent::webService("Modalidad", $param);
                //texto carta afiliado con trayectoria
                $texto = utf8_decode("Me permito certificar que el (la) se&ntilde;or(a)  ".Session::getDATA('nombre')." identificado(a) con c&eacute;dula de ciudadan&iacute;a No. ".SESSION::getDATA('documento').", estuvo afiliado(a) a nuestra Entidad por intermedio de la siguiente empresa as&iacute;");
                if(trim($result[0]['traestado'])=='A'){
                    $texto2 = "Actualmente se encuentra afiliado(a) a nuestra Entidad en la modalidad de ".$modalidad[0]['detalle']." por intermedio de la empresa ".$datemp[0]['razsoc']." Nit No. ".$datemp[0]['nit']." con fecha de ingreso ".$fecafi->getDay()." de ".$fecafi->getMonthName()." de ".$fecafi->getYear().".";
                }
                break;
            case"e":
                //texto carta afiliado con ultima trayectoria
                if($result == FALSE){
                    $result = parent::webService("Nucfamtrabajadorina", $param);
                }
                $fecre = date("Y-m-d",strtotime($result[0]['trafecret']));
                //$fecre = date("Y-m-d",strtotime($result[0]['fecret']));
                $direccion = $result[0]['tradireccion'];
                $telefono = $result[0]['tratelefono'];
                $codciu = $result[0]['codciu'];
                $coddep = $result[0]['coddep'];

                $texto = "Me permito certificar que el(la) se&ntilde;ora ".SESSION::getDATA('nombre')." identificado con c&eacute;dula de ciudadan&iacute;a No. ".SESSION::getDATA('documento').", estuvo afiliada a nuestra Entidad por intermedio de la siguiente empresa as&iacute;:";
                break;
            case"f":
                $_fecret = "";
                $_result = parent::webService("Trayectoria", $param);
                foreach($_result as $_mresult){
                    if($_mresult['estado']=='A'){
                        continue;
                    }
                    if($_mresult['fecret'] == ''){
                        continue;
                    }
                    $_fecafi = new Date(date("Y-m-d",strtotime($_mresult['fecing'])));
                    $_fecret = new Date(date("Y-m-d",strtotime($_mresult['fecret'])));
                    break;
                }
                if($_fecret==""){
                    $texto = "Me permito certificar que el(la) se&ntilde;or(a) ".SESSION::getDATA('nombre')." identificado(a) con c&eacute;dula de ciudadani&iacute;a No. ".SESSION::getData('documento').", esta afiliado(a) a nuestra Entidad por intermedio de la Empresa ".$_mresult['razsoc'].". en ".SESSION::getData('categoria').". Nit.".$_mresult['nit'].", desde el per&iacute;odo del ".$_fecafi->getDay()." de ".$_fecafi->getMonthName()." de ".$_fecafi->getYear()." .";
                }else{
                    $texto = "Me permito certificar que el(la) se&ntilde;or(a) ".SESSION::getDATA('nombre')." identificado(a) con c&eacute;dula de ciudadan&iacute;a No. ".SESSION::getData('documento').", estuvo afiliado(a) a nuestra Entidad por intermedio de la Empresa ".$_mresult['razsoc'].". en ".SESSION::getData('categoria').". Nit.".$_mresult['nit'].", durante los per&iacute;odos del ".$_fecafi->getDay()." de ".$_fecafi->getMonthName()." de ".$_fecafi->getYear()." hasta el ".$_fecret->getDay()." de ".$_fecret->getMonthName()." de ".$_fecret->getYear().".";
                }
                break;
            case"g":
                $datemp = parent::webService("DatosEmpTra", $param);
                $modalidad = parent::webService("Modalidad", $param);
                //texto carta afiliado con beneficiarios
                $texto = "Me permito certificar que el(la) se&ntilde;or(a) ".SESSION::getDATA('nombre')." identificado(a) con la c&eacute;dula No. ".SESSION::getDATA('documento').", se encuentra afiliado(a) a nuestra Entidad en la modalidad de ".$modalidad[0]['detalle']." por intermedio de la empresa ".$datemp[0]['razsoc']." Nit. ". $datemp[0]['nit']." con fecha de ingreso ".$fecafi->getDay()." de ".$fecafi->getMonthName()." de ".$fecafi->getYear()."y tiene como Beneficiarios a:";
                break;
            case"h":
                $datemp = parent::webService("DatosEmpTra", $param);
                $result = parent::webService("Nucfambeneficiarios", $param);
                if($result == false){
                    $response = parent::errorFunc("El trabajador no tiene beneficiarios registrados");
                    return $this->renderText(json_encode($response));
                }else{
                    $datemp = parent::webService("DatosEmpTra", $param);
                    $result = parent::webService("AfiliadoConsultar", array('tipoDocumento'=>Session::getData('coddoc'),"identificacion"=>Session::getData('documento')));
                    if($result['AfiliadoConsultarResult']['Beneficiarios'] == false){
                        $response = parent::errorFunc("El trabajador no tiene beneficiarios registrados");
                        return $this->renderText(json_encode($response));
                    }else{
                        $texto = array();
                        $a = 0;
                        foreach($result['AfiliadoConsultarResult']['Beneficiarios'] as $key => $value){
                            if(isset($value["Celular"])){
                                $value[0] = $value;
                            }
                            foreach($value as $mvalue){
                              if($mvalue['Parentesco']['Id'] == '34' && $mvalue['Conviven'] == 'N'){
                                  continue;
                              }
                                $texto[$a] = '';
                                $parent="";
                                $giro = "";
                                $fecafi = date("Y-m-d",strtotime($mvalue['FechaAfiliacion']));
                                $nombre = $mvalue['PrimerNombre']." ".$mvalue['SegundoNombre']." ".$mvalue['PrimerApellido']." ".$mvalue['SegundoApellido'];
                                $texto[$a] = utf8_decode("Me permito certificar que $nombre se encuentra activo(a) desde $fecafi como beneficiario ".$mvalue['Parentesco']['Detalle']." de el(la) se&ntilde;or(a) ".Session::getData('nombre')." identificado(a) con cedula N ".Session::getData('documento').".");
                                $a++;
                            }
                        }
                        for($a = 0; $a < count($datemp); $a++){
                            if($texto[$a] != ''){
                                continue;
                            }else{
                                $response = parent::errorFunc("El trabajador se encuentra en estado Pendiente");
                                return $this->renderText(json_encode($response));
                            }
                        }
                    }
                }
                break;
            case"i":
                $datemp = parent::webService("DatosEmpTra", $param);
                $modalidad = parent::webService("Modalidad", $param);
                $texto = "Me permito certificar que el(la) se&ntilde;or(a) ".SESSION::getDATA('nombre')." identificado(a) con C&eacute;dula de ciudadan&iacute;a No. ".SESSION::getData('documento').", se encuentra afiliado(a) a nuestra Entidad en la modalidad de ".$modalidad[0]['detalle']." por intermedio de la empresa ".$datemp[0]['razsoc']." Nit No. ".$datemp[0]['nit'].", desde el ".$fecafi->getDay()." de ".$fecafi->getMonthName()." de ".$fecafi->getYear().".\n\nUna vez revisada la informaci&oacute;n en nuestro sistema, hemos verificado que Seg&uacute;n nuestra base de datos No registra afiliaci&oacute;n de n&uacute;cleo familiar.";
                break;
            case"j":
                $datemp = parent::webService("DatosEmpTra", $param);
                $result = parent::webService("AfiliadoConsultar", array('tipoDocumento'=>Session::getData('coddoc'),"identificacion"=>Session::getData('documento')));
                if($result['AfiliadoConsultarResult']['Beneficiarios'] == false){
                    $response = parent::errorFunc("El trabajador no tiene beneficiarios registrados");
                    return $this->renderText(json_encode($response));
                }else{
                    $texto = array();
                    $a = 0;
                    foreach($result['AfiliadoConsultarResult']['Beneficiarios'] as $key => $value){
                            if(isset($value["Celular"])){
                                $value[0] = $value;
                            }
                        foreach($value as $mvalue){
                              if($mvalue['Parentesco']['Id'] == '34' && $mvalue['Conviven'] == 'N'){
                                  continue;
                              }
                            $texto[$a] = '';
                            $parent="";
                            $giro = "";
                            $fecafi = date("Y-m-d",strtotime($mvalue['FechaAfiliacion']));
                            $nombre = $mvalue['PrimerNombre']." ".$mvalue['SegundoNombre']." ".$mvalue['PrimerApellido']." ".$mvalue['SegundoApellido'];
                            $texto[$a] = utf8_decode("Me permito certificar que $nombre se encuentra activo(a) desde $fecafi como beneficiario ".$mvalue['Parentesco']['Detalle']." de el(la) se&ntilde;or(a) ".Session::getData('nombre')." identificada con cedula N ".Session::getData('documento').", desde $fecafi en la Modalidad ".$mvalue['Giro']." y su categoria tarifaria se establece como {$result['AfiliadoConsultarResult']['Categoria']}");
                            $a++;
                        }
                    }
                    for($a = 0; $a < count($datemp); $a++){
                        if($texto[$a] != ''){
                            continue;
                        }else{
                            $response = parent::errorFunc("El trabajador se encuentra en estado Pendiente");
                            return $this->renderText(json_encode($response));
                        }
                    }
                }
                /*
                   $result = parent::webService("Nucfambeneficiarios", $param);
                   if($result == false){
                   $response = parent::errorFunc("El trabajador no tiene beneficiarios registrados");
                   return $this->renderText(json_encode($response));
                   }else{
                   $texto = array();
                   for($a = 0; $a < count($result); $a++){
                   $texto[$a] = '';
                   $benef = $result[$a]['beneficiario'];
                   $datben = parent::webService("Datosfamiliar",array("documento"=>$benef,"cedtra"=>Session::getDATA('documento')));      
                   $parent="";
                   switch(trim($datben[0]['parent'])){
                   case 34:
                   $parent="CONYUGE";
                   break;
                   case 35:
                   $parent="HIJO(A)";
                   break;
                   case 36:
                   $parent="PADRE/MADRE";
                   break;
                   case 37:
                   $parent="HERMANO(A)";
                   break;
                   case 38:
                   $parent="HIJASTRO(A)";
                   break;
                   }
                   $giro = "";
                   switch(trim($datben[0]['giro'])){
                   case 'N':
                   $giro="SERVICIOS";
                   break;
                   case 'S':
                   $giro="SUBSIDIO";
                   break;
                   }
                   $fecafi = date("Y-m-d",strtotime($datben[0]['fecafi']));
                   if($datben[0]['categoria'] == '' || $datben[0]['categoria'] == NULL){
                   $datben[0]['categoria'] = $codcat;
                   }
                   $texto[$a] = utf8_decode("Me permito certificar que {$datben[0]['nombre']} se encuentra activo(a) desde $fecafi como beneficiario $parent de el(la) se&ntilde;or(a) ".Session::getData('nombre')." identificada con cedula N ".Session::getData('documento').", desde $fecafi en la Modalidad $giro y su categoria tarifaria se establece como {$datben[0]['categoria']}");
                   }
                   for($a = 0; $a < count($datemp); $a++){
                   if($texto[$a] != ''){
                   continue;
                   }else{
                   $response = parent::errorFunc("El trabajador se encuentra en estado Pendiente");
                   return $this->renderText(json_encode($response));
                   }
                   }
                   }
                 */
                break;
            case"k":
                $datemp = parent::webService("DatosEmpTra", $param);
                if($result == FALSE){
                    $result = parent::webService("Nucfamtrabajadorina", $param);
                }
                $texto = array();
                for($a = 0; $a < count($datemp); $a++){
                    $texto[$a] = '';
                    if($result[$a]['traestado'] == 'P'){
                        continue;
                    }
                    $codciu = $result[$a]['codciu'];
                    $coddep = $result[$a]['coddep'];
                    $fecaf = date("Y-m-d",strtotime($result[$a]['trafecafi']));
                    $fecafi = new Date($fecaf);
                    $fecre = date("Y-m-d",strtotime($result[$a]['trafecret']));
                    $fecret = new Date($fecre);
                    $texto[$a] = utf8_decode("Me permito certificar que el (la) se&ntilde;or (a) ".SESSION::getDATA('nombre')."  identificado (a) con cedula de ciudadan&iacute;a N. ".SESSION::getDATA('documento').", se encuentra inactivo (a) en nuestra entidad desde ({$fecret->getDay()}) de ({$fecret->getMonthName()}) de ({$fecret->getYear()}) y su categor&iacute;a tarifaria se estableci&oacute; como  ".SESSION::getData('codcat'));
                }
                for($a = 0; $a < count($datemp); $a++){
                    if($texto[$a] != ''){
                        continue;
                    }else{
                        $response = parent::errorFunc("El trabajador se encuentra en estado Pendiente");
                        return $this->renderText(json_encode($response));
                    }
                }
                break;
        }
        $response = parent::startFunc();
        $file = "";
        $file = self::certificado($texto,$tipfor,$datemp,$texto2,$direccion,$telefono,$codciu,$coddep,$_estado);
    }
    public function certificado($texto,$tipfor,$datemp,$texto2,$direccion,$telefono,$codciu,$coddep,$_estado){
        $fecha = new Date();
        $ano = $fecha->getYear();
        $mes = $fecha->getMonthName();
        $dia = $fecha->getDay();
        $param = array("cedtra"=>Session::getData('documento'));
        $formu = new FPDF('P','mm','Letter');
        $formu->SetMargins(20,25);
        $formu->AddFont('Calibri','','Calibri.php');
        $formu->AddFont('Calibri','BI','Calibri Bold Italic.php');
        $formu->AddFont('Calibri','I','Calibri Italic.php');
        $formu->AddPage();
        $formu->SetAutoPageBreak(true);
        $formu->SetFillColor(236,248,240); 
        $formu->SetFont('Calibri','I','12');
        $formu->Ln();
        $formu->Cell(175,4,"",0,1,"L",0,0);
        $formu->Image('public/img/comfamiliar-logo.jpg',10,15,63,18);
        $idmer20 = $this->Mercurio20->findBySql("SELECT MAX(id) as id FROM mercurio20");
        $mercurio20 = $this->Mercurio20->findBySql("SELECT * FROM mercurio20 WHERE id='{$idmer20->getId()}'");
        $fecha20 = $mercurio20->getFecha();
        $hora20 = $mercurio20->getHora();
        $codbar = $idmer20->getId()."-".$mercurio20->getDocumento();
        $formu->Codabar(120,10,$codbar);
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Cell(175,4,"",0,1,"L",0,0);
        $formu->Cell(175,4,"Neiva, ".$dia." de ".$mes." de ".$ano,0,0,"L",0,0);
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Cell(15,4,html_entity_decode("Se&ntilde;ores"),0,1,"L",0,0);
        $formu->SetFont('Calibri','BI','12');
        $formu->Cell(190,4,html_entity_decode(Session::getDATA("nombre")),0,1,"L",0,0);
        $formu->SetFont('Calibri','I','12');
        $formu->Cell(190,4,"$direccion",0,1,"L",0,0);
        $formu->Cell(190,4,"Telefono $telefono",0,1,"L",0,0);
        $gener08 = $this->Gener08->findFirst("codciu = $codciu");
        $gener07 = $this->Gener07->findFirst("coddep = $coddep");
        $formu->Cell(190,4,"{$gener08->getDetciu()} ({$gener07->getDetdep()})",0,1,"L",0,0);
        //$formu->Cell(190,4,"$codciu ($coddep)",0,1,"L",0,0);
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $asunto="";
        if($tipfor == "c"){
            $formu->Cell(190,4,html_entity_decode("Asunto: Certificado de Afiliaci&oacute;n con categoria . "),0,1,"L",0,0);
            $asunto="Asunto: Certificado de Afiliaci&oacute;n con categoria . ";
        }elseif ($tipfor=="a"){
            $formu->Cell(190,4,html_entity_decode("Asunto: Certificado de Afiliaci&oacute;n."),0,1,"L",0,0);
            $asunto="Asunto: Certificado de Afiliaci&oacute;n.";
        }elseif($tipfor=="d"){
            $formu->Cell(190,4,html_entity_decode("Asunto: Certificado de Afiliaci&oacute;n con trayectoria."),0,1,"L",0,0);
            $asunto="Asunto: Certificado de Afiliaci&oacute;n con trayectoria.";
        }elseif($tipfor=="f"){
            $formu->Cell(190,4,html_entity_decode("Asunto: Certificado de ultima trayectoria."),0,1,"L",0,0);
            $asunto="Asunto: Certificado de ultima trayectoria.";
        }elseif($tipfor=="g"){
            $formu->Cell(190,4,html_entity_decode("Asunto: Certificado con beneficiarios."),0,1,"L",0,0);
            $asunto="Asunto: Certificado con beneficiarios.";
        }elseif($tipfor=="i"){
            $formu->Cell(190,4,html_entity_decode("Asunto: Certificado sin beneficiarios."),0,1,"L",0,0);
            $asunto="Asunto: Certificado sin beneficiarios.";
        } elseif($tipfor=="j"){
            $formu->Cell(190,4,html_entity_decode("Asunto: Certificado de Afiliaci&oacute;n con categoria."),0,1,"L",0,0);
            $asunto="Asunto: Certificado de Afiliaci&oacute;n con categoria . ";
        }elseif ($tipfor=="k"){
            $formu->Cell(190,4,html_entity_decode("Asunto: Certificado de Afiliaci&oacute;n Inactiva con categoria."),0,1,"L",0,0);
            $asunto="Asunto: Certificado de Afiliaci&oacute;n Inactiva con categoria.";
        }elseif($tipfor=="h"){
            $formu->Cell(190,4,html_entity_decode("Asunto: Certificado de beneficiario(a) Individual."),0,1,"L",0,0);
            $asunto="Asunto: Certificado de beneficiario(a) Individual.";
        }
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Cell(190,4,"Cordial Saludo.",0,1,"L",0,0);
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        if($tipfor == "c"){
            $formu->Multicell(175,4,html_entity_decode("$texto[0]"),0,"J",0);
            $formu->Ln();
        } elseif ($tipfor=="b" || $tipfor=="f" || $tipfor=="g" || $tipfor=="i"){
            $formu->Multicell(175,4,html_entity_decode("$texto"),0,"J",0);
        }elseif($tipfor=="d" || $tipfor=="e"){
            $formu->MUlticell(175,4,html_entity_decode("$texto"),0,"J",0);	
        }elseif($tipfor=="j" || $tipfor=="a" || $tipfor=="k" || $tipfor=="h"){
            $formu->MUlticell(175,4,html_entity_decode("{$texto[0]}"),0,"J",0);	
        }
        $formu->Ln();
        $formu->Ln();
        if($tipfor =='d' || $tipfor=='e'){
            $formu->Cell(25,4,"Nit",1,0,"C",0,0);
            $formu->Cell(70,4,"Razon Social ",1,0,"C",0,0);
            $formu->Cell(40,4,"Fecha de Ingreso",1,0,"C",0,0);
            $formu->Cell(40,4,"Fecha de Retiro ",1,1,"C",0,0);
            $formu->Cell(10,4,"",0,0,"L",0,0);
            $result = parent::webService("Trayectoria", $param);
            $formu->Ln();
            if($result == false){
                $formu->Cell(150,4,"Favor Solicitar a la Caja de Compensacion",1,0,"C",0,0);
                $estado = "";
            }else{
                foreach($result as $mresult){
                    if($mresult['estado']=='A'){
                        $estado = $mresult['estado'];
                        continue;
                    }else{
                        $estado = "I";
                    }
                    if($mresult['fecret'] == ''){
                        //Este continue es segun la peticion para que no aparesca empresa actual en la trayectoria
                        continue;
                    }
                    $fecing = date("Y-m-d",strtotime($mresult['fecing']));
                    $fecret = date("Y-m-d",strtotime($mresult['fecret']));
                    $formu->SetFont('Calibri','I','8');
                    //$formu->Cell(10,4,"",0,0,"L",0,0);
                    $formu->Cell(25,4,$mresult['nit'],0,0,"C",0,0);
                    $formu->SetFont('Calibri','I','8');
                    $formu->Cell(70,4,substr($mresult['razsoc'],0,45),0,0,"L",0,0);
                    $formu->SetFont('Calibri','I','8');
                    $formu->Cell(40,4,$fecing,0,0,"C",0,0);
                    $formu->Cell(40,4,$fecret,0,1,"C",0,0);
                }

                $formu->SetFont('Calibri','I','12');
            }
        }
        if($tipfor =='g'){
            $formu->SetFont('Calibri','BI','10');
            $formu->Cell(15,4,"",0,0,"C",0,0);
            $formu->Cell(85,4,"Nombre Beneficiario",1,0,"C",0,0);
            $formu->Cell(35,4,"Parentesco ",1,0,"C",0,0);
            $formu->Cell(25,4,"Estado ",1,1,"C",0,0);
            $result = parent::webService("AfiliadoConsultar", array('tipoDocumento'=>Session::getData('coddoc'),"identificacion"=>Session::getData('documento')));
            $formu->SetFont('Calibri','I','9');
            if($result['AfiliadoConsultarResult']['Beneficiarios'] == false){
                $formu->Cell(150,4,"Favor Solicitar a la Caja de Compensacion",1,0,"C",0,0);
                $estado = "";
            }else{
                foreach($result['AfiliadoConsultarResult']['Beneficiarios'] as $key => $value){
                            if(isset($value["Celular"])){
                                $value[0] = $value;
                            }
                    foreach($value as $mvalue){
                      if($mvalue['Parentesco']['Id'] == '34' && $mvalue['Conviven'] == 'N'){
                          continue;
                      }
                    $nombre = $mvalue['PrimerNombre']." ".$mvalue['SegundoNombre']." ".$mvalue['PrimerApellido']." ".$mvalue['SegundoApellido'];
                    $formu->Cell(15,4,"",0,0,"C",0,0);
                    $formu->Cell(85,4,$nombre,1,0,"L",0,0);
                    $formu->Cell(35,4,utf8_decode($mvalue['Parentesco']['Detalle']),1,0,"L",0,0);
                    $formu->Cell(25,4,$result['AfiliadoConsultarResult']['Categoria'],1,1,"C",0,0);
                }
            }

                $formu->SetFont('Calibri','I','12');
            }
        }
        $formu->Ln();
        if($tipfor =='d' && $_estado=='A'){
            $formu->Ln();
            $formu->Multicell(175,4.5,html_entity_decode($texto2),0,"J",0);	
            $formu->Ln();
            $formu->Cell(190,4,"Atentamente:",0,1,"L",0,0);
            $y = $formu->getY(); 
            $formu->Ln();
            $formu->Ln();
            $formu->Ln();
            $formu->Image('public/img/firmas/firmaYazminOspina.png',15,$y,60,18);
            $formu->SetFont('Calibri','BI','12');
            $formu->Cell(190,4,"YAZMIN OSPINA GAITAN ",0,1,"L",0,0);
            $formu->SetFont('Calibri','I','12');
            $formu->Cell(190,4,"COORDINADORA AFILIACIONES Y SUBSIDIO",0,1,"L",0,0);
        }else{
            $formu->Ln();
            $formu->Ln();
            $formu->Cell(190,4,"Atentamente:",0,1,"L",0,0);
            $formu->Ln();
            $formu->Ln();
            $formu->Ln();
            $formu->Ln();
            $formu->Ln();
            $y = $formu->getY(); 
            $formu->Ln();
            $formu->Ln();
            $formu->Ln();
            $formu->Image('public/img/firmas/firmaYazminOspina.png',15,$y,60,18);
            $formu->SetFont('Calibri','BI','12');
            $formu->Cell(190,4,"YAZMIN OSPINA GAITAN ",0,1,"L",0,0);
            $formu->SetFont('Calibri','I','12');
            $formu->Cell(190,4,"COORDINADORA AFILIACIONES Y SUBSIDIO",0,1,"L",0,0);
        }
        $formu->Ln();
        $formu->Ln();
        $formu->SetFont('Calibri','I','8');
        //$formu->Cell(190,4,html_entity_decode("Y. OSPINA GAITAN / M.Y. MU&Ntilde;OZ LOZANO"),0,1,"L",0,0);
        //$formu->Ln();
        //$formu->Cell(190,4,html_entity_decode("Copia Interna: Centro de Documentaci&oacute;n e Informaci&oacute;n "),0,1,"L",0,0);
        $formu->Image('public/img/portal/piepaginaCartas.png',1,237,214,42);
        $formu->Ln();
        $arr = count($texto);
        if($arr > 1){
            $i=1;
            for( $i; $i< count($texto);$i++){
                $formu->AddPage();
                $formu->SetFillColor(236,248,240); 
                $formu->SetFont('Calibri','I','12');
                $formu->Ln();
                $formu->Cell(175,4,"",0,1,"L",0,0);
                $formu->Image('public/img/comfamiliar-logo.jpg',10,15,63,18);
                $idmer20 = $this->Mercurio20->findBySql("SELECT MAX(id) as id FROM mercurio20");
                $mercurio20 = $this->Mercurio20->findBySql("SELECT * FROM mercurio20 WHERE id='{$idmer20->getId()}'");
                $fecha20 = $mercurio20->getFecha();
                $hora20 = $mercurio20->getHora();
                $codbar = $idmer20->getId()."-".$mercurio20->getDocumento();
                $formu->Codabar(120,10,$codbar);
                $formu->Ln();
                $formu->Ln();
                $formu->Ln();
                $formu->Ln();
                $formu->Ln();
                $formu->Ln();
                $formu->Ln();
                $formu->Cell(175,4,"",0,1,"L",0,0);
                $formu->Cell(175,4,"Neiva, ".$dia." de ".$mes." de ".$ano,0,0,"L",0,0);
                $formu->Ln();
                $formu->Ln();
                $formu->Ln();
                $formu->Ln();
                $formu->Ln();
                $formu->Ln();
                $formu->Ln();
                $formu->Cell(15,4,html_entity_decode("Se&ntilde;ores"),0,1,"L",0,0);
                $formu->SetFont('Calibri','BI','12');
                $formu->Cell(190,4,Session::getDATA("nombre"),0,1,"L",0,0);
                $formu->SetFont('Calibri','I','12');
                $formu->Cell(190,4,"$direccion",0,1,"L",0,0);
                $formu->Cell(190,4,"Telefono $telefono",0,1,"L",0,0);
                $gener08 = $this->Gener08->findFirst("codciu = $codciu");
                $gener07 = $this->Gener07->findFirst("coddep = $coddep");
                $formu->Cell(190,4,"{$gener08->getDetciu()} ({$gener07->getDetdep()})",0,1,"L",0,0);
                $formu->Ln();
                $formu->Ln();
                $formu->Ln();
                $formu->Cell(190,4,html_entity_decode("$asunto"),0,1,"L",0,0);
                $formu->Ln();
                $formu->Ln();
                $formu->Cell(190,4,"Cordial Saludo.",0,1,"L",0,0);
                $formu->Ln();
                $formu->Ln();
                $formu->MultiCell(175,4,html_entity_decode("{$texto[$i]}"),0,"J",0);
                $formu->Ln();
                $formu->Ln();
                $formu->Ln();
                $formu->Ln();
                $formu->Ln();
                $formu->Cell(190,4,"Atentamente: ",0,1,"L",0,0);
                $formu->Ln();
                $formu->Ln();
                $y = $formu->getY(); 
                $formu->Ln();
                $formu->Ln();
                $formu->Ln();
                $formu->Image('public/img/firmas/firmaYazminOspina.png',15,$y,60,18);
                $formu->SetFont('Calibri','BI','12');
                $formu->Cell(190,4,"YAZMIN OSPINA GAITAN ",0,1,"L",0,0);
                $formu->SetFont('Calibri','I','12');
                $formu->Cell(190,4,"COORDINADORA AFILIACIONES Y SUBSIDIO",0,1,"L",0,0);
                $formu->Ln();
                $formu->Ln();
                $formu->SetFont('Calibri','I','8');
                //$formu->Cell(190,4,html_entity_decode("Y. OSPINA GAITAN / M.Y. MU&Ntilde;OZ LOZANO"),0,1,"L",0,0);
                //$formu->Ln();
                //$formu->Cell(190,4,html_entity_decode("Copia Interna: Centro de Documentaci&oacute;n e Informaci&oacute;n "),0,1,"L",0,0);
                $formu->Image('public/img/portal/piepaginaCartas.png',1,237,214,42);
                $formu->Ln();
            }
        }
        $this->setResponse('view');
        $file = "public/temp/reportes/trabajador_inact.pdf";
        ob_clean();
        $formu->Output( $file,"F");       
        $formu = parent::successFunc("Genera Formulario",$file);
        $this->renderText(json_encode($formu)); 
    }
    // cambio de datos Principales
    public function cambioDatosPrincipales_viewAction(){
        $this->setResponse("ajax");
        $coddoc = parent::webService('tiposdocumentos',array());
        foreach($coddoc as $mcoddoc){
            $_coddoc[$mcoddoc['coddoc']] = $mcoddoc['detalle'];
        }
        echo parent::showTitle('Cambio Datos Principales');
        $nit = Session::getData('documento');
        $codare = $this->getPostParam("codare");
        $codope = $this->getPostParam("codope");
        $result  = parent::webService("DatosReprecentante",array("nit"=>$nit));
        $html  = "";
        $html .= "<tr>";
        $html .="<td colspan=6 align='center'><strong>Datos Empresa</strong></td>";
        $html .= "</tr>";
        $html .= "<tr'>";
        $html .= "<td> Nit </td>";
        $html .= "<td>".Tag::numericField("nit","value: ".Session::getData('documento'),"readonly: true","class: form-control" )."</td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td> Razón Social </td>";
        $html .= "<td>".Tag::textUpperField("razsoc","value: ".$result[0]['razsoc'],"onkeydown: validOnlyLetter();","class: form-control","style: margin-top: 3%" )."</td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td>&nbsp</td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .="<td colspan=6 align='center'><strong>Datos Representante</strong></td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td> Tipo Documento </td>";
        $html .= "<td>".Tag::selectStatic("tipdoc",$_coddoc,"value: ".$result[0]['tipdoc'],"maxlength: 10","class: form-control","style: margin-top: 3%","use_dummy: true","onchange: doc()")."</td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td> Documento </td>";
        $html .= "<td>".Tag::numericField("cedrep","value: ".$result[0]['cedrep'],"maxlength: 10","class: form-control","style: margin-top: 3%",'onchange: dattra();')."</td>";
        $html .= "</tr>";
        $html .= "<tr id='tr_nomrep'>";
        $html .= "<td> Representante</td>";
        $html .= "<td>".Tag::textUpperField("nomrep","value: ".$result[0]['nomrep'],"maxlength: 50","onkeydown: validOnlyLetter();","class: form-control","style: margin-top: 3%")."</td>";
        $html .= "</tr>";
        $html .= "<tr class='ocl_tr'>";
        $html .= "<td> Primer Apellido</td>";
        $html .= "<td>".Tag::textUpperField("priape","value: ".$result[0]['priape'],"maxlength: 50","onkeydown: validOnlyLetter(event);","class: form-control","style: margin-top: 3%","onchange: valrep()")."</td>";
        $html .= "</tr>";
        $html .= "<tr class='ocl_tr'>";
        $html .= "<td> Segundo Apellido</td>";
        $html .= "<td>".Tag::textUpperField("segape","value: ".$result[0]['segape'],"maxlength: 50","onkeydown: validOnlyLetter(event);","class: form-control","style: margin-top: 3%","onchange: valrep()")."</td>";
        $html .= "</tr>";
        $html .= "<tr class='ocl_tr'>";
        $html .= "<td> Primer Nombre</td>";
        $html .= "<td>".Tag::textUpperField("prinom","value: ".$result[0]['prinom'],"maxlength: 50","onkeydown: validOnlyLetter(event);","class: form-control","style: margin-top: 3%","onchange: valrep()")."</td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<tr class='ocl_tr'>";
        $html .= "<td> Segundo Nombre</td>";
        $html .= "<td>".Tag::textUpperField("segnom","value: ".$result[0]['segnom'],"maxlength: 50","onkeydown: validOnlyLetter(event);","class: form-control","style: margin-top: 3%","onchange: valrep()")."</td>";
        $html .= "</tr>";
        $html .= "<tr class='ocl_tr'>";
        $html .= "<td> Fecha de Nacimiento</td>";
        $fecna = date("Y-m-d",strtotime($result[0]['fecnac']));
        $html .= "<td>".TagUser::Calendar("fecnac","value: ".$fecna,"class: form-control","style: margin-top: 3%")."</td>";
        $html .= "</tr>";
        $html .= "<tr class='ocl_tr'>";
        $html .= "<td> Genero: </td>";
        $html .= "<td>" .Tag::selectStatic('sexo',array('M'=>'Masculino','F'=>'Femenino'),"value: ".$result[0]['sexo'],"class: form-control","style: margin-top: 3%")."</td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td> Tipo de Documentos a adjuntar: </td>";
        $html .= "<td>" .Tag::selectStatic('adjdoc',array('1'=>'CONSORCIO','2'=>'UNION TEMPORAL','3'=>'PROPIEDAD HORIZONTAL','4'=>'COOPERATIVA','5'=>'PERSONA JURIDICA'),"class: form-control","style: margin-top: 3%","useDummy: yes","onchange: adj();")."</td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td colspan='4' align='center'>";
        $html .= Tag::button("Adjuntar Documentos","class: submit ","onclick: adjuntos();","class: btn btn-success","style: margin-top: 3%");
        $html .= "</td>";
        $html .= "</tr>";
        $html .= "<tr class='ocl_tr'>";
        $html .= "<td colspan='6'>";
        $html .= "<table id='adjuntos' style='width: 100%;'>";
        $html .= "</table>";
        $html .= "</td>";
        $html .= "</tr>";
        /*
        $html .= "<tr>";
        $html .= "<td> Documento Cámara de Comercio</td>";
        $html .= "<td colspan='2'>".Tag::fileField("archivo_camara","accept: image/png, application/pdf","style: margin-top: 3%")."</td>";
        $html .= "</tr>";
        */
        $this->setParamToView("html", $html);
        $this->setParamToView("codare", $codare);
        $this->setParamToView("codope", $codope);
        $this->setParamToView("tipapo", $result[0]['tipapo']);



    }

    public function cambioDatosPrincipalesAction(){
        try{
            try{
                $response = parent::startFunc();
                $modelos = array("mercurio43","mercurio21");
                $Transaccion = parent::startTrans($modelos);
                Session::setData('nota_audit',"Actualización de Datos Pincipales");
                $log_id = parent::registroOpcion();
                if($log_id==false)parent::ErrorTrans();
                $result = parent::webService('DatosReprecentante',array("nit"=>SESSION::getDATA('documento')));
                if(!isset($result[0])){
                    $result[0]['cedrep'] = "";
                    $result[0]['razsoc'] = "";
                    $result[0]['nomrep'] = "";
                    $result[0]['sexo'] = "";
                    $result[0]['fecnac'] = "";
                }
                //$campos = array("razsoc","cedrep","nomrep",'tipdoc');
                $campos = array("razsoc","cedrep","nomrep","sexo","fecnac");
                $codare = $this->getPostParam("codare");
                $codope = $this->getPostParam("codope");
                $ruta_file = "";
                $log = $log_id;
                $camposc = "";
                $id = 0;
                foreach($campos as $mcampos){
                    $valor = $this->getPostParam("{$mcampos}","addslaches","extraspaces","striptags");
                    $cedula = $this->getPostParam("cedrep","addslaches","extraspaces","striptags");
                    if($mcampos == 'fecnac'){
                        $result[0][$mcampos] =  date("Y-m-d",strtotime($result[0][$mcampos]));
                    }
                    if($result!=false){
                        if($result[0][$mcampos]==$valor)continue;
                    }
                    if($mcampos == 'nomrep' && $valor ==''){
                        $priape = $this->getPostParam("priape");
                        $segape = $this->getPostParam("segape");
                        $prinom = $this->getPostParam("prinom");
                        $segnom = $this->getPostParam("segnom");
                        $valor = $prinom." ".$segnom." ".$priape." ".$segape;
                    }
                    if(($mcampos == "fecnac" or  $mcampos == "sexo") and $cedula != $result[0]['cedrep'] ){
                        continue;
                    }
                    $mercurio43 = new Mercurio43();
                    $mercurio43->setTransaction($Transaccion);
                    $mercurio43->setId(0);
                    $mercurio43->setCodcaj(Session::getDATA('codcaj'));
                    $mercurio43->setDocumento(Session::getDATA('documento'));
                    $mercurio43->setCampo($mcampos);
                    $mercurio43->setValor($valor);
                    $mercurio43->setEstado("P");
                    $mercurio43->setLog($log_id);
                    $u = parent::asignarFuncionario($codare,$codope);
                    if($u==false){
                        return $this->redirect("login/index/La sesion a expirado");
                    }
                    $mercurio43->setUsuario($u);
                    $mercurio43->setNomarc('Vacio');
                    $conrep = 0;
                    if($mcampos == 'razsoc'){
                        $camp = "Razon Social";
                    }else if($mcampos == 'cedrep'){
                        $camp = "Cedula de Representante";
                    }else if($mcampos == 'nomrep'){
                        if($conrep == 0){
                            $camp = "Nombre del Representante";
                            $conrep++;
                        }
                    }else if($mcampos == 'sexo'){
                        $camp = "Sexo";
                    }else if($mcampos == 'fecnac'){
                        $camp = "Fecha de nacimiento";
                    }
                    $camposc .= "<b>$camp :</b> $valor <br> ";
                    /*
                       if(isset($_FILES['archivo_camara'])){
                       $_FILES["archivo_camara"]['name'] = $mercurio43->getLog()."_actprin_".substr($_FILES["archivo_camara"]['name'],-4);
                       $path = "public/files/";
                       $this->uploadFile("archivo_camara",getcwd()."/$path/");
                       $dd=pathinfo($_FILES["archivo_camara"]["name"]);
                       $archivo_nombre = $path.$dd['basename'];
                       $ruta_file[] = $archivo_nombre;
                       $mercurio43->setNomarc($archivo_nombre);
                       }
                     */
                    if(!$mercurio43->save()){
                        parent::setLogger($mercurio43->getMessages());
                        parent::ErrorTrans();
                    }
                    $mercurio13 = $this->Mercurio13->find("codcaj = '".Session::getDATA('codcaj')."' AND codare = '$codare' AND codope = '$codope'");
                    if($id == 0 || $id == "")$id = $mercurio43->getId();
                    foreach($mercurio13 as $mmercurio13){
                        if(isset($_FILES['archivo_'.$mmercurio13->getCoddoc()])){
                            $_FILES["archivo_{$mmercurio13->getCoddoc()}"]['name'] = $id."_".$mmercurio13->getCoddoc()."_".substr($_FILES["archivo_{$mmercurio13->getCoddoc()}"]['name'],-4);
                            if($_FILES["archivo_{$mmercurio13->getCoddoc()}"]['size'] == 0){
                                continue;
                            }
                            $path = "public/files/datpri/";
                            $this->uploadFile("archivo_{$mmercurio13->getCoddoc()}",getcwd()."/$path/");
                            $dd = pathinfo($_FILES["archivo_{$mmercurio13->getCoddoc()}"]["name"]);
                            $archivo_nombre = $path.$dd['basename'];
                            $ruta_file[] = $archivo_nombre;
                            $mercurio47 = new Mercurio47();
                            $mercurio47->setTransaction($Transaccion);
                            $mercurio47->setNumero($mercurio43->getId());
                            $mercurio47->setCoddoc($mmercurio13->getCoddoc());
                            $mercurio47->setNomarc($archivo_nombre);
                            if(!$mercurio47->save()){
                                parent::setLogger($mercurio47->getMessages());
                                parent::ErrorTrans();
                            }
                        }
                    }
                    if($mcampos == 'cedrep' || $mcampos == 'nomrep' || $mcampos == 'prinom' || $mcampos == 'segnom' || $mcampos == 'priape' || $mcampos == 'segape' || $mcampos == 'fecnac' || $mcampos == 'sexo'){
                        $mercurio46 = new Mercurio46();
                        $mercurio46->setTransaction($Transaccion);
                        $mercurio46->setLog($log_id);
                        $mercurio46->setCoddoc($this->getPostParam('tipdoc'));
                        $mercurio46->setDocumento($this->getPostParam('cedrep'));
                        $mercurio46->setPriape($this->getPostParam('priape'));
                        $mercurio46->setSegape($this->getPostParam('segape'));
                        $mercurio46->setPrinom($this->getPostParam('prinom'));
                        $mercurio46->setSegnom($this->getPostParam('segnom'));
                        $mercurio46->setSexo($this->getPostParam('sexo'));
                        $mercurio46->setFecnac($this->getPostParam('fecnac'));
                        $mercurio46->setTipper('idrepresentante');
                        if(!$mercurio46->save()){
                            parent::setLogger($mercurio46->getMessages());
                            parent::ErrorTrans();
                        }
                    }
                }
                $asunto = "Nueva Actualización de DatosPrincipales  ".Session::getDATA("documento")." - ".Session::getDATA('nombre');
                $mercurio07 = $this->Mercurio07->findFirst("documento = ".Session::getData('documento')." AND tipo='E'");
                $msg = "Gracias por utilizar el Servicio en Linea de Comfamiliar Huila, usted acaba de realizar el proceso de Actualizacion de datos principales:
                    <br><br><b>RAZON SOCIAL: ".Session::getData('nombre')."</b>
                    <br><b>NIT: </b>".Session::getData('documento')."<br>
                    $camposc
                    <br><br>
                    Esta informacion sera verificada por uno de nuestros funcionarios, a su correo de contacto '{$mercurio07->getEmail()}' le comunicaremos si su solicitud ha sido aprobada o rechazada.
                    ";
                $ruta_file = "";
                parent::enviarCorreo("Actualizacion datos Principales",  Session::getData("documento"), "{$mercurio07->getEmail()}", $asunto, $msg, $ruta_file);
                parent::finishTrans();
                return $this->redirect("principal/index/Actualizacion Exitosa, Pendiente por Aprobar");
            } catch (DbException $e) {
                parent::setLogger($e->getMessage());
                parent::ErrorTrans();
            }
        } catch (TransactionFailed $e) {
            return $this->redirect("principal/index/Error al Actualizar los Datos Principales");
        }
    }

//Certificados Empresa 
    public function certificadoAfiliacioniEmpresa_viewAction(){
      $this->setResponse("ajax");
      echo parent::showTitle('Certificado de Afiliacion Empresa');
      $param  = array("cedtra"=>Session::getData('documento'));
      $benefic = parent::webService("Nucfambeneficiarios", $param);      
      $this->setParamToView("benefic",$benefic);
    }
    public function generarCertificadosEmpresaAction(){
        $this->setResponse("ajax");
        $estado = "INACTIVO";
        if(Session::getData('estado') == "I" && Session::getData('codest') == "4118"){ 
        //if(Session::getData('estado') != "I") {
            $response = parent::errorFunc("Las empresas expulsada no tiene permiso de descargar algun certificado");
            return $this->renderText(json_encode($response));
        }
        if(Session::getData('estado') != "I") $estado="ACTIVO";
        $param  = array("nit"=>Session::getData('documento'));
        //$result = parent::webService("AporteMaxfec", $param);      
        $result = parent::webService("TrayectoriaEmpresa", $param);      
        //Debug::addVariable("a",$result);
        //throw new DebugException(0);
        //$texto = "no tiene ultima fecha de pago";
        if($result!=""){
            $fecpag = date("Y-m-d",strtotime($result[0]['fecafi']));
            if(Session::getData('estado') == "I"){
                if($result[0]['fecret'] == ''){
                    $fecpag = '';
                }else{
                    $fecpag = date("Y-m-d",strtotime($result[0]['fecret']));
                }
            }
            if($fecpag == '')
                $fecafi = '';
            else
                $fecafi = new Date($fecpag);
            // $texto = "La ultima fecha de pago de aportes es ".$fecpag->getDay()." de ".$fecpag->getMonthName()." de ".$fecpag->getYear();
        }
        if($fecafi == ''){
            $texto = "La empresa ".Session::getData('nombre').", identificada con Nit No. ".number_format(Session::getData('documento'),0,'.','.').", se encuentra afiliada a nuestra Entidad como Aportante de Parafiscales con estado $estado desde el mes de de  .";
        }else{
            $texto = "La empresa ".Session::getData('nombre').", identificada con Nit No. ".number_format(Session::getData('documento'),0,'.','.').", se encuentra afiliada a nuestra Entidad como Aportante de Parafiscales con estado $estado desde el mes de ".$fecafi->getMonthName()."   ".$fecafi->getDay()."  de  ".$fecafi->getYear().".";
        }
        $formu = self::certificadoEmp($texto);
        $this->renderText(json_encode($formu)); 
    }

    public function certificadoEmp($texto){
        $fecha = new Date();
        $ano = $fecha->getYear();
        $mes = $fecha->getMonthName();
        $dia = $fecha->getDay();
        $formu = new FPDF('P','mm','Letter');
        $formu->SetMargins(20,25);
        $formu->AddPage();
        $formu->SetFillColor(236,248,240); 
        $formu->AddFont('Calibri','','Calibri.php');
        $formu->AddFont('Calibri','BI','Calibri Bold Italic.php');
        $formu->AddFont('Calibri','I','Calibri Italic.php');
        $formu->SetFont('Calibri','I','12');
        $formu->Ln();
        $formu->Cell(175,4,"",0,1,"L",0,0);
        $formu->Image('public/img/comfamiliar-logo.jpg',10,15,63,18);
        $idmer20 = $this->Mercurio20->findBySql("SELECT MAX(id) as id FROM mercurio20");
        $mercurio20 = $this->Mercurio20->findBySql("SELECT * FROM mercurio20 WHERE id='{$idmer20->getId()}'");
        $fecha20 = $mercurio20->getFecha();
        $hora20 = $mercurio20->getHora();
        $codbar = $idmer20->getId()."-".Session::getDATA('documento');
        $formu->Codabar(115,20,$codbar);

        //$formu->Codabar(120,20,'123456789');
        $formu->Cell(190,18,"",0,1,"C",0,0);
        $formu->SetFont('Calibri','BI','12');
        $formu->Cell(190,4,"LA COORDINADORA DE AFILIACIONES Y SUBSIDIO",0,1,"C",0,0);
        $formu->Ln();
        $formu->SetFont('Calibri','BI','12');
        $formu->Cell(190,4,"CERTIFICA QUE",0,1,"C",0,0);
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        //$estado = $estados[Session::getData('estado')];
        $formu->SetFont('Calibri','I','11');
        $formu->MultiCell(175,4,html_entity_decode("$texto"),0,"J",0);
        $formu->Ln();
        $formu->SetFont('Calibri','I','8');
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->MultiCell(175,4,html_entity_decode("'ESTE CERTIFICADO NO ES VALIDO PARA AFILICIACION A OTRAS CAJAS DE COMPENSACION NI COMO SOPORTE DE PAGO DE PARAFISCALES POR CONTRATOS'"),0,"J",0,0);
        $formu->Ln();
        $formu->Ln();
        $formu->SetFont('Calibri','I','10');
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Cell(190,4,"La presente se expide a los {$fecha->getDay()} dias de {$fecha->getMonthName()} de {$fecha->getYear()} ",0,1,"L",0,0);
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $y = $formu->getY(); 
        $formu->SetFont('Calibri','BI','10');
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Image('public/img/firmas/firmaYazminOspina.png',100,$y,35,15);
        $formu->Cell(190,4,"YAZMIN OSPINA GAITAN",0,1,"C",0,0);
        $formu->Cell(190,4,html_entity_decode("COORDINADORA AFILIACIONES Y SUBSIDIO"),0,1,"C",0,0);
        $formu->Ln();
        $formu->Ln();
        $formu->Image('public/img/portal/piepaginaCartas.png',1,237,214,42);
        $formu->Ln();
        $this->setResponse('view');
        $file = "public/temp/reportes/Empresa_".Session::getData('documento').".pdf";
        ob_clean();
        $formu->Output( $file,"F");       
        $formu = parent::successFunc("Genera Formulario",$file);
        return $formu;
    }



    public function cambioReprecentanteAction(){
        try{
            try{
                $this->setResponse("ajax");
                $cedres = $this->getPostParam("cedres");
                $nomrep = $this->getPostParam("nomrep");
                $path   = "public/files/";

                $archivo = $_FILES["archivo_camara"];        
                Debug::addVariable("a",$archivo);
                //throw new DebugException(0);    
                $this->uploadFile("archivo_camara_".Session::getData('documento'),"/$path/");
                Debug::addVariable("a",$cedrep);
                Debug::addVariable("b",$nomrep);
                //throw new DebugException(0);    
            }catch (DbException $e) {
                parent::setLogger($e->getMessage());
                parent::ErrorTrans();
            }
        }catch (TransactionFailed $e) {
            $response = parent::errorFunc("Mal");
            return $this->renderText(json_encode($response));
        }
    }


    public function retirarAction(){
        try{
            try{
                //$this->setResponse("ajax");
                $modelos = array("mercurio35");
                $Transaccion = parent::startTrans($modelos);
                $response = parent::startFunc();
                $nit = Session::getData('documento');
                $cedtra = $this->getPostParam("cedtra","addslaches","alpha","extraspaces","striptags");
                $coddoc = $this->getPostParam("coddoc","addslaches","alpha","extraspaces","striptags");
                $codest = $this->getPostParam("codest","addslaches","alpha","extraspaces","striptags");
                $fecret = $this->getPostParam("fecret","addslaches","extraspaces","striptags");
                $nota = $this->getPostParam("nota","addslaches","alpha","extraspaces","striptags");
                $codare = $this->getPostParam("codare");
                $codope = $this->getPostParam("codope");
                $nomtra = $this->getPostParam("nomtra");
                $adjnov = $this->getPostParam("adjnov");

                $mercurio07 = $this->Mercurio07->findBysql("SELECT * FROM mercurio07 where tipo='E' and documento ='{$nit}' limit 1");

                $mresult = parent::webService("DatosTrabajador",array("nit"=>$nit,"cedtra"=> $cedtra,"coddoc"=>$coddoc));
                if($mresult==false){
                    Message::success("El trabajador no esta afiliado a la empresa. verifique la cedula");
                    return $this->routeTo("controller: principal", "action: index");  
                    /*
                    $response = parent::errorFunc("El trabajador no esta afiliado a la empresa. verifique la cedula");
                    return $this->renderText(json_encode($response));
                    */
                }
                $mer35 = $this->Mercurio35->count("*","conditions: coddoc='$coddoc' and cedtra='$cedtra' and estado= 'P'");
                if($mer35 > 0){
                    Message::success("El trabajador esta pendiente por aprobar la novedad de retiro");
                    return $this->routeTo("controller: principal", "action: index");  
                    /*
                    $response = parent::errorFunc("El trabajador esta pendiente por aprobar la novedad de retiro");
                    return $this->renderText(json_encode($response));
                    */
                }
                $fecafi = date("Y-m-d",strtotime($mresult[0]['fecafi']));
                if(Date::compareDates($fecafi,$fecret)>0){ 
                    Message::success("La Fecha de Retiro no puede ser menor a la fecha de afiliacion");
                    return $this->routeTo("controller: principal", "action: index");  
                    /*
                    $response = parent::errorFunc("La Fecha de Retiro no puede ser menor a la fecha de afiliacion");
                    return $this->renderText(json_encode($response));
                    */
                }
                Session::setData('nota_audit',"Novedad de Retiro");
                $log_id = parent::registroOpcion();
                if($log_id==false)parent::ErrorTrans();
                $mercurio35 = new Mercurio35();
                $mercurio35->setTransaction($Transaccion);
                $mercurio35->setNit($nit);
                $mercurio35->setLog($log_id);
                $mercurio35->setCedtra($cedtra);
                $mercurio35->setCoddoc($coddoc);
                $mercurio35->setNomtra($nomtra);
                $mercurio35->setCodest($codest);
                $mercurio35->setFecret($fecret);
                $mercurio35->setNota($nota);
                $mercurio35->setEstado("P");
                $mercurio35->setFecest(NULL);
                $mercurio35->setMotivo(NULL);
                $u = parent::asignarFuncionario($codare,$codope);
                if($u==false){
                    return $this->redirect("login/index/No existe un usuario a quien asignar");
                }
                $mercurio35->setUsuario($u);
                if(isset($_FILES['adjnov'])){
                    $_FILES["adjnov"]['name'] = $mercurio35->getLog()."_actprin_".substr($_FILES["adjnov"]['name'],-4);
                    if($_FILES["adjnov"]['size'] == 0){
                        $mercurio35->setNomarc('');
                    }else{
                        $path = "public/files/";
                        $this->uploadFile("adjnov",getcwd()."/$path/");
                        $dd=pathinfo($_FILES["adjnov"]["name"]);
                        $archivo_nombre = $path.$dd['basename'];
                        $ruta_file[] = $archivo_nombre;
                        $mercurio35->setNomarc($archivo_nombre);
                    }
                }
                if(!$mercurio35->save()){
                    parent::setLogger($mercurio35->getMessages());
                    parent::ErrorTrans();
                }

                $email  = $mercurio07->getEmail();
                $asunto = "Retiro de un trabajador";
                $msg = "Gracias por utilizar el Servicio en Linea de Comfamiliar Huila, usted acaba de realizar el proceso de Retiro del (los) siguiente(s) Trabajador(es):
                    <br><br><b>RAZON SOCIAL: ".Session::getData('nombre')."</b>
                    <br><b>NIT: </b>".Session::getData('documento')."<br>
                    <br><b>IDENTIFICACION: </b> $cedtra<br>
                    <br><b>NOMBRE: </b> $nomtra<br>
                    <br><br>
                    Esta informacion sera verificada por uno de nuestros funcionarios, a su correo de contacto '{$mercurio07->getEmail()}' le comunicaremos si su solicitud ha sido aprobada o rechazada.
                    ";
                parent::enviarCorreo(Session::getDATA('nombre'),Session::getDATA('nombre'),$email,$asunto,$msg,"");
                parent::finishTrans();
                //return $this->redirect("principal/index/La transaccion fue existosa");
                //Message::success("La transaccion fue exitosa");
                return $this->redirect("principal/index/Solicitud enviada con  exito, Pendiente por Aprobar");
                /*
                $response = parent::successFunc("BIEN",null);
                return $this->renderText(json_encode($response));
                */
            } catch (DbException $e) {
                parent::setLogger($e->getMessage());
                parent::ErrorTrans();
            }
        } catch (TransactionFailed $e) {
            /*
            $response = parent::errorFunc("Mal");
            return $this->renderText(json_encode($response));
            */
            return $this->redirect("principal/index/Mal");
        }
    }

    public function novmue_viewAction(){
      $this->setResponse("ajax");
      echo parent::showTitle('Novedades de Retiro por Muerte');
      echo parent::showHelp('<p>Mediante esta opción se puede generar las novedades de retiro por muete. </p>');
      $codest = parent::webService("CodigoEstado",array());
         foreach($codest as $mcodest){
               $_codest[$mcodest['codest']] = $mcodest['detalle'];
      }
      $this->setParamToView("codest", $_codest);
    }

// cargue de certificados
    public function cargueCertificados_viewAction(){
        $fecha = new Date;
        $today = new Date();
        $this->setResponse("ajax");
        echo parent::showTitle('Certificados Adjuntos');
        $codare = $this->getPostParam("codare");
        $codope = $this->getPostParam("codope");
        $param = array("cedtra"=>Session::getData('documento'));
        $response="<br>";
        $response .="<table class='table table-bordered'>";
        $response .="<thead>";
        $response .="<tr class='info' cellspacing='10'>";
        $response .="<td>Beneficiario</td>";
        $response .="<td>Certificado</td>";
        $response .="<td>Periodo</td>";
        $response .="<td>Archivo</td>";
        $response .="</tr>";
        $response .="</thead>";
        $response .="<tbody>";
        $datben = array();
        /*
        $result = parent::webService("Nucfambeneficiarios", $param);      
        if($result!=false){
            foreach ($result as $mresult){
                $benef = $mresult['beneficiario'];
                $datben = parent::webService("Datosfamiliar",array("documento"=>$benef,"cedtra"=>Session::getDATA('documento')));      
                $fecna = date("Y-m-d",strtotime($datben[0]['fecnac']));
                $fecnac = new Date($fecna);
                $difer = $fecha->diffDate($fecnac);
                $difano = $difer/365;
                if(($difano > 12 && $difano < 18) || ($difano>59)){

                    if($difano>59){
                        $_l = $this->Mercurio45->count("*","conditions: documento = '".Session::getDATA('documento')."' and codben='{$datben[0]['documento']}' and codcer='57' and estado in ('P') ");
                        $_l +=$this->Mercurio45->count("*","conditions: documento = '".Session::getDATA('documento')."' and codben='{$datben[0]['documento']}' and codcer='57' and estado in ('A') and YEAR(fecpre)='{$today->getYear()}'");
                    }else{
                        $_l = $this->Mercurio45->count("*","conditions: documento = '".Session::getDATA('documento')."' and codben='{$datben[0]['documento']}' and codcer in ('55','56') and estado in ('P') ");
                        $_l +=$this->Mercurio45->count("*","conditions: documento = '".Session::getDATA('documento')."' and codben='{$datben[0]['documento']}' and codcer in ('55') and estado in ('A') and YEAR(fecpre)='{$today->getYear()}' ");
                        if($today->getMonth()<=6){
                            $_l +=$this->Mercurio45->count("*","conditions: documento = '".Session::getDATA('documento')."' and codben='{$datben[0]['documento']}' and codcer in ('55') and estado in ('A') and YEAR(fecpre)='{$today->getYear()}' and  MONTH(fecpre)<=6");
                        }else{
                            $_l +=$this->Mercurio45->count("*","conditions: documento = '".Session::getDATA('documento')."' and codben='{$datben[0]['documento']}' and codcer in ('55') and estado in ('A') and YEAR(fecpre)='{$today->getYear()}' and MONTH(fecpre)>6");
                        }
                    }
                    $response .="<tr>";
                    $response .="<td>{$datben[0]['nombre']}</td>";
                    if($_l>0){
                    $response .="<td colspan='3'>YA PRESENTO CERTIFICADO</td>";
                    }else{
                        if($difano>59)
                            $response .="<td>".Tag::select("codcer_{$datben[0]['documento']}",$this->Migra091->find("iddefinicion = '11' AND iddetalledef<>'58' AND iddetalledef<>'59' AND iddetalledef<>'60' AND iddetalledef<>'4551' and codigo='3'"),"using: iddetalledef,detalledefinicion","style: width: 174px","useDummy: yes","class: form-control") ."</td>";
                        else
                            $response .="<td>".Tag::select("codcer_{$datben[0]['documento']}",$this->Migra091->find("iddefinicion = '11' AND iddetalledef<>'58' AND iddetalledef<>'59' AND iddetalledef<>'60' AND iddetalledef<>'4551' and codigo in ('1','2')"),"using: iddetalledef,detalledefinicion","style: width: 174px","useDummy: yes","class: form-control") ."</td>";
                        if(($today->getMonth()>=3 && $today->getMonth()<=6) || ($today->getMonth()>=9 && $today->getMonth()<=12)){
                        $response .="<td>".Tag::selectStatic("periodo_{$datben[0]['documento']}",array('0'=>'Periodo Actual'),"style: width: 174px","useDummy: yes","class: form-control") ."</td>";
                        }else{
                            $response .="<td>".Tag::selectStatic("periodo_{$datben[0]['documento']}",array('0'=>'Periodo Actual','1'=>'Periodo Anterior'),"style: width: 174px","useDummy: yes","class: form-control") ."</td>";
                        }
                        $response .="<td>".Tag::fileField("file_certificado_{$datben[0]['documento']}","accept: image/*, application/pdf")."</td>";
                        $response .= "<td>".Tag::hiddenField("certficado_{$datben[0]['documento']}")."</td>";
                    }
                    $response .="</tr>";
                }
            }
            */
        $result = parent::webService("DatosBeneficiarios", $param);      
        if($result!=false){
            foreach ($result as $datben){
                //$benef = $mresult['beneficiario'];
                //$datben = parent::webService("Datosfamiliar",array("documento"=>$benef,"cedtra"=>Session::getDATA('documento')));      
                $captra = $datben['capacidad'];
                //$captra = '';
                $fecna = date("Y-m-d",strtotime($datben['fecnac']));
                $fecnac = new Date($fecna);
                $difer = $fecha->diffDate($fecnac);
                $difano = $difer/365;
                if((($difano > 12 && $difano < 18) || ($difano>59)) || $captra=='I'){

                    //if($difano>59){
                    if($difano>59 or $captra=='I'){
                        $_l = $this->Mercurio45->count("*","conditions: documento = '".Session::getDATA('documento')."' and codben='{$datben['documento']}' and codcer='57' and estado in ('P') ");
                        $_l +=$this->Mercurio45->count("*","conditions: documento = '".Session::getDATA('documento')."' and codben='{$datben['documento']}' and codcer='57' and estado in ('A') and YEAR(fecpre)='{$today->getYear()}'");
                    }else{
                        $_l = $this->Mercurio45->count("*","conditions: documento = '".Session::getDATA('documento')."' and codben='{$datben['documento']}' and codcer in ('55','56') and estado in ('P') ");
                        $_l +=$this->Mercurio45->count("*","conditions: documento = '".Session::getDATA('documento')."' and codben='{$datben['documento']}' and codcer in ('55') and estado in ('A') and YEAR(fecpre)='{$today->getYear()}' ");
                        if($today->getMonth()<=6){
                            $_l +=$this->Mercurio45->count("*","conditions: documento = '".Session::getDATA('documento')."' and codben='{$datben['documento']}' and codcer in ('55') and estado in ('A') and YEAR(fecpre)='{$today->getYear()}' and  MONTH(fecpre)<=6");
                        }else{
                            $_l +=$this->Mercurio45->count("*","conditions: documento = '".Session::getDATA('documento')."' and codben='{$datben['documento']}' and codcer in ('55') and estado in ('A') and YEAR(fecpre)='{$today->getYear()}' and MONTH(fecpre)>6");
                        }
                    }
                    $response .="<tr>";
                    $response .="<td>{$datben['nombre']}</td>";
                    if($_l>0){
                    $response .="<td colspan='3'>YA PRESENTO CERTIFICADO</td>";
                    }else{
                        //if($difano>59)
                        if($difano>59 or $captra=='I')
                            $response .="<td>".Tag::select("codcer_{$datben['documento']}",$this->Migra091->find("iddefinicion = '11' AND iddetalledef<>'58' AND iddetalledef<>'59' AND iddetalledef<>'60' AND iddetalledef<>'4551' and codigo='3'"),"using: iddetalledef,detalledefinicion","style: width: 174px","useDummy: yes","class: form-control") ."</td>";
                        else
                            $response .="<td>".Tag::select("codcer_{$datben['documento']}",$this->Migra091->find("iddefinicion = '11' AND iddetalledef<>'58' AND iddetalledef<>'59' AND iddetalledef<>'60' AND iddetalledef<>'4551' and codigo in ('1','2')"),"using: iddetalledef,detalledefinicion","style: width: 174px","useDummy: yes","class: form-control") ."</td>";
                        if(($today->getMonth()>=3 && $today->getMonth()<=6) || ($today->getMonth()>=9 && $today->getMonth()<=12)){
                        $response .="<td>".Tag::selectStatic("periodo_{$datben['documento']}",array('0'=>'Periodo Actual'),"style: width: 174px","useDummy: yes","class: form-control") ."</td>";
                        }else{
                            $response .="<td>".Tag::selectStatic("periodo_{$datben['documento']}",array('0'=>'Periodo Actual','1'=>'Periodo Anterior'),"style: width: 174px","useDummy: yes","class: form-control") ."</td>";
                        }
                        $response .="<td>".Tag::fileField("file_certificado_{$datben['documento']}","accept: image/*, application/pdf")."</td>";
                        $response .= "<td>".Tag::hiddenField("certficado_{$datben['documento']}")."</td>";
                    }
                    $response .="</tr>";
                }
            }
        }else{
            $response .="<tr>";
            $response .="<td colspan=4>NO TIENE BENEFICIARIOS</td>";
            $response .="</tr>";
        }
        $response .="<tr>";
        $response .="<td colspan=4 align='center'>".Tag::Button("Presentar los certificados",'class: btn btn-primary','style: margin-top: 2%',"onclick: Guardar();")."</td>";
        $response .="</tr>";
        $response .="</tbody>";
        $response .="</table>";
        $this->setParamToView("html", $response);
        $this->setParamToView("codare", $codare);
        $this->setParamToView("codope", $codope);
    }

    /*
    public function cargueCertificados_viewAction(){
        $fecha = new Date;
        $this->setResponse("ajax");
        echo parent::showTitle('Certificados Adjuntos');
        $codare = $this->getPostParam("codare");
        $codope = $this->getPostParam("codope");
        $param = array("cedtra"=>Session::getData('documento'));
        $response="<br>";
        $response .="<table class='table table-bordered'>";
        $response .="<thead>";
        $response .="<tr class='info' cellspacing='10'>";
        $response .="<td>Beneficiario</td>";
        $response .="<td>Certificado</td>";
        $response .="<td>Archivo</td>";
        $response .="</tr>";
        $response .="</thead>";
        $response .="<tbody>";
        $result = parent::webService("Nucfambeneficiarios", $param);      
        if($result!=false){
            foreach ($result as $mresult){
                $benef = $mresult['beneficiario'];
                $datben = parent::webService("Datosfamiliar",array("documento"=>$benef));      
                $fecna = date("Y-m-d",strtotime($datben[0]['fecnac']));
                $fecnac = new Date($fecna);
                $difer = $fecha->diffDate($fecnac);
                $difano = $difer/365;
                if($difano > 12 && $difano < 18){
                    $response .="<tr>";
                    $response .="<td>{$datben[0]['nombre']}</td>";
                    $response .="<td>CERTIFICADO DE ESCOLARIDAD/CERTIFICADO DE UNIVERSIDAD</td>";
                    //$response .="<td>".Tag::fileField("file_certificado_{$datben[0]['documento']}")."</td>";
                    $response .="<td>".Tag::fileField("file_certificado_{$datben[0]['documento']}","accept: image/*, application/pdf")."</td>";
                    $response .= "<td>".Tag::hiddenField("certficado_{$datben[0]['documento']}","value: 01")."</td>";
                    $response .="</tr>";
                }elseif($difano > 59){
                    $response .="<tr>";
                    $response .="<td>{$datben[0]['nombre']}</td>";
                    $response .="<td>CERTIFICADO DE SUPERVIVENCIA</td>";
                    //$response .="<td>".Tag::fileField("file_certificado_{$datben[0]['documento']}")."</td>";
                    $response .="<td>".Tag::fileField("file_certificado_{$datben[0]['documento']}","accept: image/*, application/pdf")."</td>";
                    $response .= "<td>".Tag::hiddenField("certficado_{$datben[0]['documento']}","value: 02")."</td>";
                    $response .="</tr>";
                }else{
                    $response .="<tr>";
                    $response .="<td>{$datben[0]['nombre']}</td>";
                    $response .="<td align='left'>NO DEBE DE PRESENTAR CERTIFICADOS</td>";
                    $response .="</tr>";
                }
            }
        }else{
            $response .="<tr>";
            $response .="<td colspan=4>NO TIENE BENEFICIARIOS</td>";
            $response .="</tr>";
        }
            $response .="<tr>";
            $response .="<td colspan=3 align='center'>".Tag::Button("Presentar los certificados",'class: btn btn-primary','style: margin-top: 2%',"onclick: Guardar();")."</td>";
            $response .="</tr>";
        $response .="</tbody>";
        $response .="</table>";
        $this->setParamToView("html", $response);
        $this->setParamToView("nomben", $datben[0]['nombre']);
        $this->setParamToView("codare", $codare);
        $this->setParamToView("codope", $codope);
    }
*/

    public function cargueCertificadosAction(){
        try{
            try{
                $response = parent::startFunc();
                $modelos = array("mercurio45","mercurio21");
                $Transaccion = parent::startTrans($modelos);
                Session::setData('nota_audit',"Envio Certificado");
                $log_id = parent::registroOpcion();
                if($log_id==false)parent::ErrorTrans();
                $codare = $this->getPostParam("codare");
                $codope = $this->getPostParam("codope");
                $ruta_file = "";
                $param = array("cedtra"=>Session::getData('documento'));
                $result = parent::webService("nucfambeneficiarios", $param);      
                $a=0;
                $nomben = "";
                $docben = "";
                if($result!=false){
                    foreach ($result as $mresult){
                        $benef = $mresult['beneficiario'];
                        $datben = parent::webService("Datosfamiliar",array("documento"=>$benef,"cedtra"=>Session::getDATA('documento')));      
                        if(isset($_FILES["file_certificado_{$datben[0]['documento']}"])){
                            if($_FILES["file_certificado_{$datben[0]['documento']}"]['size'] == '0'){
                                continue;
                            }
                            $nomben .= "<br><br><b>NOMBRE: ".$datben[0]['nombre']."</b>";
                            $docben .= "<br><br><b>DOCUMENTO BENEFICIARIO: ".$datben[0]['documento']."</b>";
                            $mercurio45 = new Mercurio45();
                            $mercurio45->setTransaction($Transaccion);
                            $mercurio45->setId(0);
                            $mercurio45->setCodcaj(Session::getDATA('codcaj'));
                            $mercurio45->setDocumento(Session::getDATA('documento'));
                            $mercurio45->setNomben($datben[0]['nombre']);
                            $mercurio45->setCodben($datben[0]['documento']);
                            $mercurio45->setCodcer($this->getPostParam("codcer_{$datben[0]['documento']}"));
                            $mercurio45->setEstado("P");
                            $mercurio45->setLog($log_id);
                            $mercurio45->setPeriodo($this->getPostParam("periodo_{$datben[0]['documento']}"));
                            $u = parent::asignarFuncionario($codare,$codope);
                            if($u==false){
                                return $this->redirect("login/index/La sesion a expirado");
                            }
                            $mercurio45->setUsuario($u);
                            //$mercurio45->setUsuario(1);
                            if($_FILES["file_certificado_{$datben[0]['documento']}"]['name'] != false){
                                $a=1;
                            }
                            $_FILES["file_certificado_{$datben[0]['documento']}"]['name'] = $mercurio45->getLog()."_precer_".substr($_FILES["file_certificado_{$datben[0]['documento']}"]['name'],-4);
                            $path = "public/files/";
                            $this->uploadFile("file_certificado_{$datben[0]['documento']}",getcwd()."/$path/");
                            $dd=pathinfo($_FILES["file_certificado_{$datben[0]['documento']}"]["name"]);
                            $archivo_nombre = $path.$dd['basename'];
                            $ruta_file[] = $archivo_nombre;
                            $mercurio45->setNomarc($archivo_nombre);
                            if(!$mercurio45->save()){
                                parent::setLogger($mercurio45->getMessages());
                                parent::ErrorTrans();
                            }
                        }
                    }
                }else{
                    return $this->redirect("principal/index/No ingreso ningun Archivo");
                }
                if($a >0){
                    $asunto = "Nuevo Envio de Certificados  ".Session::getDATA("documento")." - ".Session::getDATA('nombre');
                    $mercurio07 = $this->Mercurio07->findFirst("documento ='".Session::getData('documento')."' AND tipo='T'");
                    $msg = "Gracias por utilizar el Servicio en Linea de Comfamiliar Huila, usted acaba de realizar el proceso de Anexo de Certificados: 
                        <br><br><b>NOMBRE: ".Session::getData('nombre')."</b>
                        <br><b>No. DOCUMENTO: </b>".Session::getData('documento')."<br>
                        $nomben
                        $docben
                        <br><br>
                        Esta informacion sera verificada por uno de nuestros funcionarios, a su correo de contacto '{$mercurio07->getEmail()}' le comunicaremos si su solicitud ha sido aprobada o rechazada.
                        ";
                    parent::enviarCorreo("Ingreso Certificado",  Session::getData("documento"), $mercurio07->getEmail(), $asunto, $msg, $ruta_file);
                    parent::finishTrans();
                    return $this->redirect("principal/index/Ingreso de Certificado Exito, Pendiente por Aprobar");
                }else{
                    return $this->redirect("principal/index/No ingreso ningun Archivo");
                }
            } catch (DbException $e) {
                parent::setLogger($e->getMessage());
                parent::ErrorTrans();
            }
        } catch (TransactionFailed $e) {
            return $this->redirect("principal/index/Error al Ingresar Certificado");
        }
    }




 
    public function novmueAction(){
               
      $this->setResponse("ajax");
          $response = "";
          $response .="<table class='resultado-sec' border='1'>";
          $response .="<tr class='tr-result' cellspacing='10'>";
          $response .="<td>Cedula</td>";
          $response .="<td>Nombre</td>";
          $response .="<td>Salario</td>";
          $response .="<td>Sexo</td>";
          $response .="<td>Fecha Nacimiento</td>";
          $response .="<td>Direccion</td>";
          $response .="<td>Telefono</td>";
          $response .="<td>Fecha Afiliacion</td>";
          $response .="</tr>";
          $cedtra = $this->getPostParam("cedtra","addslaches","extraspaces","striptags");
          $result = parent::webService("DatosTrabajador",array("cedtra"=> $cedtra));
          if($result!=false){
            foreach($result as $mresult){
              $response .="<tr>";
              $response .="<td>{$mresult['cedtra']}</td>";
              $response .="<td>{$mresult['nombre']}</td>";
              $response .="<td>{$mresult['salario']}</td>";
              $response .="<td>{$mresult['sexo']}</td>";
              $response .="<td>{$mresult['fecnac']}</td>";
              $response .="<td>{$mresult['direccion']}</td>";
              $response .="<td>{$mresult['telefono']}</td>";
              $response .="<td>{$mresult['fecafi']}</td>";
              $response .="</tr>";
            }
          }
          $response .="</table>";
           return $this->renderText(json_encode($response));  
   
    }

    public function muerteAction(){
        try{
            try{

                $this->setResponse("ajax");
                $modelos = array("mercurio36");
                $Transaccion = parent::startTrans($modelos);
                $response = parent::startFunc();
                $cedtra = $this->getPostParam("cedtra","addslaches","alpha","extraspaces","striptags");
                $codest = $this->getPostParam("codest","addslaches","alpha","extraspaces","striptags");
                $fecret = $this->getPostParam("fecret","addslaches","alpha","extraspaces","striptags");
                $nota = $this->getPostParam("nota","addslaches","alpha","extraspaces","striptags");
                $mercurio36 = new Mercurio36();
                $mercurio36->setTransaction($Transaccion);
                $mercurio36->setCedtra($cedtra);
                $mercurio36->setCodest($codest);
                $mercurio36->setFecret($fecret);
                $mercurio36->setNota($nota);
                $mercurio36->setEstado("P");
                $mercurio36->setFecest(NULL);
                $mercurio36->setMotivo(NULL);
                if(!$mercurio36->save()){
                    parent::setLogger($mercurio36->getMessages());
                    parent::ErrorTrans();
                }
                parent::finishTrans();
                $response = parent::successFunc("BIEN",null);

                return $this->renderText(json_encode($response));
            } catch (DbException $e) {
                parent::setLogger($e->getMessage());
                parent::ErrorTrans();
            }
        } catch (TransactionFailed $e) {
            $response = parent::errorFunc("Mal");
            return $this->renderText(json_encode($response));
        }
    }



    public function convenios_viewAction(){
      $this->setResponse("ajax");
      echo parent::showTitle('Consulta Convenios');
      echo parent::showHelp('<p>Mediante esta herramiente se puede visualizar los convenios a los cuales tiene la empresa, mostrando el tipo <strong>C es convenio e I es interrupcion</strong> ,ademas demuestra el periodo inicial, el preiodo final, la fecha de ingreso y el valor del convenio. </p>  ');  
 
      $param = array("nit"=>Session::getDATA('documento'));
      $response = "";
      $response .="<br>";
      $response .="<div class='resultado-prin'>";
      $response .="<table class='resultado-sec' border=1>";
      $response .="<tr class='tr-result' cellspacing='10'>";
      $response .="<td>Tipo</td>";
      $response .="<td>Periodo Inicial</td>";
      $response .="<td>Periodo Final</td>";
      $response .="<td>Fecha de Ingreso</td>";
      $response .="<td>Valor de Convenio </td>";
      $response .="</tr>";
      $response .="<tr>";
      $result = parent::webService("ConsultaConvenio",$param );
      if($result!=false){
        foreach($result as $mresult){
          $response .="<tr>";
          if($mresult['tipo']=='I'){
              $tipo="INTERRUPCI&Oacute;N";
          }else{
              $tipo="CONVENIO";
          }  
          $response .="<td>{$tipo}</td>";
          $response .="<td>{$mresult['perini']}</td>";
          $response .="<td>{$mresult['perfin']}</td>";
          $response .="<td>{$mresult['fecing']}</td>";
          $response .="<td>{$mresult['valdoc']}</td>";
          $response .="</tr>";
        }
      }
      $response .="</tr>";
      echo $response;
    
    }

    public function precer_viewAction(){
      $this->setResponse("ajax");
      echo parent::showTitle('Presentacion de Certificados');   
    }

    public function actdatobl_viewAction(){
      $this->setTemplateAfter('escritorio');
      echo parent::showTitle('Actualización Datos Básicos');
      $mercurio28  = $this->Mercurio28->find("tipo = '".Session::getDATA('tipo')."'");
      foreach($mercurio28 as $mmercurio28){
        $campos[$mmercurio28->getCampo()] = $mmercurio28->getDetalle();
      }
      $script = "";
      $script .= "<script type='text/javascript'>";
      $script .= "var val = new Validator();";
      $script .= "Event.observe(document, 'dom:loaded', function(){";
      foreach($campos as $key => $value){
        $script .= "val.addField('$key','text',null,{'alias': '$value','isNull': false});";
      }
      $script .= "});";
      
      $script .= "
      Guardar = function(){
        if(!val.valide()){
          Messages.valide(val);
          return;
        }
        ajaxRemoteForm($('form'), 'content',{
          success: function(transport){
            var response = transport.responseText.evalJSON();
            if(response['flag']==true){
              Messages.display(Array(response['msg']),'success');
              window.location = Utils.getKumbiaURL('principal');
            }else{
              Messages.display(Array(response['msg']),'warning');
            }";
            $script .= "}
        });
      }";
    
      $script .= "</script>";
      if(Session::getDATA('tipo')=="T")$titulo = "Datos del Trabajador";
      if(Session::getDATA('tipo')=="E")$titulo = "Datos de la Empresa";
      if(Session::getDATA('tipo')=="C")$titulo = "Datos del Conyuge";
      $result = parent::webService('Infobasica',array("tipo"=>Session::getDATA('tipo'),"cedtra"=>SESSION::getDATA('documento')));
      $html  = "";
      $html .= "<tr>";
      $html .="<td colspan=6>$titulo</td>";
      $html .= "</tr>";
      foreach($campos as $key => $value){
        $html .= "<tr>";
        $html .= "<td><label>{$value}</label></td>";
        $value = "value: ";
        if($result!=false){
            $value = "value: {$result[0][$key]}";
        }
        $html .= "<td>".Tag::textField($key,$value)."</td>";
        $html .= "</tr>";
      }
      $html .= "";
      $this->setParamToView("script", $script);
      $this->setParamToView("html", $html);
      
    }
    
    public function actdatoblAction(){
      try{
        try{
          $this->setResponse("ajax");
          $response = parent::startFunc();
          $modelos = array("mercurio33","mercurio21");
          $Transaccion = parent::startTrans($modelos);
          Session::setData('nota_audit',"Actualizacion de Datos Prueba");
          $log_id = parent::registroOpcion();
          if($log_id==false)parent::ErrorTrans();
          $result = parent::webService('Infobasica',array("tipo"=>Session::getDATA('tipo'),"cedtra"=>SESSION::getDATA('documento')));
          $mercurio28 = $this->Mercurio28->find("tipo = '".Session::getDATA('tipo')."'");
          foreach($mercurio28 as $mmercurio28){
            $valor = $this->getPostParam("{$mmercurio28->getCampo()}","addslaches","extraspaces","striptags");
            if($result!=false){
                if($result[0][$mmercurio28->getCampo()]==$valor)continue;
            }
            $mercurio33 = new Mercurio33();
            $mercurio33->setTransaction($Transaccion);
            $mercurio33->setId(0);
            $mercurio33->setCodcaj(Session::getDATA('codcaj'));
            $mercurio33->setTipo(Session::getDATA('tipo'));
            $mercurio33->setDocumento(Session::getDATA('documento'));
            $mercurio33->setCampo($mmercurio28->getCampo());
            $mercurio33->setValor($valor);
            $mercurio33->setEstado("P");
            $mercurio33->setLog($log_id);

            if(!$mercurio33->save()){
              parent::setLogger($mercurio33->getMessages());
              parent::ErrorTrans();
            }
          }
          $asunto = "Nueva Actualizacion de Datos ".Session::getDATA("documento")." - ".Session::getDATA('nombre');
          $msg = "Cordial Saludos<br><br>Esta pendiente por actualizar los datos<br><br>";
          foreach($mercurio28 as $mmercurio28){
            $msg .="<b>{$mmercurio28->getDetalle()}:</b> ".$this->getPostParam("{$mmercurio28->getCampo()}","addslaches","extraspaces","striptags")."<br>";
          }
          $msg .="<br><br>Atentamente,<br><br>MERCURIO";
          $ruta_file = "";
          parent::enviarCorreo("Actualización datos Trabajador Mercurio",  Session::getData("documento"), "", $asunto, $msg, $ruta_file);
          parent::finishTrans();
          $response = parent::successFunc("Actualización exitosa",null);
          return $this->renderText(json_encode($response));
        } catch (DbException $e) {
          parent::setLogger($e->getMessage());
          parent::ErrorTrans();
        }
      } catch (TransactionFailed $e) {
        $response = parent::errorFunc("Error al Actualizar Datos");
        return $this->renderText(json_encode($response));
      }
    }

    public function traerCiudadAction(){
        $this->setResponse("ajax");
        $coddep = $this->getPostParam("coddep");
        $codciu = parent::webService("CiudadesFiltradas",array("coddep"=>$coddep));
        //Debug::addVariable("b",$codciu);
        //throw new DebugException(0);
        
        if($codciu != false){
            $response = "<option value='@'>Seleccione...</option>";
            foreach($codciu as $ciudad)
                $response .= "<option value='$ciudad[codciu]'>$ciudad[detalle]</option>";
        }else{
            $response = false;
        }
        $this->renderText(json_encode($response));
    }   //////nuevo 
   
    public function actdatAction(){
        try{
            try{
                $this->setResponse("ajax");
                $response = parent::startFunc();
                $modelos = array("mercurio33","mercurio21");
                $Transaccion = parent::startTrans($modelos);
                Session::setData('nota_audit',"Actualización de Datos Prueba");
                $log_id = parent::registroOpcion();
                if($log_id==false)parent::ErrorTrans();
                $result = parent::webService('Infobasica',array("tipo"=>Session::getDATA('tipo'),"documento"=>SESSION::getDATA('documento')));
                //$result[0]['fax'] = "";
                //$mercurio28 = $this->Mercurio28->find("tipo = '".Session::getDATA('tipo')."' AND campo <> 'idciuresidencia' AND campo<>'idbarriocorresp'");
                $mercurio28 = $this->Mercurio28->find("tipo = '".Session::getDATA('tipo')."' AND campo <> 'idciuresidencia' AND campo <>'idciudad'");
                $depart = $this->getPostParam("depart","addslaches","alpha","extraspaces","striptags");
                $codciu = $this->getPostParam("codciu","addslaches","alpha","extraspaces","striptags");
                if(Session::getDATA('tipo') == 'E' ){
                    $deppri = $this->getPostParam("deppri","addslaches","alpha","extraspaces","striptags");
                    $codpri = $this->getPostParam("codpri","addslaches","alpha","extraspaces","striptags");
                }
                foreach($mercurio28 as $mmercurio28){
                    $valor = $this->getPostParam("{$mmercurio28->getCampo()}","addslaches","extraspaces","striptags");
                    if($result!=false){
                        if($result[0][$mmercurio28->getCampo()]==$valor)continue;
                    }
                    if($mmercurio28->getCampo() == 'codciu')$mmercurio28->setCampo('idciuresidencia');
                    if($valor == '@'){
                        continue;
                    }
                    $this->Mercurio33->updateAll("estado='X'","conditions: documento=".Session::getDATA('documento')." and campo='{$mmercurio28->getCampo()}' and estado='P'");

                    $mercurio33 = new Mercurio33();
                    $mercurio33->setTransaction($Transaccion);
                    $mercurio33->setId(0);
                    $mercurio33->setCodcaj(Session::getDATA('codcaj'));
                    $mercurio33->setTipo(Session::getDATA('tipo'));
                    $mercurio33->setDocumento(Session::getDATA('documento'));
                    $mercurio33->setCampo($mmercurio28->getCampo());
                    $mercurio33->setValor($valor);
                    $mercurio33->setEstado("P");
                    $mercurio33->setLog($log_id);
                    $u = parent::asignarFuncionario($codare,$codope);
                    if($u==false){
                        return $this->redirect("login/index/No existe un usuario a quien asignar");
                    }
                    $mercurio33->setUsuario($u);
                    if(!$mercurio33->save()){
                        parent::setLogger($mercurio33->getMessages());
                        parent::ErrorTrans();
                    }
                }
                $documento = Session::getData('documento');
                $mercurio07 = $this->Mercurio07->findBysql("SELECT * FROM mercurio07 where documento='{$documento}' limit 1");
                $email = $mercurio07->getEmail();
                $asunto = "Nueva Actualización de Datos ".Session::getDATA("documento")." - ".Session::getDATA('nombre');
                if(Session::getData('tipo') == 'E'){
                $msg = "Gracias por utilizar el Servicio en L&iacute;nea de Comfamiliar Huila, usted acaba de realizar el proceso de Actualizaci&oacute;n de Datos B&aacute;sicos, asi:<br><br>
                   <b> NIT: </b> ".Session::getData('documento')."
                   <b> Razon Social: </b>".Session::getData('nombre')."<br><br>";
                }else{
                $msg = "Gracias por utilizar el Servicio en L&iacute;nea de Comfamiliar Huila, usted acaba de realizar el proceso de Actualizaci&oacute;n de Datos B&aacute;sicos, asi:<br><br>
                   <b> Identificacion: </b> ".Session::getData('documento')."
                   <b> Nombre: </b>".Session::getData('nombre')."<br><br>";
                }
                foreach($mercurio28 as $mmercurio28){
                    $mvalor = $this->getPostParam("{$mmercurio28->getCampo()}","addslaches","extraspaces","striptags");
                    if($result!=false){
                        if($result[0][$mmercurio28->getCampo()]==$mvalor)continue;
                    }
                    if($mvalor == '@'){
                        continue;
                    }
                    if($mmercurio28->getCampo() == "codciu"){
                        $codciu = $this->getPostParam("{$mmercurio28->getCampo()}","addslaches","extraspaces","striptags");
                        $gener08 = $this->Gener08->findFirst("{$mmercurio28->getCampo()} = $codciu");
                        $mvalor = $gener08->getDetciu();
                        $msg .="<b>{$mmercurio28->getDetalle()}:</b> ".$mvalor."<br>";
                    }else{
                        if($mmercurio28->getCampo() == 'idciuresidencia' || $mmercurio28->getCampo() == 'idciucorresp' || $mmercurio28->getCampo() == ''){
                            $gener08 = $this->Gener08->findFirst("codciu = '$mvalor'");
                            $valor = $gener08->getDetciu(); 
                        }
                        else if($mmercurio28->getCampo() == 'idbarrio' || $mmercurio28->getCampo() == 'idbarriocorresp'){
                            if($mvalor != '@'){
                                $migra087 = $this->Migra087->findFirst("codbar = '$mvalor'");
                                if($migra087 != FALSE){
                                    $valor = $migra087->getDetalle(); 
                                }else{
                                    $valor = "";
                                    continue;
                                }
                            }else{
                                $valor = $mvalor;
                            }
                        }else if($mmercurio28->getCampo() == 'idzona'){
                            if($mvalor != '@'){
                                $migra087 = $this->Migra089->findFirst("codzon = '$mvalor'");
                                $valor = $migra087->getDetzon(); 
                            }else{
                                $valor = $mvalor;
                            }
                        }else{
                            $valor = $mvalor;
                        }
                        $mvalor = $valor;
                        $msg .="<b>{$mmercurio28->getDetalle()}:</b> ".$mvalor."<br>";
                    }
                }
                $msg .="<br><br>Esta informacion sera verificada por uno de nuestros funcionarios, a su correo de contacto '$email' le comunicaremos si su solicitud ha sido aprobada o rechazada.";
                $ruta_file = "";
                parent::enviarCorreo("Actualizacion datos Trabajador Mercurio",  Session::getData("documento"),"$email", $asunto, $msg, $ruta_file);
                parent::finishTrans();
                $response = parent::successFunc("Actualización exitosa, Pendiente por Aprobar",null);
                return $this->renderText(json_encode($response));
            } catch (DbException $e) {
                parent::setLogger($e->getMessage());
                parent::ErrorTrans();
            }
        } catch (TransactionFailed $e) {
            $response = parent::errorFunc("Error al Actualizar Datos");
            return $this->renderText(json_encode($response));
        }
    }

    public function comprobarDocumentoAction(){
        $this->setResponse('ajax');
        $documento = $this->getPostParam("documento");
        $flag = parent::webService("Autenticar",array('tipo' => 'B', 'documento' => $documento, 'coddoc'=>Session::getData('coddoc')));
        $response = true;
        if($flag != false){
            $response = false;
        }
        $this->renderText(json_encode($response));
    }

    public function ingben_viewAction(){
        $this->setResponse("ajax");
        $codare = $this->getPostParam('codare');
        $codope = $this->getPostParam('codope');
        $param = array("cedtra"=>Session::getData('documento'));
        echo parent::showTitle('Ingreso Beneficiarios');
        Tag::displayTo("cedtra", Session::getData("documento"));
        Tag::displayTo("nombre", Session::getData("nombre"));
        $coddoc = $this->Migra091->findAllBySql("SELECT iddetalledef, detalledefinicion FROM migra091 WHERE iddefinicion = '1' ORDER BY detalledefinicion ASC");
        foreach($coddoc as $mcoddoc){
            if($mcoddoc->getIddetalledef() == '1' ||  $mcoddoc->getIddetalledef() == '4' ||  $mcoddoc->getIddetalledef() == '2' ||  $mcoddoc->getIddetalledef() == '3' ||  $mcoddoc->getIddetalledef() == '6')
            $_coddoc[$mcoddoc->getIddetalledef()] = $mcoddoc->getDetalledefinicion();
        }
        $result = parent::webService("DatConyuge",array("cedtra"=>Session::getData("documento"),"coddoc"=>'1'));
        $_conyuge = "";
        if($result != FALSE){
            foreach ($result as $mresult) {
                $_conyuge[$mresult['cedcon']] = $mresult['cedcon']."-".$mresult['prinom']." ".$mresult['segnom']." ".$mresult['priape']." ".$mresult['segape'];
            }
        }
        $nivedu = $this->Migra091->findAllBySql("SELECT iddetalledef, detalledefinicion FROM migra091 WHERE iddefinicion = '26' ORDER BY detalledefinicion ASC");
        if($nivedu != false){
            foreach($nivedu as $mnivedu){
                $_nivedu[$mnivedu->getIddetalledef()] = $mnivedu->getDetalledefinicion();
            }
        }else{
                $_nivedu = array();
        }
        $calendario = array('A' => 'A', 'B' => 'B', 'N' => 'NO APLICA');
        $huerfano = array('0' => 'No aplica','1' => 'Huerfano de Padre', '2' => 'Huerfano de Madre');
        $tiphij = array('0' => 'No aplica','1' => 'Hijo', '2' => 'Hijastro');
        $depart = $this->Migra089->findAllBySql("SELECT DISTINCT coddep, detdep FROM migra089 ORDER BY detdep ASC");
        foreach($depart as $mdepart ){
            $_depart[$mdepart->getCoddep()] = $mdepart->getDetdep();
        }
        $codciu = $this->Migra089->findAllBySql("SELECT DISTINCT codciu, detciu FROM migra089 ORDER BY detciu ASC");
        foreach($codciu as $mcodciu){
            //$_ciunac[$mcodciu['codciu']] = $mcodciu['detalle'];
            $_ciunac[$mcodciu->getCodciu()] = $mcodciu->getDetciu();
        }
        $this->setParamToView("calendario",$calendario);
        $this->setParamToView("codciu",$_ciunac);
        $this->setParamToView("tiphij",$tiphij);
        $this->setParamToView("huerfano",$huerfano);
        $this->setParamToView("nivedu", $_nivedu);
        $this->setParamToView("coddoc", $_coddoc);
        $this->setParamToView("codare", $codare);
        $this->setParamToView("codope", $codope);
        $this->setParamToView("coddep", $_depart);
        $this->setParamToView("conyuge", $_conyuge);
        //$this->setParamToView("tipdis",$_tipdis);
        //$this->setParamToView("captra",$_captra);
    }

    public function ingbenAction(){
        try{
            try{
                $modelos = array("mercurio34","mercurio21","mercurio40");
                $Transaccion = parent::startTrans($modelos);
                $cedtra = $this->getPostParam("cedtra","addslaches","alpha","extraspaces","striptags");
                $nombre = $this->getPostParam("nombre","addslaches","alpha","extraspaces","striptags");
                $cedcon = $this->getPostParam("cedcon","addslaches","alpha","extraspaces","striptags");
                $documento = $this->getPostParam("documento","addslaches","alpha","extraspaces","striptags");
                $coddoc = $this->getPostParam("coddoc","addslaches","alpha","extraspaces","striptags");
                $mercurio34 = $this->Mercurio34->find("estado = 'P' AND cedtra='$cedtra' AND coddoc='$coddoc' AND documento='$documento'");
                if(count($mercurio34) > 0){
                    return $this->redirect("principal/index/Este beneficiario ya tiene una solicitud de afiliacion pendiente");
                }
                $priape = $this->getPostParam("priape","addslaches","alpha","extraspaces","striptags");
                $segape = $this->getPostParam("segape","addslaches","alpha","extraspaces","striptags");
                $prinom = $this->getPostParam("prinom","addslaches","alpha","extraspaces","striptags");
                $segnom = $this->getPostParam("segnom","addslaches","alpha","extraspaces","striptags");
                $sexo = $this->getPostParam("sexo","addslaches","alpha","extraspaces","striptags");
                $fecnac = $this->getPostParam("fecnac","addslaches","extraspaces","striptags");
                $parent = $this->getPostParam("parent","addslaches","alpha","extraspaces","striptags");
                $estudio = $this->getPostParam("estudio","addslaches","alpha","extraspaces","striptags");
                $captra = $this->getPostParam("captra","addslaches","alpha","extraspaces","striptags");
                $huerfano = $this->getPostParam("huerfano","addslaches","alpha","extraspaces","striptags");
                $tiphij = $this->getPostParam("tiphij","addslaches","alpha","extraspaces","striptags");
                $tipdis = $this->getPostParam("tipdis","addslaches","alpha","extraspaces","striptags");
                $nivedu = $this->getPostParam("nivedu","addslaches","alpha","extraspaces","striptags");
                $ciunac = $this->getPostParam("ciunac","addslaches","alpha","extraspaces","striptags");
                $calendario = $this->getPostParam ("calendario","addslaches","alpha","extraspaces","striptags");

                $coddocmb = $this->getPostParam ("coddocmb","addslaches","alpha","extraspaces","striptags");
                $documentop = $this->getPostParam ("documentop","addslaches","alpha","extraspaces","striptags");
                $priapebio = $this->getPostParam ("priapebio","addslaches","alpha","extraspaces","striptags");
                $segapebio = $this->getPostParam ("segapebio","addslaches","alpha","extraspaces","striptags");
                $prinombio = $this->getPostParam ("prinombio","addslaches","alpha","extraspaces","striptags");
                $segnombio = $this->getPostParam ("segnombio","addslaches","alpha","extraspaces","striptags");
                $sexobio = $this->getPostParam ("sexobio","addslaches","alpha","extraspaces","striptags");
                $fecnacbio = $this->getPostParam ("fecnacbio","addslaches","alpha","extraspaces","striptags");

                $coddoccon = $this->getPostParam ("coddoccon","addslaches","alpha","extraspaces","striptags");
                $documencon = $this->getPostParam ("numdoccon","addslaches","alpha","extraspaces","striptags");
                $priapecon = $this->getPostParam ("priapecon","addslaches","alpha","extraspaces","striptags");
                $segapecon = $this->getPostParam ("segapecon","addslaches","alpha","extraspaces","striptags");
                $prinomcon = $this->getPostParam ("prinomcon","addslaches","alpha","extraspaces","striptags");
                $segnomcon = $this->getPostParam ("segnomcon","addslaches","alpha","extraspaces","striptags");
                $sexocon = $this->getPostParam ("sexocon","addslaches","alpha","extraspaces","striptags");
                $fecnaccon = $this->getPostParam ("fecnaccon","addslaches","alpha","extraspaces","striptags");

                $codare = $this->getPostParam("codare");
                $codope = $this->getPostParam("codope");

                Session::setData('nota_audit',"Ingreso de Beneficiario");
                $log_id = parent::registroOpcion();
                if($log_id==false)parent::ErrorTrans();
                $mercurio34 = new Mercurio34();
                $mercurio34->setTransaction($Transaccion);
                $mercurio34->setId(0);
                $mercurio34->setLog($log_id);
                $mercurio34->setCodcaj(Session::getDATA('codcaj'));
                $mercurio34->setCedtra($cedtra);
                $mercurio34->setCedcon($cedcon);
                $mercurio34->setDocumento($documento);
                $mercurio34->setCoddoc($coddoc);
                $mercurio34->setPriape($priape);
                $mercurio34->setSegape($segape);
                $mercurio34->setPrinom($prinom);
                $mercurio34->setSegnom($segnom);
                $mercurio34->setSexo($sexo);
                $mercurio34->setFecnac($fecnac);
                $mercurio34->setParent($parent);
                $mercurio34->setEstudio($estudio);
                $mercurio34->setEstado("P");
                $mercurio34->setCaptra($captra);
                $mercurio34->setHuerfano($huerfano);
                $mercurio34->setTiphij($tiphij);
                $mercurio34->setTipdis($tipdis);
                $mercurio34->setNivedu($nivedu);
                $mercurio34->setCiunac($ciunac);
                $mercurio34->setCalendario($calendario);

                $u = parent::asignarFuncionario($codare,$codope);
                if($u==false){
                    return $this->redirect("login/index/La sesion a expirado");
                }
                $mercurio34->setUsuario($u);
                if(!$mercurio34->save()){
                    parent::setLogger($mercurio34->getMessages());
                    parent::ErrorTrans();
                }
                $mercurio07 = $this->Mercurio07->findFirst("documento = $cedtra");
                $emailTra = $mercurio07->getEmail();
                $nomben = $priape." ".$segape." ".$prinom." ".$segnom;
                $asunto = "Ingreso de Beneficiario del Trabajador $cedtra";
                $msg = "Cordial Saludos<br><br>Esta pendiente por afiliar al Beneficiario $documento - $nomben<br><br>Atentamente,<br><br>COMFAGUAJIRA";
                $ruta_file = "";

          $mercurio13 = $this->Mercurio13->find("codcaj = '".Session::getDATA('codcaj')."' AND codare = '$codare' AND codope = '$codope'");
          foreach($mercurio13 as $mmercurio13){
              if(isset($_FILES['archivo_'.$mmercurio13->getCoddoc()])){
                  $_FILES["archivo_{$mmercurio13->getCoddoc()}"]['name'] = $mercurio34->getId()."_".$mmercurio13->getCoddoc()."_".$documento.substr($_FILES["archivo_{$mmercurio13->getCoddoc()}"]['name'],-4);
                  if($_FILES["archivo_{$mmercurio13->getCoddoc()}"]['size'] == 0){
                      continue;
                  }
                  $path = "public/files/";
                  $this->uploadFile("archivo_{$mmercurio13->getCoddoc()}",getcwd()."/$path/");
                  $dd = pathinfo($_FILES["archivo_{$mmercurio13->getCoddoc()}"]["name"]);
                  $archivo_nombre = $path.$dd['basename'];
                  $ruta_file[] = $archivo_nombre;
                  $mercurio40 = new Mercurio40();
                  $mercurio40->setTransaction($Transaccion);
                  $mercurio40->setNumero($mercurio34->getId());
                  $mercurio40->setCoddoc($mmercurio13->getCoddoc());
                  $mercurio40->setNomarc($archivo_nombre);
                  if(!$mercurio40->save()){
                      parent::setLogger($mercurio40->getMessages());
                      parent::ErrorTrans();
                  }
              }
          }
          if($documentop != ''){
              $mercurio46 = new Mercurio46();
              $mercurio46->setTransaction($Transaccion);
              $mercurio46->setLog($log_id);
              $mercurio46->setCoddoc($coddocmb);
              $mercurio46->setDocumento($documentop);
              $mercurio46->setPriape($priapebio);
              $mercurio46->setSegape($segapebio);
              $mercurio46->setPrinom($prinombio);
              $mercurio46->setSegnom($segnombio);
              $mercurio46->setSexo($sexobio);
              $mercurio46->setFecnac($fecnacbio);
              $mercurio46->setTipper('idbiologico');
              if(!$mercurio46->save()){
                  parent::setLogger($mercurio46->getMessages());
                  parent::ErrorTrans();
              }
          }
          if($documencon != ''){
              $mercurio46 = new Mercurio46();
              $mercurio46->setTransaction($Transaccion);
              $mercurio46->setLog($log_id);
              $mercurio46->setCoddoc($coddoccon);
              $mercurio46->setDocumento($documencon);
              $mercurio46->setPriape($priapecon);
              $mercurio46->setSegape($segapecon);
              $mercurio46->setPrinom($prinomcon);
              $mercurio46->setSegnom($segnomcon);
              $mercurio46->setSexo($sexocon);
              $mercurio46->setFecnac($fecnaccon);
              $mercurio46->setTipper('idconyuge');
              if(!$mercurio46->save()){
                  parent::setLogger($mercurio46->getMessages());
                  parent::ErrorTrans();
              }
          }
          $asuntoben = "Solicitud de Ingreso de Beneficiario $nomben";
          /*
          $msgben ="";
          $msgben .= "<p>Apreciado(a) ".Session::getDATA('nombre')."</p><br/><br/>";
          $msgben .= "<p>La solicitud de afiliacion del beneficiario identificado con documento No.$documento y de nombre $nomben se encuentra en estado pendiente por aprobacion. En breve estaremos informandole acerca del estado de su solicitud.</p><br/><br/><br/>";
          $msgben .= "<p>Atentamente,</p><br/><br/><br/>";
          $msgben .= "<p>Mercurio";
          */
                $msgben = "Gracias por utilizar el Servicio en Linea de Comfamiliar Huila, usted acaba de realizar el proceso de Ingreso de Beneficiarios:
                    <br><br><b>Nombre: ".Session::getData('nombre')."</b>
                    <br><b>Documento: </b>".Session::getData('documento')."<br>
                    Numero de Documento: $documento
                    <br> Nombre: $nomben
                    <br> Numero de Solicitud: {$mercurio34->getId()}
                    <br><br>
                    Esta informacion sera verificada por uno de nuestros funcionarios, a su correo de contacto '{$mercurio07->getEmail()}' le comunicaremos si su solicitud ha sido aprobada o rechazada.
                    ";
          $flag_email = parent::enviarCorreo("Afiliación de Beneficiario",$nombre, $emailTra, $asuntoben, $msgben, "");
          if($flag_email==false){
              return $this->redirect("principal/index/Se envio el formulario pero sin correo electrónico");
              //$response = parent::errorFunc("Se presento un error");
              //return $this->renderText(json_encode($response)); 
          }
          parent::finishTrans();
          return $this->redirect("principal/index/Se Registro Exitosamente");
          //$response = parent::successFunc("Se registro Exitosamente");
          //return $this->renderText(json_encode($response)); 
            } catch (DbException $e) {
                parent::setLogger($e->getMessage());
                parent::ErrorTrans();
            }
        } catch (TransactionFailed $e) {
            return $this->redirect("principal/index/No se pudo registrar");
        }
    }

    public function comprobarCedconAction(){
        $this->setResponse('ajax');
        $cedcon = $this->getPostParam("cedcon");
        $flag = parent::webService("Autenticar",array('tipo' => 'C', 'documento' => $cedcon, 'coddoc'=>Session::getData('coddoc')));
        $response = true;
        if($flag != false){
            $response = false;
        }
        $this->renderText(json_encode($response));
    }
    public function traerBarrioAction(){
        $this->setResponse("ajax");
        $codciu = $this->getPostParam("ciures");
        $barrio = parent::webService("BarrioFiltrados",array("codciu"=>$codciu));
        if($barrio != false){
            $response = "<option value='@'>Seleccione...</option>";
            foreach($barrio as $barrios)
                $response .= "<option value='{$barrios['idbarrio']}'=>{$barrios['detalle']}</option>";
        }else{
            $response = "<option value='@'>Seleccione... </option>";
        }
        $this->renderText(json_encode($response));
    }
    public function traerBarrioZonAction(){
        $this->setResponse("ajax");
        $codzon = $this->getPostParam("codzon");
        if($codzon != ""){
            $barrio = $this->Migra087->find("codzon = $codzon");
            if($barrio != false){
                $response = "<option value='@'>Seleccione...</option>";
                foreach($barrio as $barrios)
                    $response .= "<option value='{$barrios->getCodbar()}'=>{$barrios->getDetalle()}</option>";
            }else{
                $response = "<option value='@'>Seleccione... </option>";
            }
        }else{
            $response = "<option value='@'>Seleccione... </option>";
        }
        $this->renderText(json_encode($response));
    }

    public function ingcon_viewAction(){
        $this->setResponse("ajax");
        $codare = $this->getPostParam('codare');
        $codope = $this->getPostParam('codope');
        echo parent::showTitle('Ingreso Conyuge');
        Tag::displayTo("cedtra", Session::getData("documento"));
        Tag::displayTo("nombre", Session::getData("nombre"));
        $coddoc = $this->Migra091->findAllBySql("SELECT iddetalledef, detalledefinicion FROM migra091 WHERE iddefinicion = '1' ORDER BY detalledefinicion ASC");
        foreach($coddoc as $mcoddoc){
            if($mcoddoc->getIddetalledef() == '1' ||  $mcoddoc->getIddetalledef() == '4' ||  $mcoddoc->getIddetalledef() == '2' ||  $mcoddoc->getIddetalledef() == '3' ||  $mcoddoc->getIddetalledef() == '6')
            $_coddoc[$mcoddoc->getIddetalledef()] = $mcoddoc->getDetalledefinicion();
        }
        $estciv = $this->Migra091->findAllBySql("SELECT iddetalledef, detalledefinicion FROM migra091 WHERE iddefinicion = '10' ORDER BY detalledefinicion ASC");
        foreach($estciv as $mestciv){
            $_estciv[$mestciv->getIddetalledef()] = $mestciv->getDetalledefinicion();
        }
        $nivedu = $this->Migra091->findAllBySql("SELECT iddetalledef, detalledefinicion FROM migra091 WHERE iddefinicion = '26' ORDER BY detalledefinicion ASC");
        foreach($nivedu as $mnivedu){
            $_nivedu[$mnivedu->getIddetalledef()] = $mnivedu->getDetalledefinicion();
        }
        $depart = $this->Migra089->findAllBySql("SELECT DISTINCT coddep, detdep FROM migra089 ORDER BY detdep ASC");
        foreach($depart as $mdepart ){
            $_depart[$mdepart->getCoddep()] = $mdepart->getDetdep();
        }
        $codciu = $this->Migra089->findAllBySql("SELECT DISTINCT codciu, detciu FROM migra089 ORDER BY detciu ASC");
        foreach($codciu as $mcodciu){
            $_codciu[$mcodciu->getCodciu()] = $mcodciu->getDetciu();
        }
        $this->setParamToView("coddoc", $_coddoc);
        $this->setParamToView("codciu", $_codciu);
        $this->setParamToView("estciv", $_estciv);
        $this->setParamToView("nivedu", $_nivedu);
        $this->setParamToView("codare", $codare);
        $this->setParamToView("codope", $codope);
        $this->setParamToView("coddep", $_depart);
    }

    public function ingconAction(){
        try{
            try{
                $this->setResponse("ajax");
                $today = new Date();
                $response = parent::startFunc();
                $modelos = array("mercurio32","mercurio21","mercurio39");
                $Transaccion = parent::startTrans($modelos);
                $codcaj = Session::getData('codcaj');
                $cajcon = $this->getPostParam("cajcon","addslaches","alpha","extraspaces","striptags");
                $cedtra = $this->getPostParam("cedtra","addslaches","alpha","extraspaces","striptags");
                $nombre = $this->getPostParam("nombre","addslaches","alpha","extraspaces","striptags");
                $cedcon = $this->getPostParam("cedcon","addslaches","alpha","extraspaces","striptags");
                $coddoc = $this->getPostParam("coddoc","addslaches","alpha","extraspaces","striptags");
                $coddoc = $this->getPostParam("coddoc","addslaches","alpha","extraspaces","striptags");
                $mercurio32 = $this->Mercurio32->find("estado = 'P' AND cedtra='$cedtra' AND coddoc='$coddoc' AND cedcon='$cedcon'");
                if(count($mercurio32) > 0){
                    return $this->redirect("principal/index/Este conyuge ya tiene una solicitud de afiliacion pendiente");
                }
                $priape = $this->getPostParam("priape","addslaches","alpha","extraspaces","striptags");
                $segape = $this->getPostParam("segape","addslaches","alpha","extraspaces","striptags");
                $prinom = $this->getPostParam("prinom","addslaches","alpha","extraspaces","striptags");
                $segnom = $this->getPostParam("segnom","addslaches","alpha","extraspaces","striptags");
                $fecnac = $this->getPostParam("fecnac","addslaches","extraspaces","striptags");
                $direccion = $this->getPostParam("direccion","addslaches","alpha","extraspaces","striptags");
                $barrio = $this->getPostParam("barrio","addslaches","alpha","extraspaces","striptags");
                $telefono = $this->getPostParam("telefono","addslaches","alpha","extraspaces","striptags");
                $celular = $this->getPostParam("celular","addslaches","alpha","extraspaces","striptags");
                $email = $this->getPostParam("email","addslaches","extraspaces","striptags");
                $salario = $this->getPostParam("salario","addslaches","alpha","extraspaces","striptags");
                $sexo = $this->getPostParam("sexo","addslaches","alpha","extraspaces","striptags");
                $estciv = $this->getPostParam("estciv","addslaches","alpha","extraspaces","striptags");
                $comper = $this->getPostParam("comper","addslaches","alpha","extraspaces","striptags");
                $ciunac = $this->getPostParam("ciunac","addslaches","alpha","extraspaces","striptags");
                $ciures = $this->getPostParam("ciures","addslaches","alpha","extraspaces","striptags");
                $codocu = $this->getPostParam("codocu","addslaches","alpha","extraspaces","striptags");
                $nivedu = $this->getPostParam("nivedu","addslaches","alpha","extraspaces","striptags");
                $captra = $this->getPostParam("captra","addslaches","alpha","extraspaces","striptags");
                $tipviv = $this->getPostParam("tipviv","addslaches","alpha","extraspaces","striptags");
                $codzon = $this->getPostParam("codzon","addslaches","alpha","extraspaces","striptags");
                $codare = $this->getPostParam("codare");
                $codope = $this->getPostParam("codope");
                Session::setData('nota_audit',"Ingreso de Conyuge");
                $log_id = parent::registroOpcion();
                if($log_id==false)parent::ErrorTrans();
                $mercurio32 = new Mercurio32();
                $mercurio32->setTransaction($Transaccion);
                $mercurio32->setId(0);
                $mercurio32->setLog($log_id);
                $mercurio32->setCodcaj($codcaj);
                $mercurio32->setCajcon($cajcon);
                $mercurio32->setCedtra($cedtra);
                $mercurio32->setCedcon($cedcon);
                $mercurio32->setCoddoc($coddoc);
                $mercurio32->setPriape($priape);
                $mercurio32->setSegape($segape);
                $mercurio32->setPrinom($prinom);
                $mercurio32->setSegnom($segnom);
                $mercurio32->setFecnac($fecnac);
                $mercurio32->setCiures($ciures);
                $mercurio32->setCodzon($codzon);
                $mercurio32->setTipviv($tipviv);
                $mercurio32->setDireccion($direccion);
                $mercurio32->setBarrio($barrio);
                $mercurio32->setTelefono($telefono);
                $mercurio32->setCelular($celular);
                $mercurio32->setEmail($email);
                $mercurio32->setSalario(NULL);
                $mercurio32->setSexo($sexo);
                $mercurio32->setEstciv($estciv);
                $mercurio32->setComper($comper);
                $mercurio32->setCiunac($ciunac);
                $mercurio32->setCodocu($codocu);
                $mercurio32->setNivedu($nivedu);
                $mercurio32->setFecing($today->getUsingFormatDefault());
                $mercurio32->setEstado("P");
                $u = parent::asignarFuncionario($codare,$codope);
                if($u==false){
                    return $this->redirect("login/index/La sesion a expirado");
                }
                $mercurio32->setUsuario($u);
                if(!$mercurio32->save()){
                    parent::setLogger($mercurio32->getMessages());
                    parent::ErrorTrans("Error del controller");
                }
                $mercurio07 = $this->Mercurio07->findFirst("documento = $cedtra");
                $emailTra = $mercurio07->getEmail();
                $nomcon = $priape." ".$segape." ".$prinom." ".$segnom;
                $asunto = "Nuevo Ingreso de Conyuge del Trabajador $cedtra - $nombre";
                $msg = "Cordial Saludos<br><br>Esta pendiente por afiliar al Conyuge $cedcon - $nomcon<br><br>Atentamente,<br><br>MERCURIO";
                $ruta_file = "";
                $mercurio13 = $this->Mercurio13->find("codcaj = '".Session::getDATA('codcaj')."' AND codare = '$codare' AND codope = '$codope'");
                foreach($mercurio13 as $mmercurio13){
                    if(isset($_FILES['archivo_'.$mmercurio13->getCoddoc()])){
                        $_FILES["archivo_{$mmercurio13->getCoddoc()}"]['name'] = $mercurio32->getId()."_".$mmercurio13->getCoddoc()."_".$cedcon.substr($_FILES["archivo_{$mmercurio13->getCoddoc()}"]['name'],-4);
                        if($_FILES["archivo_{$mmercurio13->getCoddoc()}"]['size'] == 0){
                            continue;
                        }
                        $path = "public/files/";
                        $this->uploadFile("archivo_{$mmercurio13->getCoddoc()}",getcwd()."/$path/");
                        $dd = pathinfo($_FILES["archivo_{$mmercurio13->getCoddoc()}"]["name"]);
                        $archivo_nombre = $path.$dd['basename'];
                        $ruta_file[] = $archivo_nombre;
                        $mercurio39 = new Mercurio39();
                        $mercurio39->setTransaction($Transaccion);
                        $mercurio39->setNumero($mercurio32->getId());
                        $mercurio39->setCoddoc($mmercurio13->getCoddoc());
                        $mercurio39->setNomarc($archivo_nombre);
                        if(!$mercurio39->save()){
                            parent::setLogger($mercurio39->getMessages());
                            parent::ErrorTrans();
                        }
                    }
                }
                //$flag_email = parent::enviarCorreo("Afiliación de Conyuge",$nomcon,"mercurio@syseu.com",$asunto, $msg, $ruta_file);
                $asuntocon = "Solicitud de Ingreso de Conyuge $nomcon";
                $msgcon = "Gracias por utilizar el Servicio en Linea de Comfamiliar Huila, usted acaba de realizar el proceso de Ingreso de Conyuge:
                    <br><br><b>Nombre Afiliado: ".Session::getData('nombre')."</b>
                    <br><b>Documento Afiliado: </b>".Session::getData('documento')."<br>
                    <b>Documento conyuge:</b> $cedcon
                    <br> <b>Nombre conyuge:</b> $nomcon
                    <br><br>
                    Esta informacion sera verificada por uno de nuestros funcionarios, a su correo de contacto '{$mercurio07->getEmail()}' le comunicaremos si su solicitud ha sido aprobada o rechazada.
                    ";

                $flag_email = parent::enviarCorreo("Afiliación de Conyuge",$nombre,$emailTra, $asuntocon, $msgcon, "");
                if($flag_email==false){
                    return $this->redirect("principal/index/Se envio el Formulario pero sin correo electrónico");
                    //$response = parent::errorFunc("Se presento un error");
                    //return $this->renderText(json_encode($response)); 
                }
                parent::finishTrans();
                return $this->redirect("principal/index/Se registro Exitosamente");
                //$response = parent::successFunc("Se registro Exitosamente");
                //return $this->renderText(json_encode($response)); 
            } catch (DbException $e) {
                parent::setLogger($e->getMessage());
                parent::ErrorTrans();
            }
        } catch (TransactionFailed $e) {
            return $this->redirect("principal/index/No se pudo registrar");
        }
    }



    public function planilla_viewAction(){
      parent::showTitle('Consulta de Planilla Unica');     
      echo parent::showHelp('<p>Mediante esta herramienta se puede visualizar el contenido de la planilla unica mostranndo en su contenido el n&uacute;mero del radicado, el periodo, el valor de la n&oacute;mina, el valor del interes, los trabajadores, la fecha n la que se realizo el pago.</p>');
      $response = "";
      $response .="<br>";
      $response .="<div class='resultado-prin'>";
      $response .="<table class='resultado-sec' border=1>";
      $response .="<tr class='tr-result' cellspacing='10'>";
      $response .="<td>Radicado</td>";
      $response .="<td>Periodo</td>";
      $response .="<td>Valor Nomina</td>";
      $response .="<td>Valor Consignada</td>";
      $response .="<td>Valor Interes</td>";
      $response .="<td>Trabajadores</td>";
      $response .="<td>Fecha Pago</td>";
      $response .="</tr>";
      $param = array("nit"=>Session::getDATA('documento'));
      $result = parent::webService("Planilla", $param);
      if($result!=false){
        foreach($result as $mresult){
          $fecpag = str_replace("12:00:00 AM","",$mresult['fecrec']);
          $response .="<tr>";
          $response .="<td>{$mresult['numrad']}</td>";
          $response .="<td>{$mresult['perapo']}</td>";
          $valnom = number_format(round($mresult['valnom']));
          $response .="<td>{$valnom}</td>";
          $valcon = number_format(round($mresult['valcon']));
          $response .="<td>{$valcon}</td>";
          $valint = number_format(round($mresult['valint']));
          $response .="<td>{$valint}</td>";
          $response .="<td>{$mresult['tottra']}</td>";
          $response .="<td>{$fecpag}</td>";
          $response .="</tr>";
        }        
      }
      echo $response;
    }

    public function pazysalvo_viewAction(){
      echo parent::showTitle('Certificado de Paz y Salvo');
      echo parent::showHelp('Mediante esta herramienta se puede generar el reporte de paz y salvo, según el estado actual de la empresa, con solo dar clic en el boton <strong>Generar Paz y salvo.</strong>');

    }
    
    public function pazysalvoAction(){
      $today = new Date();
      $title = "Certificado Paz y Salvo";
      $report = new UserReportPdf($title,array(),"P","A4");
      $report->startReport();
      $report->setX(15);
      $report->Ln();
      $report->Ln();
      $report->Ln();
      $report->Ln();
      $report->Image('public/img/portal/comfaguajira.jpg',6,27,73,20);
      $report->Image('public/img/LogoSuperSubsi.png',198,245,12,35);
      $report->__MultiCell(0,4,"La Caja de Compensacion Familiar certifica que la empresa ".Session::getDATA("nombre")." Identificado(a) con el Nit No ".Session::getDATA('documento')." se encuentra a paz y salvo",0,"J",0);
      $report->Ln();
      $report->Ln();
      $report->Ln();
      $report->Ln();
      $report->Ln();
      $report->Ln();
      $report->Ln();
      $report->Ln();
      $report->setX(15);
      $report->__Cell(0,4,"Esta certificacion se expide en RIOHACHA a solicitud del interesado el dia {$today->getDay()} de {$today->getMonthName()} de {$today->getYear()}. ",0,0,"L",0,0);
      $report->Ln();
      $report->Ln();
      $report->Ln();
      $report->Ln();
      $report->Ln();
      $report->Ln();
      $report->Ln();
      $report->Ln();
      $report->Ln();
      $report->Ln();
      $report->Ln();
      $report->Ln();
      $report->Ln();
      $report->Ln();
      $report->setX(25);
      $report->__Cell(50,4,"",0,0,"L",0,0);
      $report->__Cell(45,4,"","B",1,"L",0,0);
      $report->setX(25);
      $report->__Cell(50,4,"",0,0,"L",0,0);
      $report->__Cell(45,4,"LICETH RIVEIRA BARROS",0,1,"C",0,0);
      $report->setX(25);
      $report->__Cell(50,4,"",0,0,"L",0,0);
      $mercurio05 = $this->Mercurio05->findFirst("codcaj = '".Session::getDATA('codcaj')."' AND codfir = 'CSA'");
      $report->__Cell(45,4,$mercurio05->getCargo(),0,100,"C",0,0);
      if($mercurio05->getNomimg()!="") $report->Image("public/img/firmas/firmajefa.jpg",70,150,60,13);
      $file = "public/temp/reportes";
      echo $report->FinishReport($file);
      $this->setResponse('view');
    }

    public function adjuntobenAction(){
        $this->setResponse("ajax");
        $fecha = new Date();
        $codare = $this->getPostParam("codare");
        $codope = $this->getPostParam("codope");
        $coddoc = $this->getPostParam("coddoc");
        $fecnac = $this->getPostParam("fecnac");
        $parent = $this->getPostParam("parent");
        $difer = $fecha->diffDate($fecnac);
        $difano = $difer/365;
        $response = "";
        $mercurio13 = $this->Mercurio13->find("codcaj = '".SESSION::getDATA('codcaj')."' AND codare = '$codare' AND codope='$codope'");
        foreach($mercurio13 as $mmercurio13){
            if($parent != '35' && $parent != '37' && $parent != '38'){
                if($mmercurio13->getCoddoc() == 18)continue;
            }
            if($difano > 7 && $difano < 18){
                if($mmercurio13->getCoddoc() == 2)continue;
            }else{
                if($mmercurio13->getCoddoc() == 29)continue;
            }
            if($difano < 12 || $difano > 19 ){
                //if($parent == '36'){
                    if($mmercurio13->getCoddoc() == 13)continue;
                //}
            }
            if($parent != '37' && $parent != '36'){
                if($mmercurio13->getCoddoc() == 14)continue;
            }
            if($parent != '37'){
                if($mmercurio13->getCoddoc() == 19)continue;
            }
            if($parent != '36'){
                if($difano < 60){
                    if($mmercurio13->getCoddoc() == 15)continue;
                }
            }
            if($parent != '38'){
                if($mmercurio13->getCoddoc() == 20)continue;
            }
            $mercurio12 = $this->Mercurio12->findFirst("coddoc = '{$mmercurio13->getCoddoc()}'");
            $response .= "<tr>";
            $response .= " <td colspan='2' style='text-align: right;'><label>Archivo {$mercurio12->getDetalle()} (pdf):</label></td>";
            $response .= "<td colspan='2'>".Tag::fileField("archivo_{$mmercurio13->getCoddoc()}","accept: image/*, application/pdf")."</td>";
            $response .= "</tr>";
        }
        $this->renderText(json_encode($response));
    }    

    public function consultaParticularAction(){
        $this->setResponse("ajax");
        echo parent::showTitle('Consulta de Estados de Afiliación'); 
        $estado = array('A' => 'Aprobada', 'X' => 'Rechazada','P' => 'Pendiente', 'I' => 'Inactiva');
        $mercurio30 = $this->Mercurio30->find('nit = "'.Session::getDATA('documento').'"',"order: id desc");
        $mercurio31 = $this->Mercurio31->find('nit = "'.Session::getDATA('documento').'"',"order: id desc");
        $response = "";
        $response .= "<h3 style='margin-left: 20px'>Afiliaciones Trabajador</h3>";
        $response .= "<div style='margin: 5px auto; width: 100%; overflow: auto; padding: 10px;'>";
        $response .= "<table border='1' cellpadding='2' class='table table-bordered'>";
        $response .= "<thead>";
        $response .= "<tr class='info'>";
        $response .= "<th>No.</th>";
        $response .= "<th>C&eacute;dula</th>";
        $response .= "<th>Nombre</th>";
        $response .= "<th>Sexo</th>";
        $response .= "<th>Estado</th>";
        $response .= "<th>Observaci&oacute;n</th>";
        $response .= "</tr>";
        $response .= "</thead>";
        $response .= "<tbody>";
        $y = 1;
        $codest = parent::webService("CodigoEstado",array());
        foreach ($mercurio31 as $mmercurio31) {
            $response .= "<tr class='tr-res' style='text-align:left;'>";
            $response .= "<td align='right'>".$y++."</td>";
            $response .= "<td align='right'>".$mmercurio31->getCedtra()."</td>";
            $response .= "<td>".$mmercurio31->getPrinom()." ".$mmercurio31->getSegnom()." ".$mmercurio31->getPriape()." ".$mmercurio31->getSegape()."</td>";
            $response .= "<td>".$mmercurio31->getSexo()."</td>";
            $response .= "<td>".$estado[$mmercurio31->getEstado()]."</td>";
            $response .= "<td>".$mmercurio31->getMotivo()."</td>";
            $response .= "</tr>";
        }
        if($y==1){
            $response .= "<tr>";
            $response .= "<td colspan='6'>No tiene registros</td>";
            $response .= "</tr>";
        } 
        $response .= "</tbody>";
        $response .= "</table>";
        $response .= "</div>";
        $response .= "<h3 style='margin-left: 20px'>Actualización de Datos Basicos </h3>";
        $response .= "<div style='margin: 2px auto; width: 95%; min-height: 200px;max-height: 600px ; overflow: auto; padding: 10px;'>";
        $response .= "<table border='1' cellpadding='2' class='table table-bordered'>";
        $response .= "<thead>";
        $response .= "<tr class='info'>";
        $response .= "<th>No.</th>";
        $response .= "<th>Nombre</th>";
        $response .= "<th>Campo</th>";
        $response .= "<th>Campo Actualizado</th>";
        $response .= "<th>Estado</th>";
        $response .= "<th>Observaci&oacute;n</th>";
        $response .= "</tr>";
        $response .= "</thead>";
        $response .= "<tbody>";
        $y = 1;
        $mercurio33 = $this->Mercurio33->find('documento = "'.Session::getDATA('documento').'"',"order: id desc");
        foreach ($mercurio33 as $mmercurio33) {
            $campo='';
            if($mmercurio33->getCampo() == 'direcorresp')$campo="Direccion de Correspodencia";
            if($mmercurio33->getCampo() == 'fax')$campo="Otro numero";
            if($mmercurio33->getCampo() == 'idciucorresp'){
                $campo="Ciudad de Correspodencia";
                $gener08  = $this->Gener08->findFirst("codciu =".$mmercurio33->getValor());
                $mmercurio33->setValor($gener08->getDetciu());
            }
            if($mmercurio33->getCampo() == 'idciuresidencia'){
                $campo="Ciudad";
                $gener08  = $this->Gener08->findFirst("codciu =".$mmercurio33->getValor());
                $mmercurio33->setValor($gener08->getDetciu());
            }
            if($mmercurio33->getCampo() == 'telefono')$campo="Telefono";
            if($mmercurio33->getCampo() == 'celular')$campo="Celular";
            if($mmercurio33->getCampo() == 'idbarrio'){
                $campo="Barrio";
                $migra087  = $this->Migra087->findFirst("codbar='".$mmercurio33->getValor()."'");
                if($migra087==false)$migra087 = new Migra087();
                $mmercurio33->setValor($migra087->getDetalle());
            }
            if($mmercurio33->getCampo() == 'idzona'){
                $campo="Zona";
                $migra089  = $this->Migra089->findFirst("codzon='".$mmercurio33->getValor()."'");
                if($migra089==false)$migra089 = new Migra087();
                $mmercurio33->setValor($migra089->getDetzon());
            }
            if($mmercurio33->getCampo() == 'idbarriocorresp'){
                $campo="Barrio Correspondencia";
                $migra087  = $this->Migra087->findFirst("codbar='".$mmercurio33->getValor()."'");
                if($migra087==false)$migra087 = new Migra087();
                $mmercurio33->setValor($migra087->getDetalle());
            }
            if($mmercurio33->getCampo() == 'codciu'){
                $campo="Ciudad";
                $gener08  = $this->Gener08->findFirst("codciu =".$mmercurio33->getValor());
                $mmercurio33->setValor($gener08->getDetciu());
            }
            if($mmercurio33->getCampo() == 'direccion')$campo="Direccion";
            if($mmercurio33->getCampo() == 'email')$campo="Email";
            if($mmercurio33->getCampo() == 'telefono')$campo="Telefono";
            $response .= "<tr style='text-align:left;'>";
            $response .= "<td>".$y++."</td>";
            $response .= "<td>".Session::getDATA('nombre')."</td>";
            $response .= "<td>".$campo."</td>";
            $response .= "<td>".$mmercurio33->getValor()."</td>";
            $response .= "<td>".$estado[$mmercurio33->getEstado()]."</td>";
            $response .= "<td>".$mmercurio33->getMotivo()."</td>";
            $response .= "</tr>";
        }
        if($y==1){
            $response .= "<tr class='tr-res'>";
            $response .= "<td colspan='6'>No tiene registros</td>";
            $response .= "</tr>";
        }
        $response .= "</table>";
        $response .= "</div>";
        $response .= "<h3 style='margin-left: 20px'>Novedades de Retiros</h3>";
        $response .= "<div style='margin: 2px auto; width: 95%; min-height: 200px; max-height: 600px; overflow: auto; padding: 10px;'>";
        $response .= "<table border='1' cellpadding='2' class='table table-bordered'>";
        $response .= "<tr  class='info'>";
        $response .= "<th>No.</th>";
        $response .= "<th>C&eacute;dula Trabajador</th>";
        $response .= "<th>Nombre</th>";
        $response .= "<th>Fecha de Retiro</th>";
        $response .= "<th>Motivo de Retiro</th>";
        $response .= "<th>Nota de Retiro </th>";
        $response .= "<th>Estado</th>";
        $response .= "<th>Observaci&oacute;n</th>";
        $response .= "</tr>";
        $response .= "<tbody>";
        $y = 1;
        $mercurio35 = $this->Mercurio35->find('nit = "'.Session::getDATA('documento').'"',"order: id desc");
        foreach ($mercurio35 as $mmercurio35) {
            $response .= "<tr class='tr-res' style='text-align:left;'>";
            $response .= "<td>".$y++."</td>";
            $response .= "<td>".$mmercurio35->getCedtra()."</td>";
            $nit = $mmercurio35->getNit();
            $cedtra = $mmercurio35->getCedtra();
            /*
               $param = array("cedtra"=>$cedtra);
               $result = parent::webService("DatosTrabajador",$param);
               $result = parent::webService("Nucfamtrabajador", $param);
               if($result  == FALSE){
               $result = parent::webService("Nucfamtrabajadorina",array("cedtra"=> $cedtra));
               }
             */
            $response .= "<td>".$mmercurio35->getNomtra()."</td>";
            $response .= "<td>".$mmercurio35->getFecret()."</td>";
            foreach($codest as $mcodest){
                if($mcodest['coddoc'] == $mmercurio35->getCodest())
                    $codes = $mcodest['detalle'];
            }
            $response .= "<td>".$codes."</td>";
            $response .= "<td>".$mmercurio35->getNota()."</td>";
            $response .= "<td>".$estado[$mmercurio35->getEstado()]."</td>";
            $response .= "<td>".$mmercurio35->getMotivo()."</td>";
            $response .= "</tr>";
        }
        if($y==1){
            $response .= "<tr class='tr-res'>";
            $response .= "<td colspan='8'>No tiene registros</td>";
            $response .= "</tr>";
        }
        $response .= "</table>";
        $response .= "</div>";
        $response .= "<h3 style='margin-left: 20px'>Cambio de Datos Principales</h3>";
        $response .= "<div style='margin: 2px auto; width: 95%; min-height: 200px;max-height: 600px ; overflow: auto; padding: 10px;'>";
        $response .= "<table border='1' cellpadding='2' class='table table-bordered'>";
        $response .= "<thead>";
        $response .= "<tr class='info'>";
        $response .= "<th>No.</th>";
        $response .= "<th>Nombre</th>";
        $response .= "<th>Campo</th>";
        $response .= "<th>Campo Actualizado</th>";
        $response .= "<th>Estado</th>";
        $response .= "<th>Observaci&oacute;n</th>";
        $response .= "</tr>";
        $response .= "</thead>";
        $response .= "<tbody>";
        $y = 1;
        $mercurio43 = $this->Mercurio43->find('documento = "'.Session::getDATA('documento').'"',"order: id desc");
        foreach ($mercurio43 as $mmercurio43) {
            $campo='';
            if($mmercurio43->getCampo() == 'nomrep')$campo="Nombre de Representante Legal";
            if($mmercurio43->getCampo() == 'razsoc')$campo="Raz&oacute;n Social";
            if($mmercurio43->getCampo() == 'cedrep')$campo="C&eacute;dula de Representante Legal";
            if($mmercurio43->getCampo() == 'fecnac')$campo="Fecha de Nacimiento";
            if($mmercurio43->getCampo() == 'sexo'){
                $campo="Sexo";
                if($mmercurio43->getValor() == 'F')$mmercurio43->setValor('Femenino');
                if($mmercurio43->getValor() == 'M')$mmercurio43->setValor('Masculino');
            }
            $response .= "<tr class='tr-res' style='text-align:left;'>";
            $response .= "<td>".$y++."</td>";
            $response .= "<td>".Session::getData('nombre')."</td>";
            $response .= "<td>".$campo."</td>";
            $response .= "<td>".$mmercurio43->getValor()."</td>";
            $response .= "<td>".$estado[$mmercurio43->getEstado()]."</td>";
            $response .= "<td>".$mmercurio43->getMotivo()."</td>";
            $response .= "</tr>";
        }
        if($y==1){
            $response .= "<tr class='tr-res'>";
            $response .= "<td colspan='6'>No tiene registros</td>";
            $response .= "</tr>";
        }
        $response .= "</table>";
        $response .= "</div>";
        echo $response;
    }

    public function fecretAction(){
        $this->setResponse("ajax");
        $fecret = $this->getPostParam("fecret");
        $fecha = new Date();
        Debug::addVariable("a",$fecret);
        Debug::addVariable("b",$fecha);
        //throw new DebugException(0);

        if($fecret > $fecha){
            $response = parent::errorsFunc("La fecha de retiro no debe ser mayor a la fecha actual");
        }else{
            $response = parent::successFunc("");
        }
        $this->renderText(json_encode($response));
    }

    public function verfiComperAction(){
        $this->setResponse("ajax");
        $param = array("cedtra"=>Session::getData('documento'));
        $result = parent::webService("Nucfamconyuge", $param);
        $response = false;
        if($result != false){
            $response = true;
        }
        $this->renderText(json_encode($response));
    }

    public function morpretra_viewAction(){
        $this->setResponse("ajax");
        echo parent::showTitle('Consulta de mora por trabajadores');     
    }
    public function morpretraAction(){
        $this->setResponse("ajax");
        $perini = $this->getPostParam("perini","addslaches","alpha","extraspaces","striptags");
        $perfin = $this->getPostParam("perfin","addslaches","alpha","extraspaces","striptags");
        $response = "";
        $response .="<div style='margin-top: 3%'>";
        $response .="<table class='table table-bordered table-hover'>";
        $response .="<tr class='info' cellspacing='10'>";
        $response .="<th>Documento</th>";
        $response .="<th>Nombre del Afiliado</th>";
        $response .="<th>Periodo</th>";
        $response .="<th>&nbsp;</th>";
        $response .="</tr>";
        $result = parent::webService("MoraPresuntaTra",array('nit'=>Session::getDATA('documento'),'perini'=>$perini,'perfin'=>$perfin));
        $cedtra = '';
        if($result!=false){
            foreach($result as $mresult){
                $ced = $mresult['identificacion'];
                if($cedtra != $mresult['identificacion']){
                    $response .="<tr onclick='trshow($ced)' style='text-align: left; cursor:pointer'; class='active'>";
                    $response .="<td>{$mresult['identificacion']}</td>";
                    $response .="<td>{$mresult['afiliado']}</td>";
                    $response .="<td>{$mresult['periodo']}</td>";
                    $response .="<td style='text-align:center;'><input type='button' class='btn btn-info btn-xs' value='Ver mas' id=''></td>";
                    $response .="</tr>";
                }else{
                    $response .="<tr onclick='trshow($ced)' class='ced$ced 'style='text-align: left; cursor:pointer; display: none;'>";
                    $response .="<td>{$mresult['identificacion']}</td>";
                    $response .="<td>{$mresult['afiliado']}</td>";
                    $response .="<td>{$mresult['periodo']}</td>";
                    $response .="<td>&nbsp;</td>";
                    $response .="</tr>";
                }
                $cedtra = $mresult['identificacion'];
            }
        }
        $response .="</table>";
        $response .="</div>";
        return $this->renderText(json_encode($response));
    }
    public function morpretra_reportAction(){
        $this->setResponse("ajax");
        $perini = $this->getPostParam("perini","addslaches","alpha","extraspaces","striptags");
        $perfin = $this->getPostParam("perfin","addslaches","alpha","extraspaces","striptags");
        $formu = new FPDF('P','mm','A4');
        $formu->AddPage();
        $formu->SetFillColor(236,248,240); 
        //$formu->SetFont('Arial','','12');
        //$formu->SetMargins(10,20,10);
        $formu->Cell(15,4,"",0,1,"L",0,0);
        $formu->Image('public/img/comfamiliar-logo.jpg',5,5,77,20);
        $nit = Session::getDATA('documento');
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->SetFont('Arial','B','14');
        $formu->Cell(190,15,"",0,1,"C",0,0);
        $formu->Cell(190,6,"Consulta de mora por trabajadores",0,1,"C",0,0);
        $formu->Ln();
        $formu->SetFont('Arial','B','12');
        $formu->Cell(40,6,"Documentos",1,0,"C",0,0);
        $formu->Cell(110,6,"Nombre del Afiliado",1,0,"C",0,0);
        $formu->Cell(40,6,"Periodos",1,1,"C",0,0);
        $result = parent::webService("MoraPresuntaTra",array('nit'=>Session::getDATA('documento'),'perini'=>$perini,'perfin'=>$perfin));
        $formu->SetFont('Arial','','11');
        if($result != FALSE){
            foreach($result as $mresult){
                $formu->Cell(40,5,$mresult['identificacion'],1,0,"C",0,0);
                $formu->Cell(110,5,$mresult['afiliado'],1,0,"C",0,0);
                $formu->Cell(40,5,$mresult['periodo'],1,1,"C",0,0);
            }
        }
        $formu->Ln();

        $file = "public/temp/reportes/morpretra-".Session::getDATA('documento').".pdf";
        ob_clean();
        $formu->Output( $file,"F");
        $formu = parent::successFunc("Genera Formulario",$file);
        $this->renderText(json_encode($formu)); 
    }
    public function morpretra_repxlsAction($perini,$perfin){
        $fecha = new Date();
        $this->setResponse('view');
        $file = "public/temp/"."morpretra_report".Session::getDATA('documento').".xls";
        //$excel->setColumn(1,1,20);
        require_once "Library/Excel/Main.php";
        $excels = new Spreadsheet_Excel_Writer($file);
        $excel = $excels->addWorksheet();
        $column_title = $excels->addFormat(array('fontfamily' => 'Verdana',
                    'size' => 12,
                    'fgcolor' => 50,
                    'border' => 1,
                    'bordercolor' => 'black',
                    "halign" => 'center'
                    ));
        $title = $excels->addFormat(array(   'fontfamily' => 'Verdana',
                    'size' => 13,
                    'border' => 0,
                    'bordercolor' => 'black',
                    "halign" => 'center'
                    ));
        $column_style = $excels->addFormat(array(   'fontfamily' => 'Verdana',
                    'size' => 11,
                    'border' => 1,
                    'bordercolor' => 'black',
                    ));
        $excel->setMerge(0,0,0,3);

        $excel->write(0,0,'Consulta de mora por trabajadores',$title);
        //$columns = array('#','Documentos','Nombre del Afiliado','Periodos');
        $columns = array('Documentos','Nombre del Afiliado','Periodos');
        $excel->setColumn(0,0,20);
        $excel->setColumn(1,1,40);
        $excel->setColumn(2,2,20);
        //$excel->setColumn(3,3,20);
        $i = 0;
        $j = 2;
        foreach($columns as $column){
            $value = ucfirst($column);
            $excel->write($j, $i++, $value, $column_title);
        }
        $j++;
        $result = parent::webService("MoraPresuntaTra",array('nit'=>Session::getDATA('documento'),'perini'=>$perini,'perfin'=>$perfin));
        if($result!=false){
            $total=1;
            foreach($result as $mresult){
                    $i = 0;
                    //$excel->write($j, $i++,$total++, $column_style);
                    //$excel->writeString($j, $i++,number_format($salario,0,'.','.'), $column_style);
                    $excel->write($j, $i++,$mresult['identificacion'], $column_style);
                    $excel->write($j, $i++,$mresult['afiliado'], $column_style);
                    $excel->write($j, $i++,$mresult['periodo'], $column_style);
                    $j++;
            }
        }
        $fecha = new Date();
        $excels->close();
        header("location: ".Core::getInstancePath()."/{$file}");
    }
    public function dattraAction(){
        $this->setResponse('ajax');
        $cedtra = $this->getPostParam('cedtra');
        $coddoc = $this->getPostParam('coddoc');
        Debug::addVariable("a",$cedtra);
        //throw new DebugException(0);
        $param = array("cedtra"=>$cedtra,"coddoc"=>$coddoc);
        $result = parent::webService("Dattra",$param);
        if($result == false){
            $response = parent::successFunc("El Trabajador no se encuentra registrado");
            return $this->renderText(json_encode($response));
        }
        $response['prinom'] = $result[0]['prinom'];
        $response['segnom'] = $result[0]['segnom'];
        $response['priape'] = $result[0]['priape'];
        $response['segape'] = $result[0]['segape'];
        $response['direccion'] = $result[0]['direccion'];
        $response['fecafi'] = date("Y-m-d",strtotime($result[0]['fecafi']));
        $response['fecnac'] = date("Y-m-d",strtotime($result[0]['fecnac']));
        $response['salario'] = '';
        $response['sexo'] = $result[0]['sexo'];
        $response['telefono'] = $result[0]['telefono'];
        $response['celular'] = $result[0]['celular'];
        //nuevos
        $response['email']= $result[0]['email'];
        $response['barrio']= $result[0]['barrio'];
        $response['estciv']= $result[0]['estciv'];
        $response['departn']= $result[0]['departn'];
        $response['ciunac']= $result[0]['ciunac'];
        $response['profesion']= $result[0]['profesion'];
        $response['depart']= $result[0]['depart'];
        $response['codciu']= $result[0]['codciu'];
        $response['zona']= $result[0]['zona'];
        $response['ubiviv']= $result[0]['tipviv'];
        $response['tipviv']= $result[0]['vivienda'];
        $response = parent::successFunc("",$response);
        return $this->renderText(json_encode($response));
    }

    public function dattraconAction(){
        $this->setResponse('ajax');
        $cedtra = $this->getPostParam('cedtra');
        $coddoc = $this->getPostParam('coddoc');
        $param = array("cedtra"=>$cedtra,"coddoc"=>$coddoc);
        $result = parent::webService("Dattra",$param);
        if($result == false){
            $response = parent::successFunc("no se encuentra registrado");
            return $this->renderText(json_encode($response));
        }
        $response['prinom'] = $result[0]['prinom'];
        $response['segnom'] = $result[0]['segnom'];
        $response['priape'] = $result[0]['priape'];
        $response['segape'] = $result[0]['segape'];
        $response['fecnac'] = date("Y-m-d",strtotime($result[0]['fecnac']));
        $response['sexo'] = $result[0]['sexo'];
        //nuevos
        $response = parent::successFunc("",$response);
        return $this->renderText(json_encode($response));
    }

    public function traerZonaAction(){
        $this->setResponse("ajax");
        $codciu = $this->getPostParam("ciures");
        $migra089 = $this->Migra089->find("codciu = $codciu");
        if($migra089 != false){
            $response = "<option value='@'>Seleccione...</option>";
            foreach($migra089 as $mmigra089)
                $response .= "<option value='{$mmigra089->getCodzon()}'>{$mmigra089->getDetzon()}</option>";
        }else{
            $response = "<option value='@'>Seleccione... </option>";
        }
        $this->renderText(json_encode($response));
    }

    public function valconAction(){
        $this->setResponse('ajax');
        $cedtra = $this->getPostParam('cedtra');
        $cedcon = $this->getPostParam('cedcon');
        $coddoc = $this->getPostParam('coddoc');
        $param = array("cedtra"=>$cedtra,"coddoc"=>$coddoc);
        $result = parent::webService("Datconyuge",$param);
        if($result != false){
            foreach($result as $mresult){
                if($mresult['cedcon'] == $cedcon){
                    $response = parent::errorFunc("El Trabajador ya tiene afiliada esta conyuge");
                    return $this->renderText(json_encode($response));
                }
            }
        }
        $param = array("cedtra"=>$cedcon);
        $result = parent::webService("Nucfamconyuge", $param);
        $response = false;
        if($result != false){
            $idcony = $result[0]['conyuge'];
            $result = parent::webService("DatosConyuje",array("documento"=>$idcony));
            if($result != false){
                foreach ($result as $rresult) {
                    if($rresult['documento']!=$cedtra){
                        $response = parent::errorFunc("la conyuge tiene una relacion de convivencia con otra persona");
                        return $this->renderText(json_encode($response));
                    }
                }
            }
        }
        if($cedtra==$cedcon){
            $response = parent::errorFunc("la conyuges tiene el mismo documento que el trabajador");
            return $this->renderText(json_encode($response));
        }
        $response = parent::successFunc("");
        return $this->renderText(json_encode($response));
    }

    public function adjuntoCamAction(){
        $this->setResponse("ajax");
        $fecha = new Date();
        $codare = $this->getPostParam("codare");
        $codope = $this->getPostParam("codope");
        $adjdoc = $this->getPostParam("adjdoc");
        $tipapo = $this->getPostParam("tipapo");
        $response = "";
        $mercurio13 = $this->Mercurio13->find("codcaj = '".SESSION::getDATA('codcaj')."' AND codare = '$codare' AND codope='$codope'");
        foreach($mercurio13 as $mmercurio13){
            $mercurio12 = $this->Mercurio12->findFirst("coddoc = '{$mmercurio13->getCoddoc()}'");
            //if($tipapo == '102'){
                if($adjdoc == '1'){
                    if($mmercurio13->getCoddoc() == 28 ||  $mmercurio13->getCoddoc() ==  24 ||  $mmercurio13->getCoddoc() ==  21){
                        $response .= "<tr>";
                        $response .= " <td colspan='2' style='text-align: right;'><label>Archivo {$mercurio12->getDetalle()} (pdf):</label></td>";
                        $response .= "<td colspan='2'>".Tag::fileField("archivo_{$mmercurio13->getCoddoc()}","accept: application/pdf, image/*")."</td>";
                        $response .= "</tr>";
                    }else{
                        continue;
                    }
                }
                if($adjdoc == '2'){
                    if($mmercurio13->getCoddoc() == 28 ||  $mmercurio13->getCoddoc() ==  24 ||  $mmercurio13->getCoddoc() ==  22){
                        $response .= "<tr>";
                        $response .= " <td colspan='2' style='text-align: right;'><label>Archivo {$mercurio12->getDetalle()} (pdf):</label></td>";
                        $response .= "<td colspan='2'>".Tag::fileField("archivo_{$mmercurio13->getCoddoc()}","accept: application/pdf, image/*")."</td>";
                        $response .= "</tr>";
                    }else{
                        continue;
                    }
                }
                if($adjdoc == '3'){
                    if($mmercurio13->getCoddoc() == 28 ||  $mmercurio13->getCoddoc() ==  24 ||  $mmercurio13->getCoddoc() ==  23){
                        $response .= "<tr>";
                        $response .= " <td colspan='2' style='text-align: right;'><label>Archivo {$mercurio12->getDetalle()} (pdf):</label></td>";
                        $response .= "<td colspan='2'>".Tag::fileField("archivo_{$mmercurio13->getCoddoc()}","accept: application/pdf, image/*")."</td>";
                        $response .= "</tr>";
                    }else{
                        continue;
                    }
                }
                if($adjdoc == '4'){
                    if($mmercurio13->getCoddoc() == 28 ||  $mmercurio13->getCoddoc() ==  24 ||  $mmercurio13->getCoddoc() ==  25){
                        $response .= "<tr>";
                        $response .= " <td colspan='2' style='text-align: right;'><label>Archivo {$mercurio12->getDetalle()} (pdf):</label></td>";
                        $response .= "<td colspan='2'>".Tag::fileField("archivo_{$mmercurio13->getCoddoc()}","accept: application/pdf, image/*")."</td>";
                        $response .= "</tr>";
                    }else{
                        continue;
                    }
                }
                if($adjdoc == '5'){
                    if($mmercurio13->getCoddoc() == 28 ||  $mmercurio13->getCoddoc() ==  24 ||  $mmercurio13->getCoddoc() ==  25){
                        $response .= "<tr>";
                        $response .= " <td colspan='2' style='text-align: right;'><label>Archivo {$mercurio12->getDetalle()} (pdf):</label></td>";
                        $response .= "<td colspan='2'>".Tag::fileField("archivo_{$mmercurio13->getCoddoc()}","accept: application/pdf, image/*")."</td>";
                        $response .= "</tr>";
                    }else{
                        continue;
                    }
                }
                /*
            }
            if($tipapo == '101'){
                if($mmercurio13->getCoddoc() == 1 || $mmercurio13->getCoddoc() ==  2 || $mmercurio13->getCoddoc() ==  6 || $mmercurio13->getCoddoc() == 25 || $mmercurio13->getCoddoc() ==  27){
                    $response .= "<tr>";
                    $response .= " <td colspan='2' style='text-align: right;'><label>Archivo {$mercurio12->getDetalle()} (pdf):</label></td>";
                    $response .= "<td colspan='2'>".Tag::fileField("archivo_{$mmercurio13->getCoddoc()}","accept: application/pdf, image/*")."</td>";
                    $response .= "</tr>";
                }else{
                    continue;
                }
            }
            */
        }
        $this->renderText(json_encode($response));
    }    

    public function validaBeneficiariosAction(){
        $this->setResponse('ajax');
        $cedtra = $this->getPostParam('cedtra');
        $coddoc = $this->getPostParam('coddoc');
        $param = array("cedtra"=>Session::getData('documento'));
        $result = parent::webService("Nucfambeneficiarios", $param);      
        if($result!=false){
            foreach ($result as $mresult) {
                $benef = $mresult['beneficiario'];
                $datben = parent::webService("Datosfamiliar",array("documento"=>$benef,"cedtra"=>Session::getDATA('documento')));      
                if($datben[0]['documento']==$cedtra){
                    $response = parent::errorFunc("El Beneficiario ya esta registrado");
                    return $this->renderText(json_encode($response));
                }
            }
        }
        $l = $this->Mercurio34->count("*","conditions: codcaj='".Session::getDATA('codcaj')."' and cedtra='".Session::getData('documento')."' and documento='$cedtra' and estado='P'");
        if($l>0){
            $response = parent::errorFunc("El Beneficiario tiene una radicacion pendiente");
            return $this->renderText(json_encode($response));
        }
        $response = parent::successFunc("","bien");
        return $this->renderText(json_encode($response));
    }

    public function adjuntoconAction(){
        $this->setResponse("ajax");
        $fecha = new Date();
        $codare = $this->getPostParam("codare");
        $codope = $this->getPostParam("codope");
        $coddoc = $this->getPostParam("coddoc");
        $comper = $this->getPostParam("comper");
        $estciv = $this->getPostParam("estciv");
        $response = "";
        $mercurio13 = $this->Mercurio13->find("codcaj = '".SESSION::getDATA('codcaj')."' AND codare = '$codare' AND codope='$codope'");
        foreach($mercurio13 as $mmercurio13){
            if($comper == 'S'){
                if($estciv == '50' || $estciv == '54'){
                    if($mmercurio13->getCoddoc() == '16')continue;
                    if($mmercurio13->getCoddoc() == '30')continue;
                }
                if($estciv == '52'){
                    if($mmercurio13->getCoddoc() == '16')continue;
                }else{
                    if($mmercurio13->getCoddoc() == '30')continue;
                }
            }
            $mercurio12 = $this->Mercurio12->findFirst("coddoc = '{$mmercurio13->getCoddoc()}'");
            $response .= "<tr>";
            $response .= " <td colspan='2' style='text-align: right;'><label>Archivo {$mercurio12->getDetalle()} (pdf):</label></td>";
            $response .= "<td colspan='2'>".Tag::fileField("archivo_{$mmercurio13->getCoddoc()}","accept: image/*, application/pdf")."</td>";
            $response .= "</tr>";
        }
        $this->renderText(json_encode($response));
    }    


    public function valpadbioAction(){
        $this->setResponse('ajax');
        $cedtra = $this->getPostParam('cedtra');
        $cedcon = $this->getPostParam('cedcon');
        $coddoc = $this->getPostParam('coddoc');
        $parent = $this->getPostParam('parent');
        /*
        $param = array("cedtra"=>$cedtra,"coddoc"=>$coddoc);
        $result = parent::webService("Datconyuge",$param);
        if($result != false){
            foreach($result as $mresult){
                if($mresult['cedcon'] != $cedcon){
                    $response = parent::errorFunc("El Trabajador ya tiene afiliada esta conyuge");
                    return $this->renderText(json_encode($response));
                }
            }
        }
        */
        $param = array("cedtra"=>$cedtra);
        if($parent == '38'){
            $result = parent::webService("Nucfamconyuge", $param);
            $response = false;
            if($result != false){
                $documento = $result[0]['cedcon'];
                if($documento!=$cedcon){
                    $response = parent::errorFunc("No se tiene convivencia con el padre/madre biologico");
                    return $this->renderText(json_encode($response));
                }
            }else{
                $response = parent::errorFunc("No tiene una conyuge permanente registrada");
                return $this->renderText(json_encode($response));
            }
        }
        $response = parent::successFunc("");
        return $this->renderText(json_encode($response));
    }

    public function datbenAction(){
        $this->setResponse('ajax');
        $documento = $this->getPostParam('documento');
        $coddoc = $this->getPostParam('coddoc');
        //$param = array("cedtra"=>$coddoc,"coddoc"=>"1077245501");
        $param = array("cedtra"=>$coddoc,"coddoc"=>"$documento");
        $result = parent::webService("Datben",$param);
        if($result['DatBenResult']['Identificacion'] == ""){
            $response = parent::successFunc("no se encuentra registrado");
            return $this->renderText(json_encode($response));
        }
        $response['prinom'] = $result['DatBenResult']['PrimerNombre'];
        $response['segnom'] = $result['DatBenResult']['SegundoNombre'];
        $response['priape'] = $result['DatBenResult']['PrimerApellido'];
        $response['segape'] = $result['DatBenResult']['SegundoApellido'];
        $response['fecnac'] = date("Y-m-d",strtotime($result['DatBenResult']['FechaNacimiento']));
        $response['sexo'] = $result['DatBenResult']['Sexo'];

        //nuevos
        $response['captra'] = $result['DatBenResult']['CapacidadDeTrabajo'];
        $response['codciu'] = $result['DatBenResult']['CodCiudadNacimiento'];
        $response['coddep'] = $result['DatBenResult']['CodDepartamentoNacimiento'];
        $response['idpersona'] = $result['DatBenResult']['IdPersona'];
        $response['identificacion'] = $result['DatBenResult']['Identificacion'];
        $response['nivedu'] = $result['DatBenResult']['NivelEducativo']['Codigo'];
        $response['detnivedu'] = $result['DatBenResult']['NivelEducativo']['Detalle'];
        $cont = 0;
        $cont2 = 0;
        foreach($result['DatBenResult']['RelacionesBeneficiario'] as $key => $value){
            foreach($value as $mvalue){
                if(!isset($mvalue['Parentesco']['Id'])) continue;
                if($mvalue['Parentesco']['Id'] != '34')$cont++;
                if($mvalue['Parentesco']['Id'] == '36')$cont2++;
            }
        }
        $response['relacionesHijo'] = $cont;
        $response['relacionesPadre'] = $cont2;
        $response = parent::successFunc("",$response);
        return $this->renderText(json_encode($response));
    }

    public function validarHijastrosAction(){
        $this->setResponse('ajax');
        $documento = $this->getPostParam('documentop');
        $coddoc = $this->getPostParam('coddocmb');
        $docben = $this->getPostParam('documento');
        $coddocben = $this->getPostParam('coddoc');
        $param = array("cedtra"=>$coddocben,"coddoc"=>"$docben");
        $result = parent::webService("Datben",$param);
        if($result['DatBenResult']['Identificacion'] == ""){
            $response = parent::successFunc("no se encuentra registrado");
            return $this->renderText(json_encode($response));
        }
        $flagResponse = false;
        foreach($result['DatBenResult']['RelacionesBeneficiario'] as $key => $value){
            foreach($value as $mvalue){
                if(!isset($mvalue['Identificacion']) || !isset($mvalue['TipoDocumento']['Id'])) continue;
                if($mvalue['Identificacion'] == $documento && $mvalue['TipoDocumento']['Id'] == $coddoc){
                    $flagResponse = true;
                }
            }
        }
        $response = parent::successFunc("",$flagResponse);
        return $this->renderText(json_encode($response));
    }

    public function validarHermanohuerfanoAction(){
        $this->setResponse('ajax');
        $documento = $this->getPostParam('documento');
        $coddoc = $this->getPostParam('coddoc');
        $param = array("cedtra"=>$coddoc,"coddoc"=>"$documento");
        $result = parent::webService("Datben",$param);
        if($result['DatBenResult']['Identificacion'] == ""){
            $response = parent::successFunc("no se encuentra registrado");
            return $this->renderText(json_encode($response));
        }
        $flagResponse = false;
        $cont = 0;
        foreach($result['DatBenResult']['RelacionesBeneficiario'] as $key => $value){
            foreach($value as $mvalue){
                if(!isset($mvalue['Parentesco']['Id'])) continue;
                if($mvalue['Parentesco']['Id'] == '36')$cont++;
            }
        }
        if($cont > 0){
            $flagResponse = true;
        }
        $response = parent::successFunc("",$flagResponse);
        return $this->renderText(json_encode($response));
    }

}
?>
