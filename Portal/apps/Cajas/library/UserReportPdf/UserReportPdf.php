<?php

/**
 * Clase para la generacion de reportes
 */
class UserReportPdf extends FPDF {

  /**
   * Almacena la cabecera de las paginas
   */
  public $_titulo = "";

  /**
   * Almacena las opciones de los campos
   */
  private $_fields = "";

  /**
   * Tamaño de la table
   */
  public $_tWidth = 0;

  /**
   * Intercala el color de las celdas
   */
  private $_fill = true;

  /**
   * Fila actual del reporte
   */
  private $_row = array();

  /**
   * Suma Campos del reporte
   */
  private $_sum = array();

  /**
   * Almacena los diferentes agrupamientos
   */
  private $_groups = array();

  /**
   * Numero de la pagina anterior
   */
  private $_changePage = false;


  /**
   * Constructor para los reportes
   */
  public function __construct($titulo, $fields=array(), $orientation="", $format="A4"){
    $this->_titulo = $titulo;
    if(is_array($titulo)){
      $this->SetTitle($titulo[0]);
    }else{
      $this->SetTitle($titulo);
    }
    $this->_fields = $fields;
    foreach($fields as $field)
      $this->_tWidth += $field["size"];
    if($this->_tWidth>190){
      if($orientation=='')
        $orientation = "L";
      if($this->_tWidth>277){
        $format = "Legal";
        if($this->_tWidth>336){
          $format = array(216,$this->_tWidth + 20);
        }
      }
    }
    if(empty($orientation)) $orientation = "P";
    parent::__construct($orientation, "mm", $format);
  }

  /**
   * Inicializa el reporte, previamente configurado
   */
  public function StartReport(){
    $this->AliasNbPages();
    $this->AddPage();
    $this->SetAutoPageBreak(true, 11);
  }

  /**
   * Permite guardar campos de un registro, para luego ser mostrados
   */
  public function Put($field,$value,$align=''){
    if($align!=''){
      $this->_fields[$field]['align'] = $align;
    }
    while(($this->GetStringWidth($value)-$this->_fields[$field]["size"])>0)
      $value = substr($value,0,strlen($value)-4)."...";
    $this->_row[$field] = $value;
    if(isset($this->_fields[$field]['sum']) && $this->_fields[$field]['sum']=='true'){
      if(!isset($this->_sum[$field])){
        $this->_sum[$field] = 0;
      }
      $this->_sum[$field] +=$value;
    }
  }

  /**
   * Envia una fila al reporte
   */
  public function OutputToReport($line=1,$size=5.5){
    //$this->w = 225;
    $this->SetX(($this->w - $this->_tWidth)/2);
    $this->_fill = !$this->_fill;
    $this->SetDrawColor(160,160,160);
    $this->SetTextColor(100);
    $this->SetFillColor(245);
    $this->SetFont('arial','',$size);
    foreach(array_keys($this->_fields) as $field){
      $align = $this->_fields[$field]["align"];
      $fill = $this->_fill;
      if(count($this->_row) > 0){
        if(isset($this->_fields[$field]["numberFormat"]) && $this->_row[$field] != ''){
          $this->Cell($this->_fields[$field]["size"],5,number_format($this->_row[$field],2,',','.'),$line,0,$align,$fill);
        }else{
          $this->Cell($this->_fields[$field]["size"],5,$this->_row[$field],$line,0,$align,$fill);
        }
      }
    }
    $this->Ln();
  }

  /**
   * Cabecera de todas las paginas
   */
  public function Header(){
    if(count($this->_groups)){
      $this->SetFont('Arial','',8);
      $this->SetDrawColor(160,160,160);
      $this->SetTextColor(160,160,160);
      $grp = array();
      foreach(array_keys($this->_groups) as $field){
        if(empty($this->_groups[$field]["currentMsg"])||!$this->_changePage){
          $grp[] = "{grp_$field}";
        }else{
          $grp[] = $this->_groups[$field]["currentMsg"];
        }
      }
      $grp = join($grp," / ");
      $this->Cell(0,4,$grp,0,1,'L');
      $this->Ln(4);
    }
    $date = new Date();
    $this->SetTextColor(100);
    $this->SetFillColor(245);
    $this->SetY(4);
    $this->SetFont('Arial','',8);
    $str = "Elaborado el ".$date->getDay()." de ".$date->getMonthName()." del ".$date->getYear();
    $x = $this->GetStringWidth($str);
    $this->Cell($x+10,5,$str,0,0,'L');
    $x = $this->GetStringWidth(Session::get('nomcol'));
    $this->Cell(0,5,Session::get('nomcol'),0,"R",'R');
    $this->ln();
    $this->ln();
    $this->SetTextColor(0);
    $this->SetFont('Arial','B',12);
    if(is_array($this->_titulo)){
      foreach($this->_titulo as $titulo){
        $w=$this->GetStringWidth($titulo);
        $this->SetX(($this->w - $w) / 2);
        $this->Cell($w,5,$titulo,0,1,'C');
      }
    }else{
      $w=$this->GetStringWidth($this->_titulo);
      $this->SetX(($this->w - $w) / 2);
      $this->Cell($w,5,$this->_titulo,0,1,'C');
    }
    $this->Ln();
    $this->Ln();
    $this->SetTextColor(100);
    $txt = "";
    $w=$this->GetStringWidth($txt);
    $this->SetFont('Arial','',6);
    $this->multiCell(190,5,$txt,0,'J',0);
    $this->SetFont('Arial','',9);
    $this->Cell(0,5,Session::getDATA('nomsed').' '.Session::getDATA('ano'),0,1,'R');
    $this->Cell(0,2,'','T',1,'C');
   // $this->Image("public/img/LogoEmpresa.jpg",14,11,30,15);
    if(!count($this->_groups)||$this->_changePage){
        $this->__Header();
        $this->_changePage = false;
    }else{
        $this->__Header();
    }
  }

  /**
   * Cabecera de las tablas
   */
  public function __Header(){
    $this->Ln(5);
    $this->SetDrawColor(160,160,160);
    $this->SetFillColor(230,230,230);
    $this->SetTextColor(100);
    $this->SetFont('Arial','B',7);
    $this->SetX(($this->w - $this->_tWidth)/2);
    foreach($this->_fields as $field)
      $this->Cell($field["size"],5,$field["header"],0,0,"C",true);
    $this->Ln();
  }

  public function __Cell($w,$h,$txt,$border,$ln,$aling,$fill,$link){
      $txt = html_entity_decode(utf8_decode($txt));
      //$this->SetX(($this->w - $this->_tWidth)/2);
      $this->SetDrawColor(160,160,160);
      $this->SetTextColor(100);
      $this->SetFillColor(245);
      $this->SetFont('arial','',9);
      parent::Cell($w, $h, $txt, $border, $ln, $aling, $fill, $link);
  }

  /**
   * Pie de todas las paginas
   */
  public function Footer(){
    $this->SetFont('Arial','',8);
    $date = new Date();
    $this->SetTextColor(100);
    $this->SetFillColor(245);
    $this->SetY(-10);
    $str = "Elaborado Por ".Session::getDATA("nombre");
    $x = $this->GetStringWidth($str);
    $this->Cell($x+10,5,$str,'T',0,'L');
    $this->Cell(0,5,'Pagina '.$this->PageNo().' de {nb}','T',1,'R');
  }

  /**
   * Sobreescribe el metodo Cell, para que el texto sea siempre decodificado de utf_8
   */
  public function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link=''){
    $txt = html_entity_decode(utf8_decode($txt));
    parent::Cell($w, $h, $txt, $border, $ln, $align, $fill, $link);
  }

  /**
   * Selecciona un campo, para agrupar, y asi dividir el reporte por resultados
   */
  public function Group($field,$group){
    $this->_groups[$field] = array_merge($group,array("currentGroup" => "?", "currentMsg" => ""));
  }

  /**
   * Indica el grupo actual, sobre el cual se trabaja
   */
  public function SetGroup($group,$value){
    if($this->_groups[$group]["currentGroup"]!=$value){
      if($this->_groups[$group]["currentGroup"]!="?"){
        if(isset($this->_groups[$group]["afterGroup"])){
          $this->Ln(4);
          $function = array($this->_groups[$group]["afterGroup"]["class"],$this->_groups[$group]["afterGroup"]["callback"]);
          $params = array($this,$value);
          call_user_func_array($function,$params);
        }
        $this->Sum();
      }
      if(isset($this->_groups[$group]["beforeGroup"])){
        if(($this->GetY() + 22) > ($this->h - 10)){   //22 Altura de una cabecera de grupo
          $this->_changePage = false;
          $this->AddPage();
        }
        $this->Ln(6);
        $this->SetTextColor(100);
        $this->SetFont('Arial','B',11);
        $function = array($this->_groups[$group]["beforeGroup"]["class"],$this->_groups[$group]["beforeGroup"]["callback"]);
        $params = array($this,$value);
        $this->_groups[$group]["currentMsg"] = call_user_func_array($function,$params);
        $this->Ln(4);
      }
      $this->_groups[$group]["currentGroup"] = $value;
      $this->pages[$this->PageNo()] = str_replace("{grp_$group}",$this->_groups[$group]["currentMsg"],$this->pages[$this->PageNo()]);
      $this->__Header();
      $this->_changePage = false;
      $this->SumRe();
    }else{
      $this->_changePage = true;
    }
  }


  /**
   funcion para reiniciar los totales al final del reporte
   * */
  public function SumRe(){
    if(isset($this->_sum) && count($this->_sum)>0){
      foreach(array_keys($this->_fields) as $field){
        if(array_key_exists($field,$this->_sum)){
          $this->_sum[$field]=0;
        }
      }
    }
  }

  /**
   funcion para colocar los totales al final del reporte
   * */
  public function Sum(){
    if(isset($this->_sum) && count($this->_sum)>0){
      $this->SetX(($this->w - $this->_tWidth)/2);
      $this->_fill = !$this->_fill;
      $this->SetDrawColor(160,160,160);
      $this->SetTextColor(100);
      $this->SetFillColor(245);
      $this->SetFont('arial','',9);
      $fill = $this->_fill;
      foreach(array_keys($this->_fields) as $field){
        if(array_key_exists($field,$this->_sum)){
          $align = $this->_fields[$field]["align"];
          $format = false;
          if(isset($this->_fields[$field]["numberFormat"])){
            $format = $this->_fields[$field]["numberFormat"];
          }
          if($format){
            $this->Cell($this->_fields[$field]["size"],5,number_format($this->_sum[$field],2,',','.'),1,0,$align,$fill);
          }else{
            $this->Cell($this->_fields[$field]["size"],5,$this->_sum[$field],1,0,$align,$fill);
          }
        }else{
          if(isset($total)){
            $this->Cell($this->_fields[$field]["size"],5,"","BT",0,"C",$fill);
          }else{
            if(count($this->_sum)>1){
              $total="Totales: ";
            } else{
              $total="Total: ";
            }
            $format = '';
            if(isset($this->_fields[$field]["numberFormat"])){
              $format = $this->_fields[$field]["numberFormat"];
            }
            if($format == 'true'){
              $this->SetFont('arial','B',7);
              $this->Cell($this->_fields[$field]["size"],5,number_format($total,2,',','.'),"LBT",0,"L",$fill);
              $this->SetFont('arial','',7);
            }
            else{
              $this->SetFont('arial','B',7);
              $this->Cell($this->_fields[$field]["size"],5,$total,"LBT",0,"L",$fill);
              $this->SetFont('arial','',7);
            }
          }
        }
      }
      $this->Ln();
    }
  }

  /**
   * Termina el reporte
   */
  public function FinishReport($file,$option="D"){
    $this->Sum();
if($option!="F")
    ob_end_clean();
    $this->Output($file.".pdf",$option);
    //$this->Header("location: ".Core::getInstancePath()."/{$file}.pdf");
  }

  public function afterColumn($fields,$width = ""){
    $this->_fill = !$this->_fill;
    $this->SetDrawColor(160,160,160);
    $this->SetTextColor(100);
    $this->SetFillColor(245);
    foreach($fields as $field) {
      $fill = $this->_fill;
      if($width == "" ){
        $this->setX(($this->_tWidth)/2);
      }
      $this->Cell($field['size'],5,$field['value'],0,1,$field['align'],$fill);  
    }
    if(count($fields) > 0 ) $this->ln();
  }

}


