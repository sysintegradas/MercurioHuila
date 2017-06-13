<?php

class Mercurio35 extends ActiveRecord {

	/**
	 * @var integer
	 */
	protected $id;

	/**
	 * @var integer
	 */
	protected $log;

	/**
	 * @var string
	 */
	protected $nit;

	/**
	 * @var string
	 */
	protected $cedtra;

	/**
	 * @var string
	 */
	protected $nomtra;

	/**
	 * @var string
	 */
	protected $codest;

	/**
	 * @var Date
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
	 * @var Date
	 */
	protected $fecest;

	/**
	 * @var string
	 */
	protected $motivo;

	/**
	 * @var integer
	 */
	protected $usuario;

	/**
	 * @var string
	 */
	protected $nomarc;


	/**
	 * Metodo para establecer el valor del campo id
	 * @param integer $id
	 */
	public function setId($id){
		$this->id = $id;
	}

	/**
	 * Metodo para establecer el valor del campo log
	 * @param integer $log
	 */
	public function setLog($log){
		$this->log = $log;
	}

	/**
	 * Metodo para establecer el valor del campo nit
	 * @param string $nit
	 */
	public function setNit($nit){
		$this->nit = $nit;
	}

	/**
	 * Metodo para establecer el valor del campo cedtra
	 * @param string $cedtra
	 */
	public function setCedtra($cedtra){
		$this->cedtra = $cedtra;
	}

	/**
	 * Metodo para establecer el valor del campo nomtra
	 * @param string $nomtra
	 */
	public function setNomtra($nomtra){
		$this->nomtra = $nomtra;
	}

	/**
	 * Metodo para establecer el valor del campo codest
	 * @param string $codest
	 */
	public function setCodest($codest){
		$this->codest = $codest;
	}

	/**
	 * Metodo para establecer el valor del campo fecret
	 * @param Date $fecret
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
	 * Metodo para establecer el valor del campo fecest
	 * @param Date $fecest
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
	 * Metodo para establecer el valor del campo usuario
	 * @param integer $usuario
	 */
	public function setUsuario($usuario){
		$this->usuario = $usuario;
	}

	/**
	 * Metodo para establecer el valor del campo nomarc
	 * @param string $nomarc
	 */
	public function setNomarc($nomarc){
		$this->nomarc = $nomarc;
	}


	/**
	 * Devuelve el valor del campo id
	 * @return integer
	 */
	public function getId(){
		return $this->id;
	}

	/**
	 * Devuelve el valor del campo log
	 * @return integer
	 */
	public function getLog(){
		return $this->log;
	}

	/**
	 * Devuelve el valor del campo nit
	 * @return string
	 */
	public function getNit(){
		return $this->nit;
	}

	/**
	 * Devuelve el valor del campo cedtra
	 * @return string
	 */
	public function getCedtra(){
		return $this->cedtra;
	}

	/**
	 * Devuelve el valor del campo nomtra
	 * @return string
	 */
	public function getNomtra(){
		return $this->nomtra;
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
	 * @return Date
	 */
	public function getFecret(){
		if($this->fecret){
			return new Date($this->fecret);
		} else {
			return null;
		}
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
	 * Devuelve el valor del campo fecest
	 * @return Date
	 */
	public function getFecest(){
		if($this->fecest){
			return new Date($this->fecest);
		} else {
			return null;
		}
	}

	/**
	 * Devuelve el valor del campo motivo
	 * @return string
	 */
	public function getMotivo(){
		return $this->motivo;
	}

	/**
	 * Devuelve el valor del campo usuario
	 * @return integer
	 */
	public function getUsuario(){
		return $this->usuario;
	}

	/**
	 * Devuelve el valor del campo nomarc
	 * @return string
	 */
	public function getNomarc(){
		return $this->nomarc;
	}

}

