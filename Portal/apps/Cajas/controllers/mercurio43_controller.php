<?php

class Mercurio43Controller extends ApplicationController{

	private $title = "Afiliacion Datos Principales Empresa";

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
        $this->setParamToview('titulo',"Actualizacion Datos Principales Empresa");
        $html = "";
        $html .= "<table class='resultado-consul' border=1>";
        $html .= "<thead>";
        $html .= "<th>Nit</th>";
        $html .= "<th>Razon Social</th>";
        $html .= "<th>Campo Modificado</th>";
        $html .= "<th>Campo Actualizado</th>";
		$html .= "<th>Fecha</th>";
        $html .= "<th>Aprobar</th>";
        $html .= "<th>Rechazar</th>";
        $html .= "<th>Archivos</th>";
        $html .= "<th>Informacion</th>";
        $html .= "</thead>";
        $html .= "<tbody>";
        $campo = "";
      if($filt =="ADAD")
        $mercurio43 = $this->Mercurio43->find("estado='P'");
      else
         $mercurio43 = $this->Mercurio43->findAllBySql("select mercurio43.* from mercurio43,mercurio07 where mercurio43.estado='P' and mercurio07.agencia='$filt' and mercurio07.documento=mercurio43.documento AND mercurio43.usuario='".Session::getData('usuario')."'  AND mercurio07.tipo='E'");
        foreach($mercurio43 as $mmercurio43){
            $mercurio07 = $this->Mercurio07->findFirst("tipo = 'E' AND documento = '{$mmercurio43->getDocumento()}'");
            if($mercurio07->getNombre() == NULL){
                $result = parent::webService('autenticar',array("tipo"=>'E',"documento"=>$mmercurio43->getDocumento(), 'coddoc'=>Session::getData('coddoc')));
                $this->Mercurio07->updateAll("nombre='{$result[0]['nombre']}'","conditions: tipo = 'E' AND documento = '{$mmercurio43->getDocumento()}' ");
                $mercurio07->setNombre($result[0]['nombre']);
            }
            if($mmercurio43->getCampo() == "cedrep")$campo = "Cedula de Representante";
            if($mmercurio43->getCampo() == "nomrep")$campo = "Nombre de Representante";
            if($mmercurio43->getCampo() == "razsoc")$campo = "Razon Social";
            if($mmercurio43->getCampo() == "fecnac")$campo = "Fecha de Nacimiento";
            if($mmercurio43->getCampo() == "sexo")$campo = "Sexo";
            $html .= "<tr>";
            $html .= "<td>{$mmercurio43->getDocumento()}</td>";
            $html .= "<td>{$mercurio07->getNombre()}</td>";
            $html .= "<td>".$campo."</td>";
            if($mmercurio43->getCampo() == 'sexo'){
                if($mmercurio43->getValor() == 'M'){
                    $valor = 'Masculino';
                }if($mmercurio43->getValor() == 'F'){
                    $valor = 'Femenino';
                }
            }else{
                    $valor = $mmercurio43->getValor();
            }
            $html .= "<td>{$valor}</td>";
            $mercurio21 = $this->Mercurio21->findBySql("select * from mercurio43,mercurio21 where mercurio43.log= mercurio21.id AND mercurio43.log='{$mmercurio43->getLog()}' limit 1");
            $html .= "<td>{$mercurio21->getFecha()}</td>";
            $html .= "<td onclick=\"aprobar('{$mmercurio43->getId()}',this);\">".Tag::image("desktop/security-high.png", "width: 25px", "height: 25px", "style: cursor: pointer;")."</td>";
            $html .= "<td onclick=\"rechazar('{$mmercurio43->getId()}',this);\">".Tag::image("desktop/security-low.png", "width: 25px", "height: 25px", "style: cursor: pointer;")."</td>";
            $html .= "<td onclick=\"verdoc('{$mmercurio43->getId()}');\">".Tag::image("desktop/search.png","width: 25px", "height: 25px", "style: cursor: pointer;")."</td>";
            $html .= "<td onclick=\"info('{$mmercurio43->getId()}');\">".Tag::image("desktop/search.png","width: 25px", "height: 25px", "style: cursor: pointer;")."</td>";
            $html .= "</tr>";    
        }
        $html .= "</tbody>";
        $html .= "</table>";
        $this->setParamToView("html", $html);
    }

    public function aprobarAction(){
        try{
            try{
                $this->setResponse("ajax");
                $modelos = array("mercurio43");
                $Transaccion = parent::startTrans($modelos);
                $id = $this->getPostParam("mid");
                $motivo = $this->getPostParam("motivo");
                $tipo = "E";
                $today = new Date();
                $this->Mercurio43->updateAll("estado='A', fecest='$today',motivo='$motivo'","conditions: id = '$id'  ");
                $mercurio43 = $this->Mercurio43->findFirst("id = '$id' ");
                $documento=$mercurio43->getDocumento();
                $campo=$mercurio43->getCampo();
                $valor=$mercurio43->getValor();
                if($campo == 'cedrep' || $campo == 'nomrep' || $campo == 'fecnac' || $campo == 'sexo'){
                    $mercurio46 = $this->Mercurio46->findFirst("log = {$mercurio43->getLog()}");
                    $ruta = "digitalizacion\afiliados\ ".$mercurio46->getFecnac()->getUsingFormat('y')."\ ".$mercurio46->getFecnac()->getUsingFormat('m')."\ ".$mercurio46->getFecnac()->getUsingFormat('d')."\ {$mercurio46->getDocumento()}\ ";
                    $param = array(
                                'cedtra'=>$mercurio46->getDocumento(),
                                'coddoc'=>$mercurio46->getCoddoc(),
                                'prinom'=>$mercurio46->getPrinom(),
                                'segnom'=>$mercurio46->getSegnom(),
                                'priape'=>$mercurio46->getPriape(),
                                'segape'=>$mercurio46->getSegape(),
                                'sexo'=>$mercurio46->getSexo(),
                                'fecnac'=>$mercurio46->getFecnac()->getUsingFormatDefault(),
                                'fecsis'=>$today->getUsingFormatDefault(),
                                'usuario'=>'Consulta',
                                'celular'=>'',
                                'telefono'=>'',
                                'ruta' => str_replace(" ","",$ruta)
                            );
                    $idrep = parent::webService('IngresarAportes015',$param);
                    if($campo == 'cedrep'){
                        $campo = 'idrepresentante';
                        $valor = $idrep[0]['result'];
                    }
                }
                if($campo == 'cedrep' || $campo == 'razsoc' || $campo == 'idrepresentante'){
                    $result = parent::webService('ActualizacionDatoPrincipal',array("tipo"=>$tipo,"documento"=>$documento,"campo"=>$campo,"valor"=>$valor,"motivo"=>$motivo));
                    //Debug::addVariable("a",$result);
                    //throw new DebugException(0);    
                    if($result==false || (isset($result[0]['res']) && $result[0]['res']!=true)){
                        parent::ErrorTrans();
                    }
                }
                if($mercurio43->getCampo() == "cedrep" || $mercurio43->getCampo() == "idrepresentante")$campo = "Cedula de Representante";
                if($mercurio43->getCampo() == "fecnac")$campo = "Fecha de Nacimiento";
                if($mercurio43->getCampo() == "sexo")$campo = "Sexo";
                if($mercurio43->getCampo() == "nomrep")$campo = "Nombre de Representante";
                if($mercurio43->getCampo() == "razsoc")$campo = "Razon Social";
                $mercurio07 = $this->Mercurio07->findBySql("SELECT * FROM mercurio07 WHERE documento='{$mercurio43->getDocumento()}' limit 1");
                $email = $mercurio07->getEmail();
                $nombre = $mercurio07->getNombre();
                $documento = $mercurio07->getDocumento();
                if($mercurio43->getCampo() == 'cedrep'){
                    $valor = $mercurio46->getDocumento();
                }
                $msg = "Gracias por utilizar el Servicio en Linea de Comfamiliar Huila, una vez revisada la informacion suministrada por su empresa, me permito indicar usted acaba de realizar el proceso de Actualizacion datos principales como Empresa de la siguiente informacion:
                        <br><br><b>RAZON SOCIAL</b>: $nombre <br> <b>NIT</b>: $documento  <br>
                        <br><b>$campo</b>: $valor<br>
                        <br>Cualquier inquietud con gusto sera atendida por uno de nuestros funcionarios a traves de nuestras lineas telefonicas en las agencias de Neiva, Pitalito, Garzon y la plata.
                        ";
                $file = "";
                parent::enviarCorreo("Actualizacion de Datos ", "Actualizacion de datos", $email, "Actualizacion de datos", $msg, $file);
                parent::finishTrans();
                $response = parent::successFunc("Actualizacion de Datos Correcto",null);
                return $this->renderText(json_encode($response));
            }catch (DbException $e){
                parent::setLogger($e->getMessage());
                parent::ErrorTrans();
            }
        }catch (TransactionFailed $e){
            parent::setLogger("id => $id ");
            $response = parent::errorFunc("Error al Actualizar datos");
            return $this->renderText(json_encode($response));
        }

    }

    public function rechazarAction(){
        try{ 
            try{
                $this->setResponse("ajax");
                $modelos = array("mercurio43");
                $Transaccion = parent::startTrans($modelos);
                $id = $this->getPostParam("mid");
                $motivo = $this->getPostParam("motivo");
                $today = new Date();
                $mercurio43 = $this->Mercurio43->findBySql("SELECT * FROM mercurio43 WHERE id='$id' limit 1");
                $valor = $mercurio43->getValor();
                $mercurio07 = $this->Mercurio07->findBySql("SELECT * FROM mercurio07 WHERE documento='{$mercurio43->getDocumento()}' limit 1");
                $email = $mercurio07->getEmail();
                $nombre = $mercurio07->getNombre();
                $documento = $mercurio07->getDocumento();
                $funcionario = parent::getActUser('usuario');
                $this->Mercurio43->updateAll("estado='X',fecest='$today',motivo='$motivo',usuario='$funcionario'","conditions: id = '$id' ");
                $asunto = "Rechazo de Empresa";
                if($mercurio43->getCampo() == "cedrep")$campo = "Cedula de Representante";
                if($mercurio43->getCampo() == "nomrep")$campo = "Nombre de Representante";
                if($mercurio43->getCampo() == "razsoc")$campo = "Razon Social";
                if($mercurio43->getCampo() == "fecnac")$campo = "Fecha de Nacimiento";
                if($mercurio43->getCampo() == "sexo")$campo = "Sexo";
                    $msg = "Gracias por utilizar el Servicio en Linea de Comfamiliar una vez revisada la informacion suministrada por su empresa, me permito indicar que su solicitud de actualizacion de datos principales como Empresa, ha sido rechazada a continuacion se informa la causal
                    <br><br><b>RAZON SOCIAL:</b> $nombre 
                    <br><b>NIT:</b> $documento  <br>
                    <br> {$campo}: {$valor}
                    <br> Estado: Rechazado
                    <br> Motivo: $motivo
                    <br><br>
                    Le invitamos a completar la informacion y realizar nuevamente su solicitud.
                    <br><br>
                    Cualquier inquietud con gusto sera atendida por uno de nuestros funcionarios a traves de nuestras lineas telefonicas en las agencias de Neiva, Pitalito, Garzon y la plata.
                    ";
                $file = "";
                parent::enviarCorreo("Rechazo de cambio de datos Principales", $id, $email, "Rechazo de cambio de datos Principales", $msg, $file);
                parent::finishTrans();
                $response = parent::successFunc("Rechazo con Exito",null);
                return $this->renderText(json_encode($response));
            }catch (DbException $e){
                parent::setLogger($e->getMessage());
                parent::ErrorTrans();
            }   
        }catch (TransactionFailed $e){
            $response = parent::errorFunc("Error ");
            return $this->renderText(json_encode($response));
        }
    }

    public function verdocumentosAction(){
        $this->setResponse("ajax");
        $empresa = $this->getPostParam("empresa");
        $mercurio47 = $this->Mercurio47->find("numero = '$empresa'");
        $mercurio43 = $this->Mercurio43->findFirst("id = '$empresa'");
        $lista = "";
        $lista .= "<table class='resultado-consul' style='width: 100%; border: 1px double #000; border-collapse: separate; margin: auto;'>";
        $lista .= "<thead><tr style='background-color: #959595; font-size: 1.2em;'>";
        $lista .= "<th style='height: 18px;'>Item</th><th>Archivo</th>";
        $lista .= "<th>&nbsp;</th>";
        $lista .= "</tr></thead>";
        $item = 1;
        foreach($mercurio47 as $mmercurio47){
            $mercurio12 = $this->Mercurio12->findFirst("coddoc = '{$mmercurio47->getCoddoc()}'");
            $lista .= "<tr>";
            $lista .= "<td>{$item}</td>";
            $lista .= "<td class='list-arc'>{$mercurio12->getDetalle()}</td>";
            $lista .= "<td><a target='_blank' href='../{$mmercurio47->getNomarc()}'>".Tag::image("desktop/search.png","width: 25px","height: 25px","style: cursor: pointer;")."</a></td>";
            $lista .= "</tr>";
            $item++;
        }
        $lista .= "</table>";
        return $this->renderText(json_encode($lista));
    }

    public function infoAction(){
        $this->setResponse("ajax");
        $id = $this->getPostParam('id');
        $mercurio43 = $this->Mercurio43->FindFirst("id = $id");
        $formu ="";
        $formu .="<div id='empresaCampos'>";
        $formu .= Tag::form("id: form-completar","autocomplete: off","enctype: multipart/form-data");
        $formu .= "<table cellpadding='5'style='width:100%; margin: 10px '> ";
        $formu .= "<tr>";   
        $formu .= "<td colspan='2'><label> Id : </label></td>";
        $formu .= "<td>".Tag::textField("id","value: {$mercurio43->getId()}","readonly: readonly")."</td>";   
        $formu .= "<td><label> Documento : </label></td>";
        $formu .= "<td>".Tag::textField("documento","value: {$mercurio43->getDocumento()}","readonly: readonly")."</td>";   
        $formu .= "</tr>";   
        $formu .= "<tr>";   
        $formu .= "<td colspan='2'><label> Campo Modificado: </label></td>";
            if($mercurio43->getCampo() == "cedrep")$mercurio43->setCampo("Cedula de Representante");
            if($mercurio43->getCampo() == "nomrep")$mercurio43->setCampo("Nombre de Representante");
            if($mercurio43->getCampo() == "razsoc")$mercurio43->setCampo("Razon Social");
            if($mercurio43->getCampo() == "fecnac")$mercurio43->setCampo("Fecha de Nacimiento");
            if($mercurio43->getCampo() == "sexo")$mercurio43->setCampo("Sexo");
        $formu .= "<td>".Tag::textField("campo","value: {$mercurio43->getCampo()}","readonly: readonly")."</td>";   
        $formu .= "<td><label> Campo Actualizado : </label></td>";
        $formu .= "<td>".Tag::textField("valor","value: {$mercurio43->getValor()}","readonly: readonly")."</td>";   
        $formu .= "</tr>";   
        if($mercurio43->getCampo() == 'cedrep' OR $mercurio43->getCampo() == 'Cedula de Representante'){
            $mercurio46 = $this->Mercurio46->findFirst("log = '{$mercurio43->getLog()}'");
            $formu .= "<tr>";   
            $formu .= "<td colspan='2'><label> Primer Nombre : </label></td>";
            $formu .= "<td>".Tag::textField("prinom","value: {$mercurio46->getPrinom()}","readonly: readonly")."</td>";   
            $formu .= "<td><label> Segundo Nombre : </label></td>";
            $formu .= "<td>".Tag::textField("segnom","value: {$mercurio46->getSegnom()}","readonly: readonly")."</td>";   
            $formu .= "</tr>";   
            $formu .= "<tr>";   
            $formu .= "<td colspan='2'><label> Primer Apellido : </label></td>";
            $formu .= "<td>".Tag::textField("priape","value: {$mercurio46->getPriape()}","readonly: readonly")."</td>";   
            $formu .= "<td><label> Segundo Apellido : </label></td>";
            $formu .= "<td>".Tag::textField("segape","value: {$mercurio46->getSegape()}","readonly: readonly")."</td>";   
            $formu .= "</tr>";   
            $formu .= "<tr>";   
            $formu .= "<td colspan='2'><label> Fecha de Nacimiento : </label></td>";
            $formu .= "<td>".Tag::textField("fecnac","value: {$mercurio46->getFecnac()}","readonly: readonly")."</td>";   
            $formu .= "<td><label> Genero : </label></td>";
            $formu .= "<td>".Tag::textField("sexo","value: {$mercurio46->getSexo()}","readonly: readonly")."</td>";   
            $formu .= "</tr>";   
        }
        $formu .= "</table>";
        $formu .= Tag::endForm();
        $formu .="</div>";
        return $this->renderText(json_encode($formu));
    }
}
?>
