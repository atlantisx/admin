/*
 * File:		atlantis.angular.js
 * Version:    	1.0
 * Package: 	Core
 * Module: 		Angular
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


/***************************************************************************************************
 * Atlantis Angular Modules
 * 
 ***************************************************************************************************/
var ansg_module = [
	//'ui',
	//'filters',
	//'global.service',
	//'api.service',
	//'rest.service',
	//'statement.service',
	//'dialog.service',
	//'form.directive',
	//'form.service',
	//'dialog.directive'
	//'grid.service',
	//'grid.directive',
];


/***************************************************************************************************
 * Atlantis Angular Application : ASNG
 * 
 ***************************************************************************************************/
var asng = angular.module(
	'asng', 
	ansg_module, 
	function($interpolateProvider ) {
		$interpolateProvider.startSymbol('[[');
		$interpolateProvider.endSymbol(']]');
	}
);


/***************************************************************************************************
 * Angular Configs
 *
 ***************************************************************************************************/
asng.value('ui.config', {
	jq: {
	    autoNumeric: {
	    	aSep: '',
	    	mDec: '3',
	     	wEmpty: 'zero',
	     	vMax: '9999999999.999',
	     	vMin: '-9999999999.999',
	     	aForm: true
	    }
	}
});


/*
 *  Notes
 * 

//===================== Getting scope, controller and injector 

var selector = angular.element(some_dom_element);

var scope = selector.scope();
var controller = selector.controller();
var injector = selector.injector();

var scope = angular.element(dialog_elem).scope();


//===================== Compiling

ng_controller = $(raw_html).find('#transaction-panel');
var topScope = angular.element(document).scope();
html = $compile(data)(topScope);
html = $compile(data)(scope);


//===================== Compiling inside scope

$compile( $('#dialog-content') )(scope);
angular.compile($('#dialog-content'))(); //!! Not sure


//===================== Creating directive

atlantisng.directive("helloWorld", function() {
  return {
    restrict: "E",
    scope: {
      name: "@name"
    },
    template: "<div>a {{name}} a</div>"
  };
});


//===================== Compile directive

var compile = angular.module('compile', [], function($compileProvider) { 
	$compileProvider.directive('compile', function($compile) {
		return function(scope, element, attrs) { 
			scope.$watch( 
				function(scope) {
					return scope.$eval(attrs.compile);
				},
				function(value) {
					element.html(value);
					$compile(element.contents())(scope);
				}
			)
		}
	})
});


*
* End Notes
*/

