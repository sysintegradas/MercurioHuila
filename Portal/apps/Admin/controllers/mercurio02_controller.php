<?php

class Mercurio02Controller extends ApplicationController {

    private $estado = 100;
    private $title = "Cajas de Compesacion";
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
            $codcaj = $this->getPostParam("codcaj","striptags");
            $nit = $this->getPostParam("nit","striptags");
            $razsoc = $this->getPostParam("razsoc","striptags");

            $codcaj = $this->filter("codcaj: $codcaj","qchar");
            $nit = $this->filter("nit: $nit","qchar");
            $razsoc = $this->filter("razsoc: $razsoc","qchar");
            $condiciones = array();
            if(!empty($codcaj))$condiciones[] = $codcaj;
            if(!empty($nit))$condiciones[] = $nit;
            if(!empty($razsoc))$condiciones[] = $razsoc;
            $this->query = count($condiciones)!=0 ? join(" AND ", $condiciones) : " 1=1 ";
            $this->setParamToView("botones",true);
            $mercurio = Tag::paginate($this->Mercurio02->find($this->query,"order: codcaj"),1,10);
            $this->setParamToView('mercurio',$mercurio);
            $this->setParamToView("html",parent::navegacion($mercurio,Router::getController()));
        } else {
            $this->setResponse('view');
            $this->setParamToView("botones",false);
            $mercurio = parent::paginate("mercurio02",$this->query,"codcaj",$page,$pagina);
            $this->setParamToView('mercurio',$mercurio);
            $this->setParamToView("html",parent::navegacion($mercurio,Router::getController()));
        }
    }

    public function borrarAction($codcaj=''){
        try{
            try {
                $modelos = array("mercurio02");
                $Transaccion = parent::startTrans($modelos);
                $this->Mercurio02->deleteAll("codcaj='$codcaj'");
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
    }

    public function validaPkAction(){
        $this->setResponse("ajax");
        $codcaj = $this->getPostParam("codcaj");
        $response = true;
        $l = $this->Mercurio02->count("*","conditions: codcaj = '$codcaj'");
        if($l>0)$response=false;
        $this->renderText(json_encode($response));
    }

    public function editarAction($codcaj){
        $this->setParamToView("titulo",$this->title);
        $mercurio02 = $this->Mercurio02->findFirst("codcaj = '$codcaj'");
        if($mercurio02==false){
            $this->estado = 4;
            return $this->redirect(parent::getControllerName()."/index");
        }
        Tag::displayTo("codcaj",$mercurio02->getCodcaj());
        Tag::displayTo("nit",$mercurio02->getNit());
        Tag::displayTo("razsoc",$mercurio02->getRazsoc());
        Tag::displayTo("email",$mercurio02->getEmail());
        Tag::displayTo("direccion",$mercurio02->getDireccion());
        Tag::displayTo("telefono",$mercurio02->getTelefono());
        Tag::displayTo("codciu",$mercurio02->getCodciu());
    }

    public function guardarAction($codcaj=''){
        try{
            try {
                $modelos = array("mercurio02");
                $Transaccion = parent::startTrans($modelos);
                $today = new Date();
                if(!empty($codcaj)){
		            $mercurio02 = $this->Mercurio02->findFirst("mercurio02.codcaj='$codcaj'");
                }else{
		            $mercurio02 = new Mercurio02();
                    $mercurio02->setCodcaj($this->getPostParam("codcaj","striptags","extraspaces"));
                }
                $mercurio02->setTransaction($Transaccion);
                $mercurio02->setNit($this->getPostParam("nit","striptags","extraspaces"));
                $mercurio02->setRazsoc($this->getPostParam("razsoc","striptags","extraspaces"));
                $mercurio02->setEmail($this->getPostParam("email","striptags","extraspaces"));
                $mercurio02->setDireccion($this->getPostParam("direccion","striptags","extraspaces"));
                $mercurio02->setTelefono($this->getPostParam("telefono","striptags","extraspaces"));
                $mercurio02->setCodciu($this->getPostParam("codciu","striptags","extraspaces"));
                if(!$mercurio02->save()){
                    parent::setLogger($mercurio02->getMessages());
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
        $_fields["codcaj"] = array('header'=>"Caja",'size'=>"15",'align'=>"C");
        $_fields["nit"] = array('header'=>"Nit",'size'=>"20",'align'=>"C");
        $_fields["razsoc"] = array('header'=>"Razon Social",'size'=>"55",'align'=>"L");
        $_fields["email"] = array('header'=>"Email",'size'=>"40",'align'=>"L");
        $_fields["direccion"] = array('header'=>"Direccion",'size'=>"40",'align'=>"C");
        $_fields["telefono"] = array('header'=>"Telefono",'size'=>"20",'align'=>"C");
        $_fields["codciu"] = array('header'=>"Ciudad",'size'=>"20",'align'=>"C");
        parent::createReport("mercurio02",$_fields,$condi,$this->title,$format);
    }

}
?>
