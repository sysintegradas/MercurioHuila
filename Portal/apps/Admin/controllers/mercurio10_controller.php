<?php

class Mercurio10Controller extends ApplicationController {

    private $estado = 100;
    private $title = "Operaciones por Caja de Compensacion";
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
            default:
        }
        $this->estado = 100;
    }

    public function buscarAction($page="",$pagina=""){
        if($page==""){
            $this->setTemplateAfter(array("escritorio","reporte"));
            $codcaj = $this->getPostParam("codcaj","striptags");
            $codcaj = $this->filter("codcaj: $codcaj","qchar");
            $condiciones = array();
            if(isset($codcaj)&& !empty($codcaj))$condiciones[] = $codcaj;
            $this->query = count($condiciones)!=0 ? join(" AND ", $condiciones) : " 1=1 ";
            $this->setParamToView("botones",true);
            $mercurio = Tag::paginate($this->Mercurio02->find($this->query,"order: codcaj"),1,10);
            $this->setParamToView('mercurio',$mercurio);
            $this->setParamToView("html",parent::navegacion($mercurio,Router::getController()));
        } else {
            $this->setResponse('view');
            $this->setParamToView("botones",false);
            $coleg = parent::paginate("mercurio10",$this->query,"codcaj",$page,$pagina);
            $this->setParamToView('mercurio',$mercurio);
            $this->setParamToView("html",parent::navegacion($mercurio,Router::getController()));
        }
    }

    public function detalleAction($codcaj=''){
        $mercurio10 = $this->Mercurio10->find("codcaj = '$codcaj'");
        $this->setParamToView("mercurio10",$mercurio10);
        $this->setParamToView("codcaj",$codcaj);
        $this->setParamToView("titulo",$this->title);
    }

    protected static function unificarDetalle(&$item,$key,$a) {
        $item = array($item,$a[$key]);
    }

    public function guardarAction($codcaj=''){
        try{
            try {
                $modelos = array("mercurio10");
                $Transaccion = parent::startTrans($modelos);
                $codare = is_array($this->getPostParam("codare")) ? $this->getPostParam("codare","striptags","extraspaces") : array();
                $codope = is_array($this->getPostParam("codope")) ? $this->getPostParam("codope","striptags","extraspaces") : array();
                $tipo = is_array($this->getPostParam("tipo")) ? $this->getPostParam("tipo","striptags","extraspaces") : array();
                $estado = is_array($this->getPostParam("estado")) ? $this->getPostParam("estado","striptags","extraspaces") : array();
                $a1 = $codare;
                $a2 = $codope;
                array_walk($a1,array('mercurio10Controller','unificarDetalle'),$a2);
                foreach ($this->Mercurio10->find("codcaj = '$codcaj'") as $mercurio10){
                    if(!in_array(array($mercurio10->getCodare(),$mercurio10->getCodope()),$a1,true)){
                        $mercurio10->setTransaction($Transaccion);
                        $mercurio10->deleteAll("codcaj='$codcaj' and codare = '{$mercurio10->getCodare()}' AND codope = '{$mercurio10->getCodope()}'");
                    }
                }
                for($i=0;$i<count($codope);$i++){
                    $mercurio10 = new Mercurio10();
                    $mercurio10->setTransaction($Transaccion);
                    $mercurio10->setCodcaj($codcaj);
                    if($codare[$i]!=''){
                        $mercurio10->setCodare($codare[$i]);
                    }
                    if($codope[$i]!=''){
                        $mercurio10->setCodope($codope[$i]);
                    }
                    if($tipo[$i]!=''){
                        $mercurio10->setTipo($tipo[$i]);
                    }
                    if($estado[$i]!=''){
                        $mercurio10->setEstado($estado[$i]);
                    }
                    if(!$mercurio10->save()){
                        parent::setLogger($mercurio10->getMessages());
                        parent::ErrorTrans();
                    }
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
        $params = func_get_args();
        $codcaj = $params[1];
        $condi = "codcaj = '$codcaj'";
        $mercurio02 = $this->Mercurio02->findBySql("SELECT razsoc FROM mercurio02 WHERE $condi ");
        $mercurio02->setRazsoc(parent::doUpper($mercurio02->getRazsoc()));
        $_fields = array();
        $_fields["codare"] = array('header'=>"Area",'size'=>"50",'align'=>"C");
        $_fields["codope"] = array('header'=>"Operacion",'size'=>"40",'align'=>"C");
        $_fields["tipo"] = array('header'=>"Tipo",'size'=>"40",'align'=>"C");
        $_fields["estado"] = array('header'=>"Estado",'size'=>"40",'align'=>"C");
        parent::createReport("mercurio10",$_fields,$condi,array($this->title,"Caja {$mercurio02->getRazsoc()}"),$format);
    }

    public function cargarOperacionesAction(){
        $this->setResponse("ajax");
        $codare = $this->getPostParam("codare");
        $response = Tag::select("codope",$this->Mercurio11->findAllBySql("SELECT codope,detalle FROM mercurio11 WHERE codare='$codare'"),"using: codope,detalle","use_dummy: true");
        $this->renderText(json_encode($response));
    }

}
?>
