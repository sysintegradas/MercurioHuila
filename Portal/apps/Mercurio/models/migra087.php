<?php

class Migra087 extends ActiveRecord {

	/**
	 * @var string
	 */
	protected $codbar;

	/**
	 * @var string
	 */
	protected $codzon;

	/**
	 * @var string
	 */
	protected $detalle;


	/**
	 * Metodo para establecer el valor del campo codbar
	 * @param string $codbar
	 */
	public function setCodbar($codbar){
		$this->codbar = $codbar;
	}

	/**
	 * Metodo para establecer el valor del campo codzon
	 * @param string $codzon
	 */
	public function setCodzon($codzon){
		$this->codzon = $codzon;
	}

	/**
	 * Metodo para establecer el valor del campo detalle
	 * @param string $detalle
	 */
	public function setDetalle($detalle){
		$this->detalle = $detalle;
	}


	/**
	 * Devuelve el valor del campo codbar
	 * @return string
	 */
	public function getCodbar(){
		return $this->codbar;
	}

	/**
	 * Devuelve el valor del campo codzon
	 * @return string
	 */
	public function getCodzon(){
		return $this->codzon;
	}

	/**
	 * Devuelve el valor del campo detalle
	 * @return string
	 */
	public function getDetalle(){
		return $this->detalle;
	}

}

