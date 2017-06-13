function publicar(banner,estado){
    var com = confirm("Esta seguro de cambiar el estado de este banner?");
    if(com==false)return;
    new Ajax.Request(Utils.getKumbiaURL("mercurio26/publicar"), {
      parameters: {
          banner: banner,
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
