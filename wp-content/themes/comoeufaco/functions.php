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

//adiciona suporte a thumbnails
add_theme_support('post-thumbnails');

//limpa do wp_head removendo tags desnecessárias
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'start_post_rel_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'adjacent_posts_rel_link');
remove_action('wp_head', 'wp_shortlink_wp_head');

//remove smart quotes  
remove_filter('the_title', 'wptexturize');
remove_filter('the_content', 'wptexturize');
remove_filter('the_excerpt', 'wptexturize');
remove_filter('comment_text', 'wptexturize');
remove_filter('list_cats', 'wptexturize');
remove_filter('single_post_title', 'wptexturize');

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

//inclui biblioteca para resize de imgs
require_once('aq_resizer.php');

//usa a lib aq_resizer para exibir thumbs personalizadas
function rb_thumb($width=180, $height=150, $class='miniatura', $echo=TRUE){
	$titulo = get_the_title();
	global $dir_tema;
	if (has_post_thumbnail(get_the_ID())): 
		$img = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()),  'full');
		$img = aq_resize($img[0], $width, $height, TRUE);
	else:
		$img = $dir_tema.'img/nothumb.jpg';
	endif;
	if($echo == TRUE):
		echo "<img src=\"$img\" class=\"$class\" alt=\"$titulo\" title=\"$titulo\" />";
	else:
		return "<img src=\"$img\" class=\"$class\" alt=\"$titulo\" title=\"$titulo\" />";
	endif; 
}

//gera um resumo do post
function rb_resumopost($words=40, $link_text='continue lendo &raquo', $allowed_tags = '', $before='<p>', $after='</p>', $echo=TRUE, $idpost=0){
	if($idpost > 0):
		$post = get_post($idpost);
	else:
		global $post;
	endif;
	if ( $allowed_tags == 'all' ) $allowed_tags = '<a>,<i>,<em>,<b>,<strong>,<ul>,<ol>,<li>,<span>,<blockquote>,<img>';
	$text = preg_replace('/\[.*\]/', '', strip_tags($post->post_content, $allowed_tags));

	$text = explode(' ', $text);
	$tot = count($text);
	if ($tot < $words) $words = $tot;
	$output = '';
	for ($i=0; $i<$words; $i++): $output .= $text[$i] . ' '; endfor;
	$retorno = $before;
	$retorno .= force_balance_tags(rtrim($output));
	if ($i < $tot) $retorno .=  '...';
	if ($link_text != '') $retorno .=  ' <a href="'.get_permalink().'" title="'.get_the_title().'">'.$link_text.'</a>';
	$retorno .=  $after;
	if ($echo == TRUE):
		echo $retorno;
	else:
		return $retorno;
	endif;
}

//paginação de post no padrão foundation
function rb_paginacao($setas=TRUE, $finais=TRUE, $paginas=2){
    if (is_singular()) return;

    global $wp_query, $paged;
    $pagination = '';

    $max_page = $wp_query->max_num_pages;
    if ($max_page == 1) return;
    if (empty($paged)) $paged = 1;

    if ($setas) $pagination .= rb_linkpaginacao($paged - 1, 'arrow' . (($paged <= 1) ? ' unavailable' : ''), '&laquo;', 'Página anterior');
    if ($finais && $paged > $paginas + 1) $pagination .= rb_linkpaginacao(1);
    if ($finais && $paged > $paginas + 2) $pagination .= rb_linkpaginacao(1, 'unavailable', '&hellip;');
    for ($i = $paged - $paginas; $i <= $paged + $paginas; $i++):
        if($i > 0 && $i <= $max_page) $pagination .= rb_linkpaginacao($i, ($i == $paged) ? 'current' : '');
	endfor;
    if ($finais && $paged < $max_page - $paginas - 1) $pagination .= rb_linkpaginacao($max_page, 'unavailable', '&hellip;');
    if ($finais && $paged < $max_page - $paginas) $pagination .= rb_linkpaginacao($max_page);

    if ($setas) $pagination .= rb_linkpaginacao($paged + 1, 'arrow' . (($paged >= $max_page) ? ' unavailable' : ''), '&raquo;', 'Próxima página');

    $pagination = '<ul class="pagination">' . $pagination . '</ul>';
    $pagination = '<div class="pagination-centered">' . $pagination . '</div>';

    echo $pagination;
}

function rb_linkpaginacao($pagina, $class='', $conteudo='', $title='')
{
    $id = sanitize_title_with_dashes('pagination-page-' . $pagina . ' ' . $class);
    $href = (strrpos($class, 'unavailable') === false && strrpos($class, 'current') === false) ? get_pagenum_link($pagina) : "#$id";

    $class = empty($class) ? $class : " class=\"$class\"";
    $conteudo = !empty($conteudo) ? $conteudo : $pagina;
    $title = !empty($title) ? $title : 'Página ' . $pagina;

    return "<li$class><a id=\"$id\" href=\"$href\" title=\"$title\">$conteudo</a></li>\n";
}