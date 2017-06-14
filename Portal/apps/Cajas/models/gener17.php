<?php

class Gener17 extends ActiveRecord {

	/**
	 * @var string
	 */
	protected $codsex;

	/**
	 * @var string
	 */
	protected $detsex;


	/**
	 * Metodo para establecer el valor del campo codsex
	 * @param string $codsex
	 */
	public function setCodsex($codsex){
		$this->codsex = $codsex;
	}

	/**
	 * Metodo para establecer el valor del campo detsex
	 * @param string $detsex
	 */
	public function setDetsex($detsex){
		$this->detsex = $detsex;
	}


	/**
	 * Devuelve el valor del campo codsex
	 * @return string
	 */
	public function getCodsex(){
		return $this->codsex;
	}

	/**
	 * Devuelve el valor del campo detsex
	 * @return string
	 */
	public function getDetsex(){
		return $this->detsex;
	}

}

