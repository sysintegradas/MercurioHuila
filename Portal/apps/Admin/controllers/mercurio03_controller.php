<?php

class Mercurio03Controller extends ApplicationController {

    private $estado = 100;
    private $title = "Marcas Firmas";

    public function initialize(){
        $this->setTemplateAfter('escritorio');
        $this->setPersistance(true);
    }

    public function indexAction(){
		$this->setTemplateAfter(array("escritorio","reporte"));
        $mercurio03 = $this->Mercurio03->find();
        $this->setParamToView("mercurio03",$mercurio03);
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
                $modelos = array("mercurio03");
                $Transaccion = parent::startTrans($modelos);
                $codfir = is_array($this->getPostParam("codfir")) ? $this->getPostParam("codfir","striptags","extraspaces") : array();
                $detalle = is_array($this->getPostParam("detalle")) ? $this->getPostParam("detalle","striptags","extraspaces") : array();
                foreach ($this->Mercurio03->find() as $mercurio03){
                    if(!in_array($mercurio03->getCodfir(),$codfir, true)){
                        $mercurio03->setTransaction($Transaccion);
                        $mercurio03->deleteAll("codfir = '{$mercurio03->getCodfir()}'");
                    }
                }
                for($i=0;$i<count($codfir);$i++){
                    $mercurio03 = new Mercurio03();
                    $mercurio03->setTransaction($Transaccion);
                    if($codfir[$i]!=''){
                        $mercurio03->setCodfir($codfir[$i]);
                    }
                    if($detalle[$i]!=''){
                        $mercurio03->setDetalle($detalle[$i]);
                    }
                    if(!$mercurio03->save()){
                        parent::setLogger($mercurio03->getMessages());
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
        $_fields["codfir"] = array('header'=>"Firma",'size'=>"40",'align'=>"C");
        $_fields["detalle"] = array('header'=>"Detalle",'size'=>"70",'align'=>"C");
        parent::createReport("mercurio03",$_fields,$condi,$this->title,$format);
    }

}
?>
