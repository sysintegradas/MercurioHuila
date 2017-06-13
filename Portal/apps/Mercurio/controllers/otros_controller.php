<?php

class OtrosController extends ApplicationController {

    public function indexAction(){
      $this->setResponse("ajax");
      echo parent::showTitle('Bienvenidos a Mercurio');
    }
    
    public function envioCarta_viewAction(){
      $this->setResponse("ajax");
      echo parent::showTitle('Envio de Mensaje');
      
    }
    
    public function envioCartaAction(){
      $asunto = $this->getPostParam("asunto");
      $mensaje = $this->getPostParam("mensaje");
      $ruta_file = "";
      if(isset($_FILES['archivo'])){
        $path = "public/files/";
        $this->uploadFile("archivo",getcwd()."/$path/");
        $dd=pathinfo($_FILES["archivo"]["name"]);
        $ruta_file = $path."/".$dd['basename'];
      }
      parent::enviarCorreo("Contactenos",Session::getDATA('nombre'), "", $asunto, $mensaje, $ruta_file);
    }
    
    public function clasificados_viewAction(){
      $this->setResponse("ajax");
      echo parent::showTitle('Clasificados');
      $today = new Date();
      Tag::displayTo("fecini", $today->getUsingFormatDefault());  
    }
    
   /* public function clasificadosAction(){      
      try{
            try{
                $this->setResponse("ajax");
                $response = parent::startFunc();
                $modelos = array("mercurio24");
                $Transaccion = parent::startTrans($modelos);
                $codcat = $this->getPostParam("codcat");
                $fecini = $this->getPostParam("fecini");
                $titulo = $this->getPostParam("titulo");
                $descripcion = $this->getPostParam("descripcion");
                $mercurio24 = new Mercurio24();
                $mercurio24->setTransaction($Transaccion);
                $mercurio24->setId(0);
                $mercurio24->setCodcaj(Session::getDATA('codcaj'));
                $mercurio24->setTipo(Session::getDATA('tipo'));
                $mercurio24->setDocumento(Session::getDATA('documento'));
                $mercurio24->setCodcat($codcat);
                $mercurio24->setFecini($fecini);
                $mercurio24->setTitulo($titulo);
                $mercurio24->setDescripcion($descripcion);
                $mercurio24->setEstado("P");
                if(!$mercurio24->save()){
                    parent::setLogger($mercurio24->getMessages());
                    parent::ErrorTrans();
                }
                $asunto = "Adiciona de Clasificado $titulo";
                $mensaje = $descripcion;
                $ruta_file = "";
                parent::enviarCorreo("Clasificados",Session::getDATA('nombre'), "", $asunto, $mensaje, $ruta_file);
                parent::finishTrans();
                $response = parent::successFunc("adicion de clasificados con exitosa",null);
                return $this->renderText(json_encode($response));
            } catch (DbException $e){
                parent::setLogger($e->getMessage());
                parent::ErrorTrans();
            }
        } catch (TransactionFailed $e){
            $response = parent::errorFunc("Error al adicional Clasificados");
            return $this->renderText(json_encode($response));
        }
    }*/

    public function listado_viewAction(){
        $this->setResponse("ajax");
        $tipo = Session::getData("tipo");
        $estado = array('P' => 'Pendiente','A' => 'Aprobado','X' => 'Rechazado');
        echo parent::showTitle('Listado de Trámites');
        $html = "";
        if($tipo == 'T'){
            $html .= "<div style='margin-bottom: 10px; margin-top: 15px;'>";
            $html .= "<table border='1' width='100%' class='resultado-sec'>";
            $html .= "<thead>";
            $html .= "<tr class='tr-result'>";
            $html .= "<th colspan='5'>Afiliaciones de Conyuges</th>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<th>Cedula</th>";
            $html .= "<th>Nombre</th>";
            $html .= "<th>Estado</th>";
            $html .= "<th>Fecha de Estado</th>";
            $html .= "<th>Motivo</th>";
            $html .= "</tr>";
            $html .= "</thead>";
            $html .= "<tbody>";
            $mercurio32 = $this->Mercurio32->find("codcaj = '".Session::getDATA('codcaj')."' AND cedtra = '".Session::getDATA('documento')."'");
            foreach($mercurio32 as $mmercurio32){
                $html .= "<tr>";
                $html .= "<td>{$mmercurio32->getCedcon()}</td>";
                $html .= "<td>{$mmercurio32->getPriape()}</td>";
                $html .= "<td>".$estado[$mmercurio32->getEstado()]."</td>";
                $html .= "<td>{$mmercurio32->getFecest()}</td>";
                $html .= "<td>{$mmercurio32->getMotivo()}</td>";
                $html .= "</tr>";
            }
            $html .= "</tbody>";
            $html .= "</table>";
            $html .= "</div>";
            $html .= "<div style='margin-bottom: 10px;'>";
            $html .= "<table border='1' width='100%' class='resultado-sec'>";
            $html .= "<thead>";
            $html .= "<tr class='tr-result'>";
            $html .= "<th colspan='5'>Afiliaciones de Beneficiarios</th>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<th>Documento</th>";
            $html .= "<th>Nombre</th>";
            $html .= "<th>Estado</td>";
            $html .= "<th>Fecha de Estado</th>";
            $html .= "<th>Motivo</th>";
            $html .= "</tr>";
            $html .= "</thead>";
            $html .= "<tbody>";
            $mercurio34 = $this->Mercurio34->find("codcaj = '".Session::getDATA('codcaj')."' AND cedtra = '".Session::getDATA('documento')."'");
            foreach($mercurio34 as $mmercurio34){
                $html .= "<tr>";
                $html .= "<td>{$mmercurio34->getDocumento()}</td>";
                $html .= "<td>{$mmercurio34->getPriape()}</td>";
                $html .= "<td>".$estado[$mmercurio34->getEstado()]."</td>";
                $html .= "<td>{$mmercurio34->getFecest()}</td>";
                $html .= "<td>{$mmercurio34->getMotivo()}</td>";
                $html .= "</tr>";
            }
            $html .= "</tbody>";
            $html .= "</table>";
            $html .= "</div>";
            $html .= "<div style='margin-bottom: 10px;'>";
            $html .= "<table border='1' width='100%' class='resultado-sec'>";
            $html .= "<thead>";
            $html .= "<tr class='tr-result'>";
            $html .= "<th colspan='5'>Actualización de Datos</th>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<th>Campo</th>";
            $html .= "<th>Valor</th>";
            $html .= "<th>Estado</th>";
            $html .= "<th>Fecha de Estado</th>";
            $html .= "<th>Motivo</th>";
            $html .= "</tr>";
            $html .= "</thead>";
            $html .= "<tbody>";
            $mercurio33 = $this->Mercurio33->find("codcaj = '".Session::getDATA('codcaj')."' AND tipo = '".Session::getDATA('tipo')."' AND documento = '".Session::getDATA('documento')."'");
            foreach($mercurio33 as $mmercurio33){
                $html .= "<tr>";
                $html .= "<td>{$mmercurio33->getCampo()}</td>";
                $html .= "<td>{$mmercurio33->getValor()}</td>";
                $html .= "<td>".$estado[$mmercurio33->getEstado()]."</td>";
                $html .= "<td>{$mmercurio33->getFecest()}</td>";
                $html .= "<td>{$mmercurio33->getMotivo()}</td>";
                $html .= "</tr>";
            }
            $html .= "</tbody>";
            $html .= "</table>";
            $html .= "</div>";
            /*$html .= "<div style='margin-bottom: 10px;'>";
              $html .= "<table border='1' width='100%' class='resultado-sec'>";
              $html .= "<thead>";
              $html .= "<tr class='tr-result'>";
              $html .= "<th colspan='4'>Clasificados</th>";
              $html .= "</tr>";
              $html .= "<tr>";
              $html .= "<th>Titulo</th>";
              $html .= "<th>Estado</th>";
              $html .= "<th>Fecha de Estado</th>";
              $html .= "<th>Motivo</th>";
              $html .= "</tr>";
              $html .= "</thead>";
              $html .= "<tbody>";
              $mercurio24 = $this->Mercurio24->find("codcaj = '".Session::getDATA('codcaj')."' AND tipo = '".Session::getDATA('tipo')."' AND documento = '".Session::getDATA('documento')."'");
              foreach($mercurio24 as $mmercurio24){
              $html .= "<tr>";
              $html .= "<td>{$mmercurio24->getTitulo()}</td>";
              $html .= "<td>".$estado[$mmercurio24->getEstado()]."</td>";
              $html .= "<td>{$mmercurio24->getFecest()}</td>";
              $html .= "<td>{$mmercurio24->getMotivo()}</td>";
              $html .= "</tr>";
              }
              $html .= "</tbody>";
              $html .= "</table>";
              $html .= "</div>";*/
        }else if($tipo == 'E' ){
        	$html .= "<br>";
            $html .= "<div style='margin-bottom: 10px;'>";
            $html .= "<table border='1' width='100%' class='resultado-sec'>";
            $html .= "<thead>";
            $html .= "<tr class='tr-result'>";
            $html .= "<th colspan='5'>Actualización de Datos</th>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<th>Campo</th>";
            $html .= "<th>Valor</th>";
            $html .= "<th>Estado</th>";
            $html .= "<th>Fecha de Estado</th>";
            $html .= "<th>Motivo</th>";
            $html .= "</tr>";
            $html .= "</thead>";
            $html .= "<tbody>";
            $mercurio33 = $this->Mercurio33->find("codcaj = '".Session::getDATA('codcaj')."' AND tipo = '".Session::getDATA('tipo')."' AND documento = '".Session::getDATA('documento')."'");
            foreach($mercurio33 as $mmercurio33){
                $html .= "<tr>";
                $html .= "<td>{$mmercurio33->getCampo()}</td>";
                $html .= "<td>{$mmercurio33->getValor()}</td>";
                $html .= "<td>".$estado[$mmercurio33->getEstado()]."</td>";
                $html .= "<td>{$mmercurio33->getFecest()}</td>";
                $html .= "<td>{$mmercurio33->getMotivo()}</td>";
                $html .= "</tr>";
            }
            $html .= "</tbody>";
            $html .= "</table>";
            $html .= "</div>";
            $html .= "<br>";
            $html .= "<br>";
            $html .= "<div style='margin-bottom: 10px;'>";
            $html .= "<table border='1' width='100%' class='resultado-sec'>";
            $html .= "<thead>";
            $html .= "<tr class='tr-result'>";
            $html .= "<th colspan='6'>Afiliaciones de Trabajadores</th>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<th>C&eacute;dula</th>";
            $html .= "<th>Nombre</th>";
            $html .= "<th>Fecha Ingreso</th>";
            $html .= "<th>Tipo de Contrato</th>";
            $html .= "<th>Salario</th>";
            $html .= "<th>Estado</th>";
            $html .= "</tr>";
            $html .= "</thead>";
            $html .= "<tbody>";
            $mercurio31 = $this->Mercurio31->find("nit = '".Session::getDATA('documento')."'");
            foreach($mercurio31 as $mmercurio31){
                $nombreTrabajador = $mmercurio31->getPriape()." ".$mmercurio31->getSegape()."".$mmercurio31->getPrinom()." ".$mmercurio31->getSegnom();
                $html .= "<tr>";
                $html .= "<td>{$mmercurio31->getCedtra()}</td>";
                $html .= "<td>".$nombreTrabajador."</td>";
                $html .= "<td>{$mmercurio31->getFecing()}</td>";
                $html .= "<td>{$mmercurio31->getTipcon()}</td>";
                $html .= "<td>{$mmercurio31->getSalario()}</td>";
                $html .= "<td>{$mmercurio31->getEstado()}</td>";
                $html .= "</tr>";
            }
            $html .= "</tbody>";
            $html .= "</table>";
            $html .= "</div>";
            $html .= "<br>";
        }
        echo $html;
    }
}
?>
