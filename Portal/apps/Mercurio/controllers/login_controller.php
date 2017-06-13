<?php

class LoginController extends ApplicationController {

    private $contadorimg = 1;
    private $imagenes = array();

    public function initialize(){
        //$this->setTemplateAfter('escritorio');
        $this->setPersistance(true);
        $this->imagenes = array();
        $this->imagenes[0] = "head_sys.jpg";
        $mercurio26 = $this->Mercurio26->findAllBySql("SELECT * from mercurio26 WHERE tipo = '2' AND codcaj = '".Session::getDATA('codcaj')."' AND estado='A'");
        $count=1;
        foreach($mercurio26 as $mmercurio26){
          $this->imagenes[$count++] = $mmercurio26->getNomimg();
        }
    }

    public function indexAction($msg=''){
        if($msg!='') echo Message::info($msg);
        $html = "";
        $mercurio02 = $this->Mercurio02->findBySql("SELECT * FROM mercurio02 WHERE codcaj = '".Session::getDATA('codcaj')."'");
        SESSION::setData("nomcaj", $mercurio02->getRazsoc());
        $html .="<div><a href='{$mercurio02->getPagweb()}' target='_blank'> ".Tag::image("portal/{$mercurio02->getNomarc()}","height: 90px","width: 240px")."</a></div>";
        $this->setParamToView("html", $html);
        $this->setParamToView("mercurio02", $mercurio02);
        /*
           $banner = "";
           for($i =1; $i < count($this->imagenes); $i++){
           $banner.= '<div class="puntoImg" onClick="slideLogin(this)" numeroBanner="'.$i.'"></div>';
           }
         */
        $banner = "";
        for($i =1; $i < count($this->imagenes); $i++){
            if($i==1){
                $clase = "active";
            }else{
                $clase = "";
            }
            $banner .= "<div class='item {$clase}'>".Tag::image("src: ../../../Portal/public/img/portal/{$this->imagenes[$i]}")."</div>";
        }
        $this->setParamToView("banner", $banner);
        $catcla = "";
        $mercurio23 = $this->Mercurio23->findAllBySql("SELECT codcat,detalle FROM mercurio23");
        foreach ($mercurio23 as $mmercurio23) {
            $catcla .="<div class='botCat' onClick=\"clasificadoBoton('{$mmercurio23->getCodcat()}')\"> <span class='textbotcat'>".$mmercurio23->getDetalle()."</span></div>";
        }
        $this->setParamToView('catcla',$catcla);
        $puntos = "";
        $count = 1;
        $mercurio26 = $this->Mercurio26->findAllBySql("SELECT * from mercurio26 WHERE  tipo = '2' AND estado <> 'I' AND codcaj = '".Session::getDATA('codcaj')."' ");
        foreach($mercurio26 as $mmercurio26){
            $puntos .= "<div class='puntoImg' onClick='slideLogin(this)' numeroBanner='$count'></div>";
            $count++;
        }
        $this->setParamToView('puntos',$puntos);
        $noticias = "";
        $mercurio29 = $this->Mercurio29->findAllBySql("SELECT * from mercurio29 WHERE codcaj = '".Session::getDATA('codcaj')."' AND estado = 'A'");
        foreach($mercurio29 as $mmercurio29){
            $noticias .= "<li onclick=\"newWindow('{$mmercurio29->getNumero()}','{$mmercurio29->getTitulo()}','width=300, height=400');\">{$mmercurio29->getTitulo()}</li>";
        }
        $this->setParamToView('noticias',$noticias);
        $this->setParamToView("coddoc", array('5'=>'NIT','2'=>'T.I','1'=>'C.C','4'=>'C.E','3'=>'P.S'));
        //$this->setParamToView("coddoc", array('2'=>'TARJETA DE IDENTIDAD','1'=>'CEDULA DE CIUDADANIA','4'=>'CEDULA DE EXTRANJERIA','3'=>'PASAPORTE'));
    }

    public function autenticarAction() {
        try{
            /*
               $cod_ = $this->getPostParam("cap","digits");                                                     
               if($cod_!=$_SESSION['tmptxt']){                                                                  
               Flash::error("Código Incorrecto");
               return $this->routeTo("action: index");
               }                                                                                                
             */
            $this->setResponse("ajax");  
            $login = $this->getPostParam("user","striptags");
            $coddoc = $this->getPostParam("coddoc","striptags");
            $tipo = $this->getPostParam("tipo","striptags");
            $clave = $this->getPostParam("password");
            $coddoc = $this->getPostParam("coddoc");
            $filter = new Filter();                                                                          
            $clave = $filter->apply($clave,array("addslaches","alpha","extraspaces","striptags"));
            $login = $filter->apply($login,array("digits"));
            $mclave = '';
            for($i=0;$i<strlen($clave);$i++){                                                                
                if($i%2!=0){                                                                                 
                    $x=6;                                                                                    
                }else{                                                                                       
                    $x=-4;                                                                                   
                }                                                                                            
                $mclave .= chr(ord(substr($clave,$i,1)) + $x + 5);                                           
            }
            $result= parent::webService('autenticar',array("tipo"=>$tipo,"documento"=>$login, 'coddoc'=>$coddoc));
            if($tipo!="P" && $result==false){
                $response = parent::errorFunc("Debe ser un Usuario Registrado");
                return $this->renderText(json_encode($response));
                //Flash::error("Documento No Existe");
                //$response = parent::errorFunc("Documento No Existe");
                //return $this->renderText(json_encode($response));
            }
            if($tipo=="P" && $result!=false){
                $response = parent::errorFunc("El Documento Existe En la Caja,no entre como particular");
                return $this->renderText(json_encode($response));
                //Flash::error("El Documento Existe En la Caja,no entre como particular");
                //return $this->routeTo("action: index");
            }
            $tipafi ="Afiliado";
            //if($result[0]['tipafi'] == "A")$tipafi="Afiliado";
            SESSION::setData("nombre", UTF8_DECODE($result[0]['nombre']));
            SESSION::setData("codcat", $result[0]['codcat']);
            SESSION::setData("estado", $result[0]['estado']);
            SESSION::setData("codest", $result[0]['codest']);
            //SESSION::setData("clasoc", $result[0]['clasoc']);
            if(isset($result[0]['telefono'])){
                SESSION::setData("telefono", $result[0]['telefono']);
            }
            $mercurio02 = $this->Mercurio02->findBySql("SELECT razsoc FROM mercurio02 WHERE codcaj = '".Session::getDATA('codcaj')."'");
            SESSION::setData("nomcaj", $mercurio02->getRazsoc());
            SESSION::setData("tipo", $tipo);
            SESSION::setData("tipafi", $tipafi);
            SESSION::setData("documento", $login);
            SESSION::setData("coddoc", $coddoc);
            $l = $this->Mercurio07->count("*","conditions: codcaj = '".Session::getDATA('codcaj')."' AND tipo='$tipo' AND documento = '$login' AND coddoc = '$coddoc'");
            if($l==0){
                $response = parent::errorFunc("Usuario No Registrado");
                return $this->renderText(json_encode($response));
                //Flash::error("Usuario No Registrado");
                //return $this->routeTo("action: index");
            }
            //$mercurio07 = $this->Mercurio07->findFirst("codcaj = '".Session::getDATA('codcaj')."' AND tipo='$tipo' AND documento = '$login' AND clave='$mclave'  AND coddoc = '$coddoc'");
            $mercurio07 = $this->Mercurio07->findFirst("codcaj = '".Session::getDATA('codcaj')."' AND tipo='$tipo' AND documento = '$login'  AND coddoc = '$coddoc'");
            if($mercurio07==false){
                $response = parent::errorFunc("Contraseña incorrecta");
                return $this->renderText(json_encode($response));
                //Flash::error("Usuario/Clave Incorrectos");
                //return $this->routeTo("action: index");
            }
            $l = $this->Mercurio22->count("*","conditions: codcaj = '".Session::getDATA('codcaj')."' AND tipo='$tipo' AND documento = '$login' AND ip='{$_SERVER["REMOTE_ADDR"]}'  AND coddoc = '$coddoc'");
            if($l==0){
                //$response = parent::errorFunc("Ip no registrada");
                //return $this->renderText(json_encode($response));
            }
            $today = new Date();
            if(Date::compareDates($today, $mercurio07->getFeccla())>=0){
                //return $this->redirect("opciones/claveVencida_view");
            }
            $mercurio07 = $this->Mercurio07->findFirst("tipo = '{$tipo}' AND documento = '{$login}'  AND coddoc = '$coddoc'");
            SESSION::setData("agencia", $mercurio07->getAgencia());
            if($mercurio07->getNombre() == NULL || $mercurio07->getNombre() != $result[0]['nombre']){
                $this->Mercurio07->updateAll("nombre='{$result[0]['nombre']}'","conditions: tipo = '{$tipo}' AND documento = '{$login}'  AND coddoc = '$coddoc'");
            }
            $response = parent::successFunc("Bienvenido","principal/index");
            return $this->renderText(json_encode($response));
        }catch (DbException $e){
            $response = parent::errorFunc("Se Presento algo Vuelva a Intentarlo","principal/index");
            return $this->renderText(json_encode($response));
            //Flash::error("Se Presento algo Vuelva a Intentarlo");
            //return $this->routeTo("action: index");
        }
    }

    public function ClsSesionAction(){
        $this->setResponse("ajax");
        SESSION::setData("nombre","");
        SESSION::setData("estado","");
        SESSION::setData("tipo","");
        SESSION::setData("codcat","");
        SESSION::setData("documento","");
        SESSION::setData("coddoc","");
        Auth::destroyIdentity();
        $this->renderText(json_encode(true));
    }

    public function slideAction(){
      $this->setResponse("ajax");
      $numBanner = $this->getPostParam('numBanner');
      if ($numBanner != "")
        $this->contadorimg = $numBanner;

      if (!isset($this->imagenes[$this->contadorimg])){
        $this->contadorimg = 0;
      }
      $responseimg = Tag::image("portal/{$this->imagenes[$this->contadorimg]}");
      $response = array($this->contadorimg,$responseimg);
      $this->contadorimg++;
      $this->renderText(json_encode($response));
    }

    public function empresaAction(){
      $this->setResponse("ajax");
      $empresa = $this->getPostParam('empresa',"addslaches","alpha","extraspaces","striptags");
      $response = "";
      $response .= "<table width='100%' class='tab-res-cla'>";
      $response .= "<thead style='color: #ffffff; border-radius: 5px 5px 0px 0px;'>";
      $response .= "<th style='background: #5B5B5B;'>Razón Social</th>";      
      $response .= "<th style='background: #5B5B5B;'>Dirección</th>";      
      $response .= "<th style='background: #5B5B5B;'>Teléfono</th>";      
      $response .= "<th style='background: #5B5B5B;'>Email</th>";      
      $response .= "<th style='background: #5B5B5B;'>Ciudad</th>";
      $response .= "</thead>";
      $response .= "<tbody>";
      $result = parent::webService("directorioEmpresas", array('razsoc'=>$empresa));
      if($result!=false){
        foreach ($result as $datEmpresa) {
            $response .= "<tr>";
            $response .= "<td>".utf8_decode($datEmpresa['razsoc'])."</td>";
            $response .= "<td>".utf8_decode($datEmpresa['direccion'])."</td>";
            $response .= "<td>".$datEmpresa['telefono']."</td>";
            $response .= "<td>".$datEmpresa['email']."</td>";
            $response .= "<td>".$datEmpresa['codciu']."</td>";
            $response .= "</tr>";
        }
      }
      $response .= "</tbody>";
      $response .= "</table>";
      return $this->renderText(json_encode($response));
    }

    public function clasificadosAction(){
      $this->setResponse("ajax");
      $codcat = $this->getPostParam('codcat',"addslaches","alpha","extraspaces","striptags");
      $clasificado = "";
      $clasificado .="<table width='100%' cellpadding='12' class='tab-res-cla'>";
      $mercurio24 = $this->Mercurio24->findAllBySql("SELECT titulo,descripcion,fecini FROM mercurio24 WHERE codcat = '$codcat' AND estado='A'");
      foreach ($mercurio24 as $mmercurio24) {
        $clasificado .= "<tr>";
        $clasificado .= "<td class='cel-cla'>";
        $clasificado .= "<h3 class='tit-cla'>".$mmercurio24->getTitulo()."</h3>";
        $clasificado .= "<p class='desc-cla'>".$mmercurio24->getDescripcion()."</p>";
        $clasificado .= "<span class='fec-pub-cla'>Publicado: ".$mmercurio24->getFecini()."</span>";
        $clasificado .= "</td>";
        $clasificado .= "</tr>";
      }
      $clasificado .="</table>";
      return $this->renderText(json_encode($clasificado));
    }
    
    public function noticiasAction(){
      $this->setResponse("ajax");
      $id = $this->getPostParam('id',"addslaches","alpha","extraspaces","striptags","numeric");
      $mmercurio29 = $this->Mercurio29->findFirst("numero = '$id'");
      $response = "";
      $response .= "<br><div class='format-notice'>";
      $response .= "<h4>". $mmercurio29->getTitulo()."</h4>";
      $response .= "<p>".$mmercurio29->getDescripcion()."</p>";
      $response .= "</div>";
      
      return $this->renderText(json_encode($response));
    }
    public function salirAction(){ 
        Session::setDATA("codcaj","");
        Session::setDATA("tipo","");
        Session::setDATA("documento","");
        Session::setDATA("coddoc","");
        Auth::destroyIdentity();     
        $this->redirect("login");    
    }
    public function reportAbusoAction(){
        try{
            try{
                $this->setResponse("ajax");
                $nombre = $this->getPostParam("nombrerep");
                $documento = $this->getPostParam("documentorep");
                $empresa = $this->getPostParam("empresarep");
                $asunto = $this->getPostParam("empresarep");
                $correo = $this->getPostParam("correorep");
                $mensaje = $this->getPostParam("mensajerep");
                $asunto = "Reporte de Abuso";
                $msg    = "Asunto: $asunto <br/>
                            Mensaje: $mensaje <br/><br/>
                            Informacion: <br> 
                            Documento: $documento <br/>
                            Nombre: $nombre <br/>
                            Correo: $correo 
                    ";

                parent::enviarCorreo("Reporte de abuso",$nombre,'consultaenlinea@comfamiliarhuila.com',$asunto,$msg,"");
                parent::finishTrans();
                $response = parent::successFunc("El reporte fue enviado",null);
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

    public function sesionAction(){
            $this->setResponse("ajax");  
        if(SESSION::getDATA('tipo')=="" || SESSION::getDATA('documento')==""){
            $response = parent::successFunc("");
        }else{
            $response = parent::errorFunc("");
        }
        return $this->renderText(json_encode($response));
    }
}


?>
