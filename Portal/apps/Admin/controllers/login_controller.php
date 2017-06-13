<?php

class LoginController extends ApplicationController {

    public function indexAction(){
    }

    public function autenticarAction(){
        $login = $this->getPostParam("user","striptags","extraspaces");
        $clave = $this->getPostParam("password","striptags","extraspaces");
        $mclave = parent::encriptar($clave);
        $auth = new Auth('model', "class: Gener02", "usuario: $login","clave: $mclave","estado: A");
        if($auth->authenticate()==false){
            Flash::error("Usuario/clave incorrectos");
            $this->routeTo("action: index");
        }else{
            $config = CoreConfig::readFromActiveApplication('config.ini');
            $mconfig = CoreConfig::readFromActiveApplication('environment.ini');
            SESSION::setData("mmotor",$mconfig->development->{"database.type"});
            SESSION::setData("mdate",$config->application->dbdate);
            SESSION::setData("nombre",parent::doUpper(parent::getActUser("nombre")));
            $this->redirect("desktop");
        }
    }

    public function ClsSesionAction(){
        $this->setResponse("ajax");
        Auth::destroyIdentity();
        $this->renderText(json_encode(true));
    }

}
?>