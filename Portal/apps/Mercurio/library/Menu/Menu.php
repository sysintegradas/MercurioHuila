<?php

class Menu {

    public static $_menu = array(
            "1"=> array("title"=>"Datos Basicos",
                array(
                    'title' => 'Tipos Afiliado',
                    'default' => 'mercurio06',
                    'type' => 'detail',
                    'help'=>"<div class='help-title'>Tipos de Afiliado</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Son los tipos de Terceros o Afiliados que van a tener acceso al programa</div>
                            <div class='help-title-info'>Datos Importante</div>
                            <div class='help-info'><b>Tipo </b> sirve para saber cual es el tipo de terceros.</div>
                            <div class='help-info'><b>Detalle</b> sirve para saber cual es la descripcion.</div>"
                ),
                array(
                    'title' => 'Marcas para Firmas',
                    'default' => 'mercurio03',
                    'type' => 'detail',
                    'help'=>"<div class='help-title'>Marcas para Firma</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Son Marcas para identificar una firma para los reportes</div>
                            <div class='help-title-info'>Datos Importante</div>
                            <div class='help-info'><b>Firma </b> sirve para saber cual es el codigo Firma.</div>
                            <div class='help-info'><b>Detalle</b> sirve para saber cual es la descripcion.</div>"
                ),
                array(
                    'title' => 'Documentos',
                    'default' => 'mercurio12',
                    'type' => 'detail',
                    'help'=>"<div class='help-title'>Documentos para Entregar</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Son TODOS los documentos que pide la Caja para sus diferentes tramites</div>
                            <div class='help-title-info'>Datos Importante</div>
                            <div class='help-info'><b>Codigo </b> sirve para saber cual es el codigo del Documento.</div>
                            <div class='help-info'><b>Detalle</b> sirve para saber cual es la descripcion.</div>"
                ),
                array(
                    'title' => 'Areas',
                    'default' => 'mercurio08',
                    'type' => 'detail',
                    'help'=>"<div class='help-title'>Areas/Modulos</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Son las areas de las Cajas que van a tener algun tramite. </div>
                            <div class='help-title-info'>Datos Importante</div>
                            <div class='help-info'><b>Codigo </b> sirve para saber cual es el codigo del area.</div>
                            <div class='help-info'><b>Detalle</b> sirve para saber cual es la descripcion.</div>
                            <div class='help-info'><b>Archivo</b> sirve para saber cual es la imagen del area/modulo.</div>"
                ),
                array(
                    'title' => 'Operaciones',
                    'default' => 'mercurio11',
                    'type' => 'detail',
                    'help'=>"<div class='help-title'>Operaciones</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Son las Operaciones o Tramites que puede realizar un afiliado. </div>
                            <div class='help-title-info'>Datos Importante</div>
                            <div class='help-info'><b>Tipo </b> sirve para saber cual es el tipo de afiliado que realiza ese Operacion/Tramite del area.</div>"
                ),
            ),
            "2"=> array("title"=>"Configuracion",
                array(
                    'title' => 'Caja de Compensacion',
                    'type' => 'master',
                    'default' => 'mercurio02',
					'help'=>"<div class='help-title'>Caja de Compensacion</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Son los datos basicos de la Caja de Compensacion</div>
                            <div class='help-title-info'>Datos Importante</div>
                            <div class='help-info'><b>Codigo Caja</b> Es el codigo de la Caja Asignado por la superintendencia.</div>"
                 ),
                array(
                    'title' => 'Firmas por Caja de Compensacion',
                    'type' => 'master',
                    'default' => 'mercurio05',
					'help'=>"<div class='help-title'>Firmas por Caja de Compensacion</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Son los datos de las Personas Encargada de la firma por la Caja de Compensacion</div>
                            <div class='help-title-info'>Datos Importante</div>
                            <div class='help-info'><b>Cedula</b> Es la Cedula de la persona encargada para la firma.</div>
                            <div class='help-info'><b>Nombre</b> Es el Nombre de la persona encargada para la firma.</div>
                            <div class='help-info'><b>Cargo</b> Es el Cargo de la persona encargada para la firma.</div>
                            <div class='help-info'><b>Email</b> Es el email  de la persona encargada para la firma.</div>"
                 ),
                array(
                    'title' => 'Operaciones por Caja de Compensacion',
                    'type' => 'master',
                    'default' => 'mercurio10',
					'help'=>"<div class='help-title'>Operaciones/Tramites por Caja de Compensacion</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Son las operaciones o tramites que la Caja de Compensacion va a mostrar.</div>
                            <div class='help-title-info'>Datos Importante</div>
                            <div class='help-info'><b>Tipo</b>
                                <b>1)</b>Consulta
                                <b>2)</b>Sola archivo
                                <b>3)</b>descarga Formulario y enviar archivo
                                <b>4)</b>Captura,Descarga archivo y Archivos
                            </div>
                            <div class='help-info'><b>Estado</b> Es el Estado de la Operacion/Tramites de la Caja <b>A)</b>Activa <b>I)</b>Inactivo de la persona encargada para la firma.</div>"
                 ),
            ),
            "3"=> array("title"=>"Menu Publicidad",
                array(
                    'title' => 'Publicidad',
                    'default' => 'mercurio04',
                    'type' => 'otros',
                    'otros'=>array(
                        "index"=>"Principal",
                        "buscar"=>"Buscar",
                        "borrar"=>"Borrar",
                        "reporte"=>"reporte",
                        "nuevo"=>"Nuevo",
                        "validaPk"=>"Validacion",
                        "editar"=>"Editar",
                        "guardar"=>"guardar/editar",
                        "comprobarAsignatura"=>"Comprobar Asignatura",
                    ),
					'help'=>"<div class='help-title'>Publicidad</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Opcion que crea una publicidad</div>
                            <div class='help-title-info'>Datos Importante</div>
                            <div class='help-info'><b>Titulo</b> Titulo de la Publicidad.</div>
                            <div class='help-info'><b>Nota</b> Un Breve Mensaje a mostar.</div>
                            <div class='help-info'><b>Enlace</b> Es el Enlace que va a enrutar la publicidad.</div>
                            <div class='help-info'><b>Imagen</b> Es el Archivo de la Imagen que se va a mostar a las personas.</div>"
                 ),
                array(
                    'title' => 'Apertura Publicidad',
                    'default' => 'mercurio17',
                    'type' => 'otros',
                    'otros'=>array(
                        "index"=>"Principal",
                        "buscar"=>"Buscar",
                        "borrar"=>"Borrar",
                        "reporte"=>"reporte",
                        "nuevo"=>"Nuevo",
                        "validaPk"=>"Validacion",
                        "editar"=>"Editar",
                        "guardar"=>"guardar/editar",
                        "comprobarAsignatura"=>"Comprobar Asignatura",
                    ),
					'help'=>"<div class='help-title'>Apertura Publicidad</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Opcion que crea la Apertura de la publicidad</div>
                            <div class='help-title-info'>Datos Importante</div>
                            <div class='help-info'><b>Fecha Inicial</b> Apartir de que Fecha se va a Mostrar.</div>
                            <div class='help-info'><b>Fecha Final</b> Fecha Final que se Muestra.</div>
                            <div class='help-info'><b>Nivel</b> Es el nivel de intensidad a mostrar.</div>"
                 ),
            ),
            "4"=> array("title"=>"Email",
                array(
                    'title' => 'Enviar Publicidad',
                    'type' => 'otros',
                    'otros'=>array(
                        "enviarCorreo_view"=>"Principal",
                        "enviarCorreo"=>"Proceso",
                    ),
                    'default' => 'email/enviarCorreo_view',
					'help'=>"<div class='help-title'>Enviar Publicidad</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Opcion que envia un correo a las personas enviandole un archivo</div>"
                 ),
            ),
            "5"=> array("title"=>"Reportes",
            ),
            "6"=> array("title"=>"Ajustes",
                array(
                    'title' => 'Areas',
                    'type' => 'detail',
                    'default' => 'basica01',
                    'help'=>"<div class='help-title'>Areas Admnistrativas</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Captura que sirve para crear las diferentes areas de la empresa</div>
                            <div class='help-title-info'>Datos Importante</div>
                            <div class='help-info'><b>Codigo</b> Identificador unico para diferenciar las areas.</div>
                            <div class='help-info'><b>Detalle</b> Nombre del area.</div>"
                 ),
                array(
                    'title' => 'Tipos de Documentos',
                    'type' => 'detail',
                    'default' => 'gener18',
                    'help'=>"<div class='help-title'>Tipos de Documento</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Captura que sirve para crear los diferentes tipos de documentos de las personas</div>
                            <div class='help-title-info'>Datos Importante</div>
                            <div class='help-info'><b>Codigo</b> Identificador unico para diferenciar los tipos de documento.</div>
                            <div class='help-info'><b>Detalle</b> Nombre del tipo de documento.</div>"
                 ),
                array(
                    'title' => 'Funcionarios',
                    'type' => 'detail',
                    'default' => 'gener21',
                    'help'=>"<div class='help-title'>Funcionarios</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Captura que sirve para crear los diferentes Funcionarios que tiene el sistema</div>
                            <div class='help-title-info'>Datos Importante</div>
                            <div class='help-info'><b>Tipo</b> Identificador unico para diferenciar los funcionarios.</div>
                            <div class='help-info'><b>Detalle</b> Nombre del funcionario.</div>"
                 ),
                array(
                    'title' => 'Usuarios del Sistema',
                    'type' => 'master',
                    'default' => 'gener02',
                    'help'=>"<div class='help-title'>Usuarios del Sistema</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Captura que sirve para crear los diferentes usuarios del sistema</div>
                            <div class='help-title-info'>Datos Importante</div>
                            <div class='help-info'><b>Usuario</b> Identificador por el cual el usuario de logea al sistema.</div>
                            <div class='help-info'><b>Cedula</b> Cedula del Usuario.</div>
                            <div class='help-info'><b>Nombre</b> Nombre Usuario.</div>
                            <div class='help-info'><b>Funcionario</b> Tipo de funcionario del usuario.</div>
                            <div class='help-info'><b>Estado</b> Estado del usuario Activo (A) e Inactivo(I).</div>
                            <div class='help-info'><b>Clave</b> Clave de acesso al sistema.</div>"
                 ),
                array(
                    'title' => 'Permisos del Sistema',
                    'default' => 'gener28',
                    'type' => 'otros',
                    'otros'=>array(
                        "index"=>"Principal",
                        "traerFunciones"=>"Consultar Funciones",
                        "savePermiso"=>"Cambiar Permisos",
                        "reporte"=>"Reporte"
                    ),
                    'help'=>"<div class='help-title'>Permisos del Sistema</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Captura que sirve para dar o quitar permisos a los funcionarios del sistema</div>"
                 ),
                array(
                    'title' => 'Cambio de Clave',
                    'default' => 'desktop/cambiarClave_view',
                    'type' => 'otros',
                    'otros'=>array(
                        "cambiarClave_view"=>"Principal",
                        "cambiarClave"=>"Cambio de Clave"
                    ),
                    'help'=>"<div class='help-title'>Cambio de Clave</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Captura que sirve para dar o quitar permisos a los funcionarios del sistema</div>"
                 ),
            ),
    );

    public static $_types = array(
            "detail"=>array(
                "index"=>"Consulta",
                "guardar"=>"Modificar",
                "reporte"=>"Reporte",
            ),
            "master"=>array(
                "index"=>"Principal",
                "buscar"=>"Buscar",
                "borrar"=>"Borrar",
                "reporte"=>"reporte",
                "nuevo"=>"Nuevo",
                "validaPk"=>"Validacion",
                "editar"=>"Editar",
                "guardar"=>"guardar/editar",
            ),
            "visual-detail"=>array(
                "index"=>"Principal",
                "buscar"=>"Buscar",
                "detalle"=>"Consulta",
                "guardar"=>"Modificar",
                "reporte"=>"Reporte",
            ),
            "master-detail"=>array(
                "index"=>"Principal",
                "buscar"=>"Buscar",
                "borrar"=>"Borrar",
                "reporte"=>"reporte",
                "nuevo"=>"Nuevo",
                "validaPk"=>"Validacion",
                "editar"=>"Editar",
                "guardar"=>"guardar/editar",
            )
    );



    public static function showMenu($id){
        if(!isset(self::$_menu[$id]))return;
        $response  = "<nav>";
        $response .= "<nav>";
        $response .= "<ul class='list-menu'>";
        $response .= "<li><a>".self::$_menu[$id]['title']."</a><li>";
        foreach(self::$_menu[$id] as $key => $menu){
            if(!is_array($menu))continue;
            if(isset($menu['nodes'])){
                $response .= "<li><a>{$menu['title']}</a>";
                $response .="<ul>";
                Menu::showSubMenu($menu,$response);
                $response .="</ul></li>";
            }else{
                $response .= "<li>".Tag::linkTo("{$menu['default']}","{$menu['title']}")."</li>";
            }
        }
        $response .="</ul>";
        $response .="</nav>";
        $response .="</nav>";
        return $response;
    }

    public static function showSubMenu($option,&$response){
        foreach($option['nodes'] as $key => $value){
            if(is_array($value) && isset($value['nodes'])){
                $response .= "<li><a>{$value['title']}</a>";
                $response .="<ul>";
                Menu::showSubMenu($value,$response);
                $response .="</ul></li>";
            }else{
                $response .= "<li>".Tag::linkTo("{$value['default']}","{$value['title']}")."</li>";
            }
        }
    }
}

?>
