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
 * API Service
 *
 ***************************************************************************************************/
angular.module('api.service', ['ngResource']).
    factory('Api', function($rootScope,$resource,$q){
        return $resource(
            appBase + 'api/:version/:model/:id',
            {version:'v1', model:'table'},
            {
                index: {method:'GET', isArray:true},

                get: {method:'GET', param: {id:'@id'}, interceptor : {
                    response: function(response){
                        $rootScope.$broadcast('api.get');
                        return response || $q.when(response);
                    }
                }},

                save: {method:'POST', interceptor : {
                    response: function(response){
                        if(_a) _a.alert.server(response.data);
                        $rootScope.$broadcast('api.save');
                        return response || $q.when(response);
                    },
                    responseError: function(response){
                        if(_a) _a.alert.server(response.data);
                        $rootScope.$broadcast('api.save');
                        return response || $q.when(response);
                    }
                }},

                update: {method:'PUT', interceptor : {
                    response: function(response){
                        if(_a) _a.alert.server(response.data);
                        $rootScope.$broadcast('api.update');
                        return response || $q.when(response);
                    },
                    responseError: function(response){
                        if(_a) _a.alert.server(response.data);
                        $rootScope.$broadcast('api.update');
                        return response || $q.when(response);
                    }
                }},

                destroy: {method:'DELETE', interceptor : {
                    response: function(response){
                        if(_a) _a.alert.server(response.data);
                        $rootScope.$broadcast('api.destroy');
                        return response || $q.when(response);
                    },
                    responseError: function(response){
                        if(_a) _a.alert.server(response.data);
                        $rootScope.$broadcast('api.destroy');
                        return response || $q.when(response);
                    }
                }}
            }
        );
    });



/***************************************************************************************************
 * Wire Service
 * Direct connection with back-end controller
 *
 ***************************************************************************************************/
angular.module('wire.service', ['ngResource']).
    factory('Wire', function($rootScope,$resource,$q){

    });