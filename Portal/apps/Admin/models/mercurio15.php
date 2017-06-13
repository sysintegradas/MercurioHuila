<?php

class Mercurio15 extends ActiveRecord {

	/**
	 * @var integer
	 */
	protected $numero;

	/**
	 * @var integer
	 */
	protected $coddoc;

	/**
	 * @var string
	 */
	protected $nomarc;


	/**
	 * Metodo para establecer el valor del campo numero
	 * @param integer $numero
	 */
	public function setNumero($numero){
		$this->numero = $numero;
	}

	/**
	 * Metodo para establecer el valor del campo coddoc
	 * @param integer $coddoc
	 */
	public function setCoddoc($coddoc){
		$this->coddoc = $coddoc;
	}

	/**
	 * Metodo para establecer el valor del campo nomarc
	 * @param string $nomarc
	 */
	public function setNomarc($nomarc){
		$this->nomarc = $nomarc;
	}


	/**
	 * Devuelve el valor del campo numero
	 * @return integer
	 */
	public function getNumero(){
		return $this->numero;
	}

	/**
	 * Devuelve el valor del campo coddoc
	 * @return integer
	 */
	public function getCoddoc(){
		return $this->coddoc;
	}

	/**
	 * Devuelve el valor del campo nomarc
	 * @return string
	 */
	public function getNomarc(){
		return $this->nomarc;
	}

}

