/*
 * File:		atlantis-form.js
 * Version:    	1.0
 * Package: 	Core
 * Module: 		Form
 * Function:	Provide a form API and functionality.	  
 * Author:     	Azri Jamil | azri{at}nematix.com
 * Info:       	system.nematix.com/atlantis
 * 
 * Copyright 2012 Nematix Technology, all rights reserved.
 *
 * This source file is free software, under either the GPL v2 license or a
 * BSD style license.
 * 
 * This source file is distributed in the hope that it will be useful, but 
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY 
 * or FITNESS FOR A PARTICULAR PURPOSE. See the license files for details.
 * 
 * 
 */


(function(_a) { 
	
	function getIO($this){
		data = $this.data('atlantis-form');
		return data.options;
	}
    
	function setIO($this,io){ 
		data = $this.data('atlantis-form');
		
		if ( !data ) {
			$this.data('atlantis-form', {
		       target : $this,
		       options : io
			});
		}
	}
	
	
	forms = {
		init: function (options) {
			var options = options || {};
			var io = $.extend({}, _a.fn.form.defaults, options);
			
			return $.each(this, function(i){
				var $this = $(this);
				setIO($this,io);
				/*data = $this.data('atlantis-form');
				
				if ( !data ) {
					$(this).data('atlantis-form', {
				       target : $this,
				       options : io
					});
				}*/
				
				// Load form data
			})
		},
		test: function(){
			return $.each(this, function(i){
				var $this = $(this),
					io = getIO($this);
				
				console.log(io);
				return io;
			});
		},
    }
	
	_a.fn.form = function(method) {
		if (forms[method]) {
			return forms[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || !method) {
			return forms.init.apply(this, arguments);
		} else {
			$.error('Method ' + method + ' does not exist on atlantis.form');
		}
	};
	  
	  
	_a.fn.form.defaults = {
		get_url: appBase + 'api/table',
		get_param: '',
		update_url:  appBase + 'api/table',
		update_param: '',
		model: '',
		test: '',
		filters: {}
	}
})(_a);


function selectLoadOptions(target,url,data,defaultOption,successFunc){
    if( defaultOption != '' ){
    	$(target).empty().append('<option value="">' + defaultOption + '</option>');
    } else {
    	$(target).empty();
    }
    
    $.get(url,data,function(data){
        if(data.options != ''){
            $(target).append(data['options']);
            if( typeof(successFunc) == 'function' ) successFunc();
        }
    });
}


function selectUiRedraw(target){
	$(target).selectmenu('destroy');
    $(target).selectmenu({
        style: 'dropdown',
        transferClasses: true,
        width: null
    });
}


function disableFormElements(form){
	$(form).find('input,select').each(function (index, domEle) {
		$(domEle).prop('disabled', true);
		if( $(domEle).hasClass('chzn-select') ){
			control = $('#'+domEle.id+'_chzn');
			control.addClass('chzn-disabled');
		}
	});
}