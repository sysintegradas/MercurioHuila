<?php

class Basica06 extends ActiveRecord {

	/**
	 * @var integer
	 */
	protected $numero;

	/**
	 * @var string
	 */
	protected $detalle;

	/**
	 * @var string
	 */
	protected $lugar;

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
	protected $horini;

	/**
	 * @var string
	 */
	protected $horfin;

	/**
	 * @var string
	 */
	protected $obliga;

	/**
	 * @var string
	 */
	protected $nota;


	/**
	 * Metodo para establecer el valor del campo numero
	 * @param integer $numero
	 */
	public function setNumero($numero){
		$this->numero = $numero;
	}

	/**
	 * Metodo para establecer el valor del campo detalle
	 * @param string $detalle
	 */
	public function setDetalle($detalle){
		$this->detalle = $detalle;
	}

	/**
	 * Metodo para establecer el valor del campo lugar
	 * @param string $lugar
	 */
	public function setLugar($lugar){
		$this->lugar = $lugar;
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
	 * Metodo para establecer el valor del campo horini
	 * @param string $horini
	 */
	public function setHorini($horini){
		$this->horini = $horini;
	}

	/**
	 * Metodo para establecer el valor del campo horfin
	 * @param string $horfin
	 */
	public function setHorfin($horfin){
		$this->horfin = $horfin;
	}

	/**
	 * Metodo para establecer el valor del campo obliga
	 * @param string $obliga
	 */
	public function setObliga($obliga){
		$this->obliga = $obliga;
	}

	/**
	 * Metodo para establecer el valor del campo nota
	 * @param string $nota
	 */
	public function setNota($nota){
		$this->nota = $nota;
	}


	/**
	 * Devuelve el valor del campo numero
	 * @return integer
	 */
	public function getNumero(){
		return $this->numero;
	}

	/**
	 * Devuelve el valor del campo detalle
	 * @return string
	 */
	public function getDetalle(){
		return $this->detalle;
	}

	/**
	 * Devuelve el valor del campo lugar
	 * @return string
	 */
	public function getLugar(){
		return $this->lugar;
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
	 * Devuelve el valor del campo horini
	 * @return string
	 */
	public function getHorini(){
		return $this->horini;
	}

	/**
	 * Devuelve el valor del campo horfin
	 * @return string
	 */
	public function getHorfin(){
		return $this->horfin;
	}

	/**
	 * Devuelve el valor del campo obliga
	 * @return string
	 */
	public function getObliga(){
		return $this->obliga;
	}

	/**
	 * Devuelve el valor del campo nota
	 * @return string
	 */
	public function getNota(){
		return $this->nota;
	}

	/**
	 * Validaciones y reglas de negocio
	 */
	protected function validation(){		
		$this->validate("InclusionIn", array(
			"field" => "obliga",
			"domain" => array('S', 'N'),
			"required" => true
		));
		if($this->validationHasFailed()==true){
			return false;
		}
	}

}

