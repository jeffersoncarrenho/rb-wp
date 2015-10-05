<?php 
	get_header();
	if (is_home() && !is_paged()):
		get_template_part('loop-home');
	else:
		get_template_part('loop');
	endif;	
	get_sidebar();
	get_footer();
?>