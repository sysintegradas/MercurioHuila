<?php

class Gener02Controller extends ApplicationController {

    private $estado = 100;
    private $title = "Usuario del Sistema";
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
            $usuario = $this->getPostParam("usuario","striptags");
            $cedtra = $this->getPostParam("cedtra","striptags");
            $nombre = $this->getPostParam("nombre","striptags");
            $tipfun = $this->getPostParam("tipfun","striptags");
            $estado = $this->getPostParam("estado","striptags");
            $login = $this->getPostParam("login","striptags");

            $usuario = $this->filter("usuario: $usuario","qchar");
            $cedtra = $this->filter("cedtra: $cedtra","qchar");
            $nombre = $this->filter("nombre: $nombre","qchar");
            $tipfun = $this->filter("tipfun: $tipfun","qchar");
            $estado = $this->filter("estado: $estado","qchar");
            $login = $this->filter("login: $login","qchar");
            $condiciones = array();
            if(!empty($usuario))$condiciones[] = $usuario;
            if(!empty($cedtra))$condiciones[] = $cedtra;
            if(!empty($nombre))$condiciones[] = $nombre;
            if(!empty($tipfun))$condiciones[] = $tipfun;
            if(!empty($estado))$condiciones[] = $estado;
            if(!empty($login))$condiciones[] = $login;
            $this->query = count($condiciones)!=0 ? join(" AND ", $condiciones) : " 1=1 ";
            $this->setParamToView("botones",true);
            $gener = Tag::paginate($this->Gener02->find($this->query,"order: usuario"),1,10);
            $this->setParamToView('gener',$gener);
            $this->setParamToView("html",parent::navegacion($gener,Router::getController()));
        } else {
            $this->setResponse('view');
            $this->setParamToView("botones",false);
            $gener = parent::paginate("gener02",$this->query,"usuario",$page,$pagina);
            $this->setParamToView('gener',$gener);
            $this->setParamToView("html",parent::navegacion($gener,Router::getController()));
        }
    }

    public function borrarAction($usuario=''){
        try{
            try {
                $modelos = array("gener02");
                $Transaccion = parent::startTrans($modelos);
                $this->Gener02->deleteAll("gener02.usuario='$usuario'");
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
	    Tag::displayTo("estado","A");
        Tag::displayTo("usuario",$this->Gener02->maximum("gener02.usuario")+1);
    }

    public function validaPkAction(){
        $this->setResponse("ajax");
        $usuario = $this->getPostParam("usuario");
        $response = true;
        $l = $this->Gener02->count("*","conditions: usuario = '$usuario'");
        if($l>0)$response=false;
        $this->renderText(json_encode($response));
    }

    public function editarAction($usuario){
        $this->setParamToView("titulo",$this->title);
        $gener02 = $this->Gener02->findFirst("usuario = '$usuario'");
        if($gener02==false){
            $this->estado = 4;
            return $this->redirect(parent::getControllerName()."/index");
        }
        Tag::displayTo("usuario",$gener02->getUsuario());
        Tag::displayTo("cedtra",$gener02->getCedtra());
        Tag::displayTo("nombre",$gener02->getNombre());
        Tag::displayTo("tipfun",$gener02->getTipfun());
        Tag::displayTo("clave",$gener02->getClave());
        Tag::displayTo("estado",$gener02->getEstado());
        Tag::displayTo("login",$gener02->getLogin());
    }

    public function guardarAction($usuario=''){
        try{
            try {
                $modelos = array("gener02");
                $Transaccion = parent::startTrans($modelos);
                $today = new Date();
                if(!empty($usuario)){
		            $gener02 = $this->Gener02->findFirst("gener02.usuario='$usuario'");
		            $clave = $this->getPostParam("clave","striptags","extraspaces");
		            if ($gener02->getClave() != $clave){
                        $mclave = parent::encriptar($clave);
			            $gener02->setClave($mclave);
                    }
                    $gener02->setFeccla($today->getUsingFormatDefault());
                }else{
		            $gener02 = new Gener02();
		            $gener02->setUsuario($this->Gener02->maximum("gener02.usuario")+1);
		            $clave = $this->getPostParam("clave","striptags","extraspaces");
                    $mclave = parent::encriptar($clave);
                    $gener02->setClave($mclave);
                    $gener02->setFeccla($today->getUsingFormatDefault());
                }
                $gener02->setTransaction($Transaccion);
                $gener02->setCedtra($this->getPostParam("cedtra","striptags","extraspaces"));
                $gener02->setNombre($this->getPostParam("nombre","striptags","extraspaces"));
                $gener02->setTipfun($this->getPostParam("tipfun","striptags","extraspaces"));
                $gener02->setEstado($this->getPostParam("estado","striptags","extraspaces"));
                $gener02->setLogin($this->getPostParam("login","striptags","extraspaces"));
                if(!$gener02->save()){
                    parent::setLogger($gener02->getMessages());
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
        $_fields["usuario"] = array('header'=>"Usuario",'size'=>"15",'align'=>"C");
        $_fields["cedtra"] = array('header'=>"Cedula",'size'=>"20",'align'=>"C");
        $_fields["nombre"] = array('header'=>"Nombre",'size'=>"55",'align'=>"L");
        $_fields["login"] = array('header'=>"Login",'size'=>"55",'align'=>"L");
        $_fields["tipfun"] = array('header'=>"Funcionario",'size'=>"40",'align'=>"L");
        $_fields["estado"] = array('header'=>"Estado",'size'=>"20",'align'=>"C");
        parent::createReport("gener02",$_fields,$condi,$this->title,$format);
    }

}
?>
