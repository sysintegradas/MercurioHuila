<?php
Core::importFromLibrary('Excel', 'Main.php');

class ControllerBase {

    public $numpaginate = 100;

    private $log;
  
    public function init(){
        Session::setDATA("codcaj","CCF032");
        Router::routeTo("controller: login");
    }

    public function beforeFilter(){
      Session::setDATA("codcaj","CCF032");
      $controller = Router::getController();
      if($controller!="portal"){
        if(SESSION::getDATA('codcaj')==""){ 
          $this->redirect("portal/index");
          return false;
        }
      }
      if($controller=="principal"){
        if(SESSION::getDATA('tipo')=="" || SESSION::getDATA('documento')==""){
          $this->redirect("login/index");
          return false;
        }
      }
      $action = Router::getAction();
      $l = $this->Mercurio25->count("conditions: controlador = '$controller' and accion = '$action'");
      if($l>0)return true;
      $action_diff = array("slide","slideport");
      if(in_array($action,$action_diff)==true)return true;
      $today = new Date();
      $mercurio20 = new Mercurio20();
      $mercurio20->setId(0);
      $mercurio20->setTipo(Session::getDATA('tipo'));
      $mercurio20->setCodcaj(Session::getDATA('codcaj'));
      $mercurio20->setDocumento(Session::getDATA('documento'));
      $mercurio20->setIp($_SERVER["REMOTE_ADDR"]);
      $mercurio20->setFecha($today->getUsingFormatDefault());
      $mercurio20->setHora(date("H:i"));
      $mercurio20->setControlador($controller);
      $mercurio20->setAccion($action);
      $mercurio20->setNota(Session::getDATA('nota_audit'));
      $mercurio20->save();
      Session::setData('nota_audit',"");
      return true;
    }
    
    public function showTitle($title){
            
            echo "<div class='panel panel-primary' style='width: 100%; height: 100%;'>    <div class='panel-heading'> <h3 class='panel-title'>$title</h3> </div></div>";
            Session::setDATA('title',$title);
            echo "<script>document.title = 'Servicio en Linea - '+'$title'</script>";
/*
      echo "<div class='titulo'>$title</div>";
      Session::setDATA('title',$title);
      echo "<script>document.title = 'Mercurio - '+'$title'</script>";
*/
    }
    
    public function showHelp($help){
      echo "<script>actHelp('$help');</script>";
    }
    
    public function webService($funcion,$param){
        try{
            date_default_timezone_set('America/Bogota');
            $param_user= array("appId"=>"5664c5165b1e11bc56416d9e0260eae4","appPwd"=>"fec1de72b37939cb649179a046647b7c");
            $parametros = array_merge($param_user,$param);
            //$cliente = new SoapClient("http://www.comfamiliarenlinea.com/WebServicesDMZCapaExt/Services/WSMercurio.svc?wsdl", array('cache_wsdl' => 0));
            //http://10.10.1.60/WebServicesPrueba/Services/WSMercurio.svc?wsdl  Webservices Internos
            $cliente = new SoapClient("http://www.comfamiliarenlinea.com/WebServicesDMZCapaExtPrueba/Services/WSMercurio.svc?wsdl", array('cache_wsdl' => 0));
            $funcion = $funcion;
            $return = $cliente->$funcion($parametros);
            if($funcion == 'AfiliadoConsultar' or $funcion == 'Datben'){
                $return = $this->obj2array($return);
            }else{
                $return = $this->object2array($return);
            }
            return $return;
        }  catch (SoapFault $e) {  
            //Debug::addVariable("a",$e->faultstring);
            //throw new DebugException(0); 
            return false;
        }
    }




    public function object2array($object) {
        if (is_object($object)) {
            $array = false;
            foreach ($object as $key => $value) {
                foreach ($object->$key as $mkey => $value) {
                    $array_temp = array();
                    $flag = true;
                    foreach ($object->$key->$mkey as $key3 => $value) {
                        if(is_object($value)){
                            $flag = false;
                            $array_temp = array();
                            foreach ($value as $key4 => $value) {
                                $array_temp[strtolower($key4)] = trim($value);
                            }
                            $array[] = $array_temp;
                        }else{
                            $array_temp[strtolower($key3)] = trim($value);
                        }
                    }
                    if($flag==true) $array[] = $array_temp;
                }
            }
        }else{
            $array = $object;
        }
        return $array;
    } 

    public function obj2array($obj) {
        $out = array();
        foreach ($obj as $key => $val) {
            switch(true) {
                case is_object($val): $out[$key] = $this->obj2array($val); break;
                case is_array($val): $out[$key] = $this->obj2array($val); break;
                default: $out[$key] = $val;
            }
        }
        return $out;
    }

    public function webService2($funcion,$param){
      date_default_timezone_set('America/Bogota');
      $cliente = new SoapClient("http://www.comfamiliarenlinea.com/WebServicesDMZCapaExt/Services/WSMercurio.svc");
      $return = $cliente->$funcion($param);
      return $return;
    }
    
    public function showMenu(){
      $response = "<ul class='menu'>";

      $mercurio10 = $this->Mercurio10->findAllBySql("SELECT DISTINCT mercurio10.codare FROM mercurio10,mercurio11 WHERE mercurio11.codare=mercurio10.codare AND mercurio11.codope=mercurio10.codope AND mercurio11.tipo='".Session::getDATA('tipo')."' AND mercurio10.codcaj = '".Session::getDATA('codcaj')."' AND mercurio10.estado='A' and mercurio11.codare <> 'SE'");

      foreach($mercurio10 as $mmercurio10){
        $mmercurio08 = $this->Mercurio08->findFirst("codare = '{$mmercurio10->getCodare()}'");
        $response .= "<li ><a style='background-color: #44588F'>{$mmercurio08->getDetalle()}</a>";
        //importante 
        if(Session::getData('tipafi') == 'PENSIONADO REGIMEN ESPECIAL' || Session::getData('tipafi') == 'PENSIONADO VOLUNTARIO'|| Session::getData('tipafi') == 'PENSINADO SIST.CAJAS'){
            $mer10 = $this->Mercurio10->findAllBySql("SELECT DISTINCT mercurio10.codope FROM mercurio10,mercurio11 where mercurio11.tipo='".Session::getDATA('tipo')."' AND (mercurio10.codope='44' OR mercurio10.codope='29' OR mercurio10.codope='7' OR mercurio10.codope='71' OR mercurio10.codope='10' OR mercurio10.codope='17' AND (mercurio10.codope<>'16' AND mercurio10.codope<>'20' AND mercurio10.codope<>'25' AND mercurio10.codope<>'6' AND mercurio10.codope<>'5' AND mercurio10.codope<>'28')) ORDER BY detalle;"); // Restricciones del menu para un independiente segun la peticion.
        }else{
                if(Session::getData('tipo') == 'T'){
                    $param = array("cedtra"=>Session::getData('documento'));
                    $result = self::webService("Nucfamtrabajador", $param);
                    if($result == false){
                        Session::setData('estado','I');
                    }else{
                        Session::setData('estado','A');
                    }
                }
            if(Session::getData('estado') == 'A'){
                $mer10 = $this->Mercurio10->findAllBySql("SELECT DISTINCT mercurio10.codope FROM mercurio10,mercurio11 WHERE mercurio11.codare=mercurio10.codare AND mercurio11.codope=mercurio10.codope AND mercurio11.tipo='".Session::getDATA('tipo')."' AND mercurio10.codcaj = '".Session::getDATA('codcaj')."' AND mercurio10.codare = '{$mmercurio10->getCodare()}' AND mercurio10.estado='A' AND mercurio11.detalle <> 'SIMULADOR' AND mercurio11.detalle <> 'CONSULTA DE CREDITOS' ORDER BY detalle");
            }else{
                if(Session::getData('estado') == "I" && Session::getData('codest') == "4118"){
                    $condi = " AND mercurio10.codope <> '5' AND mercurio10.codope <> '45'  AND mercurio10.codope <> '44' AND mercurio10.codope <> '20' AND mercurio10.codope <> '17' AND mercurio10.codope <> '6' AND mercurio10.codope <> '16' AND mercurio10.codope <> '71'";
                }else{
                    $condi = "  ";
                }
                $mer10 = $this->Mercurio10->findAllBySql("SELECT DISTINCT mercurio10.codope FROM mercurio10,mercurio11 WHERE mercurio11.codare=mercurio10.codare AND mercurio11.codope=mercurio10.codope AND mercurio11.tipo='".Session::getDATA('tipo')."' AND mercurio10.codcaj = '".Session::getDATA('codcaj')."' AND mercurio10.codare = '{$mmercurio10->getCodare()}' AND mercurio10.estado='A' AND mercurio11.detalle <> 'SIMULADOR' AND mercurio11.detalle <> 'CONSULTA DE CREDITOS'  AND mercurio10.codope<>'29' AND mercurio10.codope<>'13' AND mercurio10.codope<>'23' AND mercurio10.codope<>'28' AND mercurio10.codope<>'42' $condi  ORDER BY detalle");
            }
        }
        foreach($mer10 as $mmer10){
            $mmercurio11 = $this->Mercurio11->findFirst("codare = '{$mmercurio10->getCodare()}' AND codope = '{$mmer10->getCodope()}'");
            $response .="<ul>";
            $response .= "<li onclick=\"traerOpcion('{$mmercurio11->getUrl()}',this,'{$mmercurio10->getCodare()}','{$mmer10->getCodope()}');\"><a><strong>{$mmercurio11->getDetalle()}</strong></a></li>";
            $response .="</ul>";
        }
        $response .="</li>";
      }
      $response .="</ul>";

      return $response;
    }


    public function enviarCorreo($descripcion='',$nombre='',$email_user='',$asunto='',$msg='',$file=''){
        $mercurio02 = $this->Mercurio02->findFirst("codcaj = '".Session::getDATA('codcaj')."'");
        //Nuevo formato de Correo
        $mensaje  = "";
        $mensaje .= "<div style='padding:0px;margin:0px'>";
        $mensaje .= "<table width='100%' bgcolor='#EEEEEE' cellpadding='0' cellspacing='0' border='0'>";
        $mensaje .= "<tbody>";
        $mensaje .= "<tr>";
        $mensaje .= "<td align='center' style='font-family:Helvetica,Arial;padding:0px'>";
        $mensaje .= "<table width='100%' cellpadding='0' cellspacing='0' border='0' style='width:100%;max-width:690px'>";
        $mensaje .= "<tbody>";
        $mensaje .= "<tr>";
        $mensaje .= "<td style='padding:0px'>";
        $mensaje .= "<table width='100%' cellpadding='0' cellspacing='0' border='0'>";
        $mensaje .= "<tbody>";
        $mensaje .= "<tr>";
        $mensaje .= "<td colspan='2'>"; 
        $mensaje .= "<img style='display:block;border:none' src='http://www.comfamiliarhuila.mercurio.net.co/Portal/public/img/email/cabezeraemail.png' width='100%' height='' title='Cabezera' alt='Cabezera' class='CToWUd'>";
        $mensaje .= "</td>";
        $mensaje .= "</tr>";
        //$mensaje .= "<tr>"; 
        //$mensaje .= "<td bgcolor='#FFFFFF' style='padding:20px 20px 0;border:1px solid #e1e1e1;border-top:none;border-bottom:none'>"; 
        //$mensaje .= "<div style='font-family:Helvetica,Arial;font-size:22px;line-height:32px;color:#00638a'>&nbsp;</div>"; 
        //$mensaje .= "</td>"; 
        //$mensaje .= "</tr>"; 
        $mensaje .= "<tr>"; 
        $mensaje .= "<td bgcolor='#FFFFFF' style='padding:15px 20px 25px;border:1px solid #e1e1e1;border-top:none;border-bottom:none'>"; 
        $mensaje .= "<div style='font-family:Helvetica,Arial;font-size:14px;font-style:italic;color:#000'>".$msg."</div>"; 
        $mensaje .= "</td>"; 
        $mensaje .= "</tr>"; 
        $mensaje .= "<tr>"; 
        //$mensaje .= "<td valign='middle' style='padding:20px;background:#fff;border-left: 1px solid #e1e1e1;border-right: 1px solid #e1e1e1;font-family:Helvetica,Arial;font-size:14px;font-style:italic;line-height:20px;color:#787878'>{$mercurio02->getRazsoc()} <br/>Direccion: {$mercurio02->getDireccion()} <br/>Email: {$mercurio02->getEmail()} <br/>Telefono: {$mercurio02->getTelefono()} <br/>Website: <a style='font-family:Helvetica,Arial;font-size:14px;line-height:20px;color:#478eae;text-decoration:none' href='{$mercurio02->getPagweb()}' target='_blank'>{$mercurio02->getPagweb()}</a>.";
        $mensaje .= "<td valign='middle' style='padding:20px;background:#fff;border-left: 1px solid #e1e1e1;border-right: 1px solid #e1e1e1;font-family:Helvetica,Arial;font-size:14px;font-style:italic;line-height:20px;color:#787878'>{$mercurio02->getRazsoc()} 
                    <br/>Neiva - Telefono: 018000918869 ext 2 
                    <br/>Pitalito - Telefono: 8360095 
                    <br/>Garzon - Telefono: 8332178 
                    <br/>La Plata - Telefono: 8371576  
                    <br/>Website: <a style='font-family:Helvetica,Arial;font-size:14px;line-height:20px;color:#478eae;text-decoration:none' href='{$mercurio02->getPagweb()}' target='_blank'>{$mercurio02->getPagweb()}</a>.";
        $mensaje .= "</td>";
        $mensaje .= "</tr>"; 

      $mensaje .= "<td colspan='2'>"; 
      $mensaje .= "<img style='display:block;border:none' src='http://www.comfamiliarhuila.mercurio.net.co/Portal/public/img/email/piepaginaemail.png' width='100%' height='' title='Piecorreo' alt='Piecorreo' class='CToWUd'>";
      $mensaje .= "</td>";
      $mensaje .= "</tr>";
      $mensaje .= "</tbody>"; 
      $mensaje .= "</table>"; 
      $mensaje .= "</td>"; 
      $mensaje .= "</tr>";  
      $mensaje .= "<tr>"; 
      $mensaje .= "<td style='padding:0px;background:#fff;border:1px solid #e1e1e1;border-top:none;border-bottom:none'>";
      $mensaje .= "</td>"; 
      $mensaje .= "</tr>";  
      $mensaje .= "<tr>"; 
      $mensaje .= "<td valign='middle'>";
      $mensaje .= "<div style='background:#373737;border:1px solid #e1e1e1;border-top:none'>"; 
      $mensaje .= "<table width='100%' cellpadding='0' cellspacing='0' border='0' style='padding:0 20px'>"; 
      $mensaje .= "<tbody>"; 
      $mensaje .= "<tr>"; 
      $mensaje .= "<td height='50' valign='middle' align='left' style='font-family:Helvetica,Arial;font-size:11px;color:#8e8e8e'>© Mercurio, 2015</td>"; 
      $mensaje .= "<td height='50' valign='middle' align='right'>"; 
      $mensaje .= "<table>"; 
      $mensaje .= "<tbody>"; 
      $mensaje .= "<tr>"; 
      $mensaje .= "<td>"; 
      $mensaje .= "<a href='{$mercurio02->getPagfac()}' target='_blank'><img style='display:block;border:none' src='http://www.mercurio.net.co/Portal/public/img/portal/newsletter-sm-facebook.png' width='20' height='20' title='Facebook' alt='Facebook' class='CToWUd'>"; 
      $mensaje .= "</a>"; 
      $mensaje .= "</td>"; 
      $mensaje .= "<td>"; 
      $mensaje .= "<a href='{$mercurio02->getPagtwi()}' target='_blank'><img style='display:block;border:none' src='http://www.mercurio.net.co/Portal/public/img/portal/newsletter-sm-twitter.png' width='20' height='20' title='Twitter' alt='Twitter' class='CToWUd'></a>"; 
      $mensaje .= "</td>"; 
      $mensaje .= "<td>";
      $mensaje .= "<a href='{$mercurio02->getPagyou()}' target='_blank'> <img style='display:block;border:none' src='http://www.mercurio.net.co/Portal/public/img/portal/newsletter-sm-gplus.png' width='20' height='20' title='Google +' alt='Google +' class='CToWUd'></a>"; 
      $mensaje .= "</td>"; 
      $mensaje .= "</tr>"; 
      $mensaje .= "</tbody>"; 
      $mensaje .= "</table>"; 
      $mensaje .= "</td>"; 
      $mensaje .= "</tbody>"; 
      $mensaje .= "</table>"; 
      $mensaje .= "</div>"; 
      $mensaje .= "</td>"; 
      $mensaje .= "</tr>"; 
      $mensaje .= "</tbody>"; 
      $mensaje .= "</table>"; 
      $mensaje .= "</td>"; 
      $mensaje .= "</tr>"; 
      $mensaje .= "</tbody>";
      $mensaje .= "</table>";
      $mensaje .= "</div>";

      Core::importFromLibrary("Swift", "Swift.php");
      Core::importFromLibrary("Swift", "Swift/Connection/SMTP.php");
      //try
      //{
          $smtp = new Swift_Connection_SMTP("mail.syseu.com", Swift_Connection_SMTP::PORT_SECURE, Swift_Connection_SMTP::ENC_TLS);
          $smtp->setUsername("mercurio@syseu.com");
          $smtp->setPassword("control");
          $message = new Swift_Message($asunto);
          if($file!=""){
              if(!is_array($file))
                  $file = array($file);
              foreach($file as $mfile){
                  $swiftfile = new Swift_File($mfile);
                  $attachment = new Swift_Message_Attachment($swiftfile);
                  $message->attach($attachment);
              }
          }
          $bodyMessage = new Swift_Message_Part(utf8_decode($mensaje), "text/html");
          $bodyMessage->setCharset("UTF-8");
          $message->attach($bodyMessage);
          $swift = new Swift($smtp);
          $email = new Swift_RecipientList();
          $email->addTo($email_user, $descripcion);
          //$swift->send($message, $email, new Swift_Address("mercurio@syseu.com"));
          $swift->send($message, $email, new Swift_Address("notificacionesweb@comfamiliarhuila.com"));
          return true;
      //}catch(Exception $e){
        //$this->setLogger($e->getMessages());
        //return false;
      //}
    
      $action = Router::getAction();
      $mercurio25 = $this->Mercurio25->findfirst("controlador = '$controller' and accion = '$action'");
      if($mercurio25==false)return false;
      $modelos = array("mercurio21");
      $Transaccion = $this->startTrans($modelos);
      $today = new Date();
      $mercurio21 = new Mercurio21();
      $mercurio21->setTransaction($Transaccion);
      $mercurio21->setId(0);
      $mercurio21->setTipo(Session::getDATA('tipo'));
      $mercurio21->setCodcaj(Session::getDATA('codcaj'));
      $mercurio21->setCoddoc(Session::getDATA('coddoc'));
      $mercurio21->setDocumento(Session::getDATA('documento'));
      $mercurio21->setIp($_SERVER["REMOTE_ADDR"]);
      $mercurio21->setFecha($today->getUsingFormatDefault());
      $mercurio21->setHora(date("H:i"));
      $mercurio21->setControlador($controller);
      $mercurio21->setAccion($action);
      $mercurio21->setValor($mercurio25->getValor());
      $mercurio21->setNota(Session::getDATA('nota_audit'));
      $mercurio21->save();
      if(!$mercurio21->save()){
        $this->setLogger($mercurio21->getMessages());
        return false;
      }
      Session::setData('nota_audit',"");
      if($commit==true){
          $Transaccion->commit();
      }
      return $mercurio21->getId();
    }

/*

    public function enviarCorreo($descripcion='',$nombre='',$email_user='',$asunto='',$msg='',$file=''){
      $mercurio02 = $this->Mercurio02->findFirst("codcaj = '".Session::getDATA('codcaj')."'");
      //Nuevo formato de Correo
      $mensaje  = "";
      $mensaje .= "<div style='padding:0px;margin:0px'>";
      $mensaje .= "<table width='100%' bgcolor='#EEEEEE' cellpadding='0' cellspacing='0' border='0'>";
      $mensaje .= "<tbody>";
      $mensaje .= "<tr>";
      $mensaje .= "<td align='center' style='font-family:Helvetica,Arial;padding:0px'>";
      $mensaje .= "<table width='100%' cellpadding='0' cellspacing='0' border='0' style='width:100%;max-width:690px'>";
      $mensaje .= "<tbody>";
      $mensaje .= "<tr>";
      $mensaje .= "<td style='padding:0px'>";
      $mensaje .= "<table width='100%' cellpadding='0' cellspacing='0' border='0'>";
      $mensaje .= "<tbody>";
      $mensaje .= "<tr>";
      $mensaje .= "<td align='center'>"; 
      $mensaje .= "<img style='display:block;border:none' src='http://www.comfamiliarhuila.mercurio.net.co/Portal/public/img/comfamiliar-logo.jpg' width='40%' height='60px' title='Comfamiliarhulia' alt='Comfamiliarhuila' class='CToWUd'>";
      $mensaje .= "</td>";
      $mensaje .= "</tr>";
      $mensaje .= "<tr>"; 
      $mensaje .= "<td bgcolor='#FFFFFF' style='padding:20px 20px 0;border:1px solid #e1e1e1;border-top:none;border-bottom:none'>"; 
      $mensaje .= "<div style='font-family:Helvetica,Arial;font-size:22px;line-height:32px;color:#00638a'>&nbsp;</div>"; 
      $mensaje .= "</td>"; 
      $mensaje .= "</tr>"; 
      $mensaje .= "<tr>"; 
      $mensaje .= "<td bgcolor='#FFFFFF' style='padding:15px 20px 25px;border:1px solid #e1e1e1;border-top:none;border-bottom:none'>"; 
      $mensaje .= "<div style='font-family:Helvetica,Arial;font-size:14px;font-style:italic;color:#2B2727'>".$msg."</div>"; 
      $mensaje .= "</td>"; 
      $mensaje .= "</tr>"; 
      $mensaje .= "</tbody>"; 
      $mensaje .= "</table>"; 
      $mensaje .= "</td>"; 
      $mensaje .= "</tr>";  
      $mensaje .= "<tr>"; 
      $mensaje .= "<td style='padding:0px;background:#fff;border:1px solid #e1e1e1;border-top:none;border-bottom:none'>";
      $mensaje .= "</td>"; 
      $mensaje .= "</tr>";  
      $mensaje .= "<tr>"; 
      $mensaje .= "<td valign='middle' style='padding:20px;background:#f5f5f5;border:1px solid #e1e1e1;border-top:1px solid #eee;border-bottom:1px solid #eeeeee;font-family:Helvetica,Arial;font-size:14px;font-style:italic;line-height:20px;color:#787878'>{$mercurio02->getRazsoc()} <br/>Direccion: {$mercurio02->getDireccion()} <br/>Email: {$mercurio02->getEmail()} <br/>Telefono: {$mercurio02->getTelefono()} <br/>Website: <a style='font-family:Helvetica,Arial;font-size:14px;line-height:20px;color:#478eae;text-decoration:none' href='{$mercurio02->getPagweb()}' target='_blank'>{$mercurio02->getPagweb()}</a>.";
      $mensaje .= "</td>";
      $mensaje .= "</tr>"; 
      $mensaje .= "<tr>"; 
      $mensaje .= "<td valign='middle'>";
      $mensaje .= "<div style='background:#373737;border:1px solid #e1e1e1;border-top:none'>"; 
      $mensaje .= "<table width='100%' cellpadding='0' cellspacing='0' border='0' style='padding:0 20px'>"; 
      $mensaje .= "<tbody>"; 
      $mensaje .= "<tr>"; 
      $mensaje .= "<td height='50' valign='middle' align='left' style='font-family:Helvetica,Arial;font-size:11px;color:#8e8e8e'>© Mercurio, 2015</td>"; 
      $mensaje .= "<td height='50' valign='middle' align='right'>"; 
      $mensaje .= "<table>"; 
      $mensaje .= "<tbody>"; 
      $mensaje .= "<tr>"; 
      $mensaje .= "<td>"; 
      $mensaje .= "<a href='{$mercurio02->getPagfac()}' target='_blank'><img style='display:block;border:none' src='http://www.mercurio.net.co/Portal/public/img/portal/newsletter-sm-facebook.png' width='20' height='20' title='Facebook' alt='Facebook' class='CToWUd'>"; 
      $mensaje .= "</a>"; 
      $mensaje .= "</td>"; 
      $mensaje .= "<td>"; 
      $mensaje .= "<a href='{$mercurio02->getPagtwi()}' target='_blank'><img style='display:block;border:none' src='http://www.mercurio.net.co/Portal/public/img/portal/newsletter-sm-twitter.png' width='20' height='20' title='Twitter' alt='Twitter' class='CToWUd'></a>"; 
      $mensaje .= "</td>"; 
      $mensaje .= "<td>";
      $mensaje .= "<a href='{$mercurio02->getPagyou()}' target='_blank'> <img style='display:block;border:none' src='http://www.mercurio.net.co/Portal/public/img/portal/newsletter-sm-gplus.png' width='20' height='20' title='Google +' alt='Google +' class='CToWUd'></a>"; 
      $mensaje .= "</td>"; 
      $mensaje .= "</tr>"; 
      $mensaje .= "</tbody>"; 
      $mensaje .= "</table>"; 
      $mensaje .= "</td>"; 
      $mensaje .= "</tbody>"; 
      $mensaje .= "</table>"; 
      $mensaje .= "</div>"; 
      $mensaje .= "</td>"; 
      $mensaje .= "</tr>"; 
      $mensaje .= "</tbody>"; 
      $mensaje .= "</table>"; 
      $mensaje .= "</td>"; 
      $mensaje .= "</tr>"; 
      $mensaje .= "</tbody>";
      $mensaje .= "</table>";
      $mensaje .= "</div>";

      Core::importFromLibrary("Swift", "Swift.php");
      Core::importFromLibrary("Swift", "Swift/Connection/SMTP.php");
      //try
      //{
          $smtp = new Swift_Connection_SMTP("mail.syseu.com", Swift_Connection_SMTP::PORT_SECURE, Swift_Connection_SMTP::ENC_TLS);
          $smtp->setUsername("mercurio@syseu.com");
          $smtp->setPassword("control");
          $message = new Swift_Message($asunto);
          if($file!=""){
              if(!is_array($file))
                  $file = array($file);
              foreach($file as $mfile){
                  $swiftfile = new Swift_File($mfile);
                  $attachment = new Swift_Message_Attachment($swiftfile);
                  $message->attach($attachment);
              }
          }
          $bodyMessage = new Swift_Message_Part(utf8_decode($mensaje), "text/html");
          $bodyMessage->setCharset("UTF-8");
          $message->attach($bodyMessage);
          $swift = new Swift($smtp);
          $email = new Swift_RecipientList();
          $email->addTo($email_user, $descripcion);
          $swift->send($message, $email, new Swift_Address("mercurio@syseu.com"));
          return true;
      //}catch(Exception $e){
        //$this->setLogger($e->getMessages());
        //return false;
      //}
    }
*/
    
    public function registroOpcion($commit=false){
      $controller = Router::getController();
      $action = Router::getAction();
      $mercurio25 = $this->Mercurio25->findfirst("controlador = '$controller' and accion = '$action'");
      if($mercurio25==false)return false;
      $modelos = array("mercurio21");
      $Transaccion = $this->startTrans($modelos);
      $today = new Date();
      $mercurio21 = new Mercurio21();
      $mercurio21->setTransaction($Transaccion);
      $mercurio21->setId(0);
      $mercurio21->setTipo(Session::getDATA('tipo'));
      $mercurio21->setCodcaj(Session::getDATA('codcaj'));
      $mercurio21->setCoddoc(Session::getDATA('coddoc'));
      $mercurio21->setDocumento(Session::getDATA('documento'));
      $mercurio21->setIp($_SERVER["REMOTE_ADDR"]);
      $mercurio21->setFecha($today->getUsingFormatDefault());
      $mercurio21->setHora(date("H:i"));
      $mercurio21->setControlador($controller);
      $mercurio21->setAccion($action);
      $mercurio21->setValor($mercurio25->getValor());
      $mercurio21->setNota(Session::getDATA('nota_audit'));
      $mercurio21->save();
      if(!$mercurio21->save()){
        $this->setLogger($mercurio21->getMessages());
        return false;
      }
      Session::setData('nota_audit',"");
      if($commit==true){
          $Transaccion->commit();
      }
      return $mercurio21->getId();
    }


    public function asignarFuncionario($codare,$codope){
        if(SESSION::getDATA('tipo')=="" || SESSION::getDATA('documento')==""){
            return false;
        }
        $modelos = array("mercurio48");
        $Transaccion = $this->startTrans($modelos);
        $flag=false;
        $intentos=1;
        if(Session::getData('agencia')==""){
            $this->redirect("login/index/La sesion a expirado");
            return false;
        }
        do{
            $agencia = "";
            switch(Session::getData('agencia')){
                case 1:
                    $agencia = "NIVA";
                    break;
                case 2:
                    $agencia = "GRZN";
                    break;
                case 3:
                    $agencia = "PITL";
                    break;
                case 4:
                    $agencia = "PLTA";
                    break;
            }
            if($agencia == ""){
                $agencia = "NIVA";
            }
            $mercurio48 = $this->Mercurio48->findFirst("codare='$codare' AND codope='$codope' and tipfun = '".$agencia."'");
            //$mercurio11 = $this->Mercurio11->findFirst("codare='$codare' AND codope='$codope' ");
            $mercurio44 = $this->Mercurio44->findFirst("codare='$codare' AND codope='$codope' AND orden='{$mercurio48->getOrden()}' and usuario in (select usuario from gener02 where tipfun='".$agencia."' and estado='A')");
            //$mercurio44 = $this->Mercurio44->findFirst("codare='$codare' AND codope='$codope' AND orden='{$mercurio11->getOrden()}' ");
            if($mercurio44==false){
                //$this->Mercurio11->updateAll("orden=orden+1","conditions: codare='$codare' AND codope='$codope'");
                $this->Mercurio48->updateAll("orden=orden+1","conditions: codare='$codare' AND codope='$codope' and tipfun='".$agencia."'");
                $intentos++;
                if($intentos==9) {
                    $orden = $this->Mercurio44->minimum("orden","conditions: codare='$codare' AND codope='$codope' and usuario in (select usuario from gener02 where tipfun='".$agencia."' and estado='A')");
                    //$orden = $this->Mercurio44->minimum("orden","conditions: codare='$codare' AND codope='$codope' ");
                    $this->Mercurio48->updateAll("orden=$orden","conditions: codare='$codare' AND codope='$codope' and tipfun='".$agencia."'");
                    $mercurio44 = $this->Mercurio44->findFirst("codare='$codare' AND codope='$codope' AND orden='{$orden}' and usuario in (select usuario from gener02 where tipfun='".$agencia."' and estado='A')");
                    //$this->Mercurio11->updateAll("orden=$orden","conditions: codare='$codare' AND codope='$codope'");
                }
            }else{
                $flag=true;
                $this->Mercurio48->updateAll("orden=orden+1","conditions: codare='$codare' AND codope='$codope' and tipfun='".$agencia."'");
                //$this->Mercurio11->updateAll("orden=orden+1","conditions: codare='$codare' AND codope='$codope'");
            }
        }while($flag==false && $intentos<10);
        return $mercurio44->getUsuario();
    }

    public function showPaginate($paginate){
        $html = "";
        $html .= "<ul class='pagination pagination-sm'> ";
        $html .= "<li onclick=\"buscar(this);\" pagina='{$paginate->first}'><a><<</a></li>";
        $html .= "<li onclick=\"buscar(this);\" pagina='{$paginate->before}'><a><</a></li>";
        for($i=$paginate->current-5;$i<$paginate->current;$i++){
            if($i<$paginate->first)continue;
            $html .= "<li onclick=\"buscar(this);\"><a>".$i."</a></li>";
        }
        for($i=$paginate->current;$i<=($paginate->current+5);$i++){
            $class = "";
            if($i==$paginate->current)$class = "active ";
            if($i>$paginate->last)continue;
            $html .= "<li class='$class' onclick=\"buscar(this);\"><a>".$i."</a></li>";
        }
        $html .= "<li onclick=\"buscar(this);\" pagina='{$paginate->next}'><a>></a></li>";
        $html .= "<li onclick=\"buscar(this);\" pagina='{$paginate->last}'><a>>></a></li>";
        $html .= "</ul>";
        return $html;
    }
}

