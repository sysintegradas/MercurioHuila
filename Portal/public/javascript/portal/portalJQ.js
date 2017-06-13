$(document).ready(function(){   
    $('[data-toggle="popover"]').popover();
    calendars();
    elem_anterior = "";
});

function confirmaSalida(){                                                                               
    if(flagSalida)
    return "Esta Seguro de Cerrar la Pagina esta Procesando algo y puede afectar la seguridad de los datos.";   
}

function actProceso(){
    flagSalida = true;
    $(window).ajaxStart($.blockUI({ message: '<span class="progress"> <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 100%"> <span >Procesando por Favor Espere..!!</span> </div> </span>' })).ajaxStop($.unblockUI);
}
function terProceso(){
    flagSalida = false;
}

function traerOpcion(url,elem,codare,codope){
    var request = $.ajax({
        type: "POST",
        url: Utils.getKumbiaURL("login/sesion"),
        data: { 
            codare: codare,
            codope: codope  
        }
    });
    request.done(function( transport ) {
        var response = jQuery.parseJSON(transport);
        if(response['flag'] == true){
            location.reload();
            return;
        }
    });
    request.fail(function( jqXHR, textStatus ) {
        Messages.display(response['msg'],"error");
    });

    var request = $.ajax({
        type: "POST",
        url: Utils.getKumbiaURL(url),
        data: { 
            codare: codare,
            codope: codope  
        }
    });
    request.done(function( transport ) {
        var response = transport;
        if(elem!=undefined){
            if(elem_anterior!=""){
                $(elem_anterior).find("a").css("color","");
                $(elem_anterior).find("a").css("background-color","");
            }
            $(elem).find("a").css("color","white");//aquiii
            $(elem).find("a").css("background-color","#496B44");//aquiii
            elem_anterior = elem;
        }
        $("#consulta").html(response); 
    });
    request.fail(function( jqXHR, textStatus ) {
        Messages.display(response['msg'],"error");
    });

}
function showMsg(msg,mclass){
    window.setTimeout("Messages.display(Array('"+msg+"'),'"+mclass+"');","1000")
}

function getMaxZindex(){
    if(arguments[0]!=undefined){
        elem = arguments[0];
    }else{
        elem = document;
    }
    var tCol=elem.getElementsByTagName('*');
    var z=0;
    for(var i=0;i<tCol.length;i++){
        if(tCol[i].style.zIndex>z){
            z=tCol[i].style.zIndex;
        }
    }
    return ++z;
}
function calendars(){
  $("input[date='date']").datepicker({
    format: 'yyyy-mm-dd',
  });
}

function validaPuntos(evt){
    evt = (evt) ? evt : ((window.event) ? window.event : null);
		var kc = evt.keyCode;
		if(kc==190){
			evt.preventDefault();
    		evt.stopPropagation();
    		evt.stopped = true;
		}

}

function changeSelect(){
    $('select').each(function(elem){
        new Chosen(elem);
    });
}
/* FS - "onkeypress: return valideOnlyEmail();"*/
function valideOnlyEmail(e){
    k = (document.all) ? e.keyCode : e.which;
    if (k==8 || k==0) return true;
    patron = /[a-zA-Z0-9_@.-]/;
    n = String.fromCharCode(k);
    return patron.test(n);
}
