/*
*   Javascript para el aplicativo desktop
*   Author: Syseu
*/
/*
*   Observadores para capturas los eventos de toda la pagina
*/
Event.observe(document, 'dom:loaded', function(){
    window.document.observe('click',closeAll);
    $('inicio').hide();
    $('calendar').hide();
    $('chat').hide();
    $('contact').hide();
    $('notas').hide();
    $('img-off').observe('click',clickClsSesion);
    $('img-not').observe('click',clickBtnNotas);
    $('img-con').observe('click',clickBtnCon);
    $('img-chat').observe('click',clickBtnChat);
    $('img-cal').observe('click',clickBtnCalen);
    $('btn-inicio').observe('click',clickBtnInicio);
    $('del-nota').observe('click',delListNotas);
    $('add-nota').observe('click',addListNotas);
    $('welcome').observe('click',clickBtnBas2);
    mes = new Date();
    ano_act = mes.getFullYear();
    mes_act = parseInt(mes.getMonth())+parseInt(1);
    ano = mes.getFullYear();
    mes = parseInt(mes.getMonth())+parseInt(1);
    nota_selec = "";
    usuario_act = "";
    view_user_info = false;
    verOpciones = true;
    //tinyMCE.init({
        //mode : "textareas",
        //theme: "advanced"
    //});
    //actTime();
});

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
        $('basica02').morph('top: -350px',{duration: .7});
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
    if($('inicio').visible()==true)$('inicio').hide();
    if($('calendar').visible()==true)$('calendar').hide();
    if($('chat').visible()==true)$('chat').hide();
    if($('contact').visible()==true)$('contact').hide();
    if($('notas').visible()==true)$('notas').hide();
    $$('.admin-suboptions').invoke("hide");
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

function actTime(){
  new Ajax.PeriodicalUpdater("time","Restaurante/desktop/actTime",{
    method: 'get', frequency: 1,
    onSuccess: function(transport){
      response = transport.responseText.evalJSON();
      $('content-time').innerHTML = response;
      countNot();
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
            $('content-numnoti').innerHTML = response;
            if(usuario_act!="")showListChatInfo();
        },
        onFailure: function(transport){
            alert(transport.responseText);
        }
    });
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
            $('nota').value = response;
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
            nota: $F('nota')
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
    $('nota').value="";
    $('nota').focus();
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
*   Funcion para mostrar el Calendario 
*/

function showListCalen(tipo){
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
            ano: ano
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

/*
*   Funcion para mostrar/ocultar el menu o iconos
*/
function clickBtnInicio(){
    if($('inicio').visible()){
        hideInicio('inicio');
    }else{
        showInicio('inicio');
    }
}
/*
*   Funcion para ocultar el menu de inicio
*/
function hideInicio(name){
    $(name).hide();
    if(name=='inicio') $('btn-inicio').removeClassName('press');
}
/*
*   Funcion para mostrar el menu de inicio
*/
function showInicio(name){
    $('inicio').hide();
    $('notas').hide();
    $('calendar').hide();
    $('chat').hide();
    $('contact').hide();
    $(name).show();
    if(name=='inicio') $('btn-inicio').addClassName('press');
}
/*
 * Funcion para Crear la ventana de la Vista
*/
function newWindow(id,title,help,width,height){
    if($(id)!=null){
        alert("Ya Tiene Esa Opcion Abierta");
        manager.getWindow($(id)).center().bringToFront();
        return;
    }
    UI.Window.addMethods({
        cerrar_ventana: function(){
            this.destroy();
        }
    });
    manager = new UI.WindowManager({
        container: 'user-bg'
    });
    var ventana = new UI.Window({
        id: id,
        theme: 'leopard',
        //minimize: true,
        //maximize: true,
        //activeOnClick: false,
        //superflousEffects: true,
        close: 'cerrar_ventana',
        width: width,
        height: height
    });
    ventana.center().setHeader(title).show();
    ventana.activate();
    manager.register(ventana);
    traerCap(id,ventana,help);
    closeAll();
}

function traerCap(url,ventana,help,condi){
    new Ajax.Request(Utils.getKumbiaURL(url), {
        onLoading: function(transport){
            ventana.setContent("<div class='loading'><img src='"+$Kumbia.path+"img/ajax-loader.gif' title='Cargando' alt=''></div>").show();
        },
        parameters: {
            url: url,
            help: help,
            condi: condi
        },
        onSuccess: function(transport){
            response = transport.responseText;
            ventana.setContent(response).show();
        },
        onFailure: function(transport){
            alert(transport.responseText);
        }
    });
}

function showSubMenu(elem){
    $$('.admin-suboptions').invoke("hide");
    elem.next().show();
}

function seleccionarFila(elem_pri){
    elem_sub = elem_pri.next();
    if(!verOpciones) return;
    verOpciones = false;
    if(!elem_sub.visible()){
        elem_sub.show();
        elem_pri.addClassName("active");
        verOpciones = true;
    } else {
        elem_pri.removeClassName("active");
        elem_sub.hide();
        verOpciones = true;
    }
}

function redirect(url,ventana,help){
    ventana = manager.getWindow(ventana);
    traerCap(url,ventana,help);
}


function edit_default(url,ventana,help,condi){
    ventana = manager.getWindow(ventana);
    traerCap(url,ventana,help,condi);
}

function help(elem){
    if(elem.next().visible())
        $(elem.next()).fade();
    else
        $(elem.next()).appear();
}

function valide_pk(model){
    var pk = eval("pk_"+model);
    var valores = "";
    for(var i=0;i<pk.length;i++){
        valores+=$F(pk[i])+"|";
    }
    new Ajax.Request(Utils.getKumbiaURL("desktop/valide_pk"), {
        parameters: {
            valores: valores,
            model: model
        },
        onSuccess: function(transport){
            response = transport.responseText.evalJSON();
            if(response==false){
                Messages.display(["El Registro ya Existe"],'warning');
                $(pk[0]).focus();
            }
        },
        onFailure: function(transport){
            alert(transport.responseText);
        }
    });
}

function guardar_default(url,model,help){
    var valide = eval("(typeof val_"+model+" == 'undefined') ? '' : val_"+model+";");
    var fl = eval("(typeof fl_validator_"+model+" == 'undefined') ? false : fl_validator_"+model+";");
    if(valide!='' && fl==true){
        if(!valide.valide()){
            Messages_Validator.ver(valide);
            return;
        }
    }
    ajaxRemoteForm($("form_"+model), "content",{
        success: function(transport){
            response = transport.responseText.evalJSON();
            if(response['flag']==true){
                ventana = manager.getWindow(model);
                traerCap(model+"/index",ventana,help);
                Messages.display([response['msg']],'success');
            }else
                Messages.display([response['msg']],'warning');
        }
    },model);
}

function generar_default(controller,action,type){
    if(type=='P'){
        ajaxRemoteForm($("form_"+action), "content",{
            success: function(transport){
                response = transport.responseText.evalJSON();
                ventana = manager.getWindow(controller+"/"+action);
                traerCap(controller+"/"+action,ventana,help);
                if(response['flag']==true){
                    Messages.display([response['msg']],'success');
                }else{
                    Messages.display([response['msg']],'warning');
                }
            }
        },action);
    }else{
        $("form_"+action).submit();
    }
}

function remove_default(controller,condi,elem){
    var con = confirm("Esta seguro que desea borrar el registro ?");
    if(con==false || con=="")return;
    new Ajax.Request(Utils.getKumbiaURL(controller+"/borrar"), {
        parameters: {
            condi: condi
        },
        onSuccess: function(transport){
            response = transport.responseText.evalJSON();
            if(response==true){
                elem.up("tr").remove();
                Messages.display(["Borrado Exitoso"],'success');
            }else{
                Messages.display(["No se pudo borrar el registro"],'warning');
            }
        },
        onFailure: function(transport){
            alert(transport.responseText);
        }
    });
}

function paginate(controller,model,option,query,pagina){
    new Ajax.Request(Utils.getKumbiaURL(controller+"/buscar"), {
        asynchronous: false,
        parameters: {
            model: model,
            option: option,
            query: query,
            pagina: pagina
        },
        onSuccess: function(transport){
            response = transport.responseText;
            ventana = manager.getWindow(model);
            var ref = $(ventana.content).select('.capture-index')[0].previous();
            $(ventana.content).select('.capture-index')[0].remove();
            $(ref).insert({after: response});
            //traerCap(url,ventana,help);
        },
        onFailure: function(transport){
            alert(transport.responseText);
        }
    });
}

function report_default(url){
    //if(Object.isElement())
    //console.log(manager.getFocusedWindow());
    //window.location = Utils.getKumbiaURL(url)+'?url='+url;
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

/*
 * Mensajes al usuario
 */
var Messages = (function(){
        function destroy(elem){
            if(Object.isElement(elem)){
                elem.remove();
            }
        }
        function create(messages,clase){
            destroy($('-messages'));
            var div = new Element('div',{id: '-messages', class: clase, style: 'display: none'});
            var html = '<table><tr><td>';
            html += '<img src="'+$Kumbia.path+'/img/msg/'+clase+'.png" width="35px"></td>';
            html += '<td><ul>';
            for(var i = 0, j = messages.length; i < j; i++){
                html += '<li>' + messages[i] + '</li>';
            }
            html += '</ul>';
            html += '</td></tr></table><p>click para ocultar<p>';
            document.body.appendChild(div.update(html));
            $('-messages').observe('click',function(){
                $('-messages').morph('right: -300px;',{propertyTransitions: {left: 'easeInBack'}, duration: 1.4, after: function(a){a.element.remove();}})
            });
        }
        function display(messages,clase){
            create(messages,clase);
            var elem = $('-messages');
            h = elem.getHeight()+20;
            var zindex = getMaxZindex() + 10;
            elem.setStyle('display: block;bottom: -'+h+'px;opacity: 0;z-index:'+zindex+'')
                .morph('bottom: 50px; opacity: 1',{propertyTransitions: {bottom: 'bouncePast', opacity: 'linear'}, duration: 1.5});
            q = function(){
                if(Object.isElement($('-messages'))){
                    $('-messages').morph('opacity: 0',{ delay: 1, duration: 2, after: function(a){a.element.remove();}
                            });
                }
            }
            //q.delay(8);
        }
        return {
            display: display
        }
})();

function firstFocus(model){
    ventana = manager.getWindow(model);
    var elems = $(ventana).content.select('input[type!=hidden]:not([readonly])');
    elems[2].focus();
}

function disabledInput(model,name){
    ventana = manager.getWindow(model);
    var elems = $(ventana).content.select('input[id='+name+']');
    elems[0].writeAttribute('readOnly',true);
}

function valMaxlength(evt,element,model){
    var mx = element.readAttribute("maxlength");
    if((parseInt(evt.keyCode)>=48 && parseInt(evt.keyCode)<=57) || (parseInt(evt.keyCode)>=65 && parseInt(evt.keyCode)<=90) || (parseInt(evt.keyCode)>=97 && parseInt(evt.keyCode)<=122)){
        if((parseInt(element.value.length)+1)<=parseInt(mx))return;
        ventana = manager.getWindow(model);
        var target = evt.target;
        //var tmp = $$("form");
        //var elems = tmp[0].elements;
        var elems = $(ventana.content).select('input[type!=hidden]');
        for(i=0;i<elems.length;i++){
            if(elems[i] == target) break;
        }
        do{
            i++;
            if(i >= elems.length) i = 0;
        }while(!elems[i].visible() || elems[i].type == 'hidden' || elems[i].disabled);
        elems[i].focus();
    }
}

function searchSeeMore(Imore){
    $(Imore).next().show();
    $(Imore).next().select('li')
    .findAll(function(e){return !e.hasClassName('separator');})
    .invoke('observe','click',function(evt){
        var e = evt.element();
        $(Imore).previous('.i-current').update(e.innerHTML + ':');
        $(Imore).previous('.w-help').store('search',e.readAttribute('param')).focus();
        searchRemoveList(Imore);
    });
}
function searchRemoveList(Imore){
    var more = $(Imore).next('.w-list');
    if(more) more.hide();
}
function searchSetRequest(search, controller, model){
    var query = '1=1';
    var field = $(search).up().down('.w-help').retrieve('search');
    var value = $(search).up().down('.w-help').value;
    if(value!=''){
        if(field=='all' || field==undefined){
            query = [];
            var j = 0;
            $(search).up().down('.w-list').select('li[param]:not(:first-child)').each(function(e){
                query[j++] = e.readAttribute('param')+" LIKE '%"+value+"%'";
            });
            query = query.join(' OR ');
        }else{
            query = field+' LIKE "%'+value+'%"';
        }
    }
    paginate(controller,model,2,query,1);
}
function searchPressKey(search, controller, model, evt){
    if(evt.keyCode==13){
        searchSetRequest(search, controller, model);
    }
}
function irPag(controller,model,option,query,help,totpag,elem,evt){
    if(evt.keyCode!=undefined && evt.keyCode==13){
        if($F(elem)>totpag){
            $(elem).value = totpag;
        }
        var pagina = $F(elem);
        paginate(controller,model,option,query,pagina,help);
    }else if(evt.keyCode==undefined){
        if($F(elem)>totpag){
            $(elem).value = totpag;
        }
        var pagina = $F(elem);
        paginate(controller,model,option,query,pagina,help);
    }
}
/*
 *  * Funciones para las pestanhas
 *   *
 *    */
var Tabs = {
    setActiveTab: function(element, number){
        if(element.hasClassName("active_tab")){
            return;
        } else {
            element.removeClassName("inactive_tab");
        }
        $$(".active_tab").each(function(tab_element){
            tab_element.removeClassName("active_tab");
        });
        $$(".tab_basic").each(function(tab_element){
            if(tab_element==element){
                tab_element.addClassName("active_tab");
            } else {
                tab_element.addClassName("inactive_tab");
            }
        });
        $$(".tab_content").each(function(tab_content){
            if(tab_content.id!="tab"+number){
                tab_content.hide();
            }
        });
        $("tab"+number).show();
        /*var campos = $$(".campo_formulario"+number);
        campos.each(function(element){
            element.enable();
        });*/
    }
};

/*
 *  Funcion que controla el arrastre de las ventanas
 */
function verifyDrag(evt){
    var mouseEvent = evt.memo.mouseEvent;
    var windowElement =  mouseEvent.srcElement.offsetParent;
    if(windowElement!=null && windowElement.offsetLeft <=5){
        windowElement.setStyle({
            left: '5px'
        });
    }else{
        console.log(mouseEvent);
    }
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
