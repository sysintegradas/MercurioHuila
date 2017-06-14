<?php

class Mercurio41 extends ActiveRecord {

	/**
	 * @var integer
	 */
	protected $codcat;

	/**
	 * @var string
	 */
	protected $nombre;

	/**
	 * @var string
	 */
	protected $descripcion;


	/**
	 * Metodo para establecer el valor del campo codcat
	 * @param integer $codcat
	 */
	public function setCodcat($codcat){
		$this->codcat = $codcat;
	}

	/**
	 * Metodo para establecer el valor del campo nombre
	 * @param string $nombre
	 */
	public function setNombre($nombre){
		$this->nombre = $nombre;
	}

	/**
	 * Metodo para establecer el valor del campo descripcion
	 * @param string $descripcion
	 */
	public function setDescripcion($descripcion){
		$this->descripcion = $descripcion;
	}


	/**
	 * Devuelve el valor del campo codcat
	 * @return integer
	 */
	public function getCodcat(){
		return $this->codcat;
	}

	/**
	 * Devuelve el valor del campo nombre
	 * @return string
	 */
	public function getNombre(){
		return $this->nombre;
	}

	/**
	 * Devuelve el valor del campo descripcion
	 * @return string
	 */
	public function getDescripcion(){
		return $this->descripcion;
	}

}

