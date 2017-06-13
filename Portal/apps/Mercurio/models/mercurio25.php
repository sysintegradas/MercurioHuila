<?php

class Mercurio25 extends ActiveRecord {

	/**
	 * @var string
	 */
	protected $controlador;

	/**
	 * @var string
	 */
	protected $accion;

	/**
	 * @var string
	 */
	protected $detalle;

	/**
	 * @var Decimal
	 */
	protected $valor;


	/**
	 * Metodo para establecer el valor del campo controlador
	 * @param string $controlador
	 */
	public function setControlador($controlador){
		$this->controlador = $controlador;
	}

	/**
	 * Metodo para establecer el valor del campo accion
	 * @param string $accion
	 */
	public function setAccion($accion){
		$this->accion = $accion;
	}

	/**
	 * Metodo para establecer el valor del campo detalle
	 * @param string $detalle
	 */
	public function setDetalle($detalle){
		$this->detalle = $detalle;
	}

	/**
	 * Metodo para establecer el valor del campo valor
	 * @param Decimal $valor
	 */
	public function setValor($valor){
		$this->valor = $valor;
	}


	/**
	 * Devuelve el valor del campo controlador
	 * @return string
	 */
	public function getControlador(){
		return $this->controlador;
	}

	/**
	 * Devuelve el valor del campo accion
	 * @return string
	 */
	public function getAccion(){
		return $this->accion;
	}

	/**
	 * Devuelve el valor del campo detalle
	 * @return string
	 */
	public function getDetalle(){
		return $this->detalle;
	}

	/**
	 * Devuelve el valor del campo valor
	 * @return Decimal
	 */
	public function getValor(){
		if($this->valor){
			return new Decimal($this->valor);
		} else {
			return null;
		}
	}

}

