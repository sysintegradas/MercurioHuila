Event.observe(document, 'dom:loaded', function(){
  setInterval('slideLogin()',5000);
  slideLogin();
});

function slideLogin(elem){
  var numero = "";
  if(elem != undefined){
    numero = elem.readAttribute('#numeroBanner');
  }
    var request  = $.ajax({
        type: "POST",
        url:Utils.getKumbiaURL("login/slide"),
        data: {
            numBanner: numero
          },
      });
        request.done(function( transport ) {
                var response = jQuery.parseJSON(transport);
                $('#banner_cajas').html(response[1]);
                $('.puntoImgSelected').removeClassName('#puntoImgSelected');
                $('[numerobanner='+response[0]+']').addClassName('#puntoImgSelected');
            });
        });
        request.fail(function( jqXHR, textStatus ) {
            Messages.display(response['msg'],"error");
        });
}

function empresaConsulta(){
  new Ajax.Request(Utils.getKumbiaURL("login/empresa"), {
    parameters: {
        empresa: $F('empresa')
    },
    onSuccess: function(transport){
        response = transport.responseText.evalJSON();
        $('resul_empresa').innerHTML = response;
    },
    onFailure: function(transport){
        alert(transport.responseText);
    }
  });
}

function clasificadoBoton(codcat){

  new Ajax.Request(Utils.getKumbiaURL("login/clasificados"),{
    parameters:{
      codcat: codcat
    },
    onSuccess: function(transport){
      response = transport.responseText.evalJSON();
      $('cla_con').innerHTML = response;
    },
    onFailure: function(transport){
      alert(transport.responseText);
    }
  });
}
