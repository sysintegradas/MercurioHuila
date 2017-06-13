<?php

class Mercurio28 extends ActiveRecord {

	/**
	 * @var string
	 */
	protected $tipo;

	/**
	 * @var string
	 */
	protected $campo;

	/**
	 * @var string
	 */
	protected $detalle;


	/**
	 * Metodo para establecer el valor del campo tipo
	 * @param string $tipo
	 */
	public function setTipo($tipo){
		$this->tipo = $tipo;
	}

	/**
	 * Metodo para establecer el valor del campo campo
	 * @param string $campo
	 */
	public function setCampo($campo){
		$this->campo = $campo;
	}

	/**
	 * Metodo para establecer el valor del campo detalle
	 * @param string $detalle
	 */
	public function setDetalle($detalle){
		$this->detalle = $detalle;
	}


	/**
	 * Devuelve el valor del campo tipo
	 * @return string
	 */
	public function getTipo(){
		return $this->tipo;
	}

	/**
	 * Devuelve el valor del campo campo
	 * @return string
	 */
	public function getCampo(){
		return $this->campo;
	}

	/**
	 * Devuelve el valor del campo detalle
	 * @return string
	 */
	public function getDetalle(){
		return $this->detalle;
	}

	/**
	 * Validaciones y reglas de negocio
	 */
	protected function validation(){		
		$this->validate("InclusionIn", array(
			"field" => "tipo",
			"domain" => array('E', 'T', 'C'),
			"required" => true
		));
		if($this->validationHasFailed()==true){
			return false;
		}
	}

}

