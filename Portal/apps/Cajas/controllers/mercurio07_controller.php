<?php

class Mercurio07Controller extends ApplicationController {

    private $estado = 100;
    private $title = "Afiliados a Servicio en Linea";
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
            $tipo = $this->getPostParam("tipo","striptags");
            $fecini = $this->getPostParam("fecini","striptags");
            $fecfin = $this->getPostParam("fecfin","striptags");

            $documento = $this->filter("documento: $documento","qchar");
            $tipo = $this->filter("tipo: $tipo","qchar");
            $condiciones = array();
            if(!empty($documento))$condiciones[] = $documento;
            if(!empty($tipo))$condiciones[] = $tipo;
            if(!empty($fecini) AND !empty($fecfin))$condiciones[] = "fecreg between '$fecini' and '$fecfin'";
            $this->query = count($condiciones)!=0 ? join(" AND ", $condiciones) : " 1=1 ";
            $this->setParamToView("botones",true);
            $gener = Tag::paginate($this->Mercurio07->find($this->query,"order: documento"),1,10);
            $this->setParamToView('gener',$gener);
            $this->setParamToView("html",parent::navegacion($gener,Router::getController()));
        } else {
            $this->setResponse('view');
            $this->setParamToView("botones",false);
            $gener = parent::paginate("mercurio07",$this->query,"documento",$page,$pagina);
            $this->setParamToView('gener',$gener);
            $this->setParamToView("html",parent::navegacion($gener,Router::getController()));
        }
    }

    public function borrarAction($documento=''){
        try{
            try {
                $modelos = array("mercurio07");
                $Transaccion = parent::startTrans($modelos);
                $this->Mercurio07->deleteAll("mercurio07.documento='$documento'");
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
        Tag::displayTo("documento",$this->Mercurio07->maximum("mercurio07.documento")+1);
    }

    public function validaPkAction(){
        $this->setResponse("ajax");
        $documento = $this->getPostParam("documento");
        $response = true;
        $l = $this->Mercurio07->count("*","conditions: documento = '$documento'");
        if($l>0)$response=false;
        $this->renderText(json_encode($response));
    }

    public function editarAction($documento){
        $this->setParamToView("titulo",$this->title);
        $mercurio07 = $this->Mercurio07->findFirst("documento = '$documento'");
        $mercurio19 = $this->Mercurio19->findFirst("documento = '$documento'");
        $mercurio18 = $this->Mercurio18->findFirst("codigo = {$mercurio19->getCodigo()}");
        $mercurio19b = $this->Mercurio19->findFirst("documento = '$documento' AND respuesta<>'{$mercurio19->getRespuesta()}'");
        if($mercurio19b == FALSE){
            $mercurio19b = $this->Mercurio19->findFirst("documento = '$documento'");
        }
        $mercurio18b = $this->Mercurio18->findFirst("codigo = {$mercurio19b->getCodigo()}");
        if($mercurio07==false){
            $this->estado = 4;
            return $this->redirect(parent::getControllerName()."/index");
        }
        Tag::displayTo("documento",$mercurio07->getDocumento());
        Tag::displayTo("email",$mercurio07->getEmail());
        Tag::displayTo("tipo",$mercurio07->getTipo());
        Tag::displayTo("agencia",$mercurio07->getAgencia());
        Tag::displayTo("clave",$mercurio07->getClave());
        Tag::displayTo("resp1",$mercurio19->getRespuesta());
        Tag::displayTo("preg1",$mercurio18->getCodigo());
        Tag::displayTo("resp2",$mercurio19b->getRespuesta());
        Tag::displayTo("preg2",$mercurio18b->getCodigo());
    }

    public function guardarAction($documento=''){
        try{
            try {
                $modelos = array("mercurio07");
                $Transaccion = parent::startTrans($modelos);
                $today = new Date();
                if(!empty($documento)){
		            $mercurio07 = $this->Mercurio07->findFirst("mercurio07.documento='$documento'");
		            $clave = $this->getPostParam("clave","striptags","extraspaces");
		            if ($mercurio07->getClave() != $clave){
                        $mclave = parent::encriptar($clave);
			            //$mercurio07->setClave($mclave);
                    }
                    //$mercurio07->setFeccla($today->getUsingFormatDefault());
                }else{
		            $mercurio07 = new Mercurio07();
		            $mercurio07->setDocumento($this->Mercurio07->maximum("mercurio07.documento")+1);
		            $clave = $this->getPostParam("clave","striptags","extraspaces");
                    $mclave = parent::encriptar($clave);
                    $mercurio07->setClave($mclave);
                    //$mercurio07->setFeccla($today->getUsingFormatDefault());
                }
                $mercurio07->setTransaction($Transaccion);
                $mercurio07->setEmail($this->getPostParam("email","striptags","extraspaces"));
                if(!$mercurio07->save()){
                    parent::setLogger($mercurio07->getMessages());
                    parent::ErrorTrans();
                }
                $email = $this->getPostParam("email","striptags","extraspaces");
                $nombre = $mercurio07->getNombre();
                $file = '';
                //$msg = "Cordial Saludos<br><br>Adjunto la carta de aceptacion como nuevo afiliado a la {$mercurio02->getRazsoc()} <br><br>Bienvenidos a la familia COMFAMILIAR HUILA, mas familias felices.<br><br>Atentamente,<br><br><br>Director Administrativo ";
                $msg = "Gracias por utilizar el Servicio en Línea de Comfamiliar Huila, una vez revisada la
                        información suministrada porusted, me permito indicar que usted acaba de
                        realizar el proceso de Actualización de Corre, el correo por el cual modifico es: $email";
                $asunto = "Actualizacion de correo";
                parent::enviarCorreo("Actualizacion de Correo",$nombre,$email,$asunto,$msg,$file);
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
        $_fields["email"] = array('header'=>"Email",'size'=>"50",'align'=>"C");
        $_fields["agencia"] = array('header'=>"Agencia",'size'=>"30",'align'=>"C");
        $_fields["tipo"] = array('header'=>"Tipo de Afiliado",'size'=>"20",'align'=>"C");
        $_fields["autoriza"] = array('header'=>"Autoriza",'size'=>"15",'align'=>"C");
        $_fields["feccla"] = array('header'=>"Fecha de Registro",'size'=>"15",'align'=>"C");
        if($format=='pdf')
            parent::createReport("mercurio07",$_fields,$condi,$this->title,$format);
        else
            self::report_xls("mercurio07",$_fields,$condi,$this->title,$format);
    }

    public function report_xls($model,$_fields,$query='1=1',$title='Reporte',$format='pdf'){
        $fecha = new Date();
        $this->setResponse('view');
        $file = "public/temp/log".$fecha->getUsingFormatDefault().".xls";
        require_once "Library/Excel/Main.php";
        $excels = new Spreadsheet_Excel_Writer($file);
        $excel = $excels->addWorksheet();
        $column_title = $excels->addFormat(array('fontfamily' => 'Verdana',
                    'size' => 12,
                    'fgcolor' => 50,
                    'border' => 1,
                    'bordercolor' => 'black',
                    "halign" => 'center'
                    ));
        $title = $excels->addFormat(array(   'fontfamily' => 'Verdana',
                    'size' => 13,
                    'border' => 0,
                    'bordercolor' => 'black',
                    "halign" => 'center'
                    ));
        $column_style = $excels->addFormat(array(   'fontfamily' => 'Verdana',
                    'size' => 11,
                    'border' => 1,
                    'bordercolor' => 'black',
                    ));
        $excel->setMerge(0,0,0,6);

        $excel->write(0,0,'Movimiento de Afiliados',$title);
        $columns = array('#','Documento','Nombre','Email','Agencia','Tipo','Autoriza','Fecha de registro');
        $excel->setColumn(0,0,10);
        $excel->setColumn(1,1,20);
        $excel->setColumn(2,2,50);
        $excel->setColumn(3,3,50);
        $excel->setColumn(4,4,30);
        $excel->setColumn(5,5,30);
        $excel->setColumn(6,6,10);
        $i = 0;
        $j = 2;
        foreach($columns as $column){
            $value = ucfirst($column);
            $excel->write($j, $i++, $value, $column_title);
        }
        $j++;
        $mercurio07 = $this->Mercurio07->find("$query","order: documento");
        if($mercurio07!=false){
            $total=1;
            foreach($mercurio07 as $mmercurio07){
                    $i = 0;
                    $excel->write($j, $i++,$total++, $column_style);
                    $excel->write($j, $i++,$mmercurio07->getDocumento(), $column_style);
                    $excel->write($j, $i++,$mmercurio07->getNombre(), $column_style);
                    $excel->write($j, $i++,$mmercurio07->getEmail(), $column_style);
                    $excel->write($j, $i++,$mmercurio07->getAgenciaDetalle(), $column_style);
                    $excel->write($j, $i++,$mmercurio07->getTipoDetalle(), $column_style);
                    $excel->write($j, $i++,$mmercurio07->getAutoriza(), $column_style);
                    $excel->write($j, $i++,$mmercurio07->getFeccla(), $column_style);
                    $j++;
            }
        }
        $fecha = new Date();
        $excels->close();
        header("location: ".Core::getInstancePath()."/{$file}");

    }
}
?>
