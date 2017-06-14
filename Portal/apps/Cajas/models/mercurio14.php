<?php

class Mercurio14 extends ActiveRecord {

	/**
	 * @var integer
	 */
	protected $numero;

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
	 * @var string
	 */
	protected $tipo;

	/**
	 * @var string
	 */
	protected $documento;

	/**
	 * @var string
	 */
	protected $nota;

	/**
	 * @var string
	 */
	protected $estado;


	/**
	 * Metodo para establecer el valor del campo numero
	 * @param integer $numero
	 */
	public function setNumero($numero){
		$this->numero = $numero;
	}

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
	 * Metodo para establecer el valor del campo tipo
	 * @param string $tipo
	 */
	public function setTipo($tipo){
		$this->tipo = $tipo;
	}

	/**
	 * Metodo para establecer el valor del campo documento
	 * @param string $documento
	 */
	public function setDocumento($documento){
		$this->documento = $documento;
	}

	/**
	 * Metodo para establecer el valor del campo nota
	 * @param string $nota
	 */
	public function setNota($nota){
		$this->nota = $nota;
	}

	/**
	 * Metodo para establecer el valor del campo estado
	 * @param string $estado
	 */
	public function setEstado($estado){
		$this->estado = $estado;
	}


	/**
	 * Devuelve el valor del campo numero
	 * @return integer
	 */
	public function getNumero(){
		return $this->numero;
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
	 * Devuelve el valor del campo tipo
	 * @return string
	 */
	public function getTipo(){
		return $this->tipo;
	}

	/**
	 * Devuelve el valor del campo documento
	 * @return string
	 */
	public function getDocumento(){
		return $this->documento;
	}

	/**
	 * Devuelve el valor del campo nota
	 * @return string
	 */
	public function getNota(){
		return $this->nota;
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
			"domain" => array('A', 'V', 'R'),
			"required" => true
		));
		if($this->validationHasFailed()==true){
			return false;
		}
	}

}

