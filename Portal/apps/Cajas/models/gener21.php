<?php

class Gener21 extends ActiveRecord {

	/**
	 * @var string
	 */
	protected $tipfun;

	/**
	 * @var string
	 */
	protected $detalle;


	/**
	 * Metodo para establecer el valor del campo coddoc
	 * @param string $coddoc
	 */
	public function setTipfun($tipfun){
		$this->tipfun = $tipfun;
	}

	/**
	 * Metodo para establecer el valor del campo detdoc
	 * @param string $detdoc
	 */
	public function setDetalle($detalle){
		$this->detalle = $detalle;
	}


	/**
	 * Devuelve el valor del campo coddoc
	 * @return string
	 */
	public function getTipfun(){
		return $this->tipfun;
	}

	/**
	 * Devuelve el valor del campo detdoc
	 * @return string
	 */
	public function getDetalle(){
		return $this->detalle;
	}

}

