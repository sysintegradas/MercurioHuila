<?php

class Basica01Controller extends ApplicationController {

    private $estado = 100;
    private $title = "Areas Administrativas";

    public function initialize(){
        $this->setTemplateAfter('escritorio');
        $this->setPersistance(true);
    }

    public function indexAction(){
		$this->setTemplateAfter(array("escritorio","reporte"));
        $basica01 = $this->Basica01->find();
        $this->setParamToView("basica01",$basica01);
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
                $modelos = array("basica01");
                $Transaccion = parent::startTrans($modelos);
                $codare = is_array($this->getPostParam("codare")) ? $this->getPostParam("codare","striptags","extraspaces") : array();
                $detalle = is_array($this->getPostParam("detalle")) ? $this->getPostParam("detalle","striptags","extraspaces") : array();
                foreach ($this->Basica01->find() as $basica01){
                    if(!in_array($basica01->getCodare(),$codare, true)){
                        $basica01->setTransaction($Transaccion);
                        $basica01->deleteAll("codare = '{$basica01->getCodare()}'");
                    }
                }
                for($i=0;$i<count($codare);$i++){
                    $basica01 = new Basica01();
                    $basica01->setTransaction($Transaccion);
                    if($codare[$i]!=''){
                        $basica01->setCodare($codare[$i]);
                    }
                    if($detalle[$i]!=''){
                        $basica01->setDetalle($detalle[$i]);
                    }
                    if(!$basica01->save()){
                        parent::setLogger($basica01->getMessages());
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
        $_fields["codare"] = array('header'=>"Areas",'size'=>"40",'align'=>"C");
        $_fields["detalle"] = array('header'=>"Detalle",'size'=>"70",'align'=>"C");
        parent::createReport("basica01",$_fields,"1=1",$this->title,$format);
    }

}
?>
