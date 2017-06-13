<?php

/**
 * Clase para la generacion de reportes
 */
class UserReportExcel extends Spreadsheet_Excel_Writer {

  /**
   * Almacena la cabecera de las paginas
   */
  private $_rx_titulo = "";

  /**
   * Almacena las opciones de los campos
   */
  private $_rx_fields = "";

  /**
   * Contenido de la fila actual del reporte
   */
  private $_rx_row = array();

  /**
   * Almacena los diferentes agrupamientos
   */
  private $_rx_groups = array();

  /**
   * Numero de fila actual del reporte
   */
  private $_rx_num_row;

  /**
   * Hojas de trabajo
   */
  private $_rx_worksheets = array();

  /**
   * Actual hoja de trabajo
   */
  private $_rx_current_worksheet = 0;

  /**
   * Formato de diversas celdas
   */
  private $_rx_formats = array();

  /**
   * Ancho de las columnas
   */
  private $_rx_widthCol = array();

  /**
   * Constructor para los reportes
   */
  public function __construct($titulo, $fields){
    $this->_rx_titulo = $titulo;
    $this->_rx_fields = $fields;
    foreach($this->_rx_fields as $key => $value){
      $this->_rx_widthCol[$key] = strlen($value["header"])*1.50;
    }
    parent::__construct();
  }

  /**
   * Inicializa el reporte, previamente configurado
   */
  public function StartReport(){
    $this->_rx_worksheets[] =& $this->addWorksheet("Hoja 1");
    $this->_SetFormats();
    $this->_SetHeader();
  }

  /**
   * Metodo para escribir celdas
   */
  public function writeCell($row, $col, $text, $format = null, $type="S"){
    if($type=="S"){
      $this->_rx_worksheets[$this->_rx_current_worksheet]->writeString($row, $col, utf8_decode($text), $format);
    }else{
      $this->_rx_worksheets[$this->_rx_current_worksheet]->write($row, $col, utf8_decode($text), $format);
    }
  }

  /**
   * Cabecera del reporte
   */
  private function _SetHeader(){
    $row = 1;
    $ws = $this->_rx_current_worksheet;
    $this->writeCell($row, 0, "CAJA DE COMPENSACION FAMILIAR DE NARI?~QO", $this->_rx_formats["title"]);
    $row++;
    if(is_array($this->_rx_titulo)){
      foreach($this->_rx_titulo as $titulo){
        $this->writeCell($row++, 0, $titulo, $this->_rx_formats["title"]);
      }
    }else{
      $this->writeCell($row++, 0, $this->_rx_titulo, $this->_rx_formats["title"]);
    }
    $date = new Date();
    $str = "Elaborado el ".$date->getDay()." de ".$date->getMonthName()." del ".$date->getYear();
    $this->writeCell($row++, 0, $str, $this->_rx_formats["subtitle"]);
    $row++;
    if(!count($this->_rx_groups)){
      $col = 0;
      foreach($this->_rx_fields as $field){
        $this->writeCell($row, $col++, $field["header"], $this->_rx_formats["header"]);
      }
    }
    for($i = 1; $i < $row-1; $i++){
      $this->_rx_worksheets[$ws]->setMerge($i, 0, $i, count($this->_rx_fields) - 1);
    }
    if(!count($this->_rx_groups)) $row += 2;
    $this->_rx_num_row = $row - 1;
  }

  /**
   * Crea los diferentes formatos para diversos usos
   */
  private function _SetFormats(){
    $this->setCustomColor(10,29,97,19);
    $this->_rx_formats["title"] =& $this->addFormat(
      array("fontfamily" => "Arial", "size" => 15, "align" => "center", "bold" => 1, "color" => 10,
            "italic" => 1));

    $this->_rx_formats["subtitle"] =& $this->addFormat(
      array("fontfamily" => "Arial", "size" => 12, "align" => "center", "bold" => 1, "color" => 10,
            "italic" => 1));

    $this->_rx_formats["group"] =& $this->addFormat(
      array("fontfamily" => "Arial", "size" => 12, "align" => "left", "bold" => 1));

    $this->setCustomColor(11,230,230,230);
    $this->_rx_formats["header"] =& $this->addFormat(
      array("fontfamily" => "Arial", "size" => 12, "align" => "center", "bold" => 1, "border" => 1,
            "fgcolor" => 11, "bordercolor" => "black"));

    $this->_rx_formats["content"] =& $this->addFormat(
      array("fontfamily" => "Arial", "size" => 10, "border" => 1, "bordercolor" => "black"));

    $this->_rx_formats["currency"] =& $this->addFormat(
      array("fontfamily" => "Arial", "size" => 10, "border" => 1, "bordercolor" => "black",
            "numFormat" => "#,##0.00;[RED]($#,##0.00)"));
  }

  /**
   * Retorna el formato especificado
   */
  public function &getFormat($format){
    return $this->_rx_formats[$format];
  }

  /**
   * Permite guardar campos de un registro, para luego ser mostrados
   */
  public function Put($field,$value){
    $_w = strlen($value) * 1.25;
    if($_w>$this->_rx_widthCol[$field]){
      $this->_rx_widthCol[$field] = $_w;
    }
    $this->_rx_row[$field] = $value;
  }

  /**
   * Envia una fila al reporte
   */
  public function OutputToReport(){
    $col = 0;
    foreach(array_keys($this->_rx_fields) as $field){
      $align = $this->_rx_fields[$field]["align"];
      $format = $this->_rx_formats["content"];
      if($align=="R"){
        $type = "N";
        if(preg_match("/^\\$\d(\d|,\d)+(\.\d*)?$/",$this->_rx_row[$field])){
          $this->_rx_row[$field] = preg_replace("/(\\$|,)/","",$this->_rx_row[$field]);
          $format = $this->_rx_formats["currency"];
        }
      }else{
        $type = "S";
      }
      $this->writeCell($this->_rx_num_row, $col++, $this->_rx_row[$field], $format, $type);
    }
    $this->_rx_num_row++;
  }

  /**
   * Retorna el valor de la fila actual
   */
  public function getCurrentRow(){
    return $this->_rx_num_row;
  }

  /**
   * Selecciona un campo, para agrupar, y asi dividir el reporte por resultados
   */
  public function Group($field,$group){
    $this->_rx_groups[$field] = array_merge($group,array("currentGroup" => "?", "currentMsg" => ""));
  }

  /**
   * Indica el grupo actual, sobre el cual se trabaja
   */
  public function SetGroup($group,$value){
    if($this->_rx_groups[$group]["currentGroup"]!=$value){
      if($this->_rx_groups[$group]["currentGroup"]!="?"){
        if(isset($this->_rx_groups[$group]["afterGroup"])){
          $function = array($this->_rx_groups[$group]["afterGroup"]["class"],$this->_rx_groups[$group]["afterGroup"]["callback"]);
          $params = array($this,$value);
          call_user_func_array($function,$params);
        }
      }
      if(isset($this->_rx_groups[$group]["beforeGroup"])){
        $this->_rx_num_row += 1;
        $_w = $this->_rx_num_row;
        $function = array($this->_rx_groups[$group]["beforeGroup"]["class"],$this->_rx_groups[$group]["beforeGroup"]["callback"]);
        $params = array($this,$value);
        $this->_rx_groups[$group]["currentMsg"] = call_user_func_array($function,$params);
        $this->_rx_num_row++;
        for($i = $_w; $i < $this->_rx_num_row; $i++){
          $this->_rx_worksheets[$this->_rx_current_worksheet]->setMerge($i, 0, $i, count($this->_rx_fields) - 1);
        }
        $col = 0;
        foreach($this->_rx_fields as $field){
          $this->writeCell($this->_rx_num_row, $col++, $field["header"], $this->_rx_formats["header"]);
        }
        $this->_rx_num_row++;
      }
      $this->_rx_groups[$group]["currentGroup"] = $value;
    }
  }

  /**
   * Termina el reporte
   */
  public function FinishReport($file,$option="D"){
    $col = 0;
    foreach($this->_rx_widthCol as $width){
      $this->_rx_worksheets[$this->_rx_current_worksheet]->setColumn($col, $col++, $width);
    }
    if($option=="D"){
      $this->send($file.".xls");
    }else{
      $this->_filename = $file;
    }
    $this->close();
  }

}
