<?php

class Gener18 extends ActiveRecord {

	/**
	 * @var string
	 */
	protected $coddoc;

	/**
	 * @var string
	 */
	protected $detdoc;


	/**
	 * Metodo para establecer el valor del campo coddoc
	 * @param string $coddoc
	 */
	public function setCoddoc($coddoc){
		$this->coddoc = $coddoc;
	}

	/**
	 * Metodo para establecer el valor del campo detdoc
	 * @param string $detdoc
	 */
	public function setDetdoc($detdoc){
		$this->detdoc = $detdoc;
	}


	/**
	 * Devuelve el valor del campo coddoc
	 * @return string
	 */
	public function getCoddoc(){
		return $this->coddoc;
	}

	/**
	 * Devuelve el valor del campo detdoc
	 * @return string
	 */
	public function getDetdoc(){
		return $this->detdoc;
	}

}

