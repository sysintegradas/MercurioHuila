<?php

class Gener02 extends ActiveRecord {

	/**
	 * @var integer
	 */
	protected $usuario;

	/**
	 * @var string
	 */
	protected $cedtra;

	/**
	 * @var string
	 */
	protected $nombre;

	/**
	 * @var string
	 */
	protected $tipfun;

	/**
	 * @var string
	 */
	protected $estado;

	/**
	 * @var string
	 */
	protected $clave;

	/**
	 * @var date
	 */
	protected $feccla;

	/**
	 * @var string
	 */
	protected $login;

	/**
	 * Metodo para establecer el valor del campo usuario
	 * @param integer $usuario
	 */
	public function setUsuario($usuario){
		$this->usuario = $usuario;
	}

	/**
	 * Metodo para establecer el valor del campo cedtra
	 * @param string $cedtra
	 */
	public function setCedtra($cedtra){
		$this->cedtra = $cedtra;
	}

	/**
	 * Metodo para establecer el valor del campo nombre
	 * @param string $nombre
	 */
	public function setNombre($nombre){
		$this->nombre = $nombre;
	}

	/**
	 * Metodo para establecer el valor del campo tipfun
	 * @param string $tipfun
	 */
	public function setTipfun($tipfun){
		$this->tipfun = $tipfun;
	}

	/**
	 * Metodo para establecer el valor del campo estado
	 * @param string $estado
	 */
	public function setEstado($estado){
		$this->estado = $estado;
	}

	/**
	 * Metodo para establecer el valor del campo clave
	 * @param string $clave
	 */
	public function setClave($clave){
		$this->clave = $clave;
	}

	public function setFeccla($feccla){
		$this->feccla = $feccla;
	}

	/**
	 * Metodo para establecer el valor del campo login
	 * @param string $login
	 */
	public function setLogin($login){
		$this->login = $login;
	}

	/**
	 * Devuelve el valor del campo usuario
	 * @return integer
	 */
	public function getUsuario(){
		return $this->usuario;
	}

	/**
	 * Devuelve el valor del campo cedtra
	 * @return string
	 */
	public function getCedtra(){
		return $this->cedtra;
	}

	/**
	 * Devuelve el valor del campo nombre
	 * @return string
	 */
	public function getNombre(){
		return $this->nombre;
	}

	/**
	 * Devuelve el valor del campo tipfun
	 * @return string
	 */
	public function getTipfun(){
		return $this->tipfun;
	}

	public function getTipfunDetalle(){
        if($this->getGener21()!=false)
            return $this->getGener21()->getDetalle();
        else
            return "";
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
	 * Devuelve el valor del campo clave
	 * @return string
	 */
	public function getClave(){
		return $this->clave;
	}

	public function getFeccla(){
		return $this->feccla;
	}

	/**
	 * Devuelve el valor del campo login
	 * @return string
	 */
	public function getLogin(){
		return $this->login;
	}

    /**
     * Metodo inicializador de la Entidad
     */
    protected function initialize(){		
        $this->belongsTo("tipfun","gener21","tipfun");
    }

}

