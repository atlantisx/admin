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
 * Rest Service
 *
 * For dynamic parameter, model could modified params variable directly from return
 * Rest object. This service is not singleton.
 ***************************************************************************************************/
angular.module('rest.service',[]).
	factory('Rest', function($http){

        var serviceProvider = function(url, params){
            this.params = {};
            this.url = url;
            this.headers = { 'Content-Type': 'application/x-www-form-urlencoded' };

            //[i] Check default params supplied
            if( typeof params == 'object' ){
                this.params = params;
            }
        }

        serviceProvider.prototype.index = function(callback){
            return $http({
                method: 'GET',
                url: this.url,
                headers : this.headers
            }).success(function(response){
                _a.alert.server(response);
                if( typeof callback == 'function' ) callback(response);

            }).error(function(response){
                _a.alert.server(response);
            });
        }

        serviceProvider.prototype.store = function(callback){
            $http({
                method: 'POST',
                url: this.url,
                data: $.param(this.params),
                headers : this.headers

            }).success(function(response){
                _a.alert.server(response);
                if( typeof callback == 'function' ) callback(response);

            }).error(function(response){
                _a.alert.server(response);
            });
        }

        serviceProvider.prototype.show = function(value,callback){
            $http({
                method: 'POST',
                url: this.url + '/' + value,
                data: $.param(this.params),
                headers : rest.headers

            }).success(function(response){
                _a.alert.server(response);
                if( typeof callback == 'function' ) callback(response);

            }).error(function(response){
                _a.alert.server(response);
            });
        }

        var serviceFactory = {
            new: function(url, params){
                return new serviceProvider(url, params)
            }
        }

        return serviceFactory;
    });