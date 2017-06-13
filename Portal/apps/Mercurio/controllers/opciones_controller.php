<?php

class OpcionesController extends ApplicationController {

    public function indexAction(){
      $this->setResponse("ajax");
      echo parent::showTitle('Bienvenidos a Mercurio');
    }
    
    public function verificarIp_viewAction(){
      $this->setTemplateAfter('escritorio');
      echo parent::showTitle('Asignar Ip');
      $i=1;
      $mercurio19 = $this->Mercurio19->find("codcaj = '".Session::getDATA('codcaj')."' AND tipo='".Session::getDATA('tipo')."' AND documento = '".Session::getDATA('documento')."' ");
      foreach($mercurio19 as $mmercurio19){
        $mmercurio18 = $this->Mercurio18->findFirst("codigo = '{$mmercurio19->getCodigo()}'");
        $this->setParamToView("pregunta$i", $mmercurio18->getDetalle());
        Tag::displayTo("pregunta$i", $mmercurio18->getCodigo());
        $i++;
      }
      echo Message::info("Por Favor conteste las preguntas y si desea asigne esta nueva ip");
    }
    
    public function verificarIpAction(){
      try{
            try{
                $this->setResponse("ajax");
                $response = parent::startFunc();
                $modelos = array("mercurio22");
                $Transaccion = parent::startTrans($modelos);
                $pregunta1 = $this->getPostParam("pregunta1");
                $respuesta1 = $this->getPostParam("respuesta1");
                $pregunta2 = $this->getPostParam("pregunta2");
                $respuesta2 = $this->getPostParam("respuesta2");
                $registra = $this->getPostParam("registra");
                $mercurio19_1 = $this->Mercurio19->findFirst("codcaj = '".Session::getDATA('codcaj')."' AND tipo='".Session::getDATA('tipo')."' AND documento = '".Session::getDATA('documento')."' AND codigo='$pregunta1' AND respuesta = '$respuesta1'");
                $mercurio19_2 = $this->Mercurio19->findFirst("codcaj = '".Session::getDATA('codcaj')."' AND tipo='".Session::getDATA('tipo')."' AND documento = '".Session::getDATA('documento')."' AND codigo='$pregunta2' AND respuesta = '$respuesta2'");
                if($mercurio19_1==false || $mercurio19_2==false){
                    $response = parent::errorFunc("las respuestas no son correctas ");
                    return $this->renderText(json_encode($response));
                }
                if($registra=="S"){
                  $mercurio22 = new Mercurio22();
                  $mercurio22->setTransaction($Transaccion);
                  $mercurio22->setCodcaj(Session::getDATA('codcaj'));
                  $mercurio22->setTipo(Session::getDATA('tipo'));
                  $mercurio22->setCoddoc(Session::getDATA('coddoc'));
                  $mercurio22->setDocumento(Session::getDATA('documento'));
                  $mercurio22->setIp($_SERVER["REMOTE_ADDR"]);
                  if(!$mercurio22->save()){
                      parent::setLogger($mercurio22->getMessages());
                      parent::ErrorTrans();
                  }
                }
                parent::finishTrans();
                $response = parent::successFunc("contestacion exitosa",null);
                return $this->renderText(json_encode($response));
            } catch (DbException $e){
                parent::setLogger($e->getMessage());
                parent::ErrorTrans();
            }
        } catch (TransactionFailed $e){
            $response = parent::errorFunc("Error al Verificar Respuesta");
            return $this->renderText(json_encode($response));
        }
    }
    
    public function claveVencida_viewAction(){
      $this->setTemplateAfter('escritorio');
      echo parent::showTitle('Clave Vencida');
      echo Message::info("Por Favor asigne su nueva clave ya que la anterior ya fue vencidad");
    }
    
    public function claveVencidaAction(){
      try{
            try{
                $this->setResponse("ajax");
                $response = parent::startFunc();
                $modelos = array("mercurio07");
                $today = new Date();
                $today->addMonths(3);
                $Transaccion = parent::startTrans($modelos);
                $claveant = $this->getPostParam("claveant");
                $clavenue = $this->getPostParam("clavenue");
                $clavecon = $this->getPostParam("clavecon");
                $mclave = parent::encriptar($claveant);
                $mercurio07 = $this->Mercurio07->findFirst("codcaj = '".Session::getDATA('codcaj')."' AND tipo='".Session::getDATA('tipo')."' AND documento = '".Session::getDATA('documento')."' AND clave='$mclave'");
                if($mercurio07==false){
                    $response = parent::errorFunc("la clave no es correcta ");
                    return $this->renderText(json_encode($response));
                }
                if($clavenue!=$clavecon){
                    $response = parent::errorFunc("las Claves no coinciden");
                    return $this->renderText(json_encode($response));
                }
                $mercurio07->setTransaction($Transaccion);
                $mercurio07->setFeccla($today);
                $mclave = parent::encriptar($clavenue);
                if($mercurio07->getClave()!=$mclave){
                    $mercurio07->setClave($mclave);
                    if(!$mercurio07->save()){
                        parent::setLogger($mercurio07->getMessages());
                        parent::ErrorTrans();
                    }
                }else{
                    $response = parent::errorFunc("la clave anterior es la misma nueva");
                    return $this->renderText(json_encode($response));
                }
                parent::finishTrans();
                $response = parent::successFunc("Cambio de Clave con Exito",null);
                return $this->renderText(json_encode($response));
            } catch (DbException $e){
                parent::setLogger($e->getMessage());
                parent::ErrorTrans();
            }
        } catch (TransactionFailed $e){
            $response = parent::errorFunc("Error al Cambiar la Clave");
            return $this->renderText(json_encode($response));
        }
    }
    
    public function preguntas_viewAction(){
      $this->setTemplateAfter('escritorio');
      echo parent::showTitle('Reestablacer la clave');
    }
    
    public function preguntasAction(){
      try{
        try{
          $this->setResponse("ajax");
          $response = parent::startFunc();
          $tipo = $this->getPostParam("tipoVal");
          $documento = $this->getPostParam("documentoVal");
          $mercurio07 = $this->Mercurio07->findFirst("codcaj = '".Session::getDATA('codcaj')."' AND tipo = '$tipo' AND documento = '$documento'");
          if($mercurio07==false){
            $response = parent::errorFunc("Debe ser un Usuario Registrado");
            return $this->renderText(json_encode($response));
          }
          $response = parent::successFunc("Por Favor Conteste sus Pregunta de asignacion",null);
          $mercurio19 = $this->Mercurio19->find("codcaj = '".Session::getDATA('codcaj')."' AND tipo = '$tipo' AND documento = '$documento'");
          $i=1;
          foreach($mercurio19 as $mmercurio19){
            $response["pregunta{$i}_cod"] = $mmercurio19->getCodigo();
            $mercurio18 = $this->Mercurio18->findFirst("codigo = '{$mmercurio19->getCodigo()}'");
            $response["pregunta{$i}_det"] = $mercurio18->getDetalle()." ?";
            $i++;
          }
          return $this->renderText(json_encode($response));
        }catch (DbException $e) {
          parent::setLogger($e->getMessage());
          parent::ErrorTrans();
        }
      }catch (TransactionFailed $e) {
        $response = parent::errorFunc("Error al verificar");
        return $this->renderText(json_encode($response));
      }
    }
    public function contestarAction(){
      try{
        try{
          $this->setResponse("ajax");
          $response = parent::startFunc();
          $modelos = array("mercurio07");
          $Transaccion = parent::startTrans($modelos);
          $tipo = $this->getPostParam("tipoVal");
          $documento = $this->getPostParam("documento");
          $pregunta1 = $this->getPostParam("pregunta1");
          $respuesta1 = $this->getPostParam("respuesta1");
          $pregunta2 = $this->getPostParam("pregunta2");
          $respuesta2 = $this->getPostParam("respuesta2");
          $mercurio07 = $this->Mercurio07->findFirst("codcaj = '".Session::getDATA('codcaj')."' AND tipo = '$tipo' AND documento = '$documento'");
          if($mercurio07==false){
            $response = parent::errorFunc("Debe ser un Usuario Registrado");
            return $this->renderText(json_encode($response));
          }
          $mercurio07->setTransaction($Transaccion);
          $mercurio19_1 = $this->Mercurio19->findFirst("codcaj = '".Session::getDATA('codcaj')."' AND tipo = '$tipo' AND documento = '$documento' AND respuesta='$respuesta1' ");
          $mercurio19_2 = $this->Mercurio19->findFirst("codcaj = '".Session::getDATA('codcaj')."' AND tipo = '$tipo' AND documento = '$documento' AND respuesta='$respuesta2' ");
          if($mercurio19_1==false || $mercurio19_2==false){
            $response = parent::errorFunc("Respuestas Incorrectas");
            return $this->renderText(json_encode($response));
          }
          $asuntoemp = "Afiliacion Consulta en Linea";
          $mclave = $mercurio07->getClave();
          $numdoc = $mercurio07->getDocumento();
          $tipo = $mercurio07->getTipo();
          if($mercurio07->getTipo() == 'T')$tipo = "Trabajador";
          if($mercurio07->getTipo() == 'E')$tipo = "Empresa";
          if($mercurio07->getTipo() == 'P')$tipo = "Particular";
          $clave = parent::desencriptar($mclave);
          $msgemp ="Tipo de Afiliado ".$tipo;
          $msgemp .="<br> Numero de Documento: ".$numdoc;
          $msgemp .="<br> su clave para ingresar a servicio en linea es : ".$clave." ";
          $email = $mercurio07->getEmail();

          parent::enviarCorreo(Session::getDATA('nombre'),Session::getDATA('nombre'),$email,$asuntoemp,$msgemp,"");
/*
          $mercurio07->setClave(parent::descriptar($documento));
          if(!$mercurio07->save()){
             parent::setLogger($mercurio07->getMessages());
            parent::ErrorTrans(); 
          }
*/
          parent::finishTrans();
          $email = substr($email,0,5)."***".substr($email,-8);
          $response = parent::successFunc("La clave fue enviada al correo electronico $email registrado por el usuario",null);
          return $this->renderText(json_encode($response));
        }catch (DbException $e) {
          parent::setLogger($e->getMessage());
          parent::ErrorTrans();
        }
      }catch (TransactionFailed $e) {
        $response = parent::errorFunc("Error al consultar");
        return $this->renderText(json_encode($response));
      }
    }
    
    
    public function beforeFirst_viewAction(){
      $this->setTemplateAfter('escritorio');
      echo parent::showTitle('Bienvenidos a Mercurio');
      echo Message::info("Por Favor Verifique su documento");
    }
   

    public function beforeFirstAction(){
        try{      
            $this->setResponse("ajax");
            $response = parent::startFunc();
            $login = $this->getPostParam("documentoIng","striptags");
            $coddoc = $this->getPostParam("coddocIng","striptags");
            $tipo = $this->getPostParam("tipoIng","striptags");
            $filter = new Filter();                                                                          
            $login = $filter->apply($login,array("digits"));
            $result = parent::webService('Autenticar',array("tipo"=>$tipo,"documento"=>$login, 'coddoc'=>$coddoc));
            if($tipo!="P" && $result==false){
                $response = parent::errorFunc("Documento no Existe");
                return $this->renderText(json_encode($response));
            }
            if($tipo=="P" && $result!=false){
                $response = parent::errorFunc("Documento Existe En la Caja");
                return $this->renderText(json_encode($response));
            }
            if($tipo=="P" ){
                $result = parent::webService('Autenticar',array("tipo"=>"T","documento"=>$login, 'coddoc'=>$coddoc));
                if($result!=false){
                    $response = parent::errorFunc("El documento esta registrado en la caja como Trabajador o Beneficiario");
                    return $this->renderText(json_encode($response));
                }
                $result = parent::webService('Autenticar',array("tipo"=>"E","documento"=>$login, 'coddoc'=>$coddoc));
                if($result!=false){
                    $response = parent::errorFunc("El documento esta registrado en la caja como Empresa");
                    return $this->renderText(json_encode($response));
                }
            }
            SESSION::setData("nombre", $result[0]['nombre']);
            SESSION::setData("codcat", $result[0]['codcat']);
            SESSION::setData("estado", $result[0]['estado']);
            $mercurio02 = $this->Mercurio02->findBySql("SELECT razsoc FROM mercurio02 WHERE codcaj = '".Session::getDATA('codcaj')."'");
            SESSION::setData("nomcaj", $mercurio02->getRazsoc());
            SESSION::setData("tipo", $tipo);
            SESSION::setData("documento", $login);
            SESSION::setData("coddoc", $coddoc);
            $l = $this->Mercurio07->count("*","conditions: codcaj = '".Session::getDATA('codcaj')."' AND tipo='$tipo' AND documento = '$login' ");
            if($l==0){
                $response = parent::successFunc("VERIFICADO",null);
                return $this->renderText(json_encode($response));
            }else{
                $response = parent::errorFunc("Usuario ya registrado");
                return $this->renderText(json_encode($response));
            }

        }catch (DbException $e){
            $response = parent::errorFunc("se presento algo vuelve a intentarlo");
            return $this->renderText(json_encode($response));
        }
    }
    public function consultaEmailAction(){
        $this->setResponse("ajax");
        $email = $this->getPostParam('email');
        $response = $this->Mercurio07->findFirst("email='{$email}'");
        return $this->renderText(json_encode($response));
    }

    public function primera_viewAction(){
      $this->setTemplateAfter('escritorio');
      echo parent::showTitle('Bienvenidos a Mercurio');
      echo Message::info("Por Favor asigne una clave para su proximo ingreso a mercurio y asigne sus preguntas de seguridad");
    }

    public function cambiarClavePriAction(){
        try{
            try{
                $this->setResponse("ajax");
                $response = parent::startFunc();
                $modelos = array("mercurio07","mercurio19","mercurio22");
                $Transaccion = parent::startTrans($modelos);
                $clavenue = $this->getPostParam("clavenue","striptags");
                $clavecon = $this->getPostParam("clavecon","striptags");
                $respuesta1 = $this->getPostParam("respuest1");
                $respuesta2 = $this->getPostParam("respuest2");
                $pregunta1 = $this->getPostParam("pregunta1");
                $pregunta2 = $this->getPostParam("pregunta2");
                $registra = $this->getPostParam("registra");
                $email = $this->getPostParam("email","striptags");
                $autoriza = $this->getPostParam("autoriza","striptags");
                $agencia = $this->getPostParam("agencia","striptags");
                /*Debug::addVariable("a",$clavenue);
                Debug::addVariable("b",$clavecon);
                Debug::addVariable("c",$respuesta1);
                Debug::addVariable("z",$respuesta2);
                Debug::addVariable("d",$pregunta1);
                Debug::addVariable("e",$pregunta2);
                Debug::addVariable("f",$registra);
                Debug::addVariable("g",$email);
                Debug::addVariable("h",$autoriza);
                throw new DebugException(0);*/

                $today = new Date();
                $today->addMonths(3);
                $today2 =new Date();
                $filter = new Filter();                                                                          
                $autoriza = $filter->apply($autoriza,array("addslaches","extraspaces","striptags"));
                $email = $filter->apply($email,array("addslaches","extraspaces","striptags"));
                $clavecon = $filter->apply($clavecon,array("addslaches","alpha","extraspaces","striptags"));
                $clavenue = $filter->apply($clavenue,array("addslaches","alpha","extraspaces","striptags"));
                //$respuesta1 = $filter->apply($respuesta1,array("addslaches","alpha","extraspaces","striptags"));
                ////$respuesta2 = $filter->apply($respuesta2,array("addslaches","alpha","extraspaces","striptags"));
                $pregunta1 = $filter->apply($pregunta1,array("addslaches","alpha","extraspaces","striptags"));
                $pregunta2 = $filter->apply($pregunta2,array("addslaches","alpha","extraspaces","striptags"));
                $preg1 = $this->Mercurio18->findFirst("codigo = $pregunta1");
                $preg2 = $this->Mercurio18->findFirst("codigo = $pregunta2");
                $asuntoemp = "Afiliacion Consulta en Linea";
                $msgemp ="Bienvenido a Comfamiliar Huila, a continuacion confirmamos sus datos de usuario para el ingreso a nuestro portal web: 
                <br/> <br/> Documento de identidad: ".Session::getDATA('documento')." 
                <br/> Clave: ".$clavecon." 
                <br/> <br/> Pregunta No. 1 : ".$preg1->getDetalle()."
                <br/> Respuesta pregunta No. 1 : ".$respuesta1."
                <br/> Pregunta No. 2 : ".$preg2->getDetalle()."
                <br/> Respuesta pregunta No. 2 : ".$respuesta2." 
                <br/> <br/> Estimado Afiliado, recuerde que por seguridad podra cambiar su clave cuando lo desee.";
                $l = $this->Mercurio07->count("*","conditions: codcaj = '".Session::getDATA('codcaj')."' AND tipo='".Session::getDATA('tipo')."' AND documento = '".Session::getDATA('documento')."'");
                if($l==0){
                  $mercurio07 = new Mercurio07();
                  $mercurio07->setTransaction($Transaccion);
                  $mercurio07->setCodcaj(Session::get('codcaj'));
                  $mercurio07->setTipo(Session::get('tipo'));
                  $mercurio07->setCoddoc(Session::get('coddoc'));
                  $mercurio07->setDocumento(Session::get('documento'));
                  $mercurio07->setClave(parent::encriptar(Session::get('documento')));
                  $mercurio07->setFeccla($today);
                  $mercurio07->setFecreg($today2->getUsingFormatDefault());
                  $mercurio07->setEmail($email);
                  $mercurio07->setAutoriza($autoriza);
                  $mercurio07->setAgencia($agencia);
                  if(!$mercurio07->save()){
                    parent::setLogger($mercurio07->getMessages());
                    parent::ErrorTrans(); 
                  }
                }
                if($clavenue!=$clavecon){
                    $response = parent::errorFunc("las Claves no coinciden");
                    return $this->renderText(json_encode($response));
                }
                $mclave = parent::encriptar($clavenue);

                $mercurio07->setClave($mclave);
                if(!$mercurio07->save()){
                    parent::setLogger($mercurio07->getMessages());
                    parent::ErrorTrans();
                }
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
                $mercurio19->setCodigo($pregunta2);
                $mercurio19->setRespuesta($respuesta2);
                if(!$mercurio19->save()){
                    parent::setLogger($mercurio19->getMessages());
                    parent::ErrorTrans();
                }
                if($registra=="S"){
                  $mercurio22 = new Mercurio22();
                  $mercurio22->setTransaction($Transaccion);
                  $mercurio22->setCodcaj(Session::getDATA('codcaj'));
                  $mercurio22->setTipo(Session::getDATA('tipo'));
                  $mercurio22->setCoddoc(Session::getDATA('coddoc'));
                  $mercurio22->setDocumento(Session::getDATA('documento'));
                  $mercurio22->setIp($_SERVER["REMOTE_ADDR"]);
                  if(!$mercurio22->save()){
                      parent::setLogger($mercurio22->getMessages());
                      parent::ErrorTrans();
                  }
                }
                parent::finishTrans();
                parent::enviarCorreo(Session::getDATA('nombre'),Session::getDATA('nombre'),$email,$asuntoemp,$msgemp,"");
                $response = parent::successFunc("Cambio de Clave y asignacion de preguntas con Exito",null);
                return $this->renderText(json_encode($response));
            } catch (DbException $e){
                parent::setLogger($e->getMessage());
                parent::ErrorTrans();
            }
        } catch (TransactionFailed $e){
            $response = parent::errorFunc("Error al Cambiar la Clave");
            return $this->renderText(json_encode($response));
        }
    }
    
    public function cambioClave_viewAction(){
      $this->setResponse("ajax");
      echo parent::showTitle('Asignacion/Cambio de Clave');
    }
    
    public function cambiarClaveAction(){
        try{
            try{
                $this->setResponse("ajax");
                $response = parent::startFunc();
                $modelos = array("mercurio07");
                $today = new Date();
                $today->addMonths(3);
                $Transaccion = parent::startTrans($modelos);
                $claveant = $this->getPostParam("claveant");
                $clavenue = $this->getPostParam("clavenue");
                $clavecon = $this->getPostParam("clavecon");
                $mclave = parent::encriptar($claveant);
                $mercurio07 = $this->Mercurio07->findFirst("codcaj = '".Session::getDATA('codcaj')."' AND tipo='".Session::getDATA('tipo')."' AND documento = '".Session::getDATA('documento')."' AND clave='$mclave'");
                if($mercurio07==false){
                    $response = parent::errorFunc("la clave no es correcta ");
                    return $this->renderText(json_encode($response));
                }
                if($clavenue!=$clavecon){
                    $response = parent::errorFunc("las Claves no coinciden");
                    return $this->renderText(json_encode($response));
                }
                $mercurio07->setTransaction($Transaccion);
                $mclave = parent::encriptar($clavenue);
                $mercurio07->setFeccla($today);
                if($mercurio07->getClave()!=$mclave){
                    $mercurio07->setClave($mclave);
                    if(!$mercurio07->save()){
                        parent::setLogger($mercurio07->getMessages());
                        parent::ErrorTrans();
                    }
                }else{
                    $response = parent::errorFunc("la clave anterior es la misma nueva");
                    return $this->renderText(json_encode($response));
                }
                $email  = $mercurio07->getEmail();
                $asunto = "Recordatorio de la Clave";
                $msg    = "Estimado Afiliado:<br/><br/>
                           Bienvenido al Servicio en L&iacute;nea de Comfamiliar Huila, si desea recordar su clave por favor tenga en cuenta los siguientes pasos:<br/><br/> 
                           1.    Ingrese el tipo de documento y n&uacute;mero de identificaci&oacute;n<br/><br/>
                           2.    Seleccione Aceptar, para que el sistema autom&aacute;ticamente le recuerde su clave a trav&eacute;s del correo electr&oacute;nico de contacto<br/><br/>
                           Estimado Afiliado, recuerde que por seguridad deber&aacute; cambiar la clave que se le asigne y hacerlo regularmente.<br/><br/>
                          Comfamiliar, m&aacute;s felicidad!!
                        ";

                parent::enviarCorreo(Session::getDATA('nombre'),Session::getDATA('nombre'),$email,$asunto,$msg,"");
                parent::finishTrans();
                $response = parent::successFunc("Cambio de Clave Exitoso",null);
                return $this->renderText(json_encode($response));
            } catch (DbException $e){
                parent::setLogger($e->getMessage());
                parent::ErrorTrans();
            }
        } catch (TransactionFailed $e){
            $response = parent::errorFunc("Error al Cambiar la Clave");
            return $this->renderText(json_encode($response));
        }
    }
    
    public function informacion_viewAction(){
      $this->setResponse("ajax");
      echo parent::showTitle('Actualizacion de Informacion');
    }
    
    public function informacionAction(){
      try{
        try{
          $this->setResponse("ajax");
          $response = parent::startFunc();
          $modelos = array("mercurio07");
          $Transaccion = parent::startTrans($modelos);
          $email = $this->getPostParam("email");
          $this->Mercurio07->updateAll("email='$email'","conditions: codcaj = '".Session::getDATA('codcaj')."' AND tipo='".Session::getDATA('tipo')."' AND documento = '".Session::getDATA('documento')."' ");
          parent::finishTrans();
          $response = parent::successFunc("Actualizacion de Informacion con Exito",null);
          return $this->renderText(json_encode($response));
        }catch (DbException $e){
          parent::setLogger($e->getMessage());
          parent::ErrorTrans();
        }
      }catch (TransactionFailed $e){
        $response = parent::errorFunc("Error al Cambiar la Clave");
        return $this->renderText(json_encode($response));
      }
    }
    
    public function cambioPreguntas_viewAction(){
      $this->setResponse("ajax");
      echo parent::showTitle('Cambios de Preguntas de seguridad');
    }
    
    public function cambioPreguntasAction(){
      try{
        try{
          $this->setResponse("ajax");
          $response = parent::startFunc();
          $modelos = array("mercurio19");
          $Transaccion = parent::startTrans($modelos);
          $pregunta1 = $this->getPostParam("pregunta1");
          $pregunta2 = $this->getPostParam("pregunta2");
          $respuesta1 = $this->getPostParam("respuesta1");
          $respuesta2 = $this->getPostParam("respuesta2");
          $clave = $this->getPostParam("clave");
          $mclave = parent::encriptar($clave);
          $mercurio07 = $this->Mercurio07->findFirst("codcaj = '".Session::getDATA('codcaj')."' AND tipo='".Session::getDATA('tipo')."' AND documento = '".Session::getDATA('documento')."' AND clave='$mclave'");
          if($mercurio07==false){
              $response = parent::errorFunc("la clave no es correcta ");
              return $this->renderText(json_encode($response));
          }
          if($this->Mercurio19->count("*","conditions: codcaj = '".Session::getDATA('codcaj')."' AND tipo='".Session::getDATA('tipo')."' AND documento = '".Session::getDATA('documento')."' AND (codigo='$pregunta1' OR codigo='$pregunta2')")>0){
              $response = parent::errorFunc("Las Preguntas deben de ser Diferentes ");
              return $this->renderText(json_encode($response));
          }
          if($respuesta1==$respuesta2){
              $response = parent::errorFunc("Las Respuestas son iguales por favor cambielas");
              return $this->renderText(json_encode($response));
          }
          $this->Mercurio19->deleteAll("codcaj = '".Session::getDATA('codcaj')."' AND tipo='".Session::getDATA('tipo')."' AND documento = '".Session::getDATA('documento')."' ");
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
          $mercurio19->setCodigo($pregunta2);
          $mercurio19->setRespuesta($respuesta2);
          if(!$mercurio19->save()){
              parent::setLogger($mercurio19->getMessages());
              parent::ErrorTrans();
          }
          parent::finishTrans();
          $response = parent::successFunc("Cambio de Preguntas Exitoso",null);
          return $this->renderText(json_encode($response));
        }catch (DbException $e){
          parent::setLogger($e->getMessage());
          parent::ErrorTrans();
        }
      }catch (TransactionFailed $e){
        $response = parent::errorFunc("Error al Cambiar la Clave");
        return $this->renderText(json_encode($response));
      }
    }
    public function respuestasAction(){
        try{
            try{
                $this->setResponse("ajax");
                $response = parent::startFunc();
                $tipo = $this->getPostParam("tipoVal");
                $documento = $this->getPostParam("documentoVal");
                $respuesta1 = $this->getPostParam("respuesta1");
                $respuesta2 = $this->getPostParam("respuesta2");
                $mercurio07 = $this->Mercurio07->findFirst("codcaj = '".Session::getDATA('codcaj')."' AND tipo = '$tipo' AND documento = '$documento'");
                if($mercurio07==false){
                    $response = parent::errorFunc("Debe ser un Usuario Registrado");
                    return $this->renderText(json_encode($response));
                }
                $response = parent::successFunc("Por Favor Conteste sus Pregunta de asignacion",null);
                $mercurio19 = $this->Mercurio19->findFirst("codcaj = '".Session::getDATA('codcaj')."' AND tipo = '$tipo' AND documento = '$documento'");
                if($mercurio19->getRespuesta() != $respuesta1){
                    $response = parent::errorFunc("Respuestas Incorrectas");
                    return $this->renderText(json_encode($response));
                }
                $mmercurio19 = $this->Mercurio19->findFirst("codcaj = '".Session::getDATA('codcaj')."' AND tipo = '$tipo' AND documento = '$documento' AND respuesta <> '{$mercurio19->getRespuesta()}'");
                if($mmercurio19 != FALSE){
                    if($mmercurio19->getRespuesta() != $respuesta2){
                        $response = parent::errorFunc("Respuestas Incorrectas");
                        return $this->renderText(json_encode($response));
                    }
                }else{
                    $mercu19 = $this->Mercurio19->count("codcaj = '".Session::getDATA('codcaj')."' AND tipo = '$tipo' AND documento = '$documento' AND respuesta = '{$mercurio19->getRespuesta()}'");
                    if($mercu19 >= 2){
                        if($mercurio19->getRespuesta() != $respuesta2){
                            $response = parent::errorFunc("Respuestas Incorrectas");
                            return $this->renderText(json_encode($response));
                        }
                    }
                }
                $response = parent::successFunc("",null);
                return $this->renderText(json_encode($response));
            }catch (DbException $e) {
                parent::setLogger($e->getMessage());
                parent::ErrorTrans();
            }
        }catch (TransactionFailed $e) {
            $response = parent::errorFunc("Error al verificar");
            return $this->renderText(json_encode($response));
        }
    }

    
}
?>
