<?php

class Mercurio45Controller extends ApplicationController {

    private $title = "Verificacion Certificados";

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
        $this->setParamToview('titulo',"Verificaci&oacute;n de Certificados ");
        $html = "";
        $html .= "<table class='resultado-consul' border=1>";
        $html .= "<thead>";
        $html .= "<th>Cedula Trabajador</th>";
        $html .= "<th>Nombre Trabajador</th>";
        $html .= "<th>Documento Beneficiario</th>";
        $html .= "<th>Nombre Beneficiario</th>";
        $html .= "<th>Certificado</th>";
		$html .= "<th>Fecha</th>";
        $html .= "<th>Aprobar</th>";
        $html .= "<th>Rechazar</th>";
        $html .= "<th>Archivos</th>";
        $html .= "</thead>";
        $html .= "<tbody>";
        if($filt =="ADAD")
            $mercurio45 = $this->Mercurio45->find("estado='P'");
        else
            $mercurio45 = $this->Mercurio45->findAllBySql("select mercurio45.* from mercurio45,mercurio07 where mercurio45.estado='P' and mercurio07.agencia='$filt' and mercurio07.documento=mercurio45.documento AND mercurio45.usuario='".Session::getData('usuario')."'");
        foreach($mercurio45 as $mmercurio45){
            $mercurio07 = $this->Mercurio07->findFirst("tipo = 'T' AND documento = '{$mmercurio45->getDocumento()}'");
            if($mercurio07->getNombre() == NULL){
                $result = parent::webService('autenticar',array("tipo"=>'T',"documento"=>$mmercurio45->getDocumento(), 'coddoc'=>Session::getData('coddoc')));
                $this->Mercurio07->updateAll("nombre='{$result[0]['nombre']}'","conditions: tipo = 'T' AND documento = '{$mmercurio45->getDocumento()}' ");
                $mercurio07->setNombre($result[0]['nombre']);
            }
            $html .= "<tr id='{$mmercurio45->getId()}' >";
            $html .= "<td>{$mmercurio45->getDocumento()}</td>";
            $html .= "<td>{$mercurio07->getNombre()}</td>";
            $html .= "<td>{$mmercurio45->getCodben()}</td>";
            if($mmercurio45->getNomben() == NULL){
                $benef = parent::webService("Nucfambeneficiarios", array("cedtra"=>$mmercurio45->getDocumento()));      
                if($benef != false){
                    foreach($benef as $mbenef){
                        $benefi = $mbenef['beneficiario'];
                        $datben = parent::webService("Datosfamiliar",array("documento"=>$benefi));      
                        foreach($datben as $mdatben){
                            if($mdatben['documento'] == $mmercurio45->getCodben()){
                                $this->Mercurio45->updateAll("nomben = '{$mdatben['nombre']}' ","conditions: codben = '{$mmercurio45->getCodben()}'  ");
                                $nomben = $mdatben['nombre'];
                            }
                        }
                    }
                }
            }else{
                $nomben = $mmercurio45->getNomben();
            }
            $html .= "<td>{$nomben}</td>";
            $mmercurio91  = $this->Migra091->findFirst("iddetalledef = {$mmercurio45->getCodcer()}");
            $html .= "<td>{$mmercurio91->getDetalledefinicion()}</td>";
            $mercurio21 = $this->Mercurio21->findBySql("select * from mercurio45,mercurio21 where mercurio45.log= mercurio21.id AND mercurio45.log='{$mmercurio45->getLog()}' limit 1");
            $html .= "<td>{$mercurio21->getFecha()}</td>";
            $html .= "<td onclick=\"completar('{$mmercurio45->getId()}',this);\">".Tag::image("desktop/security-high.png", "width: 25px", "height: 25px", "style: cursor: pointer;")."</td>";
            $html .= "<td onclick=\"rechazar('{$mmercurio45->getId()}',this);\">".Tag::image("desktop/security-low.png", "width: 25px", "height: 25px", "style: cursor: pointer;")."</td>";
            $html .= "<td onclick=\"verdoc('{$mmercurio45->getId()}');\">".Tag::image("desktop/search.png","width: 25px", "height: 25px", "style: cursor: pointer;")."</td>";
            $html .= "</tr>";    
        }
        $html .= "</tbody>";
        $html .= "</table>";
        $this->setParamToView("html", $html);

	}

    public function aprobarAction(){
        try{
            try{
                $today = new Date();
                $this->setResponse("ajax");
                $response = parent::startFunc();
                $perini =  $this->getPostParam("perini");
                $perfin =  $this->getPostParam("perfin");
                $fecpre =  $this->getPostParam("fecpre");
                $modelos = array("mercurio45");
                $Transaccion = parent::startTrans($modelos);
                $id = $this->getPostParam("id");
                $mercurio45 = $this->Mercurio45->findBySql("SELECT * FROM mercurio45 WHERE id='{$id}' limit 1");
                $benef = parent::webService("Nucfambeneficiarios", array("cedtra"=>$mercurio45->getDocumento()));      
                if($benef != false){
                    foreach($benef as $mbenef){
                        $benefi = $mbenef['beneficiario'];
                        $datben = parent::webService("Datosfamiliar",array("documento"=>$benefi,"cedtra"=>$mercurio45->getDocumento()));
                        //$datben = parent::webService("Datosfamiliar",array("documento"=>$benefi));      
                        if($datben[0]['documento'] == $mercurio45->getCodben()){
                            $idbeneficiario = $mbenef['beneficiario'];
                            $nomben = $datben[0]['nombre'];
                            $parentesco = $datben[0]['parent'];
                        }
                    }
                }
                $tipo = "T";
                $mercurio45 = $this->Mercurio45->findFirst("id = '$id' ");
                $param = array("cedtra"=>$mercurio45->getDocumento());
                $res = parent::webService("Nucfambeneficiarios", $param);
                $benef = $res[0]['beneficiario'];

                
                $datben = parent::webService("Datosfamiliar",array("documento"=>$idbeneficiario,"cedtra"=>$mercurio45->getDocumento()));
                //$datben = parent::webService("Datosfamiliar",array("documento"=>$benef));          
                $fecret = $datben[0]['fecret'];
                $documento=$mercurio45->getDocumento();
                $cert = $mercurio45->getCodcer();
                $mesini = "";
                $mesini2 = "";
                $fecpreobj = new Date($fecpre);
                if($fecpreobj->getMonth() <= 2){
                    $mesini = "01";
                }else{
                    $mesini = str_pad(($fecpreobj->getMonth()-2),2,0,STR_PAD_LEFT);
                }
                if($fecpreobj->getMonth() <= 8){
                    $mesini2 = "07";
                }else{
                    $mesini2 = str_pad(($fecpreobj->getMonth()-2),2,0,STR_PAD_LEFT);
                }
                switch($cert){
                    case '55':
                        if($mercurio45->getPeriodo() == 0){
                            $fecha = new Date();
                            $perini = $fecha->getYear()."$mesini";
                            $perfin = $fecha->getYear()."12";
                        }else{
                            $fecha = new Date();
                            if($fecpreobj->getMonth()==2){
                                $perini = ($fecha->getYear()-1)."12";
                                $perfin = ($fecha->getYear()-1)."12";
                            }else{
                                $perini = ($fecha->getYear()-1)."11";
                                $perfin = ($fecha->getYear()-1)."12";
                            }
                        }
                        break;
                    case '56':
                        $fecha = new Date();
                        if($fecpreobj->getMonth() >= 07 && $fecpreobj->getMonth() <= 12){
                            if($mercurio45->getPeriodo() == 0){
                                $perini = $fecha->getYear()."$mesini2";
                                $perfin = $fecha->getYear()."12";
                            }else{
                                if($fecpreobj->getMonth()=="7"){
                                    $perini = $fecha->getYear()."05";
                                    $perfin = $fecha->getYear()."06";
                                }else{
                                    $perini = $fecha->getYear()."06";
                                    $perfin = $fecha->getYear()."06";
                                }
                            }
                        }else{
                            if($mercurio45->getPeriodo() == 0){
                                $perini = $fecha->getYear()."$mesini";
                                $perfin = $fecha->getYear()."06";
                            }else{
                                if($fecpreobj->getMonth()==2){
                                    $perini = ($fecha->getYear()-1)."12";
                                    $perfin = ($fecha->getYear()-1)."12";
                                }else{
                                    $perini = ($fecha->getYear()-1)."11";
                                    $perfin = ($fecha->getYear()-1)."12";
                                }
                            }
                        }
                        break;
                    case '57':
                        if($mercurio45->getPeriodo() == 0){
                            $fecha = new Date();
                            $perini = $fecha->getYear()."$mesini";
                            $perfin = $fecha->getYear()."12";
                        }else{
                            $fecha = new Date();
                            if($fecpreobj->getMonth()==2){
                                $perini = ($fecha->getYear()-1)."12";
                                $perfin = ($fecha->getYear()-1)."12";
                            }else{
                                $perini = ($fecha->getYear()-1)."11";
                                $perfin = ($fecha->getYear()-1)."12";
                            }
                        }
                        break;
                    case '58':
                        if($mercurio45->getPeriodo() == 0){
                            $fecha = new Date();
                            $perini = $fecha->getYear()."$mesini";
                            $perfin = $fecha->getYear()."12";
                        }else{
                            $fecha = new Date();
                            if($fecpreobj->getMonth()==2){
                                $perini = ($fecha->getYear()-1)."12";
                                $perfin = ($fecha->getYear()-1)."12";
                            }else{
                                $perini = ($fecha->getYear()-1)."11";
                                $perfin = ($fecha->getYear()-1)."12";
                            }
                        }
                        break;
                }
                if($this->Mercurio45->count("*","conditions: codcaj='{$mercurio45->getCodcaj()}' and codben='{$mercurio45->getCodben()}' and estado='A' and perini like '".substr($perini,0,4)."%' and perfin<='$perfin'")>0){
                    $response = parent::errorFunc("no puede aceptar este certificado el beneficiario ya tiene un certificado en esos periodos");
                    return $this->renderText(json_encode($response));
                }
                //$sql "Insert into aportes026 values('"+idbenef+"','"+parent+"','"+codcer+"','"+perini+"','"+perfin+"','"+fecpre+"','"+forpre+"','"+estado+"','Consulta','"+fecsis+"')";
                $parametros = array("idbenef"=>$idbeneficiario,"parent"=>$datben[0]['parent'],"perini"=>$perini,"perfin"=>$perfin,"fecpre"=>$fecpre,"estado"=>"A","fecsis"=>$today->getUsingFormatDefault(),"codcer"=>$cert,"usuario"=>"Consulta","forpre"=>"P");
                Debug::addVariable("a",$parametros);
                //throw new DebugException(0);    
                $result = parent::webService('ActualizacionCertificado',$parametros);
                if($result==false || (isset($result[0]['res']) && $result[0]['res']!=true)){
                    parent::ErrorTrans();
                }
                $today = new Date();
                $this->Mercurio45->updateAll("estado='A', fecest='$today',perini='$perini',perfin='$perfin',fecpre='$fecpre'","conditions: id = '$id'  ");
                $msg = "Gracias por utilizar el Servicio en Linea de Comfamiliar Huila, a continuacion relacionamos la informacion del Certificado solicitado por usted.
                    <br><br> No. DE SOLICITUD: {$mercurio45->getId()}
                <br> IDENTIFICACION: {$mercurio45->getCodben()}
                <br> NOMBRE: $nomben
                    <br> FECHA DE RADICACION: {$today->getUsingFormatDefault()}
                <br><br>
                    Es muy importante que para cualquier inquietud y/o reclamo tenga en cuenta el Numero de solicitud y se comunique con nuestra Entidad, a traves de las lineas de atencion en las Agencias de Neiva, Garzon, Pitalito y  la Plata.
                    ";
                $file = "";
                $cedtra = $mercurio45->getDocumento();
                $mercurio07 = $this->Mercurio07->findBySql("SELECT * FROM mercurio07 WHERE documento='{$cedtra}' limit 1");
                $email = $mercurio07->getEmail();
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
                $fecha = new Date();
                $response = parent::startFunc();
                $modelos = array("mercurio45");
                $Transaccion = parent::startTrans($modelos);
                $id = $this->getPostParam("mid");
                $mercurio45 = $this->Mercurio45->findBySql("SELECT * FROM mercurio45 WHERE id ='{$id}' limit 1");
                $benef = parent::webService("Nucfambeneficiarios", array("cedtra"=>$mercurio45->getDocumento()));      
                if($benef != false){
                    foreach($benef as $mbenef){
                        $benefi = $mbenef['beneficiario'];
                        $datben = parent::webService("Datosfamiliar",array("documento"=>$benefi,"cedtra"=>$mercurio45->getDocumento()));      
                        if($datben[0]['documento'] == $mercurio45->getCodben()){
                            $idbeneficiario = $mbenef['beneficiario'];
                            $nomben = $datben[0]['nombre'];
                            $parentesco = $datben[0]['parent'];
                        }
                    }
                }
                $cedtra = $mercurio45->getDocumento();
                $motivo = $this->getPostParam("motivo");
                $today = new Date();
                $funcionario = parent::getActUser('usuario');
                $this->Mercurio45->updateAll("estado='X',fecest='$today',motivo='$motivo',usuario='$funcionario'","conditions: id = '$id' ");

                $result = parent::webService('autenticar',array("tipo"=>"T","documento"=>$cedtra, 'coddoc'=>Session::getData('coddoc')));
                $nombre = $result[0]['nombre'];
                $mercurio07 = $this->Mercurio07->findBySql("SELECT * FROM mercurio07 WHERE documento='{$cedtra}' limit 1");
                $email = $mercurio07->getEmail();
                $asunto = "Rechazo de entrega de Certificados";
                $today = new Date();
                $msg = "Gracias por utilizar el Servicio en Linea de Comfamiliar Huila, le informamos que la informacion del Certificado solicitado por usted, no ha sido procesada. Le invitamos a que anexe la documentacion y/o ingrese la informacion solicitada y nuevamente realice el proceso.
                    <br><br> No. DE SOLICITUD: {$mercurio45->getId()}
                <br> IDENTIFICACION: {$mercurio45->getCodben()}
                <br> NOMBRE: $nomben
                    <br> CAUSAL DE DEVOLUCION: $motivo
                    <br><br>
                    Es muy importante que para cualquier inquietud y/o reclamo tenga en cuenta el Numero de solicitud y se comunique con nuestra Entidad, a traves de las lineas de atencion en las Agencias de Neiva, Garzon, Pitalito y  la Plata.
                    ";
                $file = "";
                parent::enviarCorreo("Rechazo de Anexo Certificados", $nombre, $email, $asunto, $msg, $file);
                parent::finishTrans();
                $response = parent::successFunc("Rechazo con exito",null);
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
        $id = $this->getPostParam("empresa");
        $mercurio45 = $this->Mercurio45->findFirst("id = '$id'");
        $lista = "";
        $lista .= "<table class='resultado-consul' style='width: 100%; border: 1px double #000; border-collapse: separate; margin: auto;'>";
        $lista .= "<thead><tr style='background-color: #959595; font-size: 1.2em;'>";
        $lista .= "<th style='height: 18px;'>Item</th><th>Archivo</th>";
        $lista .= "<th>&nbsp;</th>";
        $lista .= "</tr></thead>";
        $item = 1;
        $lista .= "<tr>";
        $lista .= "<td>{$item}</td>";
        $lista .= "<td class='list-arc'>Archivo</td>";
        $lista .= "<td><a target='_blank' href='../{$mercurio45->getNomarc()}'>".Tag::image("desktop/search.png","width: 25px","height: 25px","style: cursor: pointer;")."</a></td>";
        $lista .= "</tr>";
        $lista .= "</table>";
        return $this->renderText(json_encode($lista));
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
        $formu .= "<td><label>Fecha de Presentación<label></td>";   
        $formu .= "<td>".TagUser::calendar("fecpre","size: 15")."</td>";   
        $formu .= "</tr>";   
        $formu .= "</table>";
        $formu .= Tag::endForm();
        $formu .="<table width='85%' >";
        $formu .= "<tr>";   
        $formu .= "<td>".Tag::button("Aprobar","class: submit","style: width: 300px; margin-left: 40%","onclick: aprobarF()")."</td>";   
        $formu .= "</tr>";   
        $formu .= "</table>";
        $formu .="</div>";
        return $this->renderText(json_encode($formu));
    }



}

