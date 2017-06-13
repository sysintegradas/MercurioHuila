<?php

class Basica03 extends ActiveRecord {

	/**
	 * @var integer
	 */
	protected $usuario;

	/**
	 * @var integer
	 */
	protected $numero;

	/**
	 * @var string
	 */
	protected $titulo;

	/**
	 * @var string
	 */
	protected $nota;


	/**
	 * Metodo para establecer el valor del campo usuario
	 * @param integer $usuario
	 */
	public function setUsuario($usuario){
		$this->usuario = $usuario;
	}

	/**
	 * Metodo para establecer el valor del campo numero
	 * @param integer $numero
	 */
	public function setNumero($numero){
		$this->numero = $numero;
	}

	/**
	 * Metodo para establecer el valor del campo titulo
	 * @param string $titulo
	 */
	public function setTitulo($titulo){
		$this->titulo = $titulo;
	}

	/**
	 * Metodo para establecer el valor del campo nota
	 * @param string $nota
	 */
	public function setNota($nota){
		$this->nota = $nota;
	}


	/**
	 * Devuelve el valor del campo usuario
	 * @return integer
	 */
	public function getUsuario(){
		return $this->usuario;
	}

	/**
	 * Devuelve el valor del campo numero
	 * @return integer
	 */
	public function getNumero(){
		return $this->numero;
	}

	/**
	 * Devuelve el valor del campo titulo
	 * @return string
	 */
	public function getTitulo(){
		return $this->titulo;
	}

	/**
	 * Devuelve el valor del campo nota
	 * @return string
	 */
	public function getNota(){
		return $this->nota;
	}

}

