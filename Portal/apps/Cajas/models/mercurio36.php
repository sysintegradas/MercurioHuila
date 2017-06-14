<?php

class Mercurio36 extends ActiveRecord {

  	/**
	 * @var int
	 */
	protected $id;
	/**
	 * @var string
	 */
	protected $codest;

	/**
	 * @var string
	 */
	protected $cedtra;
    /**
	 * @var string
	 */
	protected $fecret;

	/**
	 * @var string
	 */
	protected $nota;
    /**
	 * @var string
	 */
	protected $estado;

	/**
	 * @var string
	 */
	protected $fecest;
    /**
	 * @var string
	 */
	protected $motivo;

  	/**
	 * Metodo para establecer el valor del campo id 
	 * @param string $id
	 */
	public function setId($id){
		$this->id = $id;
	}

	/**
	 * Metodo para establecer el valor del campo codigo estado
	 * @param string $codest
	 */
	public function setCodest($codest){
		$this->codest = $codest;
	}
	/**
	 * Metodo para establecer el valor del campo cedula trabajador 
	 * @param string $cedtra
	 */
	public function setCedtra($cedtra){
		$this->cedtra = $cedtra;
	}

	/**
	 * Metodo para establecer el valor del campo fecha de  retiro
	 * @param string $fecret
	 */
	public function setFecret($fecret){
		$this->fecret = $fecret;
	}
    
	/**
	 * Metodo para establecer el valor del campo nota 
	 * @param string $nota
	 */
	public function setNota($nota){
		$this->nota = $nota;
	}

	/**
	 * Metodo para establecer el valor del campo estado
	 * @param string $estado
	 */
	public function setEstado($estado){
		$this->estado = $estado;
	}
	/**
	 * Metodo para establecer el valor del campo fecha de estado
	 * @param string $fecest
	 */
	public function setFecest($fecest){
		$this->fecest = $fecest;
	}

	/**
	 * Metodo para establecer el valor del campo motivo
	 * @param string $motivo
	 */
	public function setMotivo($motivo){
		$this->motivo = $motivo;
	}




	/**
	 * Devuelve el valor del campo id
	 * @return string
	 */
	public function getId(){
		return $this->id;
	}

	/**
	 * Devuelve el valor del campo cedtra
	 * @return string
	 */
	public function getCedtra(){
		return $this->cedtra;
	}
	/**
	 * Devuelve el valor del campo codest
	 * @return string
	 */
	public function getCodest(){
		return $this->codest;
	}

	/**
	 * Devuelve el valor del campo fecret
	 * @return string
	 */
	public function getFecret(){
		return $this->fecret;
	}
    /**
	 * Devuelve el valor del campo nota
	 * @return string
	 */
	public function getNota(){
		return $this->nota;
	}

	/**
	 * Devuelve el valor del campo estado
	 * @return string
	 */
	public function getEstado(){
		return $this->estado;
	}
	/**
	 * Devuelve el valor del campo fecha de  estado
	 * @return string
	 */
	public function getFecest(){
		return $this->estado;
	}

	/**
	 * Devuelve el valor del campo motivo
	 * @return string
	 */
	public function getMotivo(){
		return $this->motivo;
	}


}
