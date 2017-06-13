<?php

class Mercurio23 extends ActiveRecord {

	/**
	 * @var string
	 */
	protected $codcat;

	/**
	 * @var string
	 */
	protected $detalle;


	/**
	 * Metodo para establecer el valor del campo codcat
	 * @param string $codcat
	 */
	public function setCodcat($codcat){
		$this->codcat = $codcat;
	}

	/**
	 * Metodo para establecer el valor del campo detalle
	 * @param string $detalle
	 */
	public function setDetalle($detalle){
		$this->detalle = $detalle;
	}


	/**
	 * Devuelve el valor del campo codcat
	 * @return string
	 */
	public function getCodcat(){
		return $this->codcat;
	}

	/**
	 * Devuelve el valor del campo detalle
	 * @return string
	 */
	public function getDetalle(){
		return $this->detalle;
	}

}

