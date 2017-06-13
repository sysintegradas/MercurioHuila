<?php

class Mercurio21Controller extends ApplicationController {

    private $estado = 100;
    private $title = "Movimientos de Afiliados";
    private $query = "";

    public function initialize(){
        $this->setTemplateAfter('escritorio');
        $this->setPersistance(true);
    }

    public function indexAction(){
        $this->setParamToView("titulo",$this->title);
        switch ($this->estado){
            case 0:
                Message::success("Guardado de {$this->title} con Exito");
                break;
            case 1:
                Message::error("Error al Guardar {$this->title}");
                break;
            case 2:
                Message::success("Borrado de {$this->title} Exitoso");
                break;
            case 3:
                Message::error("Error al Borrar {$this->title}");
                break;
            case 4:
                Message::warning("El Registro no Existe");
                break;
            default:
        }
        $this->estado = 100;
    }

    public function detalleInformacionAction(){
        $this->setResponse("ajax");
        $id = $this->getPostParam("id");
        $tipo = $this->getPostParam("tipo");

        if($tipo=="30"){
            $tabla = "Mercurio"."$tipo";
            $sql = $this->$tabla->findFirst("id ='$id'");
            $documentos = $this->Mercurio37->find("numero ='$id'");
            $tipoarc ="Empresa";
            $documento =$sql->getNit();
        }else if($tipo=="31"){
            $tabla = "Mercurio"."$tipo";
            $sql = $this->$tabla->findFirst("id ='$id'");
            $documentos = $this->Mercurio38->find("numero ='$id'");
            $tipoarc ="Trabajador";
            $documento =$sql->getNit();
        }else if($tipo=="32"){
            $tabla = "Mercurio"."$tipo";
            $sql = $this->$tabla->findFirst("id ='$id'");
            $documentos = $this->Mercurio39->find("numero ='$id'");
            $tipoarc ="Conyuge";
            $documento =$sql->getCedtra();
        }else if($tipo=="34"){
            $tabla = "Mercurio"."$tipo";
            $sql = $this->$tabla->findFirst("id ='$id'");
            $documentos = $this->Mercurio40->find("numero ='$id'");
            $tipoarc ="Beneficiario";
            $documento =$sql->getCedtra();
        }else if($tipo=="35"){
            $tabla = "Mercurio"."$tipo";
            $sql = $this->$tabla->findFirst("id ='$id'");
            $documentos = "";
            $tipoarc ="Retiro de trabajador";
            $documento =$sql->getNit();
        }else if($tipo=="33"){
            $tabla = "Mercurio"."$tipo";
            $sql = $this->$tabla->findFirst("id ='$id'");
            $documento = array();
            $mercurio33 = $this->Mercurio33->find("log ='{$sql->getLog()}'");
            foreach($mercurio33 as $mmercurio33){
                $mercurio28 = $this->Mercurio28->findFirst("campo ='{$mmercurio33->getCampo()}'");
                if($mercurio28 != FALSE){
                    $detalle = $mercurio28->getDetalle();
                }else{
                    $detalle = "";
                }
                $documentos = "";
                $tipoarc="Actualizacion de datos $detalle";
                $documento[] = $mmercurio33->getId();
            }
        }else if($tipo=="43"){
            $tabla = "Mercurio"."$tipo";
            $sql = $this->$tabla->findFirst("id ='$id'");
            $documento = array();
            $mercurio43 = $this->Mercurio43->find("log ='{$sql->getLog()}'");
            foreach($mercurio43 as $mmercurio43){
                $documentos[] = $this->Mercurio47->find("numero ='$id'");
                $tipoarc ="Cambio datos principales";
                $documento[] =$sql->getId();
            }
        }else if($tipo=="45"){
            $tabla = "Mercurio"."$tipo";
            $sql = $this->$tabla->findFirst("id ='$id'");
            $documentos = "";
            $tipoarc ="Anexo de certificados";
            $documento =$sql->getDocumento();
        }
        $html = "";
        $html .= "<table class='table_resul' border='0' cellpadding='10' cellspacing='2' width='100%'>";
        if(is_array($documento) && ($tipo == '33' || $tipo == '43')){
            $documento = is_array($documento) ? join(",", $documento) : $documento;
            $tabla = "Mercurio"."$tipo";
            $query = $this->$tabla->find("id IN ($documento)");
            foreach($query as $mquery){
                $mercurio28 = $this->Mercurio28->findFirst("campo ='{$mquery->getCampo()}'");
                if($mercurio28 != FALSE){
                    $detalle = $mercurio28->getDetalle();
                }else{
                    $detalle = "";
                }
                $mer07 = $this->Mercurio07->findFirst("documento = '{$mquery->getDocumento()}'");
                $tipoarc="Actualizacion de datos $detalle";
                $nombre =$mer07->getNombre();
                $html .= "<tr>";
                $html .= "<th colspan=6 align='center'>Datos basicos</th>";
                $html .= "</tr>";
                $html .= "<tr>";
                $html .= "<td  align='center'><b>Tipo</b></td>";
                $html .= "<td colspan='5' align='center'>$tipoarc</td>";
                $html .= "</tr>";
                $html .= "<tr>";
                $html .= "<td  align='center'><b>Documento</b></td>";
                $html .= "<td  colspan='5' align='center'>{$mer07->getDocumento()}</td>";
                $html .= "</tr>";
                $html .= "<tr>";
                $html .= "<td align='center'><b>Nombre</b></td>";
                $html .= "<td  colspan='5' align='center'>{$nombre}</td>";
                $html .= "</tr>";
                $html .= "<tr>";
                $html .= "<td colspan=6 align='center'>Archivos</td>";
                $html .= "</tr>";
                $html .= "<tr>";
                $item = 1;
                if($tipo == '33' || $tipo == '35' || $tipo == '45'){
                    if($tipo != '33'){
                        $html .= "<tr>";
                        $html .= "<td>{$item}</td>";
                        $html .= "<td colspan=4 class='list-arc'>DOCUMENTO ADJUNTO</td>";
                        $html .= "<td><a target='_blank' href='../../{$sql->getNomarc()}'>".Tag::image("desktop/search.png","width: 25px","height: 25px","style: cursor: pointer;")."</a></td>";
                        $html .= "</tr>";
                    }
                }else{
                    if(!is_array($documentos)){
                        foreach($documentos as $archivo){
                            $mercurio12 = $this->Mercurio12->findFirst("coddoc = '{$archivo->getCoddoc()}'");
                            $html .= "<tr>";
                            $html .= "<td>{$item}</td>";
                            $html .= "<td colspan=4 class='list-arc'>{$mercurio12->getDetalle()}</td>";
                            $html .= "<td><a target='_blank' href='../../{$archivo->getNomarc()}'>".Tag::image("desktop/search.png","width: 25px","height: 25px","style: cursor: pointer;")."</a></td>";
                            $html .= "</tr>";
                            $item++;
                        }
                    }
                    else{
                        foreach($mdocumentos as $key => $documentos){
                            foreach($documentos as $archivo){
                                $mercurio12 = $this->Mercurio12->findFirst("coddoc = '{$archivo->getCoddoc()}'");
                                $html .= "<tr>";
                                $html .= "<td>{$item}</td>";
                                $html .= "<td colspan=4 class='list-arc'>{$mercurio12->getDetalle()}</td>";
                                $html .= "<td><a target='_blank' href='../../{$archivo->getNomarc()}'>".Tag::image("desktop/search.png","width: 25px","height: 25px","style: cursor: pointer;")."</a></td>";
                                $html .= "</tr>";
                                $item++;
                            }
                        }
                    }
                }
                $html .= "</tr>";
            }
        }
        else{
            $mmer07 = $this->Mercurio07->find("documento = '$documento'");
            foreach($mmer07 as $mer07){
                $nombre =$mer07->getNombre();
                $html .= "<tr>";
                $html .= "<th colspan=6 align='center'>Datos basicos</th>";
                $html .= "</tr>";
                $html .= "<tr>";
                $html .= "<td  align='center'><b>Tipo</b></td>";
                $html .= "<td colspan='5' align='center'>$tipoarc</td>";
                $html .= "</tr>";
                $html .= "<tr>";
                $html .= "<td  align='center'><b>Documento</b></td>";
                $html .= "<td  colspan='5' align='center'>{$mer07->getDocumento()}</td>";
                $html .= "</tr>";
                $html .= "<tr>";
                $html .= "<td align='center'><b>Nombre</b></td>";
                $html .= "<td  colspan='5' align='center'>{$nombre}</td>";
                $html .= "</tr>";
                $html .= "<tr>";
                $html .= "<td colspan=6 align='center'>Archivos</td>";
                $html .= "</tr>";
                $html .= "<tr>";
                $item = 1;
                if($tipo == '33' || $tipo == '35' || $tipo == '45'){
                    if($tipo != '33'){
                        $html .= "<tr>";
                        $html .= "<td>{$item}</td>";
                        $html .= "<td colspan=4 class='list-arc'>DOCUMENTO ADJUNTO</td>";
                        $html .= "<td><a target='_blank' href='../../{$sql->getNomarc()}'>".Tag::image("desktop/search.png","width: 25px","height: 25px","style: cursor: pointer;")."</a></td>";
                        $html .= "</tr>";
                    }
                }else{
                    if(!is_array($documentos)){
                        foreach($documentos as $archivo){
                            $mercurio12 = $this->Mercurio12->findFirst("coddoc = '{$archivo->getCoddoc()}'");
                            $html .= "<tr>";
                            $html .= "<td>{$item}</td>";
                            $html .= "<td colspan=4 class='list-arc'>{$mercurio12->getDetalle()}</td>";
                            $html .= "<td><a target='_blank' href='../../{$archivo->getNomarc()}'>".Tag::image("desktop/search.png","width: 25px","height: 25px","style: cursor: pointer;")."</a></td>";
                            $html .= "</tr>";
                            $item++;
                        }
                    }
                    else{
                        foreach($mdocumentos as $key => $documentos){
                            foreach($documentos as $archivo){
                                $mercurio12 = $this->Mercurio12->findFirst("coddoc = '{$archivo->getCoddoc()}'");
                                $html .= "<tr>";
                                $html .= "<td>{$item}</td>";
                                $html .= "<td colspan=4 class='list-arc'>{$mercurio12->getDetalle()}</td>";
                                $html .= "<td><a target='_blank' href='../../{$archivo->getNomarc()}'>".Tag::image("desktop/search.png","width: 25px","height: 25px","style: cursor: pointer;")."</a></td>";
                                $html .= "</tr>";
                                $item++;
                            }
                        }
                    }
                }
                $html .= "</tr>";
            }
        }
        $html .= "</table>";

        return $this->renderText(json_encode($html));
    }

    public function buscarAction($page="",$pagina=""){
        $this->setParamToView("titulo",$this->title);
        if($page==""){
            $this->setTemplateAfter(array("escritorio","reporte"));
            $id = $this->getPostParam("id","striptags");
            $documento = $this->getPostParam("documento","striptags");
            $tipo = $this->getPostParam("tipo","striptags");
            $fecini = $this->getPostParam("fecini","striptags");
            $fecfin = $this->getPostParam("fecfin","striptags");

            $id = $this->filter("id: $id","qchar");
            $documento = $this->filter("documento: $documento","qchar");
            $tipo = $this->filter("tipo: $tipo","qchar");
            $condiciones = array();
            if(!empty($id))$condiciones[] = $id;
            if(!empty($documento))$condiciones[] = $documento;
            if(!empty($tipo))$condiciones[] = $tipo;
            if(!empty($fecini) AND !empty($fecfin))$condiciones[] = "fecha between '$fecini' and '$fecfin'";
            $this->query = count($condiciones)!=0 ? join(" AND ", $condiciones) : " 1=1 ";
            $this->setParamToView("botones",true);
            $cond = "AND (exists (select * from  mercurio32 where mercurio32.log = mercurio21.id) ";
            $cond .= "OR exists (select * from  mercurio31 where mercurio31.log = mercurio21.id) ";
            $cond .= "OR exists (select * from  mercurio30 where mercurio30.log = mercurio21.id) ";
            $cond .= "OR exists (select * from  mercurio33 where mercurio33.log = mercurio21.id) ";
            $cond .= "OR exists (select * from  mercurio34 where mercurio34.log = mercurio21.id) ";
            $cond .= "OR exists (select * from  mercurio43 where mercurio43.log = mercurio21.id) ";
            $cond .= "OR exists (select * from  mercurio45 where mercurio45.log = mercurio21.id)) ";
            $gener = Tag::paginate($this->Mercurio21->find($this->query." $cond","order: mercurio21.id DESC"),1,10);
            $this->setParamToView('gener',$gener);
            $this->setParamToView("html",parent::navegacion($gener,Router::getController()));
        } else {
            $this->setResponse('view');
            $this->setParamToView("botones",false);
            $cond = "AND (exists (select * from  mercurio32 where mercurio32.log = mercurio21.id) ";
            $cond .= "OR exists (select * from  mercurio31 where mercurio31.log = mercurio21.id) ";
            $cond .= "OR exists (select * from  mercurio30 where mercurio30.log = mercurio21.id) ";
            $cond .= "OR exists (select * from  mercurio33 where mercurio33.log = mercurio21.id) ";
            $cond .= "OR exists (select * from  mercurio34 where mercurio34.log = mercurio21.id) ";
            $cond .= "OR exists (select * from  mercurio43 where mercurio43.log = mercurio21.id) ";
            $cond .= "OR exists (select * from  mercurio45 where mercurio45.log = mercurio21.id)) ";
            $gener = parent::paginate("mercurio21",$this->query." $cond","id",$page,$pagina,"DESC");
            $this->setParamToView('gener',$gener);
            $this->setParamToView("html",parent::navegacion($gener,Router::getController()));
        }
    }

    public function borrarAction($documento=''){
        try{
            try {
                $modelos = array("mercurio21");
                $Transaccion = parent::startTrans($modelos);
                $this->Mercurio21->deleteAll("mercurio21.documento='$documento'");
                parent::finishTrans();
                $this->estado = 2;
                return $this->redirect(parent::getControllerName()."/index");
            } catch(DbException $e){
                parent::setLogger($e->getMessage());
                parent::ErrorTrans();
            }
        } catch (TransactionFailed $e){
            $this->estado = 3;
            return $this->redirect(parent::getControllerName()."/index");
        }
    }

    public function nuevoAction(){
        $this->setParamToView("titulo",$this->title);
	    Tag::displayTo("estado","A");
        Tag::displayTo("documento",$this->Mercurio21->maximum("mercurio21.documento")+1);
    }

    public function validaPkAction(){
        $this->setResponse("ajax");
        $documento = $this->getPostParam("documento");
        $response = true;
        $l = $this->Mercurio21->count("*","conditions: documento = '$documento'");
        if($l>0)$response=false;
        $this->renderText(json_encode($response));
    }

    public function editarAction($documento){
        $this->setParamToView("titulo",$this->title);
        $mercurio21 = $this->Mercurio21->findFirst("documento = '$documento'");
        if($mercurio21==false){
            $this->estado = 4;
            return $this->redirect(parent::getControllerName()."/index");
        }
        Tag::displayTo("documento",$mercurio21->getDocumento());
        Tag::displayTo("email",$mercurio21->getEmail());
        Tag::displayTo("tipo",$mercurio21->getTipo());
        Tag::displayTo("agencia",$mercurio21->getAgencia());
        Tag::displayTo("clave",$mercurio21->getClave());
    }

    public function guardarAction($documento=''){
        try{
            try {
                $modelos = array("mercurio21");
                $Transaccion = parent::startTrans($modelos);
                $today = new Date();
                if(!empty($documento)){
		            $mercurio21 = $this->Mercurio21->findFirst("mercurio21.documento='$documento'");
		            $clave = $this->getPostParam("clave","striptags","extraspaces");
		            if ($mercurio21->getClave() != $clave){
                        $mclave = parent::encriptar($clave);
			            $mercurio21->setClave($mclave);
                    }
                    $mercurio21->setFeccla($today->getUsingFormatDefault());
                }else{
		            $mercurio21 = new Mercurio21();
		            $mercurio21->setDocumento($this->Mercurio21->maximum("mercurio21.documento")+1);
		            $clave = $this->getPostParam("clave","striptags","extraspaces");
                    $mclave = parent::encriptar($clave);
                    $mercurio21->setClave($mclave);
                    $mercurio21->setFeccla($today->getUsingFormatDefault());
                }
                $mercurio21->setTransaction($Transaccion);
                $mercurio21->setAgencia($this->getPostParam("agencia","striptags","extraspaces"));
                $mercurio21->setEmail($this->getPostParam("email","striptags","extraspaces"));
                if(!$mercurio21->save()){
                    parent::setLogger($mercurio21->getMessages());
                    parent::ErrorTrans();
                }
                parent::finishTrans();
                $this->estado = 0;
                $this->redirect(parent::getControllerName()."/index");
            } catch(DbException $e){
                parent::setLogger($e->getMessage());
                parent::ErrorTrans();
            }
        } catch (TransactionFailed $e){
            $this->estado = 1;
            $this->redirect(parent::getControllerName()."/index");
        }
    }

    public function reporteAction($format='pdf'){
        $condi = $this->query;
        $_fields = array();
        $_fields["documento"] = array('header'=>"Documento",'size'=>"15",'align'=>"C");
        $_fields["nombre"] = array('header'=>"Nombre",'size'=>"60",'align'=>"C");
        $_fields["fecha"] = array('header'=>"Fecha",'size'=>"60",'align'=>"C");
        $_fields["hora"] = array('header'=>"Hora",'size'=>"60",'align'=>"C");
        $_fields["accion"] = array('header'=>"Accion Realizada",'size'=>"60",'align'=>"C");
        //$_fields["estado"] = array('header'=>"Estado",'size'=>"60",'align'=>"C");
        if($format=='pdf')
            parent::createReport("mercurio21",$_fields,$condi,$this->title,$format);
        else
            parent::report_xls("mercurio21",$_fields,$condi,$this->title,$format);
    }

}
?>
