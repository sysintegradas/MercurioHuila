function mostrar_servicios(codigo){
	if($(codigo)!=null){
      alert("Este número de Servicio ya esta abierto");
      manager.getWindow($(codigo)).center().bringToFront();
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
        id: codigo,
        theme: 'leopard',
        //minimize: true,
        //maximize: true,
        //activeOnClick: false,
        //superflousEffects: true,
        close: 'cerrar_ventana',
        width: 700,
        height: 300
    });
    ventana.center().setHeader("Consulta de Servicio").show();
    ventana.activate();
    manager.register(ventana);
    
    new Ajax.Request(Utils.getKumbiaURL("servicios/detalleServicio"), {
      parameters: {
          codigo: codigo
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