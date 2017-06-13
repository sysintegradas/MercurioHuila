<?php

class Mercurio34 extends ActiveRecord {

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
	protected $cedtra;

	/**
	 * @var string
	 */
	protected $documento;

	/**
	 * @var string
	 */
	protected $cedcon;

	/**
	 * @var string
	 */
	protected $coddoc;

	/**
	 * @var string
	 */
	protected $priape;

	/**
	 * @var string
	 */
	protected $segape;

	/**
	 * @var string
	 */
	protected $prinom;

	/**
	 * @var string
	 */
	protected $segnom;

	/**
	 * @var Date
	 */
	protected $fecnac;

	/**
	 * @var string
	 */
	protected $ciunac;

	/**
	 * @var string
	 */
	protected $sexo;

	/**
	 * @var string
	 */
	protected $parent;
	
	/**
	 * @var string
	 */
	protected $huerfano;	
	
	/**
	 * @var string
	 */
	protected $tiphij;
	
	/**
	 * @var string
	 */
	protected $nivedu;

	/**
	 * @var string
	 */
	protected $estudio;

	/**
	 * @var string
	 */
	protected $captra;
	
	/**
	 * @var string
	 */
	protected $tipdis;
	
	/**
	 * @var string
	 */
	protected $calendario;

	/**
	 * @var string
	 */
	protected $estado;

	/**
	 * @var string
	 */
	protected $motivo;

	/**
	 * @var Date
	 */
	protected $fecest;

	protected $usuario;

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
	 * Metodo para establecer el valor del campo cedtra
	 * @param string $cedtra
	 */
	public function setCedtra($cedtra){
		$this->cedtra = $cedtra;
	}

	/**
	 * Metodo para establecer el valor del campo documento
	 * @param string $documento
	 */
	public function setDocumento($documento){
		$this->documento = $documento;
	}

	/**
	 * Metodo para establecer el valor del campo cedcon
	 * @param string $cedcon
	 */
	public function setCedcon($cedcon){
		$this->cedcon = $cedcon;
	}

	/**
	 * Metodo para establecer el valor del campo coddoc
	 * @param string $coddoc
	 */
	public function setCoddoc($coddoc){
		$this->coddoc = $coddoc;
	}

	/**
	 * Metodo para establecer el valor del campo priape
	 * @param string $priape
	 */
	public function setPriape($priape){
		$this->priape = $priape;
	}

	/**
	 * Metodo para establecer el valor del campo segape
	 * @param string $segape
	 */
	public function setSegape($segape){
		$this->segape = $segape;
	}

	/**
	 * Metodo para establecer el valor del campo prinom
	 * @param string $prinom
	 */
	public function setPrinom($prinom){
		$this->prinom = $prinom;
	}

	/**
	 * Metodo para establecer el valor del campo segnom
	 * @param string $segnom
	 */
	public function setSegnom($segnom){
		$this->segnom = $segnom;
	}

	/**
	 * Metodo para establecer el valor del campo fecnac
	 * @param Date $fecnac
	 */
	public function setFecnac($fecnac){
		$this->fecnac = $fecnac;
	}

	/**
	 * Metodo para establecer el valor del campo ciunac
	 * @param string $ciunac
	 */
	public function setCiunac($ciunac){
		$this->ciunac = $ciunac;
	}

	/**
	 * Metodo para establecer el valor del campo sexo
	 * @param string $sexo
	 */
	public function setSexo($sexo){
		$this->sexo = $sexo;
	}

	/**
	 * Metodo para establecer el valor del campo parent
	 * @param string $parent
	 */
	public function setParent($parent){
		$this->parent = $parent;
	}

	/**
	 * Metodo para establecer el valor del campo huerfano
	 * @param string $huerfano
	 */
	public function setHuerfano($huerfano){
		$this->huerfano = $huerfano;
	}

	/**
	 * Metodo para establecer el valor del campo tiphij
	 * @param string $tiphij
	 */
	public function setTiphij($tiphij){
		$this->tiphij = $tiphij;
	}

	/**
	 * Metodo para establecer el valor del campo nivedu
	 * @param string $nivedu
	 */
	public function setNivedu($nivedu){
		$this->nivedu = $nivedu;
	}

	/**
	 * Metodo para establecer el valor del campo estudio
	 * @param string $estudio
	 */
	public function setEstudio($estudio){
		$this->estudio = $estudio;
	}

	/**
	 * Metodo para establecer el valor del campo estado
	 * @param string $estado
	 */
	public function setEstado($estado){
		$this->estado = $estado;
	}

	/**
	 * Metodo para establecer el valor del campo captra
	 * @param string $captra
	 */
	public function setCaptra($captra){
		$this->captra = $captra;
	}

	/**
	 * Metodo para establecer el valor del campo tipdis
	 * @param string $tipdis
	 */
	public function setTipdis($tipdis){
		$this->tipdis = $tipdis;
	}

	/**
	 * Metodo para establecer el valor del campo calendario
	 * @param string $calendario
	 */
	public function setCalendario($calendario){
		$this->calendario = $calendario;
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

	public function setUsuario($usuario){
		$this->usuario = $usuario;
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
	 * Devuelve el valor del campo cedtra
	 * @return string
	 */
	public function getCedtra(){
		return $this->cedtra;
	}

	/**
	 * Devuelve el valor del campo documento
	 * @return string
	 */
	public function getDocumento(){
		return $this->documento;
	}

	/**
	 * Devuelve el valor del campo cedcon
	 * @return string
	 */
	public function getCedcon(){
		return $this->cedcon;
	}

	/**
	 * Devuelve el valor del campo coddoc
	 * @return string
	 */
	public function getCoddoc(){
		return $this->coddoc;
	}

	/**
	 * Devuelve el valor del campo priape
	 * @return string
	 */
	public function getPriape(){
		return $this->priape;
	}

	/**
	 * Devuelve el valor del campo segape
	 * @return string
	 */
	public function getSegape(){
		return $this->segape;
	}

	/**
	 * Devuelve el valor del campo prinom
	 * @return string
	 */
	public function getPrinom(){
		return $this->prinom;
	}

	/**
	 * Devuelve el valor del campo segnom
	 * @return string
	 */
	public function getSegnom(){
		return $this->segnom;
	}

	/**
	 * Devuelve el valor del campo fecnac
	 * @return Date
	 */
	public function getFecnac(){
		if($this->fecnac){
			return new Date($this->fecnac);
		} else {
			return null;
		}
	}

	/**
	 * Devuelve el valor del campo ciunac
	 * @return string
	 */
	public function getCiunac(){
		return $this->ciunac;
	}

	/**
	 * Devuelve el valor del campo sexo
	 * @return string
	 */
	public function getSexo(){
		return $this->sexo;
	}

	/**
	 * Devuelve el valor del campo parent
	 * @return string
	 */
	public function getParent(){
		return $this->parent;
	}

	/**
	 * Devuelve el valor del campo huerfano
	 * @return string
	 */
	public function getHuerfano(){
		return $this->huerfano;
	}

	/**
	 * Devuelve el valor del campo tiphij
	 * @return string
	 */
	public function getTiphij(){
		return $this->tiphij;
	}

	/**
	 * Devuelve el valor del campo nivedu
	 * @return string
	 */
	public function getNivedu(){
		return $this->nivedu;
	}

	/**
	 * Devuelve el valor del campo estudio
	 * @return string
	 */
	public function getEstudio(){
		return $this->estudio;
	}

	/**
	 * Devuelve el valor del campo estado
	 * @return string
	 */
	public function getEstado(){
		return $this->estado;
	}

	/**
	 * Devuelve el valor del campo captra
	 * @return string
	 */
	public function getCaptra(){
		return $this->captra;
	}

	/**
	 * Devuelve el valor del campo tipdis
	 * @return string
	 */
	public function getTipdis(){
		return $this->tipdis;
	}

	/**
	 * Devuelve el valor del campo calendario
	 * @return string
	 */
	public function getCalendario(){
		return $this->calendario;
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
	 * Validaciones y reglas de negocio
	 */
	protected function validation(){		
		$this->validate("InclusionIn", array(
			"field" => "estado",
			"domain" => array('P', 'A', 'X'),
			"required" => true
		));
		if($this->validationHasFailed()==true){
			return false;
		}
	}

	public function getUsuario(){

		return $this->usuario;
	}
}

