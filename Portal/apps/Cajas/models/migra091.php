<?php

class Migra091 extends ActiveRecord {

	/**
	 * @var integer
	 */
	protected $iddetalledef;

	/**
	 * @var integer
	 */
	protected $iddefinicion;

	/**
	 * @var string
	 */
	protected $codigo;

	/**
	 * @var string
	 */
	protected $detalledefinicion;

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
	 * Metodo para establecer el valor del campo iddetalledef
	 * @param integer $iddetalledef
	 */
	public function setIddetalledef($iddetalledef){
		$this->iddetalledef = $iddetalledef;
	}

	/**
	 * Metodo para establecer el valor del campo iddefinicion
	 * @param integer $iddefinicion
	 */
	public function setIddefinicion($iddefinicion){
		$this->iddefinicion = $iddefinicion;
	}

	/**
	 * Metodo para establecer el valor del campo codigo
	 * @param string $codigo
	 */
	public function setCodigo($codigo){
		$this->codigo = $codigo;
	}

	/**
	 * Metodo para establecer el valor del campo detalledefinicion
	 * @param string $detalledefinicion
	 */
	public function setDetalledefinicion($detalledefinicion){
		$this->detalledefinicion = $detalledefinicion;
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
	 * Devuelve el valor del campo iddetalledef
	 * @return integer
	 */
	public function getIddetalledef(){
		return $this->iddetalledef;
	}

	/**
	 * Devuelve el valor del campo iddefinicion
	 * @return integer
	 */
	public function getIddefinicion(){
		return $this->iddefinicion;
	}

	/**
	 * Devuelve el valor del campo codigo
	 * @return string
	 */
	public function getCodigo(){
		return $this->codigo;
	}

	/**
	 * Devuelve el valor del campo detalledefinicion
	 * @return string
	 */
	public function getDetalledefinicion(){
		return $this->detalledefinicion;
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

