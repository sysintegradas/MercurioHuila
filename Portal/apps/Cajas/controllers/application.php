<?php
Core::importFromLibrary('Excel', 'Main.php');

class ControllerBase {

    private $log;

	public function init(){
		//Core::info();
        Router::routeTo("controller: desktop");
        Core::importFromActiveApp('library/Menu/Menu.php');
	}

    public function beforeFilter(){
      Session::setDATA("codcaj","CCF032");
        $controller = Router::getController();
        if($controller!="login"){
            if(!Auth::getActiveIdentity()){
                $this->routeto("controller: login","action: index");
                return false;
            }
        }
        if($controller=="desktop" || $controller=="login" || $controller=="acudientes")return true;
        $acl = new Acl('Model', 'className: Gener28');
        $codapl = $this->Mercurio01->findBySql("SELECT codapl FROM mercurio01 limit 1")->getCodapl();
        Session::setData("codapl",$codapl);
        $user = Auth::getActiveIdentity();
        $role = $user['tipfun'];
            SESSION::setData('usuario',$user['usuario']);
        $action = Router::getAction();
        if($acl->isAllowed($role, $controller, $action)==false){
            Message::warning("El usuario no tiene permisos sobre esta opcion.");
            $this->routeTo("controller: desktop","action: index");
            return false;
        }
    }

    public function createReport($model,$_fields,$query='1=1',$title='Reporte',$format='pdf'){
        $table = ucfirst($model);
        $table = new $table();
        $format = strtolower($format);
        if($format=='pdf'){
            $report = new UserReportPdf($title,$_fields);
            $ext = ".pdf";
        }elseif($format=='excel'){
            $report = new UserReportExcel($title,$_fields);
            $ext = ".xls";
        }
        $report->startReport();
        foreach($table->find($query) as $mtable){
            foreach($_fields as $key=>$value){
                $get = "get".ucFirst($key)."Detalle";
                if(method_exists($mtable,$get))
                    $report->Put($key,$mtable->$get());
                else
                    $report->Put($key,$mtable->readAttribute($key));
            }
            $report->OutputToReport();
        }
        $file = "public/temp/reportes/reportes";
        echo $report->FinishReport($file,"F");
        $this->setResponse('view');
        header("location: ".Core::getInstancePath()."/{$file}{$ext}");
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
        $columns = array('#','Documento','Nombre','Fecha','Hora','Accion','Estado');
        $excel->setColumn(0,0,10);
        $excel->setColumn(1,1,20);
        $excel->setColumn(2,2,50);
        $excel->setColumn(3,4,20);
        $excel->setColumn(5,5,40);
        $excel->setColumn(6,6,25);
        $i = 0;
        $j = 2;
        foreach($columns as $column){
            $value = ucfirst($column);
            $excel->write($j, $i++, $value, $column_title);
        }
        $j++;
        $cond = "AND (exists (select * from  mercurio32 where mercurio32.log = mercurio21.id) ";
        $cond .= "OR exists (select * from  mercurio31 where mercurio31.log = mercurio21.id) ";
        $cond .= "OR exists (select * from  mercurio30 where mercurio30.log = mercurio21.id) ";
        $cond .= "OR exists (select * from  mercurio33 where mercurio33.log = mercurio21.id) ";
        $cond .= "OR exists (select * from  mercurio34 where mercurio34.log = mercurio21.id) ";
        $cond .= "OR exists (select * from  mercurio43 where mercurio43.log = mercurio21.id) ";
        $cond .= "OR exists (select * from  mercurio45 where mercurio45.log = mercurio21.id)) ";
        $mercurio21 = $this->Mercurio21->find("$query $cond","order: mercurio21.fecha desc");
        if($mercurio21!=false){
            $total=1;
            foreach($mercurio21 as $mmercurio21){
                    $i = 0;
                    $fecha = $mmercurio21->getFecha()->getUsingFormatDefault();
                    $excel->write($j, $i++,$total++, $column_style);
                    $excel->write($j, $i++,$mmercurio21->getDocumento(), $column_style);
                    $mercurio07 = $this->Mercurio07->findFirst("documento = {$mmercurio21->getDocumento()}");
                    $excel->write($j, $i++,$mercurio07->getNombre(), $column_style);
                    $excel->write($j, $i++,$mmercurio21->getFecha(), $column_style);
                    $excel->write($j, $i++,$mmercurio21->getHora(), $column_style);
                    $excel->write($j, $i++,$mmercurio21->getAccionDetalle(), $column_style);
                  switch($mmercurio21->getAccion()){
                      case "addempresa":
                            $mtabla = $this->Mercurio30->findFirst("log = '{$mmercurio21->getId()}'");
                          break;
                      case "addtrabajador":
                            $mtabla = $this->Mercurio31->findFirst("log = '{$mmercurio21->getId()}'");
                          break;
                      case "actdat":
                            $mtabla = $this->Mercurio33->findFirst("log = '{$mmercurio21->getId()}'");
                          break;
                      case "cambioDatosPrincipales": 
                            $mtabla = $this->Mercurio43->findFirst("log = '{$mmercurio21->getId()}'");
                          break;
                      case "cargueCertificados":
                            $mtabla = $this->Mercurio45->findFirst("log = '{$mmercurio21->getId()}'");
                          break;
                      case "ingben":
                            $mtabla = $this->Mercurio34->findFirst("log = '{$mmercurio21->getId()}'");
                          break;
                      case "ingcon":
                            $mtabla = $this->Mercurio32->findFirst("log = '{$mmercurio21->getId()}'");
                          break;
                  }
                    $excel->write($j, $i++,$mtabla->getEstadoDetalle(), $column_style);
                    $j++;
            }
        }
        $fecha = new Date();
        $excels->close();
        header("location: ".Core::getInstancePath()."/{$file}");
    }

    public function doUpper($texto){
        $arr = preg_split("/\ /",$texto);
        $mtexto = "";
        foreach($arr as $marr){
            $mtexto .=ucfirst(strtolower($marr))." ";
        }
        $mtexto = trim($mtexto);
        return $mtexto;
    }

    public function paginate($tabla='',$query='',$order='',$page='',$pagina='', $type_order="ASC"){
        $order .= " ".$type_order;
        $tabla = ucFirst($tabla);
        if($page=='next'){
            $response = Tag::paginate($this->$tabla->find($query,"order: $order"),$pagina,10);
        } elseif ($page=='prev'){
            $response = Tag::paginate($this->$tabla->find($query,"order: $order"),$pagina,10);
        } elseif ($page=='first'){
            $response = Tag::paginate($this->$tabla->find($query,"order: $order"),1,10);
        } elseif ($page=='last'){
            $response = Tag::paginate($this->$tabla->find($query,"order: $order"),$pagina,10);
        } elseif (eregi("p",$page)){
            $response = Tag::paginate($this->$tabla->find($query,"order: $order"),eregi_replace("p","",$page),10);
        }
        return $response;
    }
    public function paginate2($tabla='',$query='',$order='',$page='',$pagina=''){
        $tabla = ucFirst($tabla);
        if($page=='next'){
            $response = Tag::paginate($this->$tabla->findAllBySql($query,"order: $order"),$pagina,10);
        } elseif ($page=='prev'){
            $response = Tag::paginate($this->$tabla->findAllBySql($query,"order: $order"),$pagina,10);
        } elseif ($page=='first'){
            $response = Tag::paginate($this->$tabla->findAllBySql($query,"order: $order"),1,10);
        } elseif ($page=='last'){
            $response = Tag::paginate($this->$tabla->findAllBySql($query,"order: $order"),$pagina,10);
        } elseif (eregi("p",$page)){
            $response = Tag::paginate($this->$tabla->findAllBySql($query,"order: $order"),eregi_replace("p","",$page),10);
        }
        return $response;
    }

    public function navegacion($table,$controller){
        $html = "<table id='navegacion'>";
        $html .="<tr>";
        $html .="<td>".Tag::linkToRemote(Tag::image("first.png","border: 0"),"action: $controller/buscar/first/1","update: consulta")."</td>";
        $html .="<td>";
        if($table->before){
            $html.=Tag::linkToRemote(Tag::image("prev.png","border: 0"),"action: $controller/buscar/prev/".$table->before,"update: consulta");
        } else {
            $html.=Tag::image("prev_des.png","border: 0","style: cursor: pointer;");
        }
        $html .="</td>";
        $html .="<td valign='middle'>";
        $html .="<input id='num_pag' value='{$table->current}' size='3' maxlength='3' style='text-align: right;' onchange=\"irPag('$controller','{$table->total_pages}')\" name='num_pag' onkeydown='NumericField.maskNum(event)' />";
        $html .="<b style='font-size: 12px;'>/ ".$table->total_pages."</b>";
        $html .="</td>";
        $html .="<td>";
        if($table->next){
            $html .=Tag::linkToRemote(Tag::image("next.png","border: 0"),"action: $controller/buscar/next/".$table->next,"update: consulta");
        } else {
            $html .=Tag::image("next_des.png","border: 0","style: cursor: pointer;");
        }
        $html .="</td>";
        $html .="<td>";
        $html .=Tag::linkToRemote(Tag::image("last.png","border: 0"),"action: $controller/buscar/last/".$table->total_pages,"update: consulta");
        $html .="</td>";
        $html .="</tr>";
        $html .="</table>";
        return $html;
    }
    
    public function webService($funcion,$param){
        try{
            date_default_timezone_set('America/Bogota');
            $param_user= array("appId"=>"5664c5165b1e11bc56416d9e0260eae4","appPwd"=>"fec1de72b37939cb649179a046647b7c");
            $parametros = array_merge($param_user,$param);
            //$cliente = new SoapClient("http://www.comfamiliarenlinea.com/WebServicesDMZCapaExt/Services/WSMercurio.svc?wsdl", array('cache_wsdl' => 0));
            $cliente = new SoapClient("http://www.comfamiliarenlinea.com/WebServicesDMZCapaExtPrueba/Services/WSMercurio.svc?wsdl", array('cache_wsdl' => 0));
            $funcion = $funcion;
            $return = $cliente->$funcion($parametros);
            $return = $this->object2array($return);
            return $return;
        }  catch (SoapFault $e) {  
            Debug::addVariable("a",$e->faultstring);
            throw new DebugException(0); 
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
        $mensaje .= "<td>"; 
        $mensaje .= "<img style='display:block;border:none' src='http://www.mercurio.net.co/Portal/public/img/portal/headermail.jpg' width='100%' height='' title='Comfaguajira' alt='Comfaguajira' class='CToWUd'>";
        $mensaje .= "</td>";
        $mensaje .= "</tr>";
        $mensaje .= "<tr>"; 
        $mensaje .= "<td bgcolor='#FFFFFF' style='padding:20px 20px 0;border:1px solid #e1e1e1;border-top:none;border-bottom:none'>"; 
        $mensaje .= "<div style='font-family:Helvetica,Arial;font-size:22px;line-height:32px;color:#00638a'>&nbsp;</div>"; 
        $mensaje .= "</td>"; 
        $mensaje .= "</tr>"; 
        $mensaje .= "<tr>"; 
        $mensaje .= "<td bgcolor='#FFFFFF' style='padding:15px 20px 25px;border:1px solid #e1e1e1;border-top:none;border-bottom:none'>"; 
        $mensaje .= "<div style='font-family:Helvetica,Arial;font-size:14px;font-style:italic;color:#bababa'>".$msg."</div>"; 
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
        
        try{
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

        }catch(Exception $e){
            Debug::addVariable("a",print_r($e,true));
            throw new DebugException(0);
            $this->setLogger($e->getMessages());
            return false;
        }

    }*/


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

}

