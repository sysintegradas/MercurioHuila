<?php

class Mercurio03 extends ActiveRecord {

	/**
	 * @var string
	 */
	protected $codfir;

	/**
	 * @var string
	 */
	protected $detalle;


	/**
	 * Metodo para establecer el valor del campo codfir
	 * @param string $codfir
	 */
	public function setCodfir($codfir){
		$this->codfir = $codfir;
	}

	/**
	 * Metodo para establecer el valor del campo detalle
	 * @param string $detalle
	 */
	public function setDetalle($detalle){
		$this->detalle = $detalle;
	}


	/**
	 * Devuelve el valor del campo codfir
	 * @return string
	 */
	public function getCodfir(){
		return $this->codfir;
	}

	/**
	 * Devuelve el valor del campo detalle
	 * @return string
	 */
	public function getDetalle(){
		return $this->detalle;
	}

}

