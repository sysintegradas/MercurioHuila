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
        $controller = Router::getController();
        if($controller!="login"){
            if(!Auth::getActiveIdentity()){
                $this->routeto("controller: login","action: index");
                return false;
            }
        }
        if($controller=="desktop" || $controller=="login" || $controller=="acudientes")return true;
        $acl = new Acl('Model', 'className: Gener28');
        $codapl = $this->Mercurio01->findBySql("SELECT codapl FROM mercurio01")->getCodapl();
        Session::setData("codapl",$codapl);
        $user = Auth::getActiveIdentity();
        $role = $user['tipfun'];
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

    public function doUpper($texto){
        $arr = preg_split("/\ /",$texto);
        $mtexto = "";
        foreach($arr as $marr){
            $mtexto .=ucfirst(strtolower($marr))." ";
        }
        $mtexto = trim($mtexto);
        return $mtexto;
    }

    public function paginate($tabla='',$query='',$order='',$page='',$pagina=''){
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

    public function enviarCorreo($asunto,$msg,$memail,$mnombre,$file=""){
        $desema = $this->Mercurio01->findBySql("SELECT desema FROM mercurio01")->getDesema();
        Core::importFromLibrary("Swift", "Swift.php");
        Core::importFromLibrary("Swift", "Swift/Connection/SMTP.php");
        $smtp = new Swift_Connection_SMTP($desema, Swift_Connection_SMTP::PORT_SECURE, Swift_Connection_SMTP::ENC_TLS);
        $smtp->setUsername($mercurio01->getEmail());
        $smtp->setPassword($mercurio01->getPasswd());
        $message = new Swift_Message($asunto);
        if($file!=""){
            $swiftfile = new Swift_File($file);
            $attachment = new Swift_Message_Attachment($swiftfile);
            $message->attach($attachment);
        }
        $bodyMessage = new Swift_Message_Part(utf8_decode($msg), "text/html");
        $bodyMessage->setCharset("UTF-8");
        $message->attach($bodyMessage);
        $swift = new Swift($smtp);
        $email = new Swift_RecipientList();
        $email->addTo($memail, $mnombre);
        $swift->send($message, $email, new Swift_Address($mercurio01->getEmail()));
    }

}

