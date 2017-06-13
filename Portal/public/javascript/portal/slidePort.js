Event.observe(document, 'dom:loaded', function(){
  setInterval('slidePort()',5000);
  slidePort();
});

function slidePort(elem){
  var numero = "";
  if(elem != undefined){
    numero = elem.readAttribute('numeroBanner');
  }
  new Ajax.Request(Utils.getKumbiaURL("portal/slideport"), {
      parameters: {
        numBanner: numero
      },
      onSuccess: function(transport){
          var response = transport.responseText.evalJSON();
          $('banner_portada').innerHTML=response[1];
          $$('.puntoImgSelected').each(function(elem){
            elem.removeClassName('puntoImgSelected');
          });
          $$('[numerobanner='+response[0]+']').each(function(elem){
            elem.addClassName('puntoImgSelected');
          });
      },
      onFailure: function(transport){
          alert(transport.responseText);
      }
  });
}