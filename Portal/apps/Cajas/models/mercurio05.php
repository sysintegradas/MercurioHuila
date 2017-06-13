<?php

class Mercurio05 extends ActiveRecord {

	/**
	 * @var string
	 */
	protected $codcaj;

	/**
	 * @var string
	 */
	protected $codfir;

	/**
	 * @var string
	 */
	protected $cedula;

	/**
	 * @var string
	 */
	protected $nombre;

	/**
	 * @var string
	 */
	protected $cargo;

	/**
	 * @var string
	 */
	protected $email;


	/**
	 * Metodo para establecer el valor del campo codcaj
	 * @param string $codcaj
	 */
	public function setCodcaj($codcaj){
		$this->codcaj = $codcaj;
	}

	/**
	 * Metodo para establecer el valor del campo codfir
	 * @param string $codfir
	 */
	public function setCodfir($codfir){
		$this->codfir = $codfir;
	}

	/**
	 * Metodo para establecer el valor del campo cedula
	 * @param string $cedula
	 */
	public function setCedula($cedula){
		$this->cedula = $cedula;
	}

	/**
	 * Metodo para establecer el valor del campo nombre
	 * @param string $nombre
	 */
	public function setNombre($nombre){
		$this->nombre = $nombre;
	}

	/**
	 * Metodo para establecer el valor del campo cargo
	 * @param string $cargo
	 */
	public function setCargo($cargo){
		$this->cargo = $cargo;
	}

	/**
	 * Metodo para establecer el valor del campo email
	 * @param string $email
	 */
	public function setEmail($email){
		$this->email = $email;
	}


	/**
	 * Devuelve el valor del campo codcaj
	 * @return string
	 */
	public function getCodcaj(){
		return $this->codcaj;
	}

	/**
	 * Devuelve el valor del campo codfir
	 * @return string
	 */
	public function getCodfir(){
		return $this->codfir;
	}

	/**
	 * Devuelve el valor del campo cedula
	 * @return string
	 */
	public function getCedula(){
		return $this->cedula;
	}

	/**
	 * Devuelve el valor del campo nombre
	 * @return string
	 */
	public function getNombre(){
		return $this->nombre;
	}

	/**
	 * Devuelve el valor del campo cargo
	 * @return string
	 */
	public function getCargo(){
		return $this->cargo;
	}

	/**
	 * Devuelve el valor del campo email
	 * @return string
	 */
	public function getEmail(){
		return $this->email;
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

