Event.observe(document, 'dom:loaded', function(){
    importScript($Kumbia.path+"public/javascript/mercurio/messages.js");
    $('img-off').observe('click',clickClsSesion);
    mes = new Date();
    ano_act = mes.getFullYear();
    mes_act = parseInt(mes.getMonth())+parseInt(1);
    ano = mes.getFullYear();
    mes = parseInt(mes.getMonth())+parseInt(1);
    dia_ult = "";
    mes_ult = "";
    ano_ult = "";
    nota_selec = "";
    usuario_act = "";
    view_user_info = false;
    verOpciones = true;
    //tinyMCE.init({
        //mode : "textareas",
        //theme: "advanced"
    //});
    //setInterval('actTime()',30000); // no hace nada!!!
    changeSelect();
});

function saveIntro(evt,element){
    Bs.getKey(evt,{
        action: function(kc){
            if(kc==Event.KEY_RETURN) {
                save();
            }
        }
    });
}

function showMsg(msg,mclass){
    window.setTimeout("Messagespro.display(Array('"+msg+"'),'"+mclass+"');","1000")
}

function changeSelect(){
    $$('select').each(function(elem){
        new Chosen(elem);
    });
}

function importScript(url){
    var tag = document.createElement("script");
    tag.type="text/javascript";
    tag.src = url;
    document.body.appendChild(tag);
}

function traerMenu(id){
clean = arguments[1]==undefined ? true : false;
    new Ajax.Request(Utils.getKumbiaURL("desktop/traerMenu"), {
        parameters: {
            id: id
        },
        onSuccess: function(transport){
            response = transport.responseText.evalJSON();
            $('menu').innerHTML = response;
            elem = $(id);
            $$(".dock_select").each(function(melem){
                melem.removeClassName("dock_select");
            });
            elem.up().addClassName("dock_select");
            if(clean)
              if(Object.isElement($('vista')))
                $('vista').innerHTML = '';
        },
        onFailure: function(transport){
            alert(transport.responseText);
        }
    });
}

function showCapBas2(){
    new Ajax.Request(Utils.getKumbiaURL("desktop/showCapBas2"), {
        parameters: {
        },
        onSuccess: function(transport){
            response = transport.responseText.evalJSON();
            $('capt-basica02').innerHTML = response;
            $('codare_basica02').focus();
            $('basica02').select('input, select').each(function(e){
                $(e).observe('change',saveCapBas2);
            });
        },
        onFailure: function(transport){
            alert(transport.responseText);
        }
    });
}

function saveCapBas2(){
    new Ajax.Request(Utils.getKumbiaURL("desktop/saveCapBas2"), {
        asynchronous: false,
        parameters: {
            codare: $F('codare_basica02'),
            ext: $F('ext_basica02'),
            celular: $F('celular_basica02'),
            telefono: $F('telefono_basica02'),
            email: $F('email_basica02')
        },
        onSuccess: function(transport){
            response = transport.responseText.evalJSON();
            //clickBtnBas2();
        },
        onFailure: function(transport){
            alert(transport.responseText);
        }
    });
}

function showBas2(){
    showCapBas2();
}


function clickBtnBas2(){
    if(view_user_info==true)
        $('basica02').morph('top: -385px',{duration: .7});
    else{
        showBas2();
        $('basica02').morph('top: 30px',{duration: .7});
    }
    view_user_info = !(view_user_info);
}

function closeAll(evento){
    var flag = true;
    if(evento!=undefined){
    $(evento.target).ancestors().each(
        function(elem){
            if(elem.id=="no_cerrar"){
                flag=false;
                return;
            }
        }
    );
    }
    if(flag==false)return;
    if($('calendar').visible()==true)$('calendar').hide();
    if($('contact').visible()==true)$('contact').hide();
    if($('help').visible()==true)$('help').hide();
}

function clickClsSesion(){
    new Ajax.Request(Utils.getKumbiaURL("login/ClsSesion"), {
        parameters: {
        },
        onSuccess: function(transport){
            response = transport.responseText.evalJSON();
            window.location = Utils.getKumbiaURL("login"); 
        },
        onFailure: function(transport){
            alert(transport.responseText);
        }
    });
}

/*
function actTime(){
  new Ajax.PeriodicalUpdater("time",$Kumbia.path+$Kumbia.app+"/desktop/actTime",{
    method: 'get', frequency: 1,
    onSuccess: function(transport){
      response = transport.responseText.evalJSON();
      $('content-time').innerHTML = response;
    }
  });
}*/

function actTime(){
    new Ajax.Request(Utils.getKumbiaURL("desktop/actTime"), {
        parameters: {
        },
        onSuccess: function(transport){
            response = transport.responseText.evalJSON();
            $('content-time').innerHTML = response;
        },
        onFailure: function(transport){
            alert(transport.responseText);
        }
    });
}

/*
*   Funcion para contar Notificaciones 
*/

function countNot(){
    new Ajax.Request(Utils.getKumbiaURL("desktop/countNot"), {
        parameters: {
        },
        onSuccess: function(transport){
            response = transport.responseText.evalJSON();
            if(parseInt(response)>0){
                if($('chat').visible()==true && usuario_act!=""){
                    showListChat();
                    showListChatInfo().delay(2);
                }else{
                    Messagespro.display(Array("Tiene "+response+"Mensajes sin Leer"),"warning");
                }
            }
        },
        onFailure: function(transport){
            alert(transport.responseText);
        }
    });
}

/*
*   Funcion para ocultar el menu de inicio
*/
function hideInicio(name){
    $(name).hide();
}
/*
*   Funcion para mostrar el menu de inicio
*/
function showInicio(name){
    $('notas').hide();
    $('calendar').hide();
    $('chat').hide();
    $('contact').hide();
    $('help').hide();
    $(name).show();
}

/*
*   Funcion para seleccionar la nota 
*/

function selectNota(numero){
    if(nota_selec!="")
        $(nota_selec).removeClassName('active');
    $(numero).addClassName('active');
    nota_selec = numero;
    showListNotasMsg();
}

/*
*   Funcion para mostrar Las Notas 
*/

function showListNotas(act){
    new Ajax.Request(Utils.getKumbiaURL("desktop/showListNotas"), {
        parameters: {
            numero: nota_selec,
            act: act
        },
        onSuccess: function(transport){
            response = transport.responseText.evalJSON();
            $('content-notas-listas').innerHTML = response['html'];
            nota_selec = response['id'];
            showListNotasMsg();
        },
        onFailure: function(transport){
            alert(transport.responseText);
        }
    });
}

/*
*   Funcion para mostrar el mensaje de la Nota
*/

function showListNotasMsg(){
    new Ajax.Request(Utils.getKumbiaURL("desktop/showListNotasMsg"), {
        parameters: {
            numero: nota_selec
        },
        onSuccess: function(transport){
            response = transport.responseText.evalJSON();
            $('nota_nota').value = response;
        },
        onFailure: function(transport){
            alert(transport.responseText);
        }
    });
}

/*
*   Funcion para borrar el mensaje seleccionado
*/

function delListNotas(){
    new Ajax.Request(Utils.getKumbiaURL("desktop/delListNotas"), {
        parameters: {
            numero: nota_selec
        },
        onSuccess: function(transport){
            showNotas();
        },
        onFailure: function(transport){
            alert(transport.responseText);
        }
    });
}

/*
*   Funcion para adicionar el mensaje seleccionado
*/

function addListNotas(){
    new Ajax.Request(Utils.getKumbiaURL("desktop/addListNotas"), {
        parameters: {
            numero: nota_selec,
            nota: $F('nota_nota')
        },
        onSuccess: function(transport){
            response = transport.responseText.evalJSON();
            if(response['flag']==false)alert(response['msg']);
            else nota_selec = response['data'];
            showListNotas(false);
        },
        onFailure: function(transport){
            alert(transport.responseText);
        }
    });
}

/*
 * Function para crear uno notas
 */

function newNota(){
    if(nota_selec!="")
        $(nota_selec).removeClassName('active');
    nota_selec = "";
    $('nota_nota').value="";
    $('nota_nota').focus();
}

/*
 * Function para traer las notas y crear la opcion
 */

function showNotas(){
    showListNotas(true);
}

/*
*   Funcion para mostrar/ocultar el menu o iconos
*/

function clickBtnNotas(){
    if($('notas').visible()){
        hideInicio('notas');
    }else{
        showInicio('notas');
    }
    showNotas();
}



/*
 * Function para traer los Contactos  y crear la opcion
 */

function showContact(){
    showListContact();
}

function selectContact(elem,usuario){
    $$('.content-contact-listas').each(function(ele){
        ele.removeClassName('active');
    });
    elem.addClassName('active');
    showListContactInfo(usuario);
}

/*
*   Funcion para mostrar Los Contactos 
*/

function showListContact(){
    new Ajax.Request(Utils.getKumbiaURL("desktop/showListContact"), {
        parameters: {
        },
        onSuccess: function(transport){
            response = transport.responseText.evalJSON();
            $('content-contact-listas').innerHTML = response;
            $('content-contact-info').innerHTML = "";
        },
        onFailure: function(transport){
            alert(transport.responseText);
        }
    });
}

/*
*   Funcion para mostrar la informacion del contacto
*/

function showListContactInfo(usuario){
    new Ajax.Request(Utils.getKumbiaURL("desktop/showListContactInfo"), {
        parameters: {
            usuario: usuario
        },
        onSuccess: function(transport){
            response = transport.responseText.evalJSON();
            $('content-contact-info').innerHTML = response;
        },
        onFailure: function(transport){
            alert(transport.responseText);
        }
    });
}

/*
*   Funcion para mostrar/ocultar el menu o iconos
*/

function clickBtnCon(){
    if($('contact').visible()){
        hideInicio('contact');
    }else{
        showInicio('contact');
    }
    showContact();
}

/*
 * Function para traer los Contactos  y crear la opcion
 */

function showHelp(){
    showListHelp();
}

function selectHelp(elem){
    $$('.content-help-listas').each(function(ele){
        ele.removeClassName('active');
    });
    elem.addClassName('active');
    $('content-help-info').innerHTML = elem.next().innerHTML;
}

/*
*   Funcion para mostrar Los Contactos 
*/

function showListHelp(){
    new Ajax.Request(Utils.getKumbiaURL("desktop/showListHelp"), {
        parameters: {
        },
        onSuccess: function(transport){
            response = transport.responseText.evalJSON();
            $('content-help-listas').innerHTML = response;
            $('content-help-info').innerHTML = "";
        },
        onFailure: function(transport){
            alert(transport.responseText);
        }
    });
}


/*
*   Funcion para mostrar/ocultar el menu o iconos
*/

function clickBtnHelp(){
    if($('help').visible()){
        hideInicio('help');
    }else{
        showInicio('help');
    }
    showHelp();
}

/*
 * Function para Guardar el Chat
 */

function saveChat(){
    if($F('msg_chat').blank())return;
    $('btn_snd_msg').addClassName("no_send");
    new Ajax.Request(Utils.getKumbiaURL("desktop/saveChat"), {
        parameters: {
            usuario: usuario_act,
            msg: $F('msg_chat')
        },
        onSuccess: function(transport){
            response = transport.responseText.evalJSON();
            $('btn_snd_msg').removeClassName("no_send");
            showListChatInfo(usuario_act);
            $('msg_chat').value="";
        },
        onFailure: function(transport){
            alert(transport.responseText);
        }
    });
}

/*
 * Function para traer los Contactos  y crear la opcion
 */

function showChat(){
    showListChat();
}

function iniciaChat(usu){
    if($('chat').visible()){
        hideInicio('chat');
    }else{
        showInicio('chat');
    }
    showListChat2(usu);
}

/*
*   Funcion para mostrar Los Contactos del chat por inicio chat
*/

function showListChat2(usu){
    new Ajax.Request(Utils.getKumbiaURL("desktop/showListChat2"), {
        parameters: {
            usuario: usu
        },
        onSuccess: function(transport){
            response = transport.responseText.evalJSON();
            $('content-chat-listas').innerHTML = response;
            $('content-conversation').innerHTML = "";
            showListChatInfo(usu);
            usuario_act = usu;
            $('msg_chat').focus();
        },
        onFailure: function(transport){
            alert(transport.responseText);
        }
    });
}


function selectChat(elem,usuario){
    $$('.content-chat-listas').each(function(ele){
        ele.removeClassName('active');
    });
    elem.addClassName('active');
    showListChatInfo(usuario);
    usuario_act = usuario;
}

/*
*   Funcion para mostrar Los Contactos 
*/

function showListChat(){
    new Ajax.Request(Utils.getKumbiaURL("desktop/showListChat"), {
        parameters: {
        },
        onSuccess: function(transport){
            response = transport.responseText.evalJSON();
            $('content-chat-listas').innerHTML = response;
            $('content-conversation').innerHTML = "";
        },
        onFailure: function(transport){
            alert(transport.responseText);
        }
    });
}

/*
*   Funcion para mostrar la informacion del contacto
*/

function showListChatInfo(usuario){
    if(usuario==undefined && usuario_act!="")usuario=usuario_act;
    new Ajax.Request(Utils.getKumbiaURL("desktop/showListChatInfo"), {
        parameters: {
            usuario: usuario
        },
        onSuccess: function(transport){
            response = transport.responseText.evalJSON();
            $('content-conversation').innerHTML = response;
            var di = $('content-conversation');
            di.scrollTop = di.scrollHeight - di.clientHeight;
        },
        onFailure: function(transport){
            alert(transport.responseText);
        }
    });
}

/*
*   Funcion para mostrar/ocultar el menu o iconos
*/

function clickBtnChat(){
    if($('chat').visible()){
        hideInicio('chat');
    }else{
        showInicio('chat');
    }
    showChat();
}

/*
 * Function para traer el Calendario 
 */

function showCalen(){
    showListCalen(0);
}

/*
 * Funcion para mostrar los eventos del dia seleccionado
 */

function showEvent(day,month,year){
    dia_ult = day;
    mes_ult = month;
    ano_ult = year;
    showListCalen(4,day,month,year);
}

/*
*   Funcion para mostrar el Calendario 
*/

function showListCalen(tipo,dia,month,year){
    if(tipo==1){
        mes--;
    }else if(tipo==2){
        mes++;
    }
    if(mes==13){
        ano++;
        mes=1;
    }else if(mes==0){
        ano--;
        mes=12;
    }
    if(tipo==3){
        mes=mes_act;
        ano=ano_act;
    }
    new Ajax.Request(Utils.getKumbiaURL("desktop/showListCalen"), {
        parameters: {
            mes: mes,
            ano: ano,
            dia_evento: dia_ult,
            mes_evento: mes_ult,
            ano_evento: ano_ult
        },
        onSuccess: function(transport){
            response = transport.responseText.evalJSON();
            $('content-calen').innerHTML = response;
        },
        onFailure: function(transport){
            alert(transport.responseText);
        }
    });
}

/*
*   Funcion para mostrar/ocultar el menu o iconos
*/

function clickBtnCalen(){
    if($('calendar').visible()){
        hideInicio('calendar');
    }else{
        showInicio('calendar');
    }
    showCalen();
}


function help(elem){
    if(elem.next().visible())
        $(elem.next()).fade();
    else
        $(elem.next()).appear();
}

/*
 *  Devuelve el mayor valor para el atributo z-indez dentro de la pagina
 */
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




function auto(element, event,model){
    if(event.keyCode==13){
        return;
    }
    new Ajax.Autocompleter(element, 'autocomplete_choices_'+element, Utils.getKumbiaURL("ajax/auto"), {
        paramName: 'value', 
        minChars: 2, 
        indicator: 'indicator_'+element,
        parameters: "model="+model+"&campo="+element,
        afterUpdateElement: 'confAC'
        });
}
function confAC(){
    alert('o');
}


function borrar(url){
    if(confirm('Desea Borrar el registro ?') == true){
        window.location=Utils.getKumbiaURL($Kumbia.controller+"/borrar/"+url);
    }
}

function action_url(action,url){
    if(confirm('Desea '+action+' el registro ?') == true){
        window.location=Utils.getKumbiaURL($Kumbia.controller+"/"+action+"/"+url);
    }
}

function irPag(url,total_pages){
    if($F('num_pag')!=""){
        total_pages = parseInt(total_pages);
        pag = $F('num_pag').gsub(/[^0-9]/,'');
        pag = pag=='' ? pag = 0 : parseInt(pag);
        if(pag>total_pages) $('num_pag').value = total_pages;
        if(pag<=0) $('num_pag').value = 1;
        new AJAX.viewRequest({action: url+'/buscar/p'+$F('num_pag'), container: 'consulta'});
    }
}

function actualizar_select(name){
    if(name!=undefined){
        $(name+'_chosen').down('span').innerHTML=$(name).options[$(name).selectedIndex].text;
    }else{
        $$('select').each(function(elem){
            name = elem.id;
            $(name+'_chosen').down('span').innerHTML=$(name).options[$(name).selectedIndex].text;
        });
    }
}

function formatTime(field){
    var field = $(field), time = $F(field);
    field.value = "";
    if(/^(\d{1,4}|\d{1,2}:\d{2})$/.test(time)){
        time = time.gsub(/^(\d{1,2}):?(\d{2})?$/,'#{1}|#{2}').split("|");
        if(time[0]>23 || time[1]>59) return "error";
        field.value = ("00" + time[0]).gsub(/^\d*(\d{2})$/,"#{1}")+":"+("00" + time[1]).gsub(/^\d*(\d{2})$/,"#{1}");
    }else{
        return "error";
    }
}

