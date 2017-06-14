<?php

class Mercurio34Controller extends ApplicationController{

	private $title = "Afiliacion Beneficiario";

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
		$html .= "<th>Documento Beneficiario</th>";
		$html .= "<th>Nombre</th>";
		$html .= "<th>Sexo</th>";
		$html .= "<th>Parentezco</th>";
        $html .= "<th>Fecha</th>";
		$html .= "<th>Aprobar</th>";
		$html .= "<th>Rechazar</th>";
        $html .= "<th>Documentos</th>";
        $html .= "<th>Informacion</th>";
		$html .= "</thead>";
		$html .= "<tbody>";
        if($filt =="ADAD")
            $mercurio34 = $this->Mercurio34->find("estado='P'");
        else
            $mercurio34 = $this->Mercurio34->findAllBySql("select mercurio34.* from mercurio34,mercurio07 where mercurio34.estado='P' and mercurio07.agencia='$filt' and mercurio07.documento=mercurio34.cedtra AND mercurio34.usuario='".Session::getData('usuario')."'");
		foreach($mercurio34 as $mmercurio34){
		  $html .= "<tr>";
		  $html .= "<td>{$mmercurio34->getCedtra()}</td>";
		  $html .= "<td>{$mmercurio34->getDocumento()}</td>";
		  $html .= "<td>{$mmercurio34->getPriape()} {$mmercurio34->getSegape()} {$mmercurio34->getPrinom()} {$mmercurio34->getSegnom()}</td>";
		  $html .= "<td>{$mmercurio34->getSexo()}</td>";
		  //$html .= "<td>{$mmercurio34->getParent()}</td>";
          $prueba = $mmercurio34->getParent();
          switch($prueba){
                    case "35":
                        $html .="<td>HIJO </td> ";
                        break;
                    case "36":
                        $html.="<td> MADRE/PADRE </td>";
                        break;
                    case "37":
                        $html.="<td> HERMANO </td> ";
                        break;
                    case "38":
                        $html .="<td> HIJASTRO </td>";
                        break;
          }
         // Debug::addVariable("a",$mmercurio34->getParent());
         // throw new DebugException();   
          $mercurio21 = $this->Mercurio21->findBySql("select * from mercurio34,mercurio21 where mercurio34.log= mercurio21.id AND mercurio34.log='{$mmercurio34->getLog()}' limit 1");
          $html .= "<td>{$mercurio21->getFecha()}</td>";
		  $html .= "<td onclick=\"completar('{$mmercurio34->getId()}',this);\">".Tag::image("desktop/security-high.png", "width: 25px", "height: 25px", "style: cursor: pointer;")."</td>";
		  $html .= "<td onclick=\"rechazar('{$mmercurio34->getDocumento()}',this);\">".Tag::image("desktop/security-low.png", "width: 25px", "height: 25px", "style: cursor: pointer;")."</td>";
          $html .= "<td onclick=\"verdoc('{$mmercurio34->getId()}');\">".Tag::image("desktop/search.png","width: 25px", "height: 25px", "style: cursor: pointer;")."</td>";
          $html .= "<td onclick=\"info('{$mmercurio34->getId()}');\">".Tag::image("desktop/search.png","width: 25px", "height: 25px", "style: cursor: pointer;")."</td>";
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
        $formu .= "<table cellpadding='5'style='width:80%; margin: 10px 40px'> ";
        $formu .= "<tr>";   
        //$formu .= "<td><label>Giro :<label></td>";   
        //$formu .= "<td>".Tag::selectStatic("giro",array("S"=>"SI","N"=>"NO"),"style: width: 175px","use_dummy: true")."</td>";   
        $formu .= "<td><label>Tipo de Formulario :<label></td>";   
        $formu .= "<td>".Tag::selectStatic("tipfor",array("S"=>"Subsidio","N"=>"Servicios"),"style: width: 175px","use_dummy: true")."</td>";   
        $formu .= "</tr>";    
/*
       
        $formu .= "<tr>";   
        $formu .= "<td><label>Numero de Hijos :<label></td>";   
        $formu .= "<td>".Tag::textField("numhij","size: 11","style: width: 175px")."</td>";   
        $formu .= "</tr>";  
  
        $formu .= "<tr>";   
        $formu .= "<td><label>Fecha de Pre<label></td>";   
        $formu .= "<td>".TagUser::calendar("fecpre","size: 15")."</td>";   
*/
        $formu .= "<tr>";   
        //$formu .= "<td><label>Dispacidad de trabajo:<label></td>";   
        //$formu .= "<td>".Tag::selectStatic("captra",array("N"=>"Normal","I"=>"Incapacitado"))."</td>";   
        $formu .= "<td><label>Fecha de Asignaci&oacute;n<label></td>";   
        $formu .= "<td>".TagUser::calendar("fecasi","size: 15")."</td>";   
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
        $response = parent::startFunc();
        $modelos = array("mercurio34");
        $Transaccion = parent::startTrans($modelos);
        $doc = $this->getPostParam("doc");
        $motivo = $this->getPostParam("motivo");
        $mercurio34 = $this->Mercurio34->findFirst("documento = '$doc' AND estado='P'");
        $mercurio07 = $this->Mercurio07->findFirst("documento='{$mercurio34->getCedtra()}'");
        $today = new Date();
        $funcionario = parent::getActUser('usuario');
        $this->Mercurio34->updateAll("estado='X',fecest='$today',motivo='$motivo',usuario='$funcionario'","conditions: documento = '$doc' ");
        $asunto = "Rechazo de Beneficiario";
        $msg = "Gracias por utilizar el Servicio en Linea de Comfamiliar Huila, le informamos que su Afiliacion fue rechazada debido a que no cumplio en su totalidad con los requisitos exigibles, le invitamos a que anexe la documentacion y/o informacion solicitada y nuevamente  realice  el  proceso, para que sus trabajadores  puedan recibir los beneficios que nuestra Entidad ofrece.
                    <br><br> <b> No. de Solicitud: </b> {$mercurio34->getId()}
                    <br> <b> DOCUMENTO DEL AFILIADO: </b> {$mercurio07->getDocumento()}
                    <br> <b> NOMBRE DE AFILIADO: </b> {$mercurio07->getNombre()}
                    <br> <b> NOMBRE DE BENEFICIARIO: </b> {$mercurio34->getPrinom()} {$mercurio34->getSegnom()} {$mercurio34->getPriape()} {$mercurio34->getSegape()}
                    <br> <b> IDENTIFICACION: </b> {$mercurio34->getDocumento()}
                    <br> <b> CAUSAL DE DEVOLUCION: </b> $motivo
                    <br><br>
            Es muy importante que para cualquier inquietud y/o reclamo tenga en cuenta el Numero de solicitud y se comunique con nuestra Entidad, a traves de las lineas de atencion en las Agencias de Neiva, Garzon, Pitalito y  la Plata.
            ";
        $file = "";
        $mercurio07 = $this->Mercurio07->findFirst("documento = {$mercurio34->getCedtra()} and tipo='T'");
        $email = $mercurio07->getEmail();
        parent::enviarCorreo("Rechazo de Beneficiario", $mercurio34->getDocumento(), "$email", "Rechazo de Beneficiario", $msg, $file);
        parent::finishTrans();
        $response = parent::successFunc("Rechazo con Exito",null);
        return $this->renderText(json_encode($response));
      }catch (DbException $e){
        parent::setLogger($e->getMessage());
        parent::ErrorTrans();
      }
    }catch (TransactionFailed $e){
      $response = parent::errorFunc("Error al rechazar el nuevoBen");
      return $this->renderText(json_encode($response));
    }
  }
    
    public function aprobarAction(){
      try{
        try{  
        	$this->setResponse("ajax");
            $fecha =  new Date();
            $modelos = array("mercurio34");
            $Transaccion = parent::startTrans($modelos);
    	    $id = $this->getPostParam("id");
            $observacion = $this->getPostParam("observacion");
        	$mercurio34 = $this->Mercurio34->findFirst("id = '$id'");
            $mercurio46 = $this->Mercurio46->findFirst("log = {$mercurio34->getLog()}");
            $nuevoBen = $mercurio34->getArray();
            $mercurio07 = $this->Mercurio07->findFirst("tipo='T' and documento='{$nuevoBen['cedtra']}'"); 
            if($mercurio46 != FALSE){
                $ruta = "digitalizacion\afiliados\ ".$mercurio46->getFecnac()->getUsingFormat('y')."\ ".$mercurio46->getFecnac()->getUsingFormat('m')."\ ".$mercurio46->getFecnac()->getUsingFormat('d')."\ {$mercurio46->getDocumento()}\ ";
                $param = array(
                        'doctra'=>$nuevoBen['cedtra'],
                        'coddoctra'=>$mercurio07->getCoddoc(),
                        'cedtra'=>$mercurio46->getDocumento(),
                        'coddoc'=>$mercurio46->getCoddoc(),
                        'prinom'=>$mercurio46->getPrinom(),
                        'segnom'=>$mercurio46->getSegnom(),
                        'priape'=>$mercurio46->getPriape(),
                        'segape'=>$mercurio46->getSegape(),
                        'sexo'=>$mercurio46->getSexo(),
                        'fecnac'=>$mercurio46->getFecnac(),
                        'fecsis'=>$fecha->getUsingFormatDefault(),
                        'usuario'=>'Consulta',
                        'ruta' => str_replace(" ","",$ruta)
                        );
                $idrep = parent::webService('IngresarAportes021',$param);
                $nuevoBen['idbiologico'] = $idrep[0]['result'];
            }else{
                $nuevoBen['idbiologico'] = NULL;
            }
            $nuevoBen['codcaj'] = $mercurio34->getCodcaj();
            $nuevoBen['cedtra'] = $mercurio34->getCedtra();
            $nuevoBen['cedcon'] = $mercurio34->getCedcon();
            $nuevoBen['coddoc'] = $mercurio34->getCoddoc();
            $nuevoBen['documento'] = $mercurio34->getDocumento();
            $nuevoBen['priape'] = $mercurio34->getPriape();
            $nuevoBen['segape'] = $mercurio34->getSegape();
            $nuevoBen['prinom'] = $mercurio34->getPrinom();
            $nuevoBen['segnom'] = $mercurio34->getSegnom();
            $nuevoBen['sexo'] = $mercurio34->getSexo();
            $nuevoBen['fecnac'] = $mercurio34->getFecnac()->getUsingFormatDefault();
            $fecnac = new Date($mercurio34->getFecnac());
            $difer = $fecha->diffDate($fecnac);
            $difano = $difer/365;
            if($difano < 60 && $mercurio34->getParent() == '36'){
                $display_error = "El beneficiario tiene menos de 60 a&ntilde;os";
                parent::setLogger("El beneficiario tiene menos de 60 a&ntilde;os");
                parent::ErrorTrans();
            }
            $nuevoBen['edad'] = $difano;
            $nuevoBen['ciunac'] = $mercurio34->getCiunac();
            $nuevoBen['depnac'] = substr($mercurio34->getCiunac(),0,2);
            $nuevoBen['captra'] = $mercurio34->getCaptra();
            $nuevoBen['nivedu'] = $mercurio34->getNivedu();
            $nuevoBen['fecasi'] = $this->getPostParam("fecasi");
            $nuevoBen['fecafi'] = $fecha->getUsingFormatDefault();
            $nuevoBen['fecsis'] = $fecha->getUsingFormatDefault();
            $nuevoBen['parent'] = $mercurio34->getParent();
            $nuevoBen['giro'] = $this->getPostParam("giro");
            $nuevoBen['observacion'] = "{$fecha->getUsingFormatDefault()} - Consulta - SE REGISTRO LA AFILIACION DEL BENEFICIARIO {$mercurio34->getDocumento()} POR EL PORTAL DE CONSULTA EN LINEA";
            $funcionario = parent::getActUser('usuario');
            $nuevoBen['usuario'] = 'Consulta';
            $nuevoBen['giro'] = $this->getPostParam("tipfor");
            $ruta = "digitalizacion\afiliados\ ".$mercurio34->getFecnac()->getUsingFormat('y')."\ ".$mercurio34->getFecnac()->getUsingFormat('m')."\ ".$mercurio34->getFecnac()->getUsingFormat('d')."\ {$mercurio34->getDocumento()}\ ";
            $nuevoBen['ruta'] = str_replace(" ","",$ruta);
            $nuevoBen['nomcor'] = $mercurio34->getPrinom()." ".$mercurio34->getSegnom()." ".$mercurio34->getPriape()." ".$mercurio34->getSegape();
            //$result=parent::webService("InsertNuevoBens",$nuevoBen);
            $result=parent::webService("IngresarBeneficiario",$nuevoBen);
            $display_error = "";
            if($result==false || $result[0]['result']=='false'){
                $display_error = "Se presento un incoveniente reportelo ante el ingeniero de soporte";
                parent::setLogger("Error webservice");
                parent::ErrorTrans();
            }if($result[0]['result']=='dbg'){
                //Debug::addVariable("a",$result);
                //throw new DebugException(0);
            }if($result[0]['result']=='cony'){
                $display_error = "El beneficiario es conyuge de un trabajador activo";
                parent::setLogger("El beneficiario es conyuge de un trabajador activo");
                parent::ErrorTrans();
            }if($result[0]['result']=='dos'){
                $display_error = "El beneficiario tiene dos relaciones activas";
                parent::setLogger("El beneficiario tiene dos relaciones activas");
                parent::ErrorTrans();
            }if($result[0]['result']=='trabajador'){
                $display_error = "El beneficiario se encuentra afiliado como trabajador";
                parent::setLogger("El beneficiario se encuentra afiliado como trabajador");
                parent::ErrorTrans();
            }
            $email = $mercurio07->getEmail();
          
            $nombre = $mercurio34->getPrinom()." ".$mercurio34->getSegnom()." ".$mercurio34->getPriape()." ".$mercurio34->getSegape();


            //$this->carta_aprobacionAction($nuevoBen);
            $file = "";
                    $msg = "Gracias por utilizar el Servicio en Linea de Comfamiliar Huila, a continuacion relacionamos la informacion de la Afiliacion de Beneficiario que cumplio con los requisitos exigidos por la Caja y asi puedan recibir los beneficios que nuestra Entidad ofrece.
                    <br><br> No. DE SOLICITUD: {$mercurio34->getId()}
                    <br> No. DOCUMENTO DEL AFILIADO: {$mercurio07->getDocumento()}
                    <br> NOMBRE AFILIADO: {$mercurio07->getNombre()}
                    <br> IDENTIFICACION: {$mercurio34->getDocumento()}
                    <br> NOMBRE DEL BENEFICIARIO: {$mercurio34->getPrinom()} {$mercurio34->getSegnom()} {$mercurio34->getPriape()} {$mercurio34->getSegape()}
                    <br> FECHA DE RADICACION: {$fecha->getUsingFormatDefault()}
                    <br> FECHA DE AFILIACION: {$fecha->getUsingFormatDefault()}
                    <br><br>
                    Es muy importante que para cualquier inquietud y/o reclamo tenga en cuenta el Numero de solicitud y se comunique con nuestra Entidad, a traves de las lineas de atencion en las Agencias de Neiva, Garzon, Pitalito y  la Plata.
                    ";
            $asunto = "confirmacion de afiliacion";
            parent::enviarCorreo("Afiliacion Beneficiario",$nombre,"$email","Afiliacion de Beneficiario",$msg,$file);
            $today= new Date;
            /*
            $asunto = "Inscripcion Nuevo Beneficiario";
            $msg = "Cordial Saludos<br>
                    <br>Por favor completar la informacion del Beneficiario {$nuevoBen['prinom']} - {$nuevoBen['documento']} en Subsidio Familiar.<br>
                    <br>Atentamente,<br>
                    <br>Mercurio";

            parent::enviarCorreo("", $,$email, "Afiliacion de Beneficiario", $msg, $file);
            */
             $this->Mercurio34->updateAll("estado='A',fecest='{$today->getUsingFormatDefault()}',motivo='{$observacion}'","conditions: id='{$nuevoBen['id']}'");
             parent::finishTrans();
             $response = parent::successFunc("Actualizacion de la Informacion Exitosa",null);
             return $this->renderText(json_encode($response));


        }catch (DbException $e){
          parent::setLogger($e->getMessage());
          parent::ErrorTrans();
        }
      }catch (TransactionFailed $e){
          if(!isset($display_error))$display_error = "Error confirmar afiliacion";
        $response = parent::errorFunc("$display_error");
        return $this->renderText(json_encode($response));
      }

    }
public function verdocumentosAction(){
        $this->setResponse("ajax");
        $nuevoBen = $this->getPostParam("nuevoBen");
        $mercurio40 = $this->Mercurio40->find("numero = '$nuevoBen'");
        $mercurio34 = $this->Mercurio34->findFirst("id = '$nuevoBen'");
      //  Debug::addVariable("a",$nuevoBen);
        //throw new DebugException(); 
        $lista = "";
        $lista .= "<table class='resultado-consul' style='width: 100%; border: 1px double #000; border-collapse: separate; margin: auto;'>";
        $nombre=$mercurio34->getPriape().$mercurio34->getSegape().$mercurio34->getPrinom().$mercurio34->getSegnom();
        $lista .= "<thead><tr><th colspan='2' style='text-align: left; font-size: 1.2em; height: 20px;'>Beneficiario: $nombre </th></tr></thead>";
        $lista .= "<thead><tr style='background-color: #959595; font-size: 1.2em;'>";
        $lista .= "<th style='height: 18px;'>Item</th><th>Archivo</th>";
        $lista .= "</tr></thead>";
        $item = 1;
        foreach($mercurio40 as $mmercurio40){
            $lista .= "<tr>";
            $lista .= "<td>{$item}</td>";
            $lista .= "<td class='list-arc'><a target='_blank' href='../{$mmercurio40->getNomarc()}'>".$mmercurio40->getNomarc()."</a></td>";
            $lista .= "</tr>";
            $item++;
        }
        $lista .= "</table>";
        return $this->renderText(json_encode($lista));

    }
    public function infoAction(){
        $this->setResponse("ajax");
        $id = $this->getPostParam('id');
        $mercurio34 = $this->Mercurio34->FindFirst("id = $id");
        $formu ="";
        $formu .="<div id='empresaCampos'>";
        $formu .= Tag::form("id: form-completar","autocomplete: off","enctype: multipart/form-data");
        $formu .= "<table cellpadding='5'style='width:80%; margin: 10px '> ";
        $formu .= "<tr>";   
        $formu .= "<td><label> Cedula trabajador: </label></td>";
        $formu .= "<td>".Tag::textField("cedtra","value: {$mercurio34->getCedtra()}","readonly: readonly")."</td>";   
        $formu .= "<td><label> Cedula Conyuge : </label></td>";
        $formu .= "<td>".Tag::textField("cedcon","value: {$mercurio34->getCedcon()}","readonly: readonly")."</td>";   
        $formu .= "</tr>";   
        $formu .= "<tr>";   
        $formu .= "<td><label> Documento : </label></td>";
        $formu .= "<td>".Tag::textField("coddoc","value: {$mercurio34->getCoddoc()}","readonly: readonly")."</td>";   
        $formu .= "<td><label> Documento : </label></td>";
        $formu .= "<td>".Tag::textField("docmento","value: {$mercurio34->getDocumento()}","readonly: readonly")."</td>";   
        $formu .= "</tr>";   
        $formu .= "<tr>";   
        $formu .= "<td><label> Primer apellido : </label></td>";
        $formu .= "<td>".Tag::textField("priape","value: {$mercurio34->getPriape()}","readonly: readonly")."</td>";   
        $formu .= "<td><label> Segundo apellido : </label></td>";
        $formu .= "<td>".Tag::textField("segape","value: {$mercurio34->getSegape()}","readonly: readonly")."</td>";   
        $formu .= "</tr>";   
        $formu .= "<tr>";   
        $formu .= "<td><label> Primer nombre : </label></td>";
        $formu .= "<td>".Tag::textField("prinom","value: {$mercurio34->getPrinom()}","readonly: readonly")."</td>";   
        $formu .= "<td><label> Segundo nombre : </label></td>";
        $formu .= "<td>".Tag::textField("segnom","value: {$mercurio34->getSegnom()}","readonly: readonly")."</td>";   
        $formu .= "</tr>";   
        $formu .= "<tr>";   
        $formu .= "<td><label> Fecha de nacimiento : </label></td>";
        $formu .= "<td>".Tag::textField("fecnac","value: {$mercurio34->getFecnac()}","readonly: readonly")."</td>";   
        $formu .= "<td><label> Ciudad de nacimiento : </label></td>";
        $migra089 = $this->Migra089->findFirst("codciu = {$mercurio34->getCiunac()}");
        $formu .= "<td>".Tag::textField("ciunac","value: {$migra089->getDetciu()}","readonly: readonly")."</td>";   
        $formu .= "</tr>";   
        $formu .= "<tr>";   
        $formu .= "<td><label> Sexo : </label></td>";
        switch($mercurio34->getSexo()){
            case 'M':
                $mercurio34->setSexo('Masculino');
                break;
            case 'F':
                $mercurio34->setSexo('Femenino');
                break;
        }
        $formu .= "<td>".Tag::textField("sexo","value: {$mercurio34->getSexo()}","readonly: readonly")."</td>";   
        $formu .= "<td><label> Parentesco : </label></td>";
        $migra091 = $this->Migra091->findFirst("iddetalledef = {$mercurio34->getParent()}");
        $formu .= "<td>".Tag::textField("parent","value: {$migra091->getDetalledefinicion()}","readonly: readonly")."</td>";   
        $formu .= "</tr>";   
        $formu .= "<tr>";   
        $formu .= "<td><label> Huerfano : </label></td>";
        $formu .= "<td>".Tag::textField("huerfano","value: {$mercurio34->getHuerfano()}","readonly: readonly")."</td>";   
        $formu .= "<td><label> Calendario: </label></td>";
        $formu .= "<td>".Tag::textField("calendario","value: {$mercurio34->getCalendario()}","readonly: readonly")."</td>";   
        $formu .= "</tr>";   
        $formu .= "<tr>";   
        $formu .= "<td><label> Nivel de Educacion : </label></td>";
        $migra091 = $this->Migra091->findFirst("iddetalledef = {$mercurio34->getNivedu()}");
        $formu .= "<td>".Tag::textField("nivedu","value: {$migra091->getDetalledefinicion()}","readonly: readonly")."</td>";   
        $formu .= "<td><label> Capacidad de Trabajado : </label></td>";
        $formu .= "<td>".Tag::textField("captra","value: {$mercurio34->getCaptra()}","readonly: readonly")."</td>";   
        $formu .= "</tr>";   

        $formu .= "<tr>";   
        $formu .= "<td>&nbsp;</td>";
        $formu .= "</tr>";   
        $formu .= "<tr>";   
        $formu .= "<th colspan='4' style='text-align: center;'> CONYUGES AFILIADOS </td>";
        $formu .= "</tr>";   
        $mercurio46 = $this->Mercurio46->find("log='{$mercurio34->getLog()}'");
        foreach($mercurio46 as $mmercurio46){
            $formu .= "<tr>";   
            $formu .= "<td><label> Cedula conyuge: </label></td>";
            $formu .= "<td>".Tag::textField("documento","value: {$mmercurio46->getDocumento()}","readonly: readonly")."</td>";   
            $formu .= "<td><label> Primer nombre : </label></td>";
            $formu .= "<td>".Tag::textField("prinom","value: {$mmercurio46->getPrinom()}","readonly: readonly")."</td>";   
            $formu .= "</tr>";   
            $formu .= "<tr>";   
            $formu .= "<td><label> Segundo nombre : </label></td>";
            $formu .= "<td>".Tag::textField("segnom","value: {$mmercurio46->getSegnom()}","readonly: readonly")."</td>";   
            $formu .= "<td><label> Primer apellido : </label></td>";
            $formu .= "<td>".Tag::textField("priape","value: {$mmercurio46->getPriape()}","readonly: readonly")."</td>";   
            $formu .= "</tr>";   
            $formu .= "<tr>";   
            $formu .= "<td><label> Segundo apellido : </label></td>";
            $formu .= "<td>".Tag::textField("segape","value: {$mmercurio46->getSegape()}","readonly: readonly")."</td>";   
            $formu .= "<td><label> Fecha de nacimiento : </label></td>";
            $formu .= "<td>".Tag::textField("fecnac","value: {$mmercurio46->getFecnac()}","readonly: readonly")."</td>";   
            $formu .= "</tr>";   
            $formu .= "<tr>";   
            $formu .= "<td><label> Sexo : </label></td>";
            switch($mmercurio46->getSexo()){
                case 'M':
                    $mmercurio46->setSexo('Masculino');
                    break;
                case 'F':
                    $mmercurio46->setSexo('Femenino');
                    break;
            }
            $formu .= "<td>".Tag::textField("sexo","value: {$mmercurio46->getSexo()}","readonly: readonly")."</td>";   
            $formu .= "</tr>";   
        }

        $formu .= "</table>";
        $formu .= Tag::endForm();
        $formu .="</div>";
        return $this->renderText(json_encode($formu));
    }

}

?>
