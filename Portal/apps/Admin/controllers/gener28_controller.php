<?php

class Gener28Controller extends ApplicationController {

    private $estado = 100;
    private $title = "Permisos por Funcionario";
    private $query = "";

    public function initialize(){
        $this->setTemplateAfter('escritorio');
        $this->setPersistance(true);
    }

    public function indexAction(){
        $this->setParamToView("titulo",$this->title);
        $response = "";
        $response = "";
        $_menu = Menu::$_menu;
        foreach($_menu as $key => $value){
            $response = self::showMenu($key,$response);
        }
        $this->setParamToView("menu",$response);
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
                $otros = "";
                if(!isset($menu['type']))continue;
                if(isset($menu['otros']))$otros=",".json_encode($menu['otros']);
                $response .= "<div class='content-help-listas' onclick='traerFunciones(\"{$menu['title']}\",\"{$menu['default']}\",\"{$menu['type']}\"$otros)'>{$menu['title']}</div>";
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
                $otros = "";
                if(!isset($menu['type']))continue;
                if(isset($menu['otros']))$otros=",".json_encode($menu['otros']);
                $response .= "<div class='content-help-listas' onclick='traerFunciones(\"{$value['title']}\",\"{$value['default']}\",\"{$value['type']}\"$otros)'>{$value['title']}</div>";
            }
        }
    }

    public function traerFuncionesAction(){
        $this->setResponse("ajax");
        $title = $this->getPostParam("title");
        $tipfun = $this->getPostParam("tipfun");
        $controller = $this->getPostParam("controller");
        $type = $this->getPostParam("type");
        $otros = str_replace("\\","",$this->getPostParam("otros"));
        $url = $this->getPostParam("url");
        $_types = Menu::$_types;
        if($type=="otros"){
            $types = json_decode($otros);
            $otros = ",".json_encode($types);
        }else{
            $types = $_types[$type];
        }
        $controller = preg_split("/\//",$controller);
        $controller = $controller[0]; 
        $mercurio01 = $this->Mercurio01->findFirst();
        $response="<div class='content-help-title'>$title</div>";
        $response.="<div class='content-help-title'>Permite</div>";
        foreach($types as $index => $value){
            $l = $this->Gener28->count("*","conditions: codapl='{$mercurio01->getCodapl()}' AND role='$tipfun' AND resource='$controller' AND action='$index' and allow='S'");
            if($l>0) $response  .= "<div class='content-help-listas'  onclick='savePermiso(\"$title\",\"$controller\",\"$tipfun\",\"$index\",\"N\",\"$type\"$otros);' >$value</div>";
        }
        $response.="<div class='content-help-title'>No Permite</div>";
        foreach($types as $index => $value){
            $l = $this->Gener28->count("*","conditions: codapl='{$mercurio01->getCodapl()}' AND role='$tipfun' AND resource='$controller' AND action='$index' and allow='N'");
            if($l>0) $response  .= "<div class='content-help-listas' onclick='savePermiso(\"$title\",\"$controller\",\"$tipfun\",\"$index\",\"S\",\"$type\"$otros);' >$value</div>";
            $l = $this->Gener28->count("*","conditions: codapl='{$mercurio01->getCodapl()}' AND role='$tipfun' AND resource='$controller' AND action='$index'");
            if($l==0) $response  .= "<div class='content-help-listas' onclick='savePermiso(\"$title\",\"$controller\",\"$tipfun\",\"$index\",\"S\",\"$type\"$otros);' >$value</div>";
        }
        return $this->renderText(json_encode($response));
    }

    public function savePermisoAction(){
        try{
            try{
                $this->setResponse("ajax");
                $controller = $this->getPostParam("controller");
                $tipfun = $this->getPostParam("tipfun");
                $action = $this->getPostParam("action");
                $allow = $this->getPostParam("allow");
                $modelos = array("gener28");
                $Transaccion = parent::startTrans($modelos);
                $response = parent::startFunc();
                $mercurio01 = $this->Mercurio01->findFirst();
                $condi = "codapl='{$mercurio01->getCodapl()}' AND role='$tipfun' AND resource='$controller' AND action='$action'"; 
                $l = $this->Gener28->count("*","conditions: $condi");
                if($l>0){
                    $this->Gener28->updateAll("allow='$allow'","conditions: $condi");
                }else{
                    $gener28 = new Gener28();
                    $gener28->setTransaction($Transaccion);
                    $gener28->setId(0);
                    $gener28->setCodapl($mercurio01->getCodapl());
                    $gener28->setRole($tipfun);
                    $gener28->setResource($controller);
                    $gener28->setAction($action);
                    $gener28->setAllow($allow);
                    if(!$gener28->save()){
                        parent::setLogger($gener28->getMessages());
                        parent::ErrorTrans();
                    }
                }
                parent::finishTrans();
                $response = parent::successFunc("Asignacion de Permisos Exitosa");
                return $this->renderText(json_encode($response));
            } catch (DbException $e){
                parent::setLogger($e->getMessage());
                parent::ErrorTrans();
            }
        } catch (TransactionFailed $e){
            $response = parent::errorFunc("Error al Asignar Permisos");
            return $this->renderText(json_encode($response));
        }
    }


    public function reporteAction(){
        $_fields["menu"] = array('header'=>"Menu",'size'=>"80",'align'=>"C");
        $_fields["opciones"] = array('header'=>"Opciones",'size'=>"160",'align'=>"C");
        $title = "Permisos Por Funcionarios";
        $format = "pdf";
        if($format=='pdf'){
            $report = new UserReportPdf($title,$_fields);
        }elseif($format=='excel'){
            $report = new UserReportExcel($title,$_fields);
        }
        $report->startReport();
        $_menu = Menu::$_menu;
        $_types = Menu::$_types;
        $mercurio01 = $this->Mercurio01->findFirst();
        $gener21 = $this->Gener21->find();
        foreach($gener21 as $mgener21){
            foreach($_menu as $key => $value){
                $report->Put("menu",$_menu[$key]['title']);
                $report->Put("opciones","");
                $report->OutputToReport();
                foreach($_menu[$key] as $key_2 => $value_2){
                    if(!isset($_menu[$key][$key_2]['title']))continue;
                    if(!isset($_menu[$key][$key_2]['type']))continue;
                    $report->Put("menu",$_menu[$key][$key_2]['title']);
                    $type = $_menu[$key][$key_2]['type'];
                    if($type=="otros"){
                        $types = $_menu[$key][$key_2]['otros'];
                    }else{
                        $types = $_types[$type];
                    }
                    $controller = $_menu[$key][$key_2]['default'];
                    $controller = preg_split("/\//",$controller);
                    $controller = $controller[0]; 
                    $texto = "";
                    foreach($types as $index => $value){
                        $permite="No";
                        $l = $this->Gener28->count("*","conditions: codapl='{$mercurio01->getCodapl()}' AND role='{$mgener21->getTipfun()}' AND resource='$controller' AND action='$index' and allow='S'");
                        if($l>0)$permite=" Si";
                        $value.=": ".$permite." ";
                        $texto .=$value;
                    }
                    $report->Put("opciones",$texto);
                    $report->OutputToReport();
                }
            }
        }
        $file = "funcionarios";
        $report->FinishReport($file);
        $this->setResponse('view');
    }

}
?>
