<?php

class Mercurio09 extends ActiveRecord {

	/**
	 * @var integer
	 */
	protected $codpub;

	/**
	 * @var integer
	 */
	protected $numero;

	/**
	 * @var string
	 */
	protected $codcaj;


	/**
	 * Metodo para establecer el valor del campo codpub
	 * @param integer $codpub
	 */
	public function setCodpub($codpub){
		$this->codpub = $codpub;
	}

	/**
	 * Metodo para establecer el valor del campo numero
	 * @param integer $numero
	 */
	public function setNumero($numero){
		$this->numero = $numero;
	}

	/**
	 * Metodo para establecer el valor del campo codcaj
	 * @param string $codcaj
	 */
	public function setCodcaj($codcaj){
		$this->codcaj = $codcaj;
	}


	/**
	 * Devuelve el valor del campo codpub
	 * @return integer
	 */
	public function getCodpub(){
		return $this->codpub;
	}

	/**
	 * Devuelve el valor del campo numero
	 * @return integer
	 */
	public function getNumero(){
		return $this->numero;
	}

	/**
	 * Devuelve el valor del campo codcaj
	 * @return string
	 */
	public function getCodcaj(){
		return $this->codcaj;
	}

}

