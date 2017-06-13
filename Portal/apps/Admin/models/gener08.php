<?php

class Gener08 extends ActiveRecord {

	/**
	 * @var string
	 */
	protected $codciu;

	/**
	 * @var string
	 */
	protected $detciu;

	/**
	 * @var integer
	 */
	protected $numpob;


	/**
	 * Metodo para establecer el valor del campo codciu
	 * @param string $codciu
	 */
	public function setCodciu($codciu){
		$this->codciu = $codciu;
	}

	/**
	 * Metodo para establecer el valor del campo detciu
	 * @param string $detciu
	 */
	public function setDetciu($detciu){
		$this->detciu = $detciu;
	}

	/**
	 * Metodo para establecer el valor del campo numpob
	 * @param integer $numpob
	 */
	public function setNumpob($numpob){
		$this->numpob = $numpob;
	}


	/**
	 * Devuelve el valor del campo codciu
	 * @return string
	 */
	public function getCodciu(){
		return $this->codciu;
	}

	/**
	 * Devuelve el valor del campo detciu
	 * @return string
	 */
	public function getDetciu(){
		return $this->detciu;
	}

	/**
	 * Devuelve el valor del campo numpob
	 * @return integer
	 */
	public function getNumpob(){
		return $this->numpob;
	}

}

