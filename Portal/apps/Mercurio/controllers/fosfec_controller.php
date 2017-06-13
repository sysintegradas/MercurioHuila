<?php

class FosfecController extends ApplicationController {

    public function indexAction(){
      $this->setResponse("ajax");
      echo parent::showTitle('Bienvenidos a Mercurio');
    }
    
    public function formulario_viewAction(){
      $this->setResponse("ajax");
      echo parent::showTitle('Descargar Formulario'); 
    }
    
    public function confor_viewAction(){
      $this->setResponse("ajax");
      echo parent::showTitle('Consultas Formulario'); 
    }
    
}
?>
