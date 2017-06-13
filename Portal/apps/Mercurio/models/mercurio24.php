<?php

class Mercurio24 extends ActiveRecord {

	/**
	 * @var integer
	 */
	protected $id;

	/**
	 * @var string
	 */
	protected $codcaj;

	/**
	 * @var string
	 */
	protected $tipo;

	/**
	 * @var string
	 */
	protected $documento;

	/**
	 * @var string
	 */
	protected $codcat;

	/**
	 * @var Date
	 */
	protected $fecini;

	/**
	 * @var Date
	 */
	protected $fecfin;

	/**
	 * @var string
	 */
	protected $titulo;

	/**
	 * @var string
	 */
	protected $descripcion;

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
	 * Metodo para establecer el valor del campo id
	 * @param integer $id
	 */
	public function setId($id){
		$this->id = $id;
	}

	/**
	 * Metodo para establecer el valor del campo codcaj
	 * @param string $codcaj
	 */
	public function setCodcaj($codcaj){
		$this->codcaj = $codcaj;
	}

	/**
	 * Metodo para establecer el valor del campo tipo
	 * @param string $tipo
	 */
	public function setTipo($tipo){
		$this->tipo = $tipo;
	}

	/**
	 * Metodo para establecer el valor del campo documento
	 * @param string $documento
	 */
	public function setDocumento($documento){
		$this->documento = $documento;
	}

	/**
	 * Metodo para establecer el valor del campo codcat
	 * @param string $codcat
	 */
	public function setCodcat($codcat){
		$this->codcat = $codcat;
	}

	/**
	 * Metodo para establecer el valor del campo fecini
	 * @param Date $fecini
	 */
	public function setFecini($fecini){
		$this->fecini = $fecini;
	}

	/**
	 * Metodo para establecer el valor del campo fecfin
	 * @param Date $fecfin
	 */
	public function setFecfin($fecfin){
		$this->fecfin = $fecfin;
	}

	/**
	 * Metodo para establecer el valor del campo titulo
	 * @param string $titulo
	 */
	public function setTitulo($titulo){
		$this->titulo = $titulo;
	}

	/**
	 * Metodo para establecer el valor del campo descripcion
	 * @param string $descripcion
	 */
	public function setDescripcion($descripcion){
		$this->descripcion = $descripcion;
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
	 * Devuelve el valor del campo id
	 * @return integer
	 */
	public function getId(){
		return $this->id;
	}

	/**
	 * Devuelve el valor del campo codcaj
	 * @return string
	 */
	public function getCodcaj(){
		return $this->codcaj;
	}

	/**
	 * Devuelve el valor del campo tipo
	 * @return string
	 */
	public function getTipo(){
		return $this->tipo;
	}

	/**
	 * Devuelve el valor del campo documento
	 * @return string
	 */
	public function getDocumento(){
		return $this->documento;
	}

	/**
	 * Devuelve el valor del campo codcat
	 * @return string
	 */
	public function getCodcat(){
		return $this->codcat;
	}

	/**
	 * Devuelve el valor del campo fecini
	 * @return Date
	 */
	public function getFecini(){
		if($this->fecini){
			return new Date($this->fecini);
		} else {
			return null;
		}
	}

	/**
	 * Devuelve el valor del campo fecfin
	 * @return Date
	 */
	public function getFecfin(){
		if($this->fecfin){
			return new Date($this->fecfin);
		} else {
			return null;
		}
	}

	/**
	 * Devuelve el valor del campo titulo
	 * @return string
	 */
	public function getTitulo(){
		return $this->titulo;
	}

	/**
	 * Devuelve el valor del campo descripcion
	 * @return string
	 */
	public function getDescripcion(){
		return $this->descripcion;
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

}

