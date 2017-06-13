<?php

class ParticularController extends ApplicationController {

    public function indexAction(){
      $this->setResponse("ajax");
      echo parent::showTitle('Bienvenido');
    }
// consulta los movimientos de los particulares
    public function consultaParticularAction(){
        $this->setResponse("ajax");
      echo parent::showTitle('Consulta de Estados de Afiliación'); 
      //echo parent::showHelp('Esta opción le permite verificar en que estado se encuentra su proceso de afiliación.');
      $estado = array('A' => 'Aprobada', 'X' => 'Rechazada','P' => 'Pendiente');
      $mercurio30 = $this->Mercurio30->find('nit = "'.Session::getDATA('documento').'"');
      $response = "";
      $response .= "<h3 style='margin-left: 20px'>Afiliaciones Empresa</h3>";
      $response .= "<div style='margin: 15px auto; width: 95%; height: 150px; overflow: auto; padding: 10px;'>";
      $response .= "<table border='1' cellpadding='2' class='table table-bordered'>";
      $response .= "<thead>";
      $response .= "<tr class='info'>";
      $response .= "<th>No.</th>";
      $response .= "<th>Nit</th>";
      $response .= "<th>Razón Social</th>";
      $response .= "<th>Estado</th>";
      $response .= "<th>Motivo</th>";
      $response .= "</tr>";
      $response .= "</thead>";
      $response .= "<tbody>";
      $x = 1;
      foreach ($mercurio30 as $mmercurio30) {
      	$response .= "<tr class='tr-res'>";
      	$response .= "<td>".$x++."</td>";
      	$response .= "<td>".Session::getDATA('documento')."</td>";
      	$response .= "<td>".$mmercurio30->getRazsoc()."</td>";
      	$response .= "<td>".$estado[$mmercurio30->getEstado()]."</td>";
      	$response .= "<td>".$mmercurio30->getMotivo()."</td>";
      	$response .= "</tr>";
      }
      if($x==1){
      	$response .= "<tr class='tr-res'>";
      	$response .= "<td colspan='5'>No tiene registros</td>";
      	$response .= "</tr>";
      }
      $response .= "</tbody>";
      $response .= "</table>";
      $response .= "</div>";
      echo $response;
    }

// calcula el digito de verificacion
    public function digverAction(){
        $this->setResponse('ajax');
        $mnit = $this->getPostParam("nit");
        $arreglo = array(71,67,59,53,47,43,41,37,29,23,19,17,13,7,3);                    
        $nit = sprintf("%015s",$mnit);                                                   
        $suma = 0;                                                                       
        for($i=1;$i<=count($arreglo);$i++){                                              
            $suma += (int)(substr($nit,$i-1,1)) * $arreglo[$i-1];                         
        }                                                                                
        $retorno = $suma % 11;                                                           
        if($retorno >= 2) $retorno = 11 - $retorno;                                       
        $this->renderText(json_encode($retorno));
    }    

// Consulta el dependiendo del nit la razon social
    public function consultaNitAction(){
      $this->setResponse('ajax');
      $nit = $this->getPostParam("nit");
      $empresa = parent::webService("autenticar",array('tipo' => 'E', 'documento' => $nit, 'coddoc'=>Session::getData('coddoc')));
      $response = "";
      if($empresa != false){
        $razsoc = $empresa[0]['nombre'];
        $codact = $empresa[0]['codact'];
        $cod = substr($codact,0,2);
        $response = array('razsoc'=>$razsoc,'codact'=>$cod);
      }else{
          $response = false;
      }
      $this->renderText(json_encode($response));
    }

// Validacion de Nit que exista en la Caja 
    public function validaNitAction(){
      $this->setResponse('ajax');
      $nit = $this->getPostParam("nit");
      $empresa = parent::webService("autenticar",array('tipo' => 'E', 'documento' => $nit, 'coddoc'=>Session::getData('coddoc')));
      $response = true;
      if($empresa != false){
        $response = false;
      }
      $this->renderText(json_encode($response));
    }
      
// Trae la ciudad filtrada por el Departamento
    public function traerCiudadAction(){
        $this->setResponse("ajax");
        $coddep = $this->getPostParam("coddep");
        $codciu = parent::webService("ciudadesFiltradas",array("coddep"=>$coddep));
        
        if($codciu != false){
            $response = "<option value=''>Seleccione...</option>";
            foreach($codciu as $ciudad)
                $response .= "<option value='$ciudad[codciu]'=>$ciudad[detalle]</option>";
        }else{
            $response = false;
        }
        $this->renderText(json_encode($response));
    }
          
// Trae la barrio filtrada por la ciudad
    public function traerBarrioAction(){
        $this->setResponse("ajax");
        $codciu = $this->getPostParam("codciu");
        $barrio = parent::webService("BarrioFiltrados",array("codciu"=>$codciu));
        if($barrio != false){
            $response = "<option value=''>Seleccione...</option>";
            foreach($barrio as $barrios)
                $response .= "<option value='{$barrios['idbarrio']}'=>{$barrios['detalle']}</option>";
        }else{
            $response = false;
        }
        $this->renderText(json_encode($response));
    }
 
// Vista de afiliacion de Empresas
    public function addempresa_viewAction(){
        $this->setResponse("ajax");
        $codare = $this->getPostParam("codare");
        $codope = $this->getPostParam("codope");
        echo parent::showTitle('Afiliación como Empresa');
        Tag::displayTo("nit", SESSION::getDATA('documento'));
        /*
           $codact = parent::webService('actividades',array());
           foreach($codact as $mcodact){
           $_codact[$mcodact['codact']] = $mcodact['codact']." - ".substr($mcodact['detalle'],0,90);
           } 
         */
        $tipsoc = $this->Migra091->find("iddetalledef='102' OR iddetalledef='101'");
        foreach($tipsoc as $mtipsoc){
            $_tipsoc[$mtipsoc->getIddetalledef()] = $mtipsoc->getDetalledefinicion();
        }
        $mercurio13 = $this->Mercurio13->find("codcaj = '".SESSION::getDATA('codcaj')."' AND codare = '$codare' AND codope='$codope'");
        $adjdoc = "";
        foreach($mercurio13 as $mmercurio13){
            $mercurio12 = $this->Mercurio12->findFirst("coddoc = '{$mmercurio13->getCoddoc()}'");
            $adjdoc .= "<tr>";
            $adjdoc .= "<td style='text-align:left;'>";
            $adjdoc .= "{$mercurio12->getDetalle()}";
            $adjdoc .= "</td>";
            $adjdoc .= "<td>";
            $adjdoc .= "<input type='checkbox' id='coddoc".$mmercurio13->getCoddoc()."' name='coddoc".$mmercurio13->getCoddoc()."' />";
            $adjdoc .= "</td>";
            $adjdoc .= "</tr>";
        }
        $migra079 = $this->Migra079->findAllBySql("select distinct idciiu,seccion,descripcion FROM migra079 where division ='' and grupo ='' order by seccion;");
        //$_migra079[] = array();
        if(count($migra079) > 0){
        foreach($migra079 as $mmigra079){
            $_migra079[$mmigra079->getIdciiu()] = $mmigra079->getDescripcion();
        }
        }else{
            $_migra079 = "@";
        }
        //$this->setParamToView("codact", $_codact);
        $this->setParamToView("codare", $codare);
        $this->setParamToView("codope", $codope);
        $this->setParamToView("tipsoc", $_tipsoc);
        $this->setParamToView("migra079", $_migra079);
        $this->setParamToView("adjdoc", $adjdoc);
        Tag::displayTo("coddoc", SESSION::getDATA('coddoc'));
        //$this->setParamToView("coddoc", SESSION::getDATA('coddoc'));
    }

//Formularios afiliacion empresa
    public function addempresa_reportAction(){
        $this->setResponse("ajax");
        $response = parent::startFunc();
        $file = "";
        $calemp = $this->getPostParam("calemp","addslaches","alpha","extraspaces","striptags");
/*
        $nit = $this->getPostParam("nit","addslaches","numeric","extraspaces","striptags");
           $l = $this->Mercurio30->count("*","conditions: codcaj = '".Session::getDATA('codcaj')."' AND nit = '$nit' AND estado<>'X' ");

        if($l>0){
            $response = parent::errorFunc("Empresa Ya Envio Formulario");
            return $this->renderText(json_encode($response));
        }
        $result = parent::webService('autenticar',array("tipo"=>"E","cedtra"=>$nit, 'coddoc'=>Session::getData('coddoc')));
        if($result!=false){
            $response = parent::errorFunc("Empresa Existe Afiliada");
            return $this->renderText(json_encode($response));
        }
*/
        if($calemp=='E') $file = self::reporte_empresa_e();
        if($calemp=='I') $file = self::reporte_empresa_i();
        if($calemp=='P') $file = self::reporte_empresa_p();
        $response = parent::successFunc("Genera Formulario",$file);
        $this->renderText(json_encode($response)); 
    }

    public function reporte_empresa_e(){
        $this->setResponse("ajax");
        $response = parent::startFunc();
        $nit = $this->getPostParam("nit","addslaches","alpha","extraspaces","striptags");
        $razsoc = $this->getPostParam("razsoc","addslaches","alpha","extraspaces","striptags");
        $digver = $this->getPostParam("digver","addslaches","alpha","extraspaces","striptags");
        $coddoc = $this->getPostParam("coddoc","addslaches","alpha","extraspaces","striptags");
        $codciu = $this->getPostParam("codciu","addslaches","alpha","extraspaces","striptags");
        $direccion = $this->getPostParam("direccion","addslaches","alpha","extraspaces","striptags");
        $barr = $this->getPostParam("barrio","addslaches","alpha","extraspaces","striptags");
        $telefono = $this->getPostParam("telefono","addslaches","alpha","extraspaces","striptags");
        $celular = $this->getPostParam("telcel","addslaches","alpha","extraspaces","striptags");
        $fax = $this->getPostParam("fax","addslaches","alpha","extraspaces","striptags");
        $email = $this->getPostParam("email","addslaches","extraspaces","striptags");
        $pagweb = $this->getPostParam("pagweb","addslaches","extraspaces","striptags");
        $feccon = $this->getPostParam("feccon","addslaches","alpha","extraspaces","striptags");
        $codact = $this->getPostParam("codact","addslaches","alpha","extraspaces","striptags");
        $tottra = $this->getPostParam("tottra","addslaches","alpha","extraspaces","striptags");
        $totnom = $this->getPostParam("totnom","addslaches","alpha","extraspaces","striptags");
        $cedrep = $this->getPostParam("cedrep","addslaches","alpha","extraspaces","striptags");
        $nomrep = $this->getPostParam("nomrep","addslaches","alpha","extraspaces","striptags");
        $codare = $this->getPostParam("codare","addslaches","alpha","extraspaces","striptags");
        $codope = $this->getPostParam("codope","addslaches","alpha","extraspaces","striptags");
        $tipemp = $this->getPostParam("tipemp","addslaches","alpha","extraspaces","striptags");
        $tipsoc = $this->getPostParam("tipsoc","addslaches","alpha","extraspaces","striptags");
        $calemp = $this->getPostParam("calemp","addslaches","alpha","extraspaces","striptags");
        $codzon = $this->getPostParam("codzon","addslaches","alpha","extraspaces","striptags");
        $autoriza = $this->getPostParam("autoriza","addslaches","alpha","extraspaces","striptags");
        $zonsuc = $this->getPostParam("codcius","addslaches","alpha","extraspaces","striptags");
        $dirsuc = $this->getPostParam("direccions","addslaches","alpha","extraspaces","striptags");
        $emailsuc = $this->getPostParam("emails","addslaches","alpha","extraspaces","striptags");
        $telsuc = $this->getPostParam("telefonos","addslaches","alpha","extraspaces","striptags");
        $faxsuc = $this->getPostParam("faxs","addslaches","alpha","extraspaces","striptags");
        $nomsub = $this->getPostParam("nomsub","addslaches","alpha","extraspaces","striptags");
        $telsub = $this->getPostParam("telsub","addslaches","alpha","extraspaces","striptags");
        $celsub = $this->getPostParam("celsub","addslaches","alpha","extraspaces","striptags");
        $causalario = $this->getPostParam("causalario","addslaches","alpha","extraspaces","striptags");
        $segsoc = $this->getPostParam("segsoc","addslaches","alpha","extraspaces","striptags");
        $ultmes = $this->getPostParam("mesnomcau","addslaches","alpha","extraspaces","striptags");
        $valor = $this->getPostParam("valnomcau","addslaches","alpha","extraspaces","striptags");
        $cajavie = $this->getPostParam("ultcaj","addslaches","alpha","extraspaces","striptags");
        $direnvio = $this->getPostParam("direnvio","addslaches","alpha","extraspaces","striptags");
        $barrioen = $this->getPostParam("barrioen","addslaches","alpha","extraspaces","striptags");
        $munienvi = $this->getPostParam("codciuen","addslaches","alpha","extraspaces","striptags");
        $objemp = $this->getPostParam("objemp","addslaches","alpha","extraspaces","striptags");
        $feccon = $this->getPostParam("feccon","addslaches","alpha","extraspaces","striptags");
        $cargo = $this->getPostParam("cargo","addslaches","alpha","extraspaces","striptags");
        $detcar = $this->Migra091->findFirst("iddetalledef = '$cargo'");
        if($detcar == FALSE){
            $detcar = New Migra091();
        }
        //$feccon = "2015-10-05";
        $anocon = substr($feccon,0,4);
        $mescon = substr($feccon,4,2);
        $diacon = substr($feccon,6,8);    

        $fecha =  new Date();
        //$ultmesnom = $fecha->getMonthName($ultmes);
        //$ultmes = $ultmesnom;
        switch ($ultmes){
            case 0:
                $d = "Enero";
                break;
            case 1:
                $d = "Febrero";
                break;
            case 2:
                $d = "Marzo";
                break;
            case 3:
                $d = "Abril";
                break;
            case 4:
                $d = "Mayo";
                break;
            case 5:
                $d = "Junio";
                break;
            case 6:
                $d = "Julio";
                break;
            case 7:
                $d = "Agosto";
                break;
            case 8:
                $d = "Septiembre";
                break;
            case 9:
                $d = "Octubre";
                break;
            case 10:
                $d = "Noviembre";
                break;
            case 11:
                $d = "Diciembre";
                break;
        }
        $ultmes = $d;
        $valor = number_format($valor,0,".",".");
        $formu = new FPDF('P','mm',array(230,350));    
        $formu->AddPage();
        $formu->SetTextColor(44,72,151);    
        $formu->SetDrawColor(44,72,151);    
        $formu->SetFont('Arial','B','8');
        $formu->Image('public/img/comfamiliar-logo.jpg',8,12,67,20);
        $formu->Image('public/img/piePagCart/foot3.png',174,13,35,20);
        $formu->Cell(210,3,"","LTR",1,"L",0,0);
        $formu->Cell(60,3,"","L",0,"L",0,0);
        $formu->Cell(9,3,"",0,0,"L",0,0);
        $formu->Cell(70,3,"",0,0,"C",0,0);
        $formu->Cell(69,3,"",0,0,"R",0,0);
        $formu->Cell(2,3,"","R",1,"R",0,0);
        $formu->Cell(210,8,"","LR",1,"L",0,0);
        $formu->Cell(90,8,"","L",0,"L",0,0);
        $formu->Cell(9,8,"",0,0,"L",0,0);
        $formu->SetFont('Arial','B','15');
        $formu->Cell(62,8,html_entity_decode("AFILIACI&Oacute;N EMPLEADOR"),0,0,"C",0,0);
        $formu->Cell(49,8,"","R",1,"R",0,0);
        $formu->Cell(210,10,"","LR",1,"L",0,0);
        $formu->SetFont('Arial','B','9');
        $formu->Cell(159,5,html_entity_decode("Antes de diligenciar, lea cuidadosamente las instrucciones detalladas al respaldo"),"L",0,"C",0,0);
        $formu->Cell(2,5,"",0,0,"C",0,0);
        $formu->Cell(45,5,html_entity_decode("FECHA DE RECIBO"),1,0,"C",0,0);
        $formu->Cell(4,5,"","R",1,"C",0,0);
        $formu->Cell(159,5,html_entity_decode("Favor diligenciar a m&aacute;quina o en letra clara y legible. Utilice tinta color negro."),"L",0,"C",0,0);
        $formu->Cell(2,5,"",0,0,"C",0,0);
        $formu->Cell(17,5,html_entity_decode("A&Ntilde;O"),1,0,"C",0,0);
        $formu->Cell(14,5,html_entity_decode("MES"),1,0,"C",0,0);
        $formu->Cell(14,5,html_entity_decode("D&Iacute;A"),1,0,"C",0,0);
        $formu->Cell(4,5,"","R",1,"C",0,0);
        $formu->Cell(159,5,html_entity_decode("Favor no escribir en los espacios sombreados."),"L",0,"C",0,0);
        $formu->Cell(2,5,"",0,0,"C",0,0);
        $formu->SetFillColor(192,216,255);
        $formu->Cell(17,5,$fecha->getYear(),1,0,"C",true,0);
        $formu->Cell(14,5,$fecha->getMonth(),1,0,"C",true,0);
        $formu->Cell(14,5,$fecha->getDay(),1,0,"C",true,0);
        $formu->Cell(4,5,"","R",1,"C",0,0);
        $formu->Cell(210,3,"","LR",1,"L",0,0);
        $formu->Cell(210,6,html_entity_decode(" INFORMACI&Oacute;N GENERAL DEL EMPLEADOR"),1,1,"L",true,0);
        $formu->SetFont('Arial','','9');
        $formu->Cell(210,5,html_entity_decode(" NOMBRE O RAZ&Oacute;N SOCIAL"),"LR",1,"L",0,0);
        $formu->Cell(210,5,$razsoc,"LRB",1,"C",0,0);
        $formu->Cell(210,1,"","LR",1,"L",0,0);
        $formu->Cell(168,5,html_entity_decode(" NIT O C&Eacute;DULA"),"L",0,"L",0,0);
        $formu->SetFont('Arial','','7');
        $formu->Cell(40,5,html_entity_decode("FECHA DE CONSTITUCI&Oacute;N"),"LRT",0,"C",0,0);
        $formu->Cell(2,5,"","R",1,"L",0,0);
        $formu->SetFont('Arial','','9');
        $formu->Cell(50,3,"","L",0,"C",0,0);
        $a = "";
        $b = "";
        $c = "";
        $d = "";
        switch ($tipsoc){
            case 101:
                $a = "X";
                break;
            case 102:
                $b = "X";
                break;
            case 3:
                $c = "X";
                break;
            case 4:
                $d = "X";
                break;
        }
        $formu->Cell(28,3,html_entity_decode("PERSONA"),0,0,"R",0,0);
        $formu->Cell(6,3,"","LTR",0,"L",0,0);
        $formu->Cell(18,3,html_entity_decode("JURIDICA"),0,0,"C",0,0);
        $formu->Cell(6,3,"","LTR",0,"L",0,0);
        $formu->Cell(18,3,html_entity_decode("SECTOR"),0,0,"L",0,0);
        $formu->Cell(6,3,"","LTR",0,"L",0,0);
        $formu->Cell(18,3,html_entity_decode("PRIVADO"),0,0,"C",0,0);
        $formu->Cell(6,3,"","LTR",0,"L",0,0);
        $formu->Cell(12,3,"",0,0,"L",0,0);
        $formu->SetFont('Arial','','7');
        $formu->Cell(40,3,html_entity_decode("(Persona Jur&iacute;dica)"),"LRB",0,"C",0,0);
        $formu->Cell(2,3,"","R",1,"L",0,0);
        $formu->SetFont('Arial','','10');
        $formu->Cell(55,3,$nit,"LB",0,"C",0,0);
        $formu->SetFont('Arial','','9');
        $formu->Cell(23,3,html_entity_decode("NATURAL"),0,0,"R",0,0);
        $formu->Cell(6,3,$a,"LBR",0,"C",0,0);
        $formu->Cell(18,3,"",0,0,"C",0,0);
        $formu->Cell(6,3,$b,"LBR",0,"C",0,0);
        $formu->Cell(18,3,html_entity_decode("P&Uacute;BLICO"),0,0,"L",0,0);
        $formu->Cell(6,3,$c,"LBR",0,"C",0,0);
        $formu->Cell(18,3,"",0,0,"C",0,0);
        $formu->Cell(6,3,$d,"LBR",0,"C",0,0);
        $formu->Cell(12,3,"",0,0,"L",0,0);
        $formu->SetFont('Arial','','7');
        $formu->Cell(16,3,html_entity_decode("A&Ntilde;O"),"LRB",0,"C",0,0);
        $formu->Cell(12,3,html_entity_decode("MES"),"LRB",0,"C",0,0);
        $formu->Cell(12,3,html_entity_decode("D&Iacute;A"),"LRB",0,"C",0,0);
        $formu->SetFont('Arial','','9');
        $formu->Cell(2,3,"","R",1,"L",0,0);
        $formu->Cell(168,4,"OBJETO PRINCIPAL DEL NEGOCIO","L",0,"L",0,0);
        $formu->Cell(16,4,$anocon,"LBR",0,"C",0,0);
        $formu->Cell(12,4,$mescon,"LBR",0,"C",0,0);
        $formu->Cell(12,4,$diacon,"LBR",0,"C",0,0);
        $formu->Cell(2,4,"","R",1,"L",0,0);
        $formu->Cell(168,5,"$objemp","LB",0,"C",0,0);
        $formu->Cell(42,5,"","RB",1,"L",0,0);
        $formu->Cell(210,3,"","LR",1,"L",0,0);
        $formu->Cell(158,4,html_entity_decode("ACTIVIDAD ECON&Oacute;MICA"),"L",0,"L",0,0);
        $formu->Cell(10,5,"CIIU",0,0,"L",0,0);
        //$codact = "0081";
        $migra079 = $this->Migra079->findBySql("select distinct idciiu,seccion,descripcion FROM migra079 where division ='' and grupo ='' AND idciiu='$codact'  order by seccion limit 1;");
        $detact=$migra079->getDescripcion();
        /*
        $actividades = parent::webService('actividades',array());
        foreach($actividades as $mcodact){
            if($mcodact['codact']==$codact){
                $detact = substr($mcodact['detalle'],0,90);
            }
        } 
        */
        $formu->Cell(40,5,html_entity_decode($codact),1,0,"C",true,0);
        $formu->Cell(2,4,"","R",1,"L",0,0);
        $formu->SetFont('Arial','','10');
        $formu->Cell(210,4,$detact,"LRB",1,"C",0,0);
        $formu->Cell(210,3,"","LR",1,"L",0,0);
        $si = "";
        $no = "";
        if($calemp=="D"){
            $si="X";
        }else{
            $no="X";
        }
        $formu->Cell(116,5,html_entity_decode("ES EMPLEADOR DE PERSONAS DE SERVICIO DOM&Eacute;STICO"),"L",0,"L",0,0);
        $formu->Cell(7,5,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(6,5,html_entity_decode("SI"),0,0,"R",0,0);
        $formu->Cell(6,5,$si,1,0,"C",0,0);
        $formu->Cell(7,5,"",0,0,"L",0,0);
        $formu->Cell(6,5,html_entity_decode("NO"),0,0,"R",0,0);
        $formu->Cell(6,5,$no,1,0,"C",0,0);
        $formu->Cell(4,5,"",0,0,"L",0,0);
        $formu->Cell(10,5,html_entity_decode("CIIU"),0,0,"L",0,0);
        $formu->Cell(40,5,html_entity_decode("9700"),1,0,"C",true,0);
        $formu->Cell(2,5,html_entity_decode(""),"R",1,"L",0,0);
        $formu->Cell(210,3,"","LR",1,"L",0,0);
        $formu->Cell(210,6,html_entity_decode(" INFORMACI&Oacute;N ESPEC&Iacute;FICA DEL EMPLEADOR"),1,1,"L",true,0);
        $formu->Cell(98,6,html_entity_decode(" DIRECCI&Oacute;N DEL ESTABLECIMIENTO O NEGOCIO"),"L",0,"L",0,0);
        $formu->Cell(10,6,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(47,6,html_entity_decode("BARRIO"),0,0,"L",0,0);
        $formu->Cell(11,6,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(44,6,html_entity_decode("MUNICIPIO"),"R",1,"L",0,0);
        $formu->Cell(100,4,utf8_encode($direccion),"LB",0,"C",0,0);
        $formu->Cell(8,4,"",0,0,"L",0,0);

        $barrio = '';
        $barrenvio='';
        $barri = parent::webService('Barrios',array());
        foreach($barri as $mbarri){
            if($mbarri['idbarrio']==$barr){
                $barrio = $mbarri['detalle'];
            }
            if($mbarri['idbarrio'] == $barrioen){
                $barrenvio=$mbarri['detalle'];
            }
        }
        $formu->Cell(47,4,utf8_encode($barrio),"B",0,"C",0,0);
        $detciures = "";
        $ciudades = parent::webService('ciudades',array());
        foreach($ciudades as $mcodciu){
            if($mcodciu['codciu']==$codciu){
                $detciures = $mcodciu['detalle'];
            }
            if($mcodciu['codciu']== $munienvi){
                $munienvio = $mcodciu['detalle'];   
            }
        }
        $formu->Cell(11,4,"",0,0,"L",0,0);
        $formu->Cell(44,4,$detciures,"BR",1,"L",0,0);
        $formu->Cell(50,6,html_entity_decode("TEL&Eacute;FONO FIJO"),"L",0,"L",0,0);
        $formu->Cell(5,6,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(54,6,html_entity_decode("TEL&Eacute;FONO CELULAR"),0,0,"L",0,0);
        $formu->Cell(5,6,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(42,6,html_entity_decode("FAX"),0,0,"L",0,0);
        $formu->Cell(5,6,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(24,6,html_entity_decode("AA"),0,0,"L",0,0);
        $formu->Cell(25,6,html_entity_decode("DE"),"R",1,"L",0,0);
        $formu->Cell(50,4,$telefono,"LB",0,"C",0,0);
        $formu->Cell(5,4,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(54,4,$celular,"B",0,"C",0,0);
        $formu->Cell(5,4,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(42,4,$fax,"B",0,"C",0,0);
        $formu->Cell(5,4,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(49,4,html_entity_decode(""),"BR",1,"L",0,0);
        $formu->Cell(90,6,html_entity_decode(" DIRECCI&Oacute;N ENV&Iacute;O DE CORRESPONDENCIA"),"L",0,"L",0,0);
        $formu->Cell(6,6,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(57,6,html_entity_decode("BARRIO"),0,0,"L",0,0);
        $formu->Cell(11,6,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(46,6,html_entity_decode("MUNICIPIO"),"R",1,"L",0,0);


        $formu->Cell(80,4,utf8_encode($direnvio),"LB",0,"L",0,0);
        $formu->Cell(6,4,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(47,4,utf8_encode($barrenvio),"B",0,"C",0,0);
        $formu->Cell(11,4,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(66,4,$munienvio,"BR",1,"C",0,0);


        $formu->Cell(155,5,html_entity_decode(" EMAIL"),"L",0,"L",0,0);
        $formu->Cell(55,5,html_entity_decode(""),"R",1,"L",0,0);
        $formu->Cell(145,4,$email,"LB",0,"L",0,0);
        $formu->Cell(65,4,html_entity_decode(""),"R",1,"L",0,0);
        $formu->Cell(155,5,html_entity_decode(" NOMBRE DEL REPRESENTANTE LEGAL"),"L",0,"L",0,0);
        $formu->Cell(11,5,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(44,5,html_entity_decode("C.C."),"R",1,"L",0,0);
        $formu->Cell(133,4,utf8_encode($nomrep),"LB",0,"L",0,0);
        $formu->Cell(31,4,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(46,4,$cedrep,"BR",1,"L",0,0);
        $formu->Cell(115,5,html_entity_decode(" NOMBRE CONTACTO ADMINISTRATIVO"),"L",0,"L",0,0);
        $formu->Cell(18,5,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(77,5,html_entity_decode("CARGO"),"R",1,"L",0,0);
        $formu->Cell(115,4,utf8_encode($nomsub),"LB",0,"L",0,0);
        $formu->Cell(18,4,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(77,4,"{$detcar->getDetalledefinicion()}","BR",1,"L",0,0);
        $formu->Cell(60,5,html_entity_decode(" TEL&Eacute;FONO FIJO"),"L",0,"L",0,0);
        $formu->Cell(60,5,html_entity_decode("TEL&Eacute;FONO CELULAR"),0,0,"L",0,0);
        $formu->Cell(90,5,html_entity_decode("LUGAR DONDE SE CAUSAN LOS SALARIOS"),"R",1,"L",0,0);
        $formu->Cell(50,4,$telsub,"LB",0,"L",0,0);
        $formu->Cell(11,4,"",0,0,"L",0,0);
        $formu->Cell(50,4,$celsub,"B",0,"L",0,0);
        $formu->Cell(10,4,"","",0,"L",0,0);
        $formu->Cell(89,4,$causalario,"BR",1,"L",0,0);
        $formu->Cell(210,5,html_entity_decode(" ENTIDAD DE SEGURIDAD SOCIAL A LA QUE SE ENCUENTRAN AFILIADOS LOS TRABAJADORES"),"LR",1,"L",0,0);
        $formu->Cell(210,4,utf8_encode($segsoc),"LBR",1,"L",0,0);
        $formu->Cell(210,2,"","LBR",1,"L",0,0);
        $formu->Cell(210,6,html_entity_decode(" OTROS DATOS DEL EMPLEADOR"),1,1,"L",true,0);
        $formu->Cell(120,5,html_entity_decode(" &Uacute;LTIMA N&Oacute;MINA CAUSADA"),"L",0,"L",0,0);
        $formu->Cell(90,5,html_entity_decode("N&Uacute;MERO TOTAL DE TRABAJADORES"),"R",1,"L",0,0);
        $formu->Cell(15,5,html_entity_decode(" MES"),"L",0,"L",0,0);
        $formu->Cell(40,5,$ultmes,"B",0,"L",0,0);
        $formu->Cell(15,5,"VALOR",0,0,"L",0,0);
        $formu->Cell(39,5,"$ ".$valor,"B",0,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(91,5,$tottra,"BR",1,"C",0,0);
        $formu->Cell(210,6,html_entity_decode(" CAJA DE COMPENSACI&Oacute;N A LA CUAL ESTA O ESTUVO AFILIADO ANTERIORMENTE"),"LR",1,"L",0,0);
        $formu->Cell(210,4,$cajavie,"LBR",1,"L",0,0);
        $formu->Cell(98,4,html_entity_decode(""),"L",0,"L",0,0);
        $formu->SetFont('Arial','','6');
        $formu->Cell(112,4,html_entity_decode("EL EMPLEADOR QUE SUMINISTRE DATOS FALSOS SER&Aacute; SANCIONADO DE"),"R",1,"C",0,0);
        $formu->SetFont('Arial','','9');
        $formu->Cell(98,4,html_entity_decode(" FIRMA Y SELLO DEL EMPLEADOR"),"L",0,"L",0,0);
        $formu->SetFont('Arial','','6');
        $formu->Cell(112,4,html_entity_decode("ACUERDO CON EL ART&IACUTE;CULO 45 DE LA LEY DE 1982."),"R",1,"C",0,0);
        $formu->SetFont('Arial','','7');
        $formu->Cell(98,4,html_entity_decode(""),"L",0,"L",0,0);
        $formu->SetFont('Arial','','6');
        $formu->Cell(112,4,html_entity_decode("EN CASO DE SER ACEPTADOS COMO AFILIADOS NOS COMPROMETEMOS A"),"R",1,"C",0,0);
        $formu->SetFont('Arial','','7');
        $formu->Cell(98,4,html_entity_decode(""),"LB",0,"L",0,0);
        $formu->SetFont('Arial','','6');
        $formu->Cell(112,4,html_entity_decode("CUMPLIR Y RESPETAR LA LEGISLACI&Oacute;N DEL SUBSIDIO FAMILIAR, AL IGUAL"),"R",1,"C",0,0);
        $formu->Cell(98,4,html_entity_decode(""),"L",0,"L",0,0);
        $formu->SetFont('Arial','','6');
        $formu->Cell(112,4,html_entity_decode("QUE LOS ESTATUTOS Y REGLAMENTOS DE COMFAMILIAR"),"R",1,"C",0,0);
        $formu->SetFont('Arial','','9');
        $formu->Cell(100,5,html_entity_decode(" RECIBIDO POR"),"L",0,"L",0,0);
        $formu->Cell(8,5,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(102,5,html_entity_decode("GRABADO POR"),"R",1,"L",0,0);
        $formu->Cell(90,5,html_entity_decode(""),"LB",0,"L",0,0);
        $formu->Cell(20,5,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(100,5,html_entity_decode(""),"BR",1,"L",0,0);
        $formu->Cell(210,5,html_entity_decode(" OBSERVACIONES"),"LR",1,"L",0,0);
        $formu->Cell(210,5,html_entity_decode(""),"LBR",1,"L",0,0);
        $formu->Cell(70,5,html_entity_decode(""),"L",0,"L",0,0);
        $formu->Cell(25,5,html_entity_decode("NOMBRE Y NIT"),0,0,"L",0,0);
        $formu->Cell(115,5,utf8_encode($razsoc." - ".$nit),"BR",1,"L",0,0);
        $formu->Cell(70,5,html_entity_decode(""),"L",0,"L",0,0);
        $formu->Cell(25,5,html_entity_decode("Recibido por:"),0,0,"L",0,0);
        $formu->Cell(42,5,html_entity_decode(""),"B",0,"L",0,0);
        $formu->Cell(32,5,html_entity_decode("N&uacute;mero de Radicado:"),0,0,"L",0,0);
        $formu->Cell(41,5,html_entity_decode(""),"BR",1,"L",0,0);
        $formu->Cell(70,5,html_entity_decode(""),"L",0,"L",0,0);
        $formu->Cell(30,5,html_entity_decode("Fecha de Recibido:"),0,0,"L",0,0);
        $formu->Cell(110,5,$fecha,"BR",1,"L",0,0);
        $formu->Cell(70,5,html_entity_decode(""),"L",0,"L",0,0);
        $formu->Cell(140,5,html_entity_decode("OBSERVACIONES"),"R",1,"L",0,0);
        $formu->Cell(70,5,html_entity_decode("AFILIACI&Oacute;N EMPLEADOR"),"L",0,"C",0,0);
        $formu->Cell(140,5,html_entity_decode(""),"BR",1,"L",0,0);
        $formu->Cell(70,5,html_entity_decode(""),"L",0,"L",0,0);
        $formu->Cell(140,5,html_entity_decode(""),"BR",1,"L",0,0);
        $formu->Cell(70,2,html_entity_decode(""),"LB",0,"L",0,0);
        $formu->Cell(140,2,html_entity_decode(""),"TBR",1,"L",0,0);


        $this->setResponse('view');
        $file = "public/temp/reportes/empresa_$nit.pdf";
        ob_clean();
        $formu->Output( $file,"F");
        return $file;
    }

    public function reporte_empresa_p(){
        $nit = $this->getPostParam("nit","addslaches","alpha","extraspaces","striptags");
        $formu = new FPDF('L','mm','A4');
        $formu->AddPage();
	    $formu->setTextColor(0);
        $formu->SetFont('Arial','','25');
        $this->setResponse('view');
        $formu->Image('public/img/Logo-B_y_N.jpg',20,15,42,15);
        //$formu->Image('public/img/piePagCart/foot3.png',250,15,27,15);
        $formu->SetTextColor(0);    
        $formu->SetFont('Arial','B','12');
        $formu->Cell(10,5,"",0,0,"L",0,0);
        $formu->Cell(260,5,"","LTR",1,"C",0,0);
        $formu->Cell(10,5,"",0,0,"L",0,0);
        $formu->Cell(10,5,"","L",0,"L",0,0);
        $formu->Cell(250,5,html_entity_decode("AFILIACI&Oacute;N  DE PENSIONADOS AL SISTEMA DE CAJAS DE  COMPENSACION FAMILIAR"),"R",1,"C",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(10,5,"",0,0,"L",0,0);
        $formu->Cell(10,5,"","L",0,"L",0,0);
        $formu->Cell(250,5,html_entity_decode("Favor elaborar este formulario con letra imprenta y utilizar tinta de color negro"),"R",1,"C",0,0);
        $formu->Cell(10,5,"",0,0,"L",0,0);
        $formu->Cell(10,5,"","L",0,"L",0,0);
        $formu->Cell(250,3,html_entity_decode("Los pensionados no seran beneficiarios de la cuota monetaria del Subsidio Familiar"),"R",1,"C",0,0);
        $formu->Cell(10,3,"",0,0,"L",0,0);
        $formu->Cell(260,3,"","LRB",1,"C",0,0);
        $formu->SetFont('Arial','B','8');
        $formu->Cell(10,3,"",0,0,"L",0,0);
        $formu->Cell(260,5,html_entity_decode("Seleccione el tipo de afiliac&oacute;n como pensionado"),"",1,"C",0,0);
        $formu->SetFont('Arial','','5.8');
        $formu->Cell(10,5,"",0,0,"L",0,0);
        $formu->Cell(3,3,"",1,0,"",0,0);
        $formu->Cell(67,3,html_entity_decode("Pensionado con aportes 2%. Afiliacion para todos los servic&iacute;o de la caja"),0,0,"L",0,0);
        $formu->Cell(3,3,"",1,0,"",0,0);
        $formu->Cell(89,3,html_entity_decode("Pensionado de 25 a&ntilde;os. Afiliaci&oacute;n para los servicios de recreacion, capacitaci&oacute;n y turismo social"),0,0,"L",0,0);
        $formu->Cell(3,3,"",1,0,"",0,0);
        $formu->Cell(105,3,html_entity_decode("Pensionados r&eacute;egimen especial(1.5$ MLMV) Afiliaci&oacute;n para los servicios de recreacion, deporte y cultura"),0,1,"L",0,0);
	//Hasta aca va la cabecera
        $formu->SetFont('Arial','B','10');
        $formu->Cell(10,1,"","",1,"",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->SetFillColor(236,248,240);
        $formu->Cell(260,5,html_entity_decode("Datos del Pensionado"),1,1,"L",1,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(80,5,html_entity_decode("Identificaci&oacute;n"),"LB",0,"C",0,0);
        $formu->Cell(25,10,html_entity_decode("N&uacute;mero"),"LRB",0,"C",0,0);
        $formu->Cell(25,10,html_entity_decode("Primer Apellido"),"RB",0,"C",0,0);
        $formu->Cell(25,10,html_entity_decode("Segundo Apellido"),"RB",0,"C",0,0);
        $formu->Cell(25,10,html_entity_decode("Primer Nombre"),"RB",0,"C",0,0);
        $formu->Cell(25,10,html_entity_decode("Segundo Nombre"),"RB",0,"C",0,0);
        $formu->Cell(55,5,html_entity_decode("Estado Civil"),"RB",1,"C",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->SetFont('Arial','','6.5');
        $formu->Cell(80,2,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(180,2,html_entity_decode(""),"R",1,"C",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(27,3,html_entity_decode("Cedula de Ciudadania"),"L",0,"L",0,0);
        $formu->Cell(3,3,html_entity_decode(""),1,0,"L",0,0);
        $formu->Cell(5,3,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(27,3,html_entity_decode("Tarjeta de Identidad"),"",0,"L",0,0);
        $formu->Cell(3,3,html_entity_decode(""),1,0,"L",0,0);
        $formu->Cell(140,3,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(15,3,html_entity_decode("Soltero"),"",0,"C",0,0);
        $formu->Cell(3,3,html_entity_decode(""),1,0,"L",0,0);
        $formu->Cell(5,3,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(25,3,html_entity_decode("Union Libre"),"",0,"L",0,0);
        $formu->Cell(3,3,html_entity_decode(""),1,0,"L",0,0);
        $formu->Cell(4,3,html_entity_decode(""),"R",1,"L",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(80,2,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(25,6,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(25,6,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(25,6,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(25,6,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(25,6,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(55,2,html_entity_decode(""),"R",1,"C",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(27,3,html_entity_decode("Registro Civil o NUIP"),"L",0,"L",0,0);
        $formu->Cell(3,3,html_entity_decode(""),1,0,"L",0,0);
        $formu->Cell(5,3,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(27,3,html_entity_decode("Cedula Extranjera"),"",0,"L",0,0);
        $formu->Cell(3,3,html_entity_decode(""),1,0,"L",0,0);
        $formu->Cell(140,3,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(15,3,html_entity_decode("Casado"),"",0,"C",0,0);
        $formu->Cell(3,3,html_entity_decode(""),1,0,"L",0,0);
        $formu->Cell(5,3,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(25,3,html_entity_decode("Separado"),"",0,"L",0,0);
        $formu->Cell(3,3,html_entity_decode(""),1,0,"L",0,0);
        $formu->Cell(4,3,html_entity_decode(""),"R",1,"L",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(80,1,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(125,1,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(55,1,html_entity_decode(""),"BR",1,"L",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(50,6,html_entity_decode("Fecha Nacimiento"),"LBR",0,"C",0,0);
        $formu->Cell(30,6,html_entity_decode("Sexo"),"R",0,"C",0,0);
        $formu->Cell(25,6,html_entity_decode("Valor Pension Mensual"),"R",0,"C",0,0);
        $formu->Cell(25,6,html_entity_decode("Entidad Pagadora"),"R",0,"C",0,0);
        $formu->Cell(25,6,html_entity_decode("Nit"),"R",0,"C",0,0);
        $formu->Cell(25,6,html_entity_decode("Direcci&oacute;n"),"R",0,"C",0,0);
        $formu->Cell(50,6,html_entity_decode("No. Resoluci&oacute;n"),"R",0,"C",0,0);
        $formu->Cell(30,6,html_entity_decode("Fecha Resoluci&oacute;n"),"RB",1,"C",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(15,5,html_entity_decode("D"),"LR",0,"C",0,0);
        $formu->Cell(15,5,html_entity_decode("M"),"R",0,"C",0,0);
        $formu->Cell(20,5,html_entity_decode("A"),"R",0,"C",0,0);
        $formu->Cell(7,4,html_entity_decode("F"),0,0,"R",0,0);
        $formu->Cell(4,4,html_entity_decode(""),1,0,"C",0,0);
        $formu->Cell(9,4,html_entity_decode("M"),0,0,"R",0,0);
        $formu->Cell(4,4,html_entity_decode(""),1,0,"C",0,0);
        $formu->Cell(6,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(25,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(25,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(25,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(25,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(50,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode("D"),"R",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode("M"),"R",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode("A"),"R",1,"C",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(260,0,html_entity_decode(""),"B",1,"C",0,0);
        $formu->Cell(10,2,"","",1,"",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->SetFont('Arial','B','9.5');
        $formu->Cell(260,4.3,html_entity_decode("Datos del Grupo Familiar que va a Afiliar(Cuando un trabajador tenga m&aacute;s de un grupo faamiliar debera digilenciar un formato de afiliacion para cada grupo)"),1,1,"L",1,0);
        $formu->SetFont('Arial','','6');
        $formu->Cell(10,4.3,"","",0,"",0,0);
        $formu->Cell(40,4.3,html_entity_decode("Num. De Personas Grupo Familiar"),"LBR",0,"C",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(60,4.3,html_entity_decode("Direccion Residencia"),"BR",0,"C",0,0);
        $formu->Cell(40,4.3,html_entity_decode("Departamento"),"BR",0,"C",0,0);
        $formu->Cell(40,4.3,html_entity_decode("Ciudad"),"BR",0,"C",0,0);
        $formu->Cell(40,4.3,html_entity_decode("Barrio"),"BR",0,"C",0,0);
        $formu->Cell(40,4.3,html_entity_decode("Tel&eacute;fono"),"BR",1,"C",0,0);
        $formu->Cell(10,4.3,"","",0,"",0,0);
        $formu->Cell(40,4.3,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(60,4.3,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(40,4.3,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(40,4.3,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(40,4.3,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(40,4.3,html_entity_decode(""),"LBR",1,"C",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(10,2,"","",1,"",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->SetFont('Arial','B','9.5');
        $formu->Cell(260,5,html_entity_decode("Inscripcion del Grupo Familiar. Incluir C&oacute;yuge o compa&ntilde;ero(a) Permanente"),1,1,"L",1,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(60,5,html_entity_decode("Identificaci&oacute;n"),"LB",0,"C",0,0);
        $formu->Cell(25,25,html_entity_decode("Primer Apellido"),"LBR",0,"C",0,0);
        $formu->Cell(25,25,html_entity_decode("Segundo Apellido"),"BR",0,"C",0,0);
        $formu->Cell(25,25,html_entity_decode("Primer Nombre"),"BR",0,"C",0,0);
        $formu->Cell(25,25,html_entity_decode("Segundo Nombre"),"BR",0,"C",0,0);
        $formu->Cell(25,25,html_entity_decode("Fecha Nacimiento"),"BR",0,"C",0,0);
        $formu->Cell(10,25,html_entity_decode("Sexo"),"BR",0,"C",0,0);
        $formu->Cell(30,5,html_entity_decode("Parentesco"),"BR",0,"C",0,0);
        $formu->Cell(35,5,html_entity_decode("Condicion u Ocupacion"),"BR",1,"C",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(40,5,html_entity_decode("Tipo Documento"),"LBR",0,"C",0,0);
        $formu->Cell(20,5,html_entity_decode(""),"LR",0,"C",0,0);
        $formu->Cell(135,5,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(12,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(11,5,html_entity_decode("P.Prim"),"B",0,"C",0,0);
        $formu->Cell(12,5,html_entity_decode(""),"LR",1,"C",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(10,5,html_entity_decode("C.C"),"LR",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode("T.I"),"R",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode("R.C"),"R",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode("C.E"),"R",0,"C",0,0);
        $formu->Cell(20,5,html_entity_decode("N&uacute;mero"),"",0,"C",0,0);
        $formu->Cell(140,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(12,5,html_entity_decode("Estudia"),"R",0,"C",0,0);
        $formu->Cell(11,5,html_entity_decode("S.Seg"),"B",0,"C",0,0);
        $formu->SetFont('Arial','','5');
        $formu->Cell(12,5,html_entity_decode("Discapacidad"),"LR",1,"C",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"LR",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode("o"),"R",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(160,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(12,5,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(11,5,html_entity_decode("T.Tec"),"B",0,"C",0,0);
        $formu->Cell(12,5,html_entity_decode(""),"LR",1,"C",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode("NUIP"),"RB",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(160,5,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(6,5,html_entity_decode("SI"),"RB",0,"C",0,0);
        $formu->Cell(6,5,html_entity_decode("NO"),"RB",0,"C",0,0);
        $formu->Cell(11,5,html_entity_decode("U.Univ"),"BB",0,"C",0,0);
        $formu->Cell(12,5,html_entity_decode(""),"LBR",1,"C",0,0);
        $formu->Image('public/img/form/conyugue.jpg',216,105,3,10);
        $formu->Image('public/img/form/compe.jpg',221,100,3,17);
        $formu->Image('public/img/form/hijo.jpg',226,105,3,7);
        $formu->Image('public/img/form/hijastro.jpg',231,105,3,10);
        $formu->Image('public/img/form/padre.jpg',236,105,3,10);
        $formu->Image('public/img/form/hemhu.jpg',241,100,3,18);
	for ($i = 1; $i <= 5; $i++) {
        $formu->Cell(10,3,"","",0,"",0,0);
        $formu->Cell(10,3,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(10,3,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(10,3,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(10,3,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(20,3,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(25,3,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(25,3,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(25,3,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(25,3,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(8,3,html_entity_decode("D"),"BR",0,"C",0,0);
        $formu->Cell(8,3,html_entity_decode("M"),"BR",0,"C",0,0);
        $formu->Cell(9,3,html_entity_decode("A"),"BR",0,"C",0,0);
        $formu->Cell(5,3,html_entity_decode("F"),"BR",0,"C",0,0);
        $formu->Cell(5,3,html_entity_decode("M"),"BR",0,"C",0,0);
        $formu->Cell(5,3,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(5,3,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(5,3,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(5,3,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(5,3,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(5,3,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(6,3,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(6,3,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(11,3,html_entity_decode(""),"BB",0,"C",0,0);
        $formu->Cell(12,3,html_entity_decode(""),"LBR",1,"C",0,0);
	}
        $formu->Cell(10,2,"","",0,"",0,0);
        $formu->MultiCell(260,4,html_entity_decode("Autorizo al pagador de _________________________________ para que me sea deducido el 2% de mi mesada con destino a la Caja de Compensaci&oacute;n Comfamiliar, a fin de cubrir el aporte mensual de mi afiliaci&oacute;n, en cumplimiento del articulo 5 de la ley 71 de 1988 y articulo 22 y 34 del decreto 784 de 1989."),"LBR","L",0,0);
        $formu->Cell(10,2,"","",0,"",0,0);
        $formu->MultiCell(260,4,html_entity_decode("Declaro BAjo la Gravedad de juramento que: Toda la informaci&oacute;n aqui suminitrada es veridica. Autorizo a Comfamiliar para que por cualquier medio verifique los datos aqui contenidos y que en caso de falsedad se apliquen las sanciones contempladas. "),"LBR","L",0,0);
        $formu->Cell(10,1,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(100,1,html_entity_decode(""),"LR",0,"C",0,0);
        $formu->Cell(60,1,html_entity_decode(""),"LR",0,"C",0,0);
        $formu->Cell(50,1,html_entity_decode(""),"LR",0,"C",0,0);
        $formu->Cell(50,1,html_entity_decode(""),"LR",1,"C",0,0);
        $formu->Cell(10,3,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(100,3,html_entity_decode(""),"LR",0,"L",0,0);
        $formu->Cell(60,3,html_entity_decode("N&uacute;mero de radicado"),"LR",0,"L",0,0);
        $formu->Cell(50,3,html_entity_decode("Recibido por :"),"LR",0,"L",0,0);
        $formu->Cell(50,3,html_entity_decode("Grabado por :"),"LR",1,"L",0,0);
        $formu->Cell(10,3,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(100,3,html_entity_decode("Firma y C&eacute;dula del Pensionado:"),"LR",0,"L",0,0);
        $formu->Cell(60,3,html_entity_decode(""),"LR",0,"L",0,0);
        $formu->Cell(50,3,html_entity_decode(""),"LR",0,"L",0,0);
        $formu->Cell(50,3,html_entity_decode(""),"LR",1,"L",0,0);
        $formu->Cell(10,1,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(100,1,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(60,1,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(50,1,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(50,1,html_entity_decode(""),"LBR",1,"C",0,0);
        $formu->Cell(10,1,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(260,3,"-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------","LR",1,"L",0,0);
        $y=$formu->getY();
        $formu->Cell(10,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(53,5,html_entity_decode(""),0,0,"C",0,0);
        $formu->Image('public/img/comfamiliar-logo.jpg',22,$y,53,15);
        $formu->Cell(35,5,html_entity_decode("Nombre del Pensionado:"),0,0,"L",0,0);
        $formu->Cell(35,5,"","B",0,"L",0,0);
        $formu->Cell(2,5,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(20,5,html_entity_decode("Recibido por:"),0,0,"L",0,0);
        $formu->Cell(45,5,"","B",0,"L",0,0);
        $formu->Cell(2,5,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(20,5,html_entity_decode("Grabado por:"),0,0,"L",0,0);
        $formu->Cell(45,5,"","B",0,"L",0,0);
        $formu->Cell(3,5,html_entity_decode(""),"R",1,"C",0,0);

        $formu->Cell(10,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(53,5,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(55,5,html_entity_decode("N&uacute;mero de C&eacute;dula del Pensionado:"),0,0,"L",0,0);
        $formu->Cell(60,5,"","B",0,"L",0,0);
        $formu->Cell(2,5,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(30,5,html_entity_decode("Fecha de Recibido:"),0,0,"L",0,0);
        $formu->Cell(57,5,"","B",0,"L",0,0);
        $formu->Cell(3,5,html_entity_decode(""),"R",1,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(260,2,html_entity_decode(""),"LR",1,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(90,1,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(10,4,html_entity_decode(""),1,0,"C",0,0);
        $formu->Cell(50,4,html_entity_decode("Fotocopia C&eacute;dula del Pencionado"),0,0,"C",0,0);
        $formu->Cell(10,4,html_entity_decode(""),1,0,"C",0,0);
        $formu->Cell(50,4,html_entity_decode("Documento ilegible. Cual?"),0,0,"C",0,0);
        $formu->Cell(48,4,html_entity_decode(""),"B",0,"C",0,0);
        $formu->Cell(2,4,html_entity_decode(""),"R",1,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(260,1,html_entity_decode(""),"LR",1,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(90,1,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(10,4,html_entity_decode(""),1,0,"C",0,0);
        $formu->Cell(50,4,html_entity_decode("Fotocopia C&eacute;dula del C&oacute;nyuge"),0,0,"C",0,0);
        $formu->Cell(10,4,html_entity_decode(""),1,0,"C",0,0);
        $formu->Cell(50,4,html_entity_decode("Registro Civil. Cual?"),0,0,"C",0,0);
        $formu->Cell(48,4,html_entity_decode(""),"B",0,"C",0,0);
        $formu->Cell(2,4,html_entity_decode(""),"R",1,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(260,1,html_entity_decode(""),"LBR",1,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(260,3,html_entity_decode("Seleccione el tipo de afiliaci&oacute;n como Pencionado"),"LR",1,"C",0,0);
        $formu->Cell(10,2,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(1,2,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(3,2,html_entity_decode(""),1,0,"C",0,0);
        $formu->SetFont('Arial','','5.6');
        $formu->Cell(68,2,html_entity_decode("Pensionado con aportes del 2%. Afiliaci&oacute;n para todos los servicios de Caja"),0,0,"R",0,0);
        $formu->Cell(1,2,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(3,2,html_entity_decode(""),1,0,"C",0,0);
        $formu->Cell(86,2,html_entity_decode("Pensionado de 25 a&ntilde;os. Afiliaci&oacute;n para los servicios de recreacion, capacitaci&oacute;n y turismo social"),0,0,"R",0,0);
        $formu->Cell(1,2,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(3,2,html_entity_decode(""),1,0,"C",0,0);
        $formu->Cell(93,2,html_entity_decode("Pensionados r&eacute;gimen especial(1.5 SMLMV) Afiliaci&oacute;n para los servicios de recreacion, deporte y cultura"),0,0,"R",0,0);
        $formu->Cell(1,2,html_entity_decode(""),"R",1,"C",0,0);
        $formu->Cell(10,1,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(260,1,html_entity_decode(""),"LRB",1,"C",0,0);

        $formu->AddPage();
	$formu->setTextColor(0);
        $formu->SetFont('Arial','B','12');
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(260,5,html_entity_decode("DOCUMENTOS NECESARIOS PARA LA AFILIACI&Oacute;N"),1,1,"C",0,0);
        $formu->SetFont('Arial','B','10');
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("Importante"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("Hermano Hu&eacute;rfanos de padres"),"R",1,"L",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Diligenciar este formato con letra clara y utilizar tinta de color negro"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Fotocopia del folio del registro civil de nacimiento del trabajador"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  No escribir en los espacios sombreados, ni utilizar resaltador en las casillas"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Fotocopia del folio del registro civil de nacimiento de los hermanos huerfanos de padre"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Los documentos probatorios que se anexan, deben ser legibles,sin enmendaduras y sin resaltador"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Fotocopia de la partida de defunci&oacute;n o registro civil de defunci&oacute;n de los padres "),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Despu&eacute;s de diligenciar el formato, coloque  los documentos probatorios"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Constancia de dependencia economica y convivencia, a trav&eacute;s de la cual se manifiesta que los"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode(""),"BR",0,"L",0,0);
        $formu->Cell(1,3,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(129,3,html_entity_decode("   hermanos hu&eacute;rfanos  de padres conviven y dependen econ&oacute;micamente del trabajador y que"),"R",1,"L",0,0);
        $formu->Cell(10,3,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,8,html_entity_decode(""),"L",0,"C",0,0);
        $formu->SetFont('Arial','B','10');
        $formu->Cell(129,8,html_entity_decode("Documentos para el pensionado Unicamente"),"R",0,"L",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(1,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(129,8,html_entity_decode("   ninguna otra persona los tiene afiliado a Comfamiliar o a otra caja de compensaci&oacute;n familiar o"),"R",1,"L",0,0);
        $formu->Cell(10,8,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Fotocopia de  la Cedula de ciudadania o de extranjeria. Si este(a) es menor de edad,fotocopia"),"R",0,"L",0,0);
        $formu->Cell(1,1,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(129,1,html_entity_decode("   entidad similar, Podr&aacute; diligenciar el formato que Comfamiliar tiene establecido para tal fin "),"R",1,"L",0,0);
        $formu->Cell(10,1,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,1,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,1,html_entity_decode(""),"R",0,"L",0,0);
        $formu->Cell(1,2,html_entity_decode(""),"B",0,"C",0,0);
        $formu->Cell(129,2,html_entity_decode(""),"RB",1,"L",0,0);
        $formu->Cell(10,8,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,8,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,8,html_entity_decode("*  Fotocopia de la resoluci&oacute;n que reconoce la lacalidad de pensionado o certificado de la entidad "),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->SetFont('Arial','B','10');
        $formu->Cell(129,8,html_entity_decode("Padres"),"R",1,"L",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(10,1,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,1,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,1,html_entity_decode("   pagadora o la ultima colilla de pago"),"R",0,"L",0,0);
        $formu->Cell(1,1,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(129,1,html_entity_decode("*  Fotocopia del folio del registro civil de nacimineto  del trabajador"),"R",1,"L",0,0);
        $formu->Cell(10,2,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,8,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,2,html_entity_decode(""),"R",0,"L",0,0);
        $formu->Cell(1,8,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,8,html_entity_decode("*  Fotocopia del folio del registro civil de nacimineto  del trabajador donde consten sus padres"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->SetFont('Arial','B','10');
        $formu->Cell(129,5,html_entity_decode("Conyuge"),"TR",0,"L",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Constancia de dependencia econ&oacute;nomica y convivencia, a trav&eacute;s de la caul manifiesta que los"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("* Fotocopia del folio del registro civil de matrimonio o partida eclesiastica de matrimonio del trabajador"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   hermnaos hu&eacute;rfanos de padres conviven y dependen econ&oacute;nomicamente del trabajador y que"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("* Fotocopia de la c&eacute;dula de ciudadania. Si este(a) es menor de edad, fotocopia de la tarjeta de"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   ninguna otra persona los tiene afiliados a Comfamiliar o a otra caja de compensacion familiar"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("  identidad"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   o entidad similar. Podr&aacute; diligenciar el formato que Comfamiliar tiene establecido para tal fin"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("* Si el trabajador ya tiene registrada a su conyuge y esta afiliado a una nueva pareja, debera"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"BL",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode(""),"RB",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("  presentar constancia de divorcio"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->SetFont('Arial','B','10');
        $formu->Cell(129,5,html_entity_decode("Discapacidad"),"R",1,"L",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode(""),"RB",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Los padres, los hermanos hu&eacute;rfanos de padres y los hijos, que sean inv&aacute;lidos o de capacidad"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->SetFont('Arial','B','10');
        $formu->Cell(129,5,html_entity_decode("Compa&ntilde;era(o) Permanente"),"R",0,"L",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   fisica disminuida que le impida trabajar, sin limitaci&oacute;n en raz&oacute;n de su edad, para ello, el trabajador"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Fotocopia de la c&eacute;dula de ciudadania de la compa&ntilde;era permanente. si esta(a) es menor de edad"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   indenpendiente debe demostrar la convivencia y dependencia economica de la persona a cargo"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   fotocopia de la tarjeta de identidad"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   discapacitada, ademas presentar constancia  emitidas por la entidad Promotora de Salud-EPS de la"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Acreditaci&oacute;n como compa&ntilde;era(o) permanente. Podr&aacute; diligenciar el formateo que comfamiliar tiene"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   IPS de la red p&uacute;blica se salud (Decreto 1335 de 2008 y 4942 de 2009) o de la Junta Regional de"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   establecida para tal fin"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   Calificaci&oacute;n de Invalidez(Decreto 2463 del 20 de noviembre de 2001). Con el fin de acreditar la"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Si el trabajador ya tiene registrada a su conyuge o cpmpa&ntilde;era(o) y esta afiliando una nueva pareja, "),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   invalidez o disminuci&oacute;n fisica que le impida trabajar"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   deber&aacute; presentar la constancia de divorcio si es casado o carta personal firmada bajo gravedad"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  La dependencia econ&oacute;mica y convivencia de la persona a cargo discapacitada se podr&aacute; demostrar "),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   de juramenton en la cual manifiesta con quien esta conviviendo en forma permanente"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   diligenciando el formato que Comfamiliar tiene establecido para tal fin"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode(""),"RB",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode(""),"RB",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->SetFont('Arial','B','10');
        $formu->Cell(129,5,html_entity_decode("Hijos (Legitimos o Extramatrimoniales) e Hijastros"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(29,5,html_entity_decode("Observaciones"),"",0,"L",0,0);
        $formu->Cell(95,5,html_entity_decode(""),"B",0,"L",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",1,"L",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Fotocopia del folio del registro civil de nacimiento de los hijos o hijastros"),"R",0,"L",0,0);
        $formu->Cell(4,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(121,5,html_entity_decode(""),"B",0,"L",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Fotocopia de la tarjeta de identidad(mayores de 7 a&ntilde;os)"),"R",0,"L",0,0);
        $formu->Cell(4,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(121,5,html_entity_decode(""),"B",0,"L",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode(""),"BR",0,"L",0,0);
        $formu->Cell(4,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(121,5,html_entity_decode(""),"B",0,"L",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->SetFont('Arial','B','10');
        $formu->Cell(129,5,html_entity_decode("Para los Hijastros, deber&aacute; anexar adem&aacute;s"),"R",0,"L",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(4,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(121,5,html_entity_decode(""),"B",0,"L",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Constacia de dependencia econ&oacute;mica o convivencia, a trav&eacute;s de la cual de manifiesta que los"),"R",0,"L",0,0);
        $formu->Cell(4,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(121,5,html_entity_decode(""),"B",0,"L",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   hijastros conviven y dependes econ&oacute;nomicamente del trabajador. Podr&aacute; diligenciar el formato"),"R",0,"L",0,0);
        $formu->Cell(4,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(121,5,html_entity_decode(""),"B",0,"L",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",1,"L",0,0);
        $formu->Cell(10,3,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,3,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(129,3,html_entity_decode("   que comfamiliar tiene establecido para tal fin"),"RB",0,"L",0,0);
        $formu->Cell(4,3,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(121,3,html_entity_decode(""),"B",0,"L",0,0);
        $formu->Cell(5,3,html_entity_decode(""),"RB",1,"L",0,0);



        $this->setResponse('view');
        $file = "public/temp/reportes/empresa_penc_$nit.pdf";
        ob_clean();
        $formu->Output( $file,"F");
        return $file;
    }

    public function reporte_empresa_i(){
        $nit = $this->getPostParam("nit","addslaches","alpha","extraspaces","striptags");
        $formu = new FPDF('L','mm','A4');
        $formu->AddPage();
	    $formu->setTextColor(0);
        $formu->SetFont('Arial','','25');
        $this->setResponse('view');
        $formu->Image('public/img/comfamiliar-logo.jpg',20,10,38,10);
        $formu->Image('public/img/piePagCart/foot3.png',250,15,27,15);
        $formu->SetTextColor(0);    
        $formu->SetFont('Arial','B','12');
        $formu->Cell(10,5,"",0,0,"L",0,0);
        $formu->Cell(260,5,"","LTR",1,"C",0,0);
        $formu->Cell(10,5,"",0,0,"L",0,0);
        $formu->Cell(10,5,"","L",0,"L",0,0);
        $formu->Cell(250,5,html_entity_decode("Afilici&oacute;n del trabajador Independiente o Desempleado y su Grupo Familiar"),"R",1,"C",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(10,3,"",0,0,"L",0,0);
        $formu->Cell(30,3,"","L",0,"L",0,0);
        $formu->Cell(200,3,html_entity_decode("Importante: *Estos afiliados no ser&aacute;n beneficiados del subsidio en dinero"),"",0,"C",0,0);
        $formu->Cell(30,3,html_entity_decode(""),"R",1,"C",0,0);
        $formu->Cell(10,3,"",0,0,"L",0,0);
        $formu->Cell(30,3,"","L",0,"L",0,0);
        $formu->Cell(200,3,html_entity_decode("*Para el pago del 0.6% tiene derecho a recreaci&oacute;n, capacitaci&oacute;n y turismo social"),"",0,"C",0,0);
        $formu->Cell(30,3,html_entity_decode(""),"R",1,"C",0,0);
        $formu->Cell(10,3,"",0,0,"L",0,0);
        $formu->Cell(10,3,"","L",0,"L",0,0);
        $formu->SetFont('Arial','','6');
        $formu->Cell(20,3,"Porcentaje aporte",1,0,"C",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(200,3,html_entity_decode("*Por el pago del 2% de sus Ingresos mensuales o del equivalente a 2 smlv. Tiene derecho a los dem&aacute;s beneficios"),"",0,"C",0,0);
        $formu->Cell(30,3,html_entity_decode(""),"R",1,"C",0,0);
        $formu->Cell(10,3,"",0,0,"L",0,0);
        $formu->Cell(10,3,"","L",0,"L",0,0);
        $formu->SetFont('Arial','','6');
        $formu->Cell(10,3,"2 %",1,0,"C",0,0);
        $formu->Cell(10,3,"0.6 %",1,0,"C",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(200,3,html_entity_decode("excepci&oacute;n del subsidio en dinero"),"",0,"C",0,0);
        $formu->Cell(30,3,html_entity_decode(""),"R",1,"C",0,0);
        $formu->Cell(10,2,"","",0,"",0,0);
        $formu->Cell(260,2,"","LR",1,"",0,0);
        $formu->SetFont('Arial','','5.8');
	//Hasta aca va la cabecera
        $formu->SetFont('Arial','B','10');
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->SetFillColor(236,248,240);
        $formu->Cell(10,5,html_entity_decode(""),"LBT",0,"L",1,0);
        $formu->Cell(250,5,html_entity_decode("Datos de Afiliado"),"RBT",1,"L",1,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(80,5,html_entity_decode("Identificaci&oacute;n"),"LB",0,"C",0,0);
        $formu->Cell(25,10,html_entity_decode("N&uacute;mero"),"LRB",0,"C",0,0);
        $formu->Cell(25,10,html_entity_decode("Primer Apellido"),"RB",0,"C",0,0);
        $formu->Cell(25,10,html_entity_decode("Segundo Apellido"),"RB",0,"C",0,0);
        $formu->Cell(25,10,html_entity_decode("Primer Nombre"),"RB",0,"C",0,0);
        $formu->Cell(25,10,html_entity_decode("Segundo Nombre"),"RB",0,"C",0,0);
        $formu->Cell(55,5,html_entity_decode("Estado Civil"),"RB",1,"C",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->SetFont('Arial','','6.5');
        $formu->Cell(80,2,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(180,2,html_entity_decode(""),"R",1,"C",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(27,3,html_entity_decode("Cedula de Ciudadania"),"L",0,"L",0,0);
        $formu->Cell(3,3,html_entity_decode(""),1,0,"L",0,0);
        $formu->Cell(5,3,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(27,3,html_entity_decode("Tarjeta de Identidad"),"",0,"L",0,0);
        $formu->Cell(3,3,html_entity_decode(""),1,0,"L",0,0);
        $formu->Cell(140,3,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(15,3,html_entity_decode("Soltero"),"",0,"C",0,0);
        $formu->Cell(3,3,html_entity_decode(""),1,0,"L",0,0);
        $formu->Cell(5,3,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(25,3,html_entity_decode("Union Libre"),"",0,"L",0,0);
        $formu->Cell(3,3,html_entity_decode(""),1,0,"L",0,0);
        $formu->Cell(4,3,html_entity_decode(""),"R",1,"L",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(80,2,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(25,6,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(25,6,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(25,6,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(25,6,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(25,6,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(55,2,html_entity_decode(""),"R",1,"C",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(27,3,html_entity_decode("Registro Civil o NUIP"),"L",0,"L",0,0);
        $formu->Cell(3,3,html_entity_decode(""),1,0,"L",0,0);
        $formu->Cell(5,3,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(27,3,html_entity_decode("Cedula Extranjera"),"",0,"L",0,0);
        $formu->Cell(3,3,html_entity_decode(""),1,0,"L",0,0);
        $formu->Cell(140,3,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(15,3,html_entity_decode("Casado"),"",0,"C",0,0);
        $formu->Cell(3,3,html_entity_decode(""),1,0,"L",0,0);
        $formu->Cell(5,3,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(25,3,html_entity_decode("Separado"),"",0,"L",0,0);
        $formu->Cell(3,3,html_entity_decode(""),1,0,"L",0,0);
        $formu->Cell(4,3,html_entity_decode(""),"R",1,"L",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(80,1,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(125,1,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(55,1,html_entity_decode(""),"BR",1,"L",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(50,6,html_entity_decode("Fecha Nacimiento"),"LBR",0,"C",0,0);
        $formu->Cell(30,6,html_entity_decode("Sexo"),"R",0,"C",0,0);
        $formu->Cell(25,6,html_entity_decode("Nit EPS"),"R",0,"C",0,0);
        $formu->Cell(25,6,html_entity_decode("Nombre EPS"),"R",0,"C",0,0);
        $formu->SetFont('Arial','','5.8');
        $formu->Cell(25,6,html_entity_decode("Nit Fondo de Pensiones"),"R",0,"C",0,0);
        $formu->SetFont('Arial','','5.4');
        $formu->Cell(25,6,html_entity_decode("Nombre Fondo de Pensiones"),"R",0,"C",0,0);
        $formu->SetFont('Arial','','6.5');
        $formu->Cell(50,6,html_entity_decode("Valor Base se aporte EPS y Pensiones $"),"R",0,"C",0,0);
        $formu->Cell(30,6,html_entity_decode("Profesi&oacute;n(Ocupaci&oacute;n)"),"RB",1,"C",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(15,5,html_entity_decode("D"),"LR",0,"C",0,0);
        $formu->Cell(15,5,html_entity_decode("M"),"R",0,"C",0,0);
        $formu->Cell(20,5,html_entity_decode("A"),"R",0,"C",0,0);
        $formu->Cell(7,4,html_entity_decode("F"),0,0,"R",0,0);
        $formu->Cell(4,4,html_entity_decode(""),1,0,"C",0,0);
        $formu->Cell(9,4,html_entity_decode("M"),0,0,"R",0,0);
        $formu->Cell(4,4,html_entity_decode(""),1,0,"C",0,0);
        $formu->Cell(6,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(25,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(25,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(25,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(25,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(50,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(30,5,html_entity_decode(""),"R",1,"C",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(260,0,html_entity_decode(""),"B",1,"C",0,0);
        $formu->Cell(10,2,"","",1,"",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->SetFont('Arial','B','9.5');
        $formu->Cell(260,4.3,html_entity_decode("Datos del Grupo Familiar que va a Afiliar(Cuando un trabajador tenga m&aacute;s de un grupo faamiliar debera digilenciar un formato de afiliacion para cada grupo)"),1,1,"L",1,0);
        $formu->SetFont('Arial','','6');
        $formu->Cell(10,4.3,"","",0,"",0,0);
        $formu->Cell(40,4.3,html_entity_decode("Num. De Personas Grupo Familiar"),"LBR",0,"C",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(60,4.3,html_entity_decode("Direccion Residencia"),"BR",0,"C",0,0);
        $formu->Cell(40,4.3,html_entity_decode("Departamento"),"BR",0,"C",0,0);
        $formu->Cell(40,4.3,html_entity_decode("Ciudad"),"BR",0,"C",0,0);
        $formu->Cell(40,4.3,html_entity_decode("Barrio"),"BR",0,"C",0,0);
        $formu->Cell(40,4.3,html_entity_decode("Tel&eacute;fono"),"BR",1,"C",0,0);
        $formu->Cell(10,4.3,"","",0,"",0,0);
        $formu->Cell(40,4.3,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(60,4.3,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(40,4.3,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(40,4.3,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(40,4.3,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(40,4.3,html_entity_decode(""),"LBR",1,"C",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(10,2,"","",1,"",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->SetFont('Arial','B','9.5');
        $formu->Cell(260,5,html_entity_decode("Inscripcion del Grupo Familiar. Incluir C&oacute;yuge o compa&ntilde;ero(a) Permanente"),1,1,"L",1,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(60,5,html_entity_decode("Identificaci&oacute;n"),"LB",0,"C",0,0);
        $formu->Cell(25,25,html_entity_decode("Primer Apellido"),"LBR",0,"C",0,0);
        $formu->Cell(25,25,html_entity_decode("Segundo Apellido"),"BR",0,"C",0,0);
        $formu->Cell(25,25,html_entity_decode("Primer Nombre"),"BR",0,"C",0,0);
        $formu->Cell(25,25,html_entity_decode("Segundo Nombre"),"BR",0,"C",0,0);
        $formu->Cell(25,25,html_entity_decode("Fecha Nacimiento"),"BR",0,"C",0,0);
        $formu->Cell(10,25,html_entity_decode("Sexo"),"BR",0,"C",0,0);
        $formu->Cell(30,5,html_entity_decode("Parentesco"),"BR",0,"C",0,0);
        $formu->Cell(35,5,html_entity_decode("Condicion u Ocupacion"),"BR",1,"C",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(40,5,html_entity_decode("Tipo Documento"),"LBR",0,"C",0,0);
        $formu->Cell(20,5,html_entity_decode(""),"LR",0,"C",0,0);
        $formu->Cell(135,5,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(12,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(11,5,html_entity_decode("P.Prim"),"B",0,"C",0,0);
        $formu->Cell(12,5,html_entity_decode(""),"LR",1,"C",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(10,5,html_entity_decode("C.C"),"LR",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode("T.I"),"R",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode("R.C"),"R",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode("C.E"),"R",0,"C",0,0);
        $formu->Cell(20,5,html_entity_decode("N&uacute;mero"),"",0,"C",0,0);
        $formu->Cell(140,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(12,5,html_entity_decode("Estudia"),"R",0,"C",0,0);
        $formu->Cell(11,5,html_entity_decode("S.Seg"),"B",0,"C",0,0);
        $formu->SetFont('Arial','','5');
        $formu->Cell(12,5,html_entity_decode("Discapacidad"),"LR",1,"C",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"LR",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode("o"),"R",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(160,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(12,5,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(11,5,html_entity_decode("T.Tec"),"B",0,"C",0,0);
        $formu->Cell(12,5,html_entity_decode(""),"LR",1,"C",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode("NUIP"),"RB",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(160,5,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(6,5,html_entity_decode("SI"),"RB",0,"C",0,0);
        $formu->Cell(6,5,html_entity_decode("NO"),"RB",0,"C",0,0);
        $formu->Cell(11,5,html_entity_decode("U.Univ"),"BB",0,"C",0,0);
        $formu->Cell(12,5,html_entity_decode(""),"LBR",1,"C",0,0);
        $formu->Image('public/img/form/conyugue.jpg',216,100,3,10);
        $formu->Image('public/img/form/compe.jpg',221,95,3,17);
        $formu->Image('public/img/form/hijo.jpg',226,100,3,7);
        $formu->Image('public/img/form/hijastro.jpg',231,100,3,10);
        $formu->Image('public/img/form/padre.jpg',236,100,3,10);
        $formu->Image('public/img/form/hemhu.jpg',241,93,3,18);
	for ($i = 1; $i <= 6; $i++) {
        $formu->Cell(10,4,"","",0,"",0,0);
        $formu->Cell(10,4,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(10,4,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(10,4,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(10,4,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(20,4,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(25,4,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(25,4,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(25,4,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(25,4,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(8,4,html_entity_decode("D"),"BR",0,"C",0,0);
        $formu->Cell(8,4,html_entity_decode("M"),"BR",0,"C",0,0);
        $formu->Cell(9,4,html_entity_decode("A"),"BR",0,"C",0,0);
        $formu->Cell(5,4,html_entity_decode("F"),"BR",0,"C",0,0);
        $formu->Cell(5,4,html_entity_decode("M"),"BR",0,"C",0,0);
        $formu->Cell(5,4,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(5,4,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(5,4,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(5,4,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(5,4,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(5,4,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(6,4,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(6,4,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(11,4,html_entity_decode(""),"BB",0,"C",0,0);
        $formu->Cell(12,4,html_entity_decode(""),"LBR",1,"C",0,0);
	}
        $formu->Cell(10,2,"","",0,"",0,0);
        $formu->MultiCell(260,4,html_entity_decode("Declaro Bajo la Gravedad de juramento que: Toda la informaci&oacute;n aqui suminitrada es veridica. Autorizo a Comfamiliar para que por cualquier medio verifique los datos aqui contenidos y que en caso de falsedad se apliquen las sanciones contempladas. "),"LBR","L",0,0);
        $formu->Cell(10,1,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(100,1,html_entity_decode(""),"LR",0,"C",0,0);
        $formu->Cell(60,1,html_entity_decode(""),"LR",0,"C",0,0);
        $formu->Cell(50,1,html_entity_decode(""),"LR",0,"C",0,0);
        $formu->Cell(50,1,html_entity_decode(""),"LR",1,"C",0,0);
        $formu->Cell(10,3,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(100,3,html_entity_decode(""),"LR",0,"L",0,0);
        $formu->Cell(60,3,html_entity_decode("N&uacute;mero de radicado"),"LR",0,"L",0,0);
        $formu->Cell(50,3,html_entity_decode("Recibido por :"),"LR",0,"L",0,0);
        $formu->Cell(50,3,html_entity_decode("Grabado por :"),"LR",1,"L",0,0);
        $formu->Cell(10,3,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(100,3,html_entity_decode("Firma y C&eacute;dula del Afiliado Independiente:"),"LR",0,"L",0,0);
        $formu->Cell(60,3,html_entity_decode(""),"LR",0,"L",0,0);
        $formu->Cell(50,3,html_entity_decode(""),"LR",0,"L",0,0);
        $formu->Cell(50,3,html_entity_decode(""),"LR",1,"L",0,0);
        $formu->Cell(10,1,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(100,1,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(60,1,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(50,1,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(50,1,html_entity_decode(""),"LBR",1,"C",0,0);
        $formu->Cell(10,1,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(260,3,"-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------","LR",1,"L",0,0);
        $y=$formu->getY();
        $formu->Cell(10,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(53,5,html_entity_decode(""),0,0,"C",0,0);
        $formu->Image('public/img/comfamiliar-logo.jpg',22,$y,53,11);
        $formu->Cell(35,5,html_entity_decode("Nombre del Afiliado Independiente:"),0,0,"L",0,0);
        $formu->Cell(35,5,"","B",0,"L",0,0);
        $formu->Cell(2,5,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(20,5,html_entity_decode("Recibido por:"),0,0,"L",0,0);
        $formu->Cell(45,5,"","B",0,"L",0,0);
        $formu->Cell(2,5,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(20,5,html_entity_decode("Grabado por:"),0,0,"L",0,0);
        $formu->Cell(45,5,"","B",0,"L",0,0);
        $formu->Cell(3,5,html_entity_decode(""),"R",1,"C",0,0);

        $formu->Cell(10,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(53,5,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(55,5,html_entity_decode("N&uacute;mero de C&eacute;dula del Afiliado independiente:"),0,0,"L",0,0);
        $formu->Cell(60,5,"","B",0,"L",0,0);
        $formu->Cell(2,5,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(30,5,html_entity_decode("Fecha de Recibido:"),0,0,"L",0,0);
        $formu->Cell(57,5,"","B",0,"L",0,0);
        $formu->Cell(3,5,html_entity_decode(""),"R",1,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(260,2,html_entity_decode(""),"LR",1,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(20,4,html_entity_decode(""),0,0,"C",0,0);
        $formu->SetFont('Arial','','7');
        $formu->Cell(20,5,html_entity_decode("Porcentaje aporte"),1,0,"C",0,0);
        $formu->SetFont('Arial','B','8');
        $formu->Cell(50,4,html_entity_decode("CAUSALES DE"),0,0,"C",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(10,4,html_entity_decode(""),1,0,"C",0,0);
        $formu->Cell(50,4,html_entity_decode("Fotocopia C&eacute;dula del Pencionado"),0,0,"C",0,0);
        $formu->Cell(10,4,html_entity_decode(""),1,0,"C",0,0);
        $formu->Cell(50,4,html_entity_decode("Documento ilegible. Cual?"),0,0,"C",0,0);
        $formu->Cell(48,4,html_entity_decode(""),"B",0,"C",0,0);
        $formu->Cell(2,4,html_entity_decode(""),"R",1,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(260,1,html_entity_decode(""),"LR",1,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(20,4,html_entity_decode(""),0,0,"C",0,0);
        $formu->SetFont('Arial','','7');
        $formu->Cell(10,4,html_entity_decode("2 %"),"LRB",0,"C",0,0);
        $formu->Cell(10,4,html_entity_decode("0.6 %"),"RB",0,"C",0,0);
        $formu->SetFont('Arial','B','8');
        $formu->Cell(50,4,html_entity_decode("DEVOLUCI&Oacute;N"),0,0,"C",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(10,4,html_entity_decode(""),1,0,"C",0,0);
        $formu->Cell(50,4,html_entity_decode("Fotocopia C&eacute;dula del C&oacute;nyuge"),0,0,"C",0,0);
        $formu->Cell(10,4,html_entity_decode(""),1,0,"C",0,0);
        $formu->Cell(50,4,html_entity_decode("Registro Civil. Cual?"),0,0,"C",0,0);
        $formu->Cell(48,4,html_entity_decode(""),"B",0,"C",0,0);
        $formu->Cell(2,4,html_entity_decode(""),"R",1,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(260,1,html_entity_decode(""),"LBR",1,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),0,0,"C",0,0);
        $formu->SetFont('Arial','B','10');
        $formu->Cell(260,8,html_entity_decode("Afiliaci&oacute;n del trabajador Independiente o Desempleado y su Grupo Familiar"),"LRB",1,"C",0,0);

        $formu->AddPage();
	$formu->setTextColor(0);
        $formu->SetFont('Arial','B','12');
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(260,5,html_entity_decode("DOCUMENTOS NECESARIOS PARA LA AFILIACI&Oacute;N"),1,1,"C",0,0);
        $formu->SetFont('Arial','B','10');
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("Importante"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("Hermano Hu&eacute;rfanos de padres"),"R",1,"L",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Diligenciar este formato con letra clara y utilizar tinta de color negro"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Fotocopia del folio del registro civil de nacimiento del trabajador"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  No escribir en los espacios sombreados, ni utilizar resaltador en las casillas"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Fotocopia del folio del registro civil de nacimiento de los hermanos huerfanos de padre"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Los documentos probatorios que se anexan, deben ser legibles,sin enmendaduras y sin resaltador"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Fotocopia de la partida de defunci&oacute;n o registro civil de defunci&oacute;n de los padres "),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Despu&eacute;s de diligenciar el formato, coloque  los documentos probatorios"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Constancia de dependencia economica y convivencia, a trav&eacute;s de la cual se manifiesta que los"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode(""),"BR",0,"L",0,0);
        $formu->Cell(1,3,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(129,3,html_entity_decode("   hermanos hu&eacute;rfanos  de padres conviven y dependen econ&oacute;micamente del trabajador y que"),"R",1,"L",0,0);
        $formu->Cell(10,3,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,8,html_entity_decode(""),"L",0,"C",0,0);
        $formu->SetFont('Arial','B','10');
        $formu->Cell(129,8,html_entity_decode("Documentos para el pensionado Unicamente"),"R",0,"L",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(1,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(129,8,html_entity_decode("   ninguna otra persona los tiene afiliado a Comfamiliar o a otra caja de compensaci&oacute;n familiar o"),"R",1,"L",0,0);
        $formu->Cell(10,8,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Fotocopia de  la Cedula de ciudadania o de extranjeria. Si este(a) es menor de edad,fotocopia"),"R",0,"L",0,0);
        $formu->Cell(1,1,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(129,1,html_entity_decode("   entidad similar, Podr&aacute; diligenciar el formato que Comfamiliar tiene establecido para tal fin "),"R",1,"L",0,0);
        $formu->Cell(10,1,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,1,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,1,html_entity_decode(""),"R",0,"L",0,0);
        $formu->Cell(1,2,html_entity_decode(""),"B",0,"C",0,0);
        $formu->Cell(129,2,html_entity_decode(""),"RB",1,"L",0,0);
        $formu->Cell(10,8,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,8,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,8,html_entity_decode("*  Fotocopia de la resoluci&oacute;n que reconoce la lacalidad de pensionado o certificado de la entidad "),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->SetFont('Arial','B','10');
        $formu->Cell(129,8,html_entity_decode("Padres"),"R",1,"L",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(10,1,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,1,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,1,html_entity_decode("   pagadora o la ultima colilla de pago"),"R",0,"L",0,0);
        $formu->Cell(1,1,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(129,1,html_entity_decode("*  Fotocopia del folio del registro civil de nacimineto  del trabajador"),"R",1,"L",0,0);
        $formu->Cell(10,2,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,8,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,2,html_entity_decode(""),"R",0,"L",0,0);
        $formu->Cell(1,8,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,8,html_entity_decode("*  Fotocopia del folio del registro civil de nacimineto  del trabajador donde consten sus padres"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->SetFont('Arial','B','10');
        $formu->Cell(129,5,html_entity_decode("Conyuge"),"TR",0,"L",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Constancia de dependencia econ&oacute;nomica y convivencia, a trav&eacute;s de la caul manifiesta que los"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("* Fotocopia del folio del registro civil de matrimonio o partida eclesiastica de matrimonio del trabajador"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   hermnaos hu&eacute;rfanos de padres conviven y dependen econ&oacute;nomicamente del trabajador y que"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("* Fotocopia de la c&eacute;dula de ciudadania. Si este(a) es menor de edad, fotocopia de la tarjeta de"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   ninguna otra persona los tiene afiliados a Comfamiliar o a otra caja de compensacion familiar"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("  identidad"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   o entidad similar. Podr&aacute; diligenciar el formato que Comfamiliar tiene establecido para tal fin"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("* Si el trabajador ya tiene registrada a su conyuge y esta afiliado a una nueva pareja, debera"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"BL",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode(""),"RB",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("  presentar constancia de divorcio"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->SetFont('Arial','B','10');
        $formu->Cell(129,5,html_entity_decode("Discapacidad"),"R",1,"L",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode(""),"RB",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Los padres, los hermanos hu&eacute;rfanos de padres y los hijos, que sean inv&aacute;lidos o de capacidad"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->SetFont('Arial','B','10');
        $formu->Cell(129,5,html_entity_decode("Compa&ntilde;era(o) Permanente"),"R",0,"L",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   fisica disminuida que le impida trabajar, sin limitaci&oacute;n en raz&oacute;n de su edad, para ello, el trabajador"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Fotocopia de la c&eacute;dula de ciudadania de la compa&ntilde;era permanente. si esta(a) es menor de edad"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   indenpendiente debe demostrar la convivencia y dependencia economica de la persona a cargo"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   fotocopia de la tarjeta de identidad"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   discapacitada, ademas presentar constancia  emitidas por la entidad Promotora de Salud-EPS de la"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Acreditaci&oacute;n como compa&ntilde;era(o) permanente. Podr&aacute; diligenciar el formateo que comfamiliar tiene"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   IPS de la red p&uacute;blica se salud (Decreto 1335 de 2008 y 4942 de 2009) o de la Junta Regional de"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   establecida para tal fin"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   Calificaci&oacute;n de Invalidez(Decreto 2463 del 20 de noviembre de 2001). Con el fin de acreditar la"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Si el trabajador ya tiene registrada a su conyuge o cpmpa&ntilde;era(o) y esta afiliando una nueva pareja, "),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   invalidez o disminuci&oacute;n fisica que le impida trabajar"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   deber&aacute; presentar la constancia de divorcio si es casado o carta personal firmada bajo gravedad"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  La dependencia econ&oacute;mica y convivencia de la persona a cargo discapacitada se podr&aacute; demostrar "),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   de juramenton en la cual manifiesta con quien esta conviviendo en forma permanente"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   diligenciando el formato que Comfamiliar tiene establecido para tal fin"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode(""),"RB",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode(""),"RB",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->SetFont('Arial','B','10');
        $formu->Cell(129,5,html_entity_decode("Hijos (Legitimos o Extramatrimoniales) e Hijastros"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(29,5,html_entity_decode("Observaciones"),"",0,"L",0,0);
        $formu->Cell(95,5,html_entity_decode(""),"B",0,"L",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",1,"L",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Fotocopia del folio del registro civil de nacimiento de los hijos o hijastros"),"R",0,"L",0,0);
        $formu->Cell(4,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(121,5,html_entity_decode(""),"B",0,"L",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Fotocopia de la tarjeta de identidad(mayores de 7 a&ntilde;os)"),"R",0,"L",0,0);
        $formu->Cell(4,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(121,5,html_entity_decode(""),"B",0,"L",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode(""),"BR",0,"L",0,0);
        $formu->Cell(4,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(121,5,html_entity_decode(""),"B",0,"L",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->SetFont('Arial','B','10');
        $formu->Cell(129,5,html_entity_decode("Para los Hijastros, deber&aacute; anexar adem&aacute;s"),"R",0,"L",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(4,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(121,5,html_entity_decode(""),"B",0,"L",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Constacia de dependencia econ&oacute;mica o convivencia, a trav&eacute;s de la cual de manifiesta que los"),"R",0,"L",0,0);
        $formu->Cell(4,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(121,5,html_entity_decode(""),"B",0,"L",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   hijastros conviven y dependes econ&oacute;nomicamente del trabajador. Podr&aacute; diligenciar el formato"),"R",0,"L",0,0);
        $formu->Cell(4,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(121,5,html_entity_decode(""),"B",0,"L",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",1,"L",0,0);
        $formu->Cell(10,3,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,3,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(129,3,html_entity_decode("   que comfamiliar tiene establecido para tal fin"),"RB",0,"L",0,0);
        $formu->Cell(4,3,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(121,3,html_entity_decode(""),"B",0,"L",0,0);
        $formu->Cell(5,3,html_entity_decode(""),"RB",1,"L",0,0);



        $this->setResponse('view');
        $file = "public/temp/reportes/empresa_indep_$nit.pdf";
        ob_clean();
        $formu->Output( $file,"F");
        return $file;
    }

/*        
// Formulario de afiliacion de Empresas
    public function addempresa_reportAction(){
        $this->setResponse("ajax");
        $response = parent::startFunc();
        $nit = $this->getPostParam("nit","addslaches","alpha","extraspaces","striptags");
        $razsoc = $this->getPostParam("razsoc","addslaches","alpha","extraspaces","striptags");
        $digver = $this->getPostParam("digver","addslaches","alpha","extraspaces","striptags");
        $coddoc = $this->getPostParam("coddoc","addslaches","alpha","extraspaces","striptags");
        $codciu = $this->getPostParam("codciu","addslaches","alpha","extraspaces","striptags");
        $direccion = $this->getPostParam("direccion","addslaches","alpha","extraspaces","striptags");
        $barrio = $this->getPostParam("barrio","addslaches","alpha","extraspaces","striptags");
        $telefono = $this->getPostParam("telefono","addslaches","alpha","extraspaces","striptags");
        $celular = $this->getPostParam("celular","addslaches","alpha","extraspaces","striptags");
        $fax = $this->getPostParam("fax","addslaches","alpha","extraspaces","striptags");
        $email = $this->getPostParam("email","addslaches","extraspaces","striptags");
        $pagweb = $this->getPostParam("pagweb","addslaches","extraspaces","striptags");
        $feccon = $this->getPostParam("feccon","addslaches","alpha","extraspaces","striptags");
        $codact = $this->getPostParam("codact","addslaches","alpha","extraspaces","striptags");
        $tottra = $this->getPostParam("tottra","addslaches","alpha","extraspaces","striptags");
        $totnom = $this->getPostParam("totnom","addslaches","alpha","extraspaces","striptags");
        $cedrep = $this->getPostParam("cedrep","addslaches","alpha","extraspaces","striptags");
        $nomrep = $this->getPostParam("nomrep","addslaches","alpha","extraspaces","striptags");
        $codare = $this->getPostParam("codare","addslaches","alpha","extraspaces","striptags");
        $codope = $this->getPostParam("codope","addslaches","alpha","extraspaces","striptags");
        $tipemp = $this->getPostParam("tipemp","addslaches","alpha","extraspaces","striptags");
        $tipsoc = $this->getPostParam("tipsoc","addslaches","alpha","extraspaces","striptags");
        $calemp = $this->getPostParam("calemp","addslaches","alpha","extraspaces","striptags");
        $codzon = $this->getPostParam("codzon","addslaches","alpha","extraspaces","striptags");
        $autoriza = $this->getPostParam("autoriza","addslaches","alpha","extraspaces","striptags");
        $zonsuc = $this->getPostParam("codcius","addslaches","alpha","extraspaces","striptags");
        $dirsuc = $this->getPostParam("direccions","addslaches","alpha","extraspaces","striptags");
        $emailsuc = $this->getPostParam("emails","addslaches","alpha","extraspaces","striptags");
        $telsuc = $this->getPostParam("telefonos","addslaches","alpha","extraspaces","striptags");
        $faxsuc = $this->getPostParam("faxs","addslaches","alpha","extraspaces","striptags");
        $nomsub = $this->getPostParam("nomsub","addslaches","alpha","extraspaces","striptags");
        $telsub = $this->getPostParam("telsub","addslaches","alpha","extraspaces","striptags");
        $celsub = $this->getPostParam("celsub","addslaches","alpha","extraspaces","striptags");
        $causalario = $this->getPostParam("causalario","addslaches","alpha","extraspaces","striptags");
        $segsoc = $this->getPostParam("segsoc","addslaches","alpha","extraspaces","striptags");
        $ultmes = $this->getPostParam("mesnomcau","addslaches","alpha","extraspaces","striptags");
        $valor = $this->getPostParam("valnomcau","addslaches","alpha","extraspaces","striptags");
        $cajavie = $this->getPostParam("cajavie","addslaches","alpha","extraspaces","striptags");
        $direnvio = $this->getPostParam("direnvio","addslaches","alpha","extraspaces","striptags");
        $barrenvio = $this->getPostParam("barrenvio","addslaches","alpha","extraspaces","striptags");
        $munienvio = $this->getPostParam("munienvio","addslaches","alpha","extraspaces","striptags");
        //$feccon = $this->getPostParam("feccon","addslaches","alpha","extraspaces","striptags");
        $feccon = "2015-10-05";
        $anocon = substr($feccon,0,4);
        $mescon = substr($feccon,5,-3);
        $diacon = substr($feccon,8,10);    
        $fecha =  new Date();
        $ultmesnom = $fecha->getMonthName($ultmes);
        $ultmes = $ultmesnom;
        $valor = number_format($valor,0,".",".");
        $formu = new FPDF('P','mm',array(230,350));    
        $formu->AddPage();
        $formu->SetTextColor(44,72,151);    
        $formu->SetDrawColor(44,72,151);    
        $formu->SetFont('Arial','B','8');
        $formu->Image('public/img/comfamiliar-logo.jpg',8,12,67,20);
        $formu->Image('public/img/piePagCart/foot3.png',174,13,35,20);
        $formu->Cell(210,3,"","LTR",1,"L",0,0);
        $formu->Cell(60,3,"","L",0,"L",0,0);
        $formu->Cell(9,3,"",0,0,"L",0,0);
        $formu->Cell(70,3,"",0,0,"C",0,0);
        $formu->Cell(69,3,"",0,0,"R",0,0);
        $formu->Cell(2,3,"","R",1,"R",0,0);
        $formu->Cell(210,8,"","LR",1,"L",0,0);
        $formu->Cell(90,8,"","L",0,"L",0,0);
        $formu->Cell(9,8,"",0,0,"L",0,0);
        $formu->SetFont('Arial','B','15');
        $formu->Cell(62,8,html_entity_decode("AFILIACI&Oacute;N EMPLEADOR"),0,0,"C",0,0);
        $formu->Cell(49,8,"","R",1,"R",0,0);
        $formu->Cell(210,10,"","LR",1,"L",0,0);
        $formu->SetFont('Arial','B','9');
        $formu->Cell(159,5,html_entity_decode("Antes de diligenciar, lea cuidadosamente las instrucciones detalladas al respaldo"),"L",0,"C",0,0);
        $formu->Cell(2,5,"",0,0,"C",0,0);
        $formu->Cell(45,5,html_entity_decode("FECHA DE RECIBO"),1,0,"C",0,0);
        $formu->Cell(4,5,"","R",1,"C",0,0);
        $formu->Cell(159,5,html_entity_decode("Favor diligenciar a m&aacute;quina o en letra clara y legible. Utilice tinta color negro."),"L",0,"C",0,0);
        $formu->Cell(2,5,"",0,0,"C",0,0);
        $formu->Cell(17,5,html_entity_decode("A&Ntilde;O"),1,0,"C",0,0);
        $formu->Cell(14,5,html_entity_decode("MES"),1,0,"C",0,0);
        $formu->Cell(14,5,html_entity_decode("D&Iacute;A"),1,0,"C",0,0);
        $formu->Cell(4,5,"","R",1,"C",0,0);
        $formu->Cell(159,5,html_entity_decode("Favor no escribir en los espacios sombreados."),"L",0,"C",0,0);
        $formu->Cell(2,5,"",0,0,"C",0,0);
        $formu->SetFillColor(192,216,255);
        $formu->Cell(17,5,$fecha->getYear(),1,0,"C",true,0);
        $formu->Cell(14,5,$fecha->getMonth(),1,0,"C",true,0);
        $formu->Cell(14,5,$fecha->getDay(),1,0,"C",true,0);
        $formu->Cell(4,5,"","R",1,"C",0,0);
        $formu->Cell(210,3,"","LR",1,"L",0,0);
        $formu->Cell(210,6,html_entity_decode(" INFORMACI&Oacute;N GENERAL DEL EMPLEADOR"),1,1,"L",true,0);
        $formu->SetFont('Arial','','9');
        $formu->Cell(210,5,html_entity_decode(" NOMBRE O RAZ&Oacute;N SOCIAL"),"LR",1,"L",0,0);
        $formu->Cell(210,5,$razsoc,"LRB",1,"L",0,0);
        $formu->Cell(210,1,"","LR",1,"L",0,0);
        $formu->Cell(168,5,html_entity_decode(" NIT O C&Eacute;DULA"),"L",0,"L",0,0);
        $formu->SetFont('Arial','','7');
        $formu->Cell(40,5,html_entity_decode("FECHA DE CONSTITUCI&Oacute;N"),"LRT",0,"C",0,0);
        $formu->Cell(2,5,"","R",1,"L",0,0);
        $formu->SetFont('Arial','','9');
        $formu->Cell(50,3,"","L",0,"C",0,0);
        $a = "";
        $b = "";
        $c = "";
        $d = "";
        switch ($tipsoc){
            case 1:
                $a = "X";
                break;
            case 2:
                $b = "X";
                break;
            case 3:
                $c = "X";
                break;
            case 4:
                $d = "X";
                break;
        }
        $formu->Cell(28,3,html_entity_decode("PERSONA"),0,0,"R",0,0);
        $formu->Cell(6,3,"","LTR",0,"L",0,0);
        $formu->Cell(18,3,html_entity_decode("JURIDICA"),0,0,"C",0,0);
        $formu->Cell(6,3,"","LTR",0,"L",0,0);
        $formu->Cell(18,3,html_entity_decode("SECTOR"),0,0,"L",0,0);
        $formu->Cell(6,3,"","LTR",0,"L",0,0);
        $formu->Cell(18,3,html_entity_decode("PRIVADO"),0,0,"C",0,0);
        $formu->Cell(6,3,"","LTR",0,"L",0,0);
        $formu->Cell(12,3,"",0,0,"L",0,0);
        $formu->SetFont('Arial','','7');
        $formu->Cell(40,3,html_entity_decode("(Persona Jur&iacute;dica)"),"LRB",0,"C",0,0);
        $formu->Cell(2,3,"","R",1,"L",0,0);
        $formu->Cell(55,3,$nit,"LB",0,"L",0,0);
        $formu->SetFont('Arial','','9');
        $formu->Cell(23,3,html_entity_decode("NATURAL"),0,0,"R",0,0);
        $formu->Cell(6,3,$a,"LBR",0,"C",0,0);
        $formu->Cell(18,3,"",0,0,"C",0,0);
        $formu->Cell(6,3,$b,"LBR",0,"C",0,0);
        $formu->Cell(18,3,html_entity_decode("P&Uacute;BLICO"),0,0,"L",0,0);
        $formu->Cell(6,3,$c,"LBR",0,"C",0,0);
        $formu->Cell(18,3,"",0,0,"C",0,0);
        $formu->Cell(6,3,$d,"LBR",0,"C",0,0);
        $formu->Cell(12,3,"",0,0,"L",0,0);
        $formu->SetFont('Arial','','7');
        $formu->Cell(16,3,html_entity_decode("A&Ntilde;O"),"LRB",0,"C",0,0);
        $formu->Cell(12,3,html_entity_decode("MES"),"LRB",0,"C",0,0);
        $formu->Cell(12,3,html_entity_decode("D&Iacute;A"),"LRB",0,"C",0,0);
        $formu->SetFont('Arial','','9');
        $formu->Cell(2,3,"","R",1,"L",0,0);
        $formu->Cell(168,4,"OBJETO PRINCIPAL DEL NEGOCIO","L",0,"L",0,0);
        $formu->Cell(16,4,$anocon,"LBR",0,"C",0,0);
        $formu->Cell(12,4,$mescon,"LBR",0,"C",0,0);
        $formu->Cell(12,4,$diacon,"LBR",0,"C",0,0);
        $formu->Cell(2,4,"","R",1,"L",0,0);
        $formu->Cell(210,5,"","LRB",1,"L",0,0);
        $formu->Cell(210,3,"","LR",1,"L",0,0);
        $formu->Cell(158,4,html_entity_decode("ACTIVIDAD ECON&Oacute;MICA"),"L",0,"L",0,0);
        $formu->Cell(10,5,"CIIU",0,0,"L",0,0);
        $codact = "0081";
        $actividades = parent::webService('actividades',array());
        foreach($actividades as $mcodact){
            if($mcodact['codact']==$codact){
                $detact = substr($mcodact['detalle'],0,90);
            }
        } 
        $formu->Cell(40,5,html_entity_decode($codact),1,0,"C",true,0);
        $formu->Cell(2,4,"","R",1,"L",0,0);
        $formu->Cell(210,4,$detact,"LRB",1,"L",0,0);
        $formu->Cell(210,3,"","LR",1,"L",0,0);
        $si = "";
        $no = "";
        if($calemp=="D"){
            $si="X";
        }else{
            $no="X";
        }
        $formu->Cell(116,5,html_entity_decode("ES EMPLEADOR DE PERSONAS DE SERVICIO DOM&Eacute;STICO"),"L",0,"L",0,0);
        $formu->Cell(7,5,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(6,5,html_entity_decode("SI"),0,0,"R",0,0);
        $formu->Cell(6,5,$si,1,0,"C",0,0);
        $formu->Cell(7,5,"",0,0,"L",0,0);
        $formu->Cell(6,5,html_entity_decode("NO"),0,0,"R",0,0);
        $formu->Cell(6,5,$no,1,0,"C",0,0);
        $formu->Cell(4,5,"",0,0,"L",0,0);
        $formu->Cell(10,5,html_entity_decode("CIIU"),0,0,"L",0,0);
        $formu->Cell(40,5,html_entity_decode("9700"),1,0,"L",true,0);
        $formu->Cell(2,5,html_entity_decode(""),"R",1,"L",0,0);
        $formu->Cell(210,3,"","LR",1,"L",0,0);
        $formu->Cell(210,6,html_entity_decode(" INFORMACI&Oacute;N ESPEC&Iacute;FICA DEL EMPLEADOR"),1,1,"L",true,0);
        $formu->Cell(98,6,html_entity_decode(" DIRECCI&Oacute;N DEL ESTABLECIMIENTO O NEGOCIO"),"L",0,"L",0,0);
        $formu->Cell(10,6,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(47,6,html_entity_decode("BARRIO"),0,0,"L",0,0);
        $formu->Cell(11,6,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(44,6,html_entity_decode("MUNICIPIO"),"R",1,"L",0,0);
        $formu->Cell(100,4,utf8_encode($direccion),"LB",0,"L",0,0);
        $formu->Cell(8,4,"",0,0,"L",0,0);
        $formu->Cell(47,4,utf8_encode($barrio),"B",0,"L",0,0);
        $detciures = "";
        $ciudades = parent::webService('ciudades',array());
        foreach($ciudades as $mcodciu){
            if($mcodciu['codciu']==$codciu){
                $detciures = $mcodciu['detalle'];
            }
        }
        $formu->Cell(11,4,"",0,0,"L",0,0);
        $formu->Cell(44,4,$detciures,"BR",1,"L",0,0);
        $formu->Cell(50,6,html_entity_decode("TEL&Eacute;FONO FIJO"),"L",0,"L",0,0);
        $formu->Cell(5,6,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(54,6,html_entity_decode("TEL&Eacute;FONO CELULAR"),0,0,"L",0,0);
        $formu->Cell(5,6,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(42,6,html_entity_decode("FAX"),0,0,"L",0,0);
        $formu->Cell(5,6,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(24,6,html_entity_decode("AA"),0,0,"L",0,0);
        $formu->Cell(25,6,html_entity_decode("DE"),"R",1,"L",0,0);
        $formu->Cell(50,4,$telefono,"LB",0,"L",0,0);
        $formu->Cell(5,4,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(54,4,$celular,"B",0,"L",0,0);
        $formu->Cell(5,4,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(42,4,$fax,"B",0,"L",0,0);
        $formu->Cell(5,4,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(49,4,html_entity_decode(""),"BR",1,"L",0,0);
        $formu->Cell(90,6,html_entity_decode(" DIRECCI&Oacute;N ENV&Iacute;O DE CORRESPONDENCIA"),"L",0,"L",0,0);
        $formu->Cell(6,6,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(57,6,html_entity_decode("BARRIO"),0,0,"L",0,0);
        $formu->Cell(11,6,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(46,6,html_entity_decode("MUNICIPIO"),"R",1,"L",0,0);


        $formu->Cell(80,4,utf8_encode($direnvio),"LB",0,"L",0,0);
        $formu->Cell(6,4,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(47,4,utf8_encode($barrenvio),"B",0,"L",0,0);
        $formu->Cell(11,4,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(66,4,$munienvio,"BR",1,"C",0,0);


        $formu->Cell(155,5,html_entity_decode(" EMAIL"),"L",0,"L",0,0);
        $formu->Cell(55,5,html_entity_decode(""),"R",1,"L",0,0);
        $formu->Cell(145,4,$email,"LB",0,"L",0,0);
        $formu->Cell(65,4,html_entity_decode(""),"R",1,"L",0,0);
        $formu->Cell(155,5,html_entity_decode(" NOMBRE DEL REPRESENTANTE LEGAL"),"L",0,"L",0,0);
        $formu->Cell(11,5,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(44,5,html_entity_decode("C.C."),"R",1,"L",0,0);
        $formu->Cell(133,4,utf8_encode($nomrep),"LB",0,"L",0,0);
        $formu->Cell(31,4,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(46,4,$cedrep,"BR",1,"L",0,0);
        $formu->Cell(115,5,html_entity_decode(" NOMBRE CONTACTO ADMINISTRATIVO"),"L",0,"L",0,0);
        $formu->Cell(18,5,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(77,5,html_entity_decode("CARGO"),"R",1,"L",0,0);
        $formu->Cell(115,4,utf8_encode($nomsub),"LB",0,"L",0,0);
        $formu->Cell(18,4,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(77,4,"Coordinador(a) de Subsidio","BR",1,"L",0,0);
        $formu->Cell(60,5,html_entity_decode(" TEL&Eacute;FONO FIJO"),"L",0,"L",0,0);
        $formu->Cell(60,5,html_entity_decode("TEL&Eacute;FONO CELULAR"),0,0,"L",0,0);
        $formu->Cell(90,5,html_entity_decode("LUGAR DONDE SE CAUSAN LOS SALARIOS"),"R",1,"L",0,0);
        $formu->Cell(50,4,$telsub,"LB",0,"L",0,0);
        $formu->Cell(11,4,"",0,0,"L",0,0);
        $formu->Cell(50,4,$celsub,"B",0,"L",0,0);
        $formu->Cell(10,4,"","",0,"L",0,0);
        $formu->Cell(89,4,$causalario,"BR",1,"L",0,0);
        $formu->Cell(210,5,html_entity_decode(" ENTIDAD DE SEGURIDAD SOCIAL A LA QUE SE ENCUENTRAN AFILIADOS LOS TRABAJADORES"),"LR",1,"L",0,0);
        $formu->Cell(210,4,utf8_encode($segsoc),"LBR",1,"L",0,0);
        $formu->Cell(210,2,"","LBR",1,"L",0,0);
        $formu->Cell(210,6,html_entity_decode(" OTROS DATOS DEL EMPLEADOR"),1,1,"L",true,0);
        $formu->Cell(120,5,html_entity_decode(" &Uacute;LTIMA N&Oacute;MINA CAUSADA"),"L",0,"L",0,0);
        $formu->Cell(90,5,html_entity_decode("N&Uacute;MERO TOTAL DE TRABAJADORES"),"R",1,"L",0,0);
        $formu->Cell(15,5,html_entity_decode(" MES"),"L",0,"L",0,0);
        $formu->Cell(40,5,$ultmes,"B",0,"L",0,0);
        $formu->Cell(15,5,"VALOR",0,0,"L",0,0);
        $formu->Cell(39,5,"$ ".$valor,"B",0,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(91,5,$tottra,"BR",1,"L",0,0);
        $formu->Cell(210,6,html_entity_decode(" CAJA DE COMPENSACI&Oacute;N A LA CUAL ESTA O ESTUVO AFILIADO ANTERIORMENTE"),"LR",1,"L",0,0);
        $formu->Cell(210,4,$cajavie,"LBR",1,"L",0,0);
        $formu->Cell(98,4,html_entity_decode(""),"L",0,"L",0,0);
        $formu->SetFont('Arial','','6');
        $formu->Cell(112,4,html_entity_decode("EL EMPLEADOR QUE SUMINISTRE DATOS FALSOS SER&Aacute; SANCIONADO DE"),"R",1,"C",0,0);
        $formu->SetFont('Arial','','9');
        $formu->Cell(98,4,html_entity_decode(" FIRMA Y SELLO DEL EMPLEADOR"),"L",0,"L",0,0);
        $formu->SetFont('Arial','','6');
        $formu->Cell(112,4,html_entity_decode("ACUERDO CON EL ART&IACUTE;CULO 45 DE LA LEY DE 1982."),"R",1,"C",0,0);
        $formu->SetFont('Arial','','7');
        $formu->Cell(98,4,html_entity_decode(""),"L",0,"L",0,0);
        $formu->SetFont('Arial','','6');
        $formu->Cell(112,4,html_entity_decode("EN CASO DE SER ACEPTADOS COMO AFILIADOS NOS COMPROMETEMOS A"),"R",1,"C",0,0);
        $formu->SetFont('Arial','','7');
        $formu->Cell(98,4,html_entity_decode(""),"LB",0,"L",0,0);
        $formu->SetFont('Arial','','6');
        $formu->Cell(112,4,html_entity_decode("CUMPLIR Y RESPETAR LA LEGISLACI&Oacute;N DEL SUBSIDIO FAMILIAR, AL IGUAL"),"R",1,"C",0,0);
        $formu->Cell(98,4,html_entity_decode(""),"L",0,"L",0,0);
        $formu->SetFont('Arial','','6');
        $formu->Cell(112,4,html_entity_decode("QUE LOS ESTATUTOS Y REGLAMENTOS DE COMFAMILIAR"),"R",1,"C",0,0);
        $formu->SetFont('Arial','','9');
        $formu->Cell(100,5,html_entity_decode(" RECIBIDO POR"),"L",0,"L",0,0);
        $formu->Cell(8,5,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(102,5,html_entity_decode("GRABADO POR"),"R",1,"L",0,0);
        $formu->Cell(90,5,html_entity_decode(""),"LB",0,"L",0,0);
        $formu->Cell(20,5,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(100,5,html_entity_decode(""),"BR",1,"L",0,0);
        $formu->Cell(210,5,html_entity_decode(" OBSERVACIONES"),"LR",1,"L",0,0);
        $formu->Cell(210,5,html_entity_decode(""),"LBR",1,"L",0,0);
        $formu->Cell(70,5,html_entity_decode(""),"L",0,"L",0,0);
        $formu->Cell(25,5,html_entity_decode("NOMBRE Y NIT"),0,0,"L",0,0);
        $formu->Cell(115,5,utf8_encode($razsoc." - ".$nit),"BR",1,"L",0,0);
        $formu->Cell(70,5,html_entity_decode(""),"L",0,"L",0,0);
        $formu->Cell(25,5,html_entity_decode("Recibido por:"),0,0,"L",0,0);
        $formu->Cell(42,5,html_entity_decode(""),"B",0,"L",0,0);
        $formu->Cell(32,5,html_entity_decode("N&uacute;mero de Radicado:"),0,0,"L",0,0);
        $formu->Cell(41,5,html_entity_decode(""),"BR",1,"L",0,0);
        $formu->Cell(70,5,html_entity_decode(""),"L",0,"L",0,0);
        $formu->Cell(30,5,html_entity_decode("Fecha de Recibido:"),0,0,"L",0,0);
        $formu->Cell(110,5,$fecha,"BR",1,"L",0,0);
        $formu->Cell(70,5,html_entity_decode(""),"L",0,"L",0,0);
        $formu->Cell(140,5,html_entity_decode("OBSERVACIONES"),"R",1,"L",0,0);
        $formu->Cell(70,5,html_entity_decode("AFILIACI&Oacute;N EMPLEADOR"),"L",0,"C",0,0);
        $formu->Cell(140,5,html_entity_decode(""),"BR",1,"L",0,0);
        $formu->Cell(70,5,html_entity_decode(""),"L",0,"L",0,0);
        $formu->Cell(140,5,html_entity_decode(""),"BR",1,"L",0,0);
        $formu->Cell(70,2,html_entity_decode(""),"LB",0,"L",0,0);
        $formu->Cell(140,2,html_entity_decode(""),"TBR",1,"L",0,0);
        $file = "public/temp/reportes/afiliaEmpleador_".$nit.".pdf";
        ob_clean();
        $formu->Output($file,"F");
        $response = parent::successFunc("Genera Formulario",$file);
        return $this->renderText(json_encode($response)); 
    }
*/









// Afiliacion de Empresas a la mercurio30
    public function addempresaAction(){
        try{
            try{
                $mercurio30 = $this->Mercurio30->find("estado = 'P' AND nit='".SESSION::getDATA('documento')."'");
                if(count($mercurio30) > 0){
                    return $this->redirect("principal/index/Ya tiene una solicitud pendiente");
                }
                $modelos = array("mercurio30","mercurio21","mercurio37");
                $Transaccion = parent::startTrans($modelos);
                $nit = $this->getPostParam("nit","addslaches","alpha","extraspaces","striptags");
                $razsoc = $this->getPostParam("razsoc","addslaches","alpha","extraspaces","striptags");
                $sigla = $this->getPostParam("sigla","addslaches","alpha","extraspaces","striptags");
                $digver = $this->getPostParam("digver","addslaches","alpha","extraspaces","striptags");
                $coddoc = $this->getPostParam("coddoc","addslaches","alpha","extraspaces","striptags");
                $codciu = $this->getPostParam("codciu","addslaches","alpha","extraspaces","striptags");
                $barrio = $this->getPostParam("barrio","addslaches","alpha","extraspaces","striptags");
                $ciucor = $this->getPostParam("codciuen","addslaches","alpha","extraspaces","striptags");
                $barcor = $this->getPostParam("barrioen","addslaches","alpha","extraspaces","striptags");
                $direccion = $this->getPostParam("direccion","addslaches","alpha","extraspaces","striptags");
                $telefono = $this->getPostParam("telefono","addslaches","alpha","extraspaces","striptags");
                $celular = $this->getPostParam("telcel","addslaches","alpha","extraspaces","striptags");
                $fax = $this->getPostParam("fax","addslaches","alpha","extraspaces","striptags");
                $email = $this->getPostParam("email","addslaches","extraspaces","striptags");
                $pagweb = $this->getPostParam("pagweb","addslaches","extraspaces","striptags");
                $feccon = $this->getPostParam("feccon","addslaches","alpha","extraspaces","striptags");
                $codact = $this->getPostParam("codact","addslaches","alpha","extraspaces","striptags");
                $tottra = $this->getPostParam("tottra","addslaches","alpha","extraspaces","striptags");
                $totnom = $this->getPostParam("valnomcau","addslaches","alpha","extraspaces","striptags");
                $cedrep = $this->getPostParam("cedrep");
                $coddocr = $this->getPostParam("coddocr","addslaches","alpha","extraspaces","striptags");
                $nomrep = $this->getPostParam("nomrep","addslaches","alpha","extraspaces","striptags");
                $prinom = $this->getPostParam("prinom","addslaches","alpha","extraspaces","striptags");
                $segnom = $this->getPostParam("segnom","addslaches","alpha","extraspaces","striptags");
                $priape = $this->getPostParam("priape","addslaches","alpha","extraspaces","striptags");
                $segape = $this->getPostParam("segape","addslaches","alpha","extraspaces","striptags");
                $sexo = $this->getPostParam("sexo","addslaches","alpha","extraspaces","striptags");
                $fecnac = $this->getPostParam("fecnac","addslaches","alpha","extraspaces","striptags");
                $codare = $this->getPostParam("codare","addslaches","alpha","extraspaces","striptags");
                $codope = $this->getPostParam("codope","addslaches","alpha","extraspaces","striptags");
                //$tipemp = $this->getPostParam("tipemp","addslaches","alpha","extraspaces","striptags");
                $tipsoc = $this->getPostParam("tipsoc","addslaches","alpha","extraspaces","striptags");
                $calemp = $this->getPostParam("calemp","addslaches","alpha","extraspaces","striptags");
                $codzon = $this->getPostParam("codzon","addslaches","alpha","extraspaces","striptags");
                $autoriza = $this->getPostParam("autoriza","addslaches","alpha","extraspaces","striptags");
                $zonsuc = $this->getPostParam("codcius","addslaches","alpha","extraspaces","striptags");
                $dirsuc = $this->getPostParam("direnvio","addslaches","alpha","extraspaces","striptags");
                $emailsuc = $this->getPostParam("emails","addslaches","alpha","extraspaces","striptags");
                $docadm = $this->getPostParam("docadm","addslaches","alpha","extraspaces","striptags");
                $cedadm = $this->getPostParam("cedadm");
                $telsuc = $this->getPostParam("telsub","addslaches","alpha","extraspaces","striptags");
                $celsub = $this->getPostParam("celsub","addslaches","alpha","extraspaces","striptags");
                $faxsuc = $this->getPostParam("faxs","addslaches","alpha","extraspaces","striptags");
                $nomsub = $this->getPostParam("nomsub","addslaches","alpha","extraspaces","striptags");
                $prinoma = $this->getPostParam("prinoma","addslaches","alpha","extraspaces","striptags");
                $segnoma = $this->getPostParam("segnoma","addslaches","alpha","extraspaces","striptags");
                $priapea = $this->getPostParam("priapea","addslaches","alpha","extraspaces","striptags");
                $segapea = $this->getPostParam("segapea","addslaches","alpha","extraspaces","striptags");
                $sexoa = $this->getPostParam("sexoa","addslaches","alpha","extraspaces","striptags");
                $fecnaca = $this->getPostParam("fecnaca","addslaches","alpha","extraspaces","striptags");
                $agencia = $this->getPostParam("agencia","addslaches","alpha","extraspaces","striptags");
                $objemp = $this->getPostParam("objemp","addslaches","alpha","extraspaces","striptags");
                $agencia = $this->Mercurio07->findFirst("agencia","conditions: documento = '$nit'");
                $agencia = $agencia->getAgencia();
                Session::setData('nota_audit',"Ingreso de Empresa");
                $log_id = parent::registroOpcion();
                $mercurio30 = new Mercurio30();
                $mercurio30->setTransaction($Transaccion);
                $mercurio30->setId(0);
                $mercurio30->setLog($log_id);
                $mercurio30->setCodcaj(Session::getDATA('codcaj'));
                $mercurio30->setNit($nit);
                $mercurio30->setRazsoc($razsoc);
                $mercurio30->setSigla($sigla);
                $mercurio30->setDigver($digver);
                $mercurio30->setCoddoc($coddoc);
                $mercurio30->setCodciu($codciu);
                $mercurio30->setBarrio($barrio);
                $mercurio30->setCiucor($ciucor);
                $mercurio30->setBarcor($barcor);
                $mercurio30->setDireccion($direccion);
                $mercurio30->setTelefono($telefono);
                $mercurio30->setFax($fax);
                $mercurio30->setEmail($email);
                $mercurio30->setPagweb($pagweb);
                $mercurio30->setFeccon($feccon);
                $mercurio30->setCodact($codact);
                $mercurio30->setObjemp($objemp);
                $mercurio30->setTottra($tottra);
                $mercurio30->setTotnom($totnom);
                $mercurio30->setCedrep($cedrep);
                $mercurio30->setDocrep($coddocr);
                $mercurio30->setNomrep($nomrep);
                //$mercurio30->setTipemp($tipemp);
                $mercurio30->setTipsoc($tipsoc);
                $mercurio30->setCalemp($calemp);
                $mercurio30->setCodzon($codzon);
                //$mercurio30->setAutoriza($autoriza);
                $mercurio30->setEstado("P");
                $mercurio30->setCelular($celular);
                $mercurio30->setZonsuc($zonsuc);
                $mercurio30->setDirsuc($dirsuc);
                $mercurio30->setEmailsuc($emailsuc);
                $mercurio30->setTelsuc($telsuc);
                $mercurio30->setFaxsuc($faxsuc);
                $mercurio30->setNomsub($nomsub);
                $mercurio30->setAgencia($agencia);
                $mercurio30->setCedadm($cedadm);
                $mercurio30->setDocadm($docadm);
                $mercurio30->setUsuario(parent::asignarFuncionario($codare,$codope));
                //$mercurio30->setUsuario(1);
                if(!$mercurio30->save()){
                    parent::setLogger($mercurio30->getMessages());
                    parent::ErrorTrans();
                }
                $formafi = $this->getPostParam("formafi");
                if($formafi != ''){
                    $mercurio37 = new Mercurio37();
                    $mercurio37->setTransaction($Transaccion);
                    $mercurio37->setNumero($mercurio30->getId());
                    $mercurio37->setCoddoc('1');
                    $mercurio37->setNomarc($formafi);                         
                    if(!$mercurio37->save()){
                        parent::setLogger($mercurio37->getMessages());          
                        parent::ErrorTrans();
                    }
                }
                $mercurio02 = $this->Mercurio02->findFirst("codcaj = '".SESSION::getDATA('codcaj')."'");
                $asunto = "Nuevo Empresa por Aprobar $razsoc";
                $msg = "Cordial Saludos<br><br>Esta pendiente por aprobar la empresa $nit - $razsoc con $tottra trabajadores con una nomina de ".number_format($totnom,0,".",".")."<br><br>Atentamente,<br><br>MERCURIO";
                $ruta_file = "";
                $mercurio13 = $this->Mercurio13->find("codcaj = '".SESSION::getDATA('codcaj')."' AND codare = '$codare' AND codope='$codope'");
                foreach($mercurio13 as $mmercurio13){
                    if(isset($_FILES['archivo_'.$mmercurio13->getCoddoc()])){
                        $_FILES["archivo_{$mmercurio13->getCoddoc()}"]['name'] = $mercurio30->getId()."_".$mmercurio13->getCoddoc()."_".$nit.substr($_FILES["archivo_{$mmercurio13->getCoddoc()}"]['name'],-4);
                        $path = "public/files/";
                        $this->uploadFile("archivo_{$mmercurio13->getCoddoc()}",getcwd()."/$path/");
                        $dd=pathinfo($_FILES["archivo_{$mmercurio13->getCoddoc()}"]["name"]);
                        $archivo_nombre = $path.$dd['basename'];
                        $ruta_file[] = $archivo_nombre;
                        $mercurio37 = new Mercurio37();
                        $mercurio37->setTransaction($Transaccion);
                        $mercurio37->setNumero($mercurio30->getId());
                        $mercurio37->setCoddoc($mmercurio13->getCoddoc());
                        $mercurio37->setNomarc($archivo_nombre);
                        if(!$mercurio37->save()){
                            parent::setLogger($mercurio37->getMessages());
                            parent::ErrorTrans();
                        }
                    }
                }
                $formafi = $this->getPostParam("formafi");
                if($formafi != ''){
                        $mercurio37 = new Mercurio37();
                        $mercurio37->setTransaction($Transaccion);
                        $mercurio37->setNumero($mercurio30->getId());
                        $mercurio37->setCoddoc("1");
                        $mercurio37->setNomarc($formafi);
                    if(!$mercurio37->save()){
                        parent::setLogger($mercurio37->getMessages());          
                        parent::ErrorTrans();
                    }
                }

                /*
                    if(isset($_FILES['formafi'])){
                        $_FILES["formafi"]['name'] = $mercurio30->getId()."_1_".$nit.substr($_FILES["formafi"]['name'],-4);
                        $path = "public/files/";
                        $this->uploadFile("formafi",getcwd()."/$path/");
                        $dd=pathinfo($_FILES["formafi"]["name"]);
                        $archivo_nombre = $path.$dd['basename'];
                        $ruta_file[] = $archivo_nombre;
                        $mercurio37 = new Mercurio37();
                        $mercurio37->setTransaction($Transaccion);
                        $mercurio37->setNumero($mercurio30->getId());
                        $mercurio37->setCoddoc("1");
                        $mercurio37->setNomarc($archivo_nombre);
                        if(!$mercurio37->save()){
                            parent::setLogger($mercurio37->getMessages());
                            parent::ErrorTrans();
                        }
                    }
                    */
                $mercurio46 = new Mercurio46();
                $mercurio46->setTransaction($Transaccion);
                $mercurio46->setLog($log_id);
                $mercurio46->setCoddoc($coddocr);
                $mercurio46->setDocumento($cedrep);
                $mercurio46->setPriape($priape);
                $mercurio46->setSegape($segape);
                $mercurio46->setPrinom($prinom);
                $mercurio46->setSegnom($segnom);
                $mercurio46->setSexo($sexo);
                $mercurio46->setFecnac($fecnac);
                $mercurio46->setTipper('idrepresentante');
                if($cedrep == $cedadm){
                    $telsub = $this->getPostParam("telsub");
                    $celsub = $this->getPostParam("celsub");
                    $mercurio46->setCelular($celsub);
                    $mercurio46->setTelefono($telsub);
                }
                if(!$mercurio46->save()){
                    parent::setLogger($mercurio46->getMessages());
                    parent::ErrorTrans();
                }
                if($cedrep != $cedadm){
                    $mercurio46 = new Mercurio46();
                    $mercurio46->setTransaction($Transaccion);
                    $mercurio46->setLog($log_id);
                    $mercurio46->setCoddoc($docadm);
                    $mercurio46->setDocumento($cedadm);
                    $mercurio46->setPriape($priapea);
                    $mercurio46->setSegape($segapea);
                    $mercurio46->setPrinom($prinoma);
                    $mercurio46->setSegnom($segnoma);
                    $mercurio46->setSexo($sexoa);
                    $mercurio46->setFecnac($fecnaca);
                    $mercurio46->setTipper('idjefepersonal');
                    $telsub = $this->getPostParam("telsub");
                    $celsub = $this->getPostParam("celsub");
                    $mercurio46->setCelular($celsub);
                    $mercurio46->setTelefono($telsub);
                    if(!$mercurio46->save()){
                        parent::setLogger($mercurio46->getMessages());
                        parent::ErrorTrans();
                    }
                }
                parent::finishTrans();
                $flag_email = parent::enviarCorreo("Afiliacion Empresa desde Mercurio",$razsoc, "mercurio@syseu.com", $asunto, $msg, $ruta_file);
                if($flag_email==false){
                    return $this->redirect("principal/index/Se envio el Formulario pero sin correo Electrónico");
                }
                $asuntoemp = "Solicitud de Afiliacion Caja";
                $msgemp ="Gracias por utilizar el Servicio en Linea de Comfamiliar Huila, usted acaba de realizar el proceso de Afiliacion de Empresas:";
                $msgemp .= "<br><br><b>RAZON SOCIAL: </b>".$razsoc;
                $msgemp .= "<br><b>NIT: </b>".Session::getData('documento');
                $msgemp .= "<br><br>Esta informacion sera verificada por uno de nuestros funcionarios, a su correo de contacto '$email' le comunicaremos si su solicitud ha sido aprobada o rechazada.";
                $flag_email = parent::enviarCorreo("Solicitud de Afiliación a Caja",$razsoc,$email,$asuntoemp,$msgemp,"");
                if($flag_email==false){
                    return $this->redirect("principal/index/Se envio el Formulario pero Sin Correo Electrónico");
                }
                //Fin nuevo formato
                return $this->redirect("principal/index/Se Registro Exitosamente");
            } catch (DbException $e) {
                parent::setLogger($e->getMessage());
                parent::ErrorTrans();
            }
        } catch (TransactionFailed $e) {
            return $this->redirect("principal/index/No se pudo registrar");
        }
    }

// Vista de formulario de afiliacion
    public function addtrabajador_viewAction(){
        $this->setResponse("ajax");
        $codare = $this->getPostParam("codare");
        $codope = $this->getPostParam("codope");
        echo parent::showTitle('Afiliarse como Trabajador');
        Tag::displayTo("nit", SESSION::getDATA('documento'));
        $documento = Session::getDATA("documento");
        $result = parent::webService("Autenticar",array('tipo' => 'E', 'documento' => $documento, 'coddoc'=>Session::getData('coddoc')));
        Tag::displayTo("razsoc",$result[0]['nombre']);
        $this->setParamToView("coddoc", array('2'=>'TARJETA DE IDENTIDAD','1'=>'CEDULA DE CIUDADANIA','4'=>'CEDULA DE EXTRANJERIA','3'=>'PASAPORTE'));
        $this->setParamToView("estciv", array('50'=>'SOLTERO(A)','51'=>'CASADO(A)','54'=>'SEPARADO','52'=>'UNION LIBRE'));
        $this->setParamToView("autoriza", array('S'=>'SI','N'=>'NO'));
        $this->setParamToView("cabhog", array('S'=>'SI','N'=>'NO'));
        $this->setParamToView("codare", $codare);
        $this->setParamToView("codope", $codope);
    }

// formulario afiliacion de trabajadores
    public function formuAfiTrabAction(){
        $this->setResponse("ajax");
        $nit = session::getDATA('documento');
        $empresa = parent::webService("autenticar",array('tipo' => 'E', 'documento' => $nit, 'coddoc'=>Session::getData('coddoc')));
        $nit = SESSION::getDATA('documento');
        $razsoc = SESSION::getDATA('nombre');
        $direccion = $empresa[0]['direccion']; 
        $telefonoEmp = $empresa[0]['telefono']; 
        $codciu = parent::webService('ciudades',array());
        foreach($codciu as $mcodciu){
            if($mcodciu['codciu'] == $empresa[0]['codciu']) {
                $ciudadEmp = $mcodciu['detalle'];
            }
        }
        //$telefonoEmp = SESSION::getDATA('telefono');
        $cedtra = $this->getPostParam('cedtra');
        $coddoc = $this->getPostParam('coddoc');
        $prinom = $this->getPostParam('prinom');
        $segnom = $this->getPostParam('segnom');
        $priape = $this->getPostParam('priape');
        $segape = $this->getPostParam('segape');
        $estciv = $this->getPostParam('estciv');
        $madcom = $this->getPostParam('madcom');
        $fecnac = new Date($this->getPostParam('fecnac'));
        $dianac = $fecnac->getDay();
        $mesnac = $fecnac->getMonth();
        $anonac = $fecnac->getYear();
        $depana = $this->getPostParam('departn');
        $depart = parent::webService('departamento',array());
        $departn = '';
        foreach($depart as $mdepart){
            if($mdepart['coddep'] ==$depana){
                $departn = $mdepart['detalle'];
            }
        }
        $ciunac = '';
        $ciuna = $this->getPostParam('ciunac');
        $codciu = parent::webService('ciudades',array());
        foreach($codciu as $mcodciu){
            if($mcodciu['codciu'] == $ciuna){
                $ciunac = $mcodciu['detalle'];
            }
        }
        $sexo = $this->getPostParam('sexo');
        $tipcon = $this->getPostParam('tipcon');
        $salario = $this->getPostParam('salario');
        $horas = $this->getPostParam('horas');
        $fecing = new Date($this->getPostParam('fecing'));
        $diaing = $fecing->getDay();
        $mesing = $fecing->getMonth();
        $anoing = $fecing->getYear();
        $dirres = substr($this->getPostParam('direccion'), 0, 42);
        $depre = $this->getPostParam('depart');
        $depart = parent::webService('departamento',array());
        $depres='';
        foreach($depart as $mdepart){
            if($mdepart['coddep'] ==$depre){
                $depres = $mdepart['detalle'];
            }
        }

        $ciure = $this->getPostParam('codciu');
        $codciu = parent::webService('ciudades',array());
        $ciures='';
        foreach($codciu as $mcodciu){
            if($mcodciu['codciu'] == $ciure){
                $ciures = $mcodciu['detalle'];
            }
        }
        $barr = $this->getPostParam('barrio');
        $barri = parent::webService('Barrios',array());
        $barrio='';
        foreach($barri as $mbarri){
            if($mbarri['idbarrio']==$barr){
                $barrio = $mbarri['detalle'];
            }
        }
        $vivienda = $this->getPostParam('vivienda');
        $telefono = $this->getPostParam('telefono');
        $email = $this->getPostParam('email');
        $email1 = substr($email,0,29);
        $email2 = substr($email,29,29);
        $cabhog = $this->getPostParam('cabhog');
        $rural = $this->getPostParam('rural');
        $profe = $this->getPostParam('profesion');
        $profesion='';
        $prof = parent::webService('Profeciones',array());
        foreach($prof as $mprof){
            if($mprof['idprofecion'] == $profe){
                $profesion = $mprof['detalle'];

            }
        }
        $cargo = $this->getPostParam('cargo');
        $carg = parent::webService('Cargos',array());
        $cargos='';
        foreach($carg as $mcarg){
            if($mcarg['idcargos'] == $cargo){
                $cargos = $mcarg['detalle'];
            }
        }
        $response = parent::startFunc();
        $formu = new FPDF('L','mm','A4');
        $formu->AddPage();
        $formu->Image('public/img/comfamiliar-logo.jpg',4,4,67,20);
        $formu->Image('public/img/piePagCart/foot3.png',270,9,27,15);
        $formu->SetTextColor(0);    
        $formu->SetFont('Arial','B','10');
        $formu->Cell(60,5,"",0,0,"L",0,0);
        $formu->Cell(15,5,"X",1,0,"C",0,0);
        $formu->Cell(43,5,"  NUEVA",0,0,"L",0,0);
        $formu->Cell(70,5,html_entity_decode("AFILIACI&Oacute;N DEL TRABAJADOR Y SU GRUPO FAMILIAR"),0,1,"L",0,0);
        $formu->Cell(58,1,"",0,1,"L",0,0);
        $formu->Cell(60,5,"",0,0,"L",0,0);
        $formu->Cell(15,5,"",1,0,"L",0,0);
        $formu->SetFont('Arial','B','10');
        $formu->Cell(41,5,html_entity_decode("  ACTUALIZACI&Oacute;N"),0,0,"L",0,0);
        $formu->SetFont('Arial','B','6.7');
        $formu->MultiCell(100,3,html_entity_decode("importante: *Diligenciar este formato con letra clara y utilizar tinta de color negro \n *No escribir en los espacios sombreados, ni utulizar resaltador en las casillas.\n *Adjuntar los documentoscomprobatorios legibles, sin enmendeduras."),"","C",0);
        $formu->SetFont('Arial','B','9.5');
        $formu->SetFillColor(236,248,240);
        $formu->Cell(283,4,"Datos del Empleador:",1,1,"L",1,0);
        $formu->SetFont('Arial','B','8');
        $formu->Cell(43,3,html_entity_decode("NIT &oacute; Empleador:"),"LTR",0,"L",0,0);
        $formu->Cell(85,3,html_entity_decode("Nombre &oacute; Razon Social del Empleador:"),"LTR",0,"L",0,0);
        $formu->Cell(75,3,"Direccion:","LTR",0,"L",0,0);
        $formu->Cell(40,3,"Ciudad:","LTR",0,"L",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(40,3,html_entity_decode("Tel&eacute;fono:"),"LTR",1,"L",0,0);
        $formu->Cell(43,3,$nit,"LBR",0,"C",0,0);
        $formu->Cell(85,3,html_entity_decode($razsoc),"LBR",0,"C",0,0);
        $formu->SetFont('Arial','','7.5');
        $formu->Cell(75,3,$direccion,"LBR",0,"C",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(40,3,$ciudadEmp,"LBR",0,"C",0,0);
        $formu->Cell(40,3,$telefonoEmp,"LBR",1,"L",0,0);
        $formu->SetFont('Arial','B','9.5');
        $formu->SetFillColor(236,248,240);
        $formu->Cell(283,4,"Datos del Trabajador:",1,1,"L",1,0);
        $formu->SetFont('Arial','B','8');
        $formu->Cell(60,3,"","LTR",0,"C",0,0);
        $formu->Cell(30,3,"","LTR",0,"C",0,0);
        $formu->Cell(37,3,"","LTR",0,"C",0,0);
        $formu->Cell(37,3,"","LTR",0,"C",0,0);
        $formu->Cell(37,3,"","LTR",0,"C",0,0);
        $formu->Cell(37,3,"","LTR",0,"C",0,0);
        $formu->Cell(45,3,"","LTR",1,"C",0,0);
        $formu->Cell(60,3,html_entity_decode("Tipo de identificaci&oacute;n:"),"LBR",0,"C",0,0);
        $formu->Cell(30,3,html_entity_decode("N&uacute;mero:"),"LBR",0,"C",0,0);
        $formu->Cell(37,3,"Primer Apellido:","LBR",0,"C",0,0);
        $formu->Cell(37,3,"Segundo Apellido:","LBR",0,"C",0,0);
        $formu->Cell(37,3,"Primer Nombre:","LBR",0,"C",0,0);
        $formu->Cell(37,3,"Segundo Nombre:","LBR",0,"C",0,0);
        $formu->Cell(45,3,"Estado Civil:","LBR",1,"C",0,0);
        $formu->Cell(60,1,"","LR",0,"C",0,0);
        $formu->Cell(30,1,"","LR",0,"C",0,0);
        $formu->Cell(37,1,"","LR",0,"C",0,0);
        $formu->Cell(37,1,"","LR",0,"C",0,0);
        $formu->Cell(37,1,"","LR",0,"C",0,0);
        $formu->Cell(37,1,"","LR",0,"C",0,0);
        $formu->Cell(45,1,"","LR",1,"C",0,0);
        $formu->SetFont('Arial','','7.5');
        $a = "";
        $b = "";
        $c = "";
        $d = "";
        switch($coddoc){
            case 1:
                $a= "X";
                break;
            case 2:
                $b= "X";
                break;
            case 6:
                $c= "X";
                break;
            case 4:
                $d= "X";
                break;
        }
        $formu->Cell(3,4,"","L",0,"C",0,0);
        $formu->Cell(20,4,html_entity_decode("C&eacute;dula Ciudadan&iacute;a"),0,0,"C",0,0);
        $formu->Cell(3,4,"",0,0,"C",0,0);
        $formu->Cell(4,4,$a,1,0,"C",0,0);
        $formu->Cell(3,4,"","L",0,"C",0,0);
        $formu->Cell(18,4,"Tajeta Identidad",0,0,"C",0,0);
        $formu->Cell(3,4,"",0,0,"C",0,0);
        $formu->Cell(4,4,$b,1,0,"C",0,0);
        $formu->Cell(2,4,"","R",0,"C",0,0);
        $formu->Cell(30,4,$cedtra,"LR",0,"C",0,0);
        $formu->Cell(37,4,$priape,"LR",0,"C",0,0);
        $formu->Cell(37,4,$segape,"LR",0,"C",0,0);
        $formu->Cell(37,4,$prinom,"LR",0,"C",0,0);
        $formu->Cell(37,4,$segnom,"LR",0,"C",0,0);
        $formu->Cell(2,4,"",0,0,"C",0,0);
        $e = "";
        $f = "";
        $g = "";
        $h = "";
        switch($estciv){
            case 50:
                $e= "X";
                break;
            case 52:
                $f= "X";
                break;
            case 51:
                $g= "X";
                break;
            case 44:
                $h= "X";
                break;
        }
        $formu->Cell(13,4,"Soltero",0,0,"L",0,0);
        $formu->Cell(4,4,$e,1,0,"C",0,0);
        $formu->Cell(2,4,"","L",0,"C",0,0);
        $formu->Cell(16,4,html_entity_decode("Uni&oacute;n Libre"),0,0,"L",0,0);
        $formu->Cell(2,4,"",0,0,"C",0,0);
        $formu->Cell(4,4,$f,1,0,"C",0,0);
        $formu->Cell(2,4,"","R",1,"C",0,0);
        $formu->Cell(3,1,"","L",0,"C",0,0);
        $formu->Cell(20,1,"",0,0,"C",0,0);
        $formu->Cell(3,1,"",0,0,"C",0,0);
        $formu->Cell(4,1,"",0,0,"C",0,0);
        $formu->Cell(3,1,"",0,0,"C",0,0);
        $formu->Cell(18,1,"",0,0,"C",0,0);
        $formu->Cell(3,1,"",0,0,"C",0,0);
        $formu->Cell(4,1,"",0,0,"C",0,0);
        $formu->Cell(2,1,"","R",0,"C",0,0);
        $formu->Cell(30,1,"","LR",0,"C",0,0);
        $formu->Cell(37,1,"","LR",0,"C",0,0);
        $formu->Cell(37,1,"","LR",0,"C",0,0);
        $formu->Cell(37,1,"","LR",0,"C",0,0);
        $formu->Cell(37,1,"","LR",0,"C",0,0);
        $formu->Cell(2,1,"",0,0,"C",0,0);
        $formu->Cell(13,1,"",0,0,"L",0,0);
        $formu->Cell(4,1,"",0,0,"C",0,0);
        $formu->Cell(2,1,"",0,0,"C",0,0);
        $formu->Cell(16,1,"",0,0,"L",0,0);
        $formu->Cell(2,1,"",0,0,"C",0,0);
        $formu->Cell(4,1,"",0,0,"C",0,0);
        $formu->Cell(2,1,"","R",1,"C",0,0);
        $formu->Cell(3,4,"","L",0,"C",0,0);
        $formu->Cell(20,4,html_entity_decode("Registro Civil &oacute; NUIP"),0,0,"C",0,0);
        $formu->Cell(3,4,"",0,0,"C",0,0);
        $formu->Cell(4,4,$c,1,0,"C",0,0);
        $formu->Cell(3,4,"","L",0,"C",0,0);
        $formu->Cell(18,4,html_entity_decode("C&eacute;dula Extranjeria"),0,0,"C",0,0);
        $formu->Cell(3,4,"",0,0,"C",0,0);
        $formu->Cell(4,4,$d,1,0,"C",0,0);
        $formu->Cell(2,4,"","R",0,"C",0,0);
        $formu->Cell(30,4,"","LR",0,"C",0,0);
        $formu->Cell(37,4,"","LR",0,"C",0,0);
        $formu->Cell(37,4,"","LR",0,"C",0,0);
        $formu->Cell(37,4,"","LR",0,"C",0,0);
        $formu->Cell(37,4,"","LR",0,"C",0,0);
        $formu->Cell(2,4,"",0,0,"C",0,0);
        $formu->Cell(13,4,"Casado",0,0,"L",0,0);
        $formu->Cell(4,4,$g,1,0,"C",0,0);
        $formu->Cell(2,4,"","L",0,"C",0,0);
        $formu->Cell(16,4,"Separado",0,0,"L",0,0);
        $formu->Cell(2,4,"",0,0,"C",0,0);
        $formu->Cell(4,4,$h,1,0,"C",0,0);
        $formu->Cell(2,4,"","R",1,"C",0,0);
        $formu->Cell(283,1,"","RBL",1,"C",0,0);
        $formu->Cell(30,1,"","RTL",0,"C",0,0);
        $formu->Cell(30,1,"","RTL",0,"C",0,0);
        $formu->Cell(30,1,"","RTL",0,"C",0,0);
        $formu->Cell(10,1,"","TL",0,"C",0,0);
        $formu->Cell(3,1,"","",0,"C",0,0);
        $formu->Cell(2,1,"","R",0,"C",0,0);
        $formu->Cell(26,1,"","R",0,"C",0,0);
        $formu->Cell(27,1,"","R",0,"C",0,0);
        $formu->Cell(30,1,"","R",0,"C",0,0);
        $formu->Cell(30,1,"","R",0,"C",0,0);
        $formu->Cell(40,1,"","R",0,"C",0,0);
        $formu->Cell(25,1,"","RL",1,"C",0,0);
        $formu->Cell(30,3,"Fecha de Nacimiento","RL",0,"C",0,0);
        $formu->Cell(30,3,"Ciudad de Nacimiento","RL",0,"C",0,0);
        $formu->Cell(30,3,"Dpto. de Nacimiento","RL",0,"C",0,0);
        $formu->Cell(10,3,"F","L",0,"R",0,0);
        $i = "";
        $j = "";
        switch ($sexo){
            case 'M':
                $i = "X";
                break;
            case 'F':
                $j = "X";
                break;
        }
        $formu->Cell(3,3,$j,1,0,"C",0,0);
        $formu->SetTextColor(0);
        $formu->Cell(2,2,"",0,0,"C",0,0);
        $formu->Cell(26,3,"Tipo de Salario","RL",0,"C",0,0);
        $formu->Cell(27,3,"Valor salario mes","RL",0,"C",0,0);
        $formu->Cell(30,3,"Horas trabajadas mes","RL",0,"C",0,0);
        $formu->Cell(30,3,"Fecha de ingreso","RL",0,"C",0,0);
        $formu->Cell(40,3,"Cargo u oficio desempenado","RL",0,"C",0,0);
        $formu->Cell(25,3,html_entity_decode("Profesi&oacute;n"),"RL",1,"C",0,0);
        $formu->Cell(30,1,"","RL",0,"C",0,0);
        $formu->Cell(30,1,"","RL",0,"C",0,0);
        $formu->Cell(30,1,"","RL",0,"C",0,0);
        $formu->Cell(10,1,"","L",0,"C",0,0);
        $formu->Cell(3,1,"","",0,"C",0,0);
        $formu->Cell(2,1,"","R",0,"C",0,0);
        $formu->Cell(26,1,"","R",0,"C",0,0);
        $formu->Cell(27,1,"","R",0,"C",0,0);
        $formu->Cell(30,1,"","R",0,"C",0,0);
        $formu->Cell(30,1,"","R",0,"C",0,0);
        $formu->Cell(40,1,"","R",0,"C",0,0);
        $formu->Cell(25,1,"","RL",1,"C",0,0);
        $formu->Cell(10,3,$dianac,"TRL",0,"C",0,0);
        $formu->Cell(10,3,$mesnac,"RTL",0,"C",0,0);
        $formu->Cell(10,3,$anonac,"TRL",0,"C",0,0);
        $formu->SetTextColor(0);
        $formu->Cell(30,3,$ciunac,"RL",0,"C",0,0);
        $formu->Cell(30,3,$departn,"RL",0,"C",0,0);
        $formu->Cell(7,3,"Sexo","L",0,"C",0,0);
        $formu->Cell(3,3,"M","",0,"C",0,0);
        $formu->Cell(3,3,$i,1,0,"C",0,0);
        $formu->SetTextColor(0);
        $k = "";
        $l = "";
        switch ($tipcon){
            case 'F':
                $k = "X";
                break;
            case 'V':
                $l = "X";
                break;
        }
        $formu->Cell(2,2,"","R",0,"C",0,0);
        $formu->Cell(7,3,"Fijo","RL",0,"C",0,0);
        $formu->Cell(3,3,$k,1,0,"C",0,0);
        $formu->Cell(12,3,"Variable","RL",0,"C",0,0);
        $formu->Cell(3,3,$l,1,0,"C",0,0);
        $formu->Cell(1,3,"",0,0,"C",0,0);
        $formu->Cell(27,3,"$ ".$salario,"RL",0,"C",0,0);
        $formu->Cell(30,3,$horas,"RL",0,"C",0,0);
        $formu->Cell(10,3,$diaing,"RTL",0,"C",0,0);
        $formu->Cell(10,3,$mesing,"RLT",0,"C",0,0);
        $formu->Cell(10,3,$anoing,"RTL",0,"C",0,0);
        $formu->SetFont('Arial','','6');
        if(strlen($cargos) >= 30){
            $cargos = substr($cargos,0,27)."..";
        }

        $formu->Cell(40,3,$cargos,"RL",0,"C",0,0);
        $formu->SetFont('Arial','','6');
        if(strlen($profesion) >= 20){
            $profesion = substr($profesion,0,16)."..";
        }
        $formu->Cell(25,3,$profesion,"RL",1,"C",0,0);
        $formu->SetFont('Arial','B','8');
        $formu->Cell(10,1,"","RBL",0,"C",0,0);
        $formu->Cell(10,1,"","RBL",0,"C",0,0);
        $formu->Cell(10,1,"","RBL",0,"C",0,0);
        $formu->Cell(30,1,"","RBL",0,"C",0,0);
        $formu->Cell(30,1,"","RBL",0,"C",0,0);
        $formu->Cell(10,1,"","BL",0,"C",0,0);
        $formu->Cell(3,1,"","",0,"C",0,0);
        $formu->Cell(2,1,"","R",0,"C",0,0);
        $formu->Cell(26,1,"","R",0,"C",0,0);
        $formu->Cell(27,1,"","R",0,"C",0,0);
        $formu->Cell(30,1,"","R",0,"C",0,0);
        $formu->Cell(10,1,"","R",0,"C",0,0);
        $formu->Cell(10,1,"","R",0,"C",0,0);
        $formu->Cell(10,1,"","R",0,"C",0,0);
        $formu->Cell(40,1,"","R",0,"C",0,0);
        $formu->Cell(25,1,"","RL",1,"C",0,0);
        $formu->Cell(283,4,"",1,1,"L",1,0);
        $formu->Cell(26,3,"Madre Comunitaria","LTR",0,"C",0,0);
        $formu->Cell(56,3,html_entity_decode("Direcci&oacute;n residencia"),"LTR",0,"C",0,0);
        $formu->Cell(22,3,"Departamento","LTR",0,"C",0,0);
        $formu->Cell(24,3,"Ciudad/Mpio","LTR",0,"C",0,0);
        $formu->Cell(28,3,"Zona","LTR",0,"C",0,0);
        $formu->Cell(24,3,"Barrio","LTR",0,"C",0,0);
        $formu->Cell(40,3,"Tipo de Propiedad","LTR",0,"C",0,0);
        $formu->Cell(19,3,"Telefono","LTR",0,"C",0,0);
        $formu->Cell(44,3,"E-mail","LTR",1,"C",0,0);
        $formu->Cell(26,1,"","LR",0,"C",0,0);
        $formu->Cell(56,1,"","LBR",0,"C",0,0);
        $formu->Cell(22,1,"","LBR",0,"C",0,0);
        $formu->Cell(24,1,"","LBR",0,"C",0,0);
        $formu->Cell(28,1,"","LBR",0,"C",0,0);
        $formu->Cell(24,1,"","LBR",0,"C",0,0);
        $formu->Cell(40,1,"","LBR",0,"C",0,0);
        $formu->Cell(19,1,"","LBR",0,"C",0,0);
        $formu->Cell(44,1,"","LBR",1,"C",0,0);
        $z="";
        $x="";
        switch($madcom){
            case 'S':
                $z='X';
                break;
            case 'N':
                $x='X';
                break;
        }
        $formu->Cell(8,3,"SI","L",0,"C",0,0);
        $formu->Cell(3,3,$z,1,0,"C",0,0);
        $formu->Cell(2,3,"",0,0,"C",0,0);
        $formu->Cell(8,3,"NO","",0,"C",0,0);
        $formu->Cell(3,3,$x,1,0,"C",0,0);
        $formu->Cell(2,3,"",0,0,"C",0,0);
        $formu->Cell(56,3,"","LTR",0,"C",0,0);
        $formu->Cell(22,3,"","LTR",0,"C",0,0);
        $formu->Cell(24,3,"","LTR",0,"C",0,0);
        $formu->Cell(28,3,"","LTR",0,"C",0,0);
        $formu->Cell(24,3,"","LTR",0,"C",0,0);
        $formu->Cell(40,3,"","LTR",0,"C",0,0);
        $formu->Cell(19,3,"","LTR",0,"C",0,0);
        $formu->Cell(44,3,"","LTR",1,"C",0,0);
        $formu->Cell(26,1,"","LR",0,"C",0,0);
        $formu->Cell(56,1,"","LR",0,"C",0,0);
        $formu->Cell(22,1,"","LR",0,"C",0,0);
        $formu->Cell(24,1,"","LR",0,"C",0,0);
        $formu->Cell(28,1,"","LR",0,"C",0,0);
        $formu->Cell(24,1,"","LR",0,"C",0,0);
        $formu->Cell(40,1,"","LR",0,"C",0,0);
        $formu->Cell(19,1,"","LR",0,"C",0,0);
        $formu->SetFont('Arial','','7');
        $formu->Cell(44,1,"$email1","LR",1,"C",0,0);
        $formu->SetFont('Arial','B','7');
        $formu->Cell(26,3,"Jefe Cabeza de Hogar","TL",0,"C",0,0);
        $formu->SetFont('Arial','','6');
        $formu->Cell(56,3, " ".$dirres,"LR",0,"L",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(22,3,$depres,"LR",0,"C",0,0);
        $formu->Cell(24,3,$ciures,"LR",0,"C",0,0);
        $formu->Cell(14,3,"Rural","L",0,"C",0,0);
        $formu->SetFont('Arial','','6');
        $formu->Cell(14,3,"Urbano","R",0,"C",0,0);
        $formu->Cell(24,3,$barrio,"LR",0,"C",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(13,3,"Propia","L",0,"C",0,0);
        $formu->Cell(13,3,"Familiar",0,0,"C",0,0);
        $formu->Cell(14,3,"Arrienda","R",0,"C",0,0);
        $formu->Cell(19,3,$telefono,"LR",0,"C",0,0);
        $formu->SetFont('Arial','','7');
        //$formu->Cell(44,3,$email,"LR",1,"C",0,0);
        $formu->Cell(44,3,"","LR",1,"C",0,0);
        $formu->SetFont('Arial','','8');
        $p="";
        $q="";
        switch($cabhog){
            case 'S':
                $p="X";
                break;
            case 'N':
                $q="X";
                break;
        }
        $formu->Cell(8,3,"SI","L",0,"C",0,0);
        $formu->Cell(3,3,$p,1,0,"C",0,0);
        $formu->Cell(2,3,"",0,0,"C",0,0);
        $formu->Cell(8,3,"NO","",0,"C",0,0);
        $formu->Cell(3,3,$q,1,0,"C",0,0);
        $formu->Cell(2,3,"",0,0,"C",0,0);
        $formu->Cell(56,3,"","LR",0,"C",0,0);
        $formu->Cell(22,3,"","LR",0,"C",0,0);
        $formu->Cell(24,3,"","LR",0,"C",0,0);
        $formu->Cell(5,3,"",0,0,"C",0,0);
        $m="";
        $n="";
        $o="";
        switch($vivienda){
            case '2725':
                $m="X";
                break;
            case '2726':
                $n="X";
                break;
            case '2727':
                $o="X";
                break;
        }
        $r="";
        $s="";
        switch($rural){
            case 'S':
                $r="X";
                break;
            case 'N':
                $s="X";
                break;
        }
        $formu->Cell(4,3,$r,1,0,"C",0,0);
        $formu->Cell(5,3,"",0,0,"C",0,0);
        $formu->Cell(5,3,"",0,0,"C",0,0);
        $formu->Cell(4,3,$s,1,0,"C",0,0);
        $formu->Cell(5,3,"",0,0,"C",0,0);
        $formu->Cell(24,3,"","LR",0,"C",0,0);
        $formu->Cell(5,3,"",0,0,"C",0,0);
        $formu->Cell(4,3,$m,1,0,"C",0,0);
        $formu->Cell(4,3,"",0,0,"C",0,0);
        $formu->Cell(5,3,"",0,0,"C",0,0);
        $formu->Cell(4,3,$n,1,0,"C",0,0);
        $formu->Cell(4,3,"",0,0,"C",0,0);
        $formu->Cell(5,3,"",0,0,"C",0,0);
        $formu->Cell(4,3,$o,1,0,"C",0,0);
        $formu->Cell(5,3,"",0,0,"C",0,0);
        $formu->Cell(19,3,"","LR",0,"C",0,0);
        $formu->SetFont('Arial','','7');
        $formu->Cell(44,3,"$email2","LR",1,"C",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(26,1,"","LR",0,"C",0,0);
        $formu->Cell(56,1,"","LR",0,"C",0,0);
        $formu->Cell(22,1,"","LR",0,"C",0,0);
        $formu->Cell(24,1,"","LR",0,"C",0,0);
        $formu->Cell(28,1,"","LR",0,"C",0,0);
        $formu->Cell(24,1,"","LR",0,"C",0,0);
        $formu->Cell(40,1,"","LR",0,"C",0,0);
        $formu->Cell(19,1,"","LR",0,"C",0,0);
        $formu->Cell(44,1,"","LR",1,"C",0,0);
        $formu->SetFont('Arial','B','9');
        $formu->Cell(283,4,html_entity_decode("Datos adicionaes empleadas servicio dom&eacute;stico (Relacione informaci&oacute;n de empleadores adicionales con los que labora)"),1,1,"L",1,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(35,4,"NIT.","LR",0,"C",0,0);
        $formu->Cell(95,4,html_entity_decode("Raz&oacute;n Social"),"LR",0,"C",0,0);
        $formu->Cell(35,4,"No. de horas/mes","LR",0,"C",0,0);
        $formu->Cell(42,4,"Salario mes","LR",0,"C",0,0);
        $formu->Cell(76,4,"Afiliado a Caja de Compensacion","LR",1,"C",0,0);
        $formu->Cell(35,4,"",1,0,"C",0,0);
        $formu->Cell(95,4,"",1,0,"C",0,0);
        $formu->Cell(35,4,"",1,0,"C",0,0);
        $formu->Cell(42,4,"",1,0,"C",0,0);
        $formu->Cell(7,4,"SI",1,0,"C",0,0);
        $formu->Cell(6,4,"",1,0,"C",0,0);
        $formu->Cell(7,4,"NO",1,0,"C",0,0);
        $formu->Cell(6,4,"",1,0,"C",0,0);
        $formu->Cell(50,4,html_entity_decode("Cual?"),1,1,"L",0,0);
        $formu->Cell(35,4,"",1,0,"C",0,0);
        $formu->Cell(95,4,"",1,0,"C",0,0);
        $formu->Cell(35,4,"",1,0,"C",0,0);
        $formu->Cell(42,4,"",1,0,"C",0,0);
        $formu->Cell(7,4,"SI",1,0,"C",0,0);
        $formu->Cell(6,4,"",1,0,"C",0,0);
        $formu->Cell(7,4,"NO",1,0,"C",0,0);
        $formu->Cell(6,4,"",1,0,"C",0,0);
        $formu->Cell(50,4,html_entity_decode("Cual?"),1,1,"L",0,0);
        $formu->SetFont('Arial','B','9');
        $formu->Cell(283,4,html_entity_decode("Datos grupo familiar que van a afiliar, incluir c&oacute;nyugue o compa&ntilde;ero(a) permanente"),1,1,"L",1,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(59,4,html_entity_decode("Identificaci&oacute;n"),1,0,"C",0,0);
        $formu->Cell(28,4,"","LRT",0,"C",0,0);
        $formu->Cell(28,4,"","LRT",0,"C",0,0);
        $formu->Cell(28,4,"","LRT",0,"C",0,0);
        $formu->Cell(28,4,"","LRT",0,"C",0,0);
        $formu->Cell(28,4,"","LRT",0,"C",0,0);
        $formu->Cell(20,4,"","LRT",0,"C",0,0);
        $formu->Cell(32,4,"Parentesco",1,0,"C",0,0);
        $formu->Cell(32,4,html_entity_decode("Condici&oacute;n u Ocupaci&oacute;n"),1,1,"C",0,0);
        $formu->Cell(30,3,"Tipo de Documento","LBRT",0,"C",0,0);
        $formu->Cell(29,3,"","LRT",0,"C",0,0);
        $formu->Cell(28,3,"","LRT",0,"C",0,0);
        $formu->Cell(28,3,"","LRT",0,"C",0,0);
        $formu->Cell(28,3,"","LRT",0,"C",0,0);
        $formu->Cell(28,3,"","LRT",0,"C",0,0);
        $formu->Cell(28,3,"","LRT",0,"C",0,0);
        $formu->Cell(20,3,"","LRT",0,"C",0,0);
        $formu->Cell(6,3,"","LRT",0,"C",0,0);
        $formu->Cell(5,3,"","LRT",0,"C",0,0);
        $formu->Cell(5,3,"","LRT",0,"C",0,0);
        $formu->Cell(5,3,"","LRT",0,"C",0,0);
        $formu->Cell(5,3,"","LRT",0,"C",0,0);
        $formu->Cell(6,3,"","LRT",0,"C",0,0);
        $formu->Cell(16,3,"","LRT",0,"C",0,0);
        $formu->SetFont('Arial','','6');
        $formu->Cell(11,3,"P. prim","LRT",0,"C",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(5,3,"","LRT",1,"C",0,0);
        $formu->Cell(7.5,3,"","LR",0,"C",0,0);
        $formu->Cell(7.5,3,"","LR",0,"C",0,0);
        $formu->SetFont('Arial','','6');
        $formu->Cell(7.5,3,"R.C","LR",0,"C",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(7.5,3,"","LR",0,"C",0,0);
        $formu->Cell(29,3,html_entity_decode("N&uacute;mero"),"LR",0,"C",0,0);
        $formu->Cell(28,3,"Primer Apellido","LR",0,"C",0,0);
        $formu->Cell(28,3,"Segundo Apellido","LR",0,"C",0,0);
        $formu->Cell(28,3,"Primer Nombre","LR",0,"C",0,0);
        $formu->Cell(28,3,"Segundo Nombre","LR",0,"C",0,0);
        $formu->Cell(28,3,"Fecha de Nacimiento","LR",0,"C",0,0);
        $formu->Cell(20,3,"Sexo","LR",0,"C",0,0);
        $formu->Cell(6,3,"","LR",0,"C",0,0);
        $formu->Cell(5,3,"","LR",0,"C",0,0);
        $formu->Cell(5,3,"","LR",0,"C",0,0);
        $formu->Cell(5,3,"","LR",0,"C",0,0);
        $formu->Cell(5,3,"","LR",0,"C",0,0);
        $formu->Cell(6,3,"","LR",0,"C",0,0);
        $formu->Cell(16,3,"Estudia","LR",0,"C",0,0);
        $formu->SetFont('Arial','','6');
        $formu->Cell(11,3,"S. sec","LR",0,"C",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(5,3,"","LR",1,"C",0,0);
        $formu->Cell(7.5,3,"C.C","LR",0,"C",0,0);
        $formu->Cell(7.5,3,"T.I","LR",0,"C",0,0);
        $formu->Cell(7.5,3,"o","LR",0,"C",0,0);
        $formu->Cell(7.5,3,"C.E","LR",0,"C",0,0);
        $formu->Cell(29,3,"","LR",0,"C",0,0);
        $formu->Cell(28,3,"","LR",0,"C",0,0);
        $formu->Cell(28,3,"","LR",0,"C",0,0);
        $formu->Cell(28,3,"","LR",0,"C",0,0);
        $formu->Cell(28,3,"","LR",0,"C",0,0);
        $formu->Cell(28,3,"","LR",0,"C",0,0);
        $formu->Cell(20,3,"","LR",0,"C",0,0);
        $formu->Cell(6,3,"","LR",0,"C",0,0);
        $formu->Cell(5,3,"","LR",0,"C",0,0);
        $formu->Cell(5,3,"","LR",0,"C",0,0);
        $formu->Cell(5,3,"","LR",0,"C",0,0);
        $formu->Cell(5,3,"","LR",0,"C",0,0);
        $formu->Cell(6,3,"","LR",0,"C",0,0);
        $formu->Cell(16,3,"","LR",0,"C",0,0);
        $formu->SetFont('Arial','','6');
        $formu->Cell(11,3,"T. tec","LR",0,"C",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(5,3,"","LR",1,"C",0,0);
        $formu->Cell(7.5,3,"","LBR",0,"C",0,0);
        $formu->Cell(7.5,3,"","LBR",0,"C",0,0);
        $formu->SetFont('Arial','','6');
        $formu->Cell(7.5,3,"Nuip","LBR",0,"C",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(7.5,3,"","LBR",0,"C",0,0);
        $formu->Cell(29,3,"","LBR",0,"C",0,0);
        $formu->Cell(28,3,"","LBR",0,"C",0,0);
        $formu->Cell(28,3,"","LBR",0,"C",0,0);
        $formu->Cell(28,3,"","LBR",0,"C",0,0);
        $formu->Cell(28,3,"","LBR",0,"C",0,0);
        $formu->Cell(28,3,"","LBR",0,"C",0,0);
        $formu->Cell(20,3,"","LBR",0,"C",0,0);
        $formu->Cell(6,3,"","LBR",0,"C",0,0);
        $formu->Cell(5,3,"","LBR",0,"C",0,0);
        $formu->Cell(5,3,"","LBR",0,"C",0,0);
        $formu->Cell(5,3,"","LBR",0,"C",0,0);
        $formu->Cell(5,3,"","LBR",0,"C",0,0);
        $formu->Cell(6,3,"","LBR",0,"C",0,0);
        $formu->Cell(8,3,"SI","TLBR",0,"C",0,0);
        $formu->Cell(8,3,"NO","TLBR",0,"C",0,0);
        $formu->SetFont('Arial','','6');
        $formu->Cell(11,3,"U. univ","LBR",0,"C",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(5,3,"","LBR",1,"C",0,0);
        $formu->SetTextColor(219);
        $formu->Cell(7.5,4,"",1,0,"C",0,0);
        $formu->Cell(7.5,4,"",1,0,"C",0,0);
        $formu->Cell(7.5,4,"",1,0,"C",0,0);
        $formu->Cell(7.5,4,"",1,0,"C",0,0);
        $formu->Cell(29,4,"",1,0,"C",0,0);
        $formu->Cell(28,4,"",1,0,"C",0,0);
        $formu->Cell(28,4,"",1,0,"C",0,0);
        $formu->Cell(28,4,"",1,0,"C",0,0);
        $formu->Cell(28,4,"",1,0,"C",0,0);
        $formu->Cell(9,4,"DD",1,0,"C",0,0);
        $formu->Cell(9,4,"MM",1,0,"C",0,0);
        $formu->Cell(10,4,"AAAA",1,0,"C",0,0);
        $formu->Cell(10,4,"F",1,0,"C",0,0);
        $formu->Cell(10,4,"M",1,0,"C",0,0);
        $formu->Cell(6,4,"",1,0,"C",0,0);
        $formu->Cell(5,4,"",1,0,"C",0,0);
        $formu->Cell(5,4,"",1,0,"C",0,0);
        $formu->Cell(5,4,"",1,0,"C",0,0);
        $formu->Cell(5,4,"",1,0,"C",0,0);
        $formu->Cell(6,4,"",1,0,"C",0,0);
        $formu->Cell(8,4,"",1,0,"C",0,0);
        $formu->Cell(8,4,"",1,0,"C",0,0);
        $formu->Cell(11,4,"",1,0,"C",0,0);
        $formu->Cell(5,4,"",1,1,"C",0,0);
        $formu->Cell(7.5,4,"",1,0,"C",0,0);
        $formu->Cell(7.5,4,"",1,0,"C",0,0);
        $formu->Cell(7.5,4,"",1,0,"C",0,0);
        $formu->Cell(7.5,4,"",1,0,"C",0,0);
        $formu->Cell(29,4,"",1,0,"C",0,0);
        $formu->Cell(28,4,"",1,0,"C",0,0);
        $formu->Cell(28,4,"",1,0,"C",0,0);
        $formu->Cell(28,4,"",1,0,"C",0,0);
        $formu->Cell(28,4,"",1,0,"C",0,0);
        $formu->Cell(9,4,"DD",1,0,"C",0,0);
        $formu->Cell(9,4,"MM",1,0,"C",0,0);
        $formu->Cell(10,4,"AAAA",1,0,"C",0,0);
        $formu->Cell(10,4,"F",1,0,"C",0,0);
        $formu->Cell(10,4,"M",1,0,"C",0,0);
        $formu->Cell(6,4,"",1,0,"C",0,0);
        $formu->Cell(5,4,"",1,0,"C",0,0);
        $formu->Cell(5,4,"",1,0,"C",0,0);
        $formu->Cell(5,4,"",1,0,"C",0,0);
        $formu->Cell(5,4,"",1,0,"C",0,0);
        $formu->Cell(6,4,"",1,0,"C",0,0);
        $formu->Cell(8,4,"",1,0,"C",0,0);
        $formu->Cell(8,4,"",1,0,"C",0,0);
        $formu->Cell(11,4,"",1,0,"C",0,0);
        $formu->Cell(5,4,"",1,1,"C",0,0);
        $formu->Cell(7.5,4,"",1,0,"C",0,0);
        $formu->Cell(7.5,4,"",1,0,"C",0,0);
        $formu->Cell(7.5,4,"",1,0,"C",0,0);
        $formu->Cell(7.5,4,"",1,0,"C",0,0);
        $formu->Cell(29,4,"",1,0,"C",0,0);
        $formu->Cell(28,4,"",1,0,"C",0,0);
        $formu->Cell(28,4,"",1,0,"C",0,0);
        $formu->Cell(28,4,"",1,0,"C",0,0);
        $formu->Cell(28,4,"",1,0,"C",0,0);
        $formu->Cell(9,4,"DD",1,0,"C",0,0);
        $formu->Cell(9,4,"MM",1,0,"C",0,0);
        $formu->Cell(10,4,"AAAA",1,0,"C",0,0);
        $formu->Cell(10,4,"F",1,0,"C",0,0);
        $formu->Cell(10,4,"M",1,0,"C",0,0);
        $formu->Cell(6,4,"",1,0,"C",0,0);
        $formu->Cell(5,4,"",1,0,"C",0,0);
        $formu->Cell(5,4,"",1,0,"C",0,0);
        $formu->Cell(5,4,"",1,0,"C",0,0);
        $formu->Cell(5,4,"",1,0,"C",0,0);
        $formu->Cell(6,4,"",1,0,"C",0,0);
        $formu->Cell(8,4,"",1,0,"C",0,0);
        $formu->Cell(8,4,"",1,0,"C",0,0);
        $formu->Cell(11,4,"",1,0,"C",0,0);
        $formu->Cell(5,4,"",1,1,"C",0,0);
        $formu->Cell(7.5,4,"",1,0,"C",0,0);
        $formu->Cell(7.5,4,"",1,0,"C",0,0);
        $formu->Cell(7.5,4,"",1,0,"C",0,0);
        $formu->Cell(7.5,4,"",1,0,"C",0,0);
        $formu->Cell(29,4,"",1,0,"C",0,0);
        $formu->Cell(28,4,"",1,0,"C",0,0);
        $formu->Cell(28,4,"",1,0,"C",0,0);
        $formu->Cell(28,4,"",1,0,"C",0,0);
        $formu->Cell(28,4,"",1,0,"C",0,0);
        $formu->Cell(9,4,"DD",1,0,"C",0,0);
        $formu->Cell(9,4,"MM",1,0,"C",0,0);
        $formu->Cell(10,4,"AAAA",1,0,"C",0,0);
        $formu->Cell(10,4,"F",1,0,"C",0,0);
        $formu->Cell(10,4,"M",1,0,"C",0,0);
        $formu->Cell(6,4,"",1,0,"C",0,0);
        $formu->Cell(5,4,"",1,0,"C",0,0);
        $formu->Cell(5,4,"",1,0,"C",0,0);
        $formu->Cell(5,4,"",1,0,"C",0,0);
        $formu->Cell(5,4,"",1,0,"C",0,0);
        $formu->Cell(6,4,"",1,0,"C",0,0);
        $formu->Cell(8,4,"",1,0,"C",0,0);
        $formu->Cell(8,4,"",1,0,"C",0,0);
        $formu->Cell(11,4,"",1,0,"C",0,0);
        $formu->Cell(5,4,"",1,1,"C",0,0);
        $formu->Cell(7.5,4,"",1,0,"C",0,0);
        $formu->Cell(7.5,4,"",1,0,"C",0,0);
        $formu->Cell(7.5,4,"",1,0,"C",0,0);
        $formu->Cell(7.5,4,"",1,0,"C",0,0);
        $formu->Cell(29,4,"",1,0,"C",0,0);
        $formu->Cell(28,4,"",1,0,"C",0,0);
        $formu->Cell(28,4,"",1,0,"C",0,0);
        $formu->Cell(28,4,"",1,0,"C",0,0);
        $formu->Cell(28,4,"",1,0,"C",0,0);
        $formu->Cell(9,4,"DD",1,0,"C",0,0);
        $formu->Cell(9,4,"MM",1,0,"C",0,0);
        $formu->Cell(10,4,"AAAA",1,0,"C",0,0);
        $formu->Cell(10,4,"F",1,0,"C",0,0);
        $formu->Cell(10,4,"M",1,0,"C",0,0);
        $formu->Cell(6,4,"",1,0,"C",0,0);
        $formu->Cell(5,4,"",1,0,"C",0,0);
        $formu->Cell(5,4,"",1,0,"C",0,0);
        $formu->Cell(5,4,"",1,0,"C",0,0);
        $formu->Cell(5,4,"",1,0,"C",0,0);
        $formu->Cell(6,4,"",1,0,"C",0,0);
        $formu->Cell(8,4,"",1,0,"C",0,0);
        $formu->Cell(8,4,"",1,0,"C",0,0);
        $formu->Cell(11,4,"",1,0,"C",0,0);
        $formu->Cell(5,4,"",1,1,"C",0,0);
        $formu->SetTextColor(0);
        $formu->SetFont('Arial','','6.9');
        $formu->Cell(283,4,html_entity_decode("Declaro bajo la gravedad de juramento que: Toda la informaci&oacute;n aqui suministrada es veridica. Autorizo a Comfamiliar para que por cualquier medio verifique los datos aqui contenidos y que en caso de falsedad se aplique las sanciones contempladas por la ley."),1,1,"L",0,0);
        $formu->SetFont('Arial','B','9');
        $formu->Cell(283,4,html_entity_decode("INFORMACION EXC&Oacute;NYUGUE O EXCOMPA&Ntilde;ERO(A) PERMANENTE"),1,1,"L",1,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(30,4,"Tipo de Documento",1,0,"C",0,0);
        $formu->Cell(30,4,html_entity_decode("N&uacute;mero"),1,0,"C",0,0);
        $formu->Cell(30,4,"Primer Apellido",1,0,"C",0,0);
        $formu->Cell(30,4,"Segundo Apellido",1,0,"C",0,0);
        $formu->Cell(30,4,"Primer Nombre",1,0,"C",0,0);
        $formu->Cell(30,4,"Segundo Nombre",1,0,"C",0,0);
        $formu->Cell(30,4,"Fecha de Nacimiento",1,0,"C",0,0);
        $formu->Cell(30,4,"Sexo",1,0,"C",0,0);
        $formu->Cell(43,4,"Fecha de Retiro:",1,1,"L",0,0);
        $formu->SetTextColor(219);
        $formu->Cell(15,4,"C.C",1,0,"C",0,0);
        $formu->Cell(15,4,"T.I",1,0,"C",0,0);
        $formu->Cell(30,4,"",1,0,"C",0,0);
        $formu->Cell(30,4,"",1,0,"C",0,0);
        $formu->Cell(30,4,"",1,0,"C",0,0);
        $formu->Cell(30,4,"",1,0,"C",0,0);
        $formu->Cell(30,4,"",1,0,"C",0,0);
        $formu->Cell(9,4,"DD",1,0,"C",0,0);
        $formu->Cell(9,4,"MM",1,0,"C",0,0);
        $formu->Cell(12,4,"AAAA",1,0,"C",0,0);
        $formu->Cell(15,4,"F",1,0,"C",0,0);
        $formu->Cell(15,4,"M",1,0,"C",0,0);
        $formu->Cell(43,4,"",1,1,"L",0,0);
        $formu->SetTextColor(0);
        $formu->Cell(80,6,"Firma y Sello del empleador",1,0,"L",0,0);
        $formu->Cell(80,6,"Firma del Trabajador",1,0,"L",0,0);
        $formu->Cell(34,6,html_entity_decode("N&uacute;mero de radicado"),1,0,"L",0,0);
        $formu->Cell(34,6,"Recibido por:",1,0,"L",0,0);
        $formu->Cell(55,6,"Grabado por:",1,1,"L",0,0);
        $formu->SetFont('Arial','','7');
        $formu->Cell(35,4,"",0,0,"L",0,0);
        $formu->Cell(55,4,"Nombre y Cedula de Ciudadania del trabajador:",0,0,"L",0,0);
        $formu->Cell(68,4,"","B",0,"L",0,0);
        $formu->Cell(20,4,"Recibido por:","",0,"R",0,0);
        $formu->Cell(40,4,"","B",0,"L",0,0);
        $formu->Cell(25,4,html_entity_decode("N&uacute;mero de radicado:"),"",0,"R",0,0);
        $formu->Cell(40,4,"","B",1,"L",0,0);
        $formu->Cell(35,4,"",0,0,"L",0,0);
        $formu->Cell(42,4,html_entity_decode("NIT o Raz&oacute;n Social del empleador:"),0,0,"L",0,0);
        $formu->Cell(86,4,"","B",0,"L",0,0);
        $formu->Cell(25,4,"Fecha de Recibido:","",0,"R",0,0);
        $formu->Cell(95,4,"","B",1,"L",0,0);

        $formu->Cell(283,1,"",0,1,"L",0,0);

        $formu->Cell(35,4,"",0,0,"L",0,0);
        $formu->SetFont('Arial','B','8');
        $formu->Cell(22,4,html_entity_decode("CAUSALES DE"),0,0,"L",0,0);
        $formu->SetFont('Arial','','7');
        $formu->Cell(1,4,"",0,0,"L",0,0);
        $formu->Cell(4,4,"",1,0,"L",0,0);
        $formu->Cell(1,4,"",0,0,"L",0,0);
        $formu->Cell(40,4,html_entity_decode("Fotocopia C&eacute;dula del Trabajador"),"",0,"L",0,0);
        $formu->Cell(4,4,"",1,0,"L",0,0);
        $formu->Cell(1,4,"",0,0,"L",0,0);
        $formu->Cell(33,4,html_entity_decode("Documento ilegible. Cual?"),"",0,"L",0,0);
        $formu->Cell(80,4,"","B",0,"L",0,0);
        $formu->Cell(3,4,"",0,0,"L",0,0);
        $formu->Cell(4,4,"",1,0,"L",0,0);
        $formu->Cell(1,4,"",0,0,"L",0,0);
        $formu->Cell(25,4,html_entity_decode("Empresa Inactiva"),"",0,"R",0,0);
        $formu->Cell(29,4,"","B",1,"L",0,0);
        $formu->Cell(283,1,"",0,1,"L",0,0);
        $formu->Cell(35,4,"",0,0,"L",0,0);
        $formu->SetFont('Arial','B','8');
        $formu->Cell(22,4,html_entity_decode("DEVOLUCI&Oacute;N"),0,0,"L",0,0);
        $formu->SetFont('Arial','','7');
        $formu->Cell(1,4,"",0,0,"L",0,0);
        $formu->Cell(4,4,"",1,0,"L",0,0);
        $formu->Cell(1,4,"",0,0,"L",0,0);
        $formu->Cell(40,4,html_entity_decode("Fotocopia C&eacute;dula del conyugue"),"",0,"L",0,0);
        $formu->Cell(4,4,"",1,0,"L",0,0);
        $formu->Cell(1,4,"",0,0,"L",0,0);
        $formu->Cell(30,4,html_entity_decode("Registro Civil. C&uacute;al?"),"",0,"L",0,0);
        $formu->Cell(83,4,"","B",0,"L",0,0);
        $formu->Cell(3,4,"",0,0,"L",0,0);
        $formu->Cell(4,4,"",1,0,"L",0,0);
        $formu->Cell(1,4,"",0,0,"L",0,0);
        $formu->Cell(25,4,html_entity_decode("Empresa Inactiva"),"",0,"R",0,0);
        $formu->Cell(29,4,"","B",1,"L",0,0);
        $formu->Cell(283,4,"","B",1,"L",0,0);
        $formu->Cell(283,5,"","B",1,"L",0,0);
        $formu->Ln();
        $this->setResponse('view');
        $file = "public/temp/reportes/trabajador_".$cedtra.".pdf";
        ob_clean();
        $formu->Output( $file,"F");
        $response = parent::successFunc("Genera Formulario",$file);
        return $this->renderText(json_encode($response)); 
    }
//Formulario Independiente
    public function addindependienteAction(){
        $formu = new FPDF('L','mm','A4');
        $formu->AddPage();
	    $formu->setTextColor(0);
        $formu->SetFont('Arial','','25');
        $this->setResponse('view');
        $formu->Image('public/img/comfamiliar-logo.jpg',20,10,38,10);
        $formu->Image('public/img/piePagCart/foot3.png',250,15,27,15);
        $formu->SetTextColor(0);    
        $formu->SetFont('Arial','B','12');
        $formu->Cell(10,5,"",0,0,"L",0,0);
        $formu->Cell(260,5,"","LTR",1,"C",0,0);
        $formu->Cell(10,5,"",0,0,"L",0,0);
        $formu->Cell(10,5,"","L",0,"L",0,0);
        $formu->Cell(250,5,html_entity_decode("Afilici&oacute;n del trabajador Independiente o Desempleado y su Grupo Familiar"),"R",1,"C",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(10,3,"",0,0,"L",0,0);
        $formu->Cell(30,3,"","L",0,"L",0,0);
        $formu->Cell(200,3,html_entity_decode("Importante: *Estos afiliados no ser&aacute;n beneficiados del subsidio en dinero"),"",0,"C",0,0);
        $formu->Cell(30,3,html_entity_decode(""),"R",1,"C",0,0);
        $formu->Cell(10,3,"",0,0,"L",0,0);
        $formu->Cell(30,3,"","L",0,"L",0,0);
        $formu->Cell(200,3,html_entity_decode("*Para el pago del 0.6% tiene derecho a recreaci&oacute;n, capacitaci&oacute;n y turismo social"),"",0,"C",0,0);
        $formu->Cell(30,3,html_entity_decode(""),"R",1,"C",0,0);
        $formu->Cell(10,3,"",0,0,"L",0,0);
        $formu->Cell(10,3,"","L",0,"L",0,0);
        $formu->SetFont('Arial','','6');
        $formu->Cell(20,3,"Porcentaje aporte",1,0,"C",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(200,3,html_entity_decode("*Por el pago del 2% de sus Ingresos mensuales o del equivalente a 2 smlv. Tiene derecho a los dem&aacute;s beneficios"),"",0,"C",0,0);
        $formu->Cell(30,3,html_entity_decode(""),"R",1,"C",0,0);
        $formu->Cell(10,3,"",0,0,"L",0,0);
        $formu->Cell(10,3,"","L",0,"L",0,0);
        $formu->SetFont('Arial','','6');
        $formu->Cell(10,3,"2 %",1,0,"C",0,0);
        $formu->Cell(10,3,"0.6 %",1,0,"C",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(200,3,html_entity_decode("excepci&oacute;n del subsidio en dinero"),"",0,"C",0,0);
        $formu->Cell(30,3,html_entity_decode(""),"R",1,"C",0,0);
        $formu->Cell(10,2,"","",0,"",0,0);
        $formu->Cell(260,2,"","LR",1,"",0,0);
        $formu->SetFont('Arial','','5.8');
	//Hasta aca va la cabecera
        $formu->SetFont('Arial','B','10');
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->SetFillColor(236,248,240);
        $formu->Cell(10,5,html_entity_decode(""),"LBT",0,"L",1,0);
        $formu->Cell(250,5,html_entity_decode("Datos de Afiliado"),"RBT",1,"L",1,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(80,5,html_entity_decode("Identificaci&oacute;n"),"LB",0,"C",0,0);
        $formu->Cell(25,10,html_entity_decode("N&uacute;mero"),"LRB",0,"C",0,0);
        $formu->Cell(25,10,html_entity_decode("Primer Apellido"),"RB",0,"C",0,0);
        $formu->Cell(25,10,html_entity_decode("Segundo Apellido"),"RB",0,"C",0,0);
        $formu->Cell(25,10,html_entity_decode("Primer Nombre"),"RB",0,"C",0,0);
        $formu->Cell(25,10,html_entity_decode("Segundo Nombre"),"RB",0,"C",0,0);
        $formu->Cell(55,5,html_entity_decode("Estado Civil"),"RB",1,"C",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->SetFont('Arial','','6.5');
        $formu->Cell(80,2,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(180,2,html_entity_decode(""),"R",1,"C",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(27,3,html_entity_decode("Cedula de Ciudadania"),"L",0,"L",0,0);
        $formu->Cell(3,3,html_entity_decode(""),1,0,"L",0,0);
        $formu->Cell(5,3,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(27,3,html_entity_decode("Tarjeta de Identidad"),"",0,"L",0,0);
        $formu->Cell(3,3,html_entity_decode(""),1,0,"L",0,0);
        $formu->Cell(140,3,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(15,3,html_entity_decode("Soltero"),"",0,"C",0,0);
        $formu->Cell(3,3,html_entity_decode(""),1,0,"L",0,0);
        $formu->Cell(5,3,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(25,3,html_entity_decode("Union Libre"),"",0,"L",0,0);
        $formu->Cell(3,3,html_entity_decode(""),1,0,"L",0,0);
        $formu->Cell(4,3,html_entity_decode(""),"R",1,"L",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(80,2,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(25,6,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(25,6,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(25,6,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(25,6,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(25,6,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(55,2,html_entity_decode(""),"R",1,"C",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(27,3,html_entity_decode("Registro Civil o NUIP"),"L",0,"L",0,0);
        $formu->Cell(3,3,html_entity_decode(""),1,0,"L",0,0);
        $formu->Cell(5,3,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(27,3,html_entity_decode("Cedula Extranjera"),"",0,"L",0,0);
        $formu->Cell(3,3,html_entity_decode(""),1,0,"L",0,0);
        $formu->Cell(140,3,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(15,3,html_entity_decode("Casado"),"",0,"C",0,0);
        $formu->Cell(3,3,html_entity_decode(""),1,0,"L",0,0);
        $formu->Cell(5,3,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(25,3,html_entity_decode("Separado"),"",0,"L",0,0);
        $formu->Cell(3,3,html_entity_decode(""),1,0,"L",0,0);
        $formu->Cell(4,3,html_entity_decode(""),"R",1,"L",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(80,1,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(125,1,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(55,1,html_entity_decode(""),"BR",1,"L",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(50,6,html_entity_decode("Fecha Nacimiento"),"LBR",0,"C",0,0);
        $formu->Cell(30,6,html_entity_decode("Sexo"),"R",0,"C",0,0);
        $formu->Cell(25,6,html_entity_decode("Nit EPS"),"R",0,"C",0,0);
        $formu->Cell(25,6,html_entity_decode("Nombre EPS"),"R",0,"C",0,0);
        $formu->SetFont('Arial','','5.8');
        $formu->Cell(25,6,html_entity_decode("Nit Fondo de Pensiones"),"R",0,"C",0,0);
        $formu->SetFont('Arial','','5.4');
        $formu->Cell(25,6,html_entity_decode("Nombre Fondo de Pensiones"),"R",0,"C",0,0);
        $formu->SetFont('Arial','','6.5');
        $formu->Cell(50,6,html_entity_decode("Valor Base se aporte EPS y Pensiones $"),"R",0,"C",0,0);
        $formu->Cell(30,6,html_entity_decode("Profesi&oacute;n(Ocupaci&oacute;n)"),"RB",1,"C",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(15,5,html_entity_decode("D"),"LR",0,"C",0,0);
        $formu->Cell(15,5,html_entity_decode("M"),"R",0,"C",0,0);
        $formu->Cell(20,5,html_entity_decode("A"),"R",0,"C",0,0);
        $formu->Cell(7,4,html_entity_decode("F"),0,0,"R",0,0);
        $formu->Cell(4,4,html_entity_decode(""),1,0,"C",0,0);
        $formu->Cell(9,4,html_entity_decode("M"),0,0,"R",0,0);
        $formu->Cell(4,4,html_entity_decode(""),1,0,"C",0,0);
        $formu->Cell(6,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(25,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(25,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(25,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(25,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(50,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(30,5,html_entity_decode(""),"R",1,"C",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(260,0,html_entity_decode(""),"B",1,"C",0,0);
        $formu->Cell(10,2,"","",1,"",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->SetFont('Arial','B','9.5');
        $formu->Cell(260,4.3,html_entity_decode("Datos del Grupo Familiar que va a Afiliar(Cuando un trabajador tenga m&aacute;s de un grupo faamiliar debera digilenciar un formato de afiliacion para cada grupo)"),1,1,"L",1,0);
        $formu->SetFont('Arial','','6');
        $formu->Cell(10,4.3,"","",0,"",0,0);
        $formu->Cell(40,4.3,html_entity_decode("Num. De Personas Grupo Familiar"),"LBR",0,"C",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(60,4.3,html_entity_decode("Direccion Residencia"),"BR",0,"C",0,0);
        $formu->Cell(40,4.3,html_entity_decode("Departamento"),"BR",0,"C",0,0);
        $formu->Cell(40,4.3,html_entity_decode("Ciudad"),"BR",0,"C",0,0);
        $formu->Cell(40,4.3,html_entity_decode("Barrio"),"BR",0,"C",0,0);
        $formu->Cell(40,4.3,html_entity_decode("Tel&eacute;fono"),"BR",1,"C",0,0);
        $formu->Cell(10,4.3,"","",0,"",0,0);
        $formu->Cell(40,4.3,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(60,4.3,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(40,4.3,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(40,4.3,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(40,4.3,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(40,4.3,html_entity_decode(""),"LBR",1,"C",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(10,2,"","",1,"",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->SetFont('Arial','B','9.5');
        $formu->Cell(260,5,html_entity_decode("Inscripcion del Grupo Familiar. Incluir C&oacute;yuge o compa&ntilde;ero(a) Permanente"),1,1,"L",1,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(60,5,html_entity_decode("Identificaci&oacute;n"),"LB",0,"C",0,0);
        $formu->Cell(25,25,html_entity_decode("Primer Apellido"),"LBR",0,"C",0,0);
        $formu->Cell(25,25,html_entity_decode("Segundo Apellido"),"BR",0,"C",0,0);
        $formu->Cell(25,25,html_entity_decode("Primer Nombre"),"BR",0,"C",0,0);
        $formu->Cell(25,25,html_entity_decode("Segundo Nombre"),"BR",0,"C",0,0);
        $formu->Cell(25,25,html_entity_decode("Fecha Nacimiento"),"BR",0,"C",0,0);
        $formu->Cell(10,25,html_entity_decode("Sexo"),"BR",0,"C",0,0);
        $formu->Cell(30,5,html_entity_decode("Parentesco"),"BR",0,"C",0,0);
        $formu->Cell(35,5,html_entity_decode("Condicion u Ocupacion"),"BR",1,"C",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(40,5,html_entity_decode("Tipo Documento"),"LBR",0,"C",0,0);
        $formu->Cell(20,5,html_entity_decode(""),"LR",0,"C",0,0);
        $formu->Cell(135,5,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(12,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(11,5,html_entity_decode("P.Prim"),"B",0,"C",0,0);
        $formu->Cell(12,5,html_entity_decode(""),"LR",1,"C",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(10,5,html_entity_decode("C.C"),"LR",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode("T.I"),"R",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode("R.C"),"R",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode("C.E"),"R",0,"C",0,0);
        $formu->Cell(20,5,html_entity_decode("N&uacute;mero"),"",0,"C",0,0);
        $formu->Cell(140,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(12,5,html_entity_decode("Estudia"),"R",0,"C",0,0);
        $formu->Cell(11,5,html_entity_decode("S.Seg"),"B",0,"C",0,0);
        $formu->SetFont('Arial','','5');
        $formu->Cell(12,5,html_entity_decode("Discapacidad"),"LR",1,"C",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"LR",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode("o"),"R",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(160,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(12,5,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(11,5,html_entity_decode("T.Tec"),"B",0,"C",0,0);
        $formu->Cell(12,5,html_entity_decode(""),"LR",1,"C",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode("NUIP"),"RB",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(160,5,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(6,5,html_entity_decode("SI"),"RB",0,"C",0,0);
        $formu->Cell(6,5,html_entity_decode("NO"),"RB",0,"C",0,0);
        $formu->Cell(11,5,html_entity_decode("U.Univ"),"BB",0,"C",0,0);
        $formu->Cell(12,5,html_entity_decode(""),"LBR",1,"C",0,0);
        $formu->Image('public/img/form/conyugue.jpg',216,100,3,10);
        $formu->Image('public/img/form/compe.jpg',221,95,3,17);
        $formu->Image('public/img/form/hijo.jpg',226,100,3,7);
        $formu->Image('public/img/form/hijastro.jpg',231,100,3,10);
        $formu->Image('public/img/form/padre.jpg',236,100,3,10);
        $formu->Image('public/img/form/hemhu.jpg',241,93,3,18);
	for ($i = 1; $i <= 6; $i++) {
        $formu->Cell(10,4,"","",0,"",0,0);
        $formu->Cell(10,4,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(10,4,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(10,4,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(10,4,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(20,4,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(25,4,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(25,4,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(25,4,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(25,4,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(8,4,html_entity_decode("D"),"BR",0,"C",0,0);
        $formu->Cell(8,4,html_entity_decode("M"),"BR",0,"C",0,0);
        $formu->Cell(9,4,html_entity_decode("A"),"BR",0,"C",0,0);
        $formu->Cell(5,4,html_entity_decode("F"),"BR",0,"C",0,0);
        $formu->Cell(5,4,html_entity_decode("M"),"BR",0,"C",0,0);
        $formu->Cell(5,4,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(5,4,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(5,4,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(5,4,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(5,4,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(5,4,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(6,4,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(6,4,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(11,4,html_entity_decode(""),"BB",0,"C",0,0);
        $formu->Cell(12,4,html_entity_decode(""),"LBR",1,"C",0,0);
	}
        $formu->Cell(10,2,"","",0,"",0,0);
        $formu->MultiCell(260,4,html_entity_decode("Declaro Bajo la Gravedad de juramento que: Toda la informaci&oacute;n aqui suminitrada es veridica. Autorizo a Comfamiliar para que por cualquier medio verifique los datos aqui contenidos y que en caso de falsedad se apliquen las sanciones contempladas. "),"LBR","L",0,0);
        $formu->Cell(10,1,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(100,1,html_entity_decode(""),"LR",0,"C",0,0);
        $formu->Cell(60,1,html_entity_decode(""),"LR",0,"C",0,0);
        $formu->Cell(50,1,html_entity_decode(""),"LR",0,"C",0,0);
        $formu->Cell(50,1,html_entity_decode(""),"LR",1,"C",0,0);
        $formu->Cell(10,3,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(100,3,html_entity_decode(""),"LR",0,"L",0,0);
        $formu->Cell(60,3,html_entity_decode("N&uacute;mero de radicado"),"LR",0,"L",0,0);
        $formu->Cell(50,3,html_entity_decode("Recibido por :"),"LR",0,"L",0,0);
        $formu->Cell(50,3,html_entity_decode("Grabado por :"),"LR",1,"L",0,0);
        $formu->Cell(10,3,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(100,3,html_entity_decode("Firma y C&eacute;dula del Afiliado Independiente:"),"LR",0,"L",0,0);
        $formu->Cell(60,3,html_entity_decode(""),"LR",0,"L",0,0);
        $formu->Cell(50,3,html_entity_decode(""),"LR",0,"L",0,0);
        $formu->Cell(50,3,html_entity_decode(""),"LR",1,"L",0,0);
        $formu->Cell(10,1,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(100,1,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(60,1,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(50,1,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(50,1,html_entity_decode(""),"LBR",1,"C",0,0);
        $formu->Cell(10,1,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(260,3,"-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------","LR",1,"L",0,0);
        $y=$formu->getY();
        $formu->Cell(10,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(53,5,html_entity_decode(""),0,0,"C",0,0);
        $formu->Image('public/img/comfamiliar-logo.jpg',22,$y,53,11);
        $formu->Cell(35,5,html_entity_decode("Nombre del Afiliado Independiente:"),0,0,"L",0,0);
        $formu->Cell(35,5,"","B",0,"L",0,0);
        $formu->Cell(2,5,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(20,5,html_entity_decode("Recibido por:"),0,0,"L",0,0);
        $formu->Cell(45,5,"","B",0,"L",0,0);
        $formu->Cell(2,5,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(20,5,html_entity_decode("Grabado por:"),0,0,"L",0,0);
        $formu->Cell(45,5,"","B",0,"L",0,0);
        $formu->Cell(3,5,html_entity_decode(""),"R",1,"C",0,0);

        $formu->Cell(10,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(53,5,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(55,5,html_entity_decode("N&uacute;mero de C&eacute;dula del Afiliado independiente:"),0,0,"L",0,0);
        $formu->Cell(60,5,"","B",0,"L",0,0);
        $formu->Cell(2,5,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(30,5,html_entity_decode("Fecha de Recibido:"),0,0,"L",0,0);
        $formu->Cell(57,5,"","B",0,"L",0,0);
        $formu->Cell(3,5,html_entity_decode(""),"R",1,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(260,2,html_entity_decode(""),"LR",1,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(20,4,html_entity_decode(""),0,0,"C",0,0);
        $formu->SetFont('Arial','','7');
        $formu->Cell(20,5,html_entity_decode("Porcentaje aporte"),1,0,"C",0,0);
        $formu->SetFont('Arial','B','8');
        $formu->Cell(50,4,html_entity_decode("CAUSALES DE"),0,0,"C",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(10,4,html_entity_decode(""),1,0,"C",0,0);
        $formu->Cell(50,4,html_entity_decode("Fotocopia C&eacute;dula del Pencionado"),0,0,"C",0,0);
        $formu->Cell(10,4,html_entity_decode(""),1,0,"C",0,0);
        $formu->Cell(50,4,html_entity_decode("Documento ilegible. Cual?"),0,0,"C",0,0);
        $formu->Cell(48,4,html_entity_decode(""),"B",0,"C",0,0);
        $formu->Cell(2,4,html_entity_decode(""),"R",1,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(260,1,html_entity_decode(""),"LR",1,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(20,4,html_entity_decode(""),0,0,"C",0,0);
        $formu->SetFont('Arial','','7');
        $formu->Cell(10,4,html_entity_decode("2 %"),"LRB",0,"C",0,0);
        $formu->Cell(10,4,html_entity_decode("0.6 %"),"RB",0,"C",0,0);
        $formu->SetFont('Arial','B','8');
        $formu->Cell(50,4,html_entity_decode("DEVOLUCI&Oacute;N"),0,0,"C",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(10,4,html_entity_decode(""),1,0,"C",0,0);
        $formu->Cell(50,4,html_entity_decode("Fotocopia C&eacute;dula del C&oacute;nyuge"),0,0,"C",0,0);
        $formu->Cell(10,4,html_entity_decode(""),1,0,"C",0,0);
        $formu->Cell(50,4,html_entity_decode("Registro Civil. Cual?"),0,0,"C",0,0);
        $formu->Cell(48,4,html_entity_decode(""),"B",0,"C",0,0);
        $formu->Cell(2,4,html_entity_decode(""),"R",1,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(260,1,html_entity_decode(""),"LBR",1,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),0,0,"C",0,0);
        $formu->SetFont('Arial','B','10');
        $formu->Cell(260,8,html_entity_decode("Afiliaci&oacute;n del trabajador Independiente o Desempleado y su Grupo Familiar"),"LRB",1,"C",0,0);

        $formu->AddPage();
	$formu->setTextColor(0);
        $formu->SetFont('Arial','B','12');
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(260,5,html_entity_decode("DOCUMENTOS NECESARIOS PARA LA AFILIACI&Oacute;N"),1,1,"C",0,0);
        $formu->SetFont('Arial','B','10');
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("Importante"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("Hermano Hu&eacute;rfanos de padres"),"R",1,"L",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Diligenciar este formato con letra clara y utilizar tinta de color negro"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Fotocopia del folio del registro civil de nacimiento del trabajador"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  No escribir en los espacios sombreados, ni utilizar resaltador en las casillas"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Fotocopia del folio del registro civil de nacimiento de los hermanos huerfanos de padre"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Los documentos probatorios que se anexan, deben ser legibles,sin enmendaduras y sin resaltador"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Fotocopia de la partida de defunci&oacute;n o registro civil de defunci&oacute;n de los padres "),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Despu&eacute;s de diligenciar el formato, coloque  los documentos probatorios"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Constancia de dependencia economica y convivencia, a trav&eacute;s de la cual se manifiesta que los"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode(""),"BR",0,"L",0,0);
        $formu->Cell(1,3,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(129,3,html_entity_decode("   hermanos hu&eacute;rfanos  de padres conviven y dependen econ&oacute;micamente del trabajador y que"),"R",1,"L",0,0);
        $formu->Cell(10,3,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,8,html_entity_decode(""),"L",0,"C",0,0);
        $formu->SetFont('Arial','B','10');
        $formu->Cell(129,8,html_entity_decode("Documentos para el pensionado Unicamente"),"R",0,"L",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(1,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(129,8,html_entity_decode("   ninguna otra persona los tiene afiliado a Comfamiliar o a otra caja de compensaci&oacute;n familiar o"),"R",1,"L",0,0);
        $formu->Cell(10,8,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Fotocopia de  la Cedula de ciudadania o de extranjeria. Si este(a) es menor de edad,fotocopia"),"R",0,"L",0,0);
        $formu->Cell(1,1,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(129,1,html_entity_decode("   entidad similar, Podr&aacute; diligenciar el formato que Comfamiliar tiene establecido para tal fin "),"R",1,"L",0,0);
        $formu->Cell(10,1,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,1,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,1,html_entity_decode(""),"R",0,"L",0,0);
        $formu->Cell(1,2,html_entity_decode(""),"B",0,"C",0,0);
        $formu->Cell(129,2,html_entity_decode(""),"RB",1,"L",0,0);
        $formu->Cell(10,8,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,8,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,8,html_entity_decode("*  Fotocopia de la resoluci&oacute;n que reconoce la lacalidad de pensionado o certificado de la entidad "),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->SetFont('Arial','B','10');
        $formu->Cell(129,8,html_entity_decode("Padres"),"R",1,"L",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(10,1,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,1,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,1,html_entity_decode("   pagadora o la ultima colilla de pago"),"R",0,"L",0,0);
        $formu->Cell(1,1,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(129,1,html_entity_decode("*  Fotocopia del folio del registro civil de nacimineto  del trabajador"),"R",1,"L",0,0);
        $formu->Cell(10,2,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,8,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,2,html_entity_decode(""),"R",0,"L",0,0);
        $formu->Cell(1,8,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,8,html_entity_decode("*  Fotocopia del folio del registro civil de nacimineto  del trabajador donde consten sus padres"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->SetFont('Arial','B','10');
        $formu->Cell(129,5,html_entity_decode("Conyuge"),"TR",0,"L",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Constancia de dependencia econ&oacute;nomica y convivencia, a trav&eacute;s de la caul manifiesta que los"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("* Fotocopia del folio del registro civil de matrimonio o partida eclesiastica de matrimonio del trabajador"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   hermnaos hu&eacute;rfanos de padres conviven y dependen econ&oacute;nomicamente del trabajador y que"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("* Fotocopia de la c&eacute;dula de ciudadania. Si este(a) es menor de edad, fotocopia de la tarjeta de"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   ninguna otra persona los tiene afiliados a Comfamiliar o a otra caja de compensacion familiar"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("  identidad"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   o entidad similar. Podr&aacute; diligenciar el formato que Comfamiliar tiene establecido para tal fin"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("* Si el trabajador ya tiene registrada a su conyuge y esta afiliado a una nueva pareja, debera"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"BL",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode(""),"RB",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("  presentar constancia de divorcio"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->SetFont('Arial','B','10');
        $formu->Cell(129,5,html_entity_decode("Discapacidad"),"R",1,"L",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode(""),"RB",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Los padres, los hermanos hu&eacute;rfanos de padres y los hijos, que sean inv&aacute;lidos o de capacidad"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->SetFont('Arial','B','10');
        $formu->Cell(129,5,html_entity_decode("Compa&ntilde;era(o) Permanente"),"R",0,"L",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   fisica disminuida que le impida trabajar, sin limitaci&oacute;n en raz&oacute;n de su edad, para ello, el trabajador"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Fotocopia de la c&eacute;dula de ciudadania de la compa&ntilde;era permanente. si esta(a) es menor de edad"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   indenpendiente debe demostrar la convivencia y dependencia economica de la persona a cargo"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   fotocopia de la tarjeta de identidad"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   discapacitada, ademas presentar constancia  emitidas por la entidad Promotora de Salud-EPS de la"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Acreditaci&oacute;n como compa&ntilde;era(o) permanente. Podr&aacute; diligenciar el formateo que comfamiliar tiene"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   IPS de la red p&uacute;blica se salud (Decreto 1335 de 2008 y 4942 de 2009) o de la Junta Regional de"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   establecida para tal fin"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   Calificaci&oacute;n de Invalidez(Decreto 2463 del 20 de noviembre de 2001). Con el fin de acreditar la"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Si el trabajador ya tiene registrada a su conyuge o cpmpa&ntilde;era(o) y esta afiliando una nueva pareja, "),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   invalidez o disminuci&oacute;n fisica que le impida trabajar"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   deber&aacute; presentar la constancia de divorcio si es casado o carta personal firmada bajo gravedad"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  La dependencia econ&oacute;mica y convivencia de la persona a cargo discapacitada se podr&aacute; demostrar "),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   de juramenton en la cual manifiesta con quien esta conviviendo en forma permanente"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   diligenciando el formato que Comfamiliar tiene establecido para tal fin"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode(""),"RB",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode(""),"RB",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->SetFont('Arial','B','10');
        $formu->Cell(129,5,html_entity_decode("Hijos (Legitimos o Extramatrimoniales) e Hijastros"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(29,5,html_entity_decode("Observaciones"),"",0,"L",0,0);
        $formu->Cell(95,5,html_entity_decode(""),"B",0,"L",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",1,"L",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Fotocopia del folio del registro civil de nacimiento de los hijos o hijastros"),"R",0,"L",0,0);
        $formu->Cell(4,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(121,5,html_entity_decode(""),"B",0,"L",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Fotocopia de la tarjeta de identidad(mayores de 7 a&ntilde;os)"),"R",0,"L",0,0);
        $formu->Cell(4,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(121,5,html_entity_decode(""),"B",0,"L",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode(""),"BR",0,"L",0,0);
        $formu->Cell(4,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(121,5,html_entity_decode(""),"B",0,"L",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->SetFont('Arial','B','10');
        $formu->Cell(129,5,html_entity_decode("Para los Hijastros, deber&aacute; anexar adem&aacute;s"),"R",0,"L",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(4,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(121,5,html_entity_decode(""),"B",0,"L",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Constacia de dependencia econ&oacute;mica o convivencia, a trav&eacute;s de la cual de manifiesta que los"),"R",0,"L",0,0);
        $formu->Cell(4,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(121,5,html_entity_decode(""),"B",0,"L",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   hijastros conviven y dependes econ&oacute;nomicamente del trabajador. Podr&aacute; diligenciar el formato"),"R",0,"L",0,0);
        $formu->Cell(4,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(121,5,html_entity_decode(""),"B",0,"L",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",1,"L",0,0);
        $formu->Cell(10,3,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,3,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(129,3,html_entity_decode("   que comfamiliar tiene establecido para tal fin"),"RB",0,"L",0,0);
        $formu->Cell(4,3,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(121,3,html_entity_decode(""),"B",0,"L",0,0);
        $formu->Cell(5,3,html_entity_decode(""),"RB",1,"L",0,0);

/*
        $formu->Cell(10,1,html_entity_decode(""),"T",1,"C",0,0);

        $formu->Cell(10,5,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(90,5,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"LR",0,"C",0,0);
        $formu->Cell(50,5,html_entity_decode("Fotocopia C&eacute;dula del Pens&iacute;onado"),0,0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"RL",0,"C",0,0);
        $formu->Cell(50,5,html_entity_decode("Documento ilegible.Cual?"),0,0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"B",0,"C",0,0);
*/
        $file = "public/temp/reportes/trabajadorpens_.pdf";
        //$response = parent::successFunc("Genera Formulario",$file);
        ob_clean();
        $formu->Output( $file,"I");
        //return $this->renderText(json_encode($response)); 
   }


//Formulario Pensionado
    public function addpensionadoAction(){
        $formu = new FPDF('L','mm','A4');
        $formu->AddPage();
	    $formu->setTextColor(0);
        $formu->SetFont('Arial','','25');
        $this->setResponse('view');
        $formu->Image('public/img/comfamiliar-logo.jpg',20,15,42,15);
        $formu->Image('public/img/piePagCart/foot3.png',250,15,27,15);
        $formu->SetTextColor(0);    
        $formu->SetFont('Arial','B','12');
        $formu->Cell(10,5,"",0,0,"L",0,0);
        $formu->Cell(260,5,"","LTR",1,"C",0,0);
        $formu->Cell(10,5,"",0,0,"L",0,0);
        $formu->Cell(10,5,"","L",0,"L",0,0);
        $formu->Cell(250,5,html_entity_decode("AFILIACI&Oacute;N  DE PENSIONADOS AL SISTEMA DE CAJAS DE  COMPENSACION FAMILIAR"),"R",1,"C",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(10,5,"",0,0,"L",0,0);
        $formu->Cell(10,5,"","L",0,"L",0,0);
        $formu->Cell(250,5,html_entity_decode("Favor elaborar este formulario con letra imprenta y utilizar tinta de color negro"),"R",1,"C",0,0);
        $formu->Cell(10,5,"",0,0,"L",0,0);
        $formu->Cell(10,5,"","L",0,"L",0,0);
        $formu->Cell(250,3,html_entity_decode("Los pensionados no seran beneficiarios de la cuota monetaria del Subsidio Familiar"),"R",1,"C",0,0);
        $formu->Cell(10,3,"",0,0,"L",0,0);
        $formu->Cell(260,3,"","LRB",1,"C",0,0);
        $formu->SetFont('Arial','B','8');
        $formu->Cell(10,3,"",0,0,"L",0,0);
        $formu->Cell(260,5,html_entity_decode("Seleccione el tipo de afiliac&oacute;n como pensionado"),"",1,"C",0,0);
        $formu->SetFont('Arial','','5.8');
        $formu->Cell(10,5,"",0,0,"L",0,0);
        $formu->Cell(3,3,"",1,0,"",0,0);
        $formu->Cell(67,3,html_entity_decode("Pensionado con aportes 2%. Afiliacion para todos los servic&iacute;o de la caja"),0,0,"L",0,0);
        $formu->Cell(3,3,"",1,0,"",0,0);
        $formu->Cell(89,3,html_entity_decode("Pensionado de 25 a&ntilde;os. Afiliaci&oacute;n para los servicios de recreacion, capacitaci&oacute;n y turismo social"),0,0,"L",0,0);
        $formu->Cell(3,3,"",1,0,"",0,0);
        $formu->Cell(105,3,html_entity_decode("Pensionados r&eacute;egimen especial(1.5$ MLMV) Afiliaci&oacute;n para los servicios de recreacion, deporte y cultura"),0,1,"L",0,0);
	//Hasta aca va la cabecera
        $formu->SetFont('Arial','B','10');
        $formu->Cell(10,1,"","",1,"",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->SetFillColor(236,248,240);
        $formu->Cell(260,5,html_entity_decode("Datos del Pensionado"),1,1,"L",1,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(80,5,html_entity_decode("Identificaci&oacute;n"),"LB",0,"C",0,0);
        $formu->Cell(25,10,html_entity_decode("N&uacute;mero"),"LRB",0,"C",0,0);
        $formu->Cell(25,10,html_entity_decode("Primer Apellido"),"RB",0,"C",0,0);
        $formu->Cell(25,10,html_entity_decode("Segundo Apellido"),"RB",0,"C",0,0);
        $formu->Cell(25,10,html_entity_decode("Primer Nombre"),"RB",0,"C",0,0);
        $formu->Cell(25,10,html_entity_decode("Segundo Nombre"),"RB",0,"C",0,0);
        $formu->Cell(55,5,html_entity_decode("Estado Civil"),"RB",1,"C",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->SetFont('Arial','','6.5');
        $formu->Cell(80,2,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(180,2,html_entity_decode(""),"R",1,"C",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(27,3,html_entity_decode("Cedula de Ciudadania"),"L",0,"L",0,0);
        $formu->Cell(3,3,html_entity_decode(""),1,0,"L",0,0);
        $formu->Cell(5,3,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(27,3,html_entity_decode("Tarjeta de Identidad"),"",0,"L",0,0);
        $formu->Cell(3,3,html_entity_decode(""),1,0,"L",0,0);
        $formu->Cell(140,3,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(15,3,html_entity_decode("Soltero"),"",0,"C",0,0);
        $formu->Cell(3,3,html_entity_decode(""),1,0,"L",0,0);
        $formu->Cell(5,3,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(25,3,html_entity_decode("Union Libre"),"",0,"L",0,0);
        $formu->Cell(3,3,html_entity_decode(""),1,0,"L",0,0);
        $formu->Cell(4,3,html_entity_decode(""),"R",1,"L",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(80,2,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(25,6,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(25,6,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(25,6,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(25,6,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(25,6,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(55,2,html_entity_decode(""),"R",1,"C",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(27,3,html_entity_decode("Registro Civil o NUIP"),"L",0,"L",0,0);
        $formu->Cell(3,3,html_entity_decode(""),1,0,"L",0,0);
        $formu->Cell(5,3,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(27,3,html_entity_decode("Cedula Extranjera"),"",0,"L",0,0);
        $formu->Cell(3,3,html_entity_decode(""),1,0,"L",0,0);
        $formu->Cell(140,3,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(15,3,html_entity_decode("Casado"),"",0,"C",0,0);
        $formu->Cell(3,3,html_entity_decode(""),1,0,"L",0,0);
        $formu->Cell(5,3,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(25,3,html_entity_decode("Separado"),"",0,"L",0,0);
        $formu->Cell(3,3,html_entity_decode(""),1,0,"L",0,0);
        $formu->Cell(4,3,html_entity_decode(""),"R",1,"L",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(80,1,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(125,1,html_entity_decode(""),0,0,"L",0,0);
        $formu->Cell(55,1,html_entity_decode(""),"BR",1,"L",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(50,6,html_entity_decode("Fecha Nacimiento"),"LBR",0,"C",0,0);
        $formu->Cell(30,6,html_entity_decode("Sexo"),"R",0,"C",0,0);
        $formu->Cell(25,6,html_entity_decode("Valor Pension Mensual"),"R",0,"C",0,0);
        $formu->Cell(25,6,html_entity_decode("Entidad Pagadora"),"R",0,"C",0,0);
        $formu->Cell(25,6,html_entity_decode("Nit"),"R",0,"C",0,0);
        $formu->Cell(25,6,html_entity_decode("Direcci&oacute;n"),"R",0,"C",0,0);
        $formu->Cell(50,6,html_entity_decode("No. Resoluci&oacute;n"),"R",0,"C",0,0);
        $formu->Cell(30,6,html_entity_decode("Fecha Resoluci&oacute;n"),"RB",1,"C",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(15,5,html_entity_decode("D"),"LR",0,"C",0,0);
        $formu->Cell(15,5,html_entity_decode("M"),"R",0,"C",0,0);
        $formu->Cell(20,5,html_entity_decode("A"),"R",0,"C",0,0);
        $formu->Cell(7,4,html_entity_decode("F"),0,0,"R",0,0);
        $formu->Cell(4,4,html_entity_decode(""),1,0,"C",0,0);
        $formu->Cell(9,4,html_entity_decode("M"),0,0,"R",0,0);
        $formu->Cell(4,4,html_entity_decode(""),1,0,"C",0,0);
        $formu->Cell(6,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(25,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(25,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(25,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(25,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(50,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode("D"),"R",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode("M"),"R",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode("A"),"R",1,"C",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(260,0,html_entity_decode(""),"B",1,"C",0,0);
        $formu->Cell(10,2,"","",1,"",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->SetFont('Arial','B','9.5');
        $formu->Cell(260,4.3,html_entity_decode("Datos del Grupo Familiar que va a Afiliar(Cuando un trabajador tenga m&aacute;s de un grupo faamiliar debera digilenciar un formato de afiliacion para cada grupo)"),1,1,"L",1,0);
        $formu->SetFont('Arial','','6');
        $formu->Cell(10,4.3,"","",0,"",0,0);
        $formu->Cell(40,4.3,html_entity_decode("Num. De Personas Grupo Familiar"),"LBR",0,"C",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(60,4.3,html_entity_decode("Direccion Residencia"),"BR",0,"C",0,0);
        $formu->Cell(40,4.3,html_entity_decode("Departamento"),"BR",0,"C",0,0);
        $formu->Cell(40,4.3,html_entity_decode("Ciudad"),"BR",0,"C",0,0);
        $formu->Cell(40,4.3,html_entity_decode("Barrio"),"BR",0,"C",0,0);
        $formu->Cell(40,4.3,html_entity_decode("Tel&eacute;fono"),"BR",1,"C",0,0);
        $formu->Cell(10,4.3,"","",0,"",0,0);
        $formu->Cell(40,4.3,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(60,4.3,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(40,4.3,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(40,4.3,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(40,4.3,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(40,4.3,html_entity_decode(""),"LBR",1,"C",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(10,2,"","",1,"",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->SetFont('Arial','B','9.5');
        $formu->Cell(260,5,html_entity_decode("Inscripcion del Grupo Familiar. Incluir C&oacute;yuge o compa&ntilde;ero(a) Permanente"),1,1,"L",1,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(60,5,html_entity_decode("Identificaci&oacute;n"),"LB",0,"C",0,0);
        $formu->Cell(25,25,html_entity_decode("Primer Apellido"),"LBR",0,"C",0,0);
        $formu->Cell(25,25,html_entity_decode("Segundo Apellido"),"BR",0,"C",0,0);
        $formu->Cell(25,25,html_entity_decode("Primer Nombre"),"BR",0,"C",0,0);
        $formu->Cell(25,25,html_entity_decode("Segundo Nombre"),"BR",0,"C",0,0);
        $formu->Cell(25,25,html_entity_decode("Fecha Nacimiento"),"BR",0,"C",0,0);
        $formu->Cell(10,25,html_entity_decode("Sexo"),"BR",0,"C",0,0);
        $formu->Cell(30,5,html_entity_decode("Parentesco"),"BR",0,"C",0,0);
        $formu->Cell(35,5,html_entity_decode("Condicion u Ocupacion"),"BR",1,"C",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(40,5,html_entity_decode("Tipo Documento"),"LBR",0,"C",0,0);
        $formu->Cell(20,5,html_entity_decode(""),"LR",0,"C",0,0);
        $formu->Cell(135,5,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(12,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(11,5,html_entity_decode("P.Prim"),"B",0,"C",0,0);
        $formu->Cell(12,5,html_entity_decode(""),"LR",1,"C",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(10,5,html_entity_decode("C.C"),"LR",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode("T.I"),"R",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode("R.C"),"R",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode("C.E"),"R",0,"C",0,0);
        $formu->Cell(20,5,html_entity_decode("N&uacute;mero"),"",0,"C",0,0);
        $formu->Cell(140,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(12,5,html_entity_decode("Estudia"),"R",0,"C",0,0);
        $formu->Cell(11,5,html_entity_decode("S.Seg"),"B",0,"C",0,0);
        $formu->SetFont('Arial','','5');
        $formu->Cell(12,5,html_entity_decode("Discapacidad"),"LR",1,"C",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"LR",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode("o"),"R",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(160,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(12,5,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(11,5,html_entity_decode("T.Tec"),"B",0,"C",0,0);
        $formu->Cell(12,5,html_entity_decode(""),"LR",1,"C",0,0);
        $formu->Cell(10,5,"","",0,"",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode("NUIP"),"RB",0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(160,5,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(6,5,html_entity_decode("SI"),"RB",0,"C",0,0);
        $formu->Cell(6,5,html_entity_decode("NO"),"RB",0,"C",0,0);
        $formu->Cell(11,5,html_entity_decode("U.Univ"),"BB",0,"C",0,0);
        $formu->Cell(12,5,html_entity_decode(""),"LBR",1,"C",0,0);
        $formu->Image('public/img/form/conyugue.jpg',216,105,3,10);
        $formu->Image('public/img/form/compe.jpg',221,100,3,17);
        $formu->Image('public/img/form/hijo.jpg',226,105,3,7);
        $formu->Image('public/img/form/hijastro.jpg',231,105,3,10);
        $formu->Image('public/img/form/padre.jpg',236,105,3,10);
        $formu->Image('public/img/form/hemhu.jpg',241,100,3,18);
	for ($i = 1; $i <= 5; $i++) {
        $formu->Cell(10,3,"","",0,"",0,0);
        $formu->Cell(10,3,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(10,3,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(10,3,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(10,3,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(20,3,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(25,3,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(25,3,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(25,3,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(25,3,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(8,3,html_entity_decode("D"),"BR",0,"C",0,0);
        $formu->Cell(8,3,html_entity_decode("M"),"BR",0,"C",0,0);
        $formu->Cell(9,3,html_entity_decode("A"),"BR",0,"C",0,0);
        $formu->Cell(5,3,html_entity_decode("F"),"BR",0,"C",0,0);
        $formu->Cell(5,3,html_entity_decode("M"),"BR",0,"C",0,0);
        $formu->Cell(5,3,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(5,3,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(5,3,html_entity_decode(""),"BR",0,"C",0,0);
        $formu->Cell(5,3,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(5,3,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(5,3,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(6,3,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(6,3,html_entity_decode(""),"RB",0,"C",0,0);
        $formu->Cell(11,3,html_entity_decode(""),"BB",0,"C",0,0);
        $formu->Cell(12,3,html_entity_decode(""),"LBR",1,"C",0,0);
	}
        $formu->Cell(10,2,"","",0,"",0,0);
        $formu->MultiCell(260,4,html_entity_decode("Autorizo al pagador de _________________________________ para que me sea deducido el 2% de mi mesada con destino a la Caja de Compensaci&oacute;n Comfamiliar, a fin de cubrir el aporte mensual de mi afiliaci&oacute;n, en cumplimiento del articulo 5 de la ley 71 de 1988 y articulo 22 y 34 del decreto 784 de 1989."),"LBR","L",0,0);
        $formu->Cell(10,2,"","",0,"",0,0);
        $formu->MultiCell(260,4,html_entity_decode("Declaro BAjo la Gravedad de juramento que: Toda la informaci&oacute;n aqui suminitrada es veridica. Autorizo a Comfamiliar para que por cualquier medio verifique los datos aqui contenidos y que en caso de falsedad se apliquen las sanciones contempladas. "),"LBR","L",0,0);
        $formu->Cell(10,1,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(100,1,html_entity_decode(""),"LR",0,"C",0,0);
        $formu->Cell(60,1,html_entity_decode(""),"LR",0,"C",0,0);
        $formu->Cell(50,1,html_entity_decode(""),"LR",0,"C",0,0);
        $formu->Cell(50,1,html_entity_decode(""),"LR",1,"C",0,0);
        $formu->Cell(10,3,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(100,3,html_entity_decode(""),"LR",0,"L",0,0);
        $formu->Cell(60,3,html_entity_decode("N&uacute;mero de radicado"),"LR",0,"L",0,0);
        $formu->Cell(50,3,html_entity_decode("Recibido por :"),"LR",0,"L",0,0);
        $formu->Cell(50,3,html_entity_decode("Grabado por :"),"LR",1,"L",0,0);
        $formu->Cell(10,3,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(100,3,html_entity_decode("Firma y C&eacute;dula del Pensionado:"),"LR",0,"L",0,0);
        $formu->Cell(60,3,html_entity_decode(""),"LR",0,"L",0,0);
        $formu->Cell(50,3,html_entity_decode(""),"LR",0,"L",0,0);
        $formu->Cell(50,3,html_entity_decode(""),"LR",1,"L",0,0);
        $formu->Cell(10,1,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(100,1,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(60,1,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(50,1,html_entity_decode(""),"LBR",0,"C",0,0);
        $formu->Cell(50,1,html_entity_decode(""),"LBR",1,"C",0,0);
        $formu->Cell(10,1,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(260,3,"-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------","LR",1,"L",0,0);
        $y=$formu->getY();
        $formu->Cell(10,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(53,5,html_entity_decode(""),0,0,"C",0,0);
        $formu->Image('public/img/comfamiliar-logo.jpg',22,$y,53,15);
        $formu->Cell(35,5,html_entity_decode("Nombre del Pensionado:"),0,0,"L",0,0);
        $formu->Cell(35,5,"","B",0,"L",0,0);
        $formu->Cell(2,5,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(20,5,html_entity_decode("Recibido por:"),0,0,"L",0,0);
        $formu->Cell(45,5,"","B",0,"L",0,0);
        $formu->Cell(2,5,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(20,5,html_entity_decode("Grabado por:"),0,0,"L",0,0);
        $formu->Cell(45,5,"","B",0,"L",0,0);
        $formu->Cell(3,5,html_entity_decode(""),"R",1,"C",0,0);

        $formu->Cell(10,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(53,5,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(55,5,html_entity_decode("N&uacute;mero de C&eacute;dula del Pensionado:"),0,0,"L",0,0);
        $formu->Cell(60,5,"","B",0,"L",0,0);
        $formu->Cell(2,5,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(30,5,html_entity_decode("Fecha de Recibido:"),0,0,"L",0,0);
        $formu->Cell(57,5,"","B",0,"L",0,0);
        $formu->Cell(3,5,html_entity_decode(""),"R",1,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(260,2,html_entity_decode(""),"LR",1,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(90,1,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(10,4,html_entity_decode(""),1,0,"C",0,0);
        $formu->Cell(50,4,html_entity_decode("Fotocopia C&eacute;dula del Pencionado"),0,0,"C",0,0);
        $formu->Cell(10,4,html_entity_decode(""),1,0,"C",0,0);
        $formu->Cell(50,4,html_entity_decode("Documento ilegible. Cual?"),0,0,"C",0,0);
        $formu->Cell(48,4,html_entity_decode(""),"B",0,"C",0,0);
        $formu->Cell(2,4,html_entity_decode(""),"R",1,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(260,1,html_entity_decode(""),"LR",1,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(90,1,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(10,4,html_entity_decode(""),1,0,"C",0,0);
        $formu->Cell(50,4,html_entity_decode("Fotocopia C&eacute;dula del C&oacute;nyuge"),0,0,"C",0,0);
        $formu->Cell(10,4,html_entity_decode(""),1,0,"C",0,0);
        $formu->Cell(50,4,html_entity_decode("Registro Civil. Cual?"),0,0,"C",0,0);
        $formu->Cell(48,4,html_entity_decode(""),"B",0,"C",0,0);
        $formu->Cell(2,4,html_entity_decode(""),"R",1,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(260,1,html_entity_decode(""),"LBR",1,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(260,3,html_entity_decode("Seleccione el tipo de afiliaci&oacute;n como Pencionado"),"LR",1,"C",0,0);
        $formu->Cell(10,2,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(1,2,html_entity_decode(""),"R",0,"C",0,0);
        $formu->Cell(3,2,html_entity_decode(""),1,0,"C",0,0);
        $formu->SetFont('Arial','','5.6');
        $formu->Cell(68,2,html_entity_decode("Pensionado con aportes del 2%. Afiliaci&oacute;n para todos los servicios de Caja"),0,0,"R",0,0);
        $formu->Cell(1,2,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(3,2,html_entity_decode(""),1,0,"C",0,0);
        $formu->Cell(86,2,html_entity_decode("Pensionado de 25 a&ntilde;os. Afiliaci&oacute;n para los servicios de recreacion, capacitaci&oacute;n y turismo social"),0,0,"R",0,0);
        $formu->Cell(1,2,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(3,2,html_entity_decode(""),1,0,"C",0,0);
        $formu->Cell(93,2,html_entity_decode("Pensionados r&eacute;gimen especial(1.5 SMLMV) Afiliaci&oacute;n para los servicios de recreacion, deporte y cultura"),0,0,"R",0,0);
        $formu->Cell(1,2,html_entity_decode(""),"R",1,"C",0,0);
        $formu->Cell(10,1,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(260,1,html_entity_decode(""),"LRB",1,"C",0,0);

        $formu->AddPage();
	$formu->setTextColor(0);
        $formu->SetFont('Arial','B','12');
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(260,5,html_entity_decode("DOCUMENTOS NECESARIOS PARA LA AFILIACI&Oacute;N"),1,1,"C",0,0);
        $formu->SetFont('Arial','B','10');
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("Importante"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("Hermano Hu&eacute;rfanos de padres"),"R",1,"L",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Diligenciar este formato con letra clara y utilizar tinta de color negro"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Fotocopia del folio del registro civil de nacimiento del trabajador"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  No escribir en los espacios sombreados, ni utilizar resaltador en las casillas"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Fotocopia del folio del registro civil de nacimiento de los hermanos huerfanos de padre"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Los documentos probatorios que se anexan, deben ser legibles,sin enmendaduras y sin resaltador"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Fotocopia de la partida de defunci&oacute;n o registro civil de defunci&oacute;n de los padres "),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Despu&eacute;s de diligenciar el formato, coloque  los documentos probatorios"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Constancia de dependencia economica y convivencia, a trav&eacute;s de la cual se manifiesta que los"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode(""),"BR",0,"L",0,0);
        $formu->Cell(1,3,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(129,3,html_entity_decode("   hermanos hu&eacute;rfanos  de padres conviven y dependen econ&oacute;micamente del trabajador y que"),"R",1,"L",0,0);
        $formu->Cell(10,3,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,8,html_entity_decode(""),"L",0,"C",0,0);
        $formu->SetFont('Arial','B','10');
        $formu->Cell(129,8,html_entity_decode("Documentos para el pensionado Unicamente"),"R",0,"L",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(1,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(129,8,html_entity_decode("   ninguna otra persona los tiene afiliado a Comfamiliar o a otra caja de compensaci&oacute;n familiar o"),"R",1,"L",0,0);
        $formu->Cell(10,8,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Fotocopia de  la Cedula de ciudadania o de extranjeria. Si este(a) es menor de edad,fotocopia"),"R",0,"L",0,0);
        $formu->Cell(1,1,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(129,1,html_entity_decode("   entidad similar, Podr&aacute; diligenciar el formato que Comfamiliar tiene establecido para tal fin "),"R",1,"L",0,0);
        $formu->Cell(10,1,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,1,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,1,html_entity_decode(""),"R",0,"L",0,0);
        $formu->Cell(1,2,html_entity_decode(""),"B",0,"C",0,0);
        $formu->Cell(129,2,html_entity_decode(""),"RB",1,"L",0,0);
        $formu->Cell(10,8,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,8,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,8,html_entity_decode("*  Fotocopia de la resoluci&oacute;n que reconoce la lacalidad de pensionado o certificado de la entidad "),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->SetFont('Arial','B','10');
        $formu->Cell(129,8,html_entity_decode("Padres"),"R",1,"L",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(10,1,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,1,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,1,html_entity_decode("   pagadora o la ultima colilla de pago"),"R",0,"L",0,0);
        $formu->Cell(1,1,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(129,1,html_entity_decode("*  Fotocopia del folio del registro civil de nacimineto  del trabajador"),"R",1,"L",0,0);
        $formu->Cell(10,2,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,8,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,2,html_entity_decode(""),"R",0,"L",0,0);
        $formu->Cell(1,8,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,8,html_entity_decode("*  Fotocopia del folio del registro civil de nacimineto  del trabajador donde consten sus padres"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->SetFont('Arial','B','10');
        $formu->Cell(129,5,html_entity_decode("Conyuge"),"TR",0,"L",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Constancia de dependencia econ&oacute;nomica y convivencia, a trav&eacute;s de la caul manifiesta que los"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("* Fotocopia del folio del registro civil de matrimonio o partida eclesiastica de matrimonio del trabajador"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   hermnaos hu&eacute;rfanos de padres conviven y dependen econ&oacute;nomicamente del trabajador y que"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("* Fotocopia de la c&eacute;dula de ciudadania. Si este(a) es menor de edad, fotocopia de la tarjeta de"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   ninguna otra persona los tiene afiliados a Comfamiliar o a otra caja de compensacion familiar"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("  identidad"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   o entidad similar. Podr&aacute; diligenciar el formato que Comfamiliar tiene establecido para tal fin"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("* Si el trabajador ya tiene registrada a su conyuge y esta afiliado a una nueva pareja, debera"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"BL",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode(""),"RB",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("  presentar constancia de divorcio"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->SetFont('Arial','B','10');
        $formu->Cell(129,5,html_entity_decode("Discapacidad"),"R",1,"L",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode(""),"RB",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Los padres, los hermanos hu&eacute;rfanos de padres y los hijos, que sean inv&aacute;lidos o de capacidad"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->SetFont('Arial','B','10');
        $formu->Cell(129,5,html_entity_decode("Compa&ntilde;era(o) Permanente"),"R",0,"L",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   fisica disminuida que le impida trabajar, sin limitaci&oacute;n en raz&oacute;n de su edad, para ello, el trabajador"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Fotocopia de la c&eacute;dula de ciudadania de la compa&ntilde;era permanente. si esta(a) es menor de edad"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   indenpendiente debe demostrar la convivencia y dependencia economica de la persona a cargo"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   fotocopia de la tarjeta de identidad"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   discapacitada, ademas presentar constancia  emitidas por la entidad Promotora de Salud-EPS de la"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Acreditaci&oacute;n como compa&ntilde;era(o) permanente. Podr&aacute; diligenciar el formateo que comfamiliar tiene"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   IPS de la red p&uacute;blica se salud (Decreto 1335 de 2008 y 4942 de 2009) o de la Junta Regional de"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   establecida para tal fin"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   Calificaci&oacute;n de Invalidez(Decreto 2463 del 20 de noviembre de 2001). Con el fin de acreditar la"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Si el trabajador ya tiene registrada a su conyuge o cpmpa&ntilde;era(o) y esta afiliando una nueva pareja, "),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   invalidez o disminuci&oacute;n fisica que le impida trabajar"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   deber&aacute; presentar la constancia de divorcio si es casado o carta personal firmada bajo gravedad"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  La dependencia econ&oacute;mica y convivencia de la persona a cargo discapacitada se podr&aacute; demostrar "),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   de juramenton en la cual manifiesta con quien esta conviviendo en forma permanente"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   diligenciando el formato que Comfamiliar tiene establecido para tal fin"),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode(""),"RB",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode(""),"RB",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->SetFont('Arial','B','10');
        $formu->Cell(129,5,html_entity_decode("Hijos (Legitimos o Extramatrimoniales) e Hijastros"),"R",0,"L",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(29,5,html_entity_decode("Observaciones"),"",0,"L",0,0);
        $formu->Cell(95,5,html_entity_decode(""),"B",0,"L",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",1,"L",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Fotocopia del folio del registro civil de nacimiento de los hijos o hijastros"),"R",0,"L",0,0);
        $formu->Cell(4,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(121,5,html_entity_decode(""),"B",0,"L",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Fotocopia de la tarjeta de identidad(mayores de 7 a&ntilde;os)"),"R",0,"L",0,0);
        $formu->Cell(4,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(121,5,html_entity_decode(""),"B",0,"L",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode(""),"BR",0,"L",0,0);
        $formu->Cell(4,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(121,5,html_entity_decode(""),"B",0,"L",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->SetFont('Arial','B','10');
        $formu->Cell(129,5,html_entity_decode("Para los Hijastros, deber&aacute; anexar adem&aacute;s"),"R",0,"L",0,0);
        $formu->SetFont('Arial','','8');
        $formu->Cell(4,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(121,5,html_entity_decode(""),"B",0,"L",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("*  Constacia de dependencia econ&oacute;mica o convivencia, a trav&eacute;s de la cual de manifiesta que los"),"R",0,"L",0,0);
        $formu->Cell(4,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(121,5,html_entity_decode(""),"B",0,"L",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",1,"L",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(129,5,html_entity_decode("   hijastros conviven y dependes econ&oacute;nomicamente del trabajador. Podr&aacute; diligenciar el formato"),"R",0,"L",0,0);
        $formu->Cell(4,5,html_entity_decode(""),"L",0,"C",0,0);
        $formu->Cell(121,5,html_entity_decode(""),"B",0,"L",0,0);
        $formu->Cell(5,5,html_entity_decode(""),"R",1,"L",0,0);
        $formu->Cell(10,3,html_entity_decode(""),"",0,"C",0,0);
        $formu->Cell(1,3,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(129,3,html_entity_decode("   que comfamiliar tiene establecido para tal fin"),"RB",0,"L",0,0);
        $formu->Cell(4,3,html_entity_decode(""),"LB",0,"C",0,0);
        $formu->Cell(121,3,html_entity_decode(""),"B",0,"L",0,0);
        $formu->Cell(5,3,html_entity_decode(""),"RB",1,"L",0,0);

/*
        $formu->Cell(10,1,html_entity_decode(""),"T",1,"C",0,0);

        $formu->Cell(10,5,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(90,5,html_entity_decode(""),0,0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"LR",0,"C",0,0);
        $formu->Cell(50,5,html_entity_decode("Fotocopia C&eacute;dula del Pens&iacute;onado"),0,0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"RL",0,"C",0,0);
        $formu->Cell(50,5,html_entity_decode("Documento ilegible.Cual?"),0,0,"C",0,0);
        $formu->Cell(10,5,html_entity_decode(""),"B",0,"C",0,0);
*/
        $file = "public/temp/reportes/trabajadorpens_.pdf";
        //$response = parent::successFunc("Genera Formulario",$file);
        ob_clean();
        $formu->Output( $file,"I");
        //return $this->renderText(json_encode($response)); 
   }
//Adicion de trabajador a mercurio31
    public function addtrabajadorAction(){
        try{
            try{
                $today = new Date();
                $modelos = array("mercurio31","mercurio32","mercurio21","mercurio38");
                $Transaccion = parent::startTrans($modelos);
                $nit = $this->getPostParam("nit","addslaches","alpha","extraspaces","striptags");
                $razsoc = $this->getPostParam("razsoc","addslaches","alpha","extraspaces","striptags");
                $cedtra = $this->getPostParam("cedtra","addslaches","alpha","extraspaces","striptags");
                $coddoc = $this->getPostParam("coddoc","addslaches","alpha","extraspaces","striptags");
                $mercurio31 = $this->Mercurio31->find("estado = 'P' AND cedtra='$cedtra' AND coddoc='$coddoc' AND nit='".SESSION::getDATA('documento')."'");
                if(count($mercurio31) > 0){
                    return $this->redirect("principal/index/Este trabajador ya tiene una solicitud de afiliacion pendiente");
                }
                $priape = $this->getPostParam("priape","addslaches","alpha","extraspaces","striptags");
                $segape = $this->getPostParam("segape","addslaches","alpha","extraspaces","striptags");
                $prinom = $this->getPostParam("prinom","addslaches","alpha","extraspaces","striptags");
                $segnom = $this->getPostParam("segnom","addslaches","alpha","extraspaces","striptags");
                $fecnac = $this->getPostParam("fecnac","addslaches","extraspaces","striptags");
                $sexo = $this->getPostParam("sexo","addslaches","alpha","extraspaces","striptags");
                $estciv = $this->getPostParam("estciv","addslaches","alpha","extraspaces","striptags");
                $codciu = $this->getPostParam("codciu","addslaches","alpha","extraspaces","striptags");
                $direccion = $this->getPostParam("direccion","addslaches","alpha","extraspaces","striptags");
                $barrio = $this->getPostParam("barrio","addslaches","alpha","extraspaces","striptags");
                $telefono = $this->getPostParam("telefono","addslaches","alpha","extraspaces","striptags");
                $celular = $this->getPostParam("celular","addslaches","alpha","extraspaces","striptags");
                $fax = $this->getPostParam("fax","addslaches","alpha","extraspaces","striptags");
                $email = $this->getPostParam("email","addslaches","extraspaces","striptags");
                $fecing = $this->getPostParam("fecing","addslaches","extraspaces","striptags");
                $salario = $this->getPostParam("salario","addslaches","alpha","extraspaces","striptags");
                $agencia = $this->getPostParam("agencia","addslaches","alpha","extraspaces","striptags");
                $captra ="N";
                $tipdis = $this->getPostParam("tipdis","addslaches","alpha","extraspaces","striptags");
                $nivedu = $this->getPostParam("nivedu","addslaches","alpha","extraspaces","striptags");
                $autoriza = "S";
                $ciunac = $this->getPostParam("ciunac","addslaches","alpha","extraspaces","striptags");
                $cabhog = $this->getPostParam("cabhog","addslaches","alpha","extraspaces","striptags");
                $codare = $this->getPostParam("codare","addslaches","alpha","extraspaces","striptags");
                $codope = $this->getPostParam("codope","addslaches","alpha","extraspaces","striptags");
                $horas = $this->getPostParam("horas","addslaches","alpha","extraspaces","striptags");
                $rural = $this->getPostParam("rural","addslaches","alpha","extraspaces","striptags");
                $tipcon = $this->getPostParam("tipcon","addslaches","alpha","extraspaces","striptags");
                $vivienda = $this->getPostParam("vivienda","addslaches","alpha","extraspaces","striptags");
                $tipafi = $this->getPostParam("tipafi","addslaches","alpha","extraspaces","striptags");
                $profesion = $this->getPostParam("profesion","addslaches","alpha","extraspaces","striptags");
                $cargo = $this->getPostParam("cargo","addslaches","alpha","extraspaces","striptags");
                $codzon = $this->getPostParam("codzon","addslaches","alpha","extraspaces","striptags");
                Session::setData('nota_audit',"Ingreso de Trabajador");
                $log_id = parent::registroOpcion();
                if($log_id==false){
                    parent::ErrorTrans();
                }
                $nombre = $priape." ".$segape." ".$prinom." ".$segnom;
                $mercurio31 = new Mercurio31();
                $mercurio31->setTransaction($Transaccion);
                $mercurio31->setId(0);
                $mercurio31->setLog($log_id);
                $mercurio31->setCodcaj(Session::getDATA('codcaj'));
                $mercurio31->setNit($nit);
                $mercurio31->setCedtra($cedtra);
                $mercurio31->setCoddoc($coddoc);
                $mercurio31->setPriape($priape);
                $mercurio31->setSegape($segape);
                $mercurio31->setPrinom($prinom);
                $mercurio31->setSegnom($segnom);
                $mercurio31->setFecnac($fecnac);
                $mercurio31->setSexo($sexo);
                $mercurio31->setEstciv($estciv);
                $mercurio31->setCodciu($codciu);
                $mercurio31->setDireccion($direccion);
                $mercurio31->setBarrio($barrio);
                $mercurio31->setTelefono($telefono);
                $mercurio31->setCelular($celular);
                $mercurio31->setFax($fax);
                $mercurio31->setEmail($email);
                $mercurio31->setFecing($fecing);
                $mercurio31->setSalario($salario);
                $mercurio31->setCiunac($ciunac);
                $mercurio31->setCabhog($cabhog);
                $mercurio31->setEstado("P");
                $mercurio31->setCaptra($captra);
                $mercurio31->setTipdis($tipdis);
                $mercurio31->setNivedu($nivedu);
                $mercurio31->setAutoriza($autoriza);
                $mercurio31->setRural($rural);
                $mercurio31->setHoras($horas);
                $mercurio31->setTipcon($tipcon);
                $mercurio31->setVivienda($vivienda);
                $mercurio31->setTipafi($tipafi);
                $mercurio31->setProfesion($profesion);
                $mercurio31->setCargo($cargo);
                $mercurio31->setAgencia($agencia);
                $mercurio31->setCodzon($codzon);
                $u = parent::asignarFuncionario($codare,$codope);
                if($u==false){
                    return $this->redirect("login/index/La sesion a expirado");
                }
                $mercurio31->setUsuario($u);
                if(!$mercurio31->save()){
                    parent::setLogger($mercurio31->getMessages());
                    parent::ErrorTrans();
                }
                $formafi = $this->getPostParam("formafi");
                if($formafi != ''){
                    $mercurio38 = new Mercurio38();
                    $mercurio38->setTransaction($Transaccion);
                    $mercurio38->setNumero($mercurio31->getId());
                    $mercurio38->setCoddoc('1');
                    $mercurio38->setNomarc($formafi);                         
                    if(!$mercurio38->save()){
                        parent::setLogger($mercurio38->getMessages());          
                        parent::ErrorTrans();
                    }
                }
                $mercurio02 = $this->Mercurio02->findFirst("codcaj = '".SESSION::getDATA('codcaj')."'");
                $asunto = "Nuevo Trabajador por Aprobar $nombre";
                $msg = "Cordial Saludos<br><br>Esta pendiente por aprobar el trabajador $cedtra - $nombre con un salario de $salario de la empresa $nit - $razsoc <br><br>Atentamente,<br><br>MERCURIO";
                $ruta_file = "";
                $mercurio13 = $this->Mercurio13->find("codcaj = '".SESSION::getDATA('codcaj')."' AND codare = '$codare' AND codope = '$codope'");
                foreach($mercurio13 as $mmercurio13){
                    if(isset($_FILES['archivo_'.$mmercurio13->getCoddoc()])){
                        $_FILES["archivo_{$mmercurio13->getCoddoc()}"]['name'] = $mercurio31->getId()."_".$mmercurio13->getCoddoc()."_".$cedtra.substr($_FILES["archivo_{$mmercurio13->getCoddoc()}"]['name'],-4);
                        $path = "public/files/";
                        $this->uploadFile("archivo_{$mmercurio13->getCoddoc()}",getcwd()."/$path/");
                        $dd=pathinfo($_FILES["archivo_{$mmercurio13->getCoddoc()}"]["name"]);                     
                        $archivo_nombre = $path.$dd['basename'];                                            
                        $ruta_file[] = $archivo_nombre;
                        $mercurio38 = new Mercurio38();
                        $mercurio38->setTransaction($Transaccion);
                        $mercurio38->setNumero($mercurio31->getId());
                        $mercurio38->setCoddoc($mmercurio13->getCoddoc());
                        $mercurio38->setNomarc($archivo_nombre);                         
                        if(!$mercurio38->save()){
                            parent::setLogger($mercurio38->getMessages());          
                            parent::ErrorTrans();
                        }
                    }
                }
                parent::finishTrans();
                /*
                $flag_email = parent::enviarCorreo("Afiliación Trabajador Mercurio",$nombre, "mercurio@syseu.com", $asunto, $msg, $ruta_file);
                if($flag_email==false){
                    return $this->redirect("principal/index/Se envio el Formulario pero sin Correo Electrónico");
                }
                */
                $asuntotrab = "Solicitud de Afiliacion Caja";
                $emaile = Session::getData('email');
                $msgtrab ="Gracias por utilizar el Servicio en Linea de Comfamiliar Huila, usted acaba de realizar el proceso de Afiliacion de Trabajadores:";
                $msgtrab .= "<br><br><b>RAZON SOCIAL: </b>".Session::getData('nombre');
                $msgtrab .= "<br><b>NIT: </b>".Session::getData('documento');
                $msgtrab .= "<br><br><b>Nombre: </b>".$nombre;
                $msgtrab .= "<br><b>Identificacion: </b>".$cedtra;
                $msgtrab .= "<br><br>Esta informacion sera verificada por uno de nuestros funcionarios, a su correo de contacto '$emaile' le comunicaremos si su solicitud ha sido aprobada o rechazada.";
                $mercurio07 = $this->Mercurio07->findFirst("documento = '".Session::getData('documento')."'");
                if($mercurio07 != FALSE)$emaile = $mercurio07->getEmail();
                else $emaile = "";
                $flag_email = parent::enviarCorreo("",$nombre,$emaile,$asuntotrab,$msgtrab,"");
                if($flag_email==false){
                    return $this->redirect("principal/index/Se envio el formulario pero sin Correo Electrónico");
                }
                return $this->redirect("principal/index/Se registro exitosamente");
            } catch (DbException $e) {
                parent::setLogger($e->getMessage());
                parent::ErrorTrans();
            }
        } catch (TransactionFailed $e) {
            return $this->redirect("principal/index/No se pudo resgistrar");
        }

    }

//seleccion de archivos adjuntos segun Empresa 
    /*
    public function adjuntoAction(){
        $this->setResponse("ajax");
        $codare = $this->getPostParam("codare");
        $codope = $this->getPostParam("codope");
        //$pymes = $this->getPostParam("pymes");
        $siccf = $this->getPostParam("vinculo");
        $tipsoc = $this->getPostParam("tipsoc");
        $tipemp = $this->getPostParam("tipemp");
        $mercurio13 = $this->Mercurio13->find("codcaj = '".SESSION::getDATA('codcaj')."' AND codare = '$codare' AND codope='$codope'");
        $response = "";
        $declarante = $this->getPostParam("declara");
        $aniluc = $this->getPostParam("sinlucro");
        $coop = $this->getPostParam("cooperativa");
        $proph = $this->getPostParam("propieho");
        $consounite = $this->getPostParam("cutemporal");

        if($tipsoc == 1){
            foreach($mercurio13 as $mmercurio13){
                $mercurio12 = $this->Mercurio12->findFirst("coddoc = '{$mmercurio13->getCoddoc()}'");
                    if($mmercurio13->getCoddoc() == 2 || $mmercurio13->getCoddoc() == 5){
                        if(($tipemp == '1' || $tipemp == '2' || $tipemp == '3' || $tipemp == '4') && $mmercurio13->getCoddoc() == 2){
                            continue;
                        }
                        $response .= "<tr>";
                        $response .= " <td colspan='2' style='text-align: right;'><label>Archivo {$mercurio12->getDetalle()} (pdf):</label></td>";
                        $response .= "<td colspan='2'>".Tag::fileField("archivo_{$mmercurio13->getCoddoc()}","accept: application/pdf, image/*")."</td>";
                        $response .= "</tr>";
                    }
                    else if($mmercurio13->getCoddoc() == 4 || $mmercurio13->getCoddoc() == 6){
                        if($declarante == 'S'){
                            $response .= "<tr>";
                            $response .= " <td colspan='2' style='text-align: right;'><label>Archivo {$mercurio12->getDetalle()} (pdf):</label></td>";
                            $response .= "<td colspan='2'>".Tag::fileField("archivo_{$mmercurio13->getCoddoc()}","accept: application/pdf, image/*")."</td>";
                            $response .= "</tr>";
                        }
                    }else if($mmercurio13->getCoddoc() == 3){
                        if($siccf == 'S'){
                            $response .= "<tr>";
                            $response .= " <td colspan='2' style='text-align: right;'><label>Archivo {$mercurio12->getDetalle()} (pdf):</label></td>";
                            $response .= "<td colspan='2'>".Tag::fileField("archivo_{$mmercurio13->getCoddoc()}","accept: application/pdf, image/*")."</td>";
                            $response .= "</tr>";
                        }else{
                            continue;
                        }
                    }
                    if($mmercurio13->getCoddoc() == 21){
                        if($tipemp == '1'){
                            $response .= "<tr>";
                            $response .= " <td colspan='2' style='text-align: right;'><label>Archivo {$mercurio12->getDetalle()} (pdf):</label></td>";
                            $response .= "<td colspan='2'>".Tag::fileField("archivo_{$mmercurio13->getCoddoc()}","accept: application/pdf, image/*")."</td>";
                            $response .= "</tr>";
                        }else{
                            continue;
                        }
                    }
                    if($mmercurio13->getCoddoc() == 22){
                        if($tipemp == '2'){
                            $response .= "<tr>";
                            $response .= " <td colspan='2' style='text-align: right;'><label>Archivo {$mercurio12->getDetalle()} (pdf):</label></td>";
                            $response .= "<td colspan='2'>".Tag::fileField("archivo_{$mmercurio13->getCoddoc()}","accept: application/pdf, image/*")."</td>";
                            $response .= "</tr>";
                        }else{
                            continue;
                        }
                    }
                    if($mmercurio13->getCoddoc() == 23){
                        if($tipemp == '3'){
                            $response .= "<tr>";
                            $response .= " <td colspan='2' style='text-align: right;'><label>Archivo {$mercurio12->getDetalle()} (pdf):</label></td>";
                            $response .= "<td colspan='2'>".Tag::fileField("archivo_{$mmercurio13->getCoddoc()}","accept: application/pdf, image/*")."</td>";
                            $response .= "</tr>";
                        }else{
                            continue;
                        }
                    }
                    if($mmercurio13->getCoddoc() == 25){
                        if($tipemp == '4'){
                            $response .= "<tr>";
                            $response .= " <td colspan='2' style='text-align: right;'><label>Archivo {$mercurio12->getDetalle()} (pdf):</label></td>";
                            $response .= "<td colspan='2'>".Tag::fileField("archivo_{$mmercurio13->getCoddoc()}","accept: application/pdf, image/*")."</td>";
                            $response .= "</tr>";
                        }else{
                            continue;
                        }
                    }
                    if($mmercurio13->getCoddoc() == 24){
                            $response .= "<tr>";
                            $response .= " <td colspan='2' style='text-align: right;'><label>Archivo {$mercurio12->getDetalle()} (pdf):</label></td>";
                            $response .= "<td colspan='2'>".Tag::fileField("archivo_{$mmercurio13->getCoddoc()}","accept: application/pdf, image/*")."</td>";
                            $response .= "</tr>";
                    }
            }
        }else if($tipsoc == 2){
            foreach($mercurio13 as $mmercurio13){
                $mercurio12 = $this->Mercurio12->findFirst("coddoc = '{$mmercurio13->getCoddoc()}'");
                    if($mmercurio13->getCoddoc() == 2 || $mmercurio13->getCoddoc() == 4 || $mmercurio13->getCoddoc() == 5 || $mmercurio13->getCoddoc() == 6 || $mmercurio13->getCoddoc() == 7){
                        $response .= "<tr>";
                        $response .= " <td colspan='2' style='text-align: right;'><label>Archivo {$mercurio12->getDetalle()} (pdf):</label></td>";
                        $response .= "<td colspan='2'>".Tag::fileField("archivo_{$mmercurio13->getCoddoc()}","accept: application/pdf, image/*")."</td>";
                        $response .= "</tr>";
                    }else if($mmercurio13->getCoddoc() == 3){
                        if($siccf == 'S'){
                            $response .= "<tr>";
                            $response .= " <td colspan='2' style='text-align: right;'><label>Archivo {$mercurio12->getDetalle()} (pdf):</label></td>";
                            $response .= "<td colspan='2'>".Tag::fileField("archivo_{$mmercurio13->getCoddoc()}","accept: application/pdf, image/*")."</td>";
                            $response .= "</tr>";
                        }else{
                            continue;
                        }
                    }else if($mmercurio13->getCoddoc() == 8){
                        if($aniluc == 'S'){
                            $response .= "<tr>";
                            $response .= " <td colspan='2' style='text-align: right;'><label>Archivo {$mercurio12->getDetalle()} (pdf):</label></td>";
                            $response .= "<td colspan='2'>".Tag::fileField("archivo_{$mmercurio13->getCoddoc()}","accept: application/pdf, image/*")."</td>";
                            $response .= "</tr>";
                        }else{
                            continue;
                        }
                    }else if($mmercurio13->getCoddoc() == 9){
                        if($coop == 'S'){
                            $response .= "<tr>";
                            $response .= " <td colspan='2' style='text-align: right;'><label>Archivo {$mercurio12->getDetalle()} (pdf):</label></td>";
                            $response .= "<td colspan='2'>".Tag::fileField("archivo_{$mmercurio13->getCoddoc()}","accept: application/pdf, image/*")."</td>";
                            $response .= "</tr>";
                        }else{
                            continue;
                        }
                    }else if($mmercurio13->getCoddoc() == 10){
                        if($proph == 'S'){
                            $response .= "<tr>";
                            $response .= " <td colspan='2' style='text-align: right;'><label>Archivo {$mercurio12->getDetalle()} (pdf):</label></td>";
                            $response .= "<td colspan='2'>".Tag::fileField("archivo_{$mmercurio13->getCoddoc()}","accept: application/pdf, image/*")."</td>";
                            $response .= "</tr>";
                        }else{
                            continue;
                        }
                    }else if($mmercurio13->getCoddoc() == 11 || $mmercurio13->getCoddoc() == 12){
                        if($consounite == 'S'){
                            $response .= "<tr>";
                            $response .= " <td colspan='2' style='text-align: right;'><label>Archivo {$mercurio12->getDetalle()} (pdf):</label></td>";
                            $response .= "<td colspan='2'>".Tag::fileField("archivo_{$mmercurio13->getCoddoc()}","accept: application/pdf, image/*")."</td>";
                            $response .= "</tr>";
                        }else{
                            continue;
                        }
                    }
            }
        }
        $this->renderText(json_encode($response));
    }
*/
    public function adjuntoAction(){
        $this->setResponse("ajax");
        $codare = $this->getPostParam("codare");
        $codope = $this->getPostParam("codope");
        $mercurio13 = $this->Mercurio13->find("codcaj = '".SESSION::getDATA('codcaj')."' AND codare = '$codare' AND codope='$codope'");
        $response = "";
        foreach($mercurio13 as $mmercurio13){
            if(isset($_POST["coddoc{$mmercurio13->getCoddoc()}"])){
                $mercurio12 = $this->Mercurio12->findFirst("coddoc = '{$mmercurio13->getCoddoc()}'");
                $response .= "<tr>";
                $response .= " <td colspan='2' style='text-align: right;'><label>Archivo {$mercurio12->getDetalle()} (pdf):</label></td>";
                $response .= "<td colspan='2'>".Tag::fileField("archivo_{$mmercurio13->getCoddoc()}","accept: application/pdf, image/*")."</td>";
                $response .= "</tr>";
            }
        }
        $this->renderText(json_encode($response));
    }

//seleccion de archivos adjuntos segun Trabajador
    public function adjuntoTAction(){
        $this->setResponse("ajax");
        $codare = $this->getPostParam("codare");
        $codope = $this->getPostParam("codope");
        //$pymes = $this->getPostParam("pymes");
        $siccf = $this->getPostParam("vinculo");
        $fecnac = $this->getPostParam("fecnac");
        $tipsoc = 1;
        $mercurio13 = $this->Mercurio13->find("codcaj = '".SESSION::getDATA('codcaj')."' AND codare = '$codare' AND codope='$codope'");
        $response = "";
        $declarante = $this->getPostParam("declara");
        $aniluc = $this->getPostParam("sinlucro");
        $coop = $this->getPostParam("cooperativa");
        $proph = $this->getPostParam("propieho");
        $consounite = $this->getPostParam("cutemporal");
        $fecha = new Date();
        $difer = $fecha->diffDate($fecnac);
        $difano = $difer/365;
        $response = "";
        foreach($mercurio13 as $mmercurio13){
            $mercurio12 = $this->Mercurio12->findFirst("coddoc = '{$mmercurio13->getCoddoc()}'");
            //if($mmercurio13->getCoddoc() == 1 || $mmercurio13->getCoddoc() == 2 || $mmercurio13->getCoddoc() == 5){
            if($mmercurio13->getCoddoc() == 2 || $mmercurio13->getCoddoc() == 5){
                $response .= "<tr>";
                $response .= " <td colspan='2' style='text-align: right;'><label>Archivo {$mercurio12->getDetalle()} (pdf):</label></td>";
                $response .= "<td colspan='2'>".Tag::fileField("archivo_{$mmercurio13->getCoddoc()}","accept: application/pdf, image/*")."</td>";
                $response .= "</tr>";
            }else if($mmercurio13->getCoddoc() == 17 && $difano < 18){
                $response .= "<tr>";
                $response .= " <td colspan='2' style='text-align: right;'><label>Archivo {$mercurio12->getDetalle()} (pdf):</label></td>";
                $response .= "<td colspan='2'>".Tag::fileField("archivo_{$mmercurio13->getCoddoc()}","accept: application/pdf, image/*")."</td>";
                $response .= "</tr>";
            }
            else if($mmercurio13->getCoddoc() == 4 || $mmercurio13->getCoddoc() == 6){
                if($declarante == 'S'){
                    $response .= "<tr>";
                    $response .= " <td colspan='2' style='text-align: right;'><label>Archivo {$mercurio12->getDetalle()} (pdf):</label></td>";
                    $response .= "<td colspan='2'>".Tag::fileField("archivo_{$mmercurio13->getCoddoc()}","accept: application/pdf, image/*")."</td>";
                    $response .= "</tr>";
                }
            }else if($mmercurio13->getCoddoc() == 3){
                if($siccf == 'S'){
                    $response .= "<tr>";
                    $response .= " <td colspan='2' style='text-align: right;'><label>Archivo {$mercurio12->getDetalle()} (pdf):</label></td>";
                    $response .= "<td colspan='2'>".Tag::fileField("archivo_{$mmercurio13->getCoddoc()}","accept: application/pdf, image/*")."</td>";
                    $response .= "</tr>";
                }else{
                    continue;
                }
            }
        }
        $this->renderText(json_encode($response));
    }


    public function noAfiliado_viewAction(){
        $this->setResponse("ajax");
      echo parent::showTitle('Constancia de no afiliado'); 
    }

// Formulario de no afiliado
    public function noAfiliadoAction(){
        $this->setResponse("ajax");
        $nombre = $this->getPostParam("nombre");
        $fecha = new Date();
        $ano = $fecha->getYear();
        $mes = $fecha->getMonthName();
        $dia = $fecha->getDay();
        $formu = new FPDF('P','mm','Letter');
        $formu->SetMargins(20,25);
        $formu->AddPage();
        $formu->SetFillColor(236,248,240); 
        $formu->SetFont('Arial','','12');
        $formu->Ln();
        $formu->Cell(175,4,"",0,1,"L",0,0);
        $formu->Image('public/img/comfamiliar-logo.jpg',10,15,63,18);
        $idmer20 = $this->Mercurio20->findBySql("SELECT MAX(id) as id FROM mercurio20");
        $mercurio20 = $this->Mercurio20->findBySql("SELECT * FROM mercurio20 WHERE id='{$idmer20->getId()}'");
        $codbar = $idmer20->getId()."-".$mercurio20->getDocumento();
        $formu->Codabar(120,10,$codbar);
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
        $formu->Cell(15,4,html_entity_decode("Se&ntilde;or(a)"),0,1,"L",0,0);
        $formu->SetFont('Arial','B','12');
        $formu->Cell(190,4,$nombre,0,1,"L",0,0);
        $formu->SetFont('Arial','','12');
        $formu->Cell(190,4,"NEIVA (HUILA)",0,1,"L",0,0);
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Cell(190,4,"Asunto: Certificado de No Afiliado .",0,1,"L",0,0);
        $formu->Ln();
        $formu->Ln();
        $formu->Cell(190,4,"Cordial Saludo.",0,1,"L",0,0);
        $formu->Ln();
        $formu->Ln();
        $formu->MultiCell(175,4,html_entity_decode("Me permito certificar que ".$nombre." identificado(a) con el documento de No. ".Session::getDATA('documento').", No figura afiliado(a) a nuestra Entidad como cotizante &oacute; beneficiario(a). "),0,"J",0);
        $formu->Ln();
        $formu->Ln();
        $formu->SetFont('Arial','','12');
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Cell(190,4,"Atentamente, ",0,1,"L",0,0);
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Ln();
        $formu->Cell(190,4,"YAZMIN OSPINA GAITAN ",0,1,"L",0,0);
        $formu->Cell(190,4,"COORDINADORA AFILIACIONES Y SUBSIDIO",0,1,"L",0,0);
        $formu->Ln();
        $formu->Ln();
        $formu->SetFont('Arial','','8');
        $formu->Image('public/img/firmas/firmaYazminOspina.png',13,140,60,20);
        $formu->Cell(190,4,html_entity_decode("Y. OSPINA GAITAN / M.Y. MU&Ntilde;OZ LOZANO"),0,1,"L",0,0);
        $formu->Ln();
        $formu->Cell(190,4,html_entity_decode("Copia Interna: Centro de Documentaci&oacute;n e Informaci&oacute;n "),0,1,"L",0,0);
        $formu->Image('public/img/portal/piepaginaCartas.png',1,237,214,42);
        $formu->Ln();
        $this->setResponse('view');
        $file = "public/temp/reportes/trabajador_inact.pdf";
        ob_clean();
        $formu->Output( $file,"D");  
    }

    public function dattraAction(){
        $this->setResponse('ajax');
        $cedtra = $this->getPostParam('cedtra');
        $coddoc = $this->getPostParam('coddoc');
        $param = array("nit"=>session::getDATA('documento'),"cedtra"=>$cedtra,"coddoc"=>$coddoc);
        $result = parent::webService("Dattra",$param);
        if(session::getDATA('documento') == "$cedtra" && session::getDATA('coddoc') == "$coddoc"){
        //if(session::getDATA('documento') == "$cedtra" && session::getDATA('coddoc') == "$coddoc" && session::getDATA('clasoc') == "101"){
            $response = parent::errorFunc("El Trabajador es el mismo representante legal de esta persona natural");
            return $this->renderText(json_encode($response));
        }
        if($result == false){
            $response = parent::successFunc("El Trabajador no se encuentra registrado");
            return $this->renderText(json_encode($response));
        }
        $mresult = parent::webService("DatosTrabajador",array("nit"=>session::getDATA('documento'),"cedtra"=> $cedtra,"coddoc"=>$coddoc));
        if($mresult!=false){
            $response = parent::errorFunc("El trabajador se encuentra afiliado a la empresa.");
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
        $response['zona'] = $result[0]['zona'];
        //nuevos
        
        $response['email']= $result[0]['email'];
        $response['barrio']= $result[0]['barrio'];
        $response['estciv']= $result[0]['estciv'];
        $response['departn']= $result[0]['departn'];
        $response['ciunac']= $result[0]['ciunac'];
        $response['profesion']= $result[0]['profesion'];
        $response['depart']= $result[0]['depart'];
        $response['codciu']= $result[0]['codciu'];
        $response['ubiviv']= $result[0]['tipviv'];
        $response['tipviv']= $result[0]['vivienda'];
        if($response['ubiviv']=="U")$response['ubiviv']="N";
        if($response['ubiviv']=="R")$response['ubiviv']="S";
        $response = parent::successFunc("",$response);
        
        return $this->renderText(json_encode($response));
    }
    public function edadAction(){
        $this->setResponse('ajax');
        $fecnac = $this->getPostParam('fecnac');
        $parent = $this->getPostParam('parent');
        $captra = $this->getPostParam('captra');
        $fecha = new Date();
        $difer = $fecha->diffDate($fecnac);
        $difano = $difer/365.4;
        if($captra != 'I'){
            if($parent == '36' && $difano<60){
                $response = parent::errorFunc("El Beneficiario Padre/Madre debe ser mayor a 60 a&ntilde;os");
                return $this->renderText(json_encode($response));
            }
            if(($parent == '35' || $parent == '37' || $parent=='38') && $difano>19){
                $response = parent::errorFunc("El Beneficiario debe ser menor a 19 a&ntilde;os");
                return $this->renderText(json_encode($response));
            }
        }
        $response = parent::successFunc("El beneficiario cumple con los requerimiento");
        return $this->renderText(json_encode($response));
    }
    public function actividadAction(){
        $this->setResponse('ajax');
        $codact = $this->getPostParam('codact');
        $seccion = $this->Migra079->findBySql("select distinct idciiu,seccion,descripcion FROM migra079 where division ='' and grupo ='' AND idciiu='$codact' order by idciiu;");
        $migra079 = $this->Migra079->findAllBySql("select distinct * FROM migra079 where clase != '' and length(clase)>=4 AND seccion ='{$seccion->getSeccion()}' order by idciiu;");
        $response = "<option value='@'>Seleccione...</option>";
        foreach($migra079 as $mtabla)
            $response .= "<option value='{$mtabla->getClase()}'=>{$mtabla->getDescripcion()}</option>";
        $response = utf8_decode($response);
        $response = parent::successFunc("",$response);

        return $this->renderText(json_encode($response));
    }
    }
    ?>
