/*
 * File:		alert.js
 * Version:    	1.0
 * Package: 	Core
 * Module: 		Alert
 * Function:	Provide an alert API and functionality.	  
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

	_a.alert = {
		server: function(response){
			if(response != undefined) {
				if( response.status == '1' || response.status == 'success'){
					this.success(response.message);
				} else {
					this.error(response.message);
				}
			} else {
				this.error('Fatal error!');
			}
		},
		error: function(message, modal, callback){
			if(typeof(modal)==='undefined') modal=false;
			this.custom(message, 'error', 'topRight', modal, false, callback);
		},
		success: function(message, modal, callback){
			if(typeof(modal)==='undefined') modal=false;
			this.custom(message, 'success', 'topRight', modal, 3000, callback);
		},
		progress: function(){
			var notyID = noty({
				text: 'Loading..',
				layout: 'top',
				type: 'alert',
				timeout: false,
				modal: false,
				template: '<div class="spinner" style="margin: 5px;"></div>'
			})
		},
		confirm: function(message, modal, successFunc){
			var notyID = noty({
				text: message,
				layout: 'center',
				type: 'warning',
				timeout: false,
				modal: modal,
			    buttons: [
			      {addClass: 'btn btn-primary', text: 'Ok', click: function($noty) {
			          $noty.close();
			          successFunc();
			          return true;
			        }
			      },
			      {addClass: 'btn btn-danger', text: 'Cancel', click: function($noty) {
			          $noty.close();
			          return false;
			        }
			      }
			    ]
			});
		},
		custom: function(message,type,layout,modal,timeout,callback){
			if(typeof(modal)==='undefined') modal=false;
			
			var notyID = noty({
				text: message,
				layout: layout,
				type: type,
				timeout: timeout,
				modal: modal,
				onClose: callback,
			})
		},
		clear: function(){
			$.noty.closeAll();
		}
	};
	
	
	_a.validation = {
		alert: function(errors){
			if( errors == undefined ) return false;

			$.each( errors, function(key,value){
		        control = $('#'+key);
		        if( key == '_external' ){
		        	$.each( value, function(skey,svalue){
		        		scontrol = $('#'+skey);
		        		scontrol.validationEngine('showPrompt', svalue, 'error','topRight',true);
		        	});
		        	return true;
		        }
		        if( control.hasClass('chzn-select')){
		            control = $('#'+key+'_chzn');
		        }
		        /*if( control.get(0).tagName == 'SELECT'){
                    control = $('#'+key+'-button');
                }*/
		        control.validationEngine('showPrompt', value, 'error','topRight',true);
		    });
		},
		clear: function(form){
			$(form).validationEngine('hide');
			$(form).find('select').each(function (index, domEle) {
				if( $(domEle).hasClass('chzn-select') ){
					
					control = $('#'+domEle.id+'_chzn');
					control.validationEngine('hide');
				}
			});
		}
	};

})(_a);
