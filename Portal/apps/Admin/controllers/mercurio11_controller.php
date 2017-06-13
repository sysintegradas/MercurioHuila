<?php

class Mercurio11Controller extends ApplicationController {

    private $estado = 100;
    private $title = "Operaciones";
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
            $codare = $this->getPostParam("codare","striptags");
            $codope = $this->getPostParam("codope","striptags");
            $detalle = $this->getPostParam("detalle","striptags");

            $codare = $this->filter("codare: $codare","qchar");
            $codope = $this->filter("codope: $codope","qchar");
            $detalle = $this->filter("detalle: $detalle","qchar");
            $condiciones = array();
            if(!empty($codare))$condiciones[] = $codare;
            if(!empty($codope))$condiciones[] = $codope;
            if(!empty($detalle))$condiciones[] = $detalle;
            $this->query = count($condiciones)!=0 ? join(" AND ", $condiciones) : " 1=1 ";
            $this->setParamToView("botones",true);
            $mercurio = Tag::paginate($this->Mercurio11->find($this->query,"order: codare"),1,10);
            $this->setParamToView('mercurio',$mercurio);
            $this->setParamToView("html",parent::navegacion($mercurio,Router::getController()));
        } else {
            $this->setResponse('view');
            $this->setParamToView("botones",false);
            $mercurio = parent::paginate("mercurio11",$this->query,"codare",$page,$pagina);
            $this->setParamToView('mercurio',$mercurio);
            $this->setParamToView("html",parent::navegacion($mercurio,Router::getController()));
        }
    }

    public function borrarAction($codare='',$codope=''){
        try{
            try {
                $modelos = array("mercurio11");
                $Transaccion = parent::startTrans($modelos);
                $this->Mercurio11->deleteAll("codare='$codare' AND codope='$codope'");
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
        $codare = $this->getPostParam("codare");
        $codope = $this->getPostParam("codope");
        $response = true;
        $l = $this->Mercurio11->count("*","conditions: codare = '$codare' AND codope='$codope'");
        if($l>0)$response=false;
        $this->renderText(json_encode($response));
    }

    public function editarAction($codare='',$codope=''){
        $this->setParamToView("titulo",$this->title);
        $mercurio11 = $this->Mercurio11->findFirst("codare = '$codare' AND codope='$codope'");
        if($mercurio11==false){
            $this->estado = 4;
            return $this->redirect(parent::getControllerName()."/index");
        }
        Tag::displayTo("codare",$mercurio11->getCodare());
        Tag::displayTo("codope",$mercurio11->getCodope());
        Tag::displayTo("detalle",$mercurio11->getDetalle());
        Tag::displayTo("tipo",$mercurio11->getTipo());
        Tag::displayTo("mandoc",$mercurio11->getMandoc());
        Tag::displayTo("webser",$mercurio11->getWebser());
        Tag::displayTo("nota",$mercurio11->getNota());
    }

    public function guardarAction($codare='',$codope=''){
        try{
            try {
                $modelos = array("mercurio11");
                $Transaccion = parent::startTrans($modelos);
                $today = new Date();
                if(!empty($codare) && !empty($codope)){
		            $mercurio11 = $this->Mercurio11->findFirst("mercurio11.codare='$codare' AND codope = '$codope'");
                }else{
		            $mercurio11 = new Mercurio11();
                    $mercurio11->setCodare($this->getPostParam("codare","striptags","extraspaces"));
                    $mercurio11->setCodope($this->getPostParam("codope","striptags","extraspaces"));
                }
                $mercurio11->setTransaction($Transaccion);
                $mercurio11->setDetalle($this->getPostParam("detalle","striptags","extraspaces"));
                $mercurio11->setTipo($this->getPostParam("tipo","striptags","extraspaces"));
                $mercurio11->setMandoc($this->getPostParam("mandoc","striptags","extraspaces"));
                $mercurio11->setWebser($this->getPostParam("webser","striptags","extraspaces"));
                $mercurio11->setNota($this->getPostParam("nota","striptags","extraspaces"));
                if(!$mercurio11->save()){
                    parent::setLogger($mercurio11->getMessages());
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
        $_fields["codare"] = array('header'=>"Area",'size'=>"20",'align'=>"C");
        $_fields["codope"] = array('header'=>"Operacion",'size'=>"20",'align'=>"C");
        $_fields["detalle"] = array('header'=>"Detalle",'size'=>"40",'align'=>"L");
        $_fields["nota"] = array('header'=>"Nota",'size'=>"40",'align'=>"L");
        parent::createReport("mercurio11",$_fields,$condi,$this->title,$format);
    }

}
?>
