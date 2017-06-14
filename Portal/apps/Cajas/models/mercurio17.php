<?php

class Mercurio17 extends ActiveRecord {

	/**
	 * @var integer
	 */
	protected $codpub;

	/**
	 * @var integer
	 */
	protected $numero;

	/**
	 * @var integer
	 */
	protected $nivel;

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
	protected $estado;


	/**
	 * Metodo para establecer el valor del campo codpub
	 * @param integer $codpub
	 */
	public function setCodpub($codpub){
		$this->codpub = $codpub;
	}

	/**
	 * Metodo para establecer el valor del campo numero
	 * @param integer $numero
	 */
	public function setNumero($numero){
		$this->numero = $numero;
	}

	/**
	 * Metodo para establecer el valor del campo nivel
	 * @param integer $nivel
	 */
	public function setNivel($nivel){
		$this->nivel = $nivel;
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
	 * Metodo para establecer el valor del campo estado
	 * @param string $estado
	 */
	public function setEstado($estado){
		$this->estado = $estado;
	}


	/**
	 * Devuelve el valor del campo codpub
	 * @return integer
	 */
	public function getCodpub(){
		return $this->codpub;
	}

	/**
	 * Devuelve el valor del campo numero
	 * @return integer
	 */
	public function getNumero(){
		return $this->numero;
	}

	/**
	 * Devuelve el valor del campo nivel
	 * @return integer
	 */
	public function getNivel(){
		return $this->nivel;
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
	 * Devuelve el valor del campo estado
	 * @return string
	 */
	public function getEstado(){
		return $this->estado;
	}

	/**
	 * Validaciones y reglas de negocio
	 */
	protected function validation(){		
		$this->validate("InclusionIn", array(
			"field" => "estado",
			"domain" => array('A', 'I'),
			"required" => true
		));
		if($this->validationHasFailed()==true){
			return false;
		}
	}

}

