<?php

class Gener21Controller extends ApplicationController {

    private $estado = 100;
    private $title = "Funcionarios del Sistema";

    public function initialize(){
        $this->setTemplateAfter('escritorio');
        $this->setPersistance(true);
    }

    public function indexAction(){
		$this->setTemplateAfter(array("escritorio","reporte"));
        $gener21 = $this->Gener21->find();
        $this->setParamToView("gener21",$gener21);
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
                $modelos = array("gener21");
                $Transaccion = parent::startTrans($modelos);
                $tipfun = is_array($this->getPostParam("tipfun")) ? $this->getPostParam("tipfun","striptags","extraspaces") : array();
                $detalle = is_array($this->getPostParam("detalle")) ? $this->getPostParam("detalle","striptags","extraspaces") : array();
                foreach ($this->Gener21->find() as $gener21){
                    if(!in_array($gener21->getTipfun(),$tipfun, true)){
                        $gener21->setTransaction($Transaccion);
                        $gener21->deleteAll("gener21.tipfun = '{$gener21->getTipfun()}'");
                    }
                }
                for($i=0;$i<count($tipfun);$i++){
                    $gener21 = new Gener21();
                    $gener21->setTransaction($Transaccion);
                    if($tipfun[$i]!=''){
                        $gener21->setTipfun($tipfun[$i]);
                    }
                    if($detalle[$i]!=''){
                        $gener21->setDetalle($detalle[$i]);
                    }
                    if(!$gener21->save()){
                        parent::setLogger($gener21->getMessages());
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
        $_fields["tipfun"] = array('header'=>"Tipo",'size'=>"40",'align'=>"C");
        $_fields["detalle"] = array('header'=>"Detalle",'size'=>"70",'align'=>"C");
        parent::createReport("gener21",$_fields,$condi,$this->title,$format);
    }

}
?>
