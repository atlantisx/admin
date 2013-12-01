/*
 * File:		atlantis.preloader.js
 * Version:    	1.0
 * Package: 	Component\Atlantis
 * Module: 		Atlantis Preloader
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
 * Credits :-
 * 
 * Original author of Sticky v1.0
 * Daniel Raftery - http://thrivingkings.com/sticky
 * 
 */


/***************************************************************************************************
 * Directive : Form
 * 
 ***************************************************************************************************/
(function(_a) { 

	$(document).ajaxStart(function() {
		var settings = {
				'speed' : 'fast',		// animations: fast, slow, or integer
				'duplicates' : false,	// true or false
				'autoclose' : false		// integer or false
		};
		
		$.sticky('Loading..', settings);
	});
	
	$(document).ajaxSuccess(function() {
	  $.sticky.clear();
	});

})(_a);


/***************************************************************************************************
 * Directive : Form
 * 
 ***************************************************************************************************/
var globalService = angular.module('global.service', []);
globalService.config(function ($httpProvider) {
	$httpProvider.responseInterceptors.push('myHttpInterceptor');
	var spinnerFunction = function (data, headersGetter) {
		var settings = {
			'speed' : 'fast',
			'duplicates' : false,
			'autoclose' : false
		};

		$.sticky('Loading..', settings);
		return data;
	};
	$httpProvider.defaults.transformRequest.push(spinnerFunction);
});

//register the interceptor as a service, intercepts ALL angular ajax http calls
globalService.factory('myHttpInterceptor', function ($q, $window) {
	return function (promise) {
		return promise.then(function (response) {
			// do something on success
			$.sticky.clear();
			return response;

		}, function (response) {
			// do something on error
			$.sticky.clear();
			return $q.reject(response);
		});
	};
});



/***************************************************************************************************
 * Sticky v1.0.1
 * 
 * Changelog:-
 * 
 * ADDED : $sticky.clear() function to clear all the stickiness.
 * 
 ***************************************************************************************************/
(function ($) {

	// Using it without an object
    $.sticky = function (note, options, callback) {

        return $.fn.sticky(note, options, callback);
    };

    $.fn.sticky = function (note, options, callback) {
        // Default settings
        var position = 'top-right'; // top-left, top-right, bottom-left, or bottom-right
        
        var settings = {
            'speed': 'fast', // animations: fast, slow, or integer
            'duplicates': true, // true or false
            'autoclose': 5000 // integer or false
        };

        // Passing in the object instead of specifying a note
        if (!note) {
            note = this.html();
        }

        if (options) {
            $.extend(settings, options);
        }

        // Variables
        var display = true;
        var duplicate = 'no';

        // Somewhat of a unique ID
        var uniqID = Math.floor(Math.random() * 99999);

        // Handling duplicate notes and IDs
        $('.sticky-note').each(function () {
            if ($(this).html() == note && $(this).is(':visible')) {
                duplicate = 'yes';
                if (!settings['duplicates']) {
                    display = false;
                }
            }
            if ($(this).attr('id') == uniqID) {
                uniqID = Math.floor(Math.random() * 9999999);
            }
        });

        // Make sure the sticky queue exists
        if (!$('body').find('.sticky-queue').html()) {
            $('body').append('<div class="sticky-queue ' + position + '"></div>');
        }

        // Can it be displayed?
        if (display) {
            // Building and inserting sticky note
            $('.sticky-queue').prepend('<div class="sticky border-' + position + '" id="' + uniqID + '"></div>');
            //$('#' + uniqID).append('<img src="close.png" class="sticky-close" rel="' + uniqID + '" title="Close" />');
            $('#' + uniqID).append('<div class="sticky-note" rel="' + uniqID + '">' + note + '</div>');

            // Smoother animation
            var height = $('#' + uniqID).height();
            $('#' + uniqID).css('height', height);

            //$('#' + uniqID).slideDown(settings['speed']);
            $('#' + uniqID).show();
            
            display = true;
        }

        // Listeners
        $('.sticky').ready(function () {
            // If 'autoclose' is enabled, set a timer to close the sticky
            if (settings['autoclose']) {
                $('#' + uniqID).delay(settings['autoclose']).fadeOut(settings['speed']);
            }
        });
        // Closing a sticky
        $('.sticky-close').click(function () {
            $('#' + $(this).attr('rel')).dequeue().fadeOut(settings['speed']);
        });


        // Callback data
        var response = {
            'id': uniqID,
                'duplicate': duplicate,
                'displayed': display,
                'position': position
        }

        // Callback function?
        if (callback) {
            callback(response);
        } else {
            return (response);
        }
    }

    $.sticky.clear = function(){
    	$('.sticky-queue').children().each(function(){
    		$(this).dequeue().fadeOut('fast');
    	})
    	//$('.sticky-queue').dequeue().fadeOut(settings['speed']);
    }

})(jQuery);