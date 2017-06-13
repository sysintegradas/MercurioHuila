<?php

class Mercurio36Controller extends ApplicationController {

    private $title = "Retiro de Trabajador por Muerte";

    public function initialize(){
        $this->setTemplateAfter('escritorio');
        $this->setPersistance(true);
    }

    public function indexAction(){
        $this->setParamToview('titulo',$this->title);
        $html = "";
        $html .= "<table class='resultado-consul' border=1>";
        $html .= "<thead>";
        $html .= "<th>Cedula</th>";
        $html .= "<th>Codigo de Estado</th>";
        $html .= "<th>Fecha de Retiro</th>";
        $html .= "<th>Nota</th>";
        $html .= "<th>Estado</th>";
        $html .= "<th>Aprobar</th>";
        $html .= "<th>Rechazar</th>";
        $html .= "</thead>";
        $mercurio36 = $this->Mercurio36->find("estado='P'");
        foreach($mercurio36 as $mmercurio36){
            $html .= "<tr>";
            $html .= "<td>{$mmercurio36->getCedtra()}</td>";
            $html .= "<td>{$mmercurio36->getCodest()}</td>";
            $html .= "<td>{$mmercurio36->getFecret()}</td>";
            $html .= "<td>{$mmercurio36->getnota()}</td>";
            $html .= "<td>{$mmercurio36->getEstado()}</td>";
            $html .= "<td onclick=\"completar('{$mmercurio36->getId()}',this);\">".Tag::image("desktop/security-high.png", "width: 25px", "height: 25px", "style: cursor: pointer;")."</td>";
            $html .= "<td onclick=\"rechazar('{$mmercurio36->getId()}',this);\">".Tag::image("desktop/security-low.png", "width: 25px", "height: 25px", "style: cursor: pointer;")."</td>";
            $html .= "</tr>";
        }
        $html .= "</tbody>";
        $html .= "</table>";
        $this->setParamToView("html", $html);
    }

    public function rechazarAction(){
        try{
            try{
                $this->setResponse("ajax");
                $response = parent::startFunc();
                $modelos = array("mercurio36");
                $Transaccion = parent::startTrans($modelos);
                $id = $this->getPostParam("id");
                $motivo = $this->getPostParam("motivo");
                $mercurio36 = $this->Mercurio36->findFirst("id = '$id'");
                $retiro = $mercurio36->getArray();
                $cedtra = $retiro['cedtra'];
                $result = parent::webService("servidor","datosTrabajador",array("cedtra"=> $cedtra));
                $nombre = $result[0]['nombre']; 
                $today = new Date();
                $this->Mercurio36->updateAll("estado='X',fecest='$today',motivo='$motivo'","conditions: id = '$id' ");
                $asunto = "Rechazo de Empresa";
                $msg = "se le rechazo retiro ".$nombre;
                $file = "";
                parent::enviarCorreo("Rechazo Retiro de Trabajador", $id, "", "Rechazo de retiro de Trabajador ", $msg, $file);
                parent::finishTrans();
                $response = parent::successFunc("Rechazo con exito",null);
                return $this->renderText(json_encode($response));
            }catch (DbException $e){
                parent::setLogger($e->getMessage());
                parent::ErrorTrans();
            }
        }catch (TransactionFailed $e){
            $response = parent::errorFunc("Error al Rechazar Empresa");
            return $this->renderText(json_encode($response));
        }
    }

    public function aprobarAction(){
          try{
            try{
              $this->setResponse("ajax");
              $response = parent::startFunc();
              $modelos = array("mercurio36");
              $Transaccion = parent::startTrans($modelos);
              $id = $this->getPostParam("id");
              $fecret = $this->getPostParam("fecret");
              $today = new Date();
              $motivo = $this->getPostParam("motivo");
              $mercurio36 = $this->Mercurio36->findFirst("id = '$id'");
              $retiro = $mercurio36->getArray();
              $cedtra = $retiro['cedtra'];    
              $fecest = $fecret;
              $codest = $retiro['codest'];   
              $estado="M";
              $result = parent::webService('update_serv','novret',array("fecest"=>$fecest,"codest"=>$codest,"fecret"=>$fecret,"cedtra"=>$cedtra,"estado"=>$estado));
              if($result==false || (isset($result[0]['res']) && $result[0]['res']!=true)){
                  parent::ErrorTrans();
              }
              $user=Auth::getActiveIdentity();
              $Log = new Logger("File","{$user['usuario']}.log");
              $Log->setFormat("[%date%] %controller%/%action% on %application% Error: %message%");
             $result = parent::webService("servidor","datosTrabajador",array("cedtra"=> $cedtra));
              $nombre = $result[0]['nombre'];
              $email = $result[0]['mail'];
              $file = "";
              $msg = "Cordial Saludos<br> Lamento informarle que a sido desvinculado de la caja de compensación familiar<br> \"COMFAMILIAR HUILA\"<br>tentamente,<br><br>Ernesto Miguel Orozco Duran<br>Director Administrativo Comfahuila";
              
              parent::enviarCorreo("Confirmacion Retiro", $nombre, $email, "Retiro de trabajador", $msg, $file);
             // parent::enviarCorreo("Confirmacion Retiro", $nombre, , "Retiro de trabajador", $msg, $file);
             // parent::enviarCorreo("Confirmacion Retiro", $nombre,, "Retiro de trabajador", $msg, $file);
              $today = new Date();
              $this->Mercurio36->updateAll("estado='M',fecest='{$fecret}'","conditions: cedtra = '{$cedtra}'");
              parent::finishTrans();
              $response = parent::successFunc("Envio de Informacion Exitosa",null);
              return $this->renderText(json_encode($response));
            }catch (DbException $e){
              parent::setLogger($e->getMessage());
              parent::ErrorTrans();
            }
          }catch (TransactionFailed $e){
            $response = parent::errorFunc("Error confirmar afiliacion");
            return $this->renderText(json_encode($response));
          }
     }

    public function formularioAction(){
        $this->setResponse("ajax");
        $id = $this->getPostParam("id");
   
        $formu ="";
        $formu .="<div id='TrabajadoresCampos'>";
        $formu .= Tag::form("id: form-completar","autocomplete: off","enctype: multipart/form-data");
        $formu .= "<table cellpadding='5'style='width:100%; margin: 5px '> ";

        $formu .= "<tr>";   
        $formu .= "<td><label>Fecha de Retiro :<label></td>";  
        $formu .= "<td>".TagUser::calendar("fecret","size: 15")."</td>";   
        $formu .= "</tr>";   
        $formu .= "</table>";
        $formu .="<table width='85%' >";
        $formu .= "<tr>";   
        $formu .= "<td>".Tag::button("Retirar","class: submit","style: width: 100px; margin-left: 40%","onclick: aprobarF()")."</td>";   
        $formu .= "</tr>";   
        $formu .= "</table>";
        $formu .="</div>";
        $formu .="</div>";
        $formu .= Tag::endForm();
        return $this->renderText(json_encode($formu));

    }
}
?>
