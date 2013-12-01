/*
 * File:		angular.atlantis.form.js
 * Version:    	1.0
 * Package: 	Component\Atlantis
 * Module: 		Atlantis Form
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



var formDirective = angular.module('form.directive',[]);


/***************************************************************************************************
 * Service : Form
 * 
 ***************************************************************************************************/
angular.module('form.service', []).
factory('Forms', function(){
	var formService = {};
	formService.formsData = [];
	
	formService.get = function(){
		return this.formsData;
	}
	
	formService.add = function(formName, scope){
		this.formsData.push( {name:formName, scope: scope} );
	}
	
	formService.ready = function(){};
	
	return formService;
})




/***************************************************************************************************
 * Directive : Form
 * 
 ***************************************************************************************************/
formDirective.directive('asForm', function($rootScope, Statements){
	return {
		restrict: 'E',
		scope:{ share:'=asFormModel' },
		link: function(scope, element, attrs){
			//[i] Create local statement model
			scope.statement = {};
			
			//[i] Defining variable
			var actionUrl = attrs.asFormAction == undefined ? '' : attrs.asFormAction;
			scope.formName = attrs.asFormName;
			scope.formKey = scope.$parent.formKey != undefined ? scope.$parent.formKey + '-' + scope.formName : scope.formName;

			element.ready(function(){
				//[i] Load statement from server
				scope.statement = Statements.Load(scope.share.statement_id,scope.formKey);
				
				//[i] Add statement to share/global model
				if( scope.share.statement == undefined ) scope.share.statement = new Array();
				scope.share.statement[scope.formKey] = scope.statement;
			});
		}
	}
});



/***************************************************************************************************
 * Directive : Form Dialog
 * 
 ***************************************************************************************************/
formDirective.directive('asFormDialog', function($rootScope, $location, Dialog, Statements){
	return {
		restrict: 'EA',
		scope:{ share:'=asFormModel' },
		link: function postLink(scope, element, attrs){
			var formAction = attrs.asFormAction == undefined ? '' : attrs.asFormAction;
			var parentFormName = $(element).closest('as-form').attr('id');
			var parentScope = angular.element($(element).closest('as-form')).scope();
			
			scope.formName = attrs.asFormDialog;
			scope.formKey = parentFormName + '-' + scope.formName;
			
			element.bind('click', function(){
				if( formAction != '' ){
					actionUrl = './' + formAction + '&form_key=' + scope.formKey + '&statement_id=' + scope.share.statement_id;
				} else {
					actionUrl = appBase + 'api/table?' . attrs.asFormDialog;
				}
				Dialog.load(actionUrl, scope);
			})
			
			//element.bind('$destroy', function() {
			//});
		}
	}
});


/***************************************************************************************************
 * Directive : On Keyup
 * 
 ***************************************************************************************************/
formDirective.directive('onKeyup', function() {
    return function(scope, elm, attrs) {
        //Evaluate the variable that was passed
        //In this case we're just passing a variable that points
        //to a function we'll call each keyup
        var keyupFn = scope.$eval(attrs.onKeyup);
        
        elm.bind('keyup', function(evt) {
            //$apply makes sure that angular knows 
            //we're changing something
            scope.$apply(function() {
                keyupFn.call(scope, evt.which, $(elm).val());
            });
        });
    };
});


/***************************************************************************************************
 * Directive : Form
 * 
 ***************************************************************************************************/
formDirective.directive('asServerModel', function($rootScope, $http, Forms, API){
	return {
		restrict: 'A',
		scope:{ 
			share:'=asFormModel',
			server:'=asServerModel'
		},
		link: function(scope, element, attrs){
			var formAction = attrs.asFormAction == undefined ? '' : attrs.asFormAction;
			scope.formKey = scope.$parent.formKey != undefined ? scope.$parent.formKey : '';
			
			if( formAction != '' ){
				$http({method:'GET', url:appBase + formAction, params:{format:'json'}})
				.success(function(response){
					scope.server = response;
					$rootScope.$broadcast('ServerModelSuccess', scope);

					/*scope.$apply(function() {	});*/
					scope.$watch(attrs.ngModel, function(value) {
						if( value != undefined ) {
							scope.share[attrs.ngModel] = value;
						}
					});
				});
			} else {
				scope.server = API.query({controller:'code', category:attrs.asServerModel});
			}
		}
	}
});


