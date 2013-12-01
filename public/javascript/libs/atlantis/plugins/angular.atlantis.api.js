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


angular.module('api.service', ['ngResource']).
    factory('API', function($resource){
        return $resource(
            appBase + 'api/:controller/', {},
            {
                query: {method:'GET', params:{controller:'table', format:'json'}, isArray:true}
            }
        );
    });