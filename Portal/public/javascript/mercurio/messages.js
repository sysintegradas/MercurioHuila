/*
 * Mensajes al usuario
 */

var Messagespro = (function(){
        function valide(val){
            var msg = new Array();
            errores = val.getErrorMessages();
            for(var i=0;i<errores.length;i++){
                msg [i] = errores[i].msg;
            }
            new Effect.Highlight($$(errores[0].selector)[0], { startcolor: '#FF9999',endcolor: '#FFFFFF',queue: 'end', scope: 'detalle'});
            display(msg,"error");
            $$(errores[0].selector)[0].focus();
        }
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
            if(!Object.isArray(messages)){
                messages = [messages];
            }
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
        function ver(validator){
            valide(validator);
        }
        return {
            display: display,
            valide: valide,
            ver: ver
        }
})();


var Messages = (function(){
        function destroy(elem){
            $(elem).remove();
        }
        function create(messages,clase){
            destroy($('#-messages'));
            var html = "<div id='-messages' class='"+clase+"' style='display: none;'>";
            html += '<table><tr><td>';
            html += '<img src="'+$Kumbia.path+'/img/msg/'+clase+'.png" width="35px"></td>';
            html += '<td><ul>';
            for(var i = 0, j = messages.length; i < j; i++){
                html += '<li>' + messages[i] + '</li>';
            }
            html += '</ul>';
            html += '</td></tr></table><p>click para ocultar<p>';
            html += '</div>';
            $(document.body).append(html);
            $('#-messages').click(function(){
                $('#-messages').fadeOut();
            });
        }
        function display(messages,clase){
            if(!$.isArray(messages)){
                messages = [messages];
            }
            create(messages,clase);
            var elem = $('#-messages');
            h = elem.height()+20;
            var zindex = getMaxZindex() + 10;
            elem.css('display: block;bottom: -'+h+'px;opacity: 0;z-index:'+zindex+'');
            elem.fadeIn();
            //elem.morph('bottom: 50px; opacity: 1',{propertyTransitions: {bottom: 'bouncePast', opacity: 'linear'}, duration: 1.5});
        }
        return {
            display: display
        }
})();

