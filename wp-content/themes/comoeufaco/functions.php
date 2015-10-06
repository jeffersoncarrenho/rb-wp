<?php
//variáveis comuns
$ver_foundation = '5.5.2';
$dir_tema = get_template_directory_uri().'/';
$nome_blog = get_bloginfo('name');
$link_blog = get_bloginfo('url');
//inclui o painel de configurações do site
require_once (TEMPLATEPATH . '/admin/paineladmin.php');

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

//gera titulos personalizados
function rb_titulos(){
	global $nome_blog;
	if (is_home()) {
		return $nome_blog.' | '.get_bloginfo('description');
	} else {
		return wp_title('', FALSE, 'right'). ' | '. $nome_blog;
	}
	
}

//adiciona suporte a menus personalizados
function rb_menus(){
	register_nav_menus(
		array(
			'nav-topo' => 'Menu Principal',
			'nav-rodape' => 'Menu do Rodapé',	
		)
	);
}
add_action('init', 'rb_menus');

//gera um cache com os dados de um menu personalizado
function rb_mostra_menu($nome_menu = 'nav-topo'){
	$output = '';
	if ($nome_menu == 'nav-topo') {
		if (get_transient('rb_menu_principal')=== FALSE) {
			$output = wp_nav_menu(array(
					'theme_location'=> 'nav-topo',
					'container'=>FALSE,
					'items_wrap'=>'<ul class="right">%3$s</ul>',
					'echo'=> 0
				));
			set_transient('rb_menu_principal', $output, 60*60*24);
		} else {
			$output = get_transient('rb_menu_principal');
		}
	} elseif($nome_menu == 'nav-rodape') {
		if (get_transient('rb_menu_rodape')=== FALSE) {
			$output = wp_nav_menu(array(
					'theme_location'=> 'nav-rodape',
					'container'=>FALSE,
					'items_wrap'=>'<ul class="inline-list">%3$s</ul>',
					'echo'=> 0
				));
			set_transient('rb_menu_rodape', $output, 60*60*24);
		} else {
			$output = get_transient('rb_menu_rodape');
		}
	}
	return $output;
}

//limpa o cache dos menus ao atualizá-los
function rb_limpa_cache_menus(){
	delete_transient('rb_menu_principal');
	delete_transient('rb_menu_rodape');
}
add_action('wp_update_nav_menu', 'rb_limpa_cache_menus');













