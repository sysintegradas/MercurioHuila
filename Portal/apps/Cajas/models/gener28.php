<?php

class Gener28 extends ActiveRecord {

	/**
	 * @var integer
	 */
	protected $id;

	/**
	 * @var string
	 */
	protected $codapl;

	/**
	 * @var string
	 */
	protected $role;

	/**
	 * @var string
	 */
	protected $resource;

	/**
	 * @var string
	 */
	protected $action;

	/**
	 * @var string
	 */
	protected $allow;


	/**
	 * Metodo para establecer el valor del campo id
	 * @param integer $id
	 */
	public function setId($id){
		$this->id = $id;
	}

	/**
	 * Metodo para establecer el valor del campo codapl
	 * @param string $codapl
	 */
	public function setCodapl($codapl){
		$this->codapl = $codapl;
	}

	/**
	 * Metodo para establecer el valor del campo role
	 * @param string $role
	 */
	public function setRole($role){
		$this->role = $role;
	}

	/**
	 * Metodo para establecer el valor del campo resource
	 * @param string $resource
	 */
	public function setResource($resource){
		$this->resource = $resource;
	}

	/**
	 * Metodo para establecer el valor del campo action
	 * @param string $action
	 */
	public function setAction($action){
		$this->action = $action;
	}

	/**
	 * Metodo para establecer el valor del campo allow
	 * @param string $allow
	 */
	public function setAllow($allow){
		$this->allow = $allow;
	}


	/**
	 * Devuelve el valor del campo id
	 * @return integer
	 */
	public function getId(){
		return $this->id;
	}

	/**
	 * Devuelve el valor del campo codapl
	 * @return string
	 */
	public function getCodapl(){
		return $this->codapl;
	}

	/**
	 * Devuelve el valor del campo role
	 * @return string
	 */
	public function getRole(){
		return $this->role;
	}

	/**
	 * Devuelve el valor del campo resource
	 * @return string
	 */
	public function getResource(){
		return $this->resource;
	}

	/**
	 * Devuelve el valor del campo action
	 * @return string
	 */
	public function getAction(){
		return $this->action;
	}

	/**
	 * Devuelve el valor del campo allow
	 * @return string
	 */
	public function getAllow(){
		return $this->allow;
	}

}

