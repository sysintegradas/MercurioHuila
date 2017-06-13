<?php

class Mercurio46 extends ActiveRecord {

	/**
	 * @var integer
	 */
	protected $log;

	/**
	 * @var string
	 */
	protected $coddoc;

	/**
	 * @var string
	 */
	protected $documento;

	/**
	 * @var string
	 */
	protected $priape;

	/**
	 * @var string
	 */
	protected $segape;

	/**
	 * @var string
	 */
	protected $prinom;

	/**
	 * @var string
	 */
	protected $segnom;

	/**
	 * @var string
	 */
	protected $sexo;

	/**
	 * @var Date
	 */
	protected $fecnac;

	/**
	 * @var string
	 */
	protected $tipper;

	/**
	 * @var string
	 */
	protected $celular;

	/**
	 * @var string
	 */
	protected $telefono;


	/**
	 * Metodo para establecer el valor del campo log
	 * @param integer $log
	 */
	public function setLog($log){
		$this->log = $log;
	}

	/**
	 * Metodo para establecer el valor del campo coddoc
	 * @param string $coddoc
	 */
	public function setCoddoc($coddoc){
		$this->coddoc = $coddoc;
	}

	/**
	 * Metodo para establecer el valor del campo documento
	 * @param string $documento
	 */
	public function setDocumento($documento){
		$this->documento = $documento;
	}

	/**
	 * Metodo para establecer el valor del campo priape
	 * @param string $priape
	 */
	public function setPriape($priape){
		$this->priape = $priape;
	}

	/**
	 * Metodo para establecer el valor del campo segape
	 * @param string $segape
	 */
	public function setSegape($segape){
		$this->segape = $segape;
	}

	/**
	 * Metodo para establecer el valor del campo prinom
	 * @param string $prinom
	 */
	public function setPrinom($prinom){
		$this->prinom = $prinom;
	}

	/**
	 * Metodo para establecer el valor del campo segnom
	 * @param string $segnom
	 */
	public function setSegnom($segnom){
		$this->segnom = $segnom;
	}

	/**
	 * Metodo para establecer el valor del campo sexo
	 * @param string $sexo
	 */
	public function setSexo($sexo){
		$this->sexo = $sexo;
	}

	/**
	 * Metodo para establecer el valor del campo fecnac
	 * @param Date $fecnac
	 */
	public function setFecnac($fecnac){
		$this->fecnac = $fecnac;
	}

	/**
	 * Metodo para establecer el valor del campo tipper
	 * @param string $tipper
	 */
	public function setTipper($tipper){
		$this->tipper = $tipper;
	}

	/**
	 * Metodo para establecer el valor del campo celular
	 * @param string $celular
	 */
	public function setCelular($celular){
		$this->celular = $celular;
	}

	/**
	 * Metodo para establecer el valor del campo telefono
	 * @param string $telefono
	 */
	public function setTelefono($telefono){
		$this->telefono = $telefono;
	}


	/**
	 * Devuelve el valor del campo log
	 * @return integer
	 */
	public function getLog(){
		return $this->log;
	}

	/**
	 * Devuelve el valor del campo coddoc
	 * @return string
	 */
	public function getCoddoc(){
		return $this->coddoc;
	}

	/**
	 * Devuelve el valor del campo documento
	 * @return string
	 */
	public function getDocumento(){
		return $this->documento;
	}

	/**
	 * Devuelve el valor del campo priape
	 * @return string
	 */
	public function getPriape(){
		return $this->priape;
	}

	/**
	 * Devuelve el valor del campo segape
	 * @return string
	 */
	public function getSegape(){
		return $this->segape;
	}

	/**
	 * Devuelve el valor del campo prinom
	 * @return string
	 */
	public function getPrinom(){
		return $this->prinom;
	}

	/**
	 * Devuelve el valor del campo segnom
	 * @return string
	 */
	public function getSegnom(){
		return $this->segnom;
	}

	/**
	 * Devuelve el valor del campo sexo
	 * @return string
	 */
	public function getSexo(){
		return $this->sexo;
	}

	/**
	 * Devuelve el valor del campo fecnac
	 * @return Date
	 */
	public function getFecnac(){
		if($this->fecnac){
			return new Date($this->fecnac);
		} else {
			return null;
		}
	}

	/**
	 * Devuelve el valor del campo tipper
	 * @return string
	 */
	public function getTipper(){
		return $this->tipper;
	}

	/**
	 * Devuelve el valor del campo celular
	 * @return string
	 */
	public function getCelular(){
		return $this->celular;
	}

	/**
	 * Devuelve el valor del campo telefono
	 * @return string
	 */
	public function getTelefono(){
		return $this->telefono;
	}

}

