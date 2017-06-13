<?php

class Mercurio11 extends ActiveRecord {

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
	protected $detalle;

	/**
	 * @var string
	 */
	protected $tipo;

	/**
	 * @var string
	 */
	protected $mandoc;

	/**
	 * @var string
	 */
	protected $webser;
        protected $url;

	/**
	 * @var string
	 */
	protected $nota;
	protected $orden;


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
	 * Metodo para establecer el valor del campo detalle
	 * @param string $detalle
	 */
	public function setDetalle($detalle){
		$this->detalle = $detalle;
	}

	/**
	 * Metodo para establecer el valor del campo tipo
	 * @param string $tipo
	 */
	public function setTipo($tipo){
		$this->tipo = $tipo;
	}

	/**
	 * Metodo para establecer el valor del campo mandoc
	 * @param string $mandoc
	 */
	public function setMandoc($mandoc){
		$this->mandoc = $mandoc;
	}

	/**
	 * Metodo para establecer el valor del campo webser
	 * @param string $webser
	 */
	public function setWebser($webser){
		$this->webser = $webser;
	}
        
        public function setUrl($url){
		$this->url = $url;
	}

	/**
	 * Metodo para establecer el valor del campo nota
	 * @param string $nota
	 */
	public function setNota($nota){
		$this->nota = $nota;
	}

	public function setOrden($orden){
		$this->orden = $orden;
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
	 * Devuelve el valor del campo detalle
	 * @return string
	 */
	public function getDetalle(){
		return $this->detalle;
	}

	/**
	 * Devuelve el valor del campo tipo
	 * @return string
	 */
	public function getTipo(){
		return $this->tipo;
	}

	/**
	 * Devuelve el valor del campo mandoc
	 * @return string
	 */
	public function getMandoc(){
		return $this->mandoc;
	}

    public function getMandocArray(){
        return array("S"=>"SI","N"=>"NO");
    }

    public function getMandocDetalle(){
        $retorno="";
        switch($this->mandoc){
            case 'S': $retorno='SI'; break;
            case 'N': $retorno='NO'; break;
        }
        return $retorno;
    }

	/**
	 * Devuelve el valor del campo webser
	 * @return string
	 */
	public function getWebser(){
		return $this->webser;
	}
        
        public function getUrl(){
		return $this->url;
	}

	/**
	 * Devuelve el valor del campo nota
	 * @return string
	 */
	public function getNota(){
		return $this->nota;
	}

	public function getOrden(){
		return $this->orden;
	}

	/**
	 * Validaciones y reglas de negocio
	 */
	protected function validation(){		
		$this->validate("InclusionIn", array(
			"field" => "mandoc",
			"domain" => array('S', 'N'),
			"required" => true
		));
		if($this->validationHasFailed()==true){
			return false;
		}
	}

}

