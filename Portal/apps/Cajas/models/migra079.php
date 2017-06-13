<?php

class Migra079 extends ActiveRecord {

	/**
	 * @var string
	 */
	protected $idciiu;

	/**
	 * @var string
	 */
	protected $seccion;

	/**
	 * @var string
	 */
	protected $division;

	/**
	 * @var string
	 */
	protected $grupo;

	/**
	 * @var string
	 */
	protected $clase;

	/**
	 * @var string
	 */
	protected $descripcion;


	/**
	 * Metodo para establecer el valor del campo idciiu
	 * @param string $idciiu
	 */
	public function setIdciiu($idciiu){
		$this->idciiu = $idciiu;
	}

	/**
	 * Metodo para establecer el valor del campo seccion
	 * @param string $seccion
	 */
	public function setSeccion($seccion){
		$this->seccion = $seccion;
	}

	/**
	 * Metodo para establecer el valor del campo division
	 * @param string $division
	 */
	public function setDivision($division){
		$this->division = $division;
	}

	/**
	 * Metodo para establecer el valor del campo grupo
	 * @param string $grupo
	 */
	public function setGrupo($grupo){
		$this->grupo = $grupo;
	}

	/**
	 * Metodo para establecer el valor del campo clase
	 * @param string $clase
	 */
	public function setClase($clase){
		$this->clase = $clase;
	}

	/**
	 * Metodo para establecer el valor del campo descripcion
	 * @param string $descripcion
	 */
	public function setDescripcion($descripcion){
		$this->descripcion = $descripcion;
	}


	/**
	 * Devuelve el valor del campo idciiu
	 * @return string
	 */
	public function getIdciiu(){
		return $this->idciiu;
	}

	/**
	 * Devuelve el valor del campo seccion
	 * @return string
	 */
	public function getSeccion(){
		return $this->seccion;
	}

	/**
	 * Devuelve el valor del campo division
	 * @return string
	 */
	public function getDivision(){
		return $this->division;
	}

	/**
	 * Devuelve el valor del campo grupo
	 * @return string
	 */
	public function getGrupo(){
		return $this->grupo;
	}

	/**
	 * Devuelve el valor del campo clase
	 * @return string
	 */
	public function getClase(){
		return $this->clase;
	}

	/**
	 * Devuelve el valor del campo descripcion
	 * @return string
	 */
	public function getDescripcion(){
		return $this->descripcion;
	}

}

