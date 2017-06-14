<?php

class Migra090 extends ActiveRecord {

	/**
	 * @var integer
	 */
	protected $iddefinicion;

	/**
	 * @var string
	 */
	protected $definicion;

	/**
	 * @var string
	 */
	protected $concepto;

	/**
	 * @var Date
	 */
	protected $fechacreacion;

	/**
	 * @var string
	 */
	protected $usuario;


	/**
	 * Metodo para establecer el valor del campo iddefinicion
	 * @param integer $iddefinicion
	 */
	public function setIddefinicion($iddefinicion){
		$this->iddefinicion = $iddefinicion;
	}

	/**
	 * Metodo para establecer el valor del campo definicion
	 * @param string $definicion
	 */
	public function setDefinicion($definicion){
		$this->definicion = $definicion;
	}

	/**
	 * Metodo para establecer el valor del campo concepto
	 * @param string $concepto
	 */
	public function setConcepto($concepto){
		$this->concepto = $concepto;
	}

	/**
	 * Metodo para establecer el valor del campo fechacreacion
	 * @param Date $fechacreacion
	 */
	public function setFechacreacion($fechacreacion){
		$this->fechacreacion = $fechacreacion;
	}

	/**
	 * Metodo para establecer el valor del campo usuario
	 * @param string $usuario
	 */
	public function setUsuario($usuario){
		$this->usuario = $usuario;
	}


	/**
	 * Devuelve el valor del campo iddefinicion
	 * @return integer
	 */
	public function getIddefinicion(){
		return $this->iddefinicion;
	}

	/**
	 * Devuelve el valor del campo definicion
	 * @return string
	 */
	public function getDefinicion(){
		return $this->definicion;
	}

	/**
	 * Devuelve el valor del campo concepto
	 * @return string
	 */
	public function getConcepto(){
		return $this->concepto;
	}

	/**
	 * Devuelve el valor del campo fechacreacion
	 * @return Date
	 */
	public function getFechacreacion(){
		if($this->fechacreacion){
			return new Date($this->fechacreacion);
		} else {
			return null;
		}
	}

	/**
	 * Devuelve el valor del campo usuario
	 * @return string
	 */
	public function getUsuario(){
		return $this->usuario;
	}

}

