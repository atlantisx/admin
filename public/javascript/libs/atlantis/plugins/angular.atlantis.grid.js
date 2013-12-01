/*
 * File:		angular.atlantis.grid.js
 * Version:    	1.0
 * Package: 	Component\Atlantis
 * Module: 		Atlantis Grid
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



var gridService = angular.module('grid.service',[]);
var gridDirective = angular.module('grid.directive',[]);


/***************************************************************************************************
 * Grid Directive
 * 
 ***************************************************************************************************/
gridDirective.directive('asCellCalc', function(Cells, $rootScope){
	return {
		restrict: 'A',
		local: false,
		link: function($scope, element, attrs){
			var parentFormName = $(element).closest('form').attr('id')
			var calcExpression = attrs['asCellCalc'];
			
			//Cells(scope, element, attrs);
			if( calcExpression != '' ){
				$scope.$on('StatementReceived:' + parentFormName, function(){
					//element.val($scope.$eval(calcExpression));
					
					
					//$rootScope.$digest();
			        //if (callback) callback();
				})
				
				modelArray = calcExpression.match(/([\.\w]+)/g);
				//$.each(modelArray,function(key,value){
					//$scope.$watch(value, function(oldVal,newVal){
						//console.log(value);
						//console.log(oldVal);
						//element.val($scope.$eval(calcExpression));
					//})
				//})
			}
		}
	}
})



/***************************************************************************************************
 * Form Grid Service
 * 
 ***************************************************************************************************/
gridService.factory('Cells', function(API){
	/*
	 * Statement structure
	 * 
	 *  { cell:"full_column_id", value:0 }
	 *  
	 */
	
	var gridService = {};
	var cells = [];
	
	gridService = function(scope, element, attrs){
		var calcExpression = attrs['asCellCalc'];
		//var calc_columns = attrs['calcColums'];
		//var calc_rows = attrs['calcRows'];
		
		//calc_columns = _.compact( calc_columns.split(',') );
		//calc_rows = _.compact( calc_rows.split(',') );
		console.log(calcExpression);
		if( calc_function == 'sum' ){
			//Cells.sum(scope, calc_columns, calc_rows);
		}
	}
	
	
	gridService.refresh = function(){
		console.log(cells);
	}
	
	//[i] Grid cell SUM function
	gridService.sum = function(scope, columns, rows) {

		// iterate every rows provided 
		$.each(rows, function(key, value){

			// iterate every possible model rows
			inputs = $('[id^=' + value + ']');
			$.each(inputs, function(index, value){
				cells.push({
					cell: value,
					value: $(this).val()
				})
				
				// bind founded input from rows to calculation function
				$(this).bind('keyup', function(){
					console.log('bind');
					$(inputs).each(function(index, value){
						input_val = $(value).val();
						if( input_val != '' ){
							columns_values.push( parseFloat(input_val.replace(/,/g,'')) );
						}
					})
				});
			})
		})
		
	}
	
	return gridService;
})