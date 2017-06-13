<?php

class Mercurio19 extends ActiveRecord {

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
	protected $coddoc;

	/**
	 * @var string
	 */
	protected $documento;

	/**
	 * @var string
	 */
	protected $codigo;

	/**
	 * @var string
	 */
	protected $respuesta;


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
	 * Metodo para establecer el valor del campo coddoc
	 * @param string $coddoc
	 */
	public function setCoddoc($coddoc){
		$this->coddoc = $coddoc;
	}

	/**
	 * Metodo para establecer el valor del campo documento
	 * @param string $documento
	 */
	public function setDocumento($documento){
		$this->documento = $documento;
	}

	/**
	 * Metodo para establecer el valor del campo codigo
	 * @param string $codigo
	 */
	public function setCodigo($codigo){
		$this->codigo = $codigo;
	}

	/**
	 * Metodo para establecer el valor del campo respuesta
	 * @param string $respuesta
	 */
	public function setRespuesta($respuesta){
		$this->respuesta = $respuesta;
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
	 * Devuelve el valor del campo coddoc
	 * @return string
	 */
	public function getCoddoc(){
		return $this->coddoc;
	}

	/**
	 * Devuelve el valor del campo documento
	 * @return string
	 */
	public function getDocumento(){
		return $this->documento;
	}

	/**
	 * Devuelve el valor del campo codigo
	 * @return string
	 */
	public function getCodigo(){
		return $this->codigo;
	}

	/**
	 * Devuelve el valor del campo respuesta
	 * @return string
	 */
	public function getRespuesta(){
		return $this->respuesta;
	}

}

