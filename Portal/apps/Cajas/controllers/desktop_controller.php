<?php

class DesktopController extends ApplicationController {

    public function initialize(){
        $this->setTemplateAfter('escritorio');
        $this->setPersistance(true);
    }

    public function indexAction(){
        $this->setParamToView("titulo","Sericio en Linea");
    }

    /*
     * Function para actualizar la Hora
     */

    public function actTimeAction(){
        $this->setResponse("ajax");
        $response= date("Y-m-d")."<br> ".date("h:i")." ".date("A");
        $this->renderText(json_encode($response));
    }

    /*
     * Function para actualizar el menu
     */

    public function traerMenuAction(){
        $this->setResponse("ajax");
        $id = $this->getPostParam("id");
        $response = Menu::showMenu($id);
        Session::setData("menu",$id);
        $this->renderText(json_encode($response));
    }

    public function traerMenuAcuAction(){
        $this->setResponse("ajax");
        $id = $this->getPostParam("id");
        $response = Menu::showMenuAcu($id);
        Session::setData("menu",$id);
        $this->renderText(json_encode($response));
    }

    /*
     * Function para mostrar las notas
     */

    public function showListNotasAction(){
        $this->setResponse("ajax");
        $numero = $this->getPostParam("numero");
        $act = $this->getPostParam("act");
        $basica03 = $this->Basica03->findAllBySql("SELECT basica03.numero,basica03.titulo FROM basica03 WHERE usuario = '".parent::getActUser("usuario")."'");
        $response['html'] = "";
        $response['id'] = "";
        $i = 0;
        foreach($basica03 as $mbasica03){
            if($act=="false" && $mbasica03->getNumero()==$numero){
                $response['html'] .= "<div id='{$mbasica03->getNumero()}' class='content-nota-listas active' onclick=\"selectNota('{$mbasica03->getNumero()}')\">{$mbasica03->getTitulo()}</div>";
                $response['id'] = $mbasica03->getNumero();
            }elseif($i==0 && $act=="true"){
                $response['html'] .= "<div id='{$mbasica03->getNumero()}' class='content-nota-listas active' onclick=\"selectNota('{$mbasica03->getNumero()}')\">{$mbasica03->getTitulo()}</div>";
                $response['id'] = $mbasica03->getNumero();
            }else
                $response['html'] .= "<div id='{$mbasica03->getNumero()}' class='content-nota-listas' onclick=\"selectNota('{$mbasica03->getNumero()}')\">{$mbasica03->getTitulo()}</div>";
            $i++;
        }
        if($i>=8)
            $response['html'] .= "<div class='content-nota-listas' onclick='newNota();' >&nbsp;</div>";
        for($i=$i;$i<8;$i++){
            $response['html'] .= "<div class='content-nota-listas' onclick='newNota();' >&nbsp;</div>";
        }
        $this->renderText(json_encode($response));
    }

    /*
     * Function para mostrar el mensaje de la notas
     */

    public function showListNotasMsgAction(){
        $this->setResponse("ajax");
        $numero = $this->getPostParam("numero");
        $response="";
        $basica03 = $this->Basica03->findBySql("SELECT basica03.nota FROM basica03 WHERE basica03.numero = '$numero' limit 1");
        if($basica03!=false)
            $response = $basica03->getNota();
        $this->renderText(json_encode($response));
    }

    /*
     * Function para borrar la nota
     */

    public function delListNotasAction(){
        $this->setResponse("ajax");
        $numero = $this->getPostParam("numero");
        $this->Basica03->deleteAll("basica03.numero = '$numero'");
        $this->renderText(json_encode(true));
    }

    /*
     * Function para adicionar la nota
     */

    public function addListNotasAction(){
        try{
            try{
                $this->setResponse("ajax");
                $response = parent::startFunc();
                $nota = strtoupper($this->getPostParam("nota"));
                $numero = $this->getPostParam("numero");
                if($numero==""){
                    $numero = $this->Basica03->maximum("numero","conditions: usuario = '".parent::getActUser()."'")+1;
                }
                $modelos = array("basica03");
                $Transaccion = parent::startTrans($modelos);
                $basica03 = new Basica03();
                $basica03->setTransaction($Transaccion);
                $basica03->setUsuario(parent::getActUser("usuario"));
                $basica03->setNumero($numero);
                $basica03->setTitulo(substr($nota,0,20));
                $basica03->setNota($nota);
                if(!$basica03->save()){
                    parent::setLogger($basica03->getMessages());
                    parent::ErrorTrans();
                }
                parent::finishTrans();
                $response = parent::successFunc("Guardado Exitoso",$numero);
                return $this->renderText(json_encode($response));
            } catch (DbException $e){
                parent::setLogger($e->getMessage());
                parent::ErrorTrans();
            }
        } catch (TransactionFailed $e){
            $response = parent::errorFunc("Error al Guardar/Editar Notas");
            return $this->renderText(json_encode($response));
        }

    }

    /*
     * Function para mostrar los Contactos
     */

    public function showListContactAction(){
        $this->setResponse("ajax");
        $response = "";
        $basica01 = $this->Basica01->find();
        foreach($basica01 as $mbasica01){
            $response .= "<div class='content-contact-title'>{$mbasica01->getDetalle()}</div>";
            $basica02 = $this->Basica02->findAllBySql("SELECT basica02.usuario FROM basica02 WHERE basica02.codare = '{$mbasica01->getCodare()}'");
            foreach($basica02 as $mbasica02){
                $mgener02 = $this->Gener02->findBySql("SELECT gener02.nombre FROM gener02 WHERE gener02.usuario = '{$mbasica02->getUsuario()}' limit 1");
                $response .= "<div class='content-contact-listas' onclick=\"selectContact(this,'{$mbasica02->getUsuario()}')\">{$mgener02->getNombre()}</div>";
            }
        }
        $this->renderText(json_encode($response));
    }

    public function showListContactInfoAction(){
        $this->setResponse("ajax");
        $usuario = $this->getPostParam("usuario");
        $mbasica02 = $this->Basica02->findFirst("usuario = '$usuario'");
        if($mbasica02==false)$mbasica02 = new Basica02();
        $mgener02 = $this->Gener02->findFirst("usuario = '$usuario'");
        $response  = "<div class='contact-title'>{$mgener02->getNombre()}</div>";
        $response .= "<div class='contact-title-info'>Ext</div>";
        $response .= "<div class='contact-info'>{$mbasica02->getExt()}</div>";
        $response .= "<div class='contact-title-info'>Telefono</div>";
        $response .= "<div class='contact-info'>{$mbasica02->getTelefono()}</div>";
        $response .= "<div class='contact-title-info'>Celular</div>";
        $response .= "<div class='contact-info'>{$mbasica02->getCelular()}</div>";
        $response .= "<div class='contact-title-info'>Email</div>";
        $response .= "<div class='contact-info'>{$mbasica02->getEmail()}</div>";
        $response .= "<div class='contact-title-info'>";
        if(parent::getActUser("usuario")!=$usuario)
        $response .="<span id='btn_snd_msg' onclick=\"iniciaChat('$usuario')\">Iniciar Chat</span> </div>";
        $this->renderText(json_encode($response));
    }

    /*
     * Function para mostrar las Ayudas
     */

    public function showListHelpAction(){
        $this->setResponse("ajax");
        $response = "";
        $_menu = Menu::$_menu;
        foreach($_menu as $key => $value){
            $response = self::showMenu($key,$response);
        }
        $this->renderText(json_encode($response));
    }

    public function showMenu($id,$response){
        if(!isset(Menu::$_menu[$id]))return;
        $response .= "<div class='content-help-title'>".Menu::$_menu[$id]['title']."</div>";
        foreach(Menu::$_menu[$id] as $key => $menu){
            if(!is_array($menu))continue;
            if(isset($menu['nodes'])){
                $response .= "<div class='content-help-title'>{$menu['title']}</div>";
                self::showSubMenu($menu,$response);
            }else{
                $response .= "<div class='content-help-listas' onclick='selectHelp(this)'>{$menu['title']}</div>";
                $response .= "<div style='display: none;'>{$menu['help']}</div>";
            }
        }
        return $response;
    }

    public static function showSubMenu($option,&$response){
        foreach($option['nodes'] as $key => $value){
            if(is_array($value) && isset($value['nodes'])){
                $response .= "<div class='content-help-title'>{$value['title']}</div>";
                self::showSubMenu($value,$response);
            }else{
                $response .= "<div class='content-help-listas' onclick=\"selectHelp(this)\">{$value['title']}</div>";
            }
        }
    }

    public function showListHelpInfoAction(){
        $this->setResponse("ajax");
        $title = $this->getPostParam("title");
        $_menu = Menu::$_menu;
        foreach($_menu as $id => $value){
                $xx = array_search("$title",Menu::$_menu[$id]);
                Debug::addVariable("aquiiiiii",$xx);
                throw new DebugException("Error"); 
            foreach(Menu::$_menu[$id] as $key => $menu){
            }
        }
        $response  = "<div class='content_help'>{$_menu[$key][$key_2]['help']}</div>";
        $this->renderText(json_encode($response));
    }


    public function showListCalenAction(){
        $this->setResponse("ajax");
        $mes = $this->getPostParam("mes");
        $ano = $this->getPostParam("ano");
        $dia_evento = $this->getPostParam("dia_evento");
        $mes_evento = $this->getPostParam("mes_evento");
        $ano_evento = $this->getPostParam("ano_evento");
        $today_eventos = new Date();
        if($dia_evento!=""){
            $today_eventos = new Date();
            $today_eventos->setMonth($mes_evento);
            $today_eventos->setYear($ano_evento);
            $today_eventos->setDay($dia_evento);
        }
        $today = new Date();
        $today->setMonth($mes);
        $today->setYear($ano);
        $date_pasado = new Date($today->getDate());
        $date_pasado->setDay(0);
        $sem = $date_pasado->getWeekRange();
        $date_cal = new Date($sem[0]);
        $response  = "";
        $response .= "<div><span class='title-calen'>{$today->getMonthName()} {$today->getYear()}</span>";
        //$response .= "<div id='calendar_nav_bar'> <div>D&iacute;a<span>D&iacute;a</span></div> <div>Semana<span>Semana</span></div> <div class='active_nav'>Mes<span>Mes</span></div> <div>A&ntilde;o<span>A&ntilde;o</span></div> </div>";
        $response .= "<div id='today_bar'> <div id='prev_day' onclick='showListCalen(1)'></div> <div id='today' onclick='showListCalen(3)'>Hoy</div> <div id='next_day' onclick='showListCalen(2)'></div> </div>";
        //$response .= "<span class='title-calen'><span onclick='showListCalen(1)'> << </span> <span onclick='showListCalen(3)'> Hoy </span> <span onclick='showListCalen(2)'> >> </span> </span>";
        $response .= "</div>";
        $response .= "<table id='content-fec-calendar'>";
        $response .= "<tr>";
        $response .= "<td>Lunes</td>";
        $response .= "<td>Martes</td>";
        $response .= "<td>Miercoles</td>";
        $response .= "<td>Jueves</td>";
        $response .= "<td>Viernes</td>";
        $response .= "<td>Sabado</td>";
        $response .= "<td>Domingo</td>";
        $response .= "</tr>";
        for($i=0;$i<6;$i++){
            $response .="<tr>";
            for($y=0;$y<7;$y++){
                $class = "day-act";
                if($date_cal->getMonth()!=$today->getMonth())$class="day-inac";
                if($date_cal->isToday() || $date_cal->getDate()==$today_eventos->getDate())$class="day-today";
                $response .="<td class='$class' onclick=\"showEvent('{$date_cal->getDay()}','{$date_cal->getMonth()}','{$date_cal->getYear()}');\">{$date_cal->getDay()}</td>";
                $date_cal = new Date($date_cal->addDays(1));
            }
            $response .="</tr>";
        }
        $response .= "</table>";
        $response .=" <table class='content-event'>";
        $response .=" <tr><td colspan=5>Eventos Para El Dia {$today_eventos->getDayOfWeek()} {$today_eventos->getDay()} de {$today_eventos->getMonthName()} Del {$today_eventos->getYear()}</td></tr>";
        $response .=" <tr>";
        $response .=" <td>Hora</td>";
        $response .=" <td>Detalle</td>";
        $response .=" <td>Lugar</td>";
        $response .=" <td>Obliga</td>";
        $response .=" <td>Observacion</td>";
        $response .=" </tr>";
        $basica06 = $this->Basica06->findAllBySql("SELECT detalle,lugar,horini,horfin,obliga,nota FROM basica06 WHERE (fecini>='{$today_eventos->getUsingFormatDefault()}' AND fecfin<='{$today_eventos->getUsingFormatDefault()}') OR (fecini<='{$today_eventos->getUsingFormatDefault()}' AND fecfin>='{$today_eventos->getUsingFormatDefault()}') ");
        foreach($basica06 as $mbasica06){
            $response .=" <tr>";
            $response .=" <td>{$mbasica06->getHorini()}-{$mbasica06->getHorfin()}</td>";
            $response .=" <td>{$mbasica06->getDetalle()}</td>";
            $response .=" <td>{$mbasica06->getLugar()}</td>";
            $response .=" <td>{$mbasica06->getObliga()}</td>";
            $response .=" <td>{$mbasica06->getNota()}</td>";
            $response .=" </tr>";
        }
        $response .=" </table>";
        $this->renderText(json_encode($response));
    }

    /*
     * Function para mostrar la captura basica02 
     */

    public function showCapBas2Action(){
        $this->setResponse("ajax");
        $basica02 = $this->Basica02->findFirst("usuario=".parent::getActUser());
        $gener02 = $this->Gener02->findFirst("usuario = '".parent::getActUser('usuario')."'");
        if($basica02==false)$basica02=new Basica02();
        $response  = "<table>";
        $response .= "<tr><td>Area</td></tr>";
        $response .= "<tr><td>".Tag::select("codare_basica02",$this->Basica01->find(),"using: codare,detalle","value: {$basica02->getCodare()}")."</td></tr>";
        $response .= "<tr><td>Ext</td></tr>";
        $response .= "<tr><td>".Tag::textField("ext_basica02","value: {$basica02->getExt()}","size: 5")."</td></tr>";
        $response .= "<tr><td>Celular</td></tr>";
        $response .= "<tr><td>".Tag::textField("celular_basica02","value: {$basica02->getCelular()}")."</td></tr>";
        $response .= "<tr><td>Telefono</td></tr>";
        $response .= "<tr><td>".Tag::textField("telefono_basica02","value: {$basica02->getTelefono()}")."</td></tr>";
        $response .= "<tr><td>Email</td></tr>";
        $response .= "<tr><td>".Tag::textField("email_basica02","value: {$basica02->getEmail()}","size: 28")."</td></tr>";
        $response .= "</table>";
        $this->renderText(json_encode($response));
    }

    /*
     * Function para actualizar la info usuario
     */

    public function saveCapBas2Action(){
        try{
            try{
                $this->setResponse("ajax");
                $response = parent::startFunc();
                $modelos = array("basica02");
                $Transaccion = parent::startTrans($modelos);
                $basica02 = new Basica02();
                $basica02->setTransaction($Transaccion);
                $basica02->setUsuario(parent::getActUser('usuario'));
                $basica02->setCodare($this->getPostParam("codare","alpha"));
                $basica02->setEmail($this->getPostParam("email"));
                $basica02->setCelular($this->getPostParam("celular","alpha"));
                $basica02->setTelefono($this->getPostParam("telefono","alpha"));
                $basica02->setExt($this->getPostParam("ext","alpha"));
                if(!$basica02->save()){
                    parent::setLogger($basica02->getMessages());
                    parent::ErrorTrans();
                }
                parent::finishTrans();
                $response = parent::successFunc("Guardado Exitoso",null);
                return $this->renderText(json_encode($response));
            } catch (DbException $e){
                parent::setLogger($e->getMessage());
                parent::ErrorTrans();
            }
        } catch (TransactionFailed $e){
            $response = parent::errorFunc("Error al Guardar/Editar Datos Usuario");
            return $this->renderText(json_encode($response));
        }
    }

    public function cambiarClave_viewAction(){
        $this->setParamToView("titulo","Cambio de Clave");
    }

    public function cambiarClaveAction(){
        try{
            try{
                $this->setResponse("ajax");
                $response = parent::startFunc();
                $modelos = array("gener02");
                $Transaccion = parent::startTrans($modelos);
                $claveant = $this->getPostParam("claveant");
                $clavenue = $this->getPostParam("clavenue");
                $clavecon = $this->getPostParam("clavecon");
                $mclave = parent::encriptar($claveant);
                $gener02 = $this->Gener02->findFirst("usuario = '".parent::getActUser('usuario')."' and clave='$mclave'");
                if($gener02==false){
                    $response = parent::errorFunc("la clave no es correcta");
                    return $this->renderText(json_encode($response));
                }
                if($clavenue!=$clavecon){
                    $response = parent::errorFunc("las Claves no coinciden");
                    return $this->renderText(json_encode($response));
                }
                $gener02->setTransaction($Transaccion);
                $mclave = parent::encriptar($clavenue);
                if($gener02->getClave()!=$clave){
                    $gener02->setClave($mclave);
                    if(!$gener02->save()){
                        parent::setLogger($gener02->getMessages());
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

    /*
     * Function para mostrar los Chat/Mensaje
     */

    public function showListChatAction(){
        $this->setResponse("ajax");
        $response = "";
        $basica04 = $this->Basica04->findAllBySql("SELECT distinct basica04.usuario FROM basica04,basica05 WHERE basica04.numero = basica05.numero AND basica05.usuario = ".parent::getActUser());
        foreach($basica04 as $mbasica04){
            $count_chat = $this->Basica05->findBySql("SELECT count(basica05.numero) as numero FROM basica04,basica05 WHERE basica04.numero=basica05.numero AND basica04.usuario='{$mbasica04->getUsuario()}' AND basica05.usuario=".parent::getActUser()." AND estado='D' limit 1")->getNumero();
            $det = "";
            if($count_chat>0)$det = " ($count_chat) ";
            $response .= "<div class='content-chat-listas' onclick=\"selectChat(this,'{$mbasica04->getUsuario()}')\">{$mbasica04->getGener02()->getNombre()} $det</div>";
        }
        $this->renderText(json_encode($response));
    }

    public function showListChat2Action(){
        $this->setResponse("ajax");
        $usuario = $this->getPostParam("usuario");
        $response = "";
        $mbasica02 = $this->Basica02->findFirst("usuario='$usuario'");
        $count_chat = $this->Basica05->findBySql("SELECT count(basica05.numero) as numero FROM basica04,basica05 WHERE basica04.numero=basica05.numero AND basica04.usuario='$usuario' AND basica05.usuario=".parent::getActUser()." AND estado='D' limit 1")->getNumero();
        $det = "";
        if($count_chat>0)$det = " ($count_chat) ";
        $response .= "<div class='content-chat-listas active' onclick=\"selectChat(this,'{$mbasica02->getUsuario()}')\">{$mbasica02->getGener02()->getNombre()} $det</div>";
        $basica04 = $this->Basica04->findAllBySql("SELECT distinct basica04.usuario FROM basica04,basica05 WHERE basica04.numero = basica05.numero AND basica04.usuario<>'$usuario' AND basica05.usuario = ".parent::getActUser());
        foreach($basica04 as $mbasica04){
            $response .= "<div class='content-chat-listas' onclick=\"selectChat(this,'{$mbasica04->getUsuario()}')\">{$mbasica04->getGener02()->getNombre()} $det</div>";
        }
        $this->renderText(json_encode($response));
    }

    public function showListChatInfoAction(){
        $this->setResponse("ajax");
        $usuario = $this->getPostParam("usuario");
        $mbasica02 = $this->Basica02->findFirst("usuario = '$usuario'");
        $mgener02 = $this->Gener02->findFirst("usuario = '$usuario'");
        $response  = "";
        $basica04 = $this->Basica04->findAllBySql("SELECT basica04.numero,basica05.estado as usuario,basica04.fecha,basica04.hora,basica04.nota FROM basica04,basica05 WHERE basica04.numero=basica05.numero AND (basica04.usuario = '$usuario' OR basica04.usuario=".parent::getActUser()." OR basica05.usuario='$usuario' AND basica05.usuario=".parent::getActUser().") ORDER BY 1 ");
        $mfecha = "xx";
        foreach($basica04 as $mbasica04){
            if($mfecha!=$mbasica04->getFecha()->getDate()){
                $mfecha = $mbasica04->getFecha()->getDate();
                $response .= "<div class='date-msg'>$mfecha</div>";
            }
            $this->Basica05->updateAll("estado='R'","conditions: numero = '{$mbasica04->getNumero()}' AND usuario='".parent::getActUser()."'");
            $l = $this->Basica04->count("*","conditions: numero='{$mbasica04->getNumero()}' AND usuario='$usuario'");
            $class="send-msg";
            if($l>0)$class="received-msg";
            $response .= "<div class='msg-globe $class'>";
            $response .= "{$mbasica04->getNota()}";
            if($l==0) $response .= "<div class='msg-state'>{$mbasica04->getUsuario()}</div>";
            $response .= "<div class='time-msg'>{$mbasica04->getHora()}</div>";
            $response .= "</div>";
        }
        $this->renderText(json_encode($response));
    }

    /*
     * Function para Guardar Chat
     */

    public function saveChatAction(){
        try{
            try{
                $this->setResponse("ajax");
                $response = parent::startFunc();
                $date = new Date();
                $usuario = $this->getPostParam("usuario");
                $msg = strtoupper($this->getPostParam("msg"));
                $numero = $this->Basica04->maximum("numero")+1;
                $modelos = array("basica04","basica05");
                parent::startTrans($modelos);
                $Transaccion = parent::startTrans($modelos);
                $basica04 = new Basica04();
                $basica04->setTransaction($Transaccion);
                $basica04->setNumero($numero);
                $basica04->setUsuario(parent::getActUser());
                $basica04->setFecha($date->getUsingFormatDefault());
                $basica04->setHora(date("H:i"));
                $basica04->setNota($msg);
                if(!$basica04->save()){
                    parent::setLogger($basica04->getMessages());
                    parent::ErrorTrans();
                }
                $basica05 = new Basica05();
                $basica05->setTransaction($Transaccion);
                $basica05->setNumero($numero);
                $basica05->setUsuario($usuario);
                $basica05->setEstado("D");
                if(!$basica05->save()){
                    parent::setLogger($basica05->getMessages());
                    parent::ErrorTrans();
                }
                parent::finishTrans();
                $response = parent::successFunc("Guardado Exitoso",$numero);
                return $this->renderText(json_encode($response));
            } catch (DbException $e){
                parent::setLogger($e->getMessage());
                parent::ErrorTrans();
            }
        } catch (TransactionFailed $e){
            $response = parent::errorFunc("Error al Guardar Chat");
            return $this->renderText(json_encode($response));
        }

    }

    /*
     * Function para Contar Notificaciones 
     */

    public function countNotAction(){
        $this->setResponse("ajax");
        $date = new Date();
        $response = 0;
        //$count_chat = $this->Basica05->findBySql("SELECT count(*) as numero FROM basica05 WHERE usuario=".parent::getActUser()." AND estado='D'")->getNumero();
        //$response = $count_chat;
        $this->renderText(json_encode($response));
    }

}
?>
