<?php

class Mercurio27 extends ActiveRecord {

	/**
	 * @var integer
	 */
	protected $nivel;

	/**
	 * @var string
	 */
	protected $detalle;

	/**
	 * @var string
	 */
	protected $tipo;

	/**
	 * @var integer
	 */
	protected $numero;

	/**
	 * @var integer
	 */
	protected $valor;


	/**
	 * Metodo para establecer el valor del campo nivel
	 * @param integer $nivel
	 */
	public function setNivel($nivel){
		$this->nivel = $nivel;
	}

	/**
	 * Metodo para establecer el valor del campo detalle
	 * @param string $detalle
	 */
	public function setDetalle($detalle){
		$this->detalle = $detalle;
	}

	/**
	 * Metodo para establecer el valor del campo tipo
	 * @param string $tipo
	 */
	public function setTipo($tipo){
		$this->tipo = $tipo;
	}

	/**
	 * Metodo para establecer el valor del campo numero
	 * @param integer $numero
	 */
	public function setNumero($numero){
		$this->numero = $numero;
	}

	/**
	 * Metodo para establecer el valor del campo valor
	 * @param integer $valor
	 */
	public function setValor($valor){
		$this->valor = $valor;
	}


	/**
	 * Devuelve el valor del campo nivel
	 * @return integer
	 */
	public function getNivel(){
		return $this->nivel;
	}

	/**
	 * Devuelve el valor del campo detalle
	 * @return string
	 */
	public function getDetalle(){
		return $this->detalle;
	}

	/**
	 * Devuelve el valor del campo tipo
	 * @return string
	 */
	public function getTipo(){
		return $this->tipo;
	}

	/**
	 * Devuelve el valor del campo numero
	 * @return integer
	 */
	public function getNumero(){
		return $this->numero;
	}

	/**
	 * Devuelve el valor del campo valor
	 * @return integer
	 */
	public function getValor(){
		return $this->valor;
	}

	/**
	 * Validaciones y reglas de negocio
	 */
	protected function validation(){		
		$this->validate("InclusionIn", array(
			"field" => "tipo",
			"domain" => array('1', '2'),
			"required" => true
		));
		if($this->validationHasFailed()==true){
			return false;
		}
	}

}

