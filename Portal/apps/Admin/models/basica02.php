<?php

class Basica02 extends ActiveRecord {

	/**
	 * @var integer
	 */
	protected $usuario;

	/**
	 * @var string
	 */
	protected $codare;

	/**
	 * @var string
	 */
	protected $email;

	/**
	 * @var string
	 */
	protected $celular;

	/**
	 * @var string
	 */
	protected $telefono;

	/**
	 * @var string
	 */
	protected $ext;

	/**
	 * @var string
	 */
	protected $foto;


	/**
	 * Metodo para establecer el valor del campo usuario
	 * @param integer $usuario
	 */
	public function setUsuario($usuario){
		$this->usuario = $usuario;
	}

	/**
	 * Metodo para establecer el valor del campo codare
	 * @param string $codare
	 */
	public function setCodare($codare){
		$this->codare = $codare;
	}

	/**
	 * Metodo para establecer el valor del campo email
	 * @param string $email
	 */
	public function setEmail($email){
		$this->email = $email;
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
	 * Metodo para establecer el valor del campo ext
	 * @param string $ext
	 */
	public function setExt($ext){
		$this->ext = $ext;
	}

	/**
	 * Metodo para establecer el valor del campo foto
	 * @param string $foto
	 */
	public function setFoto($foto){
		$this->foto = $foto;
	}


	/**
	 * Devuelve el valor del campo usuario
	 * @return integer
	 */
	public function getUsuario(){
		return $this->usuario;
	}

	/**
	 * Devuelve el valor del campo codare
	 * @return string
	 */
	public function getCodare(){
		return $this->codare;
	}

	/**
	 * Devuelve el valor del campo email
	 * @return string
	 */
	public function getEmail(){
		return $this->email;
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

	/**
	 * Devuelve el valor del campo ext
	 * @return string
	 */
	public function getExt(){
		return $this->ext;
	}

	/**
	 * Devuelve el valor del campo foto
	 * @return string
	 */
	public function getFoto(){
		return $this->foto;
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

    /**
	 * Metodo inicializador de la Entidad
	 */
	protected function initialize(){		
		$this->belongsTo("usuario","gener02","usuario");
	}

}

