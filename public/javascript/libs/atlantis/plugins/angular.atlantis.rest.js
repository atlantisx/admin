/*
 * File:		atlantis.rest.js
 * Version:    	1.0
 * Package: 	Core
 * Module: 		Angular
 * Function:	Provide a REST API and functionality.	  
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
 * API Service
 * 
 ***************************************************************************************************/



/***************************************************************************************************
 * Rest Service
 * 
 ***************************************************************************************************/


angular.module('rest.service', ['ngResource']).
	factory('Rest', function($resource, $http){
		var restService = {};
		/*restService.Init = function(params){
			return $resource( 
				appBase + 'api/:controller', 
				params, 
				{
					get: {method:'GET', params:{format:'json'}},
					query: {method:'GET', params:{format:'json'}, isArray:true},
					put: {method:'PUT', params:{format:'json'}}
				}
			);
		}*/
		
		restService.Init = function(controller, params){
			restService.controller = appBase + 'api/' + controller;
			restService.params = params;
			
			return $resource( 
				restService.controller, 
				params, 
				{
					get: {method:'GET', params:{format:'json'}},
					query: {method:'GET', params:{format:'json'}, isArray:true},
					put: {method:'PUT', params:{format:'json'}}
				}
			);
			//return this;
		}
		
		/*restService.$get = function(){
			return $resource( 
				restService.controller, 
				restService.params,
				{
					put: {method:'PUT', params:{format:'json'}}
				}
			).get();
		}
		
		restService.$put = function(params){
			var new_params = $.extend({}, restService.params, params);

			return $http({method:'PUT', url:restService.controller, params:restService.params})
			.success(function(data, status){
				processResponse(data);
			})
			.error(function(data,status){
				_a.alert.error('Fatal error!');
			});
		}
		
		restService.$delete = function(){
			return $http({method:'DELETE', url:restService.controller, params:restService.params})
			.success(function(data, status){
				processResponse(data);
			})
			.error(function(data, status){
				_a.alert.error('Fatal error!');
			});
		}
		*/
		function processResponse(response){
			if(response != undefined) {
				if( response.status == '1'){
					_a.alert.success(data.message);
				} else {
					_a.alert.error(data.message);
				}
			}
		}
		
		return restService;
});