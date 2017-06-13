<?php

class Mercurio31Controller extends ApplicationController {

    private $estado = 100;
    private $title = "Afiliacion Trabajador";

    public function initialize(){
        $this->setTemplateAfter('escritorio');
        $this->setPersistance(true);
    }

    public function indexAction(){
      $filt = parent::getActUser('tipfun');
      if($filt=="NIVA" ){
          $filt =1;
      }if($filt=="GRZN"){
          $filt=2;
      }if($filt=="PITL"){
         $filt=3;
      }if($filt=="PLTA"){
         $filt=4;
      }
      $this->setParamToview('titulo',$this->title);
      $html = "";
      $html .= "<table class='resultado-consul' border=1>";
      $html .= "<thead>";
      $html .= "<th>Nit</th>";
      $html .= "<th>Cedula</th>";
      $html .= "<th>Nombre</th>";
      $html .= "<th>Ciudad</th>";
      $html .= "<th>Direccion</th>";
      $html .= "<th>Telefono</th>";
      $html .= "<th>Salario</th>";
      $html .= "<th>Fecha</th>";
      $html .= "<th>Agencia</th>";
      $html .= "<th>Aprobar</th>";
      $html .= "<th>Rechazar</th>";
      $html .= "<th>Documentos</th>";
      $html .= "</thead>";
      $html .= "<tbody>";
      if($filt =="ADAD")
        $mercurio31 = $this->Mercurio31->find("estado='P'");
      else
         $mercurio31 = $this->Mercurio31->findAllBySql("select mercurio31.* from mercurio31,mercurio07 where mercurio31.estado='P' and mercurio07.agencia='$filt' and mercurio07.documento=mercurio31.nit AND mercurio31.usuario='".Session::getData('usuario')."'");
      foreach($mercurio31 as $mmercurio31){
          $gener08 = $this->Gener08->findFirst("codciu = '{$mmercurio31->getCodciu()}'");
          if($gener08 == false)$gener08 = new Gener08();
          $html .= "<tr>";
          $html .= "<td>{$mmercurio31->getNit()}</td>";
          $html .= "<td>{$mmercurio31->getCedtra()}</td>";
          $html .= "<td>{$mmercurio31->getPriape()} {$mmercurio31->getSegape()} {$mmercurio31->getPrinom()} {$mmercurio31->getSegnom()}</td>";
          $html .= "<td>".$gener08->getDetciu()."</td>";
          $html .= "<td>{$mmercurio31->getDireccion()}</td>";
          $html .= "<td>{$mmercurio31->getTelefono()}</td>";
          $html .= "<td>".number_format($mmercurio31->getSalario(),0,".",".")."</td>";
          $mercurio21 = $this->Mercurio21->findBySql("select * from mercurio31,mercurio21 where mercurio31.log= mercurio21.id AND mercurio31.log='{$mmercurio31->getLog()}' limit 1");
          $html .= "<td>{$mercurio21->getFecha()}</td>";
          if($mmercurio31->getAgencia() == '1')$agencia = 'NEIVA';
          if($mmercurio31->getAgencia() == '2')$agencia = 'GARZON';
          if($mmercurio31->getAgencia() == '3') $agencia = 'PITALITO';
          if($mmercurio31->getAgencia() == '4') $agencia = 'LA PLATA';
          $html .= "<td>{$agencia}</td>";
          $html .= "<td onclick=\"completar('{$mmercurio31->getId()}|{$mmercurio31->getNit()}|{$mmercurio31->getCedtra()}',this);\">".Tag::image("desktop/security-high.png", "width: 25px", "height: 25px", "style: cursor: pointer;")."</td>";
          $html .= "<td onclick=\"rechazar('{$mmercurio31->getId()}|{$mmercurio31->getNit()}|{$mmercurio31->getCedtra()}',this);\">".Tag::image("desktop/security-low.png", "width: 25px", "height: 25px", "style: cursor: pointer;")."</td>";
          $html .= "<td onclick=\"verdoc('{$mmercurio31->getId()}');\">".Tag::image("desktop/search.png","width: 25px", "height: 25px", "style: cursor: pointer;")."</td>";
          $html .= "</tr>";
      }
      $html .= "</tbody>";
      $html .= "</table>";
      $this->setParamToView("html", $html);
    }

    public function formularioAction(){
        $this->setResponse("ajax");
        $id = $this->getPostParam("id");
        $datos = preg_split("/\|/",$id);
        $mercurio31 = $this->Mercurio31->findFirst("id = '{$datos[0]}' ");
        $today = new Date();
        $formu ="";
        $formu .="<div id='TrabajadoresCampos' style='margin: auto;'>";
        $formu .= Tag::form("id: form-completar","autocomplete: off","enctype: multipart/form-data");
        $formu .= "<table cellpadding='5'style='width:90%; margin: 5px '> ";
        $formu .= "<tr>";   
        $formu .= "<td><label>Fecha de Afiliacion<label></td>";   
        $formu .= "<td>".TagUser::calendar("fecafi","value: {$mercurio31->getFecing()}","size: 15")."</td>";   
        $formu .= "<td><label>Capacidad de Trabajo<label></td>";   
        $formu .= "<td>".Tag::selectStatic("captra",array("N"=>"Normal","I"=>"Discapacidad"))."</td>";   
        $formu .= "</tr>";   
        $formu .= "<tr>";   
        $formu .= "<td><label>Tipo de Formulario :<label></td>";   
        $formu .= "<td>".Tag::selectStatic("tipfor",array("48"=>"Subsidio","49"=>"Servicios"),"style: width: 175px","use_dummy: true")."</td>";   
        $formu .= "<td><label>Agricola<label></td>";   
        $formu .= "<td>".Tag::selectStatic("agro",array("N"=>"No","S"=>"Si"))."</td>";   
        $formu .= "</tr>";   
        /*
        $formu .= "<tr>";   
        $formu .= "<td colspan='2'><label> Observaci&oacute;n: </label></td>";
        $formu .= "</tr>";   
        $formu .= "</tr>";  
        $formu .= "<tr>";   
        $formu .= "<td colspan='4'>".Tag::textArea("observacion","cols: 60","rows: 5","class: form-control")."</td>";
        $formu .= "</tr>";   
        */
        $formu .= "</table>";
        $formu .= Tag::endForm();
        $formu .="<table width='85%' >";
        $formu .= "<tr>";   
        $formu .= "<td>".Tag::button("Aprobar","class: submit","style: width: 300px; margin-left: 25%","onclick: aprobarF()")."</td>";   
        $formu .= "</tr>";   
        $formu .= "</table>";
        $formu .="</div>";
        return $this->renderText(json_encode($formu));
    }

    public function rechazarAction(){
        try{
            try{
                $this->setResponse("ajax");
                $fecha = new Date();
                $response = parent::startFunc();
                $modelos = array("mercurio31");
                $Transaccion = parent::startTrans($modelos);
                $mer31 = explode("|",$this->getPostParam("id"));
                $id = $mer31[0];
                $nit = $mer31[1];
                $cedtra = $mer31[2];
                $datos = preg_split("/\|/",$id);
                $motivo = $this->getPostParam("motivo");
                $today = new Date();
                $funcionario = parent::getActUser('usuario');
                $this->Mercurio31->updateAll("estado='X',fecest='$today',motivo='$motivo',usuario='$funcionario'","conditions: id = '{$id}' ");
                $mercurio31 = $this->Mercurio31->findBySql("SELECT * FROM mercurio31 WHERE id='$id' limit 1");

                $documento= $mercurio31->getCedtra();
                $result = parent::webService('autenticar',array("tipo"=>"T","documento"=>$nit, 'coddoc'=>Session::getData('coddoc')));
                $nombre = $result[0]['nombre'];
                $mercurio07 = $this->Mercurio07->findFirst("documento='{$nit}'");
                $email = $mercurio07->getEmail();
                $asunto = "Rechazo de Afiliación de trabajador";
                $msg = " Gracias por utilizar el Servicio en Linea de Comfamiliar Huila, le informamos que su Afiliacion fue rechazada debido a que no cumplio en su totalidad con los requisitos exigibles, le invitamos a que anexe la documentacion y/o informacion solicitada y nuevamente  realice  el  proceso, para que sus trabajadores  puedan recibir los beneficios que nuestra Entidad ofrece.
                    <br><br> IDENTIFICACION: $cedtra
                    <br> NOMBRE DEL TRABAJADOR: {$mercurio31->getPrinom()} {$mercurio31->getSegnom()} {$mercurio31->getPriape()} {$mercurio31->getSegape()}
                    <br> NIT: $nit
                    <br> CAUSAL DE DEVOLUCION: $motivo
                    <br><br>
                    Es muy importante que para cualquier inquietud y/o reclamo tenga en cuenta el Numero de solicitud y se comunique con nuestra Entidad, a traves de las lineas de atencion en las Agencias de Neiva, Garzon, Pitalito y  la Plata.
                    ";
                $file = "";
                parent::enviarCorreo("Rechazo Actualizacion de Datos ", $id, $email, $asunto, $msg, $file);
                parent::finishTrans();
                $response = parent::successFunc("Rechazo con exito",null);
                return $this->renderText(json_encode($response));
            }catch (DbException $e){
                parent::setLogger($e->getMessage());
                parent::ErrorTrans();
            }
        }catch (TransactionFailed $e){
            $response = parent::errorFunc("Error al Rechazar Empresa");
            return $this->renderText(json_encode($response));
        }
    }

    public function aprobarAction(){
        try{
            try{
                $this->setResponse("ajax");
                $fecha = new Date();
                $today = new Date();
                $hora = time();
                $hora = date("H:i:s");
                $response = parent::startFunc();
                $modelos = array("mercurio31");
                $Transaccion = parent::startTrans($modelos);
                $id = $this->getPostParam("id");
                $datos = preg_split("/\|/",$id);
                $funcionario = parent::getActUser('usuario');

                $mercurio31 = $this->Mercurio31->findFirst("id = '{$datos[0]}' ");
                $mercurio38 = $this->Mercurio38->count("numero = '{$mercurio31->getId()}' ");
                $mercurio07 = $this->Mercurio07->findFirst("documento = {$mercurio31->getNit()} AND tipo='E'");
                $nuevoTra = $mercurio31->getArray();

                $fecsis = date("Y-m-d",strtotime($fecha));
                //$fecafi = date("Y-m-d",strtotime($this->getPostParam("fecafi")));
                $fecafi = $this->getPostParam("fecafi");
                $coddep = substr($mercurio31->getCodciu(),0,2);
                $depnac = substr($mercurio31->getCiunac(),0,2);
                $horasdia=8;

                $observacion = $this->getPostParam("observacion");
                $nuevoTra['coddoc'] = $mercurio31->getCoddoc();
                $nuevoTra['nit'] = $mercurio31->getNit();
                $nuevoTra['cedtra'] = $mercurio31->getCedtra();
                $nuevoTra['priape'] = $mercurio31->getPriape();
                $nuevoTra['segape'] = $mercurio31->getSegape();
                $nuevoTra['prinom'] = $mercurio31->getPrinom();
                $nuevoTra['segnom'] = $mercurio31->getSegnom();
                $nuevoTra['sexo'] = $mercurio31->getSexo();
                $nuevoTra['direccion'] = $mercurio31->getDireccion();
                $nuevoTra['barrio'] = $mercurio31->getBarrio();
                $nuevoTra['telefono'] = $mercurio31->getTelefono();
                $nuevoTra['celular'] = $mercurio31->getCelular();
                $nuevoTra['email'] = $mercurio31->getEmail();
                $rural = $mercurio31->getRural();
                $tipviv = "";
                if($rural == 'S'){
                    $tipviv = 'R';
                }else{
                    $tipviv = 'U';
                }
                $nuevoTra['tipviv'] = $tipviv;
                $nuevoTra['vivienda'] = $mercurio31->getVivienda();
                $nuevoTra['codciu'] = $mercurio31->getCodciu();
                $nuevoTra['estciv'] = $mercurio31->getEstciv();
                $nuevoTra['fecnac'] = $mercurio31->getFecnac()->getUsingFormatDefault();
                $nuevoTra['ciunac'] = $mercurio31->getCiunac();
                $nuevoTra['fecing'] = $fecafi;
                if($mercurio31->getHoras() != 240) $horasdia = 4;
                $nuevoTra['horasdia'] = $horasdia; 
                $nuevoTra['horas'] = $mercurio31->getHoras();
                $nuevoTra['salario'] = $mercurio31->getSalario();
                $nuevoTra['agro'] = $this->getPostParam("agro");
                $nuevoTra['codcaj'] = $mercurio31->getCodcaj();
                $nuevoTra['tipafi'] = $mercurio31->getTipafi();
                $nuevoTra['captra'] = $this->getPostParam("captra");
                $nuevoTra['codcat'] = NULL;
                $nuevoTra['coddep'] = $coddep;
                $nuevoTra['depnac'] = $depnac;
                $nuevoTra['fecafi'] = $fecafi;
                $nuevoTra['estado'] = "N";
                $nuevoTra['fecsis'] = $fecsis;
                $nuevoTra['usuario'] = 'Consulta';
                $nuevoTra['observacion'] = "{$fecha->getUsingFormatDefault()} - Consulta - SE AFILIO EL TRABAJADOR CON EL NIT {$mercurio31->getNit()}";
                //Debug::addVariable("a",$nuevoTra);
                //throw new DebugException(0);
                $ruta = "digitalizacion\afiliados\ ".$mercurio31->getFecnac()->getUsingFormat('y')."\ ".$mercurio31->getFecnac()->getUsingFormat('m')."\ ".$mercurio31->getFecnac()->getUsingFormat('d')."\ {$mercurio31->getCedtra()}\ ";
                $nuevoTra['ruta'] = str_replace(" ","",$ruta);
                $nuevoTra['zona'] = $mercurio31->getCodzon();
                $nuevoTra['cargo'] = NULL;
                $difer = $today->diffDate($mercurio31->getFecnac());
                $difano = $difer/365;
                $nuevoTra['edad'] = $difano;
                $nomcor = $mercurio31->getPrinom()." ".$mercurio31->getSegnom()." ".$mercurio31->getPriape()." ".$mercurio31->getSegape();
                $nomcor = substr($nomcor,0,25);
                $nuevoTra['nomcor'] = $nomcor;
                $nuevoTra['tipfor'] = $this->getPostParam("tipfor");
                $nuevoTra['profesion'] = $mercurio31->getProfesion();
                $nuevoTra['cargo'] = $mercurio31->getCargo();
                $nuevoTra['hora'] = $hora;
                $nuevoTra['agencia'] = "0".$mercurio31->getAgencia();
                $nuevoTra['folios'] = "$mercurio38";


                $result = parent::webservice('IngresarTrabajador',$nuevoTra);
                Debug::addVariable("a",$result);
                Debug::addVariable("b",$nuevoTra);
                //throw new DebugException(0);

                if($result[0]['result'] == 'dbg'){
                    Debug::addVariable("a",$result);
                    //throw new DebugException(0);
                }
                if($result[0]['result'] == 'natural'){
                    $response = parent::errorFunc("El trabajador es conyuge permanente de esta persona natural",null);
                }else if($result[0]['result'] == 'false' || $result==FALSE){
                    $response = parent::errorFunc("El trabajador ya se encuentra registrado en Subsidio",null);
                }else{
                    $user=Auth::getActiveIdentity();
                    $Log = new Logger("File","{$user['usuario']}.log");
                    $Log->setFormat("[%date%] %controller%/%action% on %application% Error: %message%");
                    //$file = $this->carta_aprobacionAction($nuevoTra);
                    $file = "";
                    $msg = " Gracias por utilizar el Servicio en Linea de Comfamiliar Huila, a continuacion relacionamos la informacion de la Afiliacion de Empresa que cumplio con los requisitos exigidos por la Caja y asi sus trabajadores puedan recibir los beneficios que nuestra Entidad ofrece.
                    <br><br> ID: {$mercurio31->getId()}
                    <br><br> IDENTIFICACION: {$mercurio31->getCedtra()}
                    <br> NOMBRE DEL TRABAJADOR: {$mercurio31->getPrinom()} {$mercurio31->getSegnom()} {$mercurio31->getPriape()} {$mercurio31->getSegape()}
                    <br> NIT: {$mercurio31->getNit()}
                    <br><br>
                    Es muy importante que para cualquier inquietud y/o reclamo tenga en cuenta el Numero de solicitud y se comunique con nuestra Entidad, a traves de las lineas de atencion en las Agencias de Neiva, Garzon, Pitalito y  la Plata
                    ";
                    $asunto = "RV: Afiliacion de Trabajador";

                    parent::enviarCorreo("Confirmacion Trabajador", $datos[2], $mercurio31->getEmail(), "Afiliación de Trabajador", $msg, $file);
                    $today = new Date();
                    $this->Mercurio31->updateAll("estado='A',fecest='{$today->getUsingFormatDefault()}',motivo='{$observacion}'","conditions: id='{$datos[0]}' ");
                    /*
                    $usuario = $this->Mercurio07->findFirst("coddoc='{$mercurio31->getCoddoc()}' AND tipo='P' AND documento='{$mercurio31->getCedtra()}'");
                      if($usuario != FALSE){
                          $mercurio07 = new Mercurio07();
                          $mercurio07->setTransaction($Transaccion);
                            $mercurio07->setCodcaj($usuario->getCodcaj());
                          $mercurio07->setTipo('T');
                          $mercurio07->setCoddoc($usuario->getCoddoc());
                          $mercurio07->setDocumento($usuario->getDocumento());
                          $mercurio07->setNombre($usuario->getNombre());
                          $mercurio07->setEmail($usuario->getEmail());
                          $mercurio07->setClave($usuario->getClave());
                          $mercurio07->setFeccla($usuario->getFeccla()->getUsingFormatDefault());
                          $mercurio07->setAutoriza($usuario->getAutoriza());
                          $mercurio07->setAgencia($usuario->getAgencia());
                          $mercurio07->setFecreg($usuario->getFecreg());
                          if(!$mercurio07->save()){
                              parent::setLogger($mercurio07->getMessages());
                              parent::ErrorTrans(); 
                          }
                          $mercurio19 = $this->Mercurio19->find("coddoc='{$mercurio31->getCoddoc()}' AND tipo='P' AND documento='{$mercurio31->getCedtra()}'");
                          foreach($mercurio19 as $mmercurio19){
                              $mercurio19 = new Mercurio19();                  
                              $mercurio19->setTransaction($Transaccion);
                              $mercurio19->setCodcaj($mmercurio19->getCodcaj());
                              $mercurio19->setTipo('T');
                              $mercurio19->setCoddoc($mmercurio19->getCoddoc());
                              $mercurio19->setDocumento($mmercurio19->getDocumento());
                              $mercurio19->setCodigo($mmercurio19->getCodigo());
                              $mercurio19->setRespuesta($mmercurio19->getRespuesta());
                              if(!$mercurio19->save()){
                                  parent::setLogger($mercurio19->getMessages());
                                  parent::ErrorTrans();
                              }
                          }
                      }
                      */
                    //$this->Mercurio31->updateAll("estado='A',fecest='{$today->getUsingFormatDefault()}',motivo='{$observacion}',usuario='$funcionario'","conditions: id='{$datos[0]}' ");
                    parent::finishTrans();
                    $response = parent::successFunc("Envio de Informacion Exitosa",null);
                }
                return $this->renderText(json_encode($response));
            }catch (DbException $e){
                parent::setLogger($e->getMessage());
                parent::ErrorTrans();
            }
        }catch (TransactionFailed $e){
            $response = parent::errorFunc("Error confirmar afiliacion");
            return $this->renderText(json_encode($response));
        }
    }

    public function carta_aprobacionAction($nuevoTra){
      $today = new Date();
      $title = "Afiliacion Trabajador";
      $report = new FPDF("P","mm","A4");
      $report->AddPage();
      $report->setX(15);
      $report->SetFont('Arial','',11);
      $report->SetTextColor(0);
      $carta = ""; 
      $carta .= "\n\n, {$today->getDay()} de {$today->getMonthName()} de {$today->getYear()}.
          \n\n\n\nSenor:\n{$nuevoTra['priape']} {$nuevoTra['segape']} {$nuevoTra['prinom']} {$nuevoTra['segnom']}\n{$nuevoTra['direccion']}\n{$nuevoTra['codciu']} - COLOMBIA
          \n\n\nRespetado(a) Señor(a):
          \n\nEs grato informarles que su solicitud de afiliacion como empleador aportante, cumple con lo dispuesto en la Ley 21 de 1982 y el Decreto Reglamentario 341 de 1988, por lo cual este despecho la aprueba por delegacion expresa del Consejo Directivo de esta Entidad, otorgado al tenor de la normas existentes, en acto celebrado el 12 de mayo de 1988, registrado en Acta 116.
          \n\nPor lo anterior, pueden Ustedes comenzar a pagar sus aportes a partir de {$nuevoTra['fecafi']},tomando como base la determinado en el Articulo 127 del Codigo Sustantivo de Trabajo, el cual reza:
          \n\n\"Constituye salario no solo la remuneracion ordinaria, fija o variable, sino todo lo que recibe el trabajador en dinero o en especie como contraprestacion directa del servicio, sea cualquiera la dorma o denominacion que se adopte, como primas, sobresueldos, bonificaciones habituales, valor del trabajo suplementario de las horas extras, valor del trabajo en dias de descanso obligatorio, porcentajes sobre ventas y comisiones\"
          \n\nLos Empleadores tienen la obligacion de informar oportunamente todo hecho que modifique la calidad de afiliado al regimen del subsidio familiar, respecto de los trabajadores a su servicio. Le adjuntamos los formularios para la afiliacion inmediata de los trabajadores que relaciono debido a que el subsidio familiar monetario se cancela a partir de la afiliacion del trabajador a la Caja y la ley no permite retroactividad.-Decreto 784 de 1989 articulo 2.\n\n";
      $carta .= "Una vez el trabajador se afilie debe acercarse a retirar la tarjeta donde se le consignara la cuota monetaria.
          \n\nLa Ley 789 de 2002 establece sanciones con multa sucesivas hasta de mil (1000) salarios minimos mensuales a favor del subsidio al desempleo a los empleadores que incurran en cualquiera de estas conductas: no inscribir en una caja de compensacion familiar a todas las persons con las que tenga vinculacion laboral, siempre que exista obligacion; no pagar cumplidamente los aportes de las cajas y no informar las novedades laborales de sus trabajadores frente a las cajas. Asi mismo debe informar de inmediato los cambios que presente su empresa, tales como direccion, telefono, representante legal etc.
          \n\nCancele oportunamente los aportes parafiscales a traves de planilla unica o asistida dentro del plazo estipulado por la ley de acuerdo al ultimo digito del NIT -Decreto 1464 de 2005-. Evitese el cobro de intereses por mora -Ley 1066 de 2006- y las sanciones previstas en la ley.
          \n\nCuando un trabajador quede cesante, debe acercarse a retirar el formulario para aspirar al subsidio de desempleo.
          \n\n\nCordialmente,
          \n\n\n\n
          \nDirector Administrativo
          ";
      
      $report->MultiCell(0,4,UTF8_DECODE($carta),0,"J",0);
      $report->Ln();
      $report->Ln();
      $report->setX(15);
      $report->Cell(100,4,"Esta certificacion se expide en RIOHACHA a solicitud del interesado el dia {$today->getDay()} de {$today->getMonthName()} de {$today->getYear()}. ",0,0,"L",0,0);
      $report->Ln();
      $file = "public/temp/reportes/reporte_aprobacion_".$nuevoTra['cedtra'];
      if(file_exists($file."pdf")) unlink($file.".pdf");
      echo $report->Output($file.".pdf","F");
      $this->setResponse('view');
      return $file.".pdf";
    }

public function verdocumentosAction(){
        $this->setResponse("ajax");
        $trabajador= $this->getPostParam("trabajador");
        $mercurio38 = $this->Mercurio38->find("numero = '$trabajador'");
        $mercurio31 = $this->Mercurio31->findFirst("id = '$trabajador'");
        $lista = "";
        $lista .= "<table class='resultado-consul' style='width: 100%; border: 1px double #000; border-collapse: separate; margin: auto;'>";
        $nombre=$mercurio31->getPriape()." ".$mercurio31->getSegape()." ".$mercurio31->getPrinom()." ".$mercurio31->getSegnom();
        $lista .= "<thead><tr><th colspan='2' style='text-align: left; font-size: 1.2em; height: 20px;'>Trabajador: $nombre </th></tr></thead>";
        $lista .= "<thead><tr style='background-color: #959595; font-size: 1.2em;'>";
        $lista .= "<th style='height: 18px;'>Item</th><th>Archivo</th>";
        $lista .= "<th>&nbsp;</th>";
        $lista .= "</tr></thead>";
        $item = 1;
        foreach($mercurio38 as $mmercurio38){
            $mercurio12 = $this->Mercurio12->findFirst("coddoc = '{$mmercurio38->getCoddoc()}'");
            $lista .= "<tr>";
            $lista .= "<td>{$item}</td>";
            $lista .= "<td class='list-arc'>{$mercurio12->getDetalle()}</td>";
            $lista .= "<td><a target='_blank' href='../{$mmercurio38->getNomarc()}'>".Tag::image("desktop/search.png","width: 25px", "height: 25px", "style: cursor: pointer;")."</a></td>";
            $lista .= "</tr>";
            $item++;
        }
        $lista .= "</table>";
        return $this->renderText(json_encode($lista));
    }
 public function reportea_Action(){
 
 
 
 
 
 }
}
?>
