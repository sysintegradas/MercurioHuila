<?php

class Mercurio33Controller extends ApplicationController{

	private $title = "Afiliacion Datos Trabajador";

	public function initialize(){
		$this->setTemplateAfter('escritorio');
		$this->setPersistance(true);
	}

    public function indexAction($tipo){
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
        if($tipo == 'E' ){
            $this->setParamToview('titulo',"Actualización Datos Empresa");
            if($filt =="ADAD"){
                $mercurio33 = $this->Mercurio33->findAllBySql("select * from (select mercurio33.* from mercurio33,mercurio07 where mercurio33.estado='P' and mercurio07.documento=mercurio33.documento AND mercurio33.tipo='E' AND mercurio07.tipo='E' order by id desc) as mercurio33 group by documento,campo order by id asc");
            }
            else{
                $mercurio33 = $this->Mercurio33->findAllBySql("select * from (select mercurio33.* from mercurio33,mercurio07 where mercurio33.estado='P' and mercurio07.agencia='$filt' and mercurio07.documento=mercurio33.documento AND mercurio33.tipo='E' AND mercurio07.tipo='E' AND mercurio33.usuario='".Session::getData('usuario')."' order by id desc) as mercurio33 group by documento,campo order by id asc");
            }
        }else if($tipo == 'T'){
            $this->setParamToview('titulo',"Actualización Datos Trabajador");
            if($filt =="ADAD"){
                $mercurio33 = $this->Mercurio33->findAllBySql("select * from (select mercurio33.* from mercurio33,mercurio07 where mercurio33.estado='P' and mercurio07.documento=mercurio33.documento AND mercurio33.tipo='T' AND mercurio07.tipo='T' order by id desc) as mercurio33 group by documento,campo order by id asc");
            }
            else{
                $mercurio33 = $this->Mercurio33->findAllBySql("select * from (select mercurio33.* from mercurio33,mercurio07 where mercurio33.estado='P' and mercurio07.agencia='$filt' and mercurio07.documento=mercurio33.documento AND mercurio33.tipo='T' AND mercurio07.tipo='T' AND mercurio33.usuario='".Session::getData('usuario')."' order by id desc) as mercurio33 group by documento,campo order by id asc");
            }
		}
        else {
			$this->setParamToview('titulo',"Actualización Datos Conyuge");
		}
		$html = "";
		$html .= "<table class='resultado-consul' border=1>";
		$html .= "<thead>";
		$html .= "<th>Documento</th>";
		$html .= "<th>Nombre</th>";
		$html .= "<th>Campo Modificado</th>";
		$html .= "<th>Campo Actualizado</th>";
		$html .= "<th>Fecha</th>";
		$html .= "<th>Aprobar</th>";
		$html .= "<th>Rechazar</th>";
		$html .= "</thead>";
		$html .= "<tbody>";
        foreach($mercurio33 as $mmercurio33){
            $mercurio07 = $this->Mercurio07->findFirst("tipo = '{$tipo}' AND documento = '{$mmercurio33->getDocumento()}'");
            if($mercurio07->getNombre() == NULL){
                $result = parent::webService('autenticar',array("tipo"=>$tipo,"documento"=>$mmercurio33->getDocumento(), 'coddoc'=>Session::getData('coddoc')));
                $this->Mercurio07->updateAll("nombre='{$result[0]['nombre']}'","conditions: tipo = '{$tipo}' AND documento = '{$mmercurio33->getDocumento()}' ");
                $mercurio07->setNombre($result[0]['nombre']);
            }
            $mercurio28 = $this->Mercurio28->findFirst("tipo = '$tipo' AND campo = '{$mmercurio33->getCampo()}'");
            if($mmercurio33->getCampo() == 'idciuresidencia' || $mmercurio33->getCampo() == 'idciucorresp' || $mmercurio33->getCampo() == ''){
                $gener08 = $this->Gener08->findFirst("codciu = '{$mmercurio33->getValor()}'");
                $valor = $gener08->getDetciu(); 
            }
            else if($mmercurio33->getCampo() == 'idbarrio' || $mmercurio33->getCampo() == 'idbarriocorresp'){
                if($mmercurio33->getValor() != '@'){
                    $migra087 = $this->Migra087->findFirst("codbar = '{$mmercurio33->getValor()}'");
                    if($migra087 != FALSE){
                        $valor = $migra087->getDetalle(); 
                    }else{
                        continue;
                    }
                }else{
                    $valor = $mmercurio33->getValor();
                }
            }else if($mmercurio33->getCampo() == 'idzona'){
                if($mmercurio33->getValor() != '@'){
                    $migra087 = $this->Migra089->findFirst("codzon = '{$mmercurio33->getValor()}'");
                    $valor = $migra087->getDetzon(); 
                }else{
                    $valor = $mmercurio33->getValor();
                }
            }else{
                $valor = $mmercurio33->getValor();
            }
            $camp = $mmercurio33->getCampo();
            if($camp == 'idzona' || $camp == 'idciuresidencia' || $camp == 'idciucorresp') continue;
            $html .= "<tr>";
            $html .= "<td>{$mmercurio33->getDocumento()}</td>";
            $html .= "<td>".$mercurio07->getNombre()."</td>";
            $html .= "<td>".$mercurio28->getDetalle()."</td>";
            $html .= "<td>{$valor}</td>";
            $mercurio21 = $this->Mercurio21->findBySql("select * from mercurio33,mercurio21 where mercurio33.log= mercurio21.id AND mercurio33.log='{$mmercurio33->getLog()}' limit 1");
            $html .= "<td>{$mercurio21->getFecha()}</td>";
            $html .= "<td onclick=\"aprobar('{$mmercurio33->getId()}',this);\">".Tag::image("desktop/security-high.png", "width: 25px", "height: 25px", "style: cursor: pointer;")."</td>";
            $html .= "<td onclick=\"rechazar('{$mmercurio33->getId()}',this);\">".Tag::image("desktop/security-low.png", "width: 25px", "height: 25px", "style: cursor: pointer;")."</td>";
            $html .= "</tr>";
        }
        $html .= "</tbody>";
        $html .= "</table>";
        $this->setParamToView("html", $html);
        $this->setParamToView("tipo", $tipo);
    }

    public function aprobarAction(){
        try{
            try{
                $this->setResponse("ajax");
                $modelos = array("mercurio33");
                $Transaccion = parent::startTrans($modelos);
                $id = $this->getPostParam("mid");
                $tipo = $this->getPostParam("tipo");
                $motivo = $this->getPostParam("motivo");
                $today = new Date();
                $mercurio33 = $this->Mercurio33->findFirst("id = '$id' ");
                $documento=$mercurio33->getDocumento();
                $campo=$mercurio33->getCampo();
                $campos_modify = array();
                $valores_modify = array();
                if($campo == 'idbarrio'){
                    $xmercurio33 = $this->Mercurio33->find("log = '{$mercurio33->getLog()}' AND campo IN ('idzona','idciuresidencia')");
                    foreach($xmercurio33 as $xm){
                        $valor=$xm->getValor();
                        $mercurio28 = $this->Mercurio28->findFirst("campo = '".$xm->getCampo()."' AND tipo='$tipo'");
                        $campos_modify[] = $mercurio28->getDetalle();
                        if($xm->getCampo() == 'idzona'){
                            $migra089 = $this->Migra089->findFirst("codzon='$valor'");
                            if($migra089 == false){
                                $valores_modify[] = $valor;
                            }
                            else{
                                $valores_modify[] = $migra089->getDetzon();
                            }
                        }
                        else{
                            $migra089 = $this->Migra089->findFirst("codciu='$valor'");
                            if($migra089 == false){
                                $valores_modify[] = $valor;
                            }
                            else{
                                $valores_modify[] = $migra089->getDetciu();
                            }
                        }
                        if($xm->getCampo() == 'idciuresidencia' && $tipo == 'E'){
                            $xm->setCampo("idciudad");
                        }
                        $this->Mercurio33->updateAll("estado='A', fecest='$today',motivo='$motivo'","conditions: campo='{$xm->getCampo()}' AND documento='{$documento}'");
                        $observacion = "{$today->getUsingFormatDefault()} - Consulta - Se Actualizo el seguiente campo desde el Portal de Consulta en linea";
                        $param = array("tipo"=>$tipo,"documento"=>$documento,"campo"=>$xm->getCampo(),"valor"=>$valor,"observacion"=>"$observacion");
                        $result = parent::webService('ActualizacionDato',$param);
                        if($result==false || $result[0]['result']=="false"){
                            parent::ErrorTrans();
                        }
                    }
                }
                if($campo == 'idbarriocorresp'){
                    $xmercurio33 = $this->Mercurio33->find("log = '{$mercurio33->getLog()}' AND campo IN ('idciucorresp')");
                    foreach($xmercurio33 as $xm){
                        $valor=$xm->getValor();
                        $mercurio28 = $this->Mercurio28->findFirst("campo = '".$xm->getCampo()."' AND tipo='$tipo'");
                        $campos_modify[] = $mercurio28->getDetalle();
                        $migra089 = $this->Migra089->findFirst("codciu='$valor'");
                        if($migra089 == false){
                            $valores_modify[] = $valor;
                        }
                        else{
                            $valores_modify[] = $migra089->getDetciu();
                        }
                        $this->Mercurio33->updateAll("estado='A', fecest='$today',motivo='$motivo'","conditions: campo='{$xm->getCampo()}' AND documento='{$documento}'");
                        $observacion = "{$today->getUsingFormatDefault()} - Consulta - Se Actualizo el seguiente campo desde el Portal de Consulta en linea";
                        $param = array("tipo"=>$tipo,"documento"=>$documento,"campo"=>$xm->getCampo(),"valor"=>$valor,"observacion"=>"$observacion");
                        $result = parent::webService('ActualizacionDato',$param);
                        if($result==false || $result[0]['result']=="false"){
                            parent::ErrorTrans();
                        }
                    }
                }
                $valor=$mercurio33->getValor();
                $this->Mercurio33->updateAll("estado='A', fecest='$today',motivo='$motivo'","conditions: campo='{$campo}' AND documento='{$documento}'");
                if($campo == 'idciuresidencia' && $tipo == 'E'){
                    $campo = 'idciudad';
                }
                $observacion = "{$today->getUsingFormatDefault()} - Consulta - Se Actualizo el seguiente campo desde el Portal de Consulta en linea";
                $param = array("tipo"=>$tipo,"documento"=>$documento,"campo"=>$campo,"valor"=>$valor,"observacion"=>"$observacion");
                $result = parent::webService('ActualizacionDato',$param);
                if($result==false || $result[0]['result']=="false"){
                    parent::ErrorTrans();
                }
                if($campo == 'idciudad' && $tipo == 'E'){
                    $param = array("tipo"=>$tipo,"documento"=>$documento,"campo"=>'iddepartamento',"valor"=>substr($valor,0,2));
                    $result = parent::webService('ActualizacionDato',$param);
                }if($campo == 'idciucorresp' && $tipo == 'E'){
                    $param = array("tipo"=>$tipo,"documento"=>$documento,"campo"=>'iddepcorresp',"valor"=>substr($valor,0,2));
                    $result = parent::webService('ActualizacionDato',$param);
                }if($campo == 'idciuresidencia' && $tipo == 'T'){
                    $param = array("tipo"=>$tipo,"documento"=>$documento,"campo"=>'iddepresidencia',"valor"=>substr($valor,0,2));
                    $result = parent::webService('ActualizacionDato',$param);
                }
                if($result==false || $result[0]['result']=="false"){
                    parent::ErrorTrans();
                }

                $mercurio07 = $this->Mercurio07->findFirst("documento = $documento AND tipo='$tipo'");
                $mercurio28 = $this->Mercurio28->findFirst("campo = '".$campo."' AND tipo='$tipo'");
                if($campo == 'idciuresidencia' || $campo == 'idciucorresp'  || $campo == 'codciu'){
                    $gener08 = $this->Gener08->findFirst("codciu = '{$valor}'");
                    $valor = $gener08->getDetciu();
                }else if($campo == 'idbarriocorresp' || $campo == 'idbarrio'){
                    $migra087 = $this->Migra087->findFirst("codbar = '{$valor}'");
                    $valor = $migra087->getDetalle();
                }
                $campo = $mercurio28->getDetalle();
                $campos_modify[] = $campo;
                $valores_modify[] = $valor;
                $nombre = $mercurio07->getNombre();
                $email = $mercurio07->getEmail();
                if($mercurio33->getCampo() == 'idciuresidencia' || $mercurio33->getCampo() == 'idciucorresp' || $mercurio33->getCampo() == 'codciu' || $mercurio33->getCampo() == 'idciudad'){
                    $gener08 = $this->Gener08->findFirst("codciu = '{$mercurio33->getValor()}'");
                    $valor = $gener08->getDetciu(); 
                }else if($mercurio33->getCampo() == 'idbarrio' || $mercurio33->getCampo() == 'idbarriocorresp'){
                    if($mercurio33->getValor() != '@'){
                        $migra087 = $this->Migra087->findFirst("codbar = '{$mercurio33->getValor()}'");
                        $valor = $migra087->getDetalle(); 
                    }else{
                        $valor = $mercurio33->getValor();
                    }
                }else if($mercurio33->getCampo() == 'idzona'){
                    if($mercurio33->getValor() != '@'){
                        $migra087 = $this->Migra089->findFirst("codzon = '{$mercurio33->getValor()}'");
                        $valor = $migra087->getDetzon(); 
                    }else{
                        $valor = $mercurio33->getValor();
                    }
                }else{
                    $valor = $mercurio33->getValor();
                }
                if($tipo == 'T'){
                $msg = "Gracias por utilizar el Servicio en Linea de Comfamiliar Huila, usted acaba de realizar el proceso de Actualizacion datos basicos como Trabajador de la siguiente informacion:
                        <br><br><b>Nombre</b>: $nombre <b>Identificacion</b>: $documento  <br>";
                        
                $i = 0;
                while($i<count($campos_modify)){
                    $msg .= "<br><b>{$campos_modify[$i]} :</b> {$valores_modify[$i]}<br>";
                    $i++;
                }
                $msg .= "<br>Cualquier inquietud con gusto sera atendida por uno de nuestros funcionarios a traves de nuestras lineas telefonicas en las agencias de Neiva, Pitalito, Garzon y la plata.";
                }else if($tipo == 'E'){
                $msg = "Gracias por utilizar el Servicio en Linea de Comfamiliar Huila, una vez revisada la informacion suministrada por su empresa, me permito indicar que usted acaba de realizar el proceso de Actualizacion datos basicos como Empresa de la siguiente informacion:
                        <br><br><b>RAZON SOCIAL</b>: $nombre <br> <b>NIT</b>: $documento  <br>";
                $i = 0;
                while($i<count($campos_modify)){
                    $msg .= "<br><b>{$campos_modify[$i]} :</b> {$valores_modify[$i]}<br>";
                    $i++;
                }
                $msg .= "<br>Cualquier inquietud con gusto sera atendida por uno de nuestros funcionarios a traves de nuestras lineas telefonicas en las agencias de Neiva, Pitalito, Garzon y la plata.";
                }
                $file = "";
                parent::enviarCorreo("Actualizacion de Datos ", "Actualizacion de datos", "$email", "Actualizacion de datos", $msg, $file);
                parent::finishTrans();
                $response = parent::successFunc("Actualizacion de Datos Correcto",null);
                return $this->renderText(json_encode($response));
            }catch (DbException $e){
                parent::setLogger($e->getMessage());
                parent::ErrorTrans();
            }
        }catch (TransactionFailed $e){
            parent::setLogger($e->getMessage());
            $response = parent::errorFunc("Error al Actualizar datos");
            return $this->renderText(json_encode($response));
        }

    }

    public function rechazarAction(){
        try{ 
            try{
                $this->setResponse("ajax");
                $fecha = new Date();
                $modelos = array("mercurio33");
                $Transaccion = parent::startTrans($modelos);
                $id = $this->getPostParam("mid");
                $motivo = $this->getPostParam("motivo");
                $today = new Date();
                $funcionario = parent::getActUser('usuario');
                $this->Mercurio33->updateAll("estado='X',fecest='$today',motivo='$motivo',usuario='$funcionario'","conditions: id = '$id' ");
                $mercurio33 = $this->Mercurio33->findBySql("SELECT * FROM mercurio33 WHERE id='$id' limit 1");
                $tipo = $mercurio33->getTipo();
                $documento= $mercurio33->getDocumento();
                $campo= $mercurio33->getCampo();
                $result = parent::webService('autenticar',array("tipo"=>$tipo,"documento"=>$documento, 'coddoc'=>Session::getData('coddoc')));
                $nombre = $result[0]['nombre'];
                $mercurio07 = $this->Mercurio07->findBySql("SELECT * FROM mercurio07 WHERE documento='{$documento}' AND (tipo='E' OR tipo='T') limit 1");
                $email = $mercurio07->getEmail();
                $nombre = $mercurio07->getNombre();
                $documento = $mercurio07->getDocumento();
                $mercurio28 = $this->Mercurio28->findFirst("tipo='$tipo' AND campo='$campo'");
                if($mercurio33->getCampo() == 'idciuresidencia' || $mercurio33->getCampo() == 'idciucorresp' || $mercurio33->getCampo() == 'codciu' || $mercurio33->getCampo() == 'idciudad'){
                    $gener08 = $this->Gener08->findFirst("codciu = '{$mercurio33->getValor()}'");
                    $valor = $gener08->getDetciu(); 
                }else if($mercurio33->getCampo() == 'idbarrio' || $mercurio33->getCampo() == 'idbarriocorresp'){
                    if($mercurio33->getValor() != '@'){
                        $migra087 = $this->Migra087->findFirst("codbar = '{$mercurio33->getValor()}'");
                        $valor = $migra087->getDetalle(); 
                    }else{
                        $valor = $mercurio33->getValor();
                    }
                }else if($mercurio33->getCampo() == 'idzona'){
                    if($mercurio33->getValor() != '@'){
                        $migra087 = $this->Migra089->findFirst("codzon = '{$mercurio33->getValor()}'");
                        $valor = $migra087->getDetzon(); 
                    }else{
                        $valor = $mercurio33->getValor();
                    }
                }else{
                    $valor = $mercurio33->getValor();
                }
                $asunto = "Rechazo de Actualizacion de Datos";
                if($tipo == 'T'){
                    $msg = "Gracias por utilizar el Servicio en Linea de Comfamiliar Huila, me permito indicar que una vez validada su solicitud de actualizacion de datos basicos como Trabajador, ha sido rechazada a continuacion se informa la causal
                    <br><br><b>Nombre:</b> $nombre 
                    <br><b>Identificacion:</b> $documento  <br>
                    <br> {$mercurio28->getDetalle()}: {$valor}
                    <br> Estado: Rechazado
                    <br> Motivo: $motivo
                    <br><br>
                    Le invitamos a completar la informacion y realizar nuevamente su solicitud.
                    <br><br>
                    Cualquier inquietud con gusto sera atendida por uno de nuestros funcionarios a traves de nuestras lineas telefonicas en las agencias de Neiva, Pitalito, Garzon y la plata.
                    ";
                }else if($tipo == 'E'){
                    $msg = "Gracias por utilizar el Servicio en Linea de Comfamiliar Huila, una vez revisada la informacion suministrada por su empresa, me permito indicar que su solicitud de actualizacion de datos basicos como Empresa, ha sido rechazada a continuacion se informa la causal
                    <br><br><b>RAZON SOCIAL:</b> $nombre 
                    <br><b>NIT:</b> $documento  <br>
                    <br> {$mercurio28->getDetalle()}: {$valor}
                    <br> Estado: Rechazado
                    <br> Motivo: $motivo
                    <br><br>
                    Le invitamos a completar la informacion y realizar nuevamente su solicitud.
                    <br><br>
                    Cualquier inquietud con gusto sera atendida por uno de nuestros funcionarios a traves de nuestras lineas telefonicas en las agencias de Neiva, Pitalito, Garzon y la plata.
                    ";
                }
                $file = "";
                parent::enviarCorreo("Rechazo Actualizacion de Datos ", $id, $email, $asunto, $msg, $file);
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
}
?>
