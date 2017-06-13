<?php

class Mercurio48 extends ActiveRecord {

	/**
	 * @var string
	 */
	protected $codare;

	/**
	 * @var string
	 */
	protected $codope;

	/**
	 * @var string
	 */
	protected $tipfun;

	/**
	 * @var integer
	 */
	protected $orden;


	/**
	 * Metodo para establecer el valor del campo codare
	 * @param string $codare
	 */
	public function setCodare($codare){
		$this->codare = $codare;
	}

	/**
	 * Metodo para establecer el valor del campo codope
	 * @param string $codope
	 */
	public function setCodope($codope){
		$this->codope = $codope;
	}

	/**
	 * Metodo para establecer el valor del campo tipfun
	 * @param string $tipfun
	 */
	public function setTipfun($tipfun){
		$this->tipfun = $tipfun;
	}

	/**
	 * Metodo para establecer el valor del campo orden
	 * @param integer $orden
	 */
	public function setOrden($orden){
		$this->orden = $orden;
	}


	/**
	 * Devuelve el valor del campo codare
	 * @return string
	 */
	public function getCodare(){
		return $this->codare;
	}

	/**
	 * Devuelve el valor del campo codope
	 * @return string
	 */
	public function getCodope(){
		return $this->codope;
	}

	/**
	 * Devuelve el valor del campo tipfun
	 * @return string
	 */
	public function getTipfun(){
		return $this->tipfun;
	}

	/**
	 * Devuelve el valor del campo orden
	 * @return integer
	 */
	public function getOrden(){
		return $this->orden;
	}

}

