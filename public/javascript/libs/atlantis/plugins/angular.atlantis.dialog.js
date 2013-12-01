/*
 * File:		angular.atlantis.dialog.js
 * Version:    	1.0
 * Package: 	Component\Atlantis
 * Module: 		Atlantis Dialog
 * Function:		  
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



var dialogService = angular.module('dialog.service', []);
var dialogDirective = angular.module('dialog.directive',[]);


/***************************************************************************************************
 * Dialog : Service
 * 
 ***************************************************************************************************/
dialogService.factory('Dialog', function($http, $compile, $rootScope){
	var dialogService = {};
	dialogService.load = function(url, scope){
		var child_scope;
		
		$("#dialog:ui-dialog").dialog( "destroy" );
		  
		$http.get(url).success(function (data) {
			raw_html = $('#dialog-content').html(data.content);
			new_element = $compile(raw_html)(scope);
			child_scope = angular.element(new_element).scope();

			$("#dialog").dialog({
				modal: 		true,
				title: 		data.title,
				show: 		'fade',
	            width: 		'auto',
	    		buttons: [{
	    			'text'	: 'Ok',
	    			'id'	: 'btnOk',
	    			click	: function(event) {
	    				$rootScope.$broadcast('DialogButtonOk');
	    				if (typeof (child_scope.onDialogButtonOk) == 'function') {
	    					ret = child_scope.onDialogButtonOk();
	    					if( ret != false ) $( this ).dialog('close');
	    				}
	    			}
	    		}],
	            close: function(){
	            	//[i] Broadcast event
	            	$rootScope.$broadcast('DialogClose');
	            	
	            	//[i] Execute return on close function
	            	if (typeof (onClose) == 'function') { onClose(); }
	            	
	            	//[i] Destroy instance
	            	child_scope.$destroy();
    				$( this ).dialog('destroy').find('#dialog-content').children().remove();
	            },
	            open: function(){}
	        });
		});
	}
	
	return dialogService;
});


/***************************************************************************************************
 * Dialog : Directive
 * 
 ***************************************************************************************************/
dialogDirective.directive('aDialog', function(Dialog){
	return {
		restrict: 'A',
		link: function postLink(scope, element, attrs){
			var dialog_function = attrs['aDialog'];
			element.bind('click', function(){

			});
		}
	}
});




