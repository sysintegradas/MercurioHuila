<?php

class Mercurio10 extends ActiveRecord {

	/**
	 * @var string
	 */
	protected $codcaj;

	/**
	 * @var string
	 */
	protected $codare;

	/**
	 * @var string
	 */
	protected $codope;

	/**
	 * @var string
	 */
	protected $tipo;

	/**
	 * @var string
	 */
	protected $estado;


	/**
	 * Metodo para establecer el valor del campo codcaj
	 * @param string $codcaj
	 */
	public function setCodcaj($codcaj){
		$this->codcaj = $codcaj;
	}

	/**
	 * Metodo para establecer el valor del campo codare
	 * @param string $codare
	 */
	public function setCodare($codare){
		$this->codare = $codare;
	}

	/**
	 * Metodo para establecer el valor del campo codope
	 * @param string $codope
	 */
	public function setCodope($codope){
		$this->codope = $codope;
	}

	/**
	 * Metodo para establecer el valor del campo tipo
	 * @param string $tipo
	 */
	public function setTipo($tipo){
		$this->tipo = $tipo;
	}

	/**
	 * Metodo para establecer el valor del campo estado
	 * @param string $estado
	 */
	public function setEstado($estado){
		$this->estado = $estado;
	}


	/**
	 * Devuelve el valor del campo codcaj
	 * @return string
	 */
	public function getCodcaj(){
		return $this->codcaj;
	}

	/**
	 * Devuelve el valor del campo codare
	 * @return string
	 */
	public function getCodare(){
		return $this->codare;
	}

	/**
	 * Devuelve el valor del campo codope
	 * @return string
	 */
	public function getCodope(){
		return $this->codope;
	}

	/**
	 * Devuelve el valor del campo tipo
	 * @return string
	 */
	public function getTipo(){
		return $this->tipo;
	}

    public function getTipoArray(){
        return array("1"=>"1","2"=>"2","3"=>"3","4"=>"4");
    }

    public function getTipoDetalle(){
        $retorno="";
        switch($this->tipo){
            case '1': $retorno='1'; break;
            case '2': $retorno='2'; break;
            case '3': $retorno='3'; break;
            case '4': $retorno='4'; break;
        }
        return $retorno;
    }

	/**
	 * Devuelve el valor del campo estado
	 * @return string
	 */
	public function getEstado(){
		return $this->estado;
	}

    public function getEstadoArray(){
        return array("A"=>"ACTIVO","I"=>"INACTIVO");
    }

    public function getEstadoDetalle(){
        $retorno="";
        switch($this->estado){
            case 'A': $retorno='ACTIVO'; break;
            case 'I': $retorno='INACTIVO'; break;
        }
        return $retorno;
    }

	/**
	 * Validaciones y reglas de negocio
	 */
	protected function validation(){		
		$this->validate("InclusionIn", array(
			"field" => "tipo",
			"domain" => array('1', '2', '3', '4'),
			"required" => true
		));
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

