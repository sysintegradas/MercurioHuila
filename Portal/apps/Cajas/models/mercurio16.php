<?php

class Mercurio16 extends ActiveRecord {

	/**
	 * @var integer
	 */
	protected $numero;

	/**
	 * @var integer
	 */
	protected $numcon;

	/**
	 * @var string
	 */
	protected $nota;

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
	 * Metodo para establecer el valor del campo numcon
	 * @param integer $numcon
	 */
	public function setNumcon($numcon){
		$this->numcon = $numcon;
	}

	/**
	 * Metodo para establecer el valor del campo nota
	 * @param string $nota
	 */
	public function setNota($nota){
		$this->nota = $nota;
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
	 * Devuelve el valor del campo numcon
	 * @return integer
	 */
	public function getNumcon(){
		return $this->numcon;
	}

	/**
	 * Devuelve el valor del campo nota
	 * @return string
	 */
	public function getNota(){
		return $this->nota;
	}

	/**
	 * Devuelve el valor del campo nomarc
	 * @return string
	 */
	public function getNomarc(){
		return $this->nomarc;
	}

}

