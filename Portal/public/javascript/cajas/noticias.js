function vernoticia(noticia,titulo,descripcion){
	var id = noticia+"noti";
	if($(id)!=null){
      alert("Esta noticia ya esta abierta");
      manager.getWindow($(id)).center().bringToFront();
      return;
  	}else{
    UI.Window.addMethods({
        cerrar_ventana: function(){
            this.destroy();
        }
    });
    manager = new UI.WindowManager({
        container: 'captura'
    });
    
    var ventana = new UI.Window({
        id: id,
        theme: 'leopard',
        //minimize: true,
        //maximize: true,
        //activeOnClick: false,
        //superflousEffects: true,
        close: 'cerrar_ventana',
        width: 700,
        height: 300
    });
    ventana.center().setHeader("Noticia No."+noticia).show();
    ventana.activate();
    manager.register(ventana);
    
    new Ajax.Request(Utils.getKumbiaURL("mercurio29/vent_noticia"), {
      parameters: {
          noticia: noticia,
          titulo: titulo,
          descripcion: descripcion
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
}

function publicar(noticia,estado){
    var com = confirm("Esta seguro de cambiar el estado a esta noticia ?");
    if(com==false)return;
    new Ajax.Request(Utils.getKumbiaURL("mercurio29/publicar"), {
      parameters: {
          noticia: noticia,
          estado: estado,
      },
      onSuccess: function(transport){
          response = transport.responseText.evalJSON();
          location.reload();
      },
      onFailure: function(transport){
          alert(transport.responseText);
      }
    });
}
