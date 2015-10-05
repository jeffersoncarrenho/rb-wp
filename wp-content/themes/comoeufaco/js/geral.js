jQuery.noConflict();
jQuery(document).ready(function($){
	$('.top-bar-section ul.sub-menu').addClass('dropdown').removeClass('sub-menu');
	$('ul.dropdown').parent().addClass('has-dropdown');
	$('.post-list').last().css('border', 'none');
	$(document).foundation();	
});
