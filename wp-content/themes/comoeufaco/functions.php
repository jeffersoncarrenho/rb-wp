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
	if(!rb_getopcao('seotitulos')) return wp_title('', FALSE, 'right'). ' | '. $nome_blog;
	
	global $nome_blog;
	if (is_home()) {
		return $nome_blog.' | '.get_bloginfo('description');
	} else {
		return wp_title('', FALSE, 'right'). ' | '. $nome_blog;
	}
	
}
//adiciona as tags como keywords para SEO
function rb_tagtokeyword(){  
    if(is_single() || is_page()):
		global $post;  
        $tags = wp_get_post_tags($post->ID); 
        $tag_array = NULL; 
        foreach($tags as $tag):  
            $tag_array[] = $tag->name;  
        endforeach;
		if(sizeof($tag_array)>0):  
	        $tag_string = implode(', ',$tag_array);  
	        if($tag_string !== ''):  
	            echo '<meta name="keywords" content="'.$tag_string.'" />'."\r\n";  
			endif;
		endif;  
	endif;  
}
if(rb_getopcao('seokeywords')) add_action('wp_head','rb_tagtokeyword');

//remove stop words do slug dos posts ao salvar
function rb_removestopwords($data, $postarray){
	if($data['post_status'] != 'publish'):
		$data['post_name'] = sanitize_title($data['post_title']);
		
		$slug = explode('-', $data['post_name']);
		$stopwords = array('a', 'agora', 'ainda', 'alguem', 'algum', 'alguma', 'algumas', 'alguns', 'ampla', 'amplas', 'amplo', 'amplos', 'ante', 'antes', 'ao', 'aos', 'apos', 'aquela', 'aquelas', 'aquele', 'aqueles', 'aquilo', 'as', 'ate', 'atraves',
		'cada', 'coisa', 'coisas', 'com', 'como', 'contra', 'contudo',
		'da', 'daquele', 'daqueles', 'das', 'de', 'dela', 'delas', 'dele', 'deles', 'depois', 'dessa', 'dessas', 'desse', 'desses', 'desta', 'destas', 'deste', 'deste', 'destes', 'deve', 'devem', 'devendo', 'dever', 'devera', 'deverao', 'deveria', 'deveriam', 'devia', 'deviam', 'disse', 'disso', 'disto', 'dito', 'diz', 'dizem', 'do', 'dos',
		'e', 'ela', 'elas', 'ele', 'eles', 'em', 'enquanto', 'entre', 'era', 'essa', 'essas', 'esse', 'esses', 'esta', 'estamos', 'estao', 'estas', 'estava', 'estavam', 'estavamos', 'este', 'estes', 'estou', 'eu',
		'fazendo', 'fazer', 'feita', 'feitas', 'feito', 'feitos', 'foi', 'for', 'foram', 'fosse', 'fossem',
		'grande', 'grandes', 'ha', 'isso', 'isto', 'ja', 'la', 'lhe', 'lhes', 'lo',
		'mas', 'mais', 'me', 'mesma', 'mesmas', 'mesmo', 'mesmos', 'meu', 'meus', 'minha', 'minhas', 'muita', 'muitas', 'muito', 'muitos',
		'na', 'nao', 'nas', 'nem', 'nenhum', 'nessa', 'nessas', 'nesta', 'nestas', 'ninguem', 'no', 'nos', 'nos', 'nossa', 'nossas', 'nosso', 'nossos', 'num', 'numa', 'nunca',
		'o', 'os', 'ou', 'outra', 'outras', 'outro', 'outros',
		'para', 'pela', 'pelas', 'pelo', 'pelos', 'pequena', 'pequenas', 'pequeno', 'pequenos', 'per', 'perante', 'pode', 'pude', 'podendo', 'poder', 'poderia', 'poderiam', 'podia', 'podiam', 'pois', 'por', 'porem', 'porque', 'posso', 'pouca', 'poucas', 'pouco', 'poucos', 'primeiro', 'primeiros', 'propria', 'proprias', 'proprio', 'proprios',
		'quais', 'qual', 'quando', 'quanto', 'quantos', 'que', 'quem',
		'sao', 'se', 'seja', 'sejam', 'sem', 'sempre', 'sendo', 'sera', 'serao', 'ser', 'seu', 'seus', 'si', 'sido', 'so', 'sob', 'sobre', 'sua', 'suas',
		'talvez', 'tambem', 'tampouco', 'te', 'tem', 'tendo', 'tenha', 'ter', 'teu', 'teus', 'ti', 'tido', 'tinha', 'tinham', 'toda', 'todas', 'todavia', 'todo', 'todos', 'tu', 'tua', 'tuas', 'tudo',
		'ultima', 'ultimas', 'ultimo', 'ultimos', 'um', 'uma', 'umas', 'uns', 'vai', 'vendo', 'ver', 'vez', 'vindo', 'vir', 'voce', 'voces', 'vos', 'vou'); 		
		foreach ($slug as $sl => $word):
			if(in_array($word, $stopwords)) unset($slug[$sl]);
		endforeach;		
		$data['post_name'] = implode('-', $slug);
	endif;
	return $data;
}
if(rb_getopcao('seostopwords')) add_filter('wp_insert_post_data', 'rb_removestopwords', 100, 2);

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

//registra a sidebar principal do site
register_sidebar(array(
	'name'=>'Sidebar Principal',
	'before_widget'=>'<div class="row widget-box"><div class="large-12 columns">',
	'after_widget'=>'</div></div>',
	'before_title'=>'<h5>',
	'after_title'=>'</h5>',
));

//registra a sidebar do rodapé do site
register_sidebar(array(
	'name'=>'Rodapé do site',
	'before_widget'=>'<div class="large-4 columns">',
	'after_widget'=>'</div>',
	'before_title'=>'<h5>',
	'after_title'=>'</h5>',
));

//cria um widget para inscrição via e-mail na news do Feedburner
class rb_feedemail extends WP_Widget{
	function __construct(){
		$params = array(
			'name'=>'RSS por Email',
			'description'=>'mostra um form que permite a inscrição de emails para receber as novidades via Feedburner',
		);
		parent::__construct('rb_feedemail','', $params);
	}
	
	public function form($instancia){
		extract($instancia);
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title') ?>">Título:</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('title') ?>" name="<?php echo $this->get_field_name('title') ?>" value="<?php if(isset($title)) echo esc_attr($title);?>"/>
			<span class="description">Informe o título do seu widget.</span>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('idfeed') ?>">ID do Feedburner:</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('idfeed') ?>" name="<?php echo $this->get_field_name('idfeed') ?>" value="<?php if(isset($title)) echo esc_attr($idfeed);?>"/>
			<span class="description">Informe o ID do seu feed no Feedburner.</span>
		</p>
		<?php
	}
	
	public function widget($args,$instancia){
		extract($args);
		extract($instancia);
		echo $before_widget;
		if(isset($title)&& $title != '') echo $before_title.$title.$after_title;
		?>
		<p>Receba todas as nossas novidades gratuitamente em seu e-mail</p>
		<form id="subscribeform" action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">
			<div class="row collapse">
				<div class="small-8 medium-9 large-10 columns">
			    	<input type="text" name="email" placeholder="seuemail@provedor.com">
			    </div>
			    <div class="small-4 medium-3 large-2 columns">
				    <input type="submit" value="OK" class="button prefix" />
				    <input type="hidden" name="uri" value="<?php echo $idfeed; ?>">
					<input type="hidden" value="pt_BR" name="loc">
			    </div>
			</div>
		</form>
		<?php
		echo $after_widget;
	}

}

//cria um widget para mostrar adsense
class rb_adsense extends WP_Widget{
	function __construct(){
		$params = array(
			'name'=>'Anúncios do Adsense',
			'description'=>'mostra um Anúncio do Adsense previamente configurado',
		);
		parent::__construct('rb_adsense','', $params);
	}
	
	public function form($instancia){
		extract($instancia);
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Título:</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('title') ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php if(isset($title)) echo esc_attr($title);?>"/>
			<span class="description">Informe o título do seu widget.</span>
		</p>
		<p>			
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('ocultar_single') ?>" name="<?php echo $this->get_field_name('ocultar_single'); ?>" <?php if(isset($ocultar_single)) echo 'checked="checked"';?>/>
			<label for="<?php echo $this->get_field_id('ocultar_single') ?>">Ocultar nos posts (single.php)</label>			
		</p>
		<?php
	}
	
	public function widget($args,$instancia){
		extract($args);
		extract($instancia);
		if (isset($ocultar_single)&& is_single()) return;
		if(rb_getopcao('adsidebar')=='')return;
		echo $before_widget;
		if(isset($title)&& $title != '') echo $before_title.$title.$after_title;
		echo rb_getopcao('adsidebar');
		echo $after_widget;
	}
}

//cria um widget que mostra os posts mais populares do site
class rb_popularposts extends WP_Widget{
	function __construct(){
		$params = array(
			'name'=>'Top Populares',
			'description'=>'mostra os posts mais populares do Blog com base no número de comentários',
		);
		parent::__construct('rb_popularposts','', $params);
	}
	
	public function form($instancia){
		extract($instancia);
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title') ?>">Título:</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('title') ?>" name="<?php echo $this->get_field_name('title') ?>" value="<?php if(isset($title)) echo esc_attr($title);?>"/>
			<span class="description">Informe o título do seu widget.</span>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('num_posts') ?>">Quantidade:</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('num_posts') ?>" name="<?php echo $this->get_field_name('num_posts') ?>" value="<?php if(isset($num_posts)) echo esc_attr($num_posts);?>"/>
			<span class="description">Informe quantos posts deseja mostrar.</span>
		</p>
		<?php
	}
	
	public function widget($args,$instancia){
		extract($args);
		extract($instancia);
		echo $before_widget;
		if(isset($title)&& $title != '') echo $before_title.$title.$after_title;
		if(get_transient('rb_popularposts')===FALSE){
			global $WP_Query;
			$populares = new WP_Query("showposts={$num_posts}&orderby=comment_count");
			$output = '';
			while ($populares->have_posts()):
				$populares->the_post();
				$output .='<div class="row collapse top10">';
				$output .= '<div class="small-3 medium-2 large-3 columns">';
				$output .= '<a href="'.get_permalink().'" class="th">'.rb_thumb(70,60, '', FALSE).'</a>';
				$output .= '</div>';
				$output .= '<div class="small-9 medium-10 large-9 columns">';
				$output .= '<h6><a href="'.get_permalink().'">'.get_the_title().'</a></h6>';
				$output .= '</div></div>';		
			endwhile;
			set_transient('rb_popularposts', $output, 60*60*24);
			echo $output;
		}else{
			echo get_transient('rb_popularposts');
		}
		echo $after_widget;
	}
}

//cria um widget com página do Facebook
class rb_facebook extends WP_Widget{
	function __construct(){
		$params = array(
			'name'=>'Página do Facebook',
			'description'=>'mostra um like box de sua página do Facebook',
		);
		parent::__construct('rb_facebook','', $params);
	}
	
	public function form($instancia){
		extract($instancia);
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title') ?>">Título:</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('title') ?>" name="<?php echo $this->get_field_name('title') ?>" value="<?php if(isset($title)) echo esc_attr($title);?>"/>
			<span class="description">Informe o título do seu widget.</span>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('idfacebook') ?>">ID da sua página:</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('idfacebook') ?>" name="<?php echo $this->get_field_name('idfacebook') ?>" value="<?php if(isset($idfacebook)) echo esc_attr($idfacebook);?>"/>
			<span class="description">Informe o ID de sua página no Facebook.</span>
		</p>
		<?php
	}
	
	public function widget($args,$instancia){
		extract($args);
		extract($instancia);
		echo $before_widget;
		if(isset($title)&& $title != '') echo $before_title.$title.$after_title;
		
		echo $after_widget;
	}
}

//registra os widgets no painel do WP
function rb_registrawidget(){
	register_widget('rb_feedemail');
	register_widget('rb_adsense');
	register_widget('rb_popularposts');
	register_widget('rb_facebook');	
}
add_action('widgets_init', 'rb_registrawidget');










