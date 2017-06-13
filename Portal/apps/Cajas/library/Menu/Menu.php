<?php


class Menu {




    public static $_menu = array(
            "1"=> array("title"=>"Subsidio",
                array(
                    'title' => "Afiliaciones Empresa ",
                    'default' => 'mercurio30',
                    'type' => 'detail',
                    'help'=>"<div class='help-title'>Tipos de Afiliado</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Son los tipos de Terceros o Afiliados que van a tener acceso al programa</div>
                            <div class='help-title-info'>Datos Importante</div>
                            <div class='help-info'><b>Tipo </b> sirve para saber cual es el tipo de terceros.</div>
                            <div class='help-info'><b>Detalle</b> sirve para saber cual es la descripcion.</div>"
                ),
                array(
                    'title' => 'Afiliaciones Trabajadores',
                    'default' => 'mercurio31',
                    'type' => 'detail',
                    'help'=>"<div class='help-title'>Marcas para Firma</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Son Marcas para identificar una firma para los reportes</div>
                            <div class='help-title-info'>Datos Importante</div>
                            <div class='help-info'><b>Firma </b> sirve para saber cual es el codigo Firma.</div>
                            <div class='help-info'><b>Detalle</b> sirve para saber cual es la descripcion.</div>"
                ),
                array(
                    'title' => 'Afiliaciones Conyuges',
                    'default' => 'mercurio32',
                    'type' => 'detail',
                    'help'=>"<div class='help-title'>Marcas para Firma</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Son Marcas para identificar una firma para los reportes</div>
                            <div class='help-title-info'>Datos Importante</div>
                            <div class='help-info'><b>Firma </b> sirve para saber cual es el codigo Firma.</div>
                            <div class='help-info'><b>Detalle</b> sirve para saber cual es la descripcion.</div>"
                ),
                array(
                    'title' => 'Afiliaciones Beneficiarios',
                    'default' => 'mercurio34',
                    'type' => 'detail',
                    'help'=>"<div class='help-title'>Marcas para Firma</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Son Marcas para identificar una firma para los reportes</div>
                            <div class='help-title-info'>Datos Importante</div>
                            <div class='help-info'><b>Firma </b> sirve para saber cual es el codigo Firma.</div>
                            <div class='help-info'><b>Detalle</b> sirve para saber cual es la descripcion.</div>"
                ),
                array(
                    'title' => 'Actualizacion Datos Empresa',
                    'default' => 'mercurio33/index/E',
                    'type' => 'detail',
                    'help'=>"<div class='help-title'>Marcas para Firma</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Son Marcas para identificar una firma para los reportes</div>
                            <div class='help-title-info'>Datos Importante</div>
                            <div class='help-info'><b>Firma </b> sirve para saber cual es el codigo Firma.</div>
                            <div class='help-info'><b>Detalle</b> sirve para saber cual es la descripcion.</div>"
                ),
                array(
                    'title' => 'Actualizacion Datos Trabajador',
                    'default' => 'mercurio33/index/T',
                    'type' => 'detail',
                    'help'=>"<div class='help-title'>Marcas para Firma</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Son Marcas para identificar una firma para los reportes</div>
                            <div class='help-title-info'>Datos Importante</div>
                            <div class='help-info'><b>Firma </b> sirve para saber cual es el codigo Firma.</div>
                            <div class='help-info'><b>Detalle</b> sirve para saber cual es la descripcion.</div>"
                ),
/*
                array(
                    'title' => 'Actualizacion Datos Conyuge',
                    'default' => 'mercurio33/index/C',
                    'type' => 'detail',
                    'help'=>"<div class='help-title'>Marcas para Firma</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Son Marcas para identificar una firma para los reportes</div>
                            <div class='help-title-info'>Datos Importante</div>
                            <div class='help-info'><b>Firma </b> sirve para saber cual es el codigo Firma.</div>
                            <div class='help-info'><b>Detalle</b> sirve para saber cual es la descripcion.</div>"
                ),
*/                
                array(
                    'title' => 'Retiro de Trabajador',
                    'default' => 'mercurio35',
                    'type' => 'detail',
                    'help'=>"<div class='help-title'>Marcas para Firma</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Son Marcas para identificar una firma para los reportes</div>
                            <div class='help-title-info'>Datos Importante</div>
                            <div class='help-info'><b>Firma </b> sirve para saber cual es el codigo Firma.</div>
                            <div class='help-info'><b>Detalle</b> sirve para saber cual es la descripcion.</div>"
                ),
                array(
                    'title' => 'Actualizacion de Datos Principales Empresas',
                    'default' => 'mercurio43',
                    'type' => 'detail',
                    'help'=>"<div class='help-title'>Marcas para Firma</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Son Marcas para identificar una firma para los reportes</div>
                            <div class='help-title-info'>Datos Importante</div>
                            <div class='help-info'><b>Firma </b> sirve para saber cual es el codigo Firma.</div>
                            <div class='help-info'><b>Detalle</b> sirve para saber cual es la descripcion.</div>"
                ),

                array(
                    'title' => 'Verificacion de Certificados',
                    'default' => 'mercurio45',
                    'type' => 'detail',
                    'help'=>"<div class='help-title'>Marcas para Firma</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Son Marcas para identificar una firma para los reportes</div>
                            <div class='help-title-info'>Datos Importante</div>
                            <div class='help-info'><b>Firma </b> sirve para saber cual es el codigo Firma.</div>
                            <div class='help-info'><b>Detalle</b> sirve para saber cual es la descripcion.</div>"
                ),

                /*
                array(
                    'title' => 'Validacion Certificados',
                    'default' => 'mercurio20/consultaCertificados_view',
                    'type' => 'detail',
                    'help'=>"<div class='help-title'>Marcas para Firma</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Son Marcas para identificar una firma para los reportes</div>
                            <div class='help-title-info'>Datos Importante</div>
                            <div class='help-info'><b>Firma </b> sirve para saber cual es el codigo Firma.</div>
                            <div class='help-info'><b>Detalle</b> sirve para saber cual es la descripcion.</div>"
                ),
                */


            ),
/*
            "2"=> array("title"=>"Creditos",
                array(
                    'title' => 'Consulta Movimientos',
                    'type' => 'master',
                    'default' => 'mercurio21/consultaCreditos_view',
                    'help'=>"<div class='help-title'>Caja de Compensacion</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Son los datos basicos de la Caja de Compensacion</div>
                            <div class='help-title-info'>Datos Importante</div>
                            <div class='help-info'><b>Codigo Caja</b> Es el codigo de la Caja Asignado por la superintendencia.</div>"
                 ),
            ),
*/
            "3"=> array("title"=>"Vivienda",
                array(
                    'title' => 'Formulario',
                    'default' => 'mercurio04',
                    'type' => 'detail',
                    'help'=>"<div class='help-title'>Publicidad</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Opcion que crea una publicidad</div>
                            <div class='help-title-info'>Datos Importante</div>
                            <div class='help-info'><b>Titulo</b> Titulo de la Publicidad.</div>
                            <div class='help-info'><b>Nota</b> Un Breve Mensaje a mostar.</div>
                            <div class='help-info'><b>Enlace</b> Es el Enlace que va a enrutar la publicidad.</div>
                            <div class='help-info'><b>Imagen</b> Es el Archivo de la Imagen que se va a mostar a las personas.</div>"
                 ),
            ),
            "4"=> array("title"=>"Reportes",
                array(
                    'title' => 'Afiliados al Sistema',
                    'type' => 'master',
                    'default' => 'mercurio07',
                    'help'=>"<div class='help-title'>Afiliados al sistema</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Captura que sirve para crear los diferentes usuarios del sistema</div>
                            <div class='help-title-info'>Datos Importante</div>
                            <div class='help-info'><b>Documento</b> Documento y usuario de logueo al sistema.</div>
                            <div class='help-info'><b>Nombre</b> Nombre Usuario.</div>
                            <div class='help-info'><b>Tipo</b> Tipo de Afiliado.</div>
                            <div class='help-info'><b>Agencia</b> Agencia a la cual se vinculo.</div>
                            <div class='help-info'><b>Clave</b> Clave de acesso al sistema.</div>"
                 ),
                array(
                    'title' => 'Informe de Tranzabilidad',
                    'type' => 'master',
                    'default' => 'mercurio21',
                    'help'=>"<div class='help-title'>Movimientos de Afiliados</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Captura que sirve para crear los diferentes usuarios del sistema</div>
                            <div class='help-title-info'>Datos Importante</div>
                            <div class='help-info'><b>Documento</b> Documento y usuario de logueo al sistema.</div>
                            <div class='help-info'><b>Tipo</b> Tipo de Afiliado.</div>
                            <div class='help-info'><b>Fecha inicial</b> Fecha de inicio de movimientos.</div>
                            <div class='help-info'><b>Fecha Final</b> Fecha de Final de movimientos.</div>"
                 ),
                array(
                    'title' => 'Movimientos de afiliados',
                    'type' => 'master',
                    'default' => 'mercurio20',
                    'help'=>"<div class='help-title'>Movimientos de Afiliados</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Captura que sirve para crear los diferentes usuarios del sistema</div>
                            <div class='help-title-info'>Datos Importante</div>
                            <div class='help-info'><b>Documento</b> Documento y usuario de logueo al sistema.</div>
                            <div class='help-info'><b>Tipo</b> Tipo de Afiliado.</div>
                            <div class='help-info'><b>Fecha inicial</b> Fecha de inicio de movimientos.</div>
                            <div class='help-info'><b>Fecha Final</b> Fecha de Final de movimientos.</div>"
                 ),
            ),
            "5"=> array("title"=>"Ajustes",
/*
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
*/
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
            "6"=> array("title"=>"Servicio en Linea",
/*
                array(
                    'title' => 'Gestionar Clasificados',
                    'type' => 'detail',
                    'default' => 'mercurio24',
                    'help'=>"<div class='help-title'>Areas Admnistrativas</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Captura que sirve para crear las diferentes areas de la empresa</div>
                            <div class='help-title-info'>Datos Importante</div>
                            <div class='help-info'><b>Codigo</b> Identificador unico para diferenciar las areas.</div>
                            <div class='help-info'><b>Detalle</b> Nombre del area.</div>"
                 ),
                array(
                    'title' => 'Gestionar Noticias',
                    'type' => 'detail',
                    'default' => 'mercurio29/gestionNoticias_view',
                    'help'=>"<div class='help-title'>Gestionar Noticias</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Captura cuya funcion es administrar las noticias activas en el sistema</div>
                            <div class='help-title-info'>Datos Importante</div>
                            <div class='help-info'><b>Codigo</b> Identificador unico para diferenciar los tipos de documento.</div>
                            <div class='help-info'><b>Detalle</b> Nombre del tipo de documento.</div>"
                 ),
*/
                array(
                    'title' => 'Distribucion Usuario',
                    'type' => 'detail',
                    'default' => 'mercurio44',
                    'help'=>"<div class='help-title'>Distribucion Usuario</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Captura que sirve para distribuir a los usuarios que van a trabajar las opciones y poder asignarles en orden los casos.</div>"
                 ),
                array(
                    'title' => 'Reasignar solicitudes',
                    'type' => 'detail',
                    'default' => 'mercurio44/consultaAdministrativa_view',
                    'help'=>"<div class='help-title'>Distribucion Usuario</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Captura que sirve para distribuir a los usuarios que van a trabajar las opciones y poder asignarles en orden los casos.</div>"
                 ),
                array(
                    'title' => 'Gestionar Banners',
                    'type' => 'detail',
                    'default' => 'mercurio26/gestionBanners_view',
                    'help'=>"<div class='help-title'>Publicación Notícias</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Captura que sirve para crear las noticias que aparecerán en la sección de noticias en el login</div>
                            <div class='help-title-info'>Datos Importante</div>
                            <div class='help-info'><b>Numero</b> Identificador unico para diferenciar las noticias.</div>
                            <div class='help-info'><b>Titulo</b>Titulo de la noticia que sirve para diferenciar la noticia.</div>"
                 ),
/*
                array(
                    'title' => 'Publicación Banners',
                    'type' => 'detail',
                    'default' => 'mercurio42',
                    'help'=>"<div class='help-title'>Publicación Banners</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Captura que sirve para publicar los banners que aparecerán en el login y en el escritorio del usuario al lado derecho de este mismo</div>
                            <div class='help-title-info'>Datos Importantes</div>
                            <div class='help-info'><b>Codban</b> Identificador unico para diferenciar los banners.</div>
                            <div class='help-info'><b>Nomarc</b>Nombre y ubicación de la imagen dentro de la carpeta de banners.</div>"
                 ),
*/
                array(
                    'title' => 'Publicación Banners',
                    'type' => 'detail',
                    'default' => 'mercurio26',
                    'help'=>"<div class='help-title'>Publicación Banners</div>
                            <div class='help-title-info'>Descripcion</div>
                            <div class='help-info'>Captura que sirve para publicar los banners que aparecerán en el logi.</div>
                            <div class='help-title-info'>Datos Importantes</div>
                            <div class='help-info'><b>Codban</b> Identificador unico para diferenciar los banners.</div>
                            <div class='help-info'><b>Nomarc</b>Nombre y ubicación de la imagen dentro de la carpeta de banners.</div>"
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
        $mer30 = new Mercurio30();
        $mer31 = new Mercurio31();
        $mer32 = new Mercurio32();
        $mer33 = new Mercurio33();
        $mer34 = new Mercurio34();
        $mer35 = new Mercurio35();
        $mer43 = new Mercurio43();
        $mer45 = new Mercurio45();
        $response  = "<nav>";
        $response .= "<nav>";
        $response .= "<ul class='list-menu'>";
        $response .= "<li><a>".self::$_menu[$id]['title']."</a><li>";
        foreach(self::$_menu[$id] as $key => $menu){
            if(!is_array($menu))continue;
            if(isset($menu['nodes'])){
                $response .= "<li><a>{$menu['title']} </a>";
                $response .="<ul>";
                Menu::showSubMenu($menu,$response);
                $response .="</ul></li>";
            }else{
                $count = "";
                if(Session::getData('usuario') == '1'){
                    if($menu['default']=="mercurio30") $count = "(".$mer30->count("id","conditions: estado='P'").")";
                    if($menu['default']=="mercurio31") $count = "(".$mer31->count("id","conditions: estado='P'").")";
                    if($menu['default']=="mercurio32") $count = "(".$mer32->count("id","conditions: estado='P'").")";
                    if($menu['default']=="mercurio33/index/E") $count = "(".$mer33->findBySql("select count(*) as id from (select distinct documento,campo from mercurio33 where tipo='E' and estado='P' group by 1,2) as mercurio33")->getId().")";
                    if($menu['default']=="mercurio33/index/T") $count = "(".$mer33->findBySql("select count(*) as id from (select distinct documento,campo from mercurio33 where tipo='T' and estado='P' group by 1,2) as mercurio33")->getId().")";
                    if($menu['default']=="mercurio34") $count = "(".$mer34->count("id","conditions: estado='P'").")";
                    if($menu['default']=="mercurio35") $count = "(".$mer35->count("id","conditions: estado='P'").")";
                    if($menu['default']=="mercurio43") $count = "(".$mer43->count("id","conditions: estado='P'").")";
                    if($menu['default']=="mercurio45") $count = "(".$mer45->count("id","conditions: estado='P'").")";
                    $response .= "<li>".Tag::linkTo("{$menu['default']}","{$menu['title']} $count")."</li>";
                }else{
                    if($menu['default']=="mercurio30") $count = "(".$mer30->count("id","conditions: usuario='".SESSION::getDATA('usuario')."' AND estado='P'").")";
                    if($menu['default']=="mercurio31") $count = "(".$mer31->count("id","conditions: usuario='".SESSION::getDATA('usuario')."' AND estado='P'").")";
                    if($menu['default']=="mercurio32") $count = "(".$mer32->count("id","conditions: usuario='".SESSION::getDATA('usuario')."' AND estado='P'").")";
                    if($menu['default']=="mercurio33/index/E") $count = "(".$mer33->findBySql("select count(*) as id from (select distinct documento,campo from mercurio33 where tipo='E' and estado='P' and usuario='".SESSION::getDATA('usuario')."' group by 1,2) as mercurio33")->getId().")";
                    if($menu['default']=="mercurio33/index/T") $count = "(".$mer33->findBySql("select count(*) as id from (select distinct documento,campo from mercurio33 where tipo='T' and estado='P' and usuario='".SESSION::getDATA('usuario')."' group by 1,2) as mercurio33")->getId().")";
                    if($menu['default']=="mercurio34") $count = "(".$mer34->count("id","conditions: usuario='".SESSION::getDATA('usuario')."' AND estado='P'").")";
                    if($menu['default']=="mercurio35") $count = "(".$mer35->count("id","conditions: usuario='".SESSION::getDATA('usuario')."' AND estado='P'").")";
                    if($menu['default']=="mercurio43") $count = "(".$mer43->count("id","conditions: usuario='".SESSION::getDATA('usuario')."' AND estado='P'").")";
                    if($menu['default']=="mercurio45") $count = "(".$mer45->count("id","conditions: usuario='".SESSION::getDATA('usuario')."' AND estado='P'").")";
                    $response .= "<li>".Tag::linkTo("{$menu['default']}","{$menu['title']} $count")."</li>";
                }
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
