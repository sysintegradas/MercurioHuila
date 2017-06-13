<?php

class Mercurio02 extends ActiveRecord {

	/**
	 * @var string
	 */
	protected $codcaj;

	/**
	 * @var string
	 */
	protected $nit;

	/**
	 * @var string
	 */
	protected $razsoc;

	/**
	 * @var string
	 */
	protected $email;

	/**
	 * @var string
	 */
	protected $direccion;

	/**
	 * @var string
	 */
	protected $telefono;

	/**
	 * @var string
	 */
	protected $codciu;


	/**
	 * Metodo para establecer el valor del campo codcaj
	 * @param string $codcaj
	 */
	public function setCodcaj($codcaj){
		$this->codcaj = $codcaj;
	}

	/**
	 * Metodo para establecer el valor del campo nit
	 * @param string $nit
	 */
	public function setNit($nit){
		$this->nit = $nit;
	}

	/**
	 * Metodo para establecer el valor del campo razsoc
	 * @param string $razsoc
	 */
	public function setRazsoc($razsoc){
		$this->razsoc = $razsoc;
	}

	/**
	 * Metodo para establecer el valor del campo email
	 * @param string $email
	 */
	public function setEmail($email){
		$this->email = $email;
	}

	/**
	 * Metodo para establecer el valor del campo direccion
	 * @param string $direccion
	 */
	public function setDireccion($direccion){
		$this->direccion = $direccion;
	}

	/**
	 * Metodo para establecer el valor del campo telefono
	 * @param string $telefono
	 */
	public function setTelefono($telefono){
		$this->telefono = $telefono;
	}

	/**
	 * Metodo para establecer el valor del campo codciu
	 * @param string $codciu
	 */
	public function setCodciu($codciu){
		$this->codciu = $codciu;
	}


	/**
	 * Devuelve el valor del campo codcaj
	 * @return string
	 */
	public function getCodcaj(){
		return $this->codcaj;
	}

	/**
	 * Devuelve el valor del campo nit
	 * @return string
	 */
	public function getNit(){
		return $this->nit;
	}

	/**
	 * Devuelve el valor del campo razsoc
	 * @return string
	 */
	public function getRazsoc(){
		return $this->razsoc;
	}

	/**
	 * Devuelve el valor del campo email
	 * @return string
	 */
	public function getEmail(){
		return $this->email;
	}

	/**
	 * Devuelve el valor del campo direccion
	 * @return string
	 */
	public function getDireccion(){
		return $this->direccion;
	}

	/**
	 * Devuelve el valor del campo telefono
	 * @return string
	 */
	public function getTelefono(){
		return $this->telefono;
	}

	/**
	 * Devuelve el valor del campo codciu
	 * @return string
	 */
	public function getCodciu(){
		return $this->codciu;
	}

	/**
	 * Validaciones y reglas de negocio
	 */
	protected function validation(){		
		$this->validate("Email", array(
			"field" => "email",
			"required" => true
		));
		if($this->validationHasFailed()==true){
			return false;
		}
	}

}

