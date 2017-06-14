<?php
class Mercurio32Controller extends ApplicationController{
	
	private $title = "Afiliacion Conyuge";

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
        $html .= "<th>Cedula Trabajador</th>";
        $html .= "<th>Cedula Conyuge</th>";
        $html .= "<th>Nombre</th>";
        $html .= "<th>Direccion</th>";
        $html .= "<th>Telefono</th>";
        $html .= "<th>Fecha</th>";
        $html .= "<th>Aprobar</th>";
        $html .= "<th>Rechazar</th>";
        $html .= "<th>Documentos</th>";
        $html .= "<th>Informacion</th>";
        $html .= "</thead>";
        $html .= "<tbody>";
        if($filt =="ADAD")
            $mercurio32 = $this->Mercurio32->find("estado='P'");
        else
            $mercurio32 = $this->Mercurio32->findAllBySql("select mercurio32.* from mercurio32,mercurio07 where mercurio32.estado='P' and mercurio07.agencia='$filt' and mercurio07.documento=mercurio32.cedtra AND mercurio32.usuario='".Session::getData('usuario')."'");
        foreach($mercurio32 as $mmercurio32){
            $html .= "<tr>";
            $html .= "<td>{$mmercurio32->getCedtra()}</td>";
            $html .= "<td>{$mmercurio32->getCedcon()}</td>";
            $html .= "<td>{$mmercurio32->getPriape()} {$mmercurio32->getSegape()} {$mmercurio32->getPrinom()} {$mmercurio32->getSegnom()}</td>";
            $html .= "<td>{$mmercurio32->getDireccion()}</td>";
            $html .= "<td>{$mmercurio32->getTelefono()}</td>";
            $mercurio21 = $this->Mercurio21->findBySql("select * from mercurio32,mercurio21 where mercurio32.log= mercurio21.id AND mercurio32.log='{$mmercurio32->getLog()}' limit 1");
            $html .= "<td>{$mercurio21->getFecha()}</td>";
            $html .= "<td onclick=\"aprobarF('{$mmercurio32->getId()}',this);\">".Tag::image("desktop/security-high.png", "width: 25px", "height: 25px", "style: cursor: pointer;")."</td>";
            $html .= "<td onclick=\"rechazar('{$mmercurio32->getId()}',this);\">".Tag::image("desktop/security-low.png", "width: 25px", "height: 25px", "style: cursor: pointer;")."</td>";
            $html .= "<td onclick=\"verdoc('{$mmercurio32->getId()}');\">".Tag::image("desktop/search.png","width: 25px", "height: 25px", "style: cursor: pointer;")."</td>";
            $html .= "<td onclick=\"info('{$mmercurio32->getId()}');\">".Tag::image("desktop/search.png","width: 25px", "height: 25px", "style: cursor: pointer;")."</td>";
            $html .= "</tr>";
        }
        $html .= "</tbody>";
        $html .= "</table>";
        $this->setParamToView("html", $html);
    }

    public function formularioAction(){
        $this->setResponse("ajax");
        $formu ="";
        $formu .="<div id='empresaCampos'>";
        $formu .= Tag::form("id: form-completar","autocomplete: off","enctype: multipart/form-data");
        $formu .= "<table cellpadding='5'style='width:80%; margin: 10px '> ";
        
        $formu .= "<tr>";   
        //$formu .= "<td><label>Compa&ntilde;era Permanente:<label></td>";  
        //$formu .= "<td>".Tag::selectStatic("comper",array("S"=>"SI","N"=>"NO"),"use_dummy: true" )."</td>";  
        //$formu .= "<td><label>Fecha de Afiliacion:<label></td>";
        //$formu .= "<td>".TagUser::Calendar("fecafi","size: 20")."</td>";   
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
                $response = parent::startFunc();
                $modelos = array("mercurio32");
                $Transaccion = parent::startTrans($modelos);
                $id = $this->getPostParam("id");
                $motivo = $this->getPostParam("motivo");
                $mercurio32 = $this->Mercurio32->findFirst("id=$id");
                $today = new Date();
                $funcionario = parent::getActUser('usuario');
                $this->Mercurio32->updateAll("estado='X',fecest='$today',motivo='$motivo',usuario='$funcionario'","conditions: id = '$id' ");
                $asunto = "Rechazo de Empresa";
                $msg = "Gracias por utilizar el Servicio en Linea de Comfamiliar Huila, le informamos que su Afiliacion fue rechazada debido a que no cumplio en su totalidad con los requisitos exigibles, le invitamos a que anexe la documentacion y/o informacion solicitada y nuevamente  realice  el  proceso, para que sus trabajadores  puedan recibir los beneficios que nuestra Entidad ofrece.
                    <br><br> No. de Solicitud: {$mercurio32->getId()}
                    <br> NOMBRE DE LA CONYUGE: {$mercurio32->getPrinom()} {$mercurio32->getSegnom()} {$mercurio32->getPriape()} {$mercurio32->getSegape()}
                    <br> IDENTIFICACION: {$mercurio32->getCedcon()}
                    <br> CAUSAL DE DEVOLUCION: $motivo
                    <br><br>
                    Es muy importante que para cualquier inquietud y/o reclamo tenga en cuenta el Numero de solicitud y se comunique con nuestra Entidad, a traves de las lineas de atencion en las Agencias de Neiva, Garzon, Pitalito y  la Plata.
                    ";
                $file = "";
                $file = "";
                $mercurio07 = $this->Mercurio07->findFirst("documento = {$mercurio32->getCedtra()} and tipo='T'");
                $email = $mercurio07->getEmail();
                parent::enviarCorreo("Rechazo Empresa", "", "$email", "Rechazo de Conyuge", $msg, $file);
                parent::finishTrans();
                $response = parent::successFunc("Rechazo con Exito",null);
                return $this->renderText(json_encode($response));
            }catch (DbException $e){
                parent::setLogger($e->getMessage());
                parent::ErrorTrans();
            }
        }catch (TransactionFailed $e){
            $response = parent::errorFunc("Error al Cambiar la Clave");
            return $this->renderText(json_encode($response));
        }
    }

    public function aprobarAction(){
        try{
            try{
                $this->setResponse("ajax");
                $response = parent::startFunc();
                $modelos = array("mercurio32");
                $Transaccion = parent::startTrans($modelos);
                $id = $this->getPostParam("id");
                //$observacion = $this->getPostParam("observacion");
                $mercurio32 = $this->Mercurio32->findFirst("id = '$id'");
                /*
                $codcat = parent::webService('Nucfamtrabajador',array("nit"=>$mercurio32->getCedtra()));
                if($codcat == FALSE){
                    $codcat = parent::webService('Nucfamtrabajadorina',array("cedtra"=>$mercurio32->getCedtra()));
                }
                */
                //Debug::addVariable("a",$codcat[0]);
                //throw new DebugException(0);

                //$nuevoBen = $mercurio32->getArray();
                //$nuevoBen['codcaj'] = $mercurio32->getCodcaj();
                $nuevoBen['cedtra'] = $mercurio32->getCedtra();
                //$nuevoBen['cajcon'] = $mercurio32->getCajcon();
                $nuevoBen['coddoc'] = $mercurio32->getCoddoc();
                $nuevoBen['cedcon'] = $mercurio32->getCedcon();
                $nuevoBen['priape'] = $mercurio32->getPriape();
                $nuevoBen['segape'] = $mercurio32->getSegape();
                $nuevoBen['prinom'] = $mercurio32->getPrinom();
                $nuevoBen['segnom'] = $mercurio32->getSegnom();
                $nuevoBen['sexo']   = $mercurio32->getSexo();
                $nuevoBen['direccion'] = $mercurio32->getDireccion();
                $nuevoBen['barrio'] = $mercurio32->getBarrio();
                $nuevoBen['telefono'] = $mercurio32->getTelefono();
                $nuevoBen['celular'] = $mercurio32->getCelular();
                $nuevoBen['email']  = $mercurio32->getEmail();
                $nuevoBen['ciures']  = $mercurio32->getCiures();
                if($mercurio32->getCiures() != ""){
                    $depres = substr("{$mercurio32->getCiures()}",0,2);
                }else{
                    $depres = "";
                }
                $nuevoBen['depres']  = $depres;
                $nuevoBen['estciv'] = $mercurio32->getEstciv();
                $nuevoBen['fecnac'] = $mercurio32->getFecnac()->getUsingFormatDefault();
                $nuevoBen['ciunac'] = $mercurio32->getCiunac();
                if($mercurio32->getCiunac() != ""){
                    $depnac = substr("{$mercurio32->getCiunac()}",0,2);
                }else{
                    $depnac = "";
                }
                $nuevoBen['depnac'] = $depnac;
                $nuevoBen['captra'] = 'N';
                //$nuevoBen['fecing'] = $mercurio32->getFecing();
                $today = new Date();
                $nuevoBen['fecafi'] = $today->getUsingFormatDefault();
                $nuevoBen['fecsis'] = $today->getUsingFormatDefault();
                $difer = $today->diffDate($mercurio32->getFecnac());
                $difano = $difer/365;
                $nuevoBen['edad'] = $difano;
                $nuevoBen['comper'] = $mercurio32->getComper();
                //$nuevoBen['observacion'] = $observacion;
                $nuevoBen['observacion'] = "{$today->getUsingFormatDefault()} - Consulta - SE REGISTRO LA AFILIACION DEL CONYUGE {$mercurio32->getCedcon()} POR EL PORTAL DE CONSULTA EN LINEA";
                $funcionario = parent::getActUser('usuario');
                $nuevoBen['usuario'] = 'Consulta';
                $nuevoBen['codcat'] = NULL;
                $nuevoBen['nivedu'] = $mercurio32->getNivedu();
                $nuevoBen['nomcor'] = $mercurio32->getPrinom()." ".$mercurio32->getSegnom()." ".$mercurio32->getPriape()." ".$mercurio32->getSegape();
                $ruta = "digitalizacion\afiliados\ ".$mercurio32->getFecnac()->getUsingFormat('y')."\ ".$mercurio32->getFecnac()->getUsingFormat('m')."\ ".$mercurio32->getFecnac()->getUsingFormat('d')."\ {$mercurio32->getCedcon()}\ ";
                $nuevoBen['ruta'] = str_replace(" ","",$ruta);
                $nuevoBen['zona'] = $mercurio32->getCodzon();
                $nuevoBen['tipviv'] = $mercurio32->getTipviv();

                //$nuevoBen['codocu'] = $mercurio32->getCodocu();
                //$nuevoBen['salario'] = $mercurio32->getSalario();
                //$nuevoBen['numcue'] = $this->getPostParam("numcue");

                $result= parent::webservice('IngresarConyuge',$nuevoBen);
                if($result[0]['result'] == 'natural'){
                    $response = parent::errorFunc("Este conyuge esta registrado como una persona natural",null);
                }else if($result[0]['result'] == '2cony'){
                    $response = parent::errorFunc("El conyuge ya se encuentra con una relacion de convivencia con otro trabajador.",null);
                }else if($result[0]['result'] == 'false' || $result==FALSE){
                    $response = parent::errorFunc("El trabajador ya se encuentra registrado en Subsidio",null);
                }else{
                    $user=Auth::getActiveIdentity();
                    $Log = new Logger("File","{$user['usuario']}.log");
                    $Log->setFormat("[%date%] %controller%/%action% on %application% Error: %message%");
                    //$this->carta_aprobacionAction($nuevoBen);
                    $file = "";
                    $today = new Date();
                    $msg = "Gracias por utilizar el Servicio en Linea de Comfamiliar Huila, a continuacion relacionamos la informacion de la Afiliacion de Conyuge que cumplio con los requisitos exigidos por la Caja y asi puedan recibir los beneficios que nuestra Entidad ofrece.
                    <br><br> No. DE SOLICITUD: {$mercurio32->getId()}
                    <br> IDENTIFICACION: {$mercurio32->getCedcon()}
                    <br> NOMBRE DEL CONYUGE: {$mercurio32->getPrinom()} {$mercurio32->getSegnom()} {$mercurio32->getPriape()} {$mercurio32->getSegape()}
                    <br> FECHA DE RADICACION: {$today->getUsingFormatDefault()}
                    <br><br>
                    Es muy importante que para cualquier inquietud y/o reclamo tenga en cuenta el Numero de solicitud y se comunique con nuestra Entidad, a traves de las lineas de atencion en las Agencias de Neiva, Garzon, Pitalito y  la Plata.
                    ";
                    //$asunto = "confirmacion de afiliacion";
                    //parent::enviarCorreo("Afilicion Conyuge", $nuevoBen['cedcon'], "", "Afiliacion de Conyuge", $msg, $file);
                    $asunto = "Inscripcion Nuevo Conyuge";
                    //$msg = "Cordial Saludos<br><br>Por favor complentar la informacion de la conyuge {$nuevoBen['cedcon']} en Subsidio Familiar.<br><br>Atentamente,<br><br>Mercurio";
                    $mercurio07 = $this->Mercurio07->findFirst("documento = {$mercurio32->getCedtra()} and tipo='T'");
                    $email = $mercurio07->getEmail();
                    parent::enviarCorreo("Aprobacion Conyuge", $nuevoBen['cedcon'], "$email", "Afiliacion de conyuge", $msg, $file);
                    $today = new Date();
                    $this->Mercurio32->updateAll("estado='A',fecest='{$today->getUsingFormatDefault()}',motivo='{$nuevoBen['observacion']}'","conditions: id='$id'");
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

    public function carta_aprobacionAction($nuevoBen){
      $today = new Date();
      $title = "Afiliacion Empresa";
      $report = new UserReportPdf($title,array(),"P","A4");
      $report->startReport();
      $report->setX(15);
      $carta =""; 
      $carta .= "\n\n, {$today->getDay()} de {$today->getMonthName()} de {$today->getYear()}.
          \n\n\n\nSenor:\n{$nuevoBen['priape']}\n{$nuevoBen['direccion']}\n{$nuevoBen['telefono']} 
          \n\n\nRespetado (a) Señor (a):
          \n\nEs grato informarles que su solicitud de afiliacion como empleador aportante, cumple con lo dispuesto en la Ley 21 de 1982 y el Decreto Reglamentario 341 de 1988, por lo cual este despecho la aprueba por delegacion expresa del Consejo Directivo de esta Entidad, otorgado al tenor de la normas existentes, en acto celebrado el 12 de mayo de 1988, registrado en Acta 116.
          \n\nPor lo anterior, pueden Ustedes comenzar a pagar sus aportes a partir de {$nuevoBen['fecafi']},tomando como base la determinado en el Articulo 127 del Codigo Sustantivo de Trabajo, el cual reza:
          \n\n\"Constituye salario no solo la remuneracion ordinaria, fija o variable, sino todo lo que recibe el trabajador en dinero o en especie como contraprestacion directa del servicio, sea cualquiera la dorma o denominacion que se adopte, como primas, sobresueldos, bonificaciones habituales, valor del trabajo suplementario de las horas extras, valor del trabajo en dias de descanso obligatorio, porcentajes sobre ventas y comisiones\"
          \n\nLos Empleadores tienen la obligacion de informar oportunamente todo hecho que modifique la calidad de afiliado al regimen del subsidio familiar, respecto de los trabajadores a su servicio. Le adjuntamos los formularios para la afiliacion inmediata de los trabajadores que relaciono debido a que el subsidio familiar monetario se cancela a partir de la afiliacion del trabajador a la Caja y la ley no permite retroactividad.-Decreto 784 de 1989 articulo 2.\n\n";
      $carta .= "Una vez el trabajador se afilie debe acercarse a retirar la tarjeta donde se le consignara la cuota monetaria.
          \n\nLa Ley 789 de 2002 establece sanciones con multa sucesivas hasta de mil (1000) salarios minimos mensuales a favor del subsidio al desempleo a los empleadores que incurran en cualquiera de estas conductas: no inscribir en una caja de compensacion familiar a todas las persons con las que tenga vinculacion laboral, siempre que exista obligacion; no pagar cumplidamente los aportes de las cajas y no informar las novedades laborales de sus trabajadores frente a las cajas. Asi mismo debe informar de inmediato los cambios que presente su empresa, tales como direccion, telefono, representante legal etc.
          \n\nCancele oportunamente los aportes parafiscales a traves de planilla unica o asistida dentro del plazo estipulado por la ley de acuerdo al ultimo digito del NIT -Decreto 1464 de 2005-. Evitese el cobro de intereses por mora -Ley 1066 de 2006- y las sanciones previstas en la ley.
          \n\nCuando un trabajador quede cesante, debe acercarse a retirar el formulario para aspirar al subsidio de desempleo.
          \n\n\nCordialmente,
          \n\n\n\nERNESTO MIGUEL OROZCO DURAN
          \nDirector Administrativo";
      
      $report->MultiCell(0,4,$carta,0,"J",0);
      $report->Ln();
      
      $report->Ln();
      $report->setX(15);
      $report->Cell(0,4,"Esta certificacion se expide en RIOHACHA a solicitud del interesado el dia {$today->getDay()} de {$today->getMonthName()} de {$today->getYear()}. ",0,0,"L",0,0);
      $report->Ln();
      $file = "public/temp/reportes/reporte_aprobacion";
      ob_end_clean();
      echo $report->FinishReport($file,"F");
      $this->setResponse('view');
    }

    public function verdocumentosAction(){
        $this->setResponse("ajax");
        $conyuge= $this->getPostParam("conyuge");
        $mercurio39 = $this->Mercurio39->find("numero = '$conyuge'");
        $mercurio32 = $this->Mercurio32->findFirst("id = '$conyuge'");
        $lista = "";
        $lista .= "<table class='resultado-consul' style='width: 100%; border: 1px double #000; border-collapse: separate; margin: auto;'>";
        $nombre = $mercurio32->getPriape()." ".$mercurio32->getSegape()." ".$mercurio32->getPrinom()." ".$mercurio32->getSegnom();
        $lista .= "<thead><tr><th colspan='2' style='text-align: left; font-size: 1.2em; height: 20px;'>Conyuge: $nombre </th></tr></thead>";
        $lista .= "<thead><tr style='background-color: #959595; font-size: 1.2em;'>";
        $lista .= "<th style='height: 18px;'>Item</th><th>Archivo</th>";
        $lista .= "<th>&nbsp;</th>";
        $lista .= "</tr></thead>";
        $item = 1;
            foreach($mercurio39 as $mmercurio39){
                $mercurio12 = $this->Mercurio12->findFirst("coddoc = '{$mmercurio39->getCoddoc()}'");
                $lista .= "<tr>";
                $lista .= "<td>{$item}</td>";
                $lista .= "<td class='list-arc'>{$mercurio12->getDetalle()}</td>";
                $lista .= "<td class='list-arc'><a target='_blank' href='../{$mmercurio39->getNomarc()}'>".$mmercurio39->getNomarc()."</a></td>";
                $lista .= "</tr>";
                $item++;
            }
        if(!ISSET($mmercurio39)){
            $lista .= "<tr>";
            $lista .= "<td colspan='3'>No presenta archivos adjuntos</td>";
            $lista .= "</tr>";
        }
        $lista .= "</table>";
        return $this->renderText(json_encode($lista));
    }

    public function infoAction(){
        $this->setResponse("ajax");
        $id = $this->getPostParam('id');
        $mercurio32 = $this->Mercurio32->FindFirst("id = $id");
        $formu ="";
        $formu .="<div id='empresaCampos'>";
        $formu .= Tag::form("id: form-completar","autocomplete: off","enctype: multipart/form-data");
        $formu .= "<table cellpadding='5'style='width:80%; margin: 10px '> ";
        $formu .= "<tr>";   
        $formu .= "<td colspan='2'><label> Cedula trabajador: </label></td>";
        $formu .= "<td>".Tag::textField("cedtra","value: {$mercurio32->getCedtra()}","readonly: readonly")."</td>";   
        $formu .= "<td><label> Cedula Conyuge : </label></td>";
        $formu .= "<td>".Tag::textField("cedcon","value: {$mercurio32->getCedcon()}","readonly: readonly")."</td>";   
        $formu .= "</tr>";   
        $formu .= "<tr>";   
        $formu .= "<td colspan='2'><label> Documento : </label></td>";
        $formu .= "<td>".Tag::textField("coddoc","value: {$mercurio32->getCoddoc()}","readonly: readonly")."</td>";   
        $formu .= "<td><label> Primer apellido : </label></td>";
        $formu .= "<td>".Tag::textField("priape","value: {$mercurio32->getPriape()}","readonly: readonly")."</td>";   
        $formu .= "</tr>";   
        $formu .= "<tr>";   
        $formu .= "<td colspan='2'><label> Segundo apellido : </label></td>";
        $formu .= "<td>".Tag::textField("segape","value: {$mercurio32->getSegape()}","readonly: readonly")."</td>";   
        $formu .= "<td><label> Primer nombre : </label></td>";
        $formu .= "<td>".Tag::textField("prinom","value: {$mercurio32->getPrinom()}","readonly: readonly")."</td>";   
        $formu .= "</tr>";   
        $formu .= "<tr>";   
        $formu .= "<td colspan='2'><label> Segundo nombre : </label></td>";
        $formu .= "<td>".Tag::textField("segnom","value: {$mercurio32->getSegnom()}","readonly: readonly")."</td>";   
        $formu .= "<td><label> Fecha de nacimiento : </label></td>";
        $formu .= "<td>".Tag::textField("fecnac","value: {$mercurio32->getFecnac()}","readonly: readonly")."</td>";   
        $formu .= "</tr>";   
        $formu .= "<tr>";   
        $formu .= "<td colspan='2'><label> Ciudad de nacimiento : </label></td>";
        $migra089 = $this->Migra089->findFirst("codciu = '{$mercurio32->getCiunac()}'");
        $formu .= "<td>".Tag::textField("ciunac","value: {$migra089->getDetciu()}","readonly: readonly")."</td>";   
        $formu .= "<td><label> Sexo : </label></td>";
        switch($mercurio32->getSexo()){
            case 'M':
                $mercurio32->setSexo('Masculino');
                break;
            case 'F':
                $mercurio32->setSexo('Femenino');
                break;
        }
        $formu .= "<td>".Tag::textField("sexo","value: {$mercurio32->getSexo()}","readonly: readonly")."</td>";   
        $formu .= "</tr>";   
        $formu .= "<tr>";   
        $formu .= "<td colspan='2'><label> Estado Civil : </label></td>";
        $migra091 = $this->Migra091->findFirst("iddetalledef = '{$mercurio32->getEstciv()}'");
        $formu .= "<td>".Tag::textField("estciv","value: {$migra091->getDetalledefinicion()}","readonly: readonly")."</td>";   
        $formu .= "<td><label> Conviven : </label></td>";
        switch($mercurio32->getComper()){
            case 'S':
                $mercurio32->setComper('Si');
                break;
            case 'N':
                $mercurio32->setComper('No');
                break;
        }
        $formu .= "<td>".Tag::textField("comper","value: {$mercurio32->getComper()}","readonly: readonly")."</td>";   
        $formu .= "</tr>";   
        $formu .= "<tr>";   
        $formu .= "<td colspan='2'><label> Ciudad de Residencia : </label></td>";
        $migra089 = $this->Migra089->findFirst("codciu = {$mercurio32->getCiures()}");
        $formu .= "<td>".Tag::textField("ciures","value: {$migra089->getDetciu()}","readonly: readonly")."</td>";   
        $formu .= "<td><label> Zona : </label></td>";
        $migra089 = $this->Migra089->findFirst("codzon = {$mercurio32->getCodzon()}");
        $formu .= "<td>".Tag::textField("codzon","value: {$migra089->getDetzon()}","readonly: readonly")."</td>";   
        $formu .= "</tr>";   
        $formu .= "<tr>";   
        $formu .= "<td colspan='2'><label> Tipo de vivienda : </label></td>";
        switch($mercurio32->getTipviv()){
            case 'R':
                $mercurio32->setTipviv('Rural');
                break;
            case 'U':
                $mercurio32->setTipviv('Urbano');
                break;
            default:
                $mercurio32->setTipviv('');
                break;
        }
        $formu .= "<td>".Tag::textField("tipviv","value: {$mercurio32->getTipviv()}","readonly: readonly")."</td>";   
        $formu .= "<td><label> Direccion : </label></td>";
        $formu .= "<td>".Tag::textField("direccion","value: {$mercurio32->getDireccion()}","readonly: readonly")."</td>";   
        $formu .= "</tr>";   
        $formu .= "<tr>";   
        $formu .= "<td colspan='2'><label> Barrio : </label></td>";
        if($mercurio32->getBarrio() == "" || $mercurio32->getBarrio() == NULL){
            $formu .= "<td>".Tag::textField("barrio","value: ","readonly: readonly")."</td>";   
        }else{
            $migra087 = $this->Migra087->findFirst("codbar = {$mercurio32->getBarrio()}");
            $formu .= "<td>".Tag::textField("barrio","value: {$migra087->getDetalle()}","readonly: readonly")."</td>";   
        }
        $formu .= "<td><label> Telefono : </label></td>";
        $formu .= "<td>".Tag::textField("telefono","value: {$mercurio32->getTelefono()}","readonly: readonly")."</td>";   
        $formu .= "</tr>";   
        $formu .= "<tr>";   
        $formu .= "<td colspan='2'><label> Celular : </label></td>";
        $formu .= "<td>".Tag::textField("celular","value: {$mercurio32->getCelular()}","readonly: readonly")."</td>";   
        $formu .= "<td><label> Email : </label></td>";
        $formu .= "<td>".Tag::textField("email","value: {$mercurio32->getEmail()}","readonly: readonly")."</td>";   
        $formu .= "</tr>";   
        $formu .= "<tr>";   
        $formu .= "<td colspan='2'><label> Nivel de Educacion : </label></td>";
        $migra091 = $this->Migra091->findFirst("iddetalledef = {$mercurio32->getNivedu()}");
        $formu .= "<td>".Tag::textField("nivedu","value: {$migra091->getDetalledefinicion()}","readonly: readonly")."</td>";   
        $formu .= "<td><label> Fecha de Ingreso : </label></td>";
        $formu .= "<td>".Tag::textField("fecing","value: {$mercurio32->getFecing()}","readonly: readonly")."</td>";   
        $formu .= "</tr>";   
        /*
        $formu .= "<tr>";   
        $formu .= "<td colspan='2'><label> Codigo de Ocupacion : </label></td>";
        $formu .= "<td>".Tag::textField("codocu","value: {$mercurio32->getCodocu()}","readonly: readonly")."</td>";   
        $formu .= "<td><label> Salario : </label></td>";
        $formu .= "<td>".Tag::textField("salario","value: {$mercurio32->getSalario()}","readonly: readonly")."</td>";   
        $formu .= "</tr>";   
        */
        $formu .= "</table>";
        $formu .= Tag::endForm();
        $formu .="</div>";
        return $this->renderText(json_encode($formu));
    }

}

?>
