<?php

class Gener07 extends ActiveRecord {

	/**
	 * @var string
	 */
	protected $coddep;

	/**
	 * @var string
	 */
	protected $detdep;


	/**
	 * Metodo para establecer el valor del campo coddep
	 * @param string $coddep
	 */
	public function setCoddep($coddep){
		$this->coddep = $coddep;
	}

	/**
	 * Metodo para establecer el valor del campo detdep
	 * @param string $detdep
	 */
	public function setDetdep($detdep){
		$this->detdep = $detdep;
	}


	/**
	 * Devuelve el valor del campo coddep
	 * @return string
	 */
	public function getCoddep(){
		return $this->coddep;
	}

	/**
	 * Devuelve el valor del campo detdep
	 * @return string
	 */
	public function getDetdep(){
		return $this->detdep;
	}

}

