<?php

class Reports extends FPDF {

    private $_fields="";
    private $_titulo="";

    public function __construct($titulo, $orientation="P", $format="Letter"){
        $this->_titulo = $titulo;
        parent::__construct($orientation, "mm", $format);
    }
    
    public function StartReport(){
        $this->AliasNbPages();
        $this->AddPage();
        $this->SetAutoPageBreak(true, 11);
    }

    public function Header(){
        $this->AliasNbPages();
        $this->SetAutoPageBreak(true, 11);
        $mc01 = new Mercurio01();
        $mercurio01 = $mc01->findFirst();
        $empresa = $mercurio01->getNomcol();
        $this->SetY(4);
        $this->SetFont('Arial','',8);
        $date = new Date();
        $str = "Elaborado el ".$date->getDay()." de ".$date->getMonthName()." del ".$date->getYear();
        $this->SetTextColor(100);
        $this->SetFillColor(245);
        $x = $this->GetStringWidth($str);
        $this->Cell($x+10,5,$str,'B',0,'L');
        $x = $this->GetStringWidth($empresa);
        $this->Cell(0,5,$empresa,"B","R",'R');
        $this->ln();
        $this->ln();
        $this->ln();
        $this->SetTextColor(0);
        $this->SetFont('Arial','B',12);
        $this->SetY(15);
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
        $this->Ln(4);
        $this->SetTextColor(100);
        $this->SetFont('Arial','',9);
        $this->Cell(0,5,Session::getDATA('nomsed').' '.Session::getDATA('ano'),'B',1,'R');
        $this->Cell(0,4,'','T',1,'C');
        $this->SetTextColor(0);
        $this->Image("public/img/LogoEmpresa.jpeg",14,11,30,15);
    }

    public function Footer(){
        $this->SetTextColor(100);
        $this->SetFillColor(245);
        $this->SetFont('Arial','',8);
        $this->SetY(-10);
        $str = "Elaborado Por ".Session::getDATA("nombre");
        $x = $this->GetStringWidth($str);
        $this->Cell($x+10,5,$str,'T',0,'L');
        $this->Cell(0,5,'Pagina '.$this->PageNo().' de {nb}','T',1,'R');
    }

    public function Titulos($fields){
        $this->Ln(5);
        $this->SetDrawColor(160,160,160);
        $this->SetFillColor(230,230,230);
        $this->SetTextColor(100);
        $this->SetFont('Arial','B',7);
        //$this->SetX(($this->w - $this->_tWidth)/2);
        foreach($fields as $field)
            $this->Cell($field["size"],5,$field["header"],1,0,"C",true);
        $this->Ln();
    }

    public function FinishReport($file,$option="D"){
        ob_end_clean();
        $this->Output($file.".pdf",$option);
    }

}
