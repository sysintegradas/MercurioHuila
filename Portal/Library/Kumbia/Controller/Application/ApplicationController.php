<?php

/**
 * Kumbia Enterprise Framework
 *
 * LICENSE
 *
 * This source file is subject to the New BSD License that is bundled
 * with this package in the file docs/LICENSE.txt.
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@loudertechnology.com so we can send you a copy immediately.
 *
 * @category	Kumbia
 * @package		Controller
 * @subpackage	ApplicationController
 * @copyright	Copyright (c) 2008-2011 Louder Technology COL. (http://www.loudertechnology.com)
 * @copyright	Copyright (c) 2005-2009 Andres Felipe Gutierrez (gutierrezandresfelipe at gmail.com)
 * @license		New BSD License
 * @version		$Id: ApplicationController.php 82 2009-09-13 21:06:31Z gutierrezandresfelipe $
 */

/**
 * ApplicationController
 *
 * Es la clase principal para controladores del framework
 *
 * @category	Kumbia
 * @package		Controller
 * @subpackage	ApplicationController
 * @copyright 	Copyright (c) 2008-2011 Louder Technology COL. (http://www.loudertechnology.com)
 * @copyright	Copyright (c) 2005-2009 Andres Felipe Gutierrez (gutierrezandresfelipe at gmail.com)
 * @license		New BSD License
 */
class ApplicationController extends Controller {

    private $log;
	/**
	 * Visualiza una vista en el controlador actual
	 *
	 * @access 	protected
	 * @param 	string $view
	 */
	protected function render($view){
		$viewsDir = Core::getActiveViewsDir();
		$path = $viewsDir.'/'.$view.'.phtml';
		if(Core::fileExists($path)){
			foreach(EntityManager::getEntities() as $modelName => $model){
				$$modelName = $model;
			}
			foreach($this as $_var => $_value){
				$$_var = $_value;
			}
			foreach(View::getViewParams() as $_key => $_value){
				$$_key = $_value;
			}
			include KEF_ABS_PATH.$path;
		} else {
			throw new ApplicationControllerException('No existe la vista ó no se puede leer el archivo');
		}
	}

	/**
	 * Visualiza un texto en la vista actual
	 *
	 * @access	protected
	 * @param	string $text
	 */
	protected function renderText($text){
		echo $text;
	}

	/**
	 * Visualiza una vista parcial en el controlador actual
	 *
	 * @access	protected
	 * @param	string $partial
	 * @param	string $values
	 */
	protected function renderPartial($partial, $values = ''){
		View::renderPartial($partial, $values);
	}

	/**
	 * La definición de este método indica si se debe exportar
	 * las variables públicas
	 *
	 * @access 	public
	 * @return 	true
	 */
	public function isExportable(){
		return true;
	}


    /* 
     * Function para encriptar la clave
     */

    public function encriptar($clave=''){
        $mclave = '';
        for($i=0;$i<strlen($clave);$i++){
            if($i%2!=0){
                $x=6;
            }else{
                $x=-4;
            }
            $mclave .= chr(ord(substr($clave,$i,1)) + $x + 5);
        }
        return $mclave;
    }

    /*
    * function para desencriptar
    */

    public function desencriptar($clave=''){
        $mclave = '';
        for($i=0;$i<strlen($clave);$i++){
            if($i%2!=0){
                $x=-6;
            }else{
                $x=+4;
            }
            $mclave .= chr(ord(substr($clave,$i,1)) + $x - 5);
        }
        return $mclave;
    }

    /* Function para iniciar la transacion con sus tablas
     * Ejemplo:
     * $modelos = array("subsi02","subsi15","subsi20");
     * parent::startTrans($modelos);
     */
    public function startTrans($tables){
        $Transaccion = TransactionManager::getUserTransaction();
        $Transaccion->begin();
        $tables = is_array($tables) ? $tables : array($tables);
        foreach($tables as $mtables){
            $mtables = ucfirst($mtables);
            $this->$mtables->setTransaction($Transaccion);
        }
        return $Transaccion;
    }

    /* Function para Terminar la transacion 
     * Ejemplo:
     * parent::errorTrans();
     */

    public function ErrorTrans($msg=""){
        $Transaccion = TransactionManager::getUserTransaction();
        $Transaccion->rollback($msg);
    }

    /* Function para terminar la transacion 
     * Ejemplo:
     * parent::finishTrans();
     */

    public function finishTrans(){
        $Transaccion = TransactionManager::getUserTransaction();
        $Transaccion->commit();
    }

    public function setLogger($message){
        $user = self::getActUser("usuario");
        $this->log = new Logger("File","{$user}.log");
        $this->log->setFormat("%date% > %controller%/%action% > Message => %message%");
        if(is_array($message)){
            foreach($message as $mmessage){
                $this->log->log($mmessage->getMessage());
            }
        }else{
            $this->log->log($message);
        }
    }

    /*
     * function para unir las detalle y por si las detalles son de mas llaves primarias 
     * validar
     */

    protected static function unificarDetalle(&$item,$key,$a) {
        $item = array($item,$a[$key]);
    }

    public function startFunc(){
        $response['flag']=true;
        $response['msg']='';
        $response['data']='';
        return $response;
    } 

    public function errorFunc($msg=''){
        $response['flag']=false;
        $response['msg']=$msg;
        $response['data']='';
        return $response;
    }

    public function successFunc($msg='',$data=''){
        $response['flag']=true;
        $response['msg']=$msg;
        $response['data']=$data;
        return $response;
    }

    /*
     *  Retorna un campo de la informacion del usuario actual
     */
    public function getActUser($field='usuario'){
        $user = Auth::getActiveIdentity();
        if(isset($user[$field])){
          return $user[$field];
        }else{
          return "nologin";
        }
        
    }

    
    public function firstFocus($model){
        echo "<script>firstFocus('$model');</script>";
    }

    public function disabledInput($model){
        $table = ucfirst($model);
        $table = new $table();
        $campos_key = $table->getPrimaryKeyAttributes();
        foreach($campos_key as $key){
            echo "<script>disabledInput('$model','$key');</script>";
        }
    }

    //---------------------------------------------------------------//

    /* funciones realizadas para un futuro */

       /* Function para realizar un save 
     * si no se colocan el campo en el arreglo lo busca en $_POST
     * Ejemplo:
     * $valores = array("nit"=>"1234","razsoc"=>"Prueba");
     * parent::insert("Subsi02",$valores,true);
     */

    public function insert($table,$valores=array(),$tx=true,$tipo="ms"){
        try{
            try{
                $mtable = strtolower($table);
                $table = ucfirst($table);
                $table = new $table();
                $campos = $table->getAttributes();
                if($tipo=="ms"){
                    foreach($campos as $mcampo){
                        if(!isset($valores[$mcampo])){
                            $valores[$mcampo] = $this->getPostParam($mcampo."_$mtable");
                        }
                        if($valores[$mcampo]=="")continue;
                        $table->writeAttribute($mcampo,$valores[$mcampo]);
                    }
                    if($tx==true){
                        $Transaccion = TransactionManager::getUserTransaction();
                        $table->setTransaction($Transaccion);
                    }
                    if(!$table->save()){
                        self::setLogger($table->getMessages());
                        if($tx==true) $Transaccion->rollback();
                    }
                }elseif($tipo=="dt"){
                    $campos_key = $table->getPrimaryKeyAttributes();
                    $i=1;
                    foreach($campos_key as $key){
                        if(!in_array($key,$valores)){
                            $vari = "a$i";
                            $$vari = $this->getPostParam($key."_$mtable");
                            if(!is_array($$vari)){
                                $$vari=array($$vari);
                            }
                            $i++;
                        }
                    }
                    if(!isset($valores['condi'])){
                        $valores['condi_sql'] = " 1=1 ";
                    }
                    if($i>2)
                        array_walk($a1,array('applicationController','unificarDetalle'),$a2);
                    foreach($table->find($valores['condi_sql']) as $mntable){
                        $vari_final = array();
                        $condi = array();
                        $condi[] = $valores['condi_sql'];
                        foreach($campos_key as $key){
                            $condi[] = "$key = '{$mntable->readAttribute($key)}' ";
                            if($i>2)
                                $vari_final[] = "{$mntable->readAttribute($key)}";
                            else
                                $vari_final = "{$mntable->readAttribute($key)}";
                        }
                        if(!in_array($vari_final,$a1,true)){
                            try{
                                if($tx==true){
                                    $Transaccion = TransactionManager::getUserTransaction();
                                    $mntable->setTransaction($Transaccion);
                                }
                                $condi = join(" AND ",$condi);
                                $mntable->deleteAll($condi);
                            }catch (DbException $e){
                                self::setLogger($condi);
                                self::setLogger($e->getMessages());
                                if($tx==true) $Transaccion->rollback();
                                else return false;
                            }
                        }
                    }
                    for($i=0;$i<count($this->getPostParam($campos[0]."_$mtable"));$i++){
                        foreach($campos as $mcampo){
                            if(!isset($valores[$mcampo])){
                                $valores[$mcampo] = $this->getPostParam($mcampo."_$mtable");
                            }
                            if(is_array($valores[$mcampo])){
                                if($valores[$mcampo][$i]=="")continue;
                                $table->writeAttribute($mcampo,$valores[$mcampo][$i]);
                            }else{
                                if($valores[$mcampo]=="")continue;
                                $table->writeAttribute($mcampo,$valores[$mcampo]);
                            }
                        }
                        if($tx==true){
                            $Transaccion = TransactionManager::getUserTransaction();
                            $table->setTransaction($Transaccion);
                        }
                        if(!$table->save()){
                            self::setLogger($table->getMessages());
                            if($tx==true) $Transaccion->rollback();
                        }
                    }
                }
                return $table;
            }catch (DbException $db){
                self::setLogger($db->getMessage());
                if($tx==true) $Transaccion->rollback();
                else return false;
            }
        } catch (TransactionFailed $e){
            return false;
        }
    }

    /*
     *  Genera un div que actuara como boton dentro de una captura
     */
    public function createButton($buttons=array(),$help=''){
        $code = '';
        foreach($buttons as $key => $value){
            $title = isset($value['title']) ? $value['title'] : $key;
            $class = isset($value['class']) ? $value['class'] : strtolower($key);
            if(isset($value['onclick'])){
                $onclick = "onclick='$value[onclick]'";
            }else{
                $url = Router::getController();
                $action = Router::getController().'/';
                if(strtolower($key)=="guardar"){
                    $action .= "index";
                    $onclick = "onclick='guardar_default(\"$action\",\"$url\",\"$help\")'";
                }elseif(strtolower($key)=="reporte"){
                    $action .= "reporte";
                    $onclick = "onclick='report_default(\"$action\")'";
                }elseif(strtolower($key)=="generar"){
                    $onclick = "onclick='generar_default(\"".Router::getController()."\",\"".Router::getAction()."\",\"".$value['type']."\")'";
                }else{
                    $action .= strtolower($key)=='cancelar' ? 'index' : strtolower($key);
                    $onclick = "onclick='redirect(\"$action\",\"$url\",\"$help\")'";
                }
            }
            $code .= '<div class="capture-button '.$class.'" '.$onclick.' title="'.$title.'">';
            $code .= $key;
            $code .= '</div>';
        }
        if(!(isset($buttons['Reporte']['formats']) && $buttons['Reporte']['formats']==false)){
            $code .= '<div class="set_format_report" style="display: none;">';
            $formats = isset($buttons['Reporte']['format']) ? $buttons['Reporte']['format'] : 'Pdf,Excel';
            $controller = Router::getController();
            $code .= '<div class="title-set-format">Seleccione el Formato</div>';
            foreach(explode(',',$formats) as $format){
                $code .= '<div><input type="radio" name="'.$controller.'-'.$action.'-format" id="'.$controller.'-'.$action.'-format-'.$format.'" value='.strtolower($format).' /><label for="'.$controller.'-'.$action.'-format-'.$format.'">'.$format.'</label></div>';
            }
            $code .= '</div>';
        }
        if($help!=''){
            $code .= Tag::image('opciones/help.jpg','class: capture-help-button',"onclick: help(this)");
            $code .= '<div class="capture-help" style="display: none;">'.$help.'</div>';
        }
        $code .= '<div class="capture-separator"></div>';
        echo $code;
    }

    /*
     *  Genera un div que actuara como boton dentro de una captura
     */
    public function createCapture($model,$tipo_capture='new',$action='',$valores=array()){
        if(is_array($model)){
            $campos = array_keys($model);
            $opciones = $model;
        }else{
            $table = ucfirst($model);
            $table = new $table();
            $opciones = $table->opciones();
            $campos = $table->getAttributes();
        }
        if($action==''){
            $code = Tag::form("$model/guardar", "id: form_$model");
        }else{
            $code = Tag::form($action, "id: form_".Router::getAction());
        }
        $code .= '<div class="capture-content">';
        $code .= '<table align="center">';
        foreach($campos as $mcampo){
            $attributes = $opciones[$mcampo];
            if(isset($attributes['notCapture']) && $attributes['notCapture']==true)continue;
            if(isset($attributes['notNew']) && $attributes['notNew']==true && $tipo_capture=='new')continue;
            if(isset($attributes['notEdit']) && $attributes['notEdit']==true && $tipo_capture=='edit')continue;
            if($tipo_capture=='edit' && isset($valores[$mcampo]))$attributes['value']=$valores[$mcampo];
            $code .= '<tr>';
            $attributes['null'] = (isset($attributes['null'])) ? $attributes['null'] : false;
            $null = "";
            if($attributes['null']==false)$null="*";
            $descrip = (isset($attributes['descripcion'])) ? ucfirst($attributes['descripcion']) : ucfirst($mcampo);
            $code .= "<td align='right'><label for='$mcampo'>$null".$descrip."</label> :</td>";
            $code .= "<td align='left'>".self::getInput($mcampo,$attributes,$model)."</td>";
            $code .="</tr>";
        }
        $code .= '</table>';
        $code .= '</div>';
        $code .= Tag::endForm();
        echo $code;
    }

    public function getInput($name,$attributes,$model=''){
        $name .="_$model";
        switch($attributes['type']){
            case 'int':
                $options = array($name, 'size' => $attributes['size']);
                if(isset($attributes['maxlength'])){
                    $options['maxlength'] = $attributes['maxlength'];
                }else{
                    $options['maxlength'] = $attributes['size'];
                }
                if(isset($attributes['value'])){
                    $options['value'] = $attributes['value'];
                }
                if(isset($attributes['step'])){
                    $options['step'] = $attributes['step'];
                }
                if(isset($attributes['max'])){
                    $options['max'] = $attributes['max'];
                }
                if(isset($attributes['min'])){
                    $options['min'] = $attributes['min'];
                }
                if($attributes['null']==false){
                    $options['placeholder'] = "Obligatorio";
                    $options['required'] = "required";
                }
                if(isset($attributes['value'])){
                    $options['value'] = $attributes['value'];
                }
                if(isset($attributes['disabled'])){
                    $options['disabled'] = $attributes['disabled'];
                }
                if(isset($attributes['onkeydown'])){
                    $options['onkeydown'] = $attributes['onkeydown'];
                }
                if(isset($attributes['onkeyup'])){
                    $options['onkeyup'] = $attributes['onkeyup']."valMaxlength(event,this,\"$model\");";
                }else{
                    $options['onkeyup'] = "valMaxlength(event,this,\"$model\");";
                }
                if(isset($attributes['onchange'])){
                    $options['onchange'] = $attributes['onchange'];
                }
                if(isset($attributes['onblur'])){
                    $options['onblur'] = $attributes['onblur'];
                }
                return Tag::numericField($options);
                break;
            case 'time':
                return "<input type='time' name='$name' id='$name'>";
                break;
            case 'select':
                $attributes['model'] = ucfirst($attributes['model']);
                $md = new $attributes['model']();
                if(isset($attributes['select'])){
                    $md = $md->findAllBySql($attributes['select']);
                }else{
                    $condi = "1=1";
                    if(isset($attributes['conditions'])){
                        $condi = $attributes['conditions'];
                    }
                    $md = $md->find($condi,"columns: {$attributes['using']}");
                }
                $options = array($name, $md,"using" => $attributes['using']);
                if(!isset($attributes['use_dummy'])){
                    $options['use_dummy'] = true;
                }
                if(isset($attributes['value'])){
                    $options['value'] = $attributes['value'];
                }
                if(isset($attributes['onkeydown'])){
                    $options['onkeydown'] = $attributes['onkeydown'];
                }
                if(isset($attributes['onchange'])){
                    $options['onchange'] = $attributes['onchange'];
                }
                if(isset($attributes['onblur'])){
                    $options['onblur'] = $attributes['onblur'];
                }
                return Tag::select($options);
                //return Tag::select($options)."<script>new Chosen($('$name'))</script>";
                break;
            case 'domain':
                $options = array($name, $attributes['content']);
                if(!isset($attributes['use_dummy'])){
                    $options['use_dummy'] = true;
                }
                if(isset($attributes['value'])){
                    $options['value'] = $attributes['value'];
                }
                if(isset($attributes['onkeydown'])){
                    $options['onkeydown'] = $attributes['onkeydown'];
                }
                if(isset($attributes['onchange'])){
                    $options['onchange'] = $attributes['onchange'];
                }
                if(isset($attributes['onblur'])){
                    $options['onblur'] = $attributes['onblur'];
                }
                //return Tag::selectStatic($options)."<script>new Chosen($('$name'))</script>";
                return Tag::selectStatic($options);
                break;
            case 'date':
                return TagUser::calendar($name,"size: 10");
                break;
            case 'textarea':
                return Tag::textArea(array($name, 'cols' => $attributes['cols'], 'rows' => $attributes['rows']));
                break;
            case 'passwd':
                $options = array($name, 'size' => $attributes['size']);
                if(isset($attributes['maxlength'])){
                    $options['maxlength'] = $attributes['maxlength'];
                }else{
                    $options['maxlength'] = $attributes['size'];
                }
                if(isset($attributes['value'])){
                    $options['value'] = $attributes['value'];
                }
                if(isset($attributes['disabled'])){
                    $options['disabled'] = $attributes['disabled'];
                }
                if($attributes['null']==false){
                    $options['placeholder'] = "Obligatorio";
                    $options['required'] = "required";
                }
                if(isset($attributes['onkeydown'])){
                    $options['onkeydown'] = $attributes['onkeydown'];
                }
                if(isset($attributes['onkeyup'])){
                    $options['onkeyup'] = $attributes['onkeyup']."valMaxlength(event,this,\"$model\");";
                }else{
                    $options['onkeyup'] = "valMaxlength(event,this,\"$model\");";
                }
                if(isset($attributes['onchange'])){
                    $options['onchange'] = $attributes['onchange'];
                }
                if(isset($attributes['onblur'])){
                    $options['onblur'] = $attributes['onblur'];
                }
                return Tag::passwordField($options);
                break;
            case 'help':
                $options = array(
                    $name,
                );
                if(isset($field['size'])){
                    $options['size'] = $field['size'];
                }
                if(isset($field['maxlength'])){
                    $options['maxlength'] = $field['maxlength'];
                }
                $options['onkeypress'] = 'auto("'.$name.'",event,"'.$model.'")';
                if(isset($field['onkeypress'])){
                    $options['onkeypress'] .= ';'.$field['onkeypress'];
                }
                if($attributes['null']==false){
                    $options['placeholder'] = "HELP Obligatorio";
                    $options['required'] = "required";
                }
                $code_input = Tag::textField($options);
                $code_input .= '<span id="indicator_'.$name.'" style="display: none;">loading..!!</span>';
                $code_input .= '<div id="autocomplete_choices_'.$name.'" class="autocomplete" style="display: none;"></div>';
                return $code_input;
                break;
            default:
                $options = array($name, 'size' => $attributes['size']);
                if(isset($attributes['maxlength'])){
                    $options['maxlength'] = $attributes['maxlength'];
                }else{
                    $options['maxlength'] = $attributes['size'];
                }
                if(isset($attributes['value'])){
                    $options['value'] = $attributes['value'];
                }
                if(isset($attributes['disabled'])){
                    $options['disabled'] = $attributes['disabled'];
                }
                if($attributes['null']==false){
                    $options['placeholder'] = "Obligatorio";
                    $options['required'] = "required";
                }
                if(isset($attributes['onkeydown'])){
                    $options['onkeydown'] = $attributes['onkeydown'];
                }
                if(isset($attributes['onkeyup'])){
                    $options['onkeyup'] = $attributes['onkeyup']."valMaxlength(event,this,\"$model\");";
                }else{
                    $options['onkeyup'] = "valMaxlength(event,this,\"$model\");";
                }
                if(isset($attributes['onchange'])){
                    $options['onchange'] = $attributes['onchange'];
                }
                if(isset($attributes['onblur'])){
                    $options['onblur'] = $attributes['onblur'];
                }
                if(isset($attributes['ToUpperCase']) && $attributes['ToUpperCase']==false)
                    return Tag::textField($options);
                else
                    return Tag::textUpperField($options);
                break;
        }
    }

    /*
     *  Crea la visualizacion de datos de una tabla
     */
    public function createIndex($model=''){
        $code = "";
        echo $code;
    }

    /*
     * funcion para crear el paginate 
     * recibe el modelo 
     * option (1=> campo not null para mostrar
     *         2=> todos los campos
     *         3=> los campos que envien)
     * Query es la condicion
     * pagina en que pagina va
     */
    public function search($controller='',$model='',$option='1',$query='1=1',$pagina="1",$help=''){
        if($pagina==0)return "";
        $table = ucfirst($model);
        $table = new $table();
        $attributes = $table->opciones(); 
        if($option=='1'){
            $campos = $table->getNotNullAttributes();
        }elseif($option=='2'){
            $campos = $table->getAttributes();
        }elseif($option=='3'){
            $campos = array_keys($model);
            $attributes = $option;
        }
        $campos_select = join(",",$campos);
        $campos_key = $table->getPrimaryKeyAttributes();
        $select = $table->findAllBySql("SELECT $campos_select FROM ".strtolower($model)." WHERE $query");
        $paginate = Tag::paginate($select,$pagina,5);
        if(count($paginate->items)>0){
            $code = "";
            $code .= '<div class="capture-index">';
            $code .= '<div class="content-search-box">';
            $code .= '<span class="w-input">';
            $code .= '<span class="i-cancel"></span>';
            $code .= '<span class="i-current">Todos:</span>';
            $code .= '<input type="text" class="w-help" onKeyUp="searchPressKey(this, '."'$controller'".', '."'$model'".', event);" />';
            $code .= '<span class="i-search" onclick="searchSetRequest(this, '."'$controller'".', '."'$model'".');"></span>';
            $code .= '<span class="i-more" onclick="searchSeeMore(this);"></span>';
            $code .= '<div class="w-list" style="display: none;">';
            $code .= '<ul>';
            $code .= '<li param="all">Todos</li>';
            $code .= '<li class="separator"></li>';
            foreach($campos as $mcampo){
                $opciones = $attributes[$mcampo];
                $descri = (isset($opciones['descripcion'])) ? ucfirst($opciones['descripcion']) : ucfirst($mcampo);
                $code .= '<li param="'.$mcampo.'">'.$descri.'</li>';
            }
            $code .= '</ul>';
            $code .= '</div>';
            $code .= '</span>';
            $code .= '<span class="clear"></span>';
            $code .= '</div>';
            //$code .= '<hr class="separador-inicio">';
            $code .= '<div class="capture-search">';
            $code .= "<table class='capture-search-data'>";
            $code .= "<thead>";
            $code .= "<tr>";
            foreach($campos as $mcampo){
                $opciones = $attributes[$mcampo];
                if(isset($opciones['notSearch']) && $opciones['notSearch']==true)continue;
                $descri = (isset($opciones['descripcion'])) ? ucfirst($opciones['descripcion']) : ucfirst($mcampo);
                $code .= "<th>$descri</th>";
            }
            $code .= "<th>&nbsp;</th>";
            $code .= "<th>&nbsp;</th>";
            $code .= "</tr>";
            $code .= "</thead>";
            $code .= "<tbody>";
            $num = 1;
            foreach($paginate->items as $mquery){
                $condi = "";
                $code .= "<tr>";
                foreach($campos as $mcampos){
                    $opciones = $attributes[$mcampos];
                    if(isset($opciones['notSearch']) && $opciones['notSearch']==true)continue;
                    $code .= "<td>{$mquery->readAttribute($mcampos)}</td>";
                    if(in_array($mcampos,$campos_key)){
                        $condi .=$mcampos."=\'".$mquery->readAttribute($mcampos)."\' AND ";
                    }
                }
                if($condi!="")$condi=substr($condi,0,-4);
                $code .= "<td onclick=\"edit_default('$controller/editar','$controller','$help','$condi')\">Editar</td>";
                $code .= "<td onclick=\"remove_default('$controller','$condi',this)\">Borrar</td>";
                $code .= "</tr>";
                $num++;
            }
            $code .="</tr>";
            $code .= "</tbody>";
            $code .= "</table>";
            $code .= "<table class='navegacion'>";
            $code .="<tr>";
            $code .="<td align='right' style='border: none;'>";
            $code .="<table>";
            $code .="<tr>";
            $code .="<td>".Tag::image("search/first.png","style: cursor: pointer;","onclick: paginate('$controller','$model','$option','$query',1,'$help');")."</td>";
            $code .="<td>";
            $code .=Tag::image("search/prev.png","style: cursor: pointer;","onclick: paginate('$controller','$model','$option','$query',{$paginate->before},'$help');");
            $code .="</td>";
            $code .="<td valign='middle'>";
            $code .=Tag::numericField("num_pag","size: 2","maxlength: 3","value: {$paginate->current}","min: 1","max: {$paginate->total_pages}","style: text-align: right;","onchange: {$model}_evtNumpag(this,event)","onKeyUp: {$model}_evtNumpag(this,event)");
            $code .= "<script type=\"text/javascript\"> {$model}_evtNumpag= function(elem,evt){";
            $code .= "irPag('$controller','$model','$option','$query','$help',{$paginate->total_pages},elem,evt);";
            $code .= "}</script>";
            $code .="<b style='font-size: 12px;'>/ ".$paginate->total_pages."</b>";
            $code .="</td>";
            $code .="<td>";
            $code .=Tag::image("search/next.png","border: 0","style: cursor: pointer;","onclick: paginate('$controller','$model','$option','$query',{$paginate->next},'$help');");
            $code .="</td>";
            $code .="<td>";
            $code .=Tag::image("search/last.png","border: 0","style: cursor: pointer;","onclick: paginate('$controller','$model','$option','$query',{$paginate->total_pages},'$help');");
            $code .="</td>";
            $code .="</tr>";
            $code .="</table>";
            $code .="</td>";
            $code .="</tr>";
            $code .= "</table>";
            $code .= '</div>';
            $code .= '</div>';
            return $code;
        }else{
            return "No se Encontro resultado"; 
        }
    }

    public function remove($model,$condi){
        try{
            try{
                $model = ucfirst($model);
                self::startTrans($model);
                if($this->$model->deleteAll($condi)===false){
                    self::setLogger($this->$model->getMessages());
                    $Transaccion->rollback();
                }
                self::finishTrans();
                return true;
            }catch (DbException $db){
                self::setLogger($db->getMessage());
                self::errorTrans();
            }
        } catch (TransactionFailed $e){
            return false;
        }
    }

    public function createCaptureDetalle($model=''){
        if(is_array($model)){
            $campos = array_keys($model);
            $opciones = $model;
        }else{
            $table = ucfirst($model);
            $table = new $table();
            $opciones = $table->opciones();
            $campos = $table->getAttributes();
        }
        $campos_detalle = "'".join("_$model','",$campos)."_$model'";
        $campos_key = $table->getPrimaryKeyAttributes();
        foreach($campos_key as $key){
            $condi_detalle_key[] = " $model.getRow(i)['{$key}_$model']==\$F('{$key}_$model') ";
        }
        $condi_detalle_key = join(" && ",$condi_detalle_key);
        $code = '';
        $code .= '<script type="text/javascript">';
        $code .= "save_$model = function(){";
        $code .= "if(!val_$model.valide()){";
        $code .= "Messages_Validator.ver(val_$model);";
        $code .= "return;";
        $code .= "}";
        $code .= "error_$model = false;";
        $code .= "for(i=1;i<=$model.getNumRows();i++){";
        $code .= "if($condi_detalle_key){";
        $code .= "if(!$model.isEdit()){";
        $code .= "Messages.display(['El registro ya existe'],'warning');";
        $code .= "error_$model=true;";
        $code .= "$('{$campos_key[0]}_$model').value='';";
        $code .= "$('{$campos_key[0]}_$model').focus();";
        $code .= "}";
        $code .= "}";
        $code .= "}";
        $code .= "if(!error_$model){";
        $code .= "$model.save({";
        $code .= "afterSave: function(){";
        $code .= "new Effect.Fade('edit_msg',{queue: 'end', scope: 'detalle'});";
        $code .= "}";
        $code .= "});";
        $code .= "$('{$campos_key[0]}_$model').enable();";
        $code .= "$('{$campos_key[0]}_$model').focus();";
        $code .= "}";
        $code .= "};";
        $code .= "removeRow_$model = function(row){";
        $code .= "$model.remove(row,{";
        $code .= "beforeRemove: function(elem){";
        $code .= "if($model.isEdit(elem)){";
        $code .= "Messages.display(['El registro actual esta en edicion'],'info');";
        $code .= "return false;";
        $code .= "}";
        $code .= "}";
        $code .= "});";
        $code .= "$('{$campos_key[0]}_$model').focus();";
        $code .= "};";
        $code .= "edit_$model = function(e){";
        $code .= "$('{$campos_key[0]}_$model').disable();";
        $code .= "$model.edit(e,{";
        $code .= "beforeEdit: function(){";
        $code .= "if($model.isEdit()){";
        $code .= "Messages.display(['Hay un registro en edicion'],'info');";
        $code .= "return false;";
        $code .= "}";
        $code .= "},";
        $code .= "afterEdit: function(){";
        $code .= "new Effect.Appear('edit_msg',{queue: 'end', scope: 'detalle'});";
        $code .= "}";
        $code .= "});";
        $code .= "$('{$campos[1]}_$model').focus();";
        $code .= "};";
        $code .= "cancel_$model = function(){";
        $code .= "$('{$campos_key[0]}_$model').enable();";
        $code .= "$model.cancelEdit();";
        $code .= "new Effect.Fade('edit_msg',{queue: 'end', scope: 'detalle'});";
        $code .= "$('{$campos_key[0]}_$model').focus();";
        $code .= "};";
        $code .= "saveIntro_$model = function(evt,element){";
        $code .= "Bs.getKey(evt,{";
        $code .= "action: function(kc){";
        $code .= "if(kc==Event.KEY_RETURN) {";
        $code .= "element.value=element.value.toUpperCase();";
        $code .= "save_$model();";
        $code .= "}";
        $code .= "}";
        $code .= "});";
        $code .= "};";
        $code .= '</script>';
        $code .= '<div class="capture-content">';
        $code .= '<table align="center">';
        $code .= '<tr>';
        $code .= '<td colspan="'.(count($campos)+1).'">';
        $code .= '<div id="edit_msg" style="display: none; cursor: pointer; background-color: #DDFFDD" onclick="cancel_'.$model.'();">Registro en Edici&oacute;n (Click aqui para Cancelar)</div>';
        $code .= '</td>';
        $code .= '</tr>';
        $code .= '<tr>';
        foreach($campos as $mcampo){
            $attributes = $opciones[$mcampo];
            if(isset($attributes['notCapture']) && $attributes['notCapture']==true)continue;
            $attributes['null'] = (isset($attributes['null'])) ? $attributes['null'] : false;
            $attributes['onkeydown'] = "saveIntro_$model(event,this);";
            $code .= "<td align='center'>".self::getInput($mcampo,$attributes,$model)."</td>";
        }
            $code .= "<td align='center'>".Tag::image("Plus.png","title: Agregar Fila","onclick: save_$model();")."</td>";
        $code .="</tr>";
        $code .= '<tr>';
        foreach($campos as $mcampo){
            $attributes = $opciones[$mcampo];
            if(isset($attributes['notCapture']) && $attributes['notCapture']==true)continue;
            $attributes['null'] = (isset($attributes['null'])) ? $attributes['null'] : false;
            $descrip = (isset($attributes['descripcion'])) ? ucfirst($attributes['descripcion']) : ucfirst($mcampo);
            $null = "";
            if($attributes['null']==false)$null="*";
            $code .= "<td align='center'><label for='$mcampo'>$null".$descrip."</label></td>";
        }
        $code .="</tr>";
        $code .= '</table>';
        $code .= '</div>';
        $code .= Tag::form("$model/guardar", "id: form_$model");
        $code .= '<div id="capt_det">';
        $code .= '<table align="center" class="capture-search-data">';
        $code .= '<thead align="center">';
        $code .= '<tr>';
        foreach($campos as $mcampo){
            $attributes = $opciones[$mcampo];
            $attributes['null'] = (isset($attributes['null'])) ? $attributes['null'] : false;
            $descrip = (isset($attributes['descripcion'])) ? ucfirst($attributes['descripcion']) : ucfirst($mcampo);
            $code .= "<th align='center'>".$descrip."</th>";
        }
        $code .= "<th>&nbsp;</th>";
        $code .= '</tr>';
        $style="block";
        if($table->count()>0)$style="none";
        $code .= '<tr id="empty_msg" style="display: '.$style.'">';
        $code .= '<td>';
        if($table->count()==0){
            $code .= 'No hay Tipo De Calificacion que mostrar';
        }else{
            $code .= '';
        }
        $code .= '</td>';
        $code .= '</tr>';
        $code .= '</thead>';
        if($table->count()==0){
            $code .= '<tr ondblclick="edit_'.$model.'(this);"  title="DobleClick para Editar">';
            foreach($campos as $mcampo){
                $code .= "<td class='{$mcampo}_{$model}_'></td>";
            }
            $code .= '<td class="det_img" align="center">'.Tag::image("Minus.png","title: Borrar Fila","onclick: removeRow_$model(this.ancestors()[1])").'</td>';
            $code .= '</tr>';
        }else{
            $i = 0;
            foreach($table->find() as $mtable){
                $code .= '<tr class="det_row'.($i%2+1).'" height="25px" ondblclick="edit_'.$model.'(this);"  title="DobleClick para Editar">';
                foreach($campos as $mcampo){
                    $code .= "<td class='{$mcampo}_{$model}_'>".Tag::hiddenField("{$mcampo}_{$model}[]","value: ".$mtable->readAttribute($mcampo))."{$mtable->readAttribute($mcampo)}</td>";
                }
                $code .= '<td class="det_img" align="center">'.Tag::image("Minus.png","title: Borrar Fila","onclick: removeRow_$model(this.ancestors()[1])").'</td>';
                $code .= '</tr>';
            }
        }
        $code .= '</table>';
        $code .= '</div>';
        $code .= Tag::endForm();
        $code .= "<script>";
        $code .= $model.' = new Detail({';
        $code .= 'view: "capt_det",';
        $code .= "fields: [$campos_detalle],";
        $code .= "classTr: ['det_row1','det_row2'],";
        $code .= "empty: 'empty_msg'";
        $code .= '});';
        $code .= "$('{$campos_key[0]}_$model').focus();";
        $code .= "val_$model = new Validator();";
        foreach($campos as $mcampo){
            $attributes = $opciones[$mcampo];
            if(isset($attributes['notCapture']) && $attributes['notCapture']==true)continue;
            $attributes['null'] = (isset($attributes['null'])) ? $attributes['null'] : false;
            $descrip = (isset($attributes['descripcion'])) ? ucfirst($attributes['descripcion']) : ucfirst($mcampo);
            $null = "true";
            if($attributes['null']==false)$null="false";
            $code .= "val_$model.addField('{$mcampo}_$model','text',null,{'alias': '$descrip','isNull': $null});";
            //$code .= "val_$model.addField('$mcampo','{$attributes['type']}',null,{'alias': '$descrip','isNull': $null});";
        }
        $code .= "</script>";
        echo $code;
    }

    /*public function createReport($model,$query='1=1',$title='Reporte',$format='pdf'){
        $format = strtolower($format);
        $format = 'excel';
        $table = ucfirst($model);
        $table = new $table();
        $opciones = $table->opciones();
        $campos = $table->getAttributes();
        $_fields = array();
        foreach($campos as $mcampos){
            $attributes = $opciones[$mcampos];
            if(isset($attributes['notReport']))continue;
            $descrip = (isset($attributes['descripcion'])) ? ucfirst($attributes['descripcion']) : ucfirst($mcampos);
            $width = (isset($attributes['width'])) ? $attributes['width'] : $attributes['size'];
            $align = (isset($attributes['align'])) ? $attributes['align'] : 'C';
            $_fields[$mcampos] = array('header'=>$descrip,'size'=>$width,'align'=>$align);
        }
        if($format=='pdf'){
            $report = new UserReportPdf($title,$_fields);
        }elseif($format=='excel'){
            $report = new UserReportExcel($title,$_fields);
        }
        $report->startReport();
        foreach($table->find($query) as $mtable){
            foreach($campos as $mcampos){
                $attributes = $opciones[$mcampos];
                if(isset($attributes['notReport']))continue;
                $report->Put($mcampos,$mtable->readAttribute($mcampos));
            }
            $report->OutputToReport();
        }
        $file = "public/temp/reportes/reportes.pdf";
        echo $report->FinishReport($title);
        $this->setResponse('view');
        //header('Content-Type: application/x-download');
        //header('Content-Length: ');
        //header('Content-Disposition: attachment; filename="sss.pdf"');
        //echo "<script>win = window.open('$file','Descarga', 'width=800, height=750, toolbar=no, statusbar=no, scrollbars=yes, Menubar=yes');</script>";
    }*/

    public function fillCapture($model,$condi){
        $model = ucfirst($model);
        $table = new $model();
        $campos = $table->getAttributes();
        $opciones = $table->opciones();
        $table = $table->findFirst($condi);
        foreach($campos as $mcampo){
            $attributes = $opciones[$mcampo];
            if(isset($attributes['notCapture']) && $attributes['notCapture']==true)continue;
            if(isset($attributes['notEdit']) && $attributes['notEdit']==true)continue;
            if(isset($attributes['notValue']) && $attributes['notValue']==true)continue;
            $response[$mcampo] = $table->readAttribute($mcampo);
        }
        return $response;
    }

    public function createCaptureMaestraDet($model_maestro=array(),$model_detalle=array(),$tipo_capture="new",$valores=array()){
        $code = '';
        $i=0;
        foreach($model_maestro as $key => $value){
            $model = $key;
            $table = ucfirst($model);
            $table = new $table();
            $campos_notcap = $table->getPrimaryKeyAttributes();
            $i++;
            break;
        }
        $code .= '<script type="text/javascript">';
        foreach($model_detalle as $key => $value){
            $model = $key;
            $table = ucfirst($model);
            $table = new $table();
            $opciones = $table->opciones();
            $campos = $table->getAttributes();
            $campos_detalle = "'".join("_$model','",$campos)."'";
            $campos_key = $table->getPrimaryKeyAttributes();
            foreach($campos_key as $key){
                if(in_array($key,$campos_notcap))continue;
                $condi_detalle_key[] = " $model.getRow(i)['{$key}_$model']==\$F('{$key}_$model') ";
                $mcampos_key[] = $key;
            }
            $l = count($campos_key);
            $campos_key = $mcampos_key;
            $condi_detalle_key = join(" && ",$condi_detalle_key);
            $code .= "save_$model = function(){";
            $code .= "if(!val_$model.valide()){";
            $code .= "Messages_Validator.ver(val_$model);";
            $code .= "return;";
            $code .= "}";
            $code .= "error_$model = false;";
            $code .= "for(i=1;i<=$model.getNumRows();i++){";
            $code .= "if($condi_detalle_key){";
            $code .= "if(!$model.isEdit()){";
            $code .= "Messages.display(['El registro ya existe'],'warning');";
            $code .= "error_$model=true;";
            $code .= "$('{$campos_key[0]}_$model').value='';";
            $code .= "$('{$campos_key[0]}_$model').focus();";
            $code .= "}";
            $code .= "}";
            $code .= "}";
            $code .= "if(!error_$model){";
            $code .= "$model.save({";
            $code .= "afterSave: function(){";
            $code .= "new Effect.Fade('edit_msg',{queue: 'end', scope: 'detalle'});";
            $code .= "}";
            $code .= "});";
            $code .= "$('{$campos_key[0]}_$model').enable();";
            $code .= "$('{$campos_key[0]}_$model').focus();";
            $code .= "}";
            $code .= "};";
            $code .= "removeRow_$model = function(row){";
            $code .= "$model.remove(row,{";
            $code .= "beforeRemove: function(elem){";
            $code .= "if($model.isEdit(elem)){";
            $code .= "Messages.display(['El registro actual esta en edicion'],'info');";
            $code .= "return false;";
            $code .= "}";
            $code .= "}";
            $code .= "});";
            $code .= "$('{$campos_key[0]}_$model').focus();";
            $code .= "};";
            $code .= "edit_$model = function(e){";
            $code .= "$('{$campos_key[0]}_$model').disable();";
            $code .= "$model.edit(e,{";
            $code .= "beforeEdit: function(){";
            $code .= "if($model.isEdit()){";
            $code .= "Messages.display(['Hay un registro en edicion'],'info');";
            $code .= "return false;";
            $code .= "}";
            $code .= "},";
            $code .= "afterEdit: function(){";
            $code .= "new Effect.Appear('edit_msg',{queue: 'end', scope: 'detalle'});";
            $code .= "}";
            $code .= "});";
            $code .= "$('{$campos[$l]}_$model').focus();";
            $code .= "};";
            $code .= "cancel_$model = function(){";
            $code .= "$('{$campos_key[0]}_$model').enable();";
            $code .= "$model.cancelEdit();";
            $code .= "new Effect.Fade('edit_msg',{queue: 'end', scope: 'detalle'});";
            $code .= "$('{$campos_key[0]}_$model').focus();";
            $code .= "};";
            $code .= "saveIntro_$model = function(evt,element){";
            $code .= "Bs.getKey(evt,{";
            $code .= "action: function(kc){";
            $code .= "if(kc==Event.KEY_RETURN) {";
            $code .= "element.value=element.value.toUpperCase();";
            $code .= "save_$model();";
            $code .= "}";
            $code .= "}";
            $code .= "});";
            $code .= "};";
        }
        $code .= '</script>';
        $code .= '<div>';
        $code .= '<div>';
        $code .= '<table cellspacing="0" cellpadding="0" align="center" width="100%">';
        $code .= '<tr>';
        $tabs = count($model_detalle)+count($model_maestro);
        $code .= '<td width="'.(140/$tabs).'px" class="null_tab"></td>';
        $i = 1;
        $model = "";
        foreach($model_maestro as $key => $value){
            if($model=="")$model = $key;
            $class = $i==1 ? 'active_tab' : 'inactive_tab';
            $code .= '<td width="'.($tabs*20).'px" valign="middle" class="tab_basic '.$class.'" id="ptab'.$i.'" onclick="new Tabs.setActiveTab(this, '.$i.')">'.$value.'</td>';
            $i++;
        }
        foreach($model_detalle as $key => $value){
            $code .= '<td width="'.($tabs*20).'px" valign="middle" class="tab_basic inactive_tab" id="ptab'.$i.'" onclick="new Tabs.setActiveTab(this, '.$i.')">'.$value.'</td>';
            $i++;
        }
        $code .= '<td width="'.(140/$tabs).'px" class="null_tab"></td>';
        $code .= '</tr>';
        $code .= '</table>';
        $code .= '<br />';
        $code .= '</div>';
        $code .= Tag::form("$model/guardar", "id: form_$model");
        $i=0;
        foreach($model_maestro as $key => $value){
            $i++;
            $model = $key;
            $table = ucfirst($model);
            $table = new $table();
            $opciones = $table->opciones();
            $campos = $table->getAttributes();
            if($i>1){
                $code .= '<div id="tab'.$i.'" class="tab_content capture-content" style="display: none;">';
            }else{
                $code .= '<div id="tab'.$i.'" class="tab_content capture-content">';
            }
            $code .= '<table align="center">';
            foreach($campos as $mcampo){
                $attributes = $opciones[$mcampo];
                if(isset($attributes['notCapture']) && $attributes['notCapture']==true)continue;
                if(isset($attributes['notNew']) && $attributes['notNew']==true && $tipo_capture=='new')continue;
                if(isset($attributes['notEdit']) && $attributes['notEdit']==true && $tipo_capture=='edit')continue;
                if($tipo_capture=='edit' && isset($valores[$mcampo]))$attributes['value']=$valores[$mcampo];
                $code .= '<tr>';
                $attributes['null'] = (isset($attributes['null'])) ? $attributes['null'] : false;
                $null = "";
                if($attributes['null']==false)$null="*";
                $descrip = (isset($attributes['descripcion'])) ? ucfirst($attributes['descripcion']) : ucfirst($mcampo);
                $code .= "<td align='right'><label for='$mcampo'>$null".$descrip."</label> :</td>";
                $code .= "<td align='left'>".self::getInput($mcampo,$attributes,$model)."</td>";
                $code .="</tr>";
            }
            $code .= '</table>';
            $code .= '</div>';
        }
        foreach($model_detalle as $key => $value){
            $model = $key;
            $table = ucfirst($model);
            $table = new $table();
            $opciones = $table->opciones();
            $campos = $table->getAttributes();
            $campos_key = $table->getPrimaryKeyAttributes();
            $i++;
            $code .= '<div id="tab'.$i.'" class="tab_content" style="display: none;">';
            $code .= '<div>';
            $code .= '<table align="center">';
            $code .= '<tr>';
            $code .= '<td colspan="'.(count($campos)+1).'">';
            $code .= '<div id="edit_msg" style="display: none; cursor: pointer; background-color: #DDFFDD" onclick="cancel_'.$model.'();">Registro en Edici&oacute;n (Click aqui para Cancelar)</div>';
            $code .= '</td>';
            $code .= '</tr>';
            $code .= '<tr>';
            $cp = array();
            foreach($campos as $mcampo){
                if(in_array($mcampo,$campos_notcap))continue;
                $attributes = $opciones[$mcampo];
                if(isset($attributes['notCapture']) && $attributes['notCapture']==true)continue;
                $attributes['null'] = (isset($attributes['null'])) ? $attributes['null'] : false;
                $attributes['onkeydown'] = "saveIntro_$model(event,this);";
                $code .= "<td align='center'>".self::getInput($mcampo,$attributes,$model)."</td>";
                $cp[]=$mcampo;
            }
            $campos_detalle = "'".join("_$model','",$cp)."_$model'";
            $code .= "<td align='center'>".Tag::image("Plus.png","title: Agregar Fila","onclick: save_$model();")."</td>";
            $code .="</tr>";
            $code .= '<tr>';
            foreach($campos as $mcampo){
                if(in_array($mcampo,$campos_notcap))continue;
                $attributes = $opciones[$mcampo];
                if(isset($attributes['notCapture']) && $attributes['notCapture']==true)continue;
                $attributes['null'] = (isset($attributes['null'])) ? $attributes['null'] : false;
                $descrip = (isset($attributes['descripcion'])) ? ucfirst($attributes['descripcion']) : ucfirst($mcampo);
                $null = "";
                if($attributes['null']==false)$null="*";
                $code .= "<td align='center'><label for='$mcampo'>$null".$descrip."</label></td>";
            }
            $code .="</tr>";
            $code .= '</table>';
            $code .= '</div>';
            $code .= '<div id="capt_det">';
            $code .= '<table align="center" class="capture-search-data">';
            $code .= '<thead align="center">';
            $code .= '<tr>';
            foreach($campos as $mcampo){
                if(in_array($mcampo,$campos_notcap))continue;
                $attributes = $opciones[$mcampo];
                $attributes['null'] = (isset($attributes['null'])) ? $attributes['null'] : false;
                $descrip = (isset($attributes['descripcion'])) ? ucfirst($attributes['descripcion']) : ucfirst($mcampo);
                $code .= "<th align='center'>".$descrip."</th>";
            }
            $code .= "<th>&nbsp;</th>";
            $code .= '</tr>';
            $style="block";
            if($table->count()>0)$style="none";
            $code .= '<tr id="empty_msg" style="display: '.$style.'">';
            $code .= '<td>';
            if($table->count()==0){
                $code .= 'No hay Tipo De Calificacion que mostrar';
            }else{
                $code .= '';
            }
            $code .= '</td>';
            $code .= '</tr>';
            $code .= '</thead>';
            $code .= '</tbody>';
            if($table->count()==0){
                $code .= '<tr ondblclick="edit_'.$model.'(this);"  title="DobleClick para Editar">';
                foreach($campos as $mcampo){
                    if(in_array($mcampo,$campos_notcap))continue;
                    $code .= "<td class='{$mcampo}_{$model}_'></td>";
                }
                $code .= '<td class="det_img" align="center">'.Tag::image("Minus.png","title: Borrar Fila","onclick: removeRow_$model(this.ancestors()[1])").'</td>';
                $code .= '</tr>';
            }else{
                $i = 0;
                foreach($table->find() as $mtable){
                    $code .= '<tr class="det_row'.($i%2+1).'" height="25px" ondblclick="edit_'.$model.'(this);"  title="DobleClick para Editar">';
                    foreach($campos as $mcampo){
                        if(in_array($mcampo,$campos_notcap))continue;
                        $code .= "<td class='{$mcampo}_{$model}_'>".Tag::hiddenField("{$mcampo}_{$model}[]","value: ".$mtable->readAttribute($mcampo))."{$mtable->readAttribute($mcampo)}</td>";
                    }
                    $code .= '<td class="det_img" align="center">'.Tag::image("Minus.png","title: Borrar Fila","onclick: removeRow_$model(this.ancestors()[1])").'</td>';
                    $code .= '</tr>';
                }
            }
            $code .= '</body>';
            $code .= '</table>';
            $code .= '</div>';
            $code .= '</div>';
            $code .= "<script>";
            $code .= $model.' = new Detail({';
                    $code .= 'view: "capt_det",';
                    $code .= "fields: [$campos_detalle],";
                    $code .= "classTr: ['det_row1','det_row2'],";
                    $code .= "empty: 'empty_msg'";
                    $code .= '});';
            $code .= "val_$model = new Validator();";
            foreach($campos as $mcampo){
                if(in_array($mcampo,$campos_notcap))continue;
                $attributes = $opciones[$mcampo];
                if(isset($attributes['notCapture']) && $attributes['notCapture']==true)continue;
                $attributes['null'] = (isset($attributes['null'])) ? $attributes['null'] : false;
                $descrip = (isset($attributes['descripcion'])) ? ucfirst($attributes['descripcion']) : ucfirst($mcampo);
                $null = "true";
                if($attributes['null']==false)$null="false";
                $code .= "val_$model.addField('{$mcampo}_{$model}','text',null,{'alias': '$descrip','isNull': $null});";
                //$code .= "val_$model.addField('$mcampo','{$attributes['type']}',null,{'alias': '$descrip','isNull': $null});";
            }
            $code .= "</script>";
            $code .= '</div>';
        }
        $code .= '</div>';
        $code .= Tag::endForm();
        echo $code;
    }

    public function validate($model,$pk=false){
        if(is_array($model)){
            $campos = array_keys($model);
            $opciones = $model;
        }else{
            $table = ucfirst($model);
            $table = new $table();
            $opciones = $table->opciones();
            $campos = $table->getAttributes();
        }
        $code = "<script>";
        $code .= "fl_validator_$model = true;";
        $code .= "val_$model = new Validator();";
        $code .="pk_$model= [];";
        foreach($campos as $mcampo){
            $attributes = $opciones[$mcampo];
            if(isset($attributes['notCapture']) && $attributes['notCapture']==true)continue;
            $attributes['null'] = (isset($attributes['null'])) ? $attributes['null'] : false;
            $descrip = (isset($attributes['descripcion'])) ? ucfirst($attributes['descripcion']) : ucfirst($mcampo);
            $null = "true";
            if($attributes['null']==false)$null="false";
            $code .="if(val_$model.fieldExists('{$mcampo}_$model')==false){";
            $code .= "val_$model.addField('{$mcampo}_$model','text',null,{'alias': '$descrip','isNull': $null});";
            $code .="}";
        }
        if($pk==true){
            $campos_key = $table->getPrimaryKeyAttributes();
            $i=0;
            foreach($campos_key as $key){
                $attributes = $opciones[$key];
                if(isset($attributes['notCapture']) && $attributes['notCapture']==true)continue;
                $code .="pk_$model"."[$i]='{$key}_{$model}';";
                $code .="$('{$key}_$model').observe('blur',function(){valide_pk('$model');});";
                $i++;
            }
        }
        $code .= "</script>";
        echo $code;
    }



}
