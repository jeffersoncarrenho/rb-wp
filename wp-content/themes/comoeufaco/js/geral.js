jQuery.noConflict();
jQuery(document).ready(function($){
	$('a').not('[href*="'+document.domain+'"]').attr('target', '_blank');
	$('.top-bar-section ul.sub-menu').addClass('dropdown').removeClass('sub-menu');
	$('ul.dropdown').parent().addClass('has-dropdown');
	$('.rodape-box ul').addClass('side-nav');
	$('.post-list').last().css('border', 'none');
	$('#comment').keypress(function(){
		$('#spamblock').attr('value', 'cef');
	});
	if($('a.lightbox').length > 0){
		$('a.lightbox').lightBox();	
	}	
	$(document).foundation();
});