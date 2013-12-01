/*
 * File:		atlantis-dialog.js
 * Version:    	1.0
 * Package: 	Core
 * Module: 		Dialog
 * Function:	Provide a dialog / prompt API and functionality.	  
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
	
	_a.prompt =  function(title, message, body, onYes, onCancel){
		$("#dialog:ui-dialog").dialog( "destroy" );
		$("#dialog").attr('title',title);
		
		if( body != ''){
			$(content).show();
			$(content).appendTo("#dialog-content");
		} else {
			$("#dialog-content").html(message);
		}
		//$('<br /><input type="text" id="user_input" value="" class="w90p"/>').appendTo("#dialog-content");
		
		$("#dialog").dialog({
			buttons: {
				"Yes": function() {
					if (typeof (onYes) == 'function') { setTimeout(onYes, 50); }
					$( this ).dialog( "close" );
				},
				Cancel: function() {
					if (typeof (onCancel) == 'function') { setTimeout(onCancel(), 50); }
					$( this ).dialog( "close" );
					return false;
				}
			}
		});
	}
	
	
	_a.dialog = {
		modal: function(title, url, width, onClose){
			$("#dialog:ui-dialog").dialog( "destroy" );
			$("#dialog").attr('title',title);
			
			$('#dialog-content').load(url,function(){
		        $("#dialog").dialog({
		        	modal: true,
		            width: width,
		    		buttons: {
		    			"Ok": function() {
		    				$( this ).dialog( "close" );
		    				return true;
		    			},
		    		},
		            close: function(){
		            	if (typeof (onClose) == 'function') { onClose(); }
		            },
		        });
		    });
		},
		YesNo: function(title, message, onYes){
			$("#dialog:ui-dialog").dialog( "destroy" );
			$("#dialog").attr('title',title);
			$("#dialog-content").html(message);
			
			$("#dialog").dialog({
				modal: true,
				buttons: {
					"Yes": function() {
						if (typeof (onYes) == 'function') { setTimeout(onYes, 50); }
						$( this ).dialog( "close" );
						return true;
					},
					Cancel: function() {
						$( this ).dialog( "close" );
						return false;
					}
				}
			});
		},
		YesNoLocal: function(content,title,message,onYes,onCancel){
			$("#dialog:ui-dialog").dialog( "destroy" );
			$("#dialog").attr('title',title);
			$(content).show();
			$(content).appendTo("#dialog-content");
			
			$("#dialog").dialog({
				buttons: {
					"Yes": function() {
						if (typeof (onYes) == 'function') { setTimeout(onYes, 50) };
						$( this ).dialog( "close" );
					},
					Cancel: function() {
						if (typeof (onCancel) == 'function') { setTimeout(onCancel, 50) };
						$( this ).dialog( "close" );
						$(content).hide();
						return false;
					}
				}
			});
		},
		ajax: function(title,url,width){
			$("#dialog:ui-dialog").dialog( "destroy" );
			$("#dialog").attr('title',title);
			
			$('#dialog-content').load(url,function(){
		        $("#dialog").dialog({
		            width: width,
		    		buttons: {
		    			"Ok": function() {
		    				$( this ).dialog( "close" );
		    				return true;
		    			},
		    		},
		            close: function(){
		                gridView.trigger('reloadGrid');
		            },
		        });
		    });
		}
	}

	function dialogLocalCustom(content,title,buttons,onClose){
		$("#dialog:ui-dialog").dialog( "destroy" );
		$("#dialog-content").html('');
		$("#dialog").attr('title',title);
		var contentParent = $(content).parent();
		
		$(content).show().appendTo("#dialog-content");
		
		$("#dialog").dialog({
			buttons: buttons,
			close: function(){
				$("#dialog-content").children().hide().appendTo(contentParent);
				onClose;
			}
		});
	}
	
	/*
	
	function getIO(){
		var $this = $(this);
		data = $this.data(lib_name);
		return data.options;
	}
    
	function setIO(io){ 
		var $this = $(this);
		data = $this.data(lib_name);
		
		if ( !data ) {
			$this.data(lib_name, {
		       target : $this,
		       options : io
			});
		}
	}
	
	dialog = {
		init: function (options) {
			var options = options || {};
			var io = $.extend({}, _a.fn.gui.defaults, options);
			
			var $this = $(this);
			setIO(io);
			
			
		},
		modal: function(){
			
		}
	}
	
	_a.fn.dialog = function(method) {
		if (dialog[method]) {
			return dialog[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || !method) {
			return dialog.init.apply(this, arguments);
		} else {
			$.error('Method ' + method + ' does not exist on atlantis.dialog');
		}
	};

	_a.fn.dialog.defaults = {
		e: {
			inputs: {}
		},
		title: '',
		url: '',
		width: '600px'
	}*/
})(_a);

