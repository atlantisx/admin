/*
 * File:		atlantis.navigation.js
 * Version:    	1.0
 * Package: 	Core
 * Module: 		Navigation
 * Function:	Provide an GUI API and functionality.	  
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

	var menu_ul = $('.side_menu > li > ul'),
    menu_a  = $('.side_menu > li > a');
	menu_ul.hide();
	
	menu_a.click(function(e) {
		 e.preventDefault();

		 if( $(this).parent().children('ul').length == 0 )
			 $(location).attr('href',  $(this).attr('href') );
		 
		 if(!$(this).hasClass('active')) {
		     menu_a.removeClass('active');
		     menu_ul.filter(':visible').slideUp('normal');
		     $(this).addClass('active').next().stop(true,true).slideDown('normal');
		 } else {
		     $(this).removeClass('active');
		     $(this).next().stop(true,true).slideUp('normal');
		 }
	});