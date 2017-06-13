<?php

class Mercurio31 extends ActiveRecord {

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
	protected $nit;

	/**
	 * @var string
	 */
	protected $cedtra;

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
	protected $cabhog;

	/**
	 * @var string
	 */
	protected $codciu;

	/**
	 * @var string
	 */
	protected $codzon;

	/**
	 * @var string
	 */
	protected $direccion;

	/**
	 * @var integer
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
	protected $fax;

	/**
	 * @var string
	 */
	protected $email;

	/**
	 * @var Date
	 */
	protected $fecing;

	/**
	 * @var integer
	 */
	protected $salario;

	/**
	 * @var string
	 */
	protected $captra;

	/**
	 * @var string
	 */
	protected $tipdis;

	/**
	 * @var string
	 */
	protected $nivedu;

	/**
	 * @var string
	 */
	protected $rural;

	/**
	 * @var integer
	 */
	protected $horas;

	/**
	 * @var string
	 */
	protected $tipcon;

	/**
	 * @var string
	 */
	protected $vivienda;

	/**
	 * @var string
	 */
	protected $autoriza;

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
	 * @var string
	 */
	protected $tipafi;

	/**
	 * @var integer
	 */
	protected $agencia;

	/**
	 * @var string
	 */
	protected $profesion;

	/**
	 * @var string
	 */
	protected $cargo;


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
	 * Metodo para establecer el valor del campo nit
	 * @param string $nit
	 */
	public function setNit($nit){
		$this->nit = $nit;
	}

	/**
	 * Metodo para establecer el valor del campo cedtra
	 * @param string $cedtra
	 */
	public function setCedtra($cedtra){
		$this->cedtra = $cedtra;
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
	 * Metodo para establecer el valor del campo cabhog
	 * @param string $cabhog
	 */
	public function setCabhog($cabhog){
		$this->cabhog = $cabhog;
	}

	/**
	 * Metodo para establecer el valor del campo codciu
	 * @param string $codciu
	 */
	public function setCodciu($codciu){
		$this->codciu = $codciu;
	}

	/**
	 * Metodo para establecer el valor del campo codzon
	 * @param string $codzon
	 */
	public function setCodzon($codzon){
		$this->codzon = $codzon;
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
	 * @param integer $barrio
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
	 * Metodo para establecer el valor del campo fax
	 * @param string $fax
	 */
	public function setFax($fax){
		$this->fax = $fax;
	}

	/**
	 * Metodo para establecer el valor del campo email
	 * @param string $email
	 */
	public function setEmail($email){
		$this->email = $email;
	}

	/**
	 * Metodo para establecer el valor del campo fecing
	 * @param Date $fecing
	 */
	public function setFecing($fecing){
		$this->fecing = $fecing;
	}

	/**
	 * Metodo para establecer el valor del campo salario
	 * @param integer $salario
	 */
	public function setSalario($salario){
		$this->salario = $salario;
	}

	/**
	 * Metodo para establecer el valor del campo captra
	 * @param string $captra
	 */
	public function setCaptra($captra){
		$this->captra = $captra;
	}

	/**
	 * Metodo para establecer el valor del campo tipdis
	 * @param string $tipdis
	 */
	public function setTipdis($tipdis){
		$this->tipdis = $tipdis;
	}

	/**
	 * Metodo para establecer el valor del campo nivedu
	 * @param string $nivedu
	 */
	public function setNivedu($nivedu){
		$this->nivedu = $nivedu;
	}

	/**
	 * Metodo para establecer el valor del campo rural
	 * @param string $rural
	 */
	public function setRural($rural){
		$this->rural = $rural;
	}

	/**
	 * Metodo para establecer el valor del campo horas
	 * @param integer $horas
	 */
	public function setHoras($horas){
		$this->horas = $horas;
	}

	/**
	 * Metodo para establecer el valor del campo tipcon
	 * @param string $tipcon
	 */
	public function setTipcon($tipcon){
		$this->tipcon = $tipcon;
	}

	/**
	 * Metodo para establecer el valor del campo vivienda
	 * @param string $vivienda
	 */
	public function setVivienda($vivienda){
		$this->vivienda = $vivienda;
	}

	/**
	 * Metodo para establecer el valor del campo autoriza
	 * @param string $autoriza
	 */
	public function setAutoriza($autoriza){
		$this->autoriza = $autoriza;
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
	 * Metodo para establecer el valor del campo tipafi
	 * @param string $tipafi
	 */
	public function setTipafi($tipafi){
		$this->tipafi = $tipafi;
	}

	/**
	 * Metodo para establecer el valor del campo agencia
	 * @param integer $agencia
	 */
	public function setAgencia($agencia){
		$this->agencia = $agencia;
	}

	/**
	 * Metodo para establecer el valor del campo profesion
	 * @param string $profesion
	 */
	public function setProfesion($profesion){
		$this->profesion = $profesion;
	}

	/**
	 * Metodo para establecer el valor del campo cargo
	 * @param string $cargo
	 */
	public function setCargo($cargo){
		$this->cargo = $cargo;
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
	 * Devuelve el valor del campo nit
	 * @return string
	 */
	public function getNit(){
		return $this->nit;
	}

	/**
	 * Devuelve el valor del campo cedtra
	 * @return string
	 */
	public function getCedtra(){
		return $this->cedtra;
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
	 * Devuelve el valor del campo cabhog
	 * @return string
	 */
	public function getCabhog(){
		return $this->cabhog;
	}

	/**
	 * Devuelve el valor del campo codciu
	 * @return string
	 */
	public function getCodciu(){
		return $this->codciu;
	}

	/**
	 * Devuelve el valor del campo codzon
	 * @return string
	 */
	public function getCodzon(){
		return $this->codzon;
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
	 * @return integer
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
	 * Devuelve el valor del campo fax
	 * @return string
	 */
	public function getFax(){
		return $this->fax;
	}

	/**
	 * Devuelve el valor del campo email
	 * @return string
	 */
	public function getEmail(){
		return $this->email;
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
	 * Devuelve el valor del campo salario
	 * @return integer
	 */
	public function getSalario(){
		return $this->salario;
	}

	/**
	 * Devuelve el valor del campo captra
	 * @return string
	 */
	public function getCaptra(){
		return $this->captra;
	}

	/**
	 * Devuelve el valor del campo tipdis
	 * @return string
	 */
	public function getTipdis(){
		return $this->tipdis;
	}

	/**
	 * Devuelve el valor del campo nivedu
	 * @return string
	 */
	public function getNivedu(){
		return $this->nivedu;
	}

	/**
	 * Devuelve el valor del campo rural
	 * @return string
	 */
	public function getRural(){
		return $this->rural;
	}

	/**
	 * Devuelve el valor del campo horas
	 * @return integer
	 */
	public function getHoras(){
		return $this->horas;
	}

	/**
	 * Devuelve el valor del campo tipcon
	 * @return string
	 */
	public function getTipcon(){
		return $this->tipcon;
	}

	/**
	 * Devuelve el valor del campo vivienda
	 * @return string
	 */
	public function getVivienda(){
		return $this->vivienda;
	}

	/**
	 * Devuelve el valor del campo autoriza
	 * @return string
	 */
	public function getAutoriza(){
		return $this->autoriza;
	}

	/**
	 * Devuelve el valor del campo estado
	 * @return string
	 */
	public function getEstado(){
		return $this->estado;
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
	 * Devuelve el valor del campo tipafi
	 * @return string
	 */
	public function getTipafi(){
		return $this->tipafi;
	}

	/**
	 * Devuelve el valor del campo agencia
	 * @return integer
	 */
	public function getAgencia(){
		return $this->agencia;
	}

	/**
	 * Devuelve el valor del campo profesion
	 * @return string
	 */
	public function getProfesion(){
		return $this->profesion;
	}

	/**
	 * Devuelve el valor del campo cargo
	 * @return string
	 */
	public function getCargo(){
		return $this->cargo;
	}

	/**
	 * Validaciones y reglas de negocio
	 */
	protected function validation(){		
		$this->validate("InclusionIn", array(
			"field" => "cabhog",
			"domain" => array('S', 'N'),
			"required" => true
		));
		$this->validate("Email", array(
			"field" => "email",
			"required" => true
		));
		$this->validate("InclusionIn", array(
			"field" => "captra",
			"domain" => array('N', 'I'),
			"required" => true
		));
		$this->validate("InclusionIn", array(
			"field" => "rural",
			"domain" => array('S', 'N'),
			"required" => true
		));
		$this->validate("InclusionIn", array(
			"field" => "tipcon",
			"domain" => array('F', 'I'),
			"required" => true
		));
		$this->validate("InclusionIn", array(
			"field" => "autoriza",
			"domain" => array('S', 'N'),
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

}

