jQuery(document).ready(function($){
	$('.rb-opts').slideUp();
	$('.rb-section h3').click(function(){
		if($(this).parent().next('.rb-opts').css('display')=='none'){
			$('.rb-opts').slideUp();
			$('.rb-section h3 img').removeClass('active').addClass('inactive');
			$(this).removeClass('inactive').addClass('active');
			$(this).children('img').removeClass('inactive').addClass('active');
		}else{
			$(this).removeClass('active').addClass('inactive');
			$(this).children('img').removeClass('active').addClass('inactive');
		}
		$(this).parent().next('.rb-opts').slideToggle('slow');
	});
});
