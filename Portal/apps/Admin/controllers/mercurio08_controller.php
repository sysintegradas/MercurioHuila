<?php

class Mercurio08Controller extends ApplicationController {

    private $estado = 100;
    private $title = "Areas/Modulos";

    public function initialize(){
        $this->setTemplateAfter('escritorio');
        $this->setPersistance(true);
    }

    public function indexAction(){
		$this->setTemplateAfter(array("escritorio","reporte"));
        $mercurio08 = $this->Mercurio08->find();
        $this->setParamToView("mercurio08",$mercurio08);
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
                $modelos = array("mercurio08");
                $Transaccion = parent::startTrans($modelos);
                $codare = is_array($this->getPostParam("codare")) ? $this->getPostParam("codare","striptags","extraspaces") : array();
                $detalle = is_array($this->getPostParam("detalle")) ? $this->getPostParam("detalle","striptags","extraspaces") : array();
                foreach ($this->Mercurio08->find() as $mercurio08){
                    if(!in_array($mercurio08->getCodare(),$codare, true)){
                        $mercurio08->setTransaction($Transaccion);
                        $mercurio08->deleteAll("codare = '{$mercurio08->getCodare()}'");
                    }
                }
                for($i=0;$i<count($codare);$i++){
                    $mercurio08 = new Mercurio08();
                    $mercurio08->setTransaction($Transaccion);
                    if($codare[$i]!=''){
                        $mercurio08->setCodare($codare[$i]);
                    }
                    if($detalle[$i]!=''){
                        $mercurio08->setDetalle($detalle[$i]);
                    }
                    if(!$mercurio08->save()){
                        parent::setLogger($mercurio08->getMessages());
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
        $_fields["codare"] = array('header'=>"Area",'size'=>"40",'align'=>"C");
        $_fields["detalle"] = array('header'=>"Detalle",'size'=>"70",'align'=>"C");
        parent::createReport("mercurio08",$_fields,$condi,$this->title,$format);
    }

}
?>
