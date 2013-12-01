/*
 * File:		gui.js
 * Version:    	1.0
 * Package: 	Core
 * Module: 		GUI
 * Function:	Provide an GUI API and functionality.	  
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
	var lib_name = 'atlantis-gui';
	
	function debug(info){
		var $that = $(this);
		io = getIO($that);

		if( io.debug == true )	{
			console.log( '(Atlantis) ' + info);
			return true;
		}
	}
	
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
	
	
	function initParseValidation($this,io){
		debug('Validation starting');

		/*inputs = this.$('input');
		$.each(inputs, function(i){
			console.log(this);
		});*/
	}
	
	
	function initParseScript($this,io){
		debug('Inline script parsing');
		
		var inputs,
			$that = $(this);
		io = getIO(lib_name);
	}
	
	
	gui = {
		init: function (options) {
			var options = options || {};
			var io = $.extend({}, _a.fn.gui.defaults, options);
			
			var $this = $(this);
			setIO(lib_name, io);
			debug('Core GUI');

		    $(window).resize(function() {
		    	var leftWorkspaceMargin = 1;
		    	var bottomWorkspaceMargin = 256; //254
		    	
		    	if( $.browser.mozilla == true ) {
		    		leftWorkspaceMargin = 0;
		    		bottomWorkspaceMargin = bottomWorkspaceMargin - 1;
		    	}
		    	
		    	if( $('#navigation').css('display') != 'none' ) leftWorkspaceMargin = leftWorkspaceMargin + 200;
		    	
		  		$('#workspace').height(  $(window).height() - bottomWorkspaceMargin);
		  		$('#workspace').width(  $(window).width() - leftWorkspaceMargin);
		  		
		  		$('#workspace.reporting').height(  $(window).height() - (bottomWorkspaceMargin + 42) );
		  		$('#workspace.reporting').width(  $(window).width() - leftWorkspaceMargin );
			});
			
			if(io.parse_validation == true) initParseValidation($this,io);
			if(io.parse_script == true) initParseScript($this,io);
			
			return $this;
		},
		debug: function(info){
			return debug(info);;
		},
		refresh: function(){
			var $this = $(this);
			io = getIO(lib_name);
			
			if(io.parse_validation == true) initParseValidation($this,io);
			if(io.parse_script == true) initParseScript($this,io);
		}
    }

	_a.fn.gui = function(method) {
		if (gui[method]) {
			return gui[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || !method) {
			return gui.init.apply(this, arguments);
		} else {
			$.error('Method ' + method + ' does not exist on atlantis.form');
		}
	};

	_a.fn.gui.defaults = {
		e: {
			inputs: {}
		},
		debug: false,
		parse_validation: true,
		parse_script: true
	}
	
	_a.head = {
		scripts: function(uri, successFunction){
		    url = appBase + 'default/head'
			data = 'type=scripts&uri=' + uri;
			$.post(url,data,function(response){
		        if(response.status == '1'){
		        	successFunction(response.data);
		        } else {
		        	successFunction();
		        }
		    });
		}
	}
	
})(_a);


$(document).ready(function() {
	_a(document).gui('init', {debug:true});
})

