<?php

class Mercurio12Controller extends ApplicationController {

    private $estado = 100;
    private $title = "Documentos Operacion";

    public function initialize(){
        $this->setTemplateAfter('escritorio');
        $this->setPersistance(true);
    }

    public function indexAction(){
		$this->setTemplateAfter(array("escritorio","reporte"));
        $mercurio12 = $this->Mercurio12->find();
        $this->setParamToView("mercurio12",$mercurio12);
        $this->setParamToView("titulo",$this->title);
        switch ($this->estado){
            case 0:
                Message::success("Guardado de {$this->title} con Exito");
                break;
            case 1:
                Message::error("Error al Guardar {$this->title}");
                break;
            default:
        }
        $this->estado = 100;
    }

    public function guardarAction(){
        try{
            try {
                $modelos = array("mercurio12");
                $Transaccion = parent::startTrans($modelos);
                $coddoc = is_array($this->getPostParam("coddoc")) ? $this->getPostParam("coddoc","striptags","extraspaces") : array();
                $detalle = is_array($this->getPostParam("detalle")) ? $this->getPostParam("detalle","striptags","extraspaces") : array();
                foreach ($this->Mercurio12->find() as $mercurio12){
                    if(!in_array($mercurio12->getCoddoc(),$coddoc, true)){
                        $mercurio12->setTransaction($Transaccion);
                        $mercurio12->deleteAll("coddoc = '{$mercurio12->getCoddoc()}'");
                    }
                }
                for($i=0;$i<count($coddoc);$i++){
                    $mercurio12 = new Mercurio12();
                    $mercurio12->setTransaction($Transaccion);
                    if($coddoc[$i]!=''){
                        $mercurio12->setCoddoc($coddoc[$i]);
                    }
                    if($detalle[$i]!=''){
                        $mercurio12->setDetalle($detalle[$i]);
                    }
                    if(!$mercurio12->save()){
                        parent::setLogger($mercurio12->getMessages());
                        parent::ErrorTrans();
                    }
                }
                $Transaccion->commit();
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
        $condi = "1=1";
        $_fields = array();
        $_fields["coddoc"] = array('header'=>"Docuemnto",'size'=>"40",'align'=>"C");
        $_fields["detalle"] = array('header'=>"Detalle",'size'=>"70",'align'=>"C");
        parent::createReport("mercurio12",$_fields,$condi,$this->title,$format);
    }

}
?>
