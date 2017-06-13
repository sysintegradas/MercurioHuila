function simular(){
  new Ajax.Request(Utils.getKumbiaURL("creditos/simulador"), {
    parameters: {
        tipcre: $F('tipcre'),
        codcat: $F('codcat'),
        valsim: $F('valsim'),
        cuotas: $F('cuotas'),
    },
    onSuccess: function(transport){
        response = transport.responseText.evalJSON();
        $('simular').innerHTML = response;
    },
    onFailure: function(transport){
        alert(transport.responseText);
    }
  });
}

function mostrar_pagos(credito){
  if($(credito)!=null){
      alert("Este número de Crédito ya esta abierto");
      manager.getWindow($(credito)).center().bringToFront();
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
        id: credito,
        theme: 'leopard',
        //minimize: true,
        //maximize: true,
        //activeOnClick: false,
        //superflousEffects: true,
        close: 'cerrar_ventana',
        width: 700,
        height: 300
    });
    ventana.center().setHeader("Consulta de Pagos Crédito No."+credito).show();
    ventana.activate();
    manager.register(ventana);
    
    new Ajax.Request(Utils.getKumbiaURL("creditos/detalleCredito"), {
      parameters: {
          credito: credito
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