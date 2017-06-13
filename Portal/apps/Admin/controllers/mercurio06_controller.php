<?php

class Mercurio06Controller extends ApplicationController {

    private $estado = 100;
    private $title = "Tipos Afiliados";

    public function initialize(){
        $this->setTemplateAfter('escritorio');
        $this->setPersistance(true);
    }

    public function indexAction(){
		$this->setTemplateAfter(array("escritorio","reporte"));
        $mercurio06 = $this->Mercurio06->find();
        $this->setParamToView("mercurio06",$mercurio06);
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
                $modelos = array("mercurio06");
                $Transaccion = parent::startTrans($modelos);
                $tipo = is_array($this->getPostParam("tipo")) ? $this->getPostParam("tipo","striptags","extraspaces") : array();
                $detalle = is_array($this->getPostParam("detalle")) ? $this->getPostParam("detalle","striptags","extraspaces") : array();
                foreach ($this->Mercurio06->find() as $mercurio06){
                    if(!in_array($mercurio06->getTipo(),$tipo, true)){
                        $mercurio06->setTransaction($Transaccion);
                        $mercurio06->deleteAll("tipo = '{$mercurio06->getTipo()}'");
                    }
                }
                for($i=0;$i<count($tipo);$i++){
                    $mercurio06 = new Mercurio06();
                    $mercurio06->setTransaction($Transaccion);
                    if($tipo[$i]!=''){
                        $mercurio06->setTipo($tipo[$i]);
                    }
                    if($detalle[$i]!=''){
                        $mercurio06->setDetalle($detalle[$i]);
                    }
                    if(!$mercurio06->save()){
                        parent::setLogger($mercurio06->getMessages());
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
        $_fields["tipo"] = array('header'=>"Tipo",'size'=>"40",'align'=>"C");
        $_fields["detalle"] = array('header'=>"Detalle",'size'=>"70",'align'=>"C");
        parent::createReport("mercurio06",$_fields,$condi,$this->title,$format);
    }

}
?>
