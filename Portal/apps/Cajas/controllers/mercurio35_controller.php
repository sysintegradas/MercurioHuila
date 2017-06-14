<?php

class Mercurio35Controller extends ApplicationController {

    private $title = "Retiro de Trabajador";

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
        $html .= "<th>Fecha retiro</th>";
        $html .= "<th>Cod. Estado</th>";
        $html .= "<th>Motivo</th>";
        //$html .= "<th>Fecha de Retiro</th>";
        $html .= "<th>Nota</th>";
        $html .= "<th>Estado</th>";
        $html .= "<th>Fecha sistema</th>";
        $html .= "<th>Aprobar</th>";
        $html .= "<th>Rechazar</th>";
        $html .= "<th>Documentos</th>";
        $html .= "</thead>";
        if($filt =="ADAD")
            $mercurio35 = $this->Mercurio35->find("estado='P'");
        else
            $mercurio35 = $this->Mercurio35->findAllBySql("select mercurio35.* from mercurio35,mercurio07 where mercurio35.estado='P' and mercurio07.agencia='$filt' and mercurio07.documento=mercurio35.nit AND mercurio35.usuario='".Session::getData('usuario')."'");
            $result = parent::webService("CodigoEstado",array());
        foreach($mercurio35 as $mmercurio35){
            $codest = $mmercurio35->getCodest();
            foreach($result as $mresult){
                if($mresult['coddoc'] == $codest){
                    $motivo = $mresult['detalle'];
                }
            
            }
            $html .= "<tr>";
            $html .= "<td>{$mmercurio35->getNit()}</td>";
            $html .= "<td>{$mmercurio35->getCedtra()}</td>";
            $html .= "<td>{$mmercurio35->getFecret()}</td>";
            $html .= "<td>{$mmercurio35->getCodest()}</td>";
            $html .= "<td>{$motivo}</td>";
            //$html .= "<td>{$mmercurio35->getFecret()}</td>";
            $html .= "<td>{$mmercurio35->getnota()}</td>";
            $html .= "<td>{$mmercurio35->getEstado()}</td>";
            $mercurio21 = $this->Mercurio21->findBySql("select * from mercurio35,mercurio21 where mercurio35.log= mercurio21.id AND mercurio35.log='{$mmercurio35->getLog()}' limit 1");
            if($mercurio21 == FALSE)
                $html .= "<td></td>";
            else
                $html .= "<td>{$mercurio21->getFecha()}</td>";
            $html .= "<td onclick=\"aprobarF('{$mmercurio35->getId()}',this);\">".Tag::image("desktop/security-high.png", "width: 25px", "height: 25px", "style: cursor: pointer;")."</td>";
            $html .= "<td onclick=\"rechazar('{$mmercurio35->getId()}',this);\">".Tag::image("desktop/security-low.png", "width: 25px", "height: 25px", "style: cursor: pointer;")."</td>";
            $html .= "<td onclick=\"verdoc('{$mmercurio35->getId()}');\">".Tag::image("desktop/search.png","width: 25px", "height: 25px", "style: cursor: pointer;")."</td>";
            $html .= "</tr>";
        }
        $html .= "</tbody>";
        $html .= "</table>";
        $this->setParamToView("html", $html);
    }

    public function rechazarAction(){
        try{
            try{
                $this->setResponse("ajax");
                $response = parent::startFunc();
                $modelos = array("mercurio35");
                $Transaccion = parent::startTrans($modelos);
                $id = $this->getPostParam("id");
                $motivo = $this->getPostParam("motivo");
                $mercurio35 = $this->Mercurio35->findFirst("id = '$id'");
                $retiro = $mercurio35->getArray();
                $nit= $retiro['nit'];
                $cedtra = $retiro['cedtra'];
                $codest = $retiro['codest'];   
                $est = $codest;
                $result = parent::webService("DatosTrabajador",array("nit"=>"$nit","cedtra"=> $cedtra));
                $nombre = $result[0]['nombre']; 
                $today = new Date();
                $funcionario = parent::getActUser('usuario');
                $update = $this->Mercurio35->updateAll("estado='X',fecest='$today',motivo='$motivo',usuario='$funcionario'","conditions: id = '$id' ");
                $mercurio35 = $this->Mercurio35->findBySql("SELECT * FROM mercurio35 WHERE id='$id' limit 1");
                $documento= $mercurio35->getCedtra();
                $result = parent::webService('autenticar',array("tipo"=>"T","documento"=>$documento, 'coddoc'=>Session::getData('coddoc')));
                $nombre = $result[0]['nombre'];
                $mercurio07 = $this->Mercurio07->findBySql("SELECT * FROM mercurio07 WHERE documento='{$mercurio35->getNit()}' AND tipo='E' limit 1");
                $email = $mercurio07->getEmail();
                
                $asunto = "Rechazo de Actualizacion de Datos";
                $codest = parent::webService("CodigoEstado",array());
                $estado = "";
                foreach($codest as $mcodest){
                    if($mcodest['coddoc'] == $est)
                        $estado = $mcodest['detalle'];
                }
                $msg = "Gracias por utilizar el Servicio en Linea de Comfamiliar Huila, una vez revisada la informacion suministrada por su empresa y nuestra base de datos, me permito indicar que su solicitud de Retiro, anulacion ($estado) de (los) siguiente (s) trabajador (es), ha sido rechazada a continuacion se informa la causal.
                            <br><br> <b> RAZON SOCIAL: </b> {$mercurio07->getNombre()}
                            <br> <b> NIT: </b> {$mercurio07->getDocumento()}
                            <br> <b> CEDULA: </b> {$mercurio35->getCedtra()}
                            <br> <b> NOMBRE: </b> {$mercurio35->getNomtra()}
                            <br> <b> OBSERVACION: </b> $motivo
                            <br><br>
                    Le invitamos a completar la informacion y realizar nuevamente su solicitud.
                    <br><br>
                    Cualquier inquietud con gusto sera atendida por uno de nuestros funcionarios a traves de nuestras lineas telefonicas en las agencias de Neiva, Pitalito, Garzon y la plata.
                    ";
                $file = "";
                parent::enviarCorreo("Rechazo con Exito ", $id, $email, $asunto, $msg, $file);
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
                $response = parent::startFunc();
                $modelos = array("mercurio35");
                $Transaccion = parent::startTrans($modelos);
                $id = $this->getPostParam("id");
                //$fecret = $this->getPostParam("fecret");
                $today = new Date();
                $mercurio35 = $this->Mercurio35->findFirst("id = '$id'");
                $retiro = $mercurio35->getArray();
                $nit = $retiro['nit'];    
                $cedtra = $retiro['cedtra'];    
                $coddoc = $retiro['coddoc'];    
                $fecret = $retiro['fecret'];
                $fecest = $fecret;
                $codest = $retiro['codest'];   
                $estado="I";
                $fecsis = new Date();
                $observacion = "{$fecsis->getUsingFormatDefault()} - Consulta - SE INACTIVO EL TRABAJADOR CON LA EMPRESA {$mercurio35->getNit()}";
                $result = parent::webService('novedadRetiro',array("coddoc"=>$coddoc,"cedtra"=>$cedtra,"fecret"=>$fecest,"codest"=>$codest,"fecsis"=>$fecsis->getUsingFormatDefault(),"observacion"=>$observacion,"nit"=>$mercurio35->getNit()));
                Debug::addVariable("a",$result);
                Debug::addVariable("b",array("coddoc"=>$coddoc,"cedtra"=>$cedtra,"fecret"=>$fecest,"codest"=>$codest,"fecsis"=>$fecsis->getUsingFormatDefault(),"observacion"=>$observacion,"nit"=>$mercurio35->getNit()));
                //throw new DebugException(0);

                if($result==false){
                    //throw new DebugException(0);
                    parent::ErrorTrans();
                }
                if($result[0]['result']=="false"){
                    //throw new DebugException(0);
                    parent::ErrorTrans();
                }
                $user=Auth::getActiveIdentity();
                $Log = new Logger("File","{$user['usuario']}.log");
                $Log->setFormat("[%date%] %controller%/%action% on %application% Error: %message%");
                $result = parent::webService('autenticar',array("tipo"=>"E","documento"=>$nit, 'coddoc'=>Session::getData('coddoc')));
                $nombre = $result[0]['nombre'];
                $mercurio07 = $this->Mercurio07->findFirst("tipo='E' AND documento='{$mercurio35->getNit()}'");
                $email = $mercurio07->getEmail();
                $est = $codest;
                $codest = parent::webService("CodigoEstado",array());
                $estado = "";
                foreach($codest as $mcodest){
                    if($mcodest['coddoc'] == $est)
                        $estado = $mcodest['detalle'];
                }
                $file = "";
                    $msg = "Gracias por utilizar el Servicio en L&iacute;nea de Comfamiliar Huila, una vez revisada la informaci&oacute;n suministrada por su empresa y nuestra base de datos, me permito indicar que usted acaba de realizar el proceso de Retiro, ( $estado) del (los) siguiente(s) Trabajador(es):
                    <b> NIT: </b> {$mercurio07->getDocumento()}
                    <b> Razon Social: </b>{$mercurio07->getNombre()}<br><br>
                    <br> CEDULA: {$mercurio35->getCedtra()}
                    <br> NOMBRE : {$mercurio35->getNomtra()}
                    <br> FECHA RETIRO: {$fecret}
                    <br> CAUSAL: {$estado}
                    <br><br>
                    Es muy importante que para cualquier inquietud y/o reclamo tenga en cuenta el Numero de solicitud y se comunique con nuestra Entidad, a traves de las lineas de atencion en las Agencias de Neiva, Garzon, Pitalito y  la Plata
                    ";
                parent::enviarCorreo("Confirmacion Retiro", $nombre, $email, "Retiro de trabajador", $msg, $file);
                // parent::enviarCorreo("Confirmacion Retiro", $nombre, , "Retiro de trabajador", $msg, $file);
                // parent::enviarCorreo("Confirmacion Retiro", $nombre,, "Retiro de trabajador", $msg, $file);
                $today = new Date();
                $this->Mercurio35->updateAll("estado='A',fecest='{$fecret}',motivo='{$observacion}'","conditions: cedtra = '{$cedtra}'");
                parent::finishTrans();
                $response = parent::successFunc("Envio de Informacion Exitosa",null);
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

    public function formularioAction(){
        $this->setResponse("ajax");
        $id = $this->getPostParam("id");
   
        $formu ="";
        $formu .="<div id='TrabajadoresCampos'>";
        $formu .= Tag::form("id: form-completar","autocomplete: off","enctype: multipart/form-data");
        $formu .= "<table cellpadding='5'style='width:100%; margin: 5px '> ";
        /*
        $formu .= "<tr>";   
        $formu .= "<td><label>Fecha de Retiro:<label></td>";  
        $formu .= "<td>".TagUser::calendar("fecret","size: 15")."</td>";   
        $formu .= "</tr>";   
        $formu .= "<tr>";   
        */
        $formu .= "<td colspan='2'><label> Observaci&oacute;n: </label></td>";
        $formu .= "</tr>";   
        $formu .= "<tr>";   
        $formu .= "<td colspan='2'>".Tag::textArea("observacion","cols: 50","rows: 5","class: form-control")."</td>";
        $formu .= "</tr>";   
        $formu .= "</table>";
        $formu .="<table width='85%' >";
        $formu .= "<tr>";   
        $formu .= "<td>".Tag::button("Retirar","class: submit","style: width: 100px; margin-left: 40%","onclick: aprobarF()")."</td>";   
        $formu .= "</tr>";   
        $formu .= "</table>";
        $formu .="</div>";
        $formu .="</div>";
        $formu .= Tag::endForm();
        return $this->renderText(json_encode($formu));

    }
    public function verdocumentosAction(){
        $this->setResponse("ajax");
        $id = $this->getPostParam("trabajador");
        $mercurio35 = $this->Mercurio35->findFirst("id = '$id'");
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
        $lista .= "<td><a target='_blank' href='../{$mercurio35->getNomarc()}'>".Tag::image("desktop/search.png","width: 25px","height: 25px","style: cursor: pointer;")."</a></td>";
        $lista .= "</tr>";
        $lista .= "</table>";
        return $this->renderText(json_encode($lista));
    }
}
?>
