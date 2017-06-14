<?php

class Mercurio04 extends ActiveRecord {

	/**
	 * @var integer
	 */
	protected $codpub;

	/**
	 * @var string
	 */
	protected $titulo;

	/**
	 * @var string
	 */
	protected $imagen;

	/**
	 * @var string
	 */
	protected $nota;

	/**
	 * @var string
	 */
	protected $enlace;


	/**
	 * Metodo para establecer el valor del campo codpub
	 * @param integer $codpub
	 */
	public function setCodpub($codpub){
		$this->codpub = $codpub;
	}

	/**
	 * Metodo para establecer el valor del campo titulo
	 * @param string $titulo
	 */
	public function setTitulo($titulo){
		$this->titulo = $titulo;
	}

	/**
	 * Metodo para establecer el valor del campo imagen
	 * @param string $imagen
	 */
	public function setImagen($imagen){
		$this->imagen = $imagen;
	}

	/**
	 * Metodo para establecer el valor del campo nota
	 * @param string $nota
	 */
	public function setNota($nota){
		$this->nota = $nota;
	}

	/**
	 * Metodo para establecer el valor del campo enlace
	 * @param string $enlace
	 */
	public function setEnlace($enlace){
		$this->enlace = $enlace;
	}


	/**
	 * Devuelve el valor del campo codpub
	 * @return integer
	 */
	public function getCodpub(){
		return $this->codpub;
	}

	/**
	 * Devuelve el valor del campo titulo
	 * @return string
	 */
	public function getTitulo(){
		return $this->titulo;
	}

	/**
	 * Devuelve el valor del campo imagen
	 * @return string
	 */
	public function getImagen(){
		return $this->imagen;
	}

	/**
	 * Devuelve el valor del campo nota
	 * @return string
	 */
	public function getNota(){
		return $this->nota;
	}

	/**
	 * Devuelve el valor del campo enlace
	 * @return string
	 */
	public function getEnlace(){
		return $this->enlace;
	}

}

