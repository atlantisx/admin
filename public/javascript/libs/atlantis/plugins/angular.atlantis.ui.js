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



var atlantisUi = angular.module('atlantis.ui',[]);


/***************************************************************************************************
 * Directive : as-ui-icheck
 *
 ***************************************************************************************************/
atlantisUi.directive('asUiIcheck', function($timeout){
    return {
        require: 'ngModel',
        link: function($scope, element, $attrs, ngModel) {
            return $timeout(function() {
                var value = $attrs['value'];

                $scope.$watch($attrs['ngModel'], function(){
                    $(element).iCheck('update');
                })

                return $(element).iCheck({
                    checkboxClass: 'icheckbox_flat-aero',
                    radioClass: 'iradio_flat-aero'

                }).on('ifChanged', function(event) {
                    if ($(element).attr('type') === 'checkbox' && $attrs['ngModel']) {
                        //$scope.$apply(function() {
                            return ngModel.$setViewValue(event.target.checked);
                        //});
                    }
                    if ($(element).attr('type') === 'radio' && $attrs['ngModel']) {
                        //return $scope.$apply(function() {
                            return ngModel.$setViewValue(value);
                        //});
                    }
                    $scope.$emit('atlantis.ui.icheck.changed',event,element)

                }).on('ifChecked', function(event){
                    $scope.$emit('atlantis.ui.icheck.checked',event,element)

                }).on('ifUnchecked', function(event){
                    $scope.$emit('atlantis.ui.icheck.unchecked',event,element)

                });
            });
        }
    };
});


/***************************************************************************************************
 * Directive : ui-datepicker
 *
 ***************************************************************************************************/
atlantisUi.directive('uiasDatepicker', function() {
    return {
        restrict: 'A',
        require : 'ngModel',
        link : function (scope, element, attrs, ngModelCtrl) {
            $(function(){
                element.datepicker({
                    todayBtn: false,
                    dateFormat:'yy-mm-dd',
                    onSelect:function (date) {
                        scope.$apply(function () {
                            ngModelCtrl.$setViewValue(date);
                        });
                    }
                });
            });
        }
    }
});


/***************************************************************************************************
 * Directive :
 *
 ***************************************************************************************************/
atlantisUi.directive('asUiTable', function($rootScope) {
    return {
        scope: true,
        link: function (scope, element, attrs){
            var options = {
                //"sDom": '<"table-header"fr>t<"table-toolbar"lpi>',
                "bLengthChange": false,
                "bSort": false,
                "bAutoWidth": false,
                "bDestroy": true,
                "bStateSave": true
            };

            //[i] Extend default options with user assigned
            if( attrs.asUiTableOptions ){
                angular.extend( options, scope.$eval(attrs.asUiTableOptions) );
            }

            //[i] Default filters
            scope.filters = options['aDefaultFilters'];

            //[i] Columns definition
            var explicitColumns = [];
            element.find('th').each(function(index, elem) {
                explicitColumns.push($(elem).text());
            });
            if (explicitColumns.length > 0) {
                options["aoColumns"] = explicitColumns;
            } else if (attrs.aoColumns) {
                options["aoColumns"] = scope.$eval(attrs.aoColumns);
            }
            if (attrs.aoColumnDefs) {
                options["aoColumnDefs"] = scope.$eval(attrs.aoColumnDefs);
            }

            //[i] Row callback
            if (attrs.fnRowCallback) {
                options["fnRowCallback"] = scope.$eval(attrs.fnRowCallback);
            }

            //[i] Data transmission callback
            if (attrs.fnServerData) {
                options["fnServerData"] = scope.$eval(attrs.fnServerData);
            }else{
                //[i] Get filters
                scope.$watch('filters', function(value) {
                    dataTable.fnSettings().fnServerData = function( sSource, aoData, fnCallback ){
                        scope.fnFiltering(value, sSource,aoData,fnCallback);
                    }
                }, true);
            }

            //[i] Default filtering
            options['fnServerData'] = function( sSource, aoData, fnCallback ){
                scope.fnFiltering(scope.filters,sSource,aoData,fnCallback);
            }

            //[i] Filtering function
            scope.fnFiltering = function(aoValue,sSource,aoData,fnCallback){
                $.each(aoValue, function(i,element_name){
                    var element = $('#'+element_name.replace(/[.]/g,'\\.'));
                    aoData.push( {'name': 'search['+element_name+']', 'value':element.val() } );
                });

                $.getJSON( sSource, aoData, function (json) {
                    fnCallback(json)
                });
            }

            //[i] Instantiate DataTables
            if( !$rootScope.asTables ) $rootScope.asTables = [];
            var dataTable = element.dataTable(options);
            $rootScope.asTables[element.attr('id')] = dataTable;

            //[i] Watch for aaData change
            scope.$watch(attrs.aaData, function(value) {
                var val = value || null;

                if(val == null){
                    return false;

                }else if (val.$promise) {
                    val.$promise.then(function(data){
                        dataTable.fnClearTable();
                        dataTable.fnAddData(scope.$eval(data));
                    });

                }else if( typeof val == 'object' ){
                    dataTable.fnClearTable();
                    dataTable.fnAddData(scope.$eval(attrs.aaData));
                }
            });

            //[i] Event : Table refresh
            $rootScope.asTableRefresh = function(value){
                $rootScope.asTables[value].fnFilter('');
            }

            //[i] Event : Table reset
            $rootScope.asTableReset = function(value){
                $.each(scope.filters, function(i,element_name){
                    var element = $(':input#'+element_name.replace(/[.]/g,'\\.'));
                    if( !element.attr('ng-disabled') || !element.attr('disabled') ) element.val('');
                });
                $rootScope.asTables[value].fnFilter('');
            }
        }
    }
});


/***************************************************************************************************
 * Directive : as-ui-table-filters
 *
 ***************************************************************************************************/
atlantisUi.directive('asUiTableFilters', function($rootScope) {
    return {
        scope: {},
        link: function (scope, element, attrs) {
            var current_table = {};

            //[i] Table name is not set, get first instance in table
            if(attrs.asUiTableFilters == 'true'){
                var table_key = Object.keys($rootScope.asTables)[0];
                current_table = $rootScope.asTables[table_key];

            //[i] Table name is set, get table instance
            }else if(attrs.asUiTableFilters.length > 0){
                current_table = $rootScope.asTables[attrs.asUiTableFilters];
            };

            //[i] Push filter to table
            if( current_table != undefined ){
                var table_scope = angular.element(current_table).scope();
                if( table_scope.filters.indexOf( element.attr('id') ) ) table_scope.filters.push( element.attr('id') );

                element.on('change',function(){
                    current_table.fnFilter('');
                });
            }
        }
    }
});


/***************************************************************************************************
 * Directive : as-ui-editor-wysihtml
 *
 ***************************************************************************************************/
atlantisUi.directive('asUiEditorWysihtml', function() {
    return {
        //template: '<textarea ng-model="model" rows="5" class="wysiwyg"></textarea>',
        require: 'ngModel',
        restrict: 'A',
        link: function(scope, element, attrs, ngModel) {
            var textarea = element;

            scope.$watch('model', function(val) {
                textarea.siblings("iframe").contents().find("body").html(val);
            });

            textarea.wysihtml5({
                "font-styles": true,
                "emphasis": true,
                "lists": true,
                "html": false,
                "link": false,
                "image": false,
                "color": false,
                stylesheets: false,
                events: {
                    "blur": function() {
                        scope.$apply(function() {
                            var html = textarea.siblings("iframe").contents().find("body").html();
                            return ngModel.$setViewValue(html);
                        });
                    }
                }
            });
        }
    };
})


/***************************************************************************************************
 * Directive : as-ui-card
 *
 ***************************************************************************************************/
atlantisUi.directive('asUiCard', ['Validation', function(Validation) {
    return {
        restrict: 'A',
        link: function(scope, element, attrs){
            //i: Getting control under card with validation
            var controls = element.find('input[as-ui-validation]');

            //i: Preparing all found control
            angular.forEach(controls, function(control){
                //i: Getting model controller of control's
                var model_controller = angular.element(control).data('$ngModelController');

                //i: Extending control model controller
                angular.extend(model_controller,{card:attrs.asUiCard});
            })

            //i: Event
            scope.$emit('atlantis.ui.card.init',attrs.asUiCard)
        }
    }
}]);


/***************************************************************************************************
 * Directive : as-ui-validation
 *
 ***************************************************************************************************/
atlantisUi.directive('asUiValidation', ['ui.config','Validation', function(uiConfig){
    return {
        require: 'ngModel',
        restrict: 'A',
        link: function(scope, element, attrs, ngModel){
            var config = uiConfig.validation;

            //i: Check for engine
            if($.validationEngine == undefined) return $.error('ValidationEngine instance not loaded!');

            //i: Boot atlantis on VE
            if( $.validationEngine.defaults.atlantis == undefined ){
                angular.extend(config,{atlantis:true});
                angular.extend($.validationEngine.defaults,config);
            }

            //i: View - Model
            ngModel.$parsers.unshift(function(viewValue){
                var valid = element.validationEngine('validate');
                ngModel.$setValidity('asUiValidation', !valid)

                //i: Event : Validated
                scope.$emit('atlantis.ui.model.parsed',element)

                return viewValue;
            });

            //i: Model - View
            ngModel.$formatters.push(function(modelValue){
                return modelValue;
            });

            //i: Render
            ngModel.$render = function(){
                //i: Setting value to element
                element.val(ngModel.$viewValue)

                //i: Validate element
                var valid = element.validationEngine('validate');

                //i: Set validity
                ngModel.$setValidity('asUiValidation', !valid)

                //i: Event : Validated
                scope.$emit('atlantis.ui.model.rendered',element)
            }

            //i: Event : Validation Init
            scope.$emit('atlantis.ui.validation.init',element)
        }
    }
}]);


/***************************************************************************************************
 * Directive : as-ui-wizard-step
 *
 ***************************************************************************************************/
atlantisUi.directive('asUiWizardStep', ['ui.config','Validation', function(uiConfig,Validation){
    return {
        restrict: 'A',
        scope: {
            asUiCallbackValidation: '&'
        },
        link: function(scope, element, attrs){
            var config = {
                validation_rule: function() {
                    var step_name = this.attr('as-ui-card');

                    //i: Event callback with override
                    if( typeof scope.asUiCallbackValidation == 'function') {
                        var event = jQuery.Event('validation');
                        var result = scope.asUiCallbackValidation({$event:event,element:this})

                        if( event.isDefaultPrevented() ) return result;;
                    }

                    //i: If step still not validated return warning
                    if( Validation.$error.cards[step_name] == undefined ){
                        return 'warning';

                    //i: Return true
                    }else if( Validation.$error.cards[step_name].$valid ){
                        return true

                    //i: Return warning on else
                    }else{
                        return 'warning'
                    }
                }
            };

            //i: Get validation service
            scope.validation = Validation;

            //i: Extending config
            angular.extend(config,uiConfig.wizardstep);

            //i: Instantiate steps
            $(element).psteps(config);

            //i: Watch control error count
            scope.$watch('validation.$error.controls.$count',function(){
                $(element).trigger('validate.psteps')
            },true);
        }
    }
}]);


/***************************************************************************************************
 * Service : Validation
 *
 ***************************************************************************************************/
atlantisUi.factory('Validation',function($rootScope){
    var serviceProvider = {
        $error: {
            controls: {
                $count: 0,
                $valid: true
            },
            cards: {}
        },
        cards: {},
        controls: [],
        $refresh: function(){
            var error_count = 0;

            //i: Count error for all card
            angular.forEach(serviceProvider.cards, function(card,key){
                var error_card_count = 0;

                angular.forEach(card, function(control,key){
                    if( control.$invalid ) {
                        error_card_count++;
                    }
                },[error_count]);

                if( serviceProvider.$error.cards[key] !== undefined ){
                    serviceProvider.$error.cards[key].$count = error_card_count;

                    //i: Validity
                    if( error_card_count > 0 ){
                        serviceProvider.$error.cards[key].$valid = false
                    }else{
                        serviceProvider.$error.cards[key].$valid = true
                    }
                }
            },[error_count]);

            //i: Count error for all control
            angular.forEach(serviceProvider.controls, function(control){
                if( control.$invalid ) error_count++;
            },[error_count]);


            //i: Error count
            serviceProvider.$error.controls.$count = error_count;

            //i: Validity
            if( error_count > 0 ){
                serviceProvider.$error.controls.$valid = false
            }else{
                serviceProvider.$error.controls.$valid = true
            }

            return error_count;
        }
    }

    $rootScope.$on('atlantis.ui.validation.init',function(e,elem){
        var model_controller = angular.element(elem).data('$ngModelController');

        //i: Push control into array
        serviceProvider.controls.push(model_controller)
    });

    $rootScope.$on('atlantis.ui.card.init',function(e,card){
        serviceProvider.cards[card] = [];

        //i: Traverse all control to check card child
        angular.forEach(serviceProvider.controls, function(control){
            if( control.card == card ){
                serviceProvider.cards[card].push(control)
                serviceProvider.$error.cards[card] = {
                    $count:0,
                    $valid:true
                }
            }
        },[card]);
    });

    $rootScope.$on('atlantis.ui.model.parsed',function(e,elem){
        //i: Refresh, should be improved without traversing
        serviceProvider.$refresh()
    });

    $rootScope.$on('atlantis.ui.model.rendered',function(e,elem){
        //i: Refresh, should be improved without traversing
        serviceProvider.$refresh()
    });

    return serviceProvider;
})