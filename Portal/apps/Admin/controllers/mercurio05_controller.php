<?php

class Mercurio05Controller extends ApplicationController {

    private $estado = 100;
    private $title = "Firmas por Caja de Compensacion";
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
            $coleg = parent::paginate("mercurio05",$this->query,"codcaj",$page,$pagina);
            $this->setParamToView('mercurio',$mercurio);
            $this->setParamToView("html",parent::navegacion($mercurio,Router::getController()));
        }
    }

    public function detalleAction($codcaj=''){
        $mercurio05 = $this->Mercurio05->find("codcaj = '$codcaj'");
        $this->setParamToView("mercurio05",$mercurio05);
        $this->setParamToView("codcaj",$codcaj);
        $this->setParamToView("titulo",$this->title);
    }

    public function guardarAction($codcaj=''){
        try{
            try {
                $modelos = array("mercurio05");
                $Transaccion = parent::startTrans($modelos);
                $codfir = is_array($this->getPostParam("codfir")) ? $this->getPostParam("codfir","striptags","extraspaces") : array();
                $cedula = is_array($this->getPostParam("cedula")) ? $this->getPostParam("cedula","striptags","extraspaces") : array();
                $nombre = is_array($this->getPostParam("nombre")) ? $this->getPostParam("nombre","striptags","extraspaces") : array();
                $cargo = is_array($this->getPostParam("cargo")) ? $this->getPostParam("cargo","striptags","extraspaces") : array();
                $email = is_array($this->getPostParam("email")) ? $this->getPostParam("email","striptags","extraspaces") : array();
                foreach ($this->Mercurio05->find("codcaj = '$codcaj'") as $mercurio05){
                    if(!in_array($mercurio05->getCodfir(),$codfir, true)){
                        $mercurio05->setTransaction($Transaccion);
                        $mercurio05->deleteAll("codcaj='$codcaj' AND codfir = '{$mercurio05->getCodfir()}'");
                    }
                }
                for($i=0;$i<count($codfir);$i++){
                    $mercurio05 = new Mercurio05();
                    $mercurio05->setTransaction($Transaccion);
                    $mercurio05->setCodcaj($codcaj);
                    if($codfir[$i]!=''){
                        $mercurio05->setCodfir($codfir[$i]);
                    }
                    if($cedula[$i]!=''){
                        $mercurio05->setCedula($cedula[$i]);
                    }
                    if($nombre[$i]!=''){
                        $mercurio05->setNombre($nombre[$i]);
                    }
                    if($cargo[$i]!=''){
                        $mercurio05->setCargo($cargo[$i]);
                    }
                    if($email[$i]!=''){
                        $mercurio05->setEmail($email[$i]);
                    }
                    if(!$mercurio05->save()){
                        parent::setLogger($mercurio05->getMessages());
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
        $_fields["codfir"] = array('header'=>"Firma",'size'=>"15",'align'=>"C");
        $_fields["cedula"] = array('header'=>"Cedula",'size'=>"20",'align'=>"C");
        $_fields["nombre"] = array('header'=>"Nombre",'size'=>"50",'align'=>"C");
        $_fields["cargo"] = array('header'=>"Cargo",'size'=>"40",'align'=>"C");
        $_fields["email"] = array('header'=>"Email",'size'=>"40",'align'=>"C");
        parent::createReport("mercurio05",$_fields,$condi,array($this->title,"Caja {$mercurio02->getRazsoc()}"),$format);
    }

}
?>
