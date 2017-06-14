<?php

class Mercurio21 extends ActiveRecord {

	/**
	 * @var integer
	 */
	protected $id;

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
	protected $documento;

	/**
	 * @var string
	 */
	protected $ip;

	/**
	 * @var Date
	 */
	protected $fecha;

	/**
	 * @var string
	 */
	protected $hora;

	/**
	 * @var string
	 */
	protected $controlador;

	/**
	 * @var string
	 */
	protected $accion;

	/**
	 * @var string
	 */
	protected $nota;


	/**
	 * Metodo para establecer el valor del campo id
	 * @param integer $id
	 */
	public function setId($id){
		$this->id = $id;
	}

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
	 * Metodo para establecer el valor del campo documento
	 * @param string $documento
	 */
	public function setDocumento($documento){
		$this->documento = $documento;
	}

	/**
	 * Metodo para establecer el valor del campo ip
	 * @param string $ip
	 */
	public function setIp($ip){
		$this->ip = $ip;
	}

	/**
	 * Metodo para establecer el valor del campo fecha
	 * @param Date $fecha
	 */
	public function setFecha($fecha){
		$this->fecha = $fecha;
	}

	/**
	 * Metodo para establecer el valor del campo hora
	 * @param string $hora
	 */
	public function setHora($hora){
		$this->hora = $hora;
	}

	/**
	 * Metodo para establecer el valor del campo controlador
	 * @param string $controlador
	 */
	public function setControlador($controlador){
		$this->controlador = $controlador;
	}

	/**
	 * Metodo para establecer el valor del campo accion
	 * @param string $accion
	 */
	public function setAccion($accion){
		$this->accion = $accion;
	}

	/**
	 * Metodo para establecer el valor del campo nota
	 * @param string $nota
	 */
	public function setNota($nota){
		$this->nota = $nota;
	}


	/**
	 * Devuelve el valor del campo id
	 * @return integer
	 */
	public function getId(){
		return $this->id;
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

    public function getTipoArray(){
        return array('T'=>'Afiliado','E'=>'Empresa','P'=>'Particular');
    }

    public function getTipoDetalle(){
        $retorno="";
        switch($this->tipo){
            case 'T': $retorno='Afiliado'; break;
            case 'E': $retorno='Empresa'; break;
            case 'P': $retorno='Particular'; break;
        }
        return $retorno;
    }

	/**
	 * Devuelve el valor del campo documento
	 * @return string
	 */
	public function getDocumento(){
		return $this->documento;
	}

	/**
	 * Devuelve el valor del campo ip
	 * @return string
	 */
	public function getIp(){
		return $this->ip;
	}

	/**
	 * Devuelve el valor del campo fecha
	 * @return Date
	 */
	public function getFecha(){
		if($this->fecha){
			return new Date($this->fecha);
		} else {
			return null;
		}
	}

	/**
	 * Devuelve el valor del campo hora
	 * @return string
	 */
	public function getHora(){
		return $this->hora;
	}

	/**
	 * Devuelve el valor del campo controlador
	 * @return string
	 */
	public function getControlador(){
		return $this->controlador;
	}

	/**
	 * Devuelve el valor del campo accion
	 * @return string
	 */
	public function getAccion(){
		return $this->accion;
	}

    public function getAccionArray(){
        return array('addempresa'=>'Afiliacion como empresa','addtrabajador'=>'Afiliacion como trabajador','actdat'=>'Actualizacion de datos','cambioDatosPrincipales'=>'Cambio de datos principales','cargueCertificados'=>'Anexo de certificados','ingben'=>'Ingreso beneficiario','ingcon'=>'Ingreso conyuge');
    }

    public function getAccionDetalle(){
        $retorno="";
        switch($this->accion){
            case 'addempresa': $retorno='Afiliacion como empresa'; break;
            case 'addtrabajador': $retorno='Afiliacion como trabajador'; break;
            case 'actdat': $retorno='Actualizacion de datos'; break;
            case 'cambioDatosPrincipales': $retorno='Cambio de datos principales'; break;
            case 'cargueCertificados': $retorno='Anexo de certificados'; break;
            case 'ingben': $retorno='Ingreso beneficiario'; break;
            case 'ingcon': $retorno='Ingreso conyuge'; break;
        }
        return $retorno;
    }


	/**
	 * Devuelve el valor del campo nota
	 * @return string
	 */
	public function getNota(){
		return $this->nota;
	}

    public function getNombreDetalle(){
        $det = '';
        if($this->getMercurio07())
            $det = $this->getMercurio07()->getNombre();
        return $det;
    }

    protected function initialize(){		
        $this->belongsTo("documento","mercurio07","documento");
    }

}

