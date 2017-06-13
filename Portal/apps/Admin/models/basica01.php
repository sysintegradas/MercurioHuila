<?php

class Basica01 extends ActiveRecord {

	/**
	 * @var string
	 */
	protected $codare;

	/**
	 * @var string
	 */
	protected $detalle;


	/**
	 * Metodo para establecer el valor del campo codare
	 * @param string $codare
	 */
	public function setCodare($codare){
		$this->codare = $codare;
	}

	/**
	 * Metodo para establecer el valor del campo detalle
	 * @param string $detalle
	 */
	public function setDetalle($detalle){
		$this->detalle = $detalle;
	}


	/**
	 * Devuelve el valor del campo codare
	 * @return string
	 */
	public function getCodare(){
		return $this->codare;
	}

	/**
	 * Devuelve el valor del campo detalle
	 * @return string
	 */
	public function getDetalle(){
		return $this->detalle;
	}


    public function opciones(){
        $model = array(
            "codare"=>array("descripcion"=>"Area","type"=>"text","size"=>"5","null"=>true,"width"=>"20"),
            "detalle"=>array("type"=>"text","size"=>"20","width"=>"50")
        );
        return $model;
    }

}

