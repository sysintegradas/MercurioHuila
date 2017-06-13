<?php

class ajaxController extends ApplicationController {
    public function permisosAction(){
        $this->setResponse("ajax");
        if(SESSION::getDATA('tipo')=="" || SESSION::getDATA('documento')==""){
            $flag= false;
        }else{
            $flag= true;
        }
        return $this->renderText(json_encode($flag));
    }
}
?>
