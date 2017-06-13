<?php

class Mercurio20Controller extends ApplicationController {

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

    public function buscarAction($page="",$pagina=""){
        $this->setParamToView("titulo",$this->title);
        if($page==""){
            $this->setTemplateAfter(array("escritorio","reporte"));
            $documento = $this->getPostParam("documento","striptags");
            $tipo = $this->getPostParam("tipo","striptags");
            $fecini = $this->getPostParam("fecini","striptags");
            $fecfin = $this->getPostParam("fecfin","striptags");

            $documento = $this->filter("documento: $documento","qchar");
            $tipo = $this->filter("tipo: $tipo","qchar");
            $condiciones = array();
            if(!empty($documento))$condiciones[] = $documento;
            if(!empty($tipo))$condiciones[] = $tipo;
            if(!empty($fecini) AND !empty($fecfin))$condiciones[] = "fecha between '$fecini' and '$fecfin'";
            $this->query = count($condiciones)!=0 ? join(" AND ", $condiciones) : " 1=1 ";
            $this->setParamToView("botones",true);
            $gener = Tag::paginate($this->Mercurio20->findAllBySql("select count(*) as id,accion,fecha from mercurio20 where {$this->query} group by accion"),1,10);
            $this->setParamToView('gener',$gener);
            $this->setParamToView("html",parent::navegacion($gener,Router::getController()));
        } else {
            $this->setResponse('view');
            $this->setParamToView("botones",false);
            $gener = parent::paginate2("mercurio20","select count(*) as id,accion,fecha from mercurio20 where {$this->query} group by accion","accion",$page,$pagina);
            $this->setParamToView('gener',$gener);
            $this->setParamToView("html",parent::navegacion($gener,Router::getController()));
        }
    }

    public function borrarAction($documento=''){
        try{
            try {
                $modelos = array("mercurio20");
                $Transaccion = parent::startTrans($modelos);
                $this->Mercurio20->deleteAll("mercurio20.documento='$documento'");
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
        Tag::displayTo("documento",$this->Mercurio20->maximum("mercurio20.documento")+1);
    }

    public function validaPkAction(){
        $this->setResponse("ajax");
        $documento = $this->getPostParam("documento");
        $response = true;
        $l = $this->Mercurio20->count("*","conditions: documento = '$documento'");
        if($l>0)$response=false;
        $this->renderText(json_encode($response));
    }

    public function editarAction($documento){
        $this->setParamToView("titulo",$this->title);
        $mercurio20 = $this->Mercurio20->findFirst("documento = '$documento'");
        if($mercurio20==false){
            $this->estado = 4;
            return $this->redirect(parent::getControllerName()."/index");
        }
        Tag::displayTo("documento",$mercurio20->getDocumento());
        Tag::displayTo("email",$mercurio20->getEmail());
        Tag::displayTo("tipo",$mercurio20->getTipo());
        Tag::displayTo("agencia",$mercurio20->getAgencia());
        Tag::displayTo("clave",$mercurio20->getClave());
    }

    public function guardarAction($documento=''){
        try{
            try {
                $modelos = array("mercurio20");
                $Transaccion = parent::startTrans($modelos);
                $today = new Date();
                if(!empty($documento)){
		            $mercurio20 = $this->Mercurio20->findFirst("mercurio20.documento='$documento'");
		            $clave = $this->getPostParam("clave","striptags","extraspaces");
		            if ($mercurio20->getClave() != $clave){
                        $mclave = parent::encriptar($clave);
			            $mercurio20->setClave($mclave);
                    }
                    $mercurio20->setFeccla($today->getUsingFormatDefault());
                }else{
		            $mercurio20 = new Mercurio20();
		            $mercurio20->setDocumento($this->Mercurio20->maximum("mercurio20.documento")+1);
		            $clave = $this->getPostParam("clave","striptags","extraspaces");
                    $mclave = parent::encriptar($clave);
                    $mercurio20->setClave($mclave);
                    $mercurio20->setFeccla($today->getUsingFormatDefault());
                }
                $mercurio20->setTransaction($Transaccion);
                $mercurio20->setAgencia($this->getPostParam("agencia","striptags","extraspaces"));
                $mercurio20->setEmail($this->getPostParam("email","striptags","extraspaces"));
                if(!$mercurio20->save()){
                    parent::setLogger($mercurio20->getMessages());
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
        if($format=='pdf')
        parent::createReport("mercurio20",$_fields,$condi,$this->title,$format);
        else
        parent::report_xls("mercurio20",$_fields,$condi,$this->title,$format);
    }

}
?>
