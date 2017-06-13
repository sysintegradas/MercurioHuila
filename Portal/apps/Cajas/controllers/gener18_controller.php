<?php

class Gener18Controller extends ApplicationController {

    private $estado = 100;
    private $title = "Tipos de Documentos";

    public function initialize(){
        $this->setTemplateAfter('escritorio');
        $this->setPersistance(true);
    }

    public function indexAction(){
		$this->setTemplateAfter(array("escritorio","reporte"));
        $gener18 = $this->Gener18->find();
        $this->setParamToView("gener18",$gener18);
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
                $modelos = array("gener18");
                $Transaccion = parent::startTrans($modelos);
                $coddoc = is_array($this->getPostParam("coddoc")) ? $this->getPostParam("coddoc","striptags","extraspaces") : array();
                $detdoc = is_array($this->getPostParam("detdoc")) ? $this->getPostParam("detdoc","striptags","extraspaces") : array();
                foreach ($this->Gener18->find() as $gener18){
                    if(!in_array($gener18->getCoddoc(),$coddoc, true)){
                        $gener18->setTransaction($Transaccion);
                        $gener18->deleteAll("gener18.coddoc = '{$gener18->getCoddoc()}'");
                    }
                }
                for($i=0;$i<count($coddoc);$i++){
                    $gener18 = new Gener18();
                    $gener18->setTransaction($Transaccion);
                    if($coddoc[$i]!=''){
                        $gener18->setCoddoc($coddoc[$i]);
                    }
                    if($detdoc[$i]!=''){
                        $gener18->setDetdoc($detdoc[$i]);
                    }
                    if(!$gener18->save()){
                        parent::setLogger($gener18->getMessages());
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
        $_fields = array();
        $condi = "1=1";
        $_fields["coddoc"] = array('header'=>"Codigo",'size'=>"40",'align'=>"C");
        $_fields["detdoc"] = array('header'=>"Detalle",'size'=>"70",'align'=>"C");
        parent::createReport("gener18",$_fields,$condi,$this->title,$format);
    }

}
?>
