<?php

class Mercurio30 extends ActiveRecord {

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
	protected $razsoc;

	/**
	 * @var string
	 */
	protected $sigla;

	/**
	 * @var string
	 */
	protected $digver;

	/**
	 * @var string
	 */
	protected $coddoc;

	/**
	 * @var string
	 */
	protected $codciu;

	/**
	 * @var string
	 */
	protected $barrio;

	/**
	 * @var string
	 */
	protected $ciucor;

	/**
	 * @var string
	 */
	protected $barcor;

	/**
	 * @var string
	 */
	protected $direccion;

	/**
	 * @var string
	 */
	protected $telefono;

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
	 * @var string
	 */
	protected $pagweb;

	/**
	 * @var Date
	 */
	protected $feccon;

	/**
	 * @var integer
	 */
	protected $codact;

	/**
	 * @var string
	 */
	protected $objemp;

	/**
	 * @var integer
	 */
	protected $tottra;

	/**
	 * @var integer
	 */
	protected $totnom;

	/**
	 * @var string
	 */
	protected $cedrep;

	/**
	 * @var string
	 */
	protected $docrep;

	/**
	 * @var string
	 */
	protected $nomrep;

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
	 * @var string
	 */
	protected $tipsoc;

	/**
	 * @var string
	 */
	protected $calemp;

	/**
	 * @var string
	 */
	protected $codzon;

	/**
	 * @var string
	 */
	protected $zonsuc;

	/**
	 * @var string
	 */
	protected $dirsuc;

	/**
	 * @var string
	 */
	protected $emailsuc;

	/**
	 * @var string
	 */
	protected $telsuc;

	/**
	 * @var string
	 */
	protected $faxsuc;

	/**
	 * @var string
	 */
	protected $nomsub;

	/**
	 * @var integer
	 */
	protected $usuario;

	/**
	 * @var integer
	 */
	protected $agencia;

	/**
	 * @var string
	 */
	protected $docadm;

	/**
	 * @var string
	 */
	protected $cedadm;


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
	 * Metodo para establecer el valor del campo razsoc
	 * @param string $razsoc
	 */
	public function setRazsoc($razsoc){
		$this->razsoc = $razsoc;
	}

	/**
	 * Metodo para establecer el valor del campo sigla
	 * @param string $sigla
	 */
	public function setSigla($sigla){
		$this->sigla = $sigla;
	}

	/**
	 * Metodo para establecer el valor del campo digver
	 * @param string $digver
	 */
	public function setDigver($digver){
		$this->digver = $digver;
	}

	/**
	 * Metodo para establecer el valor del campo coddoc
	 * @param string $coddoc
	 */
	public function setCoddoc($coddoc){
		$this->coddoc = $coddoc;
	}

	/**
	 * Metodo para establecer el valor del campo codciu
	 * @param string $codciu
	 */
	public function setCodciu($codciu){
		$this->codciu = $codciu;
	}

	/**
	 * Metodo para establecer el valor del campo barrio
	 * @param string $barrio
	 */
	public function setBarrio($barrio){
		$this->barrio = $barrio;
	}

	/**
	 * Metodo para establecer el valor del campo ciucor
	 * @param string $ciucor
	 */
	public function setCiucor($ciucor){
		$this->ciucor = $ciucor;
	}

	/**
	 * Metodo para establecer el valor del campo barcor
	 * @param string $barcor
	 */
	public function setBarcor($barcor){
		$this->barcor = $barcor;
	}

	/**
	 * Metodo para establecer el valor del campo direccion
	 * @param string $direccion
	 */
	public function setDireccion($direccion){
		$this->direccion = $direccion;
	}

	/**
	 * Metodo para establecer el valor del campo telefono
	 * @param string $telefono
	 */
	public function setTelefono($telefono){
		$this->telefono = $telefono;
	}

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
	 * Metodo para establecer el valor del campo pagweb
	 * @param string $pagweb
	 */
	public function setPagweb($pagweb){
		$this->pagweb = $pagweb;
	}

	/**
	 * Metodo para establecer el valor del campo feccon
	 * @param Date $feccon
	 */
	public function setFeccon($feccon){
		$this->feccon = $feccon;
	}

	/**
	 * Metodo para establecer el valor del campo codact
	 * @param integer $codact
	 */
	public function setCodact($codact){
		$this->codact = $codact;
	}

	/**
	 * Metodo para establecer el valor del campo objemp
	 * @param string $objemp
	 */
	public function setObjemp($objemp){
		$this->objemp = $objemp;
	}

	/**
	 * Metodo para establecer el valor del campo tottra
	 * @param integer $tottra
	 */
	public function setTottra($tottra){
		$this->tottra = $tottra;
	}

	/**
	 * Metodo para establecer el valor del campo totnom
	 * @param integer $totnom
	 */
	public function setTotnom($totnom){
		$this->totnom = $totnom;
	}

	/**
	 * Metodo para establecer el valor del campo cedrep
	 * @param string $cedrep
	 */
	public function setCedrep($cedrep){
		$this->cedrep = $cedrep;
	}

	/**
	 * Metodo para establecer el valor del campo docrep
	 * @param string $docrep
	 */
	public function setDocrep($docrep){
		$this->docrep = $docrep;
	}

	/**
	 * Metodo para establecer el valor del campo nomrep
	 * @param string $nomrep
	 */
	public function setNomrep($nomrep){
		$this->nomrep = $nomrep;
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
	 * Metodo para establecer el valor del campo tipsoc
	 * @param string $tipsoc
	 */
	public function setTipsoc($tipsoc){
		$this->tipsoc = $tipsoc;
	}

	/**
	 * Metodo para establecer el valor del campo calemp
	 * @param string $calemp
	 */
	public function setCalemp($calemp){
		$this->calemp = $calemp;
	}

	/**
	 * Metodo para establecer el valor del campo codzon
	 * @param string $codzon
	 */
	public function setCodzon($codzon){
		$this->codzon = $codzon;
	}

	/**
	 * Metodo para establecer el valor del campo zonsuc
	 * @param string $zonsuc
	 */
	public function setZonsuc($zonsuc){
		$this->zonsuc = $zonsuc;
	}

	/**
	 * Metodo para establecer el valor del campo dirsuc
	 * @param string $dirsuc
	 */
	public function setDirsuc($dirsuc){
		$this->dirsuc = $dirsuc;
	}

	/**
	 * Metodo para establecer el valor del campo emailsuc
	 * @param string $emailsuc
	 */
	public function setEmailsuc($emailsuc){
		$this->emailsuc = $emailsuc;
	}

	/**
	 * Metodo para establecer el valor del campo telsuc
	 * @param string $telsuc
	 */
	public function setTelsuc($telsuc){
		$this->telsuc = $telsuc;
	}

	/**
	 * Metodo para establecer el valor del campo faxsuc
	 * @param string $faxsuc
	 */
	public function setFaxsuc($faxsuc){
		$this->faxsuc = $faxsuc;
	}

	/**
	 * Metodo para establecer el valor del campo nomsub
	 * @param string $nomsub
	 */
	public function setNomsub($nomsub){
		$this->nomsub = $nomsub;
	}

	/**
	 * Metodo para establecer el valor del campo usuario
	 * @param integer $usuario
	 */
	public function setUsuario($usuario){
		$this->usuario = $usuario;
	}

	/**
	 * Metodo para establecer el valor del campo agencia
	 * @param integer $agencia
	 */
	public function setAgencia($agencia){
		$this->agencia = $agencia;
	}

	/**
	 * Metodo para establecer el valor del campo docadm
	 * @param string $docadm
	 */
	public function setDocadm($docadm){
		$this->docadm = $docadm;
	}

	/**
	 * Metodo para establecer el valor del campo cedadm
	 * @param string $cedadm
	 */
	public function setCedadm($cedadm){
		$this->cedadm = $cedadm;
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
	 * Devuelve el valor del campo razsoc
	 * @return string
	 */
	public function getRazsoc(){
		return $this->razsoc;
	}

	/**
	 * Devuelve el valor del campo sigla
	 * @return string
	 */
	public function getSigla(){
		return $this->sigla;
	}

	/**
	 * Devuelve el valor del campo digver
	 * @return string
	 */
	public function getDigver(){
		return $this->digver;
	}

	/**
	 * Devuelve el valor del campo coddoc
	 * @return string
	 */
	public function getCoddoc(){
		return $this->coddoc;
	}

	/**
	 * Devuelve el valor del campo codciu
	 * @return string
	 */
	public function getCodciu(){
		return $this->codciu;
	}

	/**
	 * Devuelve el valor del campo barrio
	 * @return string
	 */
	public function getBarrio(){
		return $this->barrio;
	}

	/**
	 * Devuelve el valor del campo ciucor
	 * @return string
	 */
	public function getCiucor(){
		return $this->ciucor;
	}

	/**
	 * Devuelve el valor del campo barcor
	 * @return string
	 */
	public function getBarcor(){
		return $this->barcor;
	}

	/**
	 * Devuelve el valor del campo direccion
	 * @return string
	 */
	public function getDireccion(){
		return $this->direccion;
	}

	/**
	 * Devuelve el valor del campo telefono
	 * @return string
	 */
	public function getTelefono(){
		return $this->telefono;
	}

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
	 * Devuelve el valor del campo pagweb
	 * @return string
	 */
	public function getPagweb(){
		return $this->pagweb;
	}

	/**
	 * Devuelve el valor del campo feccon
	 * @return Date
	 */
	public function getFeccon(){
		if($this->feccon){
			return new Date($this->feccon);
		} else {
			return null;
		}
	}

	/**
	 * Devuelve el valor del campo codact
	 * @return integer
	 */
	public function getCodact(){
		return $this->codact;
	}

	/**
	 * Devuelve el valor del campo objemp
	 * @return string
	 */
	public function getObjemp(){
		return $this->objemp;
	}

	/**
	 * Devuelve el valor del campo tottra
	 * @return integer
	 */
	public function getTottra(){
		return $this->tottra;
	}

	/**
	 * Devuelve el valor del campo totnom
	 * @return integer
	 */
	public function getTotnom(){
		return $this->totnom;
	}

	/**
	 * Devuelve el valor del campo cedrep
	 * @return string
	 */
	public function getCedrep(){
		return $this->cedrep;
	}

	/**
	 * Devuelve el valor del campo docrep
	 * @return string
	 */
	public function getDocrep(){
		return $this->docrep;
	}

	/**
	 * Devuelve el valor del campo nomrep
	 * @return string
	 */
	public function getNomrep(){
		return $this->nomrep;
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
	 * Devuelve el valor del campo tipsoc
	 * @return string
	 */
	public function getTipsoc(){
		return $this->tipsoc;
	}

	/**
	 * Devuelve el valor del campo calemp
	 * @return string
	 */
	public function getCalemp(){
		return $this->calemp;
	}

	/**
	 * Devuelve el valor del campo codzon
	 * @return string
	 */
	public function getCodzon(){
		return $this->codzon;
	}

	/**
	 * Devuelve el valor del campo zonsuc
	 * @return string
	 */
	public function getZonsuc(){
		return $this->zonsuc;
	}

	/**
	 * Devuelve el valor del campo dirsuc
	 * @return string
	 */
	public function getDirsuc(){
		return $this->dirsuc;
	}

	/**
	 * Devuelve el valor del campo emailsuc
	 * @return string
	 */
	public function getEmailsuc(){
		return $this->emailsuc;
	}

	/**
	 * Devuelve el valor del campo telsuc
	 * @return string
	 */
	public function getTelsuc(){
		return $this->telsuc;
	}

	/**
	 * Devuelve el valor del campo faxsuc
	 * @return string
	 */
	public function getFaxsuc(){
		return $this->faxsuc;
	}

	/**
	 * Devuelve el valor del campo nomsub
	 * @return string
	 */
	public function getNomsub(){
		return $this->nomsub;
	}

	/**
	 * Devuelve el valor del campo usuario
	 * @return integer
	 */
	public function getUsuario(){
		return $this->usuario;
	}

	/**
	 * Devuelve el valor del campo agencia
	 * @return integer
	 */
	public function getAgencia(){
		return $this->agencia;
	}

	/**
	 * Devuelve el valor del campo docadm
	 * @return string
	 */
	public function getDocadm(){
		return $this->docadm;
	}

	/**
	 * Devuelve el valor del campo cedadm
	 * @return string
	 */
	public function getCedadm(){
		return $this->cedadm;
	}

	/**
	 * Validaciones y reglas de negocio
	 */
	protected function validation(){		
		$this->validate("Email", array(
			"field" => "email",
			"required" => true
		));
		$this->validate("InclusionIn", array(
			"field" => "estado",
			"domain" => array('P', 'A', 'X'),
			"required" => true
		));
		$this->validate("InclusionIn", array(
			"field" => "calemp",
			"domain" => array('E', 'F', 'P', 'D', 'N'),
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

