/*
 * File:		atlantis.js
 * Version:    	1.0
 * Package: 	Core
 * Module: 		Core
 * Function:	Main API and functionality.	  
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


(function(window, undefined) {
	
	var atlantis = (function() {
		var atlantis = function( selector ) {
			return new atlantis.fn.init( selector );
		};
			
		atlantis.fn = atlantis.prototype = {
				init: function( selector ) {
					var nodes = $(selector).toArray();
					for (var i = 0; i < nodes.length; i++) {
			            this[i] = nodes[i];
			        }
					this.length = nodes.length;

					return this;
				},
				version: "0.0.1"
		}
		
		atlantis.fn.init.prototype = atlantis.fn;
		return (window.atlantis = window._a = atlantis);
	})();
	
	atlantis.utils = {
		validateJSON: function(json_string){
			try {
				var a = JSON.parse(json_string);
				return true;
			} catch(e){
				return false;
			}
		},
		JSONToParam: function(json_object){
			var params = '';
			$.each(json_object, function(key,value){
				params += '&' + key + '=' + value;
			})
			
			return params;
		},
		URLSanitize: function(url, data){

			url_array = url.split('?');
			url_array = _.compact(url_array);
			console.log(url);
			if( url_array.length < 1 ) return false;
			
			data_array = data.split('&');
			data_array = _.compact(data_array);
			
			if( url_array.length == 1 ){
				url = url_array[0] + '?' + data_array.join('&');
			} else {
				url = url_array.shift();
				url = url + '?' + url_array.concat(data_array).join('&');
			}
			
			return url;
		}
	}
	
	function getIO(lib_name){
		var $this = $(this);
		data = $this.data(lib_name);
		return data.options;
	}
    
	function setIO(lib_name, io){ 
		var $this = $(this);
		data = $this.data(lib_name);
		
		if ( !data ) {
			$this.data(lib_name, {
		       target : $this,
		       options : io
			});
		}
	}

})(window);


