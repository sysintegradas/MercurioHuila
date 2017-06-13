<?php

class Mercurio45 extends ActiveRecord {

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
	protected $codcaj;

	/**
	 * @var string
	 */
	protected $documento;

	/**
	 * @var string
	 */
	protected $nomben;

	/**
	 * @var string
	 */
	protected $codben;

	/**
	 * @var string
	 */
	protected $codcer;

	/**
	 * @var string
	 */
	protected $estado;

	/**
	 * @var integer
	 */
	protected $usuario;

	/**
	 * @var string
	 */
	protected $motivo;

	/**
	 * @var Date
	 */
	protected $fecest;

	/**
	 * @var string
	 */
	protected $nomarc;

	/**
	 * @var string
	 */
	protected $periodo;

	protected $perini;
	protected $perfin;
	protected $fecpre;


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
	 * Metodo para establecer el valor del campo codcaj
	 * @param string $codcaj
	 */
	public function setCodcaj($codcaj){
		$this->codcaj = $codcaj;
	}

	/**
	 * Metodo para establecer el valor del campo documento
	 * @param string $documento
	 */
	public function setDocumento($documento){
		$this->documento = $documento;
	}

	/**
	 * Metodo para establecer el valor del campo nomben
	 * @param string $nomben
	 */
	public function setNomben($nomben){
		$this->nomben = $nomben;
	}

	/**
	 * Metodo para establecer el valor del campo codben
	 * @param string $codben
	 */
	public function setCodben($codben){
		$this->codben = $codben;
	}

	/**
	 * Metodo para establecer el valor del campo codcer
	 * @param string $codcer
	 */
	public function setCodcer($codcer){
		$this->codcer = $codcer;
	}

	/**
	 * Metodo para establecer el valor del campo estado
	 * @param string $estado
	 */
	public function setEstado($estado){
		$this->estado = $estado;
	}

	/**
	 * Metodo para establecer el valor del campo usuario
	 * @param integer $usuario
	 */
	public function setUsuario($usuario){
		$this->usuario = $usuario;
	}

	/**
	 * Metodo para establecer el valor del campo motivo
	 * @param string $motivo
	 */
	public function setMotivo($motivo){
		$this->motivo = $motivo;
	}

	/**
	 * Metodo para establecer el valor del campo fecest
	 * @param Date $fecest
	 */
	public function setFecest($fecest){
		$this->fecest = $fecest;
	}

	/**
	 * Metodo para establecer el valor del campo nomarc
	 * @param string $nomarc
	 */
	public function setNomarc($nomarc){
		$this->nomarc = $nomarc;
	}

	/**
	 * Metodo para establecer el valor del campo periodo
	 * @param string $periodo
	 */
	public function setPeriodo($periodo){
		$this->periodo = $periodo;
	}

    public function setPerini($perini){
		$this->perini = $perini;
	}

	public function setPerfin($perfin){
		$this->perfin = $perfin;
	}

	public function setFecpre($fecpre){
		$this->fecpre = $fecpre;
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
	 * Devuelve el valor del campo codcaj
	 * @return string
	 */
	public function getCodcaj(){
		return $this->codcaj;
	}

	/**
	 * Devuelve el valor del campo documento
	 * @return string
	 */
	public function getDocumento(){
		return $this->documento;
	}

	/**
	 * Devuelve el valor del campo nomben
	 * @return string
	 */
	public function getNomben(){
		return $this->nomben;
	}

	/**
	 * Devuelve el valor del campo codben
	 * @return string
	 */
	public function getCodben(){
		return $this->codben;
	}

	/**
	 * Devuelve el valor del campo codcer
	 * @return string
	 */
	public function getCodcer(){
		return $this->codcer;
	}

	/**
	 * Devuelve el valor del campo estado
	 * @return string
	 */
	public function getEstado(){
		return $this->estado;
	}

	/**
	 * Devuelve el valor del campo usuario
	 * @return integer
	 */
	public function getUsuario(){
		return $this->usuario;
	}

	/**
	 * Devuelve el valor del campo motivo
	 * @return string
	 */
	public function getMotivo(){
		return $this->motivo;
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
	 * Devuelve el valor del campo nomarc
	 * @return string
	 */
	public function getNomarc(){
		return $this->nomarc;
	}

	/**
	 * Devuelve el valor del campo periodo
	 * @return string
	 */
	public function getPeriodo(){
		return $this->periodo;
	}

    public function getPerini(){
		return $this->perini;
	}

	public function getPerfin(){
		return $this->perfin;
	}
	public function getFecpre(){
		if($this->fecpre){
			return new Date($this->fecpre);
		} else {
			return null;
		}
	}

}

