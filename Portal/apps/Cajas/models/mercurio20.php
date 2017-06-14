<?php

class Mercurio20 extends ActiveRecord {

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
        return array('conemptra_view'=>'CONSULTA TRABAJADORES','conemptra_view'=>'INGRESO A CONSULTA TRABAJADORES','confor_view'=>'CONSULTA FORMULARIO',
                    'consultaParticular'=>'ESTADOS DE AFILIACION','consultaParticular'=>'CONSULTE SUS SOLICITUDES',
                    'consultaPostulaciones_view'=>'CONSULTA DE POSTULACIONES','consultaProyectos_view'=>'CONSULTA DE PROYECTOS',
                    'formulario_view'=>'DESCARGA FORMULARIO','formulario_view'=>'DESCARGA FORMULARIO','formulario_view'=>'DESCARGA FORMULARIO',
                    'giro_view'=>'INGRESO A CONSULTA GIRO','giroEmp_view'=>'CONSULTA GIRO','nogiro_view'=>'CONSULTA NO GIRO',
                    'giroEmp_view'=>'CONSULTA GIRO','giroEmp_view'=>'CONSULTA GIRO','giro_view'=>'CONSULTA GIRO',
                    'nogiro_view'=>'CONSULTA NO GIRO','guiaDilig_report_view'=>'GUIA PARA EL DILIGENCIAMIENTO INSCRIPCION',
                    'guiaDilig_report_view'=>'GUIA PARA EL DILIGENCIAMIENTO INSCRIPCION','historicoTarjeta_view'=>'HISTORICO DE TARJETAS',
                    'hogActualizado_report_view'=>'CONFORMACION DE NUEVO HOGAR','hogActualizado_report_view'=>'CONFORMACION DE NUEVO HOGAR',
                    'ingben_view'=>'INGRESO BENEFICIARIO','ingben_view'=>'INGRESO BENEFICIARIO','ingcon_view'=>'INGRESO CONYUGE',
                    'kitEscolar_view'=>'CONSULTA SUBSIDIO ESCOLAR','morapresunta_view'=>'CONSULTA MORA PRESUNTA',
                    'moremp_view'=>'CONSULTA MORA REAL','morpretra_view'=>'CONSULTA DE MORA POR TRABAJADORES',
                    'morpretra_view'=>'CONSULTA DE MORA POR TRABAJADORES','movilizacion_report_view'=>'SOLICITUD MOVILIZACION AHORRO PREVIO',
                    'movilizacion_report_view'=>'SOLICITUD MOVILIZACION AHORRO PREVIO','noAfiliado_view'=>'CONSTANCIA DE NO AFILIADO',
                    'nogiro_view'=>'CONSULTA NO GIRO','nogiro_view'=>'CONSULTA NO GIRO','nomina_view'=>'CONSULTA NOMINA',
                    'nomina_view'=>'CONSULTA NOMINA','novret_view'=>'NOVEDADES DE RETIRO TRABAJADOR',
                    'novret_view'=>'NOVEDADES DE RETIRO TRABAJADOR','nucfam_view'=>'CONSULTA NUCLEO FAMILIAR',
                    'planillaTra_view'=>'CONSULTA DE PLANILLA','planillaTra_view'=>'CONSULTA DE PLANILLA',
                    'saldoTarjeta_view'=>'CONSULTA MOVIMIENTO TARJETA','saldoTarjeta_view'=>'CONSULTA MOVIMIENTO TARJETA',
                    'addempresa_report'=>'Formulario afiliacion empresa','addindependiente '=>'afiliacion independiente',
                    'addpensionado'=>'afiliacion pensionado','adjunto'=>'traer archivos adjuntos',
                    'adjuntoben '=>'traer archivos adjuntos beneficiario','adjuntoT '=>'traer archivos adjuntos trabajador',
                    'aportes'=>'consulta aportes','autenticar '=>'Ingreso al modulo','barrio_filtraos'=>'Traer Barrios',
                    'beforeFirst'=>'Registro','beforeFirst_view '=>'Primer Ingreso','calcularPreven '=>'Calcular Precio de Venta',
                    'cambiarClave '=>'Cambiar Clave','cambiarClavePri'=>'Cambiar Clave','cambiarDireccion '=>'Cambiar Direccion',
                    'cambioClave_view '=>'Cambiar clave vista','cambioPreguntas'=>'cambiar preguntar',
                    'cambioPreguntas_view '=>'vista cambiar preguntas','cargueCertificados '=>'anexo de certificados',
                    'certAsig_report'=>'Certificado de asignacion vivienda','certificadoAfiliacionEmpresa '=>'certificado de afiliacion empresa',
                    'certPostAct_report '=>'certificado de postulacion','claveVencida '=>'cambiar clave por vencimiento',
                    'claveVencida_view'=>'cambiar clave por vencimiento vista','ClsSesion'=>'Cerrar Session ',
                    'comprobarDocumento '=>'Comprobar Documento','conemptra'=>'Consulta de Trabajadores',
                    'conemptra_rep'=>'consulta de Trabajadores reporte','contestar'=>'Enviar respuestas al correo',
                    'dattra '=>'Traer Datos del trabajador','digver '=>'','envioCarta '=>'Enviar carta al correo',
                    'envioCarta_view'=>'Vista enviar Carta al correo','fecret '=>'Fecha de retiro',
                    'formuAfiTrab '=>'Formulario de Afiliacion de trabajador','generarCertificados'=>'Generar Certificados',
                    'generarCertificadosEmpresa '=>'Generar Certificados empresa','giro '=>'Consulta de giro',
                    'giroEmp'=>'Consulta de Giro empresa','guiaDilig_report '=>'guia de diligenciamiento',
                    'hogActualizado_report'=>'Reporte de hogar Actualizado','img'=>'','index'=>'Vista principal del modulo',
                    'informacion_view '=>'Vista de Informacion','ingben '=>'Ingreso Beneficiario','listado_view '=>'Listado',
                    'moremp_report'=>'Reporte de Mora de Empresa PDF','moremp_repxls'=>'Reporte de Mora de Empresa Excel',
                    'morepresunta_report'=>'Reporte de mora Presunta PDF','morepresunta_repxls'=>'Reporte de mora Presunta Excel',
                    'morpretra'=>'Consulta de mora presunta por trabajador','morpretra_report '=>'Reporte de mora presunta por trabajador PDF',
                    'morpretra_repxls '=>'Reporte de mora preusnta por trabajador Excel','morpretra_report '=>'Reporte de mora presunta por trabajador PDF',
                    'morpretra_repxls '=>'Reporte de mora preusnta por trabajador Excel','movilizacion_report'=>'Reporte de movilizacion',
                    'movilizacion_reporte '=>'REPORTE DE MOVILIZACION','noAfiliado '=>'nO AFILIADO','nogiro '=>'CONSULTA DE NO GIRO',
                    'nomina '=>'CONSULTA DE NOMINA','novret '=>'DATOS DE TRABAJADOR A RETIRAR','planillaTra'=>'CONSULTA DE PLANILLA POR TRABAJADOR',
                    'preguntas'=>'VERIFICAR PREGUNTAS DE SEGURIDAD','preguntas_view '=>'VISTA DE PREGUNTAS DE SEGURIDAD',
                    'primera_view '=>'PRIMER INGRESO','reporteConfor'=>'CONSULTA DE FORMULARIOS ','respuestas '=>'VERIFICAR RESPUESTAS DE SEGURIDAD',
                    'retirar'=>'RETIRAR TRABAJADOR','saldoTarjeta '=>'SALDO DE TARJETA','salir'=>'SALIR DEL MODULO',
                    'traerBarrio'=>'TRAER BARRIOS','traerCiudad'=>'TRAER CIUDADES','traerDepartamento'=>'TRAER DEPARTAMENTOS',
                    'verfiComper'=>'REVISION DE COMPANERO PERMANENTE','verfiConper'=>'REVISION DE COMPANERO PERMANENTE',
                    'verificarIp'=>'REVISION DE DIRECCION IP','verificarIp_view '=>'VISTA DE REVISION DE DIRECCION IP',
                    'vigenciaRegistro '=>'VIGENCIA DE REGISTRO','vigenciaRegistros'=>'VIGENCIA DE REGISTROS',
                    'vigenciaSubsidioVivienda '=>'VIGENCIA DE SUBSIDIO DE VIVIENDA','actividad'=>"CONSULTA ACTIVIDADES ECONOMICAS", 
                    'adjuntoCam'=>'ADJUNTAR ARCHIVOS EMPRESA','adjuntocon'=>"ADJUNTAR ARCHIVOS CONYUGE" );
    }

    public function getAccionDetalle(){
        $retorno="";
        switch(trim($this->accion)){
            case 'actdat_view': $retorno='ACTUALIZACION DATOS BASICOS'; break;
            case 'addempresa_view': $retorno='INGRESO A REGISTRO COMO EMPRESA'; break;
            case 'addtrabajador_view': $retorno='INGRESO A REGISTRO COMO TRABAJADORES'; break;
            case 'aportes_view': $retorno='INGRESO CONSULTA APORTES'; break;
            case 'simulador_view': $retorno='SIMULADOR'; break;
            case 'concre_view': $retorno='CONSULTA DE CREDITOS'; break;
            case 'formulario_view': $retorno='DESCARGA FORMULARIO'; break;
            case 'conser_view': $retorno='CONSULTA SERVICIOS'; break;
            case 'nucfam_view': $retorno='CONSULTA NUCLEO FAMILIAR'; break;
            case 'certificadoAfiliacionEmpresa_view': $retorno='CERTIFICADO AFILIACION'; break;
            case 'precer_view': $retorno='PRESENTACION CONSTANCIA'; break;
            case 'actdat_view': $retorno='ACTUALIZACION DATOS BASICOS'; break;
            case 'conemptra_view': $retorno='INGRESO A CONSULTA TRABAJADORES'; break;
            case 'kitEscolar_view': $retorno='CONSULTA SUBSIDIO ESCOLAR'; break;
            case 'kitEscolarEmp_view': $retorno='CONSULTA KIT'; break;
            case 'addempresa_view': $retorno='INGRESO COMO EMPRESA'; break;
            case 'ingcon_view': $retorno='INGRESO CONYUGE'; break;
            case 'actdat_view': $retorno='ACTUALIZA DATOS BASICOS'; break;
            case 'consultaParticular': $retorno='ESTADOS DE AFILIACION'; break;
            case 'addtrabajador_view': $retorno='AFILIACION DE TRABAJADORES'; break;
            case 'noAfiliado_view': $retorno='INGRESO A CONSTANCIA DE NO AFILIADO'; break;
            case 'certificadoAdd_view': $retorno='CERTIFICADOS ADICIONADOS'; break;
            case 'historicoTarjeta_view': $retorno='HISTORICO DE TARJETAS'; break;
            case 'certificadoAfiliacion_view': $retorno='CERTIFICADOS'; break;
            case 'certificadoAfiliacionEmpresa_view': $retorno='CERTIFICADOS'; break;
            case 'cargueCertificados_view': $retorno='ANEXO DE CERTIFICADOS'; break;
            case 'cambioDatosPrincipales_view': $retorno='CAMBIO DATOS PRINCIPALES'; break;
            case 'consultaParticular': $retorno='CONSULTE SUS SOLICITUDES'; break;
            case 'convenios_view': $retorno='CONSULTA CONVENIOS'; break;
            case 'confor_view': $retorno='CONSULTA FORMULARIO'; break;
            case 'certPostAct_report_view': $retorno='CERTIFICADO DE POSTULACION ACTIVA'; break;
            case 'certAsig_report_view': $retorno='CERTIFICADO DE ASIGNACION'; break;
            case 'consultaPostulaciones_view': $retorno='CONSULTA DE POSTULACIONES'; break;
            case 'hogActualizado_report_view': $retorno='CONFORMACION DE NUEVO HOGAR'; break;
            case 'movilizacion_report_view': $retorno='SOLICITUD MOVILIZACION AHORRO PREVIO'; break;
            case 'consultaProyectos_view': $retorno='CONSULTA DE PROYECTOS'; break;
            case 'cambioDatosPrincipales_view': $retorno='CAMBIO DATOS PRINCIPALES'; break;
            case 'cargueCertificados_view': $retorno='ANEXO DE CERTIFICADOS'; break;
            case 'certAsig_report_view': $retorno='CERTIFICADO DE ASIGNACION'; break;
            case 'certificadoAfiliacionEmpresa_view': $retorno='CERTIFICADOS'; break;
            case 'certificadoAfiliacion_view': $retorno='CERTIFICADOS'; break;
            case 'certPostAct_report_view': $retorno='CERTIFICADO DE POSTULACION ACTIVA'; break;
            case 'conemptra_view': $retorno='INGRESO A CONSULTA TRABAJADORES'; break;
            case 'confor_view': $retorno='CONSULTA FORMULARIO'; break;
            case 'consultaParticular': $retorno='ESTADOS DE AFILIACION'; break;
            case 'consultaParticular': $retorno='CONSULTE SUS SOLICITUDES'; break;
            case 'consultaPostulaciones_view': $retorno='CONSULTA DE POSTULACIONES'; break;
            case 'consultaProyectos_view': $retorno='CONSULTA DE PROYECTOS'; break;
            case 'formulario_view': $retorno='DESCARGA FORMULARIO'; break;
            case 'giro_view': $retorno='INGRESO A CONSULTA GIRO TRABAJADOR'; break;
            case 'giroEmp_view': $retorno='INGRESO A CONSULTA GIRO EMPRESA'; break;
            case 'nogiro_view': $retorno='INGRESO A CONSULTA NO GIRO'; break;
            case 'giroEmp_view': $retorno='INGRESO A CONSULTA GIRO EMPRESA'; break;
            case 'giro_view': $retorno='INGRESO A CONSULTA GIRO TRABAJADOR'; break;
            case 'nogiro_view': $retorno='INGRESO A CONSULTA NO GIRO'; break;
            case 'guiaDilig_report_view': $retorno='GUIA PARA EL DILIGENCIAMIENTO INSCRIPCION'; break;
            case 'historicoTarjeta_view': $retorno='HISTORICO DE TARJETAS'; break;
            case 'hogActualizado_report_view': $retorno='CONFORMACION DE NUEVO HOGAR'; break;
            case 'ingben_view': $retorno='INGRESO BENEFICIARIO'; break;
            case 'ingcon_view': $retorno='INGRESO CONYUGE'; break;
            case 'kitEscolar_view': $retorno='CONSULTA SUBSIDIO ESCOLAR'; break;
            case 'morapresunta_view': $retorno='CONSULTA MORA PRESUNTA'; break;
            case 'moremp_view': $retorno='CONSULTA MORA REAL'; break;
            case 'morpretra_view': $retorno='CONSULTA DE MORA POR TRABAJADORES'; break;
            case 'movilizacion_report_view': $retorno='SOLICITUD MOVILIZACION AHORRO PREVIO'; break;
            case 'noAfiliado_view': $retorno='INGRSO A CONSTANCIA DE NO AFILIADO'; break;
            case 'nogiro_view': $retorno='INGRESO A CONSULTA NO GIRO'; break;
            case 'nomina_view': $retorno='INGRESO A CONSULTA NOMINA'; break;
            case 'novret_view': $retorno='NOVEDADES DE RETIRO TRABAJADOR'; break;
            case 'nucfam_view': $retorno='CONSULTA NUCLEO FAMILIAR'; break;
            case 'planillaTra_view': $retorno='CONSULTA DE PLANILLA'; break;
            case 'saldoTarjeta_view': $retorno='CONSULTA MOVIMIENTO TARJETA'; break;
            case 'addempresa_report': $retorno='FORMULARIO AFILIACION EMPRESA'; break; 
            case 'addindependiente': $retorno='AFILIACION INDEPENDIENTE'; break; 
            case 'addpensionado': $retorno='AFILIACION PENSIONADO'; break; 
            case 'adjunto': $retorno='TRAER ARCHIVOS ADJUNTOS'; break; 
            case 'adjuntoben': $retorno='TRAER ARCHIVOS ADJUNTOS BENEFICIARIO'; break; 
            case 'adjuntoT': $retorno='TRAER ARCHIVOS ADJUNTOS TRABAJADOR'; break; 
            case 'aportes': $retorno='CONSULTA APORTES'; break; 
            case 'autenticar': $retorno='INGRESO AL MODULO'; break; 
            case 'barrio_filtraos': $retorno='TRAER BARRIOS'; break; 
            case 'beforeFirst': $retorno='REGISTRO'; break; 
            case 'beforeFirst_view': $retorno='PRIMER INGRESO'; break; 
            case 'calcularPreven': $retorno='CALCULAR PRECIO DE VENTA'; break; 
            case 'cambiarClave': $retorno='CAMBIAR CLAVE'; break; 
            case 'cambiarClavePri': $retorno='CAMBIAR CLAVE'; break; 
            case 'cambiarDireccion': $retorno='CAMBIAR DIRECCION'; break; 
            case 'cambioClave_view': $retorno='CAMBIAR CLAVE VISTA'; break; 
            case 'cambioPreguntas': $retorno='CAMBIAR PREGUNTAR'; break; 
            case 'cambioPreguntas_view': $retorno='VISTA CAMBIAR PREGUNTAS'; break; 
            case 'cargueCertificados': $retorno='ANEXO DE CERTIFICADOS'; break; 
            case 'certAsig_report': $retorno='CERTIFICADO DE ASIGNACION VIVIENDA'; break; 
            case 'certificadoAfiliacionEmpresa': $retorno='CERTIFICADO DE AFILIACION EMPRESA'; break; 
            case 'certPostAct_report': $retorno='CERTIFICADO DE POSTULACION'; break; 
            case 'claveVencida': $retorno='CAMBIAR CLAVE POR VENCIMIENTO'; break; 
            case 'claveVencida_view': $retorno='CAMBIAR CLAVE POR VENCIMIENTO VISTA'; break; 
            case 'ClsSesion': $retorno='CERRAR sESSION '; break; 
            case 'comprobarDocumento': $retorno='COMPROBAR dOCUMENTO'; break; 
            case 'conemptra': $retorno='CONSULTA DE tRABAJADORES'; break; 
            case 'conemptra_rep': $retorno='CONSULTA DE tRABAJADORES REPORTE'; break; 
            case 'contestar': $retorno='ENVIAR RESPUESTAS AL CORREO'; break; 
            case 'dattra': $retorno='TRAER dATOS DEL TRABAJADOR'; break; 
            case 'digver': $retorno=''; break; 
            case 'envioCarta': $retorno='ENVIAR CARTA AL CORREO'; break; 
            case 'envioCarta_view': $retorno='VISTA ENVIAR CARTA AL CORREO'; break; 
            case 'fecret': $retorno='FECHA DE RETIRO'; break; 
            case 'formuAfiTrab': $retorno='FORMULARIO DE AFILIACION DE TRABAJADOR'; break; 
            case 'generarCertificados': $retorno='GENERAR cERTIFICADOS'; break; 
            case 'generarCertificadosEmpresa': $retorno='GENERAR CERTIFICADOS EMPRESA'; break; 
            case 'giro': $retorno='CONSULTA DE GIRO'; break; 
            case 'giroEmp': $retorno='CONSULTA DE GIRO EMPRESA'; break; 
            case 'guiaDilig_report': $retorno='GUIA DE DILIGENCIAMIENTO'; break; 
            case 'hogActualizado_report': $retorno='REPORTE DE HOGAR ACTUALIZADO'; break; 
            case 'img': $retorno=''; break; 
            case 'index': $retorno='VISTA PRINCIPAL DEL MODULO'; break; 
            case 'informacion_view': $retorno='VISTA DE iNFORMACION'; break; 
            case 'ingben': $retorno='INGRESO BENEFICIARIO'; break; 
            case 'listado_view': $retorno='LISTADO'; break; 
            case 'moremp_report': $retorno='REPORTE DE MORA DE EMPRESA PDF'; break; 
            case 'moremp_repxls': $retorno='REPORTE de MORA DE EMPRESA Excel'; break; 
            case 'morepresunta_report': $retorno='Reporte de mora Presunta PDF'; break; 
            case 'morepresunta_repxls': $retorno='Reporte de mora Presunta Excel'; break; 
            case 'morpretra': $retorno='Consulta de mora presunta por trabajador'; break; 
            case 'morpretra_report': $retorno='Reporte de mora presunta por trabajador PDF'; break; 
            case 'morpretra_repxls': $retorno='Reporte de mora preusnta por trabajador Excel'; break; 
            case 'morpretra_report': $retorno='Reporte de mora presunta por trabajador PDF'; break; 
            case 'morpretra_repxls': $retorno='Reporte de mora preusnta por trabajador Excel'; break; 
            case 'movilizacion_report': $retorno='Reporte de movilizacion'; break; 
            case 'movilizacion_reporte': $retorno='Reporte de movilizacion'; break; 
            case 'noAfiliado': $retorno='DESCARGA DE CONSTANCIA DE NO AFILIADO'; break; 
            case 'nogiro': $retorno='consulta de no giro'; break; 
            case 'nomina': $retorno='Consulta de nomina'; break; 
            case 'novret': $retorno='Datos de trabajador a retirar'; break; 
            case 'planillaTra': $retorno='consulta de planilla por trabajador'; break; 
            case 'preguntas': $retorno='Verificar preguntas de seguridad'; break; 
            case 'preguntas_view': $retorno='Vista de preguntas de seguridad'; break; 
            case 'primera_view': $retorno='Primer ingreso'; break; 
            case 'reporteConfor': $retorno='Reporte '; break; 
            case 'respuestas': $retorno='Verificar respuestas de seguridad'; break; 
            case 'retirar': $retorno='Retirar Trabajador'; break; 
            case 'saldoTarjeta': $retorno='Saldo de Tarjeta'; break; 
            case 'salir': $retorno='Salir del Modulo'; break; 
            case 'traerBarrio': $retorno='Traer barrios'; break; 
            case 'traerCiudad': $retorno='Traer ciudades'; break; 
            case 'traerDepartamento': $retorno='Traer departamentos'; break; 
            case 'verfiComper': $retorno='Revision de companero permanente'; break; 
            case 'verfiConper': $retorno='Revision de companero permanente'; break; 
            case 'verfiConper': $retorno='Revision de companero permanente'; break; 
            case 'verificarIp': $retorno='Revision de Direccion Ip'; break; 
            case 'verificarIp_view': $retorno='Vista de Revision de Direccion Ip'; break; 
            case 'vigenciaRegistro': $retorno='Vigencia de Registro'; break; 
            case 'vigenciaRegistros': $retorno='Vigencia de Registros'; break; 
            case 'vigenciaSubsidioVivienda': $retorno='Vigencia de Subsidio de Vivienda'; break; 
            case 'actividad': $retorno='CONSULTA ACTIVIDADES ECONOMICAS'; break; 
            case 'adjuntoCam': $retorno='ADJUNTAR ARCHIVOS EMPRESA'; break; 
            case 'adjuntocon': $retorno='ADJUNTAR ARCHIVOS CONYUGE'; break; 
            case 'certLeg_view': $retorno='CERTIFICADO LEGALIZACION'; break; 
            case 'certLeg': $retorno='DESCARGA CERTIFICADO LEGALIZACION'; break; 
            case 'certProrroga': $retorno='DESCARGA CERTIFICADO PRORROGA'; break; 
            case 'certProrroga_view': $retorno='INGRESO A CERTIFICADO PRORROGA'; break; 
            case 'dattracon': $retorno='CONSULTA DE DATOS TRABAJADOR'; break; 
            case 'preguntas': $retorno='CONSULTA DE RESPUESTAS DE SEGURIDAD'; break; 
            case 'sesion': $retorno='VALIDACION DE VARIBLE DE SESION'; break; 
            case 'traerBarrioZon': $retorno='CONSULTA DE BARRIOS POR ZONA'; break; 
            case 'traerZona': $retorno='CONSULTA DE ZONAS'; break; 
            case 'valcon': $retorno='VALIDACION DE RECAION CON CONYUGE'; break; 
            case 'validaBeneficiarios': $retorno='VALIDACION DE RECAION CON BENEFICIARIO'; break; 
            case 'valpadbio': $retorno='VALIDACION DE CONVIVENCIA '; break; 
            case 'reportAbuso': $retorno='ENVIO DE CORREO POR ABUSO'; break; 
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

