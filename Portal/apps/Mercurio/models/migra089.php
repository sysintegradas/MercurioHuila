<?php

class Migra089 extends ActiveRecord {

	/**
	 * @var string
	 */
	protected $coddep;

	/**
	 * @var string
	 */
	protected $codciu;

	/**
	 * @var string
	 */
	protected $codzon;

	/**
	 * @var string
	 */
	protected $detdep;

	/**
	 * @var string
	 */
	protected $detciu;

	/**
	 * @var string
	 */
	protected $detzon;

	/**
	 * @var string
	 */
	protected $pais;


	/**
	 * Metodo para establecer el valor del campo coddep
	 * @param string $coddep
	 */
	public function setCoddep($coddep){
		$this->coddep = $coddep;
	}

	/**
	 * Metodo para establecer el valor del campo codciu
	 * @param string $codciu
	 */
	public function setCodciu($codciu){
		$this->codciu = $codciu;
	}

	/**
	 * Metodo para establecer el valor del campo codzon
	 * @param string $codzon
	 */
	public function setCodzon($codzon){
		$this->codzon = $codzon;
	}

	/**
	 * Metodo para establecer el valor del campo detdep
	 * @param string $detdep
	 */
	public function setDetdep($detdep){
		$this->detdep = $detdep;
	}

	/**
	 * Metodo para establecer el valor del campo detciu
	 * @param string $detciu
	 */
	public function setDetciu($detciu){
		$this->detciu = $detciu;
	}

	/**
	 * Metodo para establecer el valor del campo detzon
	 * @param string $detzon
	 */
	public function setDetzon($detzon){
		$this->detzon = $detzon;
	}

	/**
	 * Metodo para establecer el valor del campo pais
	 * @param string $pais
	 */
	public function setPais($pais){
		$this->pais = $pais;
	}


	/**
	 * Devuelve el valor del campo coddep
	 * @return string
	 */
	public function getCoddep(){
		return $this->coddep;
	}

	/**
	 * Devuelve el valor del campo codciu
	 * @return string
	 */
	public function getCodciu(){
		return $this->codciu;
	}

	/**
	 * Devuelve el valor del campo codzon
	 * @return string
	 */
	public function getCodzon(){
		return $this->codzon;
	}

	/**
	 * Devuelve el valor del campo detdep
	 * @return string
	 */
	public function getDetdep(){
		return $this->detdep;
	}

	/**
	 * Devuelve el valor del campo detciu
	 * @return string
	 */
	public function getDetciu(){
		return $this->detciu;
	}

	/**
	 * Devuelve el valor del campo detzon
	 * @return string
	 */
	public function getDetzon(){
		return $this->detzon;
	}

	/**
	 * Devuelve el valor del campo pais
	 * @return string
	 */
	public function getPais(){
		return $this->pais;
	}

}

