Event.observe(document, 'dom:loaded', function(){
  importScript($Kumbia.path+"public/javascript/portal/messages.js");
  importScript($Kumbia.path+"public/javascript/portal/creditos.js");
  importScript($Kumbia.path+"public/javascript/portal/servicios.js");
  if($('img-off')!=undefined)$('img-off').observe('click',clickClsSesion);
  if($('img-cuadros')!=undefined)$('img-cuadros').observe('click',clickOption);
  changeSelect();
});

function actHelp(help){
  //$('help-views').innerHTML = help;
}
function showMsg(msg,mclass){
    window.setTimeout("Messages.display(Array('"+msg+"'),'"+mclass+"');","1000")
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

function clickClsSesion(){
    new Ajax.Request(Utils.getKumbiaURL("login/ClsSesion"), {
        parameters: {
        },
        onSuccess: function(transport){
            response = transport.responseText.evalJSON();
            window.location = Utils.getKumbiaURL("login/index"); 
        },
        onFailure: function(transport){
            alert(transport.responseText);
        }
    });
}

function traerOpcion(url,elem,codare,codope){
    new Ajax.Request(Utils.getKumbiaURL(url), {
        parameters: {
            codare: codare,
            codope: codope
        },
        onLoading: function(transport){
            d = new Element('div',{id: 'o',style: 'width: 100%;height: 100%; background: rgba(60,60,60,0.8);position: fixed;top:0;left:0;z-index:2000;color: #fff'});
            d.innerHTML = "<table class='load-table'><tr><td align='center'><img src='"+$Kumbia.path+"img/ajax-loader.gif' title='Cargando' alt=''></td></tr><tr><td align='center' class='load-text'>Cargando</td></tr></table>";
            $$('body')[0].insert(d);
            /*$('vista').innerHTML = "<table class='load-table'><tr><td align='center'><div class='loading'><img src='"+$Kumbia.path+"img/ajax-loader.gif' title='Cargando' alt=''></div></td></tr><tr><td align='center' class='load-text'>Cargando</td></tr></table>";*/
        },
        onSuccess: function(transport){
            if(Object.isElement($('o'))){$('o').remove()}
            response = transport.responseText;
            $('vista').update(response); 
            $$('.menu_hover').each(function(melem){
              melem.removeClassName("menu_hover");
            });
            if(elem!=undefined){
              elem.addClassName("menu_hover");
            }
            Effect.Fade('option');
        },
        onFailure: function(transport){
            alert(transport.responseText);
        }
    });
}

function traerInformacion(elem){
    elem = $(elem);
    new Ajax.Request(Utils.getKumbiaURL("portal/traerInformacion"), {
        parameters: {
            codcaj: elem.readAttribute('codcaj')
        },
        onSuccess: function(transport){
            response = transport.responseText.evalJSON();
            $('informacion-cajas').innerHTML=response;
        },
        onFailure: function(transport){
            alert(transport.responseText);
        }
    });
}

function irlogin(elem){
  elem = $(elem);
  new Ajax.Request(Utils.getKumbiaURL("portal/asignarCodcaj"), {
      parameters: {
          codcaj: elem.readAttribute('codcaj')
      },
      onSuccess: function(transport){
          response = transport.responseText.evalJSON();
          window.location = Utils.getKumbiaURL("login/index");
      },
      onFailure: function(transport){
          alert(transport.responseText);
      }
  });  
}


function clickOption(){
  if($('option').visible()==false){
    //Effect.BlindDown('option');
    Effect.Appear('option');
    //$('option').show();
  }else{
    //$('option').hide();
    Effect.Fade('option');
    //Effect.BlindUp('option');
  }
}

function formContactenos(){

      if($F('name') == "" || $F('subject') == "" || $F('email') == "" || $F('message') == ""){
        Messages.display(Array('Debe completar el formulario','antes de enviarlo.'),'warning');
        return;
      }

  new Ajax.Request(Utils.getKumbiaURL("portal/enviarCorreo"), {
      parameters: {
          nombre: $F('name'),
          asunto: $F('subject'),
          email: $F('email'),
          mensaje: $F('message')
      },

      onSuccess: function(transport){
          response = transport.responseText.evalJSON();
          Messages.display(Array('Su mensaje ha sido enviado uno de nuestros asesores se pondrá en contacto con usted.'),'success');
          $('name').value="";
          $('subject').value="";
          $('email').value="";
          $('message').value="";
      },
      onFailure: function(transport){
          alert(transport.responseText);
      }
  });
}

function mostrarContenedor(id){
  $$('.content_tab').each(function(elem){
    elem.hide();
  });
  $('content-'+id).show();
  $$('.tabSelected').each(function(elem){
    elem.removeClassName('tabSelected');
    elem.addClassName('tab_inactive');
  });
  $('tab-'+id).removeClassName('tab_inactive');
  $('tab-'+id).addClassName('tabSelected');
}

function mostrarVideos(id,vinculo){
	$$('.linkYoutube').each(function(elem2){
		elem2.removeClassName('linkVideosActivo');
	});
	vinculo.addClassName('linkVideosActivo');
    
    $$('.videoTutoriales').each(function(elem){
        elem.hide();
    });
    $('video'+id).show();
}

function newWindow(id,title){
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
        container: 'container_login'
    });
    var ventana = new UI.Window({
        id: id,
        theme: 'leopard',
        //minimize: true,
        //maximize: true,
        //activeOnClick: false,
        //superflousEffects: true,
        close: 'cerrar_ventana',
        width: 500,
        height: 350
    });
    ventana.center().setHeader(title).show();
    ventana.activate();
    manager.register(ventana);
    traerCap(id,ventana);
}

function traerCap(id,ventana){
  new Ajax.Request(Utils.getKumbiaURL("login/noticias"), {
      onLoading: function(transport){
          ventana.setContent("<div class='loading'><img src='"+$Kumbia.path+"img/ajax-loader.gif' title='Cargando' alt=''></div>").show();
      },
      parameters: {
          id: id,
      },
      onSuccess: function(transport){
          response = transport.responseText.evalJSON();
          ventana.setContent(response).show();
      },
      onFailure: function(transport){
          alert(transport.responseText);
      }
  });
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


function validaNumeros(evt){
    evt = (evt) ? evt : ((window.event) ? window.event : null);
		var kc = evt.keyCode;
        if(kc==37 || kc==8)return;
		var ev = ((kc>=48&&kc<=57)||(kc>=96&&kc<=105)||(kc==8)||(kc==9)||(kc==13)||(kc==17)||(kc==36)||(kc==35)||(kc==37)||(kc==46)||(kc==39)||(kc==190)||(kc==110));
		if(!ev){
			ev = (evt.ctrlKey==true&&(kc==67||kc==86||kc==88));
			if(ev){
				ev = (evt.shiftKey==true&&(kc==9||(kc>=35&&kc<=39)));
				if(!ev){
					ev = (evt.altKey==true&&(kc==84||kc==82));
				}
			}
		};
		if(ev){
			evt.preventDefault();
    		evt.stopPropagation();
    		evt.stopped = true;
		}
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
