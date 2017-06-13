<?php
class Mercurio30Controller extends ApplicationController {
    private $title = "Afiliacion Empresas";
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
      $this->setParamToview('titu lo',$this->title);
      $html = "";
      $html .= "<table class='resultado-consul' border=1>";
      $html .= "<thead>";
      $html .= "<th>Nit</th>";
      $html .= "<th>Razsoc</th>";
      $html .= "<th>Representante</th>";
      $html .= "<th>Ciudad</th>";
      $html .= "<th>Direccion</th>";
      $html .= "<th>Telefono</th>";
      $html .= "<th>Total Trabajador</th>";
      $html .= "<th>Total Nomina</th>";
      $html .= "<th>Fecha</th>";
      $html .= "<th>Agencia</th>";
      $html .= "<th>Aprobar</th>";
      $html .= "<th>Rechazar</th>";
      $html .= "<th>Documentos</th>";
      $html .= "</thead>";
      $html .= "<tbody>";
      $a = parent::getActUser("tipfun");
     if($a=="ADAD")
        $mercurio30 = $this->Mercurio30->find("estado='P' ");
     else
         $mercurio30 = $this->Mercurio30->findAllBySql("select mercurio30.* from mercurio30,mercurio07 where mercurio30.estado='P' and mercurio07.agencia='$filt' and mercurio07.documento=mercurio30.nit AND mercurio30.usuario='".Session::getData('usuario')."'");

      foreach($mercurio30 as $mmercurio30){
          $html .= "<tr>";
          $html .= "<td>{$mmercurio30->getNit()}</td>";
          $html .= "<td>{$mmercurio30->getRazsoc()}</td>";
          $html .= "<td>{$mmercurio30->getNomrep()}</td>";
          $gener08 = $this->Gener08->findFirst("codciu = {$mmercurio30->getCodciu()}");
          $html .= "<td>{$gener08->getDetciu()}</td>";
          $html .= "<td>{$mmercurio30->getDireccion()}</td>";
          $html .= "<td>{$mmercurio30->getTelefono()}</td>";
          $html .= "<td>{$mmercurio30->getTottra()}</td>";
          $html .= "<td>{$mmercurio30->getTotnom()}</td>";
          $mercurio21 = $this->Mercurio21->findBySql("select * from mercurio30,mercurio21 where mercurio30.log= mercurio21.id AND mercurio30.log='{$mmercurio30->getLog()}' limit 1");
          $html .= "<td>{$mercurio21->getFecha()}</td>";
          
          if($mmercurio30->getAgencia() == '1')$agencia = 'NEIVA';
          if($mmercurio30->getAgencia() == '2')$agencia = 'GARZON';
          if($mmercurio30->getAgencia() == '3') $agencia = 'PITALITO';
          if($mmercurio30->getAgencia() == '4') $agencia = 'LA PLATA';
          $html .= "<td>{$agencia}</td>";
          $html .= "<td onclick=\"completar('{$mmercurio30->getId()}',this);\">".Tag::image("desktop/security-high.png", "width: 25px", "height: 25px", "style: cursor: pointer;")."</td>";
          $html .= "<td onclick=\"rechazar('{$mmercurio30->getId()}|{$mmercurio30->getNit()}',this);\">".Tag::image("desktop/security-low.png", "width: 25px", "height: 25px", "style: cursor: pointer;")."</td>";
          $html .= "<td onclick=\"verdoc('{$mmercurio30->getId()}');\">".Tag::image("desktop/search.png","width: 25px", "height: 25px", "style: cursor: pointer;")."</td>";
          $html .= "</tr>";
      }
      $html .= "</tbody>";
      $html .= "</table>";
      $this->setParamToView("html", $html);
    }

    public function formularioAction(){
        $this->setResponse("ajax");
        $id = $this->getPostParam("id");
        $this->Mercurio30->findFirst(" id = '$id' ");
        $mercurio30 = $this->Mercurio30->findFirst("id = '$id'");
        $today = new Date();
        $formu ="";
        $formu .="<div id='empresaCampos'>";
        $formu .= Tag::form("id: form-completar","autocomplete: off","enctype: multipart/form-data");
        $formu .= "<table cellpadding='5'style='width:100%; margin: 10px'> ";
        $formu .= "<tr>";   
        /*
        $formu .= "<td><label>Resolucion de Afiliacion: <label></td>";  
        $formu .= "<td>".Tag::textField("resafi","maxlengt: 50","use_dummy: true","style: width: 105px")."</td>";
        */
        $formu .= "<td><label>Sector</label></td>";
        $formu .= "<td>".Tag::select('sector',$this->Migra091->find('iddefinicion=16'),'using: iddetalledef,detalledefinicion','useDummy: yes','class: form-control') ."</td>";
        $formu .= "<td><label>Fecha de Afiliaci&oacute;n:<label></td>";   
        $formu .= "<td>".TagUser::calendar("fecafi","size: 15")."</td>";   
        $formu .= "</tr>";   
        $formu .= "<tr>";   
        $formu .= "<td><label>Fecha de inicio de aportes:<label></td>";   
        $formu .= "<td>".TagUser::calendar("fecapo","size: 15")."</td>";   
        $formu .= "<td><label>Contratista:<label></td>";
        $formu .= "<td>".Tag::selectStatic("contratista",array('S'=>'SI','N'=>'NO'),"use_dummy: true","style: width: 105px")."</td>";
        $formu .= "</tr>";   
        $formu .= "<tr>";   
        $formu .= "<td><label>Clase  de Aportante:<label></td>";
        $claapo = array('2653'=>'APORTANTE CON 200 O MAS COTIZANTES','2654'=>'APORTANTE CON MENOS DE 200 COTIZANTES','2655'=>'INDEPENDIENTE','2874'=>'APORTANTE MIPYME (LEY 590 DE 2000)','2875'=>'APORTANTE PRIMER EMPLEO (LEY 1429 DE 2010)');
        $formu .= "<td>".Tag::selectStatic("claapo",$claapo,"use_dummy: true","style: width: 105px")."</td>";
        $formu .= "<td><label>Tipo de Aportante:<label></td>";
        $formu .= "<td>".Tag::select("tipapo",$this->Migra091->find("iddefinicion = 34"),"using: iddetalledef,detalledefinicion","use_dummy: true","style: width: 105px")."</td>";
        $formu .= "</tr>";   
        $formu .= "<tr>";   
        $formu .= "<td><label>Indice de Aportes:<label></td>";
        $formu .= "<td>".Tag::select("indice",$this->Migra091->find("iddefinicion='18' and (concepto='DEPENDIENTES' OR concepto='APORTANTES DE TRABAJADORES DEPENDIENTES')"),"using: iddetalledef,detalledefinicion","use_dummy: true","style: width: 105px")."</td>";
        $formu .= "</tr>";  
        /*
        $formu .= "<tr>";   
        $formu .= "<td colspan='2'><label> Observaci&oacute;n: </label></td>";
        $formu .= "</tr>";   
        $formu .= "<tr>";   
        $formu .= "<td colspan='4'>".Tag::textArea("observacion","cols: 80","rows: 5","class: form-control")."</td>";
        $formu .= "</tr>";   
        */
        $formu .= "</table>";
        $formu .= Tag::endForm();
        $formu .="<table width='100%' >";
        $formu .= "<tr>";   
        $formu .= "<td>".Tag::button("Aprobar","class: submit","style: width: 100px; margin-left: 50%","onclick: aprobarF()")."</td>";   
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
                $modelos = array("mercurio30");
                $Transaccion = parent::startTrans($modelos);
                $mer30 = explode("|",$this->getPostParam("nit"));
                $id = $mer30[0];
                $nit = $mer30[1];
                $mercurio30 = $this->Mercurio30->findFirst("id = {$id}");
                $motivo = $this->getPostParam("motivo");
                $today = new Date();
                $funcionario = parent::getActUser('usuario');
                $this->Mercurio30->updateAll("estado='X',fecest='$today',motivo='$motivo',usuario='$funcionario'","conditions: nit = '$nit'");
                $result = parent::webService('autenticar',array("tipo"=>"E","documento"=>$nit, 'coddoc'=>Session::getData('coddoc')));
                $nombre = $result[0]['nombre'];
                $mercurio07 = $this->Mercurio07->findBySql("SELECT * FROM mercurio07 WHERE documento='{$nit}' limit 1");
                $email = $mercurio07->getEmail();
                $asunto = "Rechazo de Afiliacion de Empresa";
                $msg = " Gracias por utilizar el Servicio en Linea de Comfamiliar Huila, le informamos que su Afiliacion fue rechazada debido a que no cumplio en su totalidad con los requisitos exigibles,  le invitamos a que anexe la documentacion y/o informacion solicitada y nuevamente  realice  el  proceso, para que sus trabajadores  puedan recibir los  beneficios  que  nuestra Entidad ofrece.
                    <br><br> NIT: $nit
                    <br> NOMBRE DE LA EMPRESA: {$mercurio30->getRazsoc()}
                    <br> FECHA DE RADICACION: {$today->getUsingFormatDefault()}
                    <br> MOTIVO: $motivo
                    <br><br>
                    Es muy importante que para cualquier inquietud y/o reclamo tenga en cuenta el Numero de solicitud y se comunique con nuestra Entidad, a traves de las lineas de atencion en las Agencias de Neiva, Garzon, Pitalito y  la Plata
                    ";
                $file = "";
                parent::enviarCorreo("Rechazo Actualizacion de Datos ", $id, $email, $asunto, $msg, $file);
                parent::finishTrans();
                $response = parent::successFunc("Actualizacion de Informacion con Exito",null);
                return $this->renderText(json_encode($response));
            }catch (DbException $e){
                parent::setLogger($e->getMessage());
                parent::ErrorTrans();
            }
        }catch (TransactionFailed $e){
            $response = parent::errorFunc("Error al Rechazar la Empresa");
            return $this->renderText(json_encode($response));
        }
    }

    public function aprobarAction(){
        try{
            try{
                $this->setResponse("ajax");
                $response = parent::startFunc();
                $modelos = array("mercurio30");
                $fecha = new Date();
                $Transaccion = parent::startTrans($modelos);
                $id = $this->getPostParam("id");
                $observacion = $this->getPostParam("observacion");
                $mercurio30 = $this->Mercurio30->findFirst("id = '$id'");
                $mercurio46 = $this->Mercurio46->find("log = {$mercurio30->getLog()}");
                $ced = "";
                if($mercurio46 != FALSE){
                    foreach($mercurio46 as $mmercurio46){
                        if($ced != $mmercurio46->getDocumento()){
                            $celular = "";
                            $telefono = "";
                            if($mmercurio46->getCelular() != "" && $mmercurio46->getCelular() != null)
                                $celular = $mmercurio46->getCelular();
                            if($mmercurio46->getTelefono() != "" && $mmercurio46->getTelefono() != null)
                                $telefono = $mmercurio46->getTelefono();
                            $ruta = "digitalizacion\afiliados\ ".$mmercurio46->getFecnac()->getUsingFormat('y')."\ ".$mmercurio46->getFecnac()->getUsingFormat('m')."\ ".$mmercurio46->getFecnac()->getUsingFormat('d')."\ {$mmercurio46->getDocumento()}\ ";
                            $param = array(
                                    'cedtra'=>$mmercurio46->getDocumento(),
                                    'coddoc'=>$mmercurio46->getCoddoc(),
                                    'prinom'=>$mmercurio46->getPrinom(),
                                    'segnom'=>$mmercurio46->getSegnom(),
                                    'priape'=>$mmercurio46->getPriape(),
                                    'segape'=>$mmercurio46->getSegape(),
                                    'sexo'=>$mmercurio46->getSexo(),
                                    'fecnac'=>$mmercurio46->getFecnac(),
                                    'fecsis'=>$fecha->getUsingFormatDefault(),
                                    'usuario'=>'Consulta',
                                    'celular'=> $celular,
                                    'telefono' => $telefono,
                                    'ruta' => str_replace(" ","",$ruta)
                                    );
                            $idrep = parent::webService('IngresarAportes015',$param);
                            if($mmercurio46->getTipper() == 'idrepresentante'){
                                $nuevaEmp['idrepresentante'] = $idrep[0]['result'];
                                $idper = $nuevaEmp['idrepresentante'];
                            }else{
                                $nuevaEmp['idjefepersonal'] = $idrep[0]['result'];
                                $idper = $nuevaEmp['idjefepersonal'];
                            }
                            $ced = $mmercurio46->getDocumento();
                        }else{
                            if($mmercurio46->getTipper() == 'idrepresentante'){
                                $nuevaEmp['idrepresentante'] = $idper;
                            }else{
                                $nuevaEmp['idjefepersonal'] = $idper;
                            }
                        }
                        if(!isset($nuevaEmp['idjefepersonal'])){
                            $nuevaEmp['idjefepersonal'] = $nuevaEmp['idrepresentante'];
                        }
                    }
                }
                $mercurio02 = $this->Mercurio02->findFirst("codcaj = '".Session::getDATA('codcaj')."'");

                if($mercurio30->getTipsoc() == '101')
                    $nuevaEmp['coddoc'] = $mercurio30->getCoddoc();
                else
                    $nuevaEmp['coddoc'] = '5';
                $nuevaEmp['nit'] = $mercurio30->getNit();
                $nuevaEmp['digver'] = $mercurio30->getDigver();
                $nuevaEmp['nit'] = $mercurio30->getNit();
                //$nuevaEmp['codsuc'] = $mercurio30->getCodsuc();
                $nuevaEmp['codsuc'] = "000";
                $nuevaEmp['principal'] = "S";
                $nuevaEmp['razsoc'] = $mercurio30->getRazsoc();
                $nuevaEmp['direccion'] = $mercurio30->getDireccion();
                //$nuevaEmp['coddep'] = $mercurio30->getCoddep();
                $nuevaEmp['coddep'] = substr($mercurio30->getCodciu(),0,2);
                $nuevaEmp['codciu'] = $mercurio30->getCodciu();
                $nuevaEmp['depcor'] = substr($mercurio30->getCiucor(),0,2);
                $nuevaEmp['ciucor'] = $mercurio30->getCiucor();
                $nuevaEmp['barcor'] = $mercurio30->getBarcor();
                $nuevaEmp['barrio'] = $mercurio30->getBarrio();
                $nuevaEmp['codzon'] = $mercurio30->getCodzon();
                $nuevaEmp['telefono'] = $mercurio30->getTelefono();
                $nuevaEmp['celular'] = $mercurio30->getCelular();
                $nuevaEmp['fax'] = $mercurio30->getFax();
                $nuevaEmp['email'] = $mercurio30->getEmail();
                $nuevaEmp['codact'] = $mercurio30->getCodact();
                $nuevaEmp['objemp'] = $mercurio30->getObjemp();
                $nuevaEmp['clase'] = $mercurio30->getCodact(); // Clase LLeva lo mismo que la actividad economica
                $nuevaEmp['fecafi'] = $this->getPostParam("fecafi");
                $nuevaEmp['numtra'] = $mercurio30->getTottra();
                //$nuevaEmp['fecsis'] = $this->getPostParam("fecsis");
                $nuevaEmp['cedrep'] = $mercurio30->getCedrep();
                $nuevaEmp['nomrep'] = $mercurio30->getNomrep();
                $nuevaEmp['digver'] = $mercurio30->getDigver();
                $nuevaEmp['feccon'] = $mercurio30->getFeccon()->getUsingFormatDefault();
                $nuevaEmp['sigla'] = $mercurio30->getSigla();
                $nuevaEmp['seccional'] = str_pad($mercurio30->getAgencia(),2,0,STR_PAD_LEFT);
                $nuevaEmp['contratista'] = $this->getPostParam("contratista");
                $nuevaEmp['colegio'] = 'N';
                $nuevaEmp['claapo'] = $this->getPostParam("claapo");
                $nuevaEmp['indice'] = $this->getPostParam("indice");
                $nuevaEmp['tipapo'] = $this->getPostParam("tipapo");
                $nuevaEmp['fecapo'] = $this->getPostParam("fecapo");
                $nuevaEmp['sector'] = $this->getPostParam("sector");
                $fecafi = $this->getPostParam("fecafi");
                $ruta = "digitalizacion\empresa\ ".substr($fecafi,0,4)."\ ".substr($fecafi,5,2)."\ ".substr($fecafi,8,2)."\ {$mercurio30->getNit()}\ ";
                $nuevaEmp['ruta'] = str_replace(" ","",$ruta);
                $nuevaEmp['observacion'] = "{$fecha->getUsingFormatDefault()} - Consulta - SE REGISTRO LA EMPRESA CON EL NIT {$mercurio30->getNit()} DESDE EL PORTAL DE CONSULTA EN LINEA";
                $date = new Date();
                $nuevaEmp['fecsis'] = $date->getUsingFormatDefault();
                $nuevaEmp['usuario'] = 'Consulta';
                $nuevaEmp['clasoc'] = $mercurio30->getTipsoc();
/*
                Debug::addVariable("a",$nuevaEmp['depcor']);
                Debug::addVariable("b",$nuevaEmp['ciucor']);
                Debug::addVariable("c",$nuevaEmp['barcor']);
                Debug::addVariable("d",$nuevaEmp['fecapo']);
                Debug::addVariable("e",$nuevaEmp['usuario']);
                Debug::addVariable("f",$nuevaEmp['ruta']);
                Debug::addVariable("g",$nuevaEmp['barrio']);
                Debug::addVariable("t",$nuevaEmp['clasoc']);
                throw new DebugException(0);
*/
                Debug::addVariable("a",$nuevaEmp);
                //throw new DebugException(0);    
                $result = parent::webService('IngresarEmpresa',$nuevaEmp);
                Debug::addVariable("a",$result);
                //throw new DebugException(0);
                if($result[0]['result'] == false || $result[0]['result'] == 'IF' || $result[0]['result'] == 'Else'){
                    $response = parent::errorFunc("La empresa ya se encuentra registrada",null);
                }else{
                    $user=Auth::getActiveIdentity();
                    $Log = new Logger("File","{$user['usuario']}.log");
                    $Log->setFormat("[%date%] %controller%/%action% on %application% Error: %message%");
                    $razsoc= $nuevaEmp['razsoc'];
                    $email= $nuevaEmp['email'];
                    //$file = $this->carta_aprobacionAction($nuevaEmp);
                    $file = "";
                    //$msg = "Cordial Saludos<br><br>Adjunto la carta de aceptacion como nuevo afiliado a la {$mercurio02->getRazsoc()} <br><br>Bienvenidos a la familia COMFAMILIAR HUILA, mas familias felices.<br><br>Atentamente,<br><br><br>Director Administrativo ";
                    $msg = " Gracias por utilizar el Servicio en Linea de Comfamiliar Huila, a continuacion relacionamos la informacion de la Afiliacion de Empresa que cumplio con los requisitos exigidos por la Caja y asi sus trabajadores puedan recibir los beneficios que nuestra Entidad ofrece.
                        <br><br> ID: {$mercurio30->getId()}
                    <br> NIT: {$mercurio30->getNit()}
                    <br> NOMBRE DE LA EMPRESA: {$mercurio30->getRazsoc()}
                    <br> FECHA DE RADICACION: {$fecha->getUsingFormatDefault()}
                    <br><br>
                        Es muy importante que para cualquier inquietud y/o reclamo tenga en cuenta el Numero de solicitud y se comunique con nuestra Entidad, a traves de las lineas de atencion en las Agencias de Neiva, Garzon, Pitalito y  la Plata
                        ";
                    $asunto = "confirmacion de afiliacion";
                    parent::enviarCorreo("Afiliacion Empresa",$razsoc,$email,$asunto,$msg,$file);
                    $today = new Date();
                    $usuario = $this->Mercurio07->findFirst("documento={$nuevaEmp['nit']} AND tipo='P'");
                    $this->Mercurio30->updateAll("estado='A',fecest='{$today->getUsingFormatDefault()}',motivo='{$observacion}'","conditions: nit='{$nuevaEmp['nit']}'");
                    if($usuario != FALSE){
                        $mercurio07 = new Mercurio07();
                        $mercurio07->setTransaction($Transaccion);
                        $mercurio07->setCodcaj($usuario->getCodcaj());
                        $mercurio07->setTipo('E');
                        $mercurio07->setCoddoc($usuario->getCoddoc());
                        $mercurio07->setDocumento($usuario->getDocumento());
                        $mercurio07->setNombre($usuario->getNombre());
                        $mercurio07->setEmail($usuario->getEmail());
                        $mercurio07->setClave($usuario->getClave());
                        $mercurio07->setFeccla($usuario->getFeccla()->getUsingFormatDefault());
                        $mercurio07->setAutoriza($usuario->getAutoriza());
                        $mercurio07->setAgencia($usuario->getAgencia());
                        //$mercurio07->setFecreg($usuario->getFecreg()->getUsingFormatDefault());
                        if(!$mercurio07->save()){
                            parent::setLogger($mercurio07->getMessages());
                            parent::ErrorTrans(); 
                        }
                        $mercurio19 = $this->Mercurio19->find("documento={$nuevaEmp['nit']} AND tipo='P'");
                        foreach($mercurio19 as $mmercurio19){
                            $mercurio19 = new Mercurio19();                  
                            $mercurio19->setTransaction($Transaccion);
                            $mercurio19->setCodcaj($mmercurio19->getCodcaj());
                            $mercurio19->setTipo('E');
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
                    parent::finishTrans();
                    $response = parent::successFunc("Envio de Información Exitosa",null);
                }
                return $this->renderText(json_encode($response));
            }catch (DbException $e){
                parent::setLogger($e->getMessage());
                parent::ErrorTrans();
            }
        }catch (TransactionFailed $e){
            $response = parent::errorFunc("Error confirmar afiliación");
            return $this->renderText(json_encode($response));
        }
    }

    public function carta_aprobacionAction($nuevaEmp){
        $mercurio02 = $this->Mercurio02->findFirst("codcaj = '".Session::getDATA('codcaj')."'");
        $gener08 = $this->Gener08->findFirst("codciu = '".$mercurio02->getCodciu()."'");
        $today = new Date();
        $title = "";
        $report = new FPDF("P","mm","A4");
        $report->AddPage();
        $report->setX(15);
        $report->SetFont('Arial','',11);
        $report->SetTextColor(0);
        $carta = ""; 
        $carta .= "{$gener08->getDetciu()}, {$today->getDay()} de {$today->getMonthName()} de {$today->getYear()}.
            \n\n\n\nSeñor:\n{$nuevaEmp['nomrep']}\nRepresentate Legal\n{$nuevaEmp['razsoc']}\n{$nuevaEmp['direccion']}\n{$nuevaEmp['codciu']} - COLOMBIA
            \n\n\nRespetado (a) Señor (a):
                \n\nEs grato informarles que su solicitud de afiliacion como empleador aportante, cumple con lo dispuesto en la Ley 21 de 1982 y el Decreto Reglamentario 341 de 1988, por lo cual este despecho la aprueba por delegacion expresa del Consejo Directivo de esta Entidad, otorgado al tenor de la normas existentes, en acto celebrado el 12 de mayo de 1988, registrado en Acta 116.
                 \n\nPor lo anterior, pueden Ustedes comenzar a pagar sus aportes a partir de {$nuevaEmp['fecafi']},tomando como base la determinado en el Articulo 127 del Codigo Sustantivo de Trabajo, el cual reza:
                     \n\n\"Constituye salario no solo la remuneracion ordinaria, fija o variable, sino todo lo que recibe el trabajador en dinero o en especie como contraprestacion directa del servicio, sea cualquiera la dorma o denominacion que se adopte, como primas, sobresueldos, bonificaciones habituales, valor del trabajo suplementario de las horas extras, valor del trabajo en dias de descanso obligatorio, porcentajes sobre ventas y comisiones\"
                     \n\nLos Empleadores tienen la obligacion de informar oportunamente todo hecho que modifique la calidad de afiliado al regimen del subsidio familiar, respecto de los trabajadores a su servicio. Le adjuntamos los formularios para la afiliacion inmediata de los trabajadores que relaciono debido a que el subsidio familiar monetario se cancela a partir de la afiliacion del trabajador a la Caja y la ley no permite retroactividad.-Decreto 784 de 1989 articulo 2.\n\n";
        $carta .= "Una vez el trabajador se afilie debe acercarse a retirar la tarjeta donde se le consignara la cuota monetaria.
            \n\nLa Ley 789 de 2002 establece sanciones con multa sucesivas hasta de mil (1000) salarios minimos mensuales a favor del subsidio al desempleo a los empleadores que incurran en cualquiera de estas conductas: no inscribir en una caja de compensacion familiar a todas las persons con las que tenga vinculacion laboral, siempre que exista obligacion; no pagar cumplidamente los aportes de las cajas y no informar las novedades laborales de sus trabajadores frente a las cajas. Asi mismo debe informar de inmediato los cambios que presente su empresa, tales como direccion, telefono, representante legal etc.
            \n\nCancele oportunamente los aportes parafiscales a traves de planilla unica o asistida dentro del plazo estipulado por la ley de acuerdo al ultimo digito del NIT -Decreto 1464 de 2005-. Evitese el cobro de intereses por mora -Ley 1066 de 2006- y las sanciones previstas en la ley.
            \n\nCuando un trabajador quede cesante, debe acercarse a retirar el formulario para aspirar al subsidio de desempleo.
            \n\n\nCordialmente,\n\n\n
            \nDirector Administrativo";
        $report->MultiCell(0,4,UTF8_DECODE($carta),0,"J",0);
        $report->Ln();
        $report->Ln();
        $report->setX(15);
        $report->Cell(100,4,"Esta certificacion se expide en RIOHACHA a solicitud del interesado el dia {$today->getDay()} de {$today->getMonthName()} de {$today->getYear()}. ",0,0,"L",0,0);
        $report->Ln();
        $file = "public/temp/reportes/reporte_aprobacion_".$nuevaEmp['nit'];
        if(file_exists($file."pdf")) unlink($file.".pdf");
        ob_end_clean();
        echo $report->Output($file.".pdf","F");
        $this->setResponse('view');
        return $file.".pdf";
    }

    public function verdocumentosAction(){
        $this->setResponse("ajax");
        $empresa = $this->getPostParam("empresa");
        $mercurio37 = $this->Mercurio37->find("numero = '$empresa'");
        $mercurio30 = $this->Mercurio30->findFirst("id = '$empresa'");
        $lista = "";
        $lista .= "<table class='resultado-consul' style='width: 100%; border: 1px double #000; border-collapse: separate; margin: auto;'>";
        $lista .= "<thead><tr><th colspan='2' style='text-align: left; font-size: 1.2em; height: 20px;'>Empresa: {$mercurio30->getRazsoc()}</th></tr></thead>";
        $lista .= "<thead><tr style='background-color: #959595; font-size: 1.2em;'>";
        $lista .= "<th style='height: 18px;'>Item</th><th>Archivo</th>";
        $lista .= "<th>&nbsp;</th>";
        $lista .= "</tr></thead>";
        $item = 1;
        foreach($mercurio37 as $mmercurio37){
            $mercurio12 = $this->Mercurio12->findFirst("coddoc = '{$mmercurio37->getCoddoc()}'");
            $lista .= "<tr>";
            $lista .= "<td>{$item}</td>";
            $lista .= "<td class='list-arc'>{$mercurio12->getDetalle()}</td>";
            $lista .= "<td><a target='_blank' href='../{$mmercurio37->getNomarc()}'>".Tag::image("desktop/search.png","width: 25px","height: 25px","style: cursor: pointer;")."</a></td>";
            $lista .= "</tr>";
            $item++;
        }
        $lista .= "</table>";
        return $this->renderText(json_encode($lista));
    }
}
?>
