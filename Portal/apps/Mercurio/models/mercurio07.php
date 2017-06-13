<?php

class Mercurio07 extends ActiveRecord {

	/**
	 * @var string
	 */
	protected $codcaj;

	/**
	 * @var string
	 */
	protected $tipo;

	/**
	 * @var string
	 */
	protected $coddoc;

	/**
	 * @var string
	 */
	protected $documento;

	/**
	 * @var string
	 */
	protected $nombre;

	/**
	 * @var string
	 */
	protected $email;

	/**
	 * @var string
	 */
	protected $clave;

	/**
	 * @var Date
	 */
	protected $feccla;

	/**
	 * @var string
	 */
	protected $autoriza;

	/**
	 * @var string
	 */
	protected $agencia;

	/**
	 * @var Date
	 */
	protected $fecreg;


	/**
	 * Metodo para establecer el valor del campo codcaj
	 * @param string $codcaj
	 */
	public function setCodcaj($codcaj){
		$this->codcaj = $codcaj;
	}

	/**
	 * Metodo para establecer el valor del campo tipo
	 * @param string $tipo
	 */
	public function setTipo($tipo){
		$this->tipo = $tipo;
	}

	/**
	 * Metodo para establecer el valor del campo coddoc
	 * @param string $coddoc
	 */
	public function setCoddoc($coddoc){
		$this->coddoc = $coddoc;
	}

	/**
	 * Metodo para establecer el valor del campo documento
	 * @param string $documento
	 */
	public function setDocumento($documento){
		$this->documento = $documento;
	}

	/**
	 * Metodo para establecer el valor del campo nombre
	 * @param string $nombre
	 */
	public function setNombre($nombre){
		$this->nombre = $nombre;
	}

	/**
	 * Metodo para establecer el valor del campo email
	 * @param string $email
	 */
	public function setEmail($email){
		$this->email = $email;
	}

	/**
	 * Metodo para establecer el valor del campo clave
	 * @param string $clave
	 */
	public function setClave($clave){
		$this->clave = $clave;
	}

	/**
	 * Metodo para establecer el valor del campo feccla
	 * @param Date $feccla
	 */
	public function setFeccla($feccla){
		$this->feccla = $feccla;
	}

	/**
	 * Metodo para establecer el valor del campo autoriza
	 * @param string $autoriza
	 */
	public function setAutoriza($autoriza){
		$this->autoriza = $autoriza;
	}

	/**
	 * Metodo para establecer el valor del campo agencia
	 * @param string $agencia
	 */
	public function setAgencia($agencia){
		$this->agencia = $agencia;
	}

	/**
	 * Metodo para establecer el valor del campo fecreg
	 * @param Date $fecreg
	 */
	public function setFecreg($fecreg){
		$this->fecreg = $fecreg;
	}


	/**
	 * Devuelve el valor del campo codcaj
	 * @return string
	 */
	public function getCodcaj(){
		return $this->codcaj;
	}

	/**
	 * Devuelve el valor del campo tipo
	 * @return string
	 */
	public function getTipo(){
		return $this->tipo;
	}

	/**
	 * Devuelve el valor del campo coddoc
	 * @return string
	 */
	public function getCoddoc(){
		return $this->coddoc;
	}

	/**
	 * Devuelve el valor del campo documento
	 * @return string
	 */
	public function getDocumento(){
		return $this->documento;
	}

	/**
	 * Devuelve el valor del campo nombre
	 * @return string
	 */
	public function getNombre(){
		return $this->nombre;
	}

	/**
	 * Devuelve el valor del campo email
	 * @return string
	 */
	public function getEmail(){
		return $this->email;
	}

	/**
	 * Devuelve el valor del campo clave
	 * @return string
	 */
	public function getClave(){
		return $this->clave;
	}

	/**
	 * Devuelve el valor del campo feccla
	 * @return Date
	 */
	public function getFeccla(){
		if($this->feccla){
			return new Date($this->feccla);
		} else {
			return null;
		}
	}

	/**
	 * Devuelve el valor del campo autoriza
	 * @return string
	 */
	public function getAutoriza(){
		return $this->autoriza;
	}

	/**
	 * Devuelve el valor del campo agencia
	 * @return string
	 */
	public function getAgencia(){
		return $this->agencia;
	}

	/**
	 * Devuelve el valor del campo fecreg
	 * @return Date
	 */
	public function getFecreg(){
		if($this->fecreg){
			return new Date($this->fecreg);
		} else {
			return null;
		}
	}

    public function getTipoArray(){
        return array("E"=>"EMPRESA","T"=>"TRABAJADOR","P"=>"PARTICULAR");
    }

    public function getTipoDetalle(){
        $retorno="";
        switch($this->tipo){
            case 'E': $retorno='EMPRESA'; break;
            case 'T': $retorno='TRABAJADOR'; break;
            case 'P': $retorno='PARTICULAR'; break;
        }
        return $retorno;
    }

	/**
	 * Validaciones y reglas de negocio
	 */
	protected function validation(){		
		$this->validate("Email", array(
			"field" => "email",
			"required" => true
		));
		$this->validate("InclusionIn", array(
			"field" => "autoriza",
			"domain" => array('S', 'N'),
			"required" => true
		));
		if($this->validationHasFailed()==true){
			return false;
		}
	}

}

