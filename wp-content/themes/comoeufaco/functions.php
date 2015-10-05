<?php
//variáveis comuns
$ver_foundation = '5.5.2';
$dir_tema = get_template_directory_uri().'/';
$nome_blog = get_bloginfo('name');
$link_blog = get_bloginfo('url');

//registra e carrega os arquivos CSS e JS necessários ao tema
function rb_cssejs(){
	global $ver_foundation, $dir_tema;
	
	//remove o registro nativo do jquery
	wp_deregister_script('jquery');	
	
	//registrar os arquivos necessários
	wp_register_style('normalize', $dir_tema.'css/normalize.css', array(), $ver_foundation, 'all');
	wp_register_style('foundation-min', $dir_tema.'css/foundation.min.css', array(), $ver_foundation, 'all');
	wp_register_style('style', $dir_tema.'style.css', array('normalize','foundation-min'), $ver_foundation, 'all');
	wp_register_script('modernizr', $dir_tema.'js/vendor/modernizr.js', array(), $ver_foundation, FALSE);
	wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js', array(), $ver_foundation, TRUE);
	wp_register_script('foundation-js',$dir_tema.'js/foundation.min.js', array('jquery'), $ver_foundation, TRUE);
	wp_register_script('geral-js',$dir_tema.'js/geral.js', array('jquery'), $ver_foundation, TRUE);
	
	//carrega os arquivo
	wp_enqueue_style('normalize');
	wp_enqueue_style('foundation-min');
	wp_enqueue_style('style');
	wp_enqueue_script('modernizr');
	wp_enqueue_script('jquery');
	wp_enqueue_script('foundation-js');
	wp_enqueue_script('geral-js');	
}
add_action('wp_enqueue_scripts', 'rb_cssejs');






















