<?php

class Mercurio42 extends ActiveRecord {

	/**
	 * @var integer
	 */
	protected $codban;

	/**
	 * @var integer
	 */
	protected $codcat;

	/**
	 * @var string
	 */
	protected $codcaj;

	/**
	 * @var string
	 */
	protected $nomarc;

	/**
	 * @var string
	 */
	protected $estado;


	/**
	 * Metodo para establecer el valor del campo codban
	 * @param integer $codban
	 */
	public function setCodban($codban){
		$this->codban = $codban;
	}

	/**
	 * Metodo para establecer el valor del campo codcat
	 * @param integer $codcat
	 */
	public function setCodcat($codcat){
		$this->codcat = $codcat;
	}

	/**
	 * Metodo para establecer el valor del campo codcaj
	 * @param string $codcaj
	 */
	public function setCodcaj($codcaj){
		$this->codcaj = $codcaj;
	}

	/**
	 * Metodo para establecer el valor del campo nomarc
	 * @param string $nomarc
	 */
	public function setNomarc($nomarc){
		$this->nomarc = $nomarc;
	}

	/**
	 * Metodo para establecer el valor del campo estado
	 * @param string $estado
	 */
	public function setEstado($estado){
		$this->estado = $estado;
	}


	/**
	 * Devuelve el valor del campo codban
	 * @return integer
	 */
	public function getCodban(){
		return $this->codban;
	}

	/**
	 * Devuelve el valor del campo codcat
	 * @return integer
	 */
	public function getCodcat(){
		return $this->codcat;
	}

	/**
	 * Devuelve el valor del campo codcaj
	 * @return string
	 */
	public function getCodcaj(){
		return $this->codcaj;
	}

	/**
	 * Devuelve el valor del campo nomarc
	 * @return string
	 */
	public function getNomarc(){
		return $this->nomarc;
	}

	/**
	 * Devuelve el valor del campo estado
	 * @return string
	 */
	public function getEstado(){
		return $this->estado;
	}

	/**
	 * Validaciones y reglas de negocio
	 */
	protected function validation(){		
		$this->validate("InclusionIn", array(
			"field" => "estado",
			"domain" => array('A', 'I'),
			"required" => true
		));
		if($this->validationHasFailed()==true){
			return false;
		}
	}

}

