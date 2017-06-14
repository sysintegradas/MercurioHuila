<?php

class Mercurio32 extends ActiveRecord {

	/**
	 * @var integer
	 */
	protected $id;

	/**
	 * @var integer
	 */
	protected $log;

	/**
	 * @var string
	 */
	protected $codcaj;

	/**
	 * @var string
	 */
	protected $cedtra;

	/**
	 * @var string
	 */
	protected $cajcon;

	/**
	 * @var string
	 */
	protected $cedcon;

	/**
	 * @var string
	 */
	protected $coddoc;

	/**
	 * @var string
	 */
	protected $priape;

	/**
	 * @var string
	 */
	protected $segape;

	/**
	 * @var string
	 */
	protected $prinom;

	/**
	 * @var string
	 */
	protected $segnom;

	/**
	 * @var Date
	 */
	protected $fecnac;

	/**
	 * @var string
	 */
	protected $ciunac;

	/**
	 * @var string
	 */
	protected $sexo;

	/**
	 * @var string
	 */
	protected $estciv;

	/**
	 * @var string
	 */
	protected $comper;

	/**
	 * @var string
	 */
	protected $ciures;

	/**
	 * @var string
	 */
	protected $codzon;

	/**
	 * @var string
	 */
	protected $tipviv;

	/**
	 * @var string
	 */
	protected $direccion;

	/**
	 * @var string
	 */
	protected $barrio;

	/**
	 * @var string
	 */
	protected $telefono;

	/**
	 * @var string
	 */
	protected $celular;

	/**
	 * @var string
	 */
	protected $email;

	/**
	 * @var string
	 */
	protected $nivedu;

	/**
	 * @var Date
	 */
	protected $fecing;

	/**
	 * @var string
	 */
	protected $codocu;

	/**
	 * @var integer
	 */
	protected $salario;

	/**
	 * @var string
	 */
	protected $estado;

	/**
	 * @var string
	 */
	protected $motivo;

	/**
	 * @var Date
	 */
	protected $fecest;

	/**
	 * @var integer
	 */
	protected $usuario;


	/**
	 * Metodo para establecer el valor del campo id
	 * @param integer $id
	 */
	public function setId($id){
		$this->id = $id;
	}

	/**
	 * Metodo para establecer el valor del campo log
	 * @param integer $log
	 */
	public function setLog($log){
		$this->log = $log;
	}

	/**
	 * Metodo para establecer el valor del campo codcaj
	 * @param string $codcaj
	 */
	public function setCodcaj($codcaj){
		$this->codcaj = $codcaj;
	}

	/**
	 * Metodo para establecer el valor del campo cedtra
	 * @param string $cedtra
	 */
	public function setCedtra($cedtra){
		$this->cedtra = $cedtra;
	}

	/**
	 * Metodo para establecer el valor del campo cajcon
	 * @param string $cajcon
	 */
	public function setCajcon($cajcon){
		$this->cajcon = $cajcon;
	}

	/**
	 * Metodo para establecer el valor del campo cedcon
	 * @param string $cedcon
	 */
	public function setCedcon($cedcon){
		$this->cedcon = $cedcon;
	}

	/**
	 * Metodo para establecer el valor del campo coddoc
	 * @param string $coddoc
	 */
	public function setCoddoc($coddoc){
		$this->coddoc = $coddoc;
	}

	/**
	 * Metodo para establecer el valor del campo priape
	 * @param string $priape
	 */
	public function setPriape($priape){
		$this->priape = $priape;
	}

	/**
	 * Metodo para establecer el valor del campo segape
	 * @param string $segape
	 */
	public function setSegape($segape){
		$this->segape = $segape;
	}

	/**
	 * Metodo para establecer el valor del campo prinom
	 * @param string $prinom
	 */
	public function setPrinom($prinom){
		$this->prinom = $prinom;
	}

	/**
	 * Metodo para establecer el valor del campo segnom
	 * @param string $segnom
	 */
	public function setSegnom($segnom){
		$this->segnom = $segnom;
	}

	/**
	 * Metodo para establecer el valor del campo fecnac
	 * @param Date $fecnac
	 */
	public function setFecnac($fecnac){
		$this->fecnac = $fecnac;
	}

	/**
	 * Metodo para establecer el valor del campo ciunac
	 * @param string $ciunac
	 */
	public function setCiunac($ciunac){
		$this->ciunac = $ciunac;
	}

	/**
	 * Metodo para establecer el valor del campo sexo
	 * @param string $sexo
	 */
	public function setSexo($sexo){
		$this->sexo = $sexo;
	}

	/**
	 * Metodo para establecer el valor del campo estciv
	 * @param string $estciv
	 */
	public function setEstciv($estciv){
		$this->estciv = $estciv;
	}

	/**
	 * Metodo para establecer el valor del campo comper
	 * @param string $comper
	 */
	public function setComper($comper){
		$this->comper = $comper;
	}

	/**
	 * Metodo para establecer el valor del campo ciures
	 * @param string $ciures
	 */
	public function setCiures($ciures){
		$this->ciures = $ciures;
	}

	/**
	 * Metodo para establecer el valor del campo codzon
	 * @param string $codzon
	 */
	public function setCodzon($codzon){
		$this->codzon = $codzon;
	}

	/**
	 * Metodo para establecer el valor del campo tipviv
	 * @param string $tipviv
	 */
	public function setTipviv($tipviv){
		$this->tipviv = $tipviv;
	}

	/**
	 * Metodo para establecer el valor del campo direccion
	 * @param string $direccion
	 */
	public function setDireccion($direccion){
		$this->direccion = $direccion;
	}

	/**
	 * Metodo para establecer el valor del campo barrio
	 * @param string $barrio
	 */
	public function setBarrio($barrio){
		$this->barrio = $barrio;
	}

	/**
	 * Metodo para establecer el valor del campo telefono
	 * @param string $telefono
	 */
	public function setTelefono($telefono){
		$this->telefono = $telefono;
	}

	/**
	 * Metodo para establecer el valor del campo celular
	 * @param string $celular
	 */
	public function setCelular($celular){
		$this->celular = $celular;
	}

	/**
	 * Metodo para establecer el valor del campo email
	 * @param string $email
	 */
	public function setEmail($email){
		$this->email = $email;
	}

	/**
	 * Metodo para establecer el valor del campo nivedu
	 * @param string $nivedu
	 */
	public function setNivedu($nivedu){
		$this->nivedu = $nivedu;
	}

	/**
	 * Metodo para establecer el valor del campo fecing
	 * @param Date $fecing
	 */
	public function setFecing($fecing){
		$this->fecing = $fecing;
	}

	/**
	 * Metodo para establecer el valor del campo codocu
	 * @param string $codocu
	 */
	public function setCodocu($codocu){
		$this->codocu = $codocu;
	}

	/**
	 * Metodo para establecer el valor del campo salario
	 * @param integer $salario
	 */
	public function setSalario($salario){
		$this->salario = $salario;
	}

	/**
	 * Metodo para establecer el valor del campo estado
	 * @param string $estado
	 */
	public function setEstado($estado){
		$this->estado = $estado;
	}

	/**
	 * Metodo para establecer el valor del campo motivo
	 * @param string $motivo
	 */
	public function setMotivo($motivo){
		$this->motivo = $motivo;
	}

	/**
	 * Metodo para establecer el valor del campo fecest
	 * @param Date $fecest
	 */
	public function setFecest($fecest){
		$this->fecest = $fecest;
	}

	/**
	 * Metodo para establecer el valor del campo usuario
	 * @param integer $usuario
	 */
	public function setUsuario($usuario){
		$this->usuario = $usuario;
	}


	/**
	 * Devuelve el valor del campo id
	 * @return integer
	 */
	public function getId(){
		return $this->id;
	}

	/**
	 * Devuelve el valor del campo log
	 * @return integer
	 */
	public function getLog(){
		return $this->log;
	}

	/**
	 * Devuelve el valor del campo codcaj
	 * @return string
	 */
	public function getCodcaj(){
		return $this->codcaj;
	}

	/**
	 * Devuelve el valor del campo cedtra
	 * @return string
	 */
	public function getCedtra(){
		return $this->cedtra;
	}

	/**
	 * Devuelve el valor del campo cajcon
	 * @return string
	 */
	public function getCajcon(){
		return $this->cajcon;
	}

	/**
	 * Devuelve el valor del campo cedcon
	 * @return string
	 */
	public function getCedcon(){
		return $this->cedcon;
	}

	/**
	 * Devuelve el valor del campo coddoc
	 * @return string
	 */
	public function getCoddoc(){
		return $this->coddoc;
	}

	/**
	 * Devuelve el valor del campo priape
	 * @return string
	 */
	public function getPriape(){
		return $this->priape;
	}

	/**
	 * Devuelve el valor del campo segape
	 * @return string
	 */
	public function getSegape(){
		return $this->segape;
	}

	/**
	 * Devuelve el valor del campo prinom
	 * @return string
	 */
	public function getPrinom(){
		return $this->prinom;
	}

	/**
	 * Devuelve el valor del campo segnom
	 * @return string
	 */
	public function getSegnom(){
		return $this->segnom;
	}

	/**
	 * Devuelve el valor del campo fecnac
	 * @return Date
	 */
	public function getFecnac(){
		if($this->fecnac){
			return new Date($this->fecnac);
		} else {
			return null;
		}
	}

	/**
	 * Devuelve el valor del campo ciunac
	 * @return string
	 */
	public function getCiunac(){
		return $this->ciunac;
	}

	/**
	 * Devuelve el valor del campo sexo
	 * @return string
	 */
	public function getSexo(){
		return $this->sexo;
	}

	/**
	 * Devuelve el valor del campo estciv
	 * @return string
	 */
	public function getEstciv(){
		return $this->estciv;
	}

	/**
	 * Devuelve el valor del campo comper
	 * @return string
	 */
	public function getComper(){
		return $this->comper;
	}

	/**
	 * Devuelve el valor del campo ciures
	 * @return string
	 */
	public function getCiures(){
		return $this->ciures;
	}

	/**
	 * Devuelve el valor del campo codzon
	 * @return string
	 */
	public function getCodzon(){
		return $this->codzon;
	}

	/**
	 * Devuelve el valor del campo tipviv
	 * @return string
	 */
	public function getTipviv(){
		return $this->tipviv;
	}

	/**
	 * Devuelve el valor del campo direccion
	 * @return string
	 */
	public function getDireccion(){
		return $this->direccion;
	}

	/**
	 * Devuelve el valor del campo barrio
	 * @return string
	 */
	public function getBarrio(){
		return $this->barrio;
	}

	/**
	 * Devuelve el valor del campo telefono
	 * @return string
	 */
	public function getTelefono(){
		return $this->telefono;
	}

	/**
	 * Devuelve el valor del campo celular
	 * @return string
	 */
	public function getCelular(){
		return $this->celular;
	}

	/**
	 * Devuelve el valor del campo email
	 * @return string
	 */
	public function getEmail(){
		return $this->email;
	}

	/**
	 * Devuelve el valor del campo nivedu
	 * @return string
	 */
	public function getNivedu(){
		return $this->nivedu;
	}

	/**
	 * Devuelve el valor del campo fecing
	 * @return Date
	 */
	public function getFecing(){
		if($this->fecing){
			return new Date($this->fecing);
		} else {
			return null;
		}
	}

	/**
	 * Devuelve el valor del campo codocu
	 * @return string
	 */
	public function getCodocu(){
		return $this->codocu;
	}

	/**
	 * Devuelve el valor del campo salario
	 * @return integer
	 */
	public function getSalario(){
		return $this->salario;
	}

	/**
	 * Devuelve el valor del campo estado
	 * @return string
	 */
	public function getEstado(){
		return $this->estado;
	}
    public function getEstadoArray(){
        return array("P"=>"PENDIENTE","X"=>"RECHAZADO","A"=>"APROBADO");
    }

    public function getEstadoDetalle(){
        $retorno="";
        switch($this->estado){
            case 'P': $retorno='PENDIENTE'; break;
            case 'X': $retorno='RECHAZADO'; break;
            case 'A': $retorno='APROBADO'; break;
        }
        return $retorno;
    }



	/**
	 * Devuelve el valor del campo motivo
	 * @return string
	 */
	public function getMotivo(){
		return $this->motivo;
	}

	/**
	 * Devuelve el valor del campo fecest
	 * @return Date
	 */
	public function getFecest(){
		if($this->fecest){
			return new Date($this->fecest);
		} else {
			return null;
		}
	}

	/**
	 * Devuelve el valor del campo usuario
	 * @return integer
	 */
	public function getUsuario(){
		return $this->usuario;
	}

	/**
	 * Validaciones y reglas de negocio
	 */
	protected function validation(){		
		$this->validate("InclusionIn", array(
			"field" => "comper",
			"domain" => array('S', 'N'),
			"required" => true
		));
		$this->validate("Email", array(
			"field" => "email",
			"required" => true
		));
		$this->validate("InclusionIn", array(
			"field" => "estado",
			"domain" => array('P', 'A', 'X'),
			"required" => true
		));
		if($this->validationHasFailed()==true){
			return false;
		}
	}

    protected function initialize(){		
        $this->belongsTo("usuario","gener02","usuario");
    }
}

