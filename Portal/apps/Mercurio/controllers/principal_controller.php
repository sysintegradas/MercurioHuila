<?php

class PrincipalController extends ApplicationController {
    private $mensaje = "";
    public function initialize(){
        $this->setTemplateAfter('escritorio');
        $this->setPersistance(true);
    }
    public function indexAction($msj=''){
        $mer19 = $this->Mercurio19->count("*","conditions: codcaj = '".Session::getDATA('codcaj')."' AND tipo='".Session::getDATA('tipo')."' AND documento = '".Session::getDATA('documento')."' ");
        if($msj!=""){
            $this->mensaje=$msj;
            return $this->redirect("principal/index");
        }else{
            if($this->mensaje!="") echo Message::info($this->mensaje);
            $this->mensaje="";
        }
        $l = $this->Mercurio07->count("*","conditions: codcaj = '".Session::getDATA('codcaj')."' AND tipo='".Session::getDATA('tipo')."' AND documento = '".Session::getDATA('documento')."' ");
        if(Session::getDATA('tipo')!="P" && $l==0){
            return $this->redirect("login");
        }
        echo parent::showTitle('Bienvenido');
        $response = parent::showMenu();
        $this->setParamToView("menu", $response);
        $param = array("cedtra"=>SESSION::getData('documento'));
        if(SESSION::getData('tipo')=="T"){
            $result = parent::webService("Nucfamtrabajador", $param);
            $tipafi = $result[0]['tipafi'];
            if($result == false){
                $result = parent::webService("Nucfamtrabajadorina", $param);
                $tipafi = $result[0]['tipafi'];
            }
        }
        if(SESSION::getData('tipo')=="E")$tipafi="EMPRESA";
        if(SESSION::getData('tipo')=="P")$tipafi="PARTICULAR";
        $this->setParamToView("tipafi",$tipafi);
        $this->setParamToView("mer19",$mer19);
    }
    public function vigenciaSubsidioViviendaAction(){
        $formu= new FPDF('P','mm','A4');
        $formu->Addpage();
        $formu->setTextColor(0);
        $formu->SetFont('Arial','B','9');
        $this->setResponse('view');
        $formu->Cell(190,5,html_entity_decode("DF-"), 0,1,"L",0,0);
        $formu->SetFont('Arial','','9');
        $formu->Cell(190,4,html_entity_decode(""), 0,1,"L",0,0);
        $formu->Cell(190,5,html_entity_decode("Neiva,(fecha)"), 0,1,"L",0,0);
        $formu->Cell(190,4,html_entity_decode(""), 0,1,"L",0,0);
        $formu->SetFont('Arial','B','9');
        $formu->Cell(190,5,html_entity_decode("Se&ntilde;or(a)"), 0,1,"L",0,0);
        $formu->Cell(190,5,html_entity_decode("NOMBRE DEL POSTULANTE"), 0,1,"L",0,0);
        $formu->Cell(190,5,html_entity_decode("CC"), 0,1,"L",0,0);
        $formu->Cell(190,5,html_entity_decode("DIRECCION"), 0,1,"L",0,0);
        $formu->Cell(190,5,html_entity_decode("TELEFONO"), 0,1,"L",0,0);
        $formu->Cell(190,5,html_entity_decode("DEPARTAMENTO-CIUDAD"), 0,1,"L",0,0);
        $formu->Cell(190,4,html_entity_decode(""), 0,1,"L",0,0);
        $formu->Cell(30,5,html_entity_decode("Asunto:              "), 0,0,"L",0,0);
        $formu->SetFont('Arial','','9');
        $formu->Cell(140,5,html_entity_decode("Vigencia Subsidio de Vivienda. "), 0,1,"L",0,0);
        $formu->Cell(190,4,html_entity_decode(""), 0,1,"L",0,0);
        $formu->Cell(190,5,html_entity_decode("Cordial saludo, "), 0,1,"L",0,0);
        $formu->Cell(190,4,html_entity_decode(""), 0,1,"L",0,0);
        $formu->SetFont('Arial','','9');
        $formu->Cell(190,5,html_entity_decode("Comedidamente, me permito informar que una vez cumplido el  (a&ntilde;os que tiene la asignaci&oacute;n) _______________a&ntilde;o de vigencia del "), 0,1,"L",0,0);
        $formu->Cell(190,5,html_entity_decode("Subsidio Familiar de Vivienda, el Consejo Directivo de la Caja de Compensaci&oacute;n Familiar del Huila en el desarrollo  de sus facultades "), 0,1,"L",0,0);
        $formu->Cell(190,5,html_entity_decode("legales a trav&eacute;s del acuerdo No. ____ del ___(d&iacute;a) de ___(mes) de ____(a&ntilde;o) fue ampliado hasta el ____ (d&iacute;a) de ____(mes)____ de "), 0,1,"L",0,0);
        $formu->Cell(190,5,html_entity_decode("___(a&ntilde;o) y el acuerdo No. ____ del ____de ____de ____, ampli&oacute; por ____meses  la vigencia del Subsidios Familiares de Vivienda de "), 0,1,"L",0,0);
        $formu->Cell(190,5,html_entity_decode("inter&eacute;s social asignados mediante Acta No. ____ del ___(d&iacute;a) de _____(mes) de _____(a&ntilde;o), hasta el ____(d&iacute;a) de ____(mes)___ de "), 0,1,"L",0,0);
        $formu->Cell(190,5,html_entity_decode("_____(a&ntilde;o)."), 0,1,"L",0,0);
        $formu->Cell(190,4,html_entity_decode(""), 0,1,"L",0,0);
        $formu->Cell(190,5,html_entity_decode("Recuerde que la ampliaci&oacute;n de las vigencias de los subsidios se cumple seg&uacute;n lo reglamentado en el Decreto 1077 del 2015 Art&iacute;culo "), 0,1,"L",0,0);
        $formu->Cell(44,5,html_entity_decode("2.1.1.1.1.4.2.5, Par&aacute;grafo 4&deg;."), 0,0,"L",0,0);
        $formu->SetFont('Arial','B','8.3');
        $formu->Cell(146,5,html_entity_decode("\"Las Cajas de Compensaci&oacute;n Familiar podr&aacute;n prorrogar, mediante acuerdo expedido por su respectivo "), 0,1,"L",0,0);
        $formu->SetFont('Arial','B','8.45');
        $formu->Cell(190,5,html_entity_decode("Consejo Directivo, la vigencia de los subsidios familiares de vivienda asignados a sus afiliados por un plazo no superior a doce (12) "), 0,1,"L",0,0);
        $formu->SetFont('Arial','B','8.5');
        $formu->Cell(190,5,html_entity_decode("meses,  prorrogable  m&aacute;ximo  por  doce  (12)  meses  m&aacute;s.  Para  los  casos  en  los  que  exista  giro  anticipado  de  subsidio, esta "), 0,1,"L",0,0);
        $formu->Cell(190,5,html_entity_decode("ampliaci&oacute;n estar&aacute; condicionada a la entrega por parte del oferente \"."), 0,1,"L",0,0);
        $formu->SetFont('Arial','','9');
        $formu->Cell(190,4,html_entity_decode(""), 0,1,"L",0,0);
        $formu->Cell(84,5,html_entity_decode("Articulo 2.1.1.1.1.5.1.1. Giro de los recursos Par&aacute;grafo 2&deg;. "), 0,0,"L",0,0);
        $formu->Cell(106,5,html_entity_decode("\"La escritura p&uacute;blica en la que conste la adquisici&oacute;n, la construcci&oacute;n o el "), 0,1,"L",0,0);
        $formu->Cell(190,5,html_entity_decode("mejoramiento, seg&uacute;n sea el caso, deber&aacute; suscribirse dentro del per&iacute;odo de vigencia del Subsidio Familiar de Vivienda. Dentro de los "), 0,1,"L",0,0);
        $formu->Cell(190,5,html_entity_decode("sesenta  (60)  d&iacute;as siguientes a su vencimiento el subsidio ser&aacute;  pagado, siempre que se acredite el cumplimiento de los respectivos "), 0,1,"L",0,0);
        $formu->Cell(190,5,html_entity_decode("requisitos en las modalidades de adquisici&oacute;n de vivienda nueva, construcci&oacute;n en sitio propio o mejoramiento, seg&uacute;n corresponda\". "), 0,1,"L",0,0);
        $formu->Cell(190,4,html_entity_decode(""), 0,1,"L",0,0);
        $formu->Cell(190,5,html_entity_decode("Par&aacute;grafo 3&deg;.Adicionalmente, se podr&aacute;n realizar los pagos aqu&iacute; previstos en forma extempor&aacute;nea en los siguientes casos, siempre y "), 0,1,"L",0,0);
        $formu->Cell(190,5,html_entity_decode("cuando el plazo adicional no supere los sesenta (60) d&iacute;as calendario: "), 0,1,"L",0,0);
        $formu->Cell(190,4,html_entity_decode(""), 0,1,"L",0,0);
        $formu->Cell(190,5,html_entity_decode("       1.  Cuando encontr&aacute;ndose en tr&aacute;mite la operaci&oacute;n de compraventa, la construcci&oacute;n o el mejoramiento al cual se aplicar&aacute; el "), 0,1,"L",0,0);
        $formu->Cell(190,5,html_entity_decode("            Subsidio  Familiar  de  Vivienda y  antes de la  expiraci&oacute;n  de  su  vigencia,  se hace necesario designar un sustituto por "), 0,1,"L",0,0);
        $formu->Cell(190,5,html_entity_decode("            fallecimiento del beneficiario. "), 0,1,"L",0,0);
        $formu->Cell(190,5,html_entity_decode("       2.  Cuando  la  documentaci&oacute;n  completa  ingrese  oportunamente  para  el pago  del  valor del  subsidio al vendedor  de la"), 0,1,"L",0,0);
        $formu->Cell(190,5,html_entity_decode("            vivienda, pero se detectaren en la misma, errores no advertidos anteriormente, que se deban subsanar. "), 0,1,"L",0,0);
        $formu->Cell(190,4,html_entity_decode(""), 0,1,"L",0,0);
        $formu->Cell(190,5,html_entity_decode("Par&aacute;grafo 4&deg;. Los desembolsos de los subsidios asignados por  las  Cajas de  Compensaci&oacute;n se realizar&aacute;n en un plazo m&aacute;ximo de"), 0,1,"L",0,0);
        $formu->Cell(190,5,html_entity_decode("quince(15) d&iacute;as h&aacute;biles, una vez el hogar beneficiado cumpla con los requisitos exigidos en la presente secci&oacute;n. "), 0,1,"L",0,0);
        $formu->Cell(190,4,html_entity_decode(""), 0,1,"L",0,0);
        $formu->Cell(190,5,html_entity_decode("Par&aacute;grafo 5&deg;. Los documentos exigidos para el giro del subsidio se acreditar&aacute;n ante la entidad otorgante, quien autorizar&aacute; el giro al "), 0,1,"L",0,0);
        $formu->Cell(190,5,html_entity_decode("oferente de la soluci&oacute;n de vivienda. "), 0,1,"L",0,0);
        $formu->SetFont('Arial','','9.5');
        $formu->Cell(190,4,html_entity_decode(""), 0,1,"L",0,0);
        $formu->Cell(190,5,html_entity_decode("Cualquier  inquietud  adicional con  gusto  le  atenderemos en  nuestra  sede ubicada en la Calle 11 N&deg; 5 - 63, segundo piso "), 0,1,"L",0,0);
        $formu->Cell(190,5,html_entity_decode("del  Hipermercado  en  los  m&oacute;dulos  de  atenci&oacute;n  al  usuario  y/o en  las  l&iacute;neas  telef&oacute;nicas  No.  872 31 26,  l&iacute;nea  gratuita "), 0,1,"L",0,0);
        $formu->Cell(190,5,html_entity_decode("018000918869 Ext. 6, correo electr&oacute;nico coordinacionsfv@comfamilairhuila.com,   Pagina Web www.comfamiliarhuila.com. "), 0,1,"L",0,0);
        $formu->Cell(190,4,html_entity_decode(""), 0,1,"L",0,0);
        $formu->Cell(190,5,html_entity_decode("Atentamente,"), 0,1,"L",0,0);
        $formu->SetFont('Arial','B','9.5');
        $formu->Cell(190,4,html_entity_decode(""), 0,1,"L",0,0);
        $formu->Cell(190,5,html_entity_decode("MARIA ALVAREZ"), 0,1,"L",0,0);
        $formu->Cell(190,5,html_entity_decode("Coordinadora Subsidio de Vivienda"), 0,1,"L",0,0);
        $file="public/temp/reportes/empresa.pdf";
        ob_clean();
        $formu->Output( $file, "I");
    }
    public function vigenciaRegistrosAction(){
        $formu= new FPDF('P','mm','A4');
        $formu->Addpage();
        $formu->setTextColor(0);
        $formu->SetFont('Arial','','12');
        $this->setResponse('view');
        $formu->Cell(190,5,html_entity_decode(""), 0,1,"L",0,0);
        $formu->Cell(190,5,html_entity_decode(""), 0,1,"L",0,0);
        $formu->Cell(190,6,html_entity_decode("Neiva,__________________"), 0,1,"L",0,0);
        $formu->Cell(190,6,html_entity_decode(""), 0,1,"L",0,0);
        $formu->Cell(190,6,html_entity_decode("Se&ntilde;ores"), 0,1,"L",0,0);
        $formu->SetFont('Arial','B','12');
        $formu->Cell(190,6,html_entity_decode("COMFAMILIAR HUILA"), 0,1,"L",0,0);
        $formu->SetFont('Arial','','12');
        $formu->Cell(190,6,html_entity_decode("Ciudad."), 0,1,"L",0,0);
        $formu->Cell(190,5,html_entity_decode(""), 0,1,"L",0,0);
        $formu->Cell(190,5,html_entity_decode("Cordial saludo."), 0,1,"L",0,0);
        $formu->Cell(190,6,html_entity_decode(""), 0,1,"L",0,0);
        $formu->Cell(190,6,html_entity_decode("Yo_____________________________________________________________ identifiado(a)"), 0,1,"L",0,0);
        $formu->Cell(190,6,html_entity_decode("con cedula de ciudadan&iacute;a No._____________________________, me permito declarar  que "), 0,1,"L",0,0);
        $formu->Cell(190,6,html_entity_decode("conozco  desde  el  primer  momento  de  la  recepci&oacute;n  del  formulario  para  postulaci&oacute;n del "), 0,1,"L",0,0);
        $formu->Cell(190,6,html_entity_decode("Subsidio  Familiar  de  Vivienda  mi  compromiso de mantener la vigencias de mi postulaci&oacute;n "), 0,1,"L",0,0);
        $formu->Cell(190,6,html_entity_decode("y  en  caso de  no  salir  asignado  durante  el  a&ntilde;o, radicare  este  oficio  a  partir  del  23  de"), 0,1,"L",0,0);
        $formu->Cell(190,6,html_entity_decode("Diciembre  hasta  el  30  de  Enero  del  a&ntilde;o  vigente, para comunicar mi inter&eacute;s de continuar"), 0,1,"L",0,0);
        $formu->Cell(190,6,html_entity_decode("Activo como postulante."), 0,1,"L",0,0);
        $formu->Cell(190,6,html_entity_decode(""), 0,1,"L",0,0);
        $formu->Cell(142,6,html_entity_decode("Seg&uacute;n  lo  estipula  el  Decreto  1077  del  2015  Articulo  2.1.1.1.1.3.3.3.3."), 0,0,"L",0,0);
        $formu->SetFont('Arial','B','12');
        $formu->Cell(48,6,html_entity_decode("Vigencia  de  la "), 0,1,"L",0,0);
        $formu->Cell(190,6,html_entity_decode("postulaci&oacute;n \" Los inscritos en el Registro de Postulantes, que no fueren beneficiarios "), 0,1,"L",0,0);
        $formu->Cell(190,6,html_entity_decode("en una asignaci&oacute;n de subsidios, podr&aacute;n  continuar como postulantes h&aacute;biles para las "), 0,1,"L",0,0);
        $formu->Cell(190,6,html_entity_decode("asignaciones de  la  totalidad  del  a&ntilde;o  calendario.  Si  no  fueren  beneficiarios en las "), 0,1,"L",0,0);
        $formu->Cell(190,6,html_entity_decode("dem&aacute;s   asignaciones  de   dicho  a&ntilde;o,  para   continuar   siendo   postulantes   en  las "), 0,1,"L",0,0);
        $formu->Cell(190,6,html_entity_decode("asignaciones   del   a&ntilde;o   siguiente   deber&aacute;n   manifestar   tal   inter&eacute;s,  mediante  una "), 0,1,"L",0,0);
        $formu->Cell(190,6,html_entity_decode("comunicaci&oacute;n  escrita  dirigida  a  la  entidad  donde  postularon  por  primera vez. Lo "), 0,1,"L",0,0);
        $formu->Cell(190,6,html_entity_decode("anterior, sin perjuicio de la posibilidad de mantenerse en el  Registro  de  Postulantes "), 0,1,"L",0,0);
        $formu->Cell(190,6,html_entity_decode("mediante la actualizaci&oacute;n de la informaci&oacute;n, sin que  ello  afecte la continuidad de las "), 0,1,"L",0,0);
        $formu->Cell(190,6,html_entity_decode("condiciones de postulaci&oacute;n del hogar correspondiente\"."), 0,1,"L",0,0);
        $formu->SetFont('Arial','','12');
        $formu->Cell(190,6,html_entity_decode(""), 0,1,"L",0,0);
        $formu->Cell(190,6,html_entity_decode("Esta  actualizaci&oacute;n  se  efectuara  en  un  nuevo  formulario  en  la primera  convocatoria del "), 0,1,"L",0,0);
        $formu->Cell(190,6,html_entity_decode("a&ntilde;o en vigencia, relacionando  la variaci&oacute;n del ingreso base  familiar  no se  podr&aacute; modificar"), 0,1,"L",0,0);
        $formu->Cell(190,6,html_entity_decode("el grupo familiar."), 0,1,"L",0,0);
        $formu->Cell(190,10,html_entity_decode(""), 0,1,"L",0,0);
        $formu->Cell(190,6,html_entity_decode("Atentamente,"), 0,1,"L",0,0);
        $formu->Cell(190,10,html_entity_decode(""), 0,1,"L",0,0);
        $formu->Cell(190,6,html_entity_decode("Firma   _____________________               Direcci&oacute;n __________________________ "), 0,1,"L",0,0);
        $formu->Cell(190,6,html_entity_decode(""), 0,1,"L",0,0);
        $formu->Cell(190,6,html_entity_decode("Cedula _____________________       		     Tel&eacute;fono___________________________"), 0,1,"L",0,0);
        $file="public/temp/reportes/empresa.pdf";
        ob_clean();
        $formu->Output( $file, "I");
    }
    
    public function preguntasAction(){
        try{
            try{
                $this->setResponse("ajax");
                $response = parent::startFunc();
                $modelos = array("mercurio07","mercurio19","mercurio22");
                $Transaccion = parent::startTrans($modelos);
                $respuesta1 = $this->getPostParam("respuest1","striptags");
                $respuesta2 = $this->getPostParam("respuest2","striptags");
                $pregunta1 = $this->getPostParam("pregunta1");
                $pregunta2 = $this->getPostParam("pregunta2");

                $mercurio19 = new Mercurio19();                  
                $mercurio19->setTransaction($Transaccion);
                $mercurio19->setCodcaj(Session::getDATA('codcaj'));
                $mercurio19->setTipo(Session::getDATA('tipo'));
                $mercurio19->setCoddoc(Session::getDATA('coddoc'));
                $mercurio19->setDocumento(Session::getDATA('documento'));
                $mercurio19->setCodigo($pregunta1);
                $mercurio19->setRespuesta($respuesta1);
                if(!$mercurio19->save()){
                    parent::setLogger($mercurio19->getMessages());
                    parent::ErrorTrans();
                }
                $mercurio19->setTransaction($Transaccion);
                $mercurio19->setCodcaj(Session::getDATA('codcaj'));
                $mercurio19->setTipo(Session::getDATA('tipo'));
                $mercurio19->setCoddoc(Session::getDATA('coddoc'));
                $mercurio19->setDocumento(Session::getDATA('documento'));
                $mercurio19->setCodigo($pregunta2);
                $mercurio19->setRespuesta($respuesta2);
                if(!$mercurio19->save()){
                    parent::setLogger($mercurio19->getMessages());
                    parent::ErrorTrans();
                }

                parent::finishTrans();
                //parent::enviarCorreo(Session::getDATA('nombre'),Session::getDATA('nombre'),$email,$asuntoemp,$msgemp,"");
                    return $this->redirect("principal/index/Se guardo la informacion correctamente");
            } catch (DbException $e){
                parent::setLogger($e->getMessage());
                parent::ErrorTrans();
            }
        } catch (TransactionFailed $e){
            $response = parent::errorFunc("Error al Cambiar la Clave");
            return $this->renderText(json_encode($response));
        }
    }
}

?>
