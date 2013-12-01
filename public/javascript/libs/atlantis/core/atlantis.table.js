/*
 * File:		atlantis-table.js
 * Version:    	1.0
 * Package: 	Core
 * Module: 		Table
 * Function:	Provide a table API and functionality.	  
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


(function(atlantis) { 
	
	function getIO($this){
		data = $this.data('atlantis-grid');
		return data.options;
	}
    
	function setIO($this,io){ 
		data = $this.data('atlantis-grid');
		
		if ( !data ) {
			$this.data('atlantis-grid', {
		       target : $this,
		       options : io
			});
		}
	}

	grids = {
		init: function(options){
			options = options || {};
			var that = this;
			var io = $.extend({}, _a.fn.grid.defaults, options, {that: this});
			_a.fn.grid.io = io;
			
			return $.each(this, function(i){
				var $this = $(this);
				setIO($this,io);
	
				if( io.model != '' ){
					tableGetModel(that[i], io)
				} else {
					tableGet(that[i], io);
				}
				
			})
		},
	
		refresh: function(){
			$('form').each(function(index, value){
				tableCalcD(value.id);
			})
		},
		
		reset: function(){
			var $this = $(this);
			var io = getIO($this);
			
			return $.each($this, function(i){
				if( io.model != '' ){
					tableGetModel(this, io)
				} else {
					tableGet(this, io);
				}
			})
		},
	
		update: function(){
			var $this = $(this);
			var io = getIO($this);
			
			return $.each($this, function(){
				if( io.model != '' ){
					console.log('model update');
				} else {
					tableUpdate(this, io);
				}
			})
		}
	}
	

	atlantis.fn.grid = function(method){
		if (grids[method]) {
			return grids[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || !method) {
			return grids.init.apply(this, arguments);
		} else {
			$.error('Method ' + method + ' does not exist on atlantis.form');
		}
	}
	
	function tableGetModel(form, io){
        form_key = form.id;
        
        $.extend(io.filters, {
        	transaction_type: form_key
        })

        new_param = 'model=' + io.model + '&filters=' + JSON.stringify(io.filters);
        if( this.get_param != undefined ) new_param += '&' + this.get_param;
        
        $.get(io.get_url, new_param, function(response){
        	if(response.status == '1'){
        		if( _a.utils.validateJSON(response.data) ){
	        		response.data = JSON.parse(response.data);
	                //$.each(response.data, function(value){
	                	//form_key = key;
	                	tableDataParse(response.data);
	                	tableCalc(form_key);
	                //})
        		}
            } else {
                _a.alert.error(response.message);
            }
        });
	}
	
    
	function tableGet(form, io){
        new_params = 'form_key=' + form.id + '&' + io.get_param;

        $.get(io.get_url, new_params, function(response){
            if(response.status == '1'){
            	$.each(response.statements, function(key, value){
                	form_key = key;
                	statement_data = JSON.parse(value);
                	tableDataParse(statement_data);
                	tableCalc(form_key);
                })
            } else {
                _a.alert.error(response.message);
            }
        });
	}
	
    function tableUpdate(form, io){
    	new_param = io.update_param + '&' + $(form).serialize() + '&material_category=' + form.id;
        $.post(io.update_url, new_param, function(response){
            if(response.status == '1'){
                _a.alert.success(response.message);
            } else {
                _a.alert.error(response.message);
            }
        });
    }
	
	function tableDataParse(data_object){
		$.each(data_object, function(key, rows){
    		form_id = key;
    		$.each(rows, function(row, fields){
				$.each(fields, function(field, value){
					element = $('#' + form_id + '-' + row + '-' + field);
					if(element.is('span')){
						element.html(value);
					} else {
						element.val(value);
					}
				})
    		})
    	})
	}
	
	var tableCalcD = _.debounce(tableCalc, 300);
	
	function tableCalc(form_id){
		//console.profile('Build table');
		
    	$('form#' + form_id + ' input[cell_type=calc]').each(function(){
    		var rows_values = new Array,
    			columns_values = new Array;
    		var rows_total = 0,
				columns_total = 0,
				total = 0;
    		
    		calc_elem = $(this);

			rows = _.compact( calc_elem.attr('rows').split(',') );
			$(rows).each(function(key, value){
				inputs = new Array;
				
				// Search for normal row type
				inputs_normal = $('[id^=' + value + ']');

				// Search for json row type
				id_parts = value.split('-');
				id_parts[1] = '(' + id_parts[1] + ')_[0-9]+';
				id_regex = id_parts.join('-');
				inputs_json = $('input:regex(id, ' + id_regex + ' )');
				
				inputs = inputs.concat(_.compact(inputs_normal),_.compact(inputs_json));
				
				$(inputs).each(function(index, value){
					input_val = $(value).val();
					if( input_val != '' ){
						rows_values.push( parseFloat(input_val.replace(/,/g,'')) );
					}
				})
			})
			
    		columns = _.compact( calc_elem.attr('columns').split(',') );
			$.each(columns, function(key, value){
				inputs = $('[id^=' + value + ']');

				$(inputs).each(function(index, value){
					input_val = $(value).val();
					if( input_val != '' ){
						columns_values.push( parseFloat(input_val.replace(/,/g,'')) );
					}
				})
			})
			
    		if(calc_elem.attr('function') == 'sum'){
    			total = _.flatten([rows_values, columns_values]);
    			//total = _.reduce(total, function(memo, num){ return memo + num; }, 0);
    			total = eval(total.join('+'));
    		}else if(calc_elem.attr('function') == 'subtract-col'){ 
    			minuend = rows_values.shift();
    			//subtrahend = _.reduce(rows_values, function(memo, num){ return memo + num; }, 0);
    			subtrahend = eval(rows_values.join('+'));
    			total = minuend - subtrahend;
    		}
			
			calc_elem.val(accounting.formatNumber(total,2));
    	})
    	
    	//console.profileEnd();
	}
	
	atlantis.fn.grid.io = {};
	atlantis.fn.grid.defaults = {
		get_url: appBase + 'api/table',
		get_param: '',
		update_url:  appBase + 'api/table',
		update_param: '',
		model: '',
		filters: {}
	}
})(atlantis);