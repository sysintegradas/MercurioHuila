
var Detail = Class.create({
	
	initialize: function(params){
		this._options = Object.extend({view: ''},params || { });
		$$('#'+this._options.view+' tbody')[0].addClassName('detalle__');
		this._options.selectorRow = '#'+this._options.view+' tbody.detalle__ tr.row__';
		$$('#'+this._options.view+' tbody.detalle__')[0].childElements().each(function(element){
			element.addClassName('row__');
		});
		if(Selector.findChildElements($$(this._options.selectorRow)[0], ['input[type=hidden]']).length <= 0){
			this.___row = $$(this._options.selectorRow)[0];
			$$(this._options.selectorRow)[0].remove();
		}
	},
	
	getEditRow: function(){
		return this._editRow;
	},
	
	getView: function(){
		return this._options.view;
	},
	
	getFields: function(){
		return this._options.fields;
	},
	
	getClassTr: function(){
		return this._options.classTr;
	},
	
	getSelectorRow: function(){
		return this._options.selectorRow;
	},
	
	getNumRows: function(){
		if(!Object.isUndefined(arguments[0])){
			return $$(this._options.selectorRow+'.'+arguments[0].toLowerCase()+'__').length;
		} else {
			return $$(this._options.selectorRow).length;
		}
	},
	
	getRow: function(index){
		if(this.getNumRows() < index) throw "Detail.getRow\n * El numero de filas es menor al deseado.";
		e = $$(this._options.selectorRow)[index - 1];
		if(Object.isUndefined(arguments[1]) || (!Object.isUndefined(arguments[1]) && arguments[1]==true)){
			result = new Object();
			this._options.fields.each(function(field){
				result[field] = $F(Selector.findChildElements(e,['.'+field+'_ input[type=hidden]'])[0]);
			});
			return result;
		} else{
			return e;
		}
	},
	
	getColumn: function(name){
		if(this.getNumRows() <= 0) throw "Detail.getColumn\n * El numero de filas es 0.";
		if(Object.isUndefined(arguments[1]) || (!Object.isUndefined(arguments[1]) && arguments[1]==true)){
			result = [];
			$$('#'+this._options.view+' .'+name+'_ input[type=hidden]').each(function(field,index){
				result[index] = $F(field);
			});
			return result;
		} else {
			return $$('#'+this._options.view+' .'+name+'_');
		}
	},

	save: function(){
		_save = !Object.isUndefined(arguments[0]) ? arguments[0] : { };
		if(this._editRow!=undefined && Object.keys(this._editRow).length > 0){
			element_sv = this._editRow;
		}else{
			if(_save['element']){
				element_sv = _save['element'];
			} else {
				element_sv = this.getRowBase();
			}
			element_sv.addClassName(Object.isArray(this._options.classTr) ?
				(this._options.classTr[!this.getNumRows()==0 ? this.getNumRows()%this._options.classTr.length : 0]) : '');
			element_sv.show();
			$$('#'+this._options.view+' tbody.detalle__')[0].appendChild(element_sv);
		}
		if(_save['beforeSave']){
			if(_save['beforeSave'](element_sv)===false) return false;
		}
		this._options.fields.each(function(k){
            var valor = k ? Bs.getValue($(k))['value'] : "";
            valor = $(k).getAttribute('format') == null ? valor : this._options.format.deFormat(valor ,$(k).getAttribute('format'));
			fieldHidden = "<input type='hidden' id='"+k+"[]' value='"+(valor)+"' name='"+k+"[]'  />";
			Selector.findChildElements(element_sv,['.'+k+'_'])[0].update(fieldHidden + Bs.getValue($(k))['text']);
			$(k).value = ($(k).type == 'text') || ($(k).type == 'textarea') || ($(k).type == 'hidden') ? '' : '@'; //Por mejorar, para todos los campos
            if($(k).type=="select-one"){
                $(k+'_chosen').down('span').innerHTML=$(k).options[$(k).selectedIndex].text;
            }
		}.bind(this));
		if(_save['afterSave']){
			if(_save['afterSave'](element_sv)===false) return false;
		}
		element_sv.removeClassName('_edit');
		this._editRow = { };
	},
	
	edit: function(fila){
		element_ed = Object.isNumber(fila) ? this.getRow(fila) : fila;
		_edit = !Object.isUndefined(arguments[1]) ? arguments[1] : { };
		if(_edit['beforeEdit']){
			if(_edit['beforeEdit'](element_ed)===false) return false;
		}
		if(this._editRow!=undefined && Object.keys(this._editRow).length > 0){
			this._editRow.removeClassName('_edit');
		}
		this._editRow = element_ed;
		this._editRow.addClassName('_edit');
		result = [];
		if(!Object.isNumber(fila)){
			this._options.fields.each(function(field){
				result[field] = $F(Selector.findChildElements(element_ed,['.'+field+'_ input[type=hidden]'])[0]);
			});
		} else {
			result = element_ed;
		}
		if(this._editRow!=undefined && Object.keys(result).length > 0){
			this._options.fields.each(function(k,index){
            			valor = $(k).getAttribute('format') == null ? result[k]: this._options.format.execute($(k).getAttribute('format'),result[k]);
				$(k).value = valor;
                if($(k).type=="select-one"){
                    if($(k).options[$(k).selectedIndex]!=undefined)
                        $(k+'_chosen').down('span').innerHTML=$(k).options[$(k).selectedIndex].text;
                }
				if(!index) $(k).focus();
			}.bind(this));
		}
		if(_edit['afterEdit']){
			if(_edit['afterEdit'](element_ed)===false) return false;
		}
	},
	
	remove: function(fila){
		element_rm = Object.isNumber(fila) ? this.getRow(fila,false) : fila;
		_remove = !Object.isUndefined(arguments[1]) ? arguments[1] : { };
		if(_remove['beforeRemove']) {
			if(_remove['beforeRemove'](element_rm)===false) return false;
		}
		if(this.isEdit(element_rm)) throw 'Detail.remove\n * El elemento actual se encuentra en edicion';
		if(this.getNumRows() == 1){
			!Object.isUndefined($$('#'+this._options.view+' #'+this._options.empty)[0]) ?
				$$('#'+this._options.view+' #'+this._options.empty)[0].show() : Prototype.emptyFunction();
			this.___row = element_rm;
		}
		element_rm.remove();
		if(Object.isArray(this._options.classTr)){
			$$(this._options.selectorRow).each(function(element,index){
				this._options.classTr.each(function(k){
					element.removeClassName(k);
				});
				element.addClassName(Object.isArray(this._options.classTr) ? (this._options.classTr[!index==0 ? index%this._options.classTr.length : 0]) : '');
			}.bind(this));
		}
		if(_remove['afterRemove']) {
			if(_remove['afterRemove']()===false) return false;
		}
	},
	
	isEdit: function(){
		element_ie = Object.isNumber(arguments[0]) ? this.getRow(arguments[0],false) : arguments[0];
		if(element_ie){
			return element_ie.hasClassName('_edit');
		} else {
			return this._editRow!=undefined && Object.keys(this._editRow).length > 0;
		}
	},
	
	cancelEdit: function(){
		this._editRow.removeClassName('_edit');
		this._options.fields.each(function(k){
			$(k).value = ($(k).type == 'text') || ($(k).type == 'textarea') ? '' : '@'; //Por mejorar, para todos los campos
            if($(k).type=="select-one"){
                $(k+'_chosen').down('span').innerHTML=$(k).options[$(k).selectedIndex].text;
            }
		});
		this._editRow = { };
	},
	
	getRowBase: function(){
		if(this.getNumRows() == 0){
			element_gr = this.___row;			
		} else {
			element_gr = $$(this._options.selectorRow)[0].cloneNode(true);
		}
		if(!Object.isUndefined($$('#'+this._options.view+' #'+this._options.empty)[0]) &&
			$$('#'+this._options.view+' #'+this._options.empty)[0].visible()){
			$$('#'+this._options.view+' #'+this._options.empty)[0].hide();
		} else {
			Selector.findChildElements(element_gr,['input']).each(function(e){
				e.remove();
			});
			if(Object.isArray(this._options.classTr)){
				this._options.classTr.each(function(k){
					element_gr.removeClassName(k);
				});
			}
		}
		return element_gr;
	}
});




var Bs = {
	
	shadow: function(){
		options = {
			position: 'absolute',
			top: '0px',
			left: '0px',
			width: '100%',
			height: '100%',
			background: '#000000',
			opacity: 0.0,
			zIndex: '100' };
		opacity = { to: 0.6 };
		obj = !Object.isUndefined(arguments[0]) ? arguments[0] : { };
		Object.extend(options,obj);
		obj = !Object.isUndefined(arguments[1]) ? arguments[1] : { };
		Object.extend(opacity,obj);
		divId = arguments[2] || 'divOpaco__';
		element = $(divId);
		if(!element){
			element = new Element('div');
			element.id = divId;
			element.setStyle(options);
			document.body.appendChild(element);
		}else{
			element.setStyle(options);
		}
		return new Effect.Opacity(element,opacity);
	},
	
	getDimensionsView: function() { //De Prototype version 1.6.0 - Por compatibilidad con I.E. 6.0
		var dimensions = { };
		$w('width height').each(function(d) {
			var D = d.capitalize();
			dimensions[d] = self['inner' + D] || (document.documentElement['client' + D] || document.body['client' + D]);
		});
		return dimensions;
	},
	
	getValue: function(element){ //Falta resto de tipos de input
		result = [];
		switch (element.type){
			case "select-one":
                if(element.value=="@" || element.value==""){
                    result['value'] = "@";
                    result['text'] = "";
                }else{
                    result['value'] = element.value;
                    result['text'] = element.options[element.selectedIndex].text;
                }
                break;
			case "text":
			case "number":
			case "textarea":
			case "hidden": result['value'] = element.value; result['text'] = element.value; break;
			default: result['value'] = ''; result['text'] = ''; break; 
		}
		return result;
	},
	
	getKey: function(evt,action){
		var key;
		evt = (evt) ? evt : ((window.event) ? window.event : null);
		if(document.all) {
			key = event.keyCode
		} else {
			key = evt.keyCode
		}
		action['action'](key);
	}

}
