<?php

class Mercurio08 extends ActiveRecord {

	/**
	 * @var string
	 */
	protected $codare;

	/**
	 * @var string
	 */
	protected $detalle;

	/**
	 * Metodo para establecer el valor del campo codare
	 * @param string $codare
	 */
	public function setCodare($codare){
		$this->codare = $codare;
	}

	/**
	 * Metodo para establecer el valor del campo detalle
	 * @param string $detalle
	 */
	public function setDetalle($detalle){
		$this->detalle = $detalle;
	}


	/**
	 * Devuelve el valor del campo codare
	 * @return string
	 */
	public function getCodare(){
		return $this->codare;
	}

	/**
	 * Devuelve el valor del campo detalle
	 * @return string
	 */
	public function getDetalle(){
		return $this->detalle;
	}

}

