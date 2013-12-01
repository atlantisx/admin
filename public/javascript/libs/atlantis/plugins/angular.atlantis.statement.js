/*
 * File:		angular.atlantis.statement.js
 * Version:    	1.0
 * Package: 	Component\Atlantis
 * Module: 		Atlantis Statment
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



angular.module('statement.service', ['ngResource']).


/***************************************************************************************************
 * Statement : Service
 * 
 ***************************************************************************************************/

factory('Statements', function($rootScope, $http, $resource){
	var statementService = {};
	
	//[i] Statement resource
	statementService.statementResource = [];
	statementService.statementData = [];
	
	statementService.Load = function(statementID, formKey){
		var sResource, sData;
		
		//[i] Create statement resource object and store in global collection
		sResource = $resource(
				appBase + 'api/statement/',
				{
					statement_id:statementID, 
					form_key:formKey
				},
				{
					query: {method:'GET', isArray:false},
					save: {method:'PUT', isArray:false, headers: {'Content-Type': 'application/json'}},
					remove: {method:'DELETE', isArray:false, headers: {'Content-Type': 'application/x-www-form-urlencoded'}}
				}
		);
		
		//[i] Execute resource query
		sData = sResource.query({}, function(){
			$rootScope.$broadcast('StatementReceived:' + formKey);
		});
		
		//[i] Add to global !! check if exist
		if( _.where(this.statementResource, {key: formKey}).length == 0 ) {
			this.statementResource.push({key:formKey,value:sResource});
			this.statementData.push({key:formKey,value:sData});
		} else {
			sData = _.where(this.statementData, {key: formKey})[0].value;
		}
		
		//[i] Return result
		return sData;
	}

	statementService.Refresh = function(formKey, params, onStatementReceive){
		if(formKey ==  undefined) formKey = '';
		params = params ==  undefined ? {} : params ;
		
		//[i] If no form key provided, update all statement
		if( formKey == '' ){

		} else {
			sResource = _.where(this.statementResource, {key: formKey})[0].value;
			sData = _.where(this.statementData, {key: formKey})[0].value;
			
			sData = sResource.query(params, function(){
				$rootScope.$broadcast('StatementReceived:' + formKey);
				if( typeof onStatementReceive == 'function' ) onStatementReceive(sData);
			});
			
			return sData;
		}
	}
	
	statementService.Update = function(formKey, onSuccess){
		if(formKey ==  undefined) formKey = '';
		
		//[i] If no form key provided, update all statement
		if( formKey == '' ){
			keys = _.pluck(this.statementResource, 'key');
			angular.forEach(keys,function(keyName){
				sResource = _.where(this.statementResource, {key: keyName})[0].value;
				sData = _.where(this.statementData, {key: keyName})[0].value;
				
				jsonString = JSON.stringify(sData);
				sResource.save({data:jsonString}, sData, function(response){
					_a.alert.success(response.message);
				});
			}, this);
		} else {
			sResource = _.where(this.statementResource, {key: formKey})[0].value;
			sData = _.where(this.statementData, {key: formKey})[0].value;
			jsonString = JSON.stringify(sData);
			
			sResource.save({data:jsonString}, sData, function(response){
				_a.alert.success(response.message);
				if( typeof onSuccess === 'function' ) onSuccess.call();
			});
		}
	}
	
	statementService.Delete = function(statementID){
		//if(formKey ==  undefined) formKey = '';
		var requestData = {
			statement_id:statementID 
			//form_key:formKey
		};
		
		if(statementID != '' || statementID != undefined){
			return $http({method:'DELETE', url:appBase + 'api/statement/', params:requestData})
			.success(function(response){
				if(response.status == 1) _a.alert.success(response.message);
				if(response.status == 0) _a.alert.error(response.message);
			});
		}
	}
	
	
	statementService.Global = function(statementID){ 
		return this.statementData;
	}
	
	
	
	
	return statementService;
})