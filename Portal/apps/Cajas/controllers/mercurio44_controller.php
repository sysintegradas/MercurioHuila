<?php
class Mercurio44Controller extends ApplicationController{
	
	private $title = "Distribucion Usuario";

	public function initialize(){
		$this->setTemplateAfter('escritorio');
	}

	public function indexAction(){
		$this->setParamToview('titulo',$this->title);

	}

	public function traerDatosAction(){
        $this->setResponse("ajax");
        $usuario = $this->getPostParam("usuario");
        $html = "";
        $html .= "<table class='resultado-consul' border=1>";
        $html .= "<thead>";
        $html .= "<th colspan=2>Opciones a Seleccionar</th>";
        $html .= "</thead>";
        $html .= "<tbody>";
        $mercurio11 = $this->Mercurio11->findAllBySql("SELECT mercurio11.codare,mercurio11.codope,mercurio11.detalle from mercurio10,mercurio11 WHERE mercurio11.codare=mercurio10.codare AND mercurio11.codope=mercurio10.codope AND mercurio10.tipo='2' AND mercurio10.estado='A'");
        foreach($mercurio11 as $mmercurio11){
            $html .= "<tr>";
            $html .= "<td>{$mmercurio11->getDetalle()}</td>";
            $checked = "";
            if($this->Mercurio44->count("*","conditions: usuario='$usuario' AND codare='{$mmercurio11->getCodare()}' and codope='{$mmercurio11->getCodope()}'")>0) $checked = "checked: true";
            $html .= "<td>".Tag::checkBoxField("codope[]","value: {$mmercurio11->getCodare()}_{$mmercurio11->getCodope()}","$checked","onclick: AsignarOpcion(\"{$mmercurio11->getCodare()}\",\"{$mmercurio11->getCodope()}\",this);")."</td>";
            $html .= "</tr>";
        }
        $html .= "</tbody>";
        $html .= "</table>";
        return $this->renderText(json_encode($html));
    }

	public function AsignarOpcionAction(){
        try{
            try {
                $modelos = array("mercurio44");
                $Transaccion = parent::startTrans($modelos);
                $response = parent::startFunc();
                $this->setResponse("ajax");
                $usuario = $this->getPostParam("usuario");
                $codare = $this->getPostParam("codare");
                $codope = $this->getPostParam("codope");
                $tipo = $this->getPostParam("tipo");
                if($tipo==1){
                $orden = $this->Mercurio44->maximum("orden","conditions: codare='$codare' AND codope='$codope'")+1;
                $mercurio44 = new Mercurio44();
                $mercurio44->setTransaction($Transaccion);
                $mercurio44->setUsuario($usuario);
                $mercurio44->setCodare($codare);
                $mercurio44->setCodope($codope);
                $mercurio44->setOrden($orden);
                $mercurio44->setEstado("A");
                if(!$mercurio44->save()){
                    parent::setLogger($mercurio44->getMessages());
                    parent::ErrorTrans();
                }
                }else{
                    $this->Mercurio44->deleteAll("usuario='$usuario' AND codare='$codare' AND codope='$codope'");
                }
                parent::finishTrans();
                $response = parent::successFunc("Asignacion de Permisos Exitosa");
                return $this->renderText(json_encode($response));
            } catch(DbException $e){
                parent::setLogger($e->getMessage());
                parent::ErrorTrans();
            }
        } catch (TransactionFailed $e){
            $response = parent::errorFunc("Error al Asignar Permisos");
            return $this->renderText(json_encode($response));
        }
    }

    public function consultaAdministrativa_viewAction(){
    }

    public function aplicarFiltroConsultaAction(){
        $this->setResponse("ajax");
        $usuario = $this->getPostParam("usuario");
        $estado = $this->getPostParam("estado");
        $html = "";
        $html .= "<table class='resultado-consul' border=1>";
        $html .= "<thead>";
        $html .= "<tr>";
        $html .= "<th colspan=6>Radicados</th>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<th>Usuario</th>";
        $html .= "<th>Fecha</th>";
        $html .= "<th>Tipo</th>";
        $html .= "<th>Nota</th>";
        $html .= "<th>Estado</th>";
        $html .= "<th>&nbsp;</th>";
        $html .= "</tr>";
        $html .= "</thead>";
        $html .= "<tbody>";
        $subsi= DbBase::rawConnect();
        $mercurio = $subsi->inQueryAssoc("SELECT 1 as tipo,id,log,estado,razsoc as nota,usuario,(mercurio07.agencia) as agencia FROM mercurio30,mercurio07 where estado='$estado' and usuario='$usuario' AND mercurio07.documento=mercurio30.nit AND mercurio07.tipo='P'
                                            UNION
                                            SELECT 2 as tipo,id,log,estado,CONCAT(priape,' ',prinom) as nota,usuario,(mercurio07.agencia) as agencia  FROM mercurio31,mercurio07 where  estado='$estado' and usuario='$usuario' AND mercurio07.documento=mercurio31.nit AND mercurio07.tipo='E'
                                            UNION
                                            SELECT 3 as tipo,id,log,estado,CONCAT(priape,' ',prinom) as nota,usuario,(mercurio07.agencia) as agencia FROM mercurio32,mercurio07 where  estado='$estado' and usuario='$usuario' AND mercurio07.documento=mercurio32.cedtra AND mercurio07.tipo='T'
                                            UNION
                                            SELECT 4 as tipo,id,log,estado,CONCAT(priape,' ',prinom) as nota,usuario,(mercurio07.agencia) as agencia FROM mercurio34,mercurio07 where  estado='$estado' and usuario='$usuario' AND mercurio07.documento=mercurio34.cedtra AND mercurio07.tipo='T'
                                            UNION
                                            SELECT 5 as tipo,id,log,(mercurio33.estado) as estado,(mercurio07.nombre) as nota,mercurio33.usuario,(mercurio07.agencia) as agencia FROM mercurio33,mercurio07 where  mercurio33.estado='$estado' and usuario='$usuario'  AND mercurio33.documento=mercurio07.documento AND mercurio33.tipo='E' AND mercurio07.tipo='E'
                                            UNION
                                            SELECT 6 as tipo,id,log,(mercurio33.estado) as estado,(mercurio07.nombre) as nota,mercurio33.usuario,(mercurio07.agencia) as agencia FROM mercurio33,mercurio07 where  mercurio33.estado='$estado' and usuario='$usuario'  AND mercurio33.documento=mercurio07.documento AND mercurio33.tipo='T' AND mercurio07.tipo='T'
                                            UNION
                                            SELECT 7 as tipo,id,log,estado,CONCAT(nomtra) as nota,usuario,(mercurio07.agencia) as agencia FROM mercurio35,mercurio07 where  estado='$estado' and usuario='$usuario' AND mercurio07.documento=mercurio35.nit AND mercurio07.tipo='E'
                                            UNION
                                            SELECT 8 as tipo,id,log,(mercurio43.estado) as estado,(mercurio07.nombre) as nota,mercurio43.usuario,(mercurio07.agencia) as agencia FROM mercurio43,mercurio07 where  mercurio43.estado='$estado' and usuario='$usuario'  AND mercurio43.documento=mercurio07.documento
                ");
        if($mercurio == array()){
            $html .= "<tr>";
            $html .= "<td colspan='6'>No hay puntos para consultar con este usuario bajo esta condici&oacute;n</td>";
            $html .= "</tr>";
        }else{
            foreach($mercurio as $mmercurio){
                $estado_detalle="";
                $tipo_detalle="";
                $nota="";
                if($mmercurio['tipo']=="1") $tipo_detalle="EMPRESA";
                if($mmercurio['tipo']=="2") $tipo_detalle="TRABAJADOR";
                if($mmercurio['tipo']=="3") $tipo_detalle="CONYUGE";
                if($mmercurio['tipo']=="4") $tipo_detalle="BENEFICIARIO";
                if($mmercurio['tipo']=="5") $tipo_detalle="DATOS EMPRESA";
                if($mmercurio['tipo']=="6") $tipo_detalle="DATOS TRABAJADOR";
                if($mmercurio['tipo']=="7") $tipo_detalle="RETIRO";
                if($mmercurio['tipo']=="8") $tipo_detalle="DATOS PRINCIPALES";
                if($mmercurio['estado']=="P") $estado_detalle="PENDIENTE";
                if($mmercurio['estado']=="X") $estado_detalle="RECHAZADO";
                if($mmercurio['estado']=="A") $estado_detalle="APROBADO";
                $nota = $mmercurio['nota'];
                $mercurio21 = $this->Mercurio21->findFirst("id = '{$mmercurio['log']}'");
                $mgener02 = $this->Gener02->findFirst("usuario = '{$mmercurio['usuario']}'");
                if($mgener02==false)$mgener02 = new Gener02();
                $html .= "<tr>";
                $html .= "<td>{$mgener02->getNombre()} </td>";
                $html .= "<td>{$mercurio21->getFecha()}</td>";
                $html .= "<td>$tipo_detalle </td>";
                $html .= "<td>$nota </td>";
                $html .= "<td>{$estado_detalle} </td>";
                if($mmercurio['estado']=="P")
                    $html .= "<td>".Tag::image("edit.gif","onclick: cambiarUsuarioView('{$mmercurio['tipo']}','{$mmercurio['id']}','{$mmercurio['agencia']}');")."</td>";
                else
                    $html .= "<td>&nbsp;</td>";
                $html .= "</tr>";
            }
        }
        $html .= "</tbody>";
        $html .= "</table>";
        return $this->renderText(json_encode($html));
    }

    public function cambiarUsuarioAction(){
        try{
            try {
                $modelos = array("mercurio30","mercurio31");
                $Transaccion = parent::startTrans($modelos);
                $response = parent::startFunc();
                $this->setResponse("ajax");
                $tipo = $this->getPostParam("tipo");
                $usuario = $this->getPostParam("usuario");
                $id = $this->getPostParam("id");
                if($tipo==1) $this->Mercurio30->updateAll("usuario='$usuario'","conditions: id='$id'");
                if($tipo==2) $this->Mercurio31->updateAll("usuario='$usuario'","conditions: id='$id'");
                if($tipo==3) $this->Mercurio32->updateAll("usuario='$usuario'","conditions: id='$id'");
                if($tipo==4) $this->Mercurio34->updateAll("usuario='$usuario'","conditions: id='$id'");
                if($tipo==5) $this->Mercurio33->updateAll("usuario='$usuario'","conditions: id='$id'");
                if($tipo==6) $this->Mercurio33->updateAll("usuario='$usuario'","conditions: id='$id'");
                if($tipo==7) $this->Mercurio35->updateAll("usuario='$usuario'","conditions: id='$id'");
                if($tipo==8) $this->Mercurio43->updateAll("usuario='$usuario'","conditions: id='$id'");
                parent::finishTrans();
                $response = parent::successFunc("Asignacion de Permisos Exitosa");
                return $this->renderText(json_encode($response));
            } catch(DbException $e){
                parent::setLogger($e->getMessage());
                parent::ErrorTrans();
            }
        } catch (TransactionFailed $e){
            $response = parent::errorFunc("Error al Asignar Permisos");
            return $this->renderText(json_encode($response));
        }
    }

    public function usuariosDisponiblesAction(){
        $this->setResponse("ajax");
        $id = $this->getPostParam("id");
        $tipo = $this->getPostParam("tipo");
        $agencia = $this->getPostParam("agencia");
      if($agencia=="1" ){
          $agencia="NIVA";
      }if($agencia==2){
          $agencia="GRZN";
      }if($agencia==3){
         $agencia="PITL";
      }if($agencia==4){
         $agencia="PLTA";
      }
        $response = Tag::select("usuario_cambio",$this->Gener02->findAllBySql("SELECT usuario,nombre from gener02 where usuario in (select usuario from mercurio44 where estado='A') AND tipfun='$agencia'"),"using: usuario,nombre");
        $response .= Tag::button("Cambiar Usuario","onclick: cambiarUsuario('$tipo','$id');");
        return $this->renderText(json_encode($response));
    }

}
?>
