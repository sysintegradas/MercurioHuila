<?php

class ConsultasController extends ApplicationController {

    private $estado = 100;
    private $title = "Informe de Solicitudes ";
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
            $documento = $this->getPostParam("documento","striptags");
            $solicitud = $this->getPostParam("solicitud","striptags");
            $tipo = $this->getPostParam("tipo","striptags");
            $fecini = $this->getPostParam("fecini","striptags");
            $fecfin = $this->getPostParam("fecfin","striptags");

            $documento = $this->filter("documento: $documento","qchar");
            $tipo = $this->filter("tipo: $tipo","qchar");
            $condiciones = array();
            if(!empty($documento))$condiciones[] = $documento;
            if(!empty($tipo))$condiciones[] = $tipo;
            if(!empty($fecini) AND !empty($fecfin))$condiciones[] = "fecha between '$fecini' and '$fecfin'";
            $this->query = count($condiciones)!=0 ? join(" AND ", $condiciones) : " 1=1 ";
            $this->setParamToView("botones",true);
            if($solicitud == 'addempresa'){
                $gener = Tag::paginate($this->Mercurio30->find($this->query,"order: id asc"),1,10);
            }else if($solicitud == 'addtrabajador'){
                $gener = Tag::paginate($this->Mercurio31->find($this->query,"order: id asc"),1,10);
            }else if($solicitud == 'ingcon'){
                $gener = Tag::paginate($this->Mercurio32->find($this->query,"order: id asc"),1,10);
            }else if($solicitud == 'ingben'){
                $gener = Tag::paginate($this->Mercurio34->find($this->query,"order: id asc"),1,10);
            }else if($solicitud == 'actdatE'){
                $gener = Tag::paginate($this->Mercurio33->find($this->query."AND Tipo='E'","order: id asc"),1,10);
            }else if($solicitud == 'actdatT'){
                $gener = Tag::paginate($this->Mercurio33->find($this->query."AND Tipo='T'","order: id asc"),1,10);
            }else if($solicitud == 'novret'){
                $gener = Tag::paginate($this->Mercurio35->find($this->query,"order: id asc"),1,10);
            }else if($solicitud == 'actpri'){
                $gener = Tag::paginate($this->Mercurio43->find($this->query,"order: id asc"),1,10);
            }
            $this->setParamToView('gener',$gener);
            $this->setParamToView("html",parent::navegacion($gener,Router::getController()));
            $this->setParamToView("solicitud",$solicitud);
        } else {
            $solicitud = $this->getPostParam("codare","striptags");
            $this->setResponse('view');
            $this->setParamToView("botones",false);
            if($solicitud == 'addempresa'){
                $gener = parent::paginate("mercurio30",$this->query,"id",$page,$pagina);
            }else if($solicitud == 'addtrabajador'){
                $gener = parent::paginate("mercurio31",$this->query,"id",$page,$pagina);
            }else if($solicitud == 'ingcon'){
                $gener = parent::paginate("mercurio32",$this->query,"id",$page,$pagina);
            }else if($solicitud == 'ingben'){
                $gener = parent::paginate("mercurio34",$this->query,"id",$page,$pagina);
            }else if($solicitud == 'actdatE'){
                $gener = parent::paginate("mercurio33",$this->query,"id",$page,$pagina);
            }else if($solicitud == 'actdatT'){
                $gener = parent::paginate("mercurio33",$this->query,"id",$page,$pagina);
            }else if($solicitud == 'novret'){
                $gener = parent::paginate("mercurio35",$this->query,"id",$page,$pagina);
            }else if($solicitud == 'actpri'){
                $gener = parent::paginate("mercurio43",$this->query,"id",$page,$pagina);
            }
            $this->setParamToView('gener',$gener);
            $this->setParamToView("html",parent::navegacion($gener,Router::getController()));
            $this->setParamToView("solicitud",$solicitud);
        }
    }

    public function editarAction($documento){
        $this->setParamToView("titulo",$this->title);
        $mercurio21 = $this->Mercurio21->findFirst("documento = '$documento'");
        if($mercurio21==false){
            $this->estado = 4;
            return $this->redirect(parent::getControllerName()."/index");
        }
        Tag::displayTo("documento",$mercurio21->getDocumento());
        Tag::displayTo("email",$mercurio21->getEmail());
        Tag::displayTo("tipo",$mercurio21->getTipo());
        Tag::displayTo("agencia",$mercurio21->getAgencia());
        Tag::displayTo("clave",$mercurio21->getClave());
    }

    public function guardarAction($documento=''){
        try{
            try {
                $modelos = array("mercurio21");
                $Transaccion = parent::startTrans($modelos);
                $today = new Date();
                if(!empty($documento)){
		            $mercurio21 = $this->Mercurio21->findFirst("mercurio21.documento='$documento'");
		            $clave = $this->getPostParam("clave","striptags","extraspaces");
		            if ($mercurio21->getClave() != $clave){
                        $mclave = parent::encriptar($clave);
			            $mercurio21->setClave($mclave);
                    }
                    $mercurio21->setFeccla($today->getUsingFormatDefault());
                }else{
		            $mercurio21 = new Mercurio21();
		            $mercurio21->setDocumento($this->Mercurio21->maximum("mercurio21.documento")+1);
		            $clave = $this->getPostParam("clave","striptags","extraspaces");
                    $mclave = parent::encriptar($clave);
                    $mercurio21->setClave($mclave);
                    $mercurio21->setFeccla($today->getUsingFormatDefault());
                }
                $mercurio21->setTransaction($Transaccion);
                $mercurio21->setAgencia($this->getPostParam("agencia","striptags","extraspaces"));
                $mercurio21->setEmail($this->getPostParam("email","striptags","extraspaces"));
                if(!$mercurio21->save()){
                    parent::setLogger($mercurio21->getMessages());
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
        $_fields["documento"] = array('header'=>"Documento",'size'=>"15",'align'=>"C");
        $_fields["nombre"] = array('header'=>"Nombre",'size'=>"60",'align'=>"C");
        $_fields["fecha"] = array('header'=>"Fecha",'size'=>"60",'align'=>"C");
        $_fields["hora"] = array('header'=>"Hora",'size'=>"60",'align'=>"C");
        $_fields["accion"] = array('header'=>"Accion Realizada",'size'=>"60",'align'=>"C");
        //$_fields["estado"] = array('header'=>"Estado",'size'=>"60",'align'=>"C");
        if($format=='pdf')
            parent::createReport("mercurio21",$_fields,$condi,$this->title,$format);
        else
            parent::report_xls("mercurio21",$_fields,$condi,$this->title,$format);
    }

}
?>
