<?php

class Mercurio01 extends ActiveRecord {

	/**
	 * @var integer
	 */
	protected $codapl;

	/**
	 * @var string
	 */
	protected $control;

	/**
	 * @var string
	 */
	protected $audtra;

	/**
	 * @var string
	 */
	protected $email;

	/**
	 * @var string
	 */
	protected $clave;

	/**
	 * @var string
	 */
	protected $desema;


	/**
	 * Metodo para establecer el valor del campo codapl
	 * @param integer $codapl
	 */
	public function setCodapl($codapl){
		$this->codapl = $codapl;
	}

	/**
	 * Metodo para establecer el valor del campo control
	 * @param string $control
	 */
	public function setControl($control){
		$this->control = $control;
	}

	/**
	 * Metodo para establecer el valor del campo audtra
	 * @param string $audtra
	 */
	public function setAudtra($audtra){
		$this->audtra = $audtra;
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
	 * Metodo para establecer el valor del campo desema
	 * @param string $desema
	 */
	public function setDesema($desema){
		$this->desema = $desema;
	}


	/**
	 * Devuelve el valor del campo codapl
	 * @return integer
	 */
	public function getCodapl(){
		return $this->codapl;
	}

	/**
	 * Devuelve el valor del campo control
	 * @return string
	 */
	public function getControl(){
		return $this->control;
	}

	/**
	 * Devuelve el valor del campo audtra
	 * @return string
	 */
	public function getAudtra(){
		return $this->audtra;
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
	 * Devuelve el valor del campo desema
	 * @return string
	 */
	public function getDesema(){
		return $this->desema;
	}

	/**
	 * Validaciones y reglas de negocio
	 */
	protected function validation(){		
		$this->validate("InclusionIn", array(
			"field" => "control",
			"domain" => array('S', 'N'),
			"required" => true
		));
		$this->validate("InclusionIn", array(
			"field" => "audtra",
			"domain" => array('S', 'N'),
			"required" => true
		));
		$this->validate("Email", array(
			"field" => "email",
			"required" => true
		));
		if($this->validationHasFailed()==true){
			return false;
		}
	}

}

