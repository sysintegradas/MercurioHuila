<?php

class Mercurio13 extends ActiveRecord {

	/**
	 * @var string
	 */
	protected $codcaj;

	/**
	 * @var string
	 */
	protected $codare;

	/**
	 * @var string
	 */
	protected $codope;

	/**
	 * @var integer
	 */
	protected $coddoc;

	/**
	 * @var string
	 */
	protected $archivo;

	/**
	 * @var string
	 */
	protected $obliga;


	/**
	 * Metodo para establecer el valor del campo codcaj
	 * @param string $codcaj
	 */
	public function setCodcaj($codcaj){
		$this->codcaj = $codcaj;
	}

	/**
	 * Metodo para establecer el valor del campo codare
	 * @param string $codare
	 */
	public function setCodare($codare){
		$this->codare = $codare;
	}

	/**
	 * Metodo para establecer el valor del campo codope
	 * @param string $codope
	 */
	public function setCodope($codope){
		$this->codope = $codope;
	}

	/**
	 * Metodo para establecer el valor del campo coddoc
	 * @param integer $coddoc
	 */
	public function setCoddoc($coddoc){
		$this->coddoc = $coddoc;
	}

	/**
	 * Metodo para establecer el valor del campo archivo
	 * @param string $archivo
	 */
	public function setArchivo($archivo){
		$this->archivo = $archivo;
	}

	/**
	 * Metodo para establecer el valor del campo obliga
	 * @param string $obliga
	 */
	public function setObliga($obliga){
		$this->obliga = $obliga;
	}


	/**
	 * Devuelve el valor del campo codcaj
	 * @return string
	 */
	public function getCodcaj(){
		return $this->codcaj;
	}

	/**
	 * Devuelve el valor del campo codare
	 * @return string
	 */
	public function getCodare(){
		return $this->codare;
	}

	/**
	 * Devuelve el valor del campo codope
	 * @return string
	 */
	public function getCodope(){
		return $this->codope;
	}

	/**
	 * Devuelve el valor del campo coddoc
	 * @return integer
	 */
	public function getCoddoc(){
		return $this->coddoc;
	}

	/**
	 * Devuelve el valor del campo archivo
	 * @return string
	 */
	public function getArchivo(){
		return $this->archivo;
	}

	/**
	 * Devuelve el valor del campo obliga
	 * @return string
	 */
	public function getObliga(){
		return $this->obliga;
	}

	/**
	 * Validaciones y reglas de negocio
	 */
	protected function validation(){		
		$this->validate("InclusionIn", array(
			"field" => "obliga",
			"domain" => array('S', 'N'),
			"required" => true
		));
		if($this->validationHasFailed()==true){
			return false;
		}
	}

}

