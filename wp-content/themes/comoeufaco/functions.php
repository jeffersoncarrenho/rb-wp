<?php
//variáveis comuns
$ver_foundation = '5.5.2';
$dir_tema = get_template_directory_uri().'/';
$nome_blog = get_bloginfo('name');
$link_blog = get_bloginfo('url');
//inclui o painel de configurações do site
require_once (TEMPLATEPATH . '/admin/paineladmin.php');

//coloca o site em modo de manutenção
function rb_manutencao(){
	if (!current_user_can('publish_posts') || !is_user_logged_in()):
		global $nome_blog;
		echo '
			<!doctype html>
			<head>
				<meta charset="UTF-8" />
				<title>'.$nome_blog.'</title>
			</head>
			<body>
				<div style="text-align:center;margin-top:100px;">'.rb_getopcao('msgmanutencao').'</div>
			</body>
			</html>
		';
		die();
	endif;
}

if (rb_getopcao('modomanutencao')) add_action('get_header', 'rb_manutencao');

//registra e carrega os arquivos CSS e JS necessários ao tema
function rb_cssejs(){
	global $ver_foundation, $dir_tema;
	
	//remove o registro nativo do jquery
	wp_deregister_script('jquery');	
	
	//registrar os arquivos necessários
	wp_register_style('normalize', $dir_tema.'css/normalize.css', array(), $ver_foundation, 'all');
	wp_register_style('foundation-min', $dir_tema.'css/foundation.min.css', array(), $ver_foundation, 'all');
	wp_register_style('style', $dir_tema.'style.css', array('normalize','foundation-min'), $ver_foundation, 'all');
	wp_register_style('lightbox-css', $dir_tema.'css/lightbox.css', array(), $ver_foundation, 'all');
		
	wp_register_script('modernizr', $dir_tema.'js/vendor/modernizr.js', array(), $ver_foundation, FALSE);
	wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js', array(), $ver_foundation, TRUE);
	wp_register_script('foundation-js',$dir_tema.'js/foundation.min.js', array('jquery'), $ver_foundation, TRUE);
	wp_register_script('geral-js',$dir_tema.'js/geral.js', array('jquery'), $ver_foundation, TRUE);
	wp_register_script('lightbox-js',$dir_tema.'js/lightbox.min.js', array('jquery'), $ver_foundation, TRUE);
	
	//carrega os arquivo
	wp_enqueue_style('normalize');
	wp_enqueue_style('foundation-min');
	wp_enqueue_style('style');
	if(is_single()) wp_enqueue_style('lightbox-css');
		
	wp_enqueue_script('modernizr');
	wp_enqueue_script('jquery');
	wp_enqueue_script('foundation-js');
	wp_enqueue_script('geral-js');
	if(is_single()) wp_enqueue_script('comment-reply');
	if(is_single()) wp_enqueue_script('lightbox-js');
		
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

//mostra botões de redes sociais
function rb_botoessociais(){
	$url = get_permalink();
	$title = get_the_title();

	$button = '<ul class="inline-list">';
	
	//twitter
	$button .= '<script type="text/javascript">!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';
	$button .= sprintf('<li><a href="https://twitter.com/share" class="twitter-share-button" data-url="%s" data-text="%s" data-via="%s" data-lang="pt-BR" data-count="vertical">Tweet</a></li>', $url, $title, 'comofaco' );

	//facebook
	$button .= '<div id="fb-root"></div><script type="text/javascript">(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) {return;} js = d.createElement(s); js.id = id;js.src = "//connect.facebook.net/pt_BR/all.js#xfbml=1&appId=228619377180035"; fjs.parentNode.insertBefore(js, fjs);} (document, "script", "facebook-jssdk"));</script>';
	$button .= sprintf('<li><div class="fb-like" data-href="%s" data-send="false" data-layout="box_count" data-show-faces="false" data-share="false" data-font="arial"></div></li>', $url);

	//google plus
	$button .= '<script type="text/javascript" src="https://apis.google.com/js/plusone.js">{lang: \'pt-BR\'}</script>';
	$button .= '<li><div><g:plusone size="tall"></g:plusone></div></li>';

	//linkedin
	$button .= '<script type="text/javascript" src="http://platform.linkedin.com/in.js"></script>';
	$button .= sprintf( '<li><div><script type="IN/Share" data-url="%s" data-counter="top"></script></div></li>', $url );	
	
	$button .= '</ul>';
	
	echo $button;
}

//mostrar posts relacionados com base na categoria
function rb_relacionados($qtde=3){
	if (!is_single()) return FALSE;
	$id_post = get_the_ID();
	$categorias = get_the_terms($id_post, 'category');
	$cat_busca = array();
	foreach ($categorias as $cat) {
		$cat_busca[]= $cat->cat_ID;
	}
	global $WP_Query;
	$relacionados = new WP_Query(array(
		'posts_per_page' => $qtde,
		'category__in' => $cat_busca,
		'post__not_in' => array($id_post),
		'orderby'=> 'rand',
 	));
	if($relacionados->have_posts()):while($relacionados->have_posts()): $relacionados->the_post();
		?>
		<div class="row collapse top10">
			<div class="small-3 medium-2 large-3 columns">
				<a href="<?php the_permalink(); ?>" class="th"><?php rb_thumb(70,60);?></a>
			</div>
			<div class="small-9 medium-10 large-9 columns">
				<h6><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
			</div>
		</div>
		<?php 
	endwhile;else:?>
		<div class="row collapse top10">
			<div class="small-12 columns">
				<h6>Nenhum post Relacionado!</h6>
			</div>
		</div>
	<?php endif;
	wp_reset_query();	
} 

//previne a publicação de comentários por spammers robotizados
function rb_campoformcomentarios(){
	echo '<input type="hidden" name="antisp" value="abc" id="spamblock" />';	
}
add_action('comment_form', 'rb_campoformcomentarios');

function rb_verificaspam(){
	if (trim($_POST['antisp'])!= 'cef') die('Desculpe mas suspeitamos que seu comentário seja SPAM, volte e tente novamente');
}
add_action('pre_comment_on_post', 'rb_verificaspam');

//shortcode para embed do youtube
function rb_youtubesc( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'id' => '',
	), $atts ) );
	
	$resultado = '<div class="flex-video"><iframe src="//www.youtube.com/embed/'.$id.'" allowfullscreen="" frameborder="0"></iframe></div>';
	return $resultado;
}
add_shortcode('youtube', 'rb_youtubesc');

//adiciona campos extras ao perfil do usuário
function rb_camposextrauser($user){?>
	<h3>Configurações de Publicidade</h3>
	<table class="form-table">
		<tr>
			<th><label for="adtopopost">Anúncio no topo dos posts</label></th>
			<td>
				<textarea name="adtopopost" rows="5" class="regular-text" id="adtopopost"><?php echo esc_attr(get_the_author_meta('adtopopost', $user->ID)) ?></textarea><br />
				<span class="description">Insira o código de um anúncio de 300x250 (ou responsivo) que será exibido no topo de cada post</span>
			</td>
		</tr>
		<tr>
			<th><label for="adrodapepost">Anúncio no Rodapé dos posts</label></th>
			<td>
				<textarea name="adrodapepost" rows="5" class="regular-text" id="adrodapepost"><?php echo esc_attr(get_the_author_meta('adrodapepost', $user->ID))?></textarea><br />
				<span class="description">Insira o código de um anúncio de 300x250 (ou responsivo) que será exibido no rodapé de cada post</span>
			</td>
		</tr>
	</table>
<?php
}
add_action('show_user_profile', 'rb_camposextrauser');
add_action('edit_user_profile', 'rb_camposextrauser');

//salva os campos extra no perfil do usuário
function rb_salvarcamposextrauser($user_id){
	if (!current_user_can('edit_user', $user_id)) return FALSE;
	update_user_meta($user_id, 'adtopopost', $_POST['adtopopost']);
	update_user_meta($user_id, 'adrodapepost', $_POST['adrodapepost']);	
}
add_action('personal_options_update', 'rb_salvarcamposextrauser');
add_action('edit_user_profile_update', 'rb_salvarcamposextrauser');

//adiciona campos extras para links de contatos dos usuários
function rb_userlink($linkscontato){
	$linkscontato['twitter'] = 'Twitter';
	$linkscontato['facebook'] = 'Facebook';
	$linkscontato['linkedin'] = 'LinkedIn';
	$linkscontato['googleplus'] = 'Google+';
	return $linkscontato;	
}
add_filter('user_contactmethods', 'rb_userlink', 10,1);

//adiciona o ligthbox automaticamente nas img dos posts
function rb_addlightbox($content) {
       global $post;
       $pattern ="/<a(.*?)href=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>/i";
       $replacement = '<a$1href=$2$3.$4$5 class="lightbox" title="'.$post->post_title.'"$6>';
       $content = preg_replace($pattern, $replacement, $content);
       return $content;
}
add_filter('the_content', 'rb_addlightbox');


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
		<p>			
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('ocultar_page') ?>" name="<?php echo $this->get_field_name('ocultar_page'); ?>" <?php if(isset($ocultar_page)) echo 'checked="checked"';?>/>
			<label for="<?php echo $this->get_field_id('ocultar_page') ?>">Ocultar na páginas (page.php)</label>			
		</p>
		<?php
	}
	
	public function widget($args,$instancia){
		extract($args);
		extract($instancia);
		if (isset($ocultar_single)&& is_single()) return;
		if (isset($ocultar_page)&& is_page()) return;
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

//gera um form de contato para posts ou páginas via shortcode
function rb_formcontatosc( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'destinatario' => get_bloginfo('admin_email'),
	), $atts ) );
	if (isset($_POST['antisp']) && $_POST['antisp'] == 'cef'):
		$nome = trim($_POST['nome']);
		$email = trim($_POST['email']);
		$assunto = trim($_POST['assunto']);
		$mensagem = trim(stripslashes($_POST['mensagem']));
		if($nome == '' || $email == '' || $assunto == '' || $mensagem == ''):
			$aviso = '<div data-alert class="alert-box warning">Todos os campos são obrigatórios, por favor informe todos os dados solicitados!</div>';
			$validado = FALSE;
		elseif(!preg_match(
'/^[^0-9][a-zA-Z0-9_-]+([.][a-zA-Z0-9_-]+)*[@][a-zA-Z0-9_-]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/',
$email)):
			$aviso = '<div data-alert class="alert-box warning">Informe um endereço de e-mail válido!</div>';
			$validado = FALSE;
		else:
			$validado = TRUE;
		endif;
		if($validado == TRUE):
			$headers[] = 'From: '.$nome.' <'.$email.'>';
			$mensagem .= "\n\nEste email foi enviado através do site ".get_bloginfo('name')." em ".date('d/m/Y \a\s H:i');
			if (wp_mail($destinatario, $assunto, $mensagem, $headers)):
				$aviso = '<div data-alert class="alert-box success">Sua mensagem foi enviada com sucesso! Assim que possível iremos responder.</div>';
				$assunto = '';
				$mensagem = '';
				unset($_POST);
			endif;
		endif;
	else:
		(isset($_POST['nome'])) ? $nome = $_POST['nome'] : $nome = '';
		(isset($_POST['email'])) ? $email = $_POST['email'] : $email = '';
		(isset($_POST['assunto'])) ? $assunto = $_POST['assunto'] : $assunto = '';
		(isset($_POST['mensagem'])) ? $mensagem = $_POST['mensagem'] : $mensagem = '';
		if (isset($_POST['antisp']) && $_POST['antisp'] != 'cef') $aviso = '<div data-alert class="alert-box warning">Sua mensagem não foi enviada por suspeita de spam, tente escrevê-la novamente!</div>';
	endif;
	$retorno = '';
	if(isset($aviso)) $retorno .= $aviso;
	$retorno .= '
		<form data-abide method="post" action="">
			<div class="row">
				<div class="small-8 columns">
					<label>Seu nome:<input type="text" required name="nome" value="'.$nome.'" /></label>
					<small class="error">Campo obrigatório: informe seu nome.</small>
				</div>
			</div>
			<div class="row">
				<div class="small-8 columns">
					<label>Seu email:<input type="email" required name="email" value="'.$email.'" /></label>
					<small class="error">Campo obrigatório: informe um email válido</small>
				</div>
			</div>
			<div class="row">
				<div class="small-8 columns">
					<label>Assunto:<input type="text" required name="assunto" value="'.$assunto.'" /></label>
					<small class="error">Campo obrigatório: informe o assunto</small>
				</div>
			</div>
			<div class="row">
				<div class="small-12 columns">
					<label>Mensagem:<textarea name="mensagem" required id="comment">'.$mensagem.'</textarea></label>
					<small class="error">Campo obrigatório: digite a mensagem</small>
				</div>
			</div>
			<div class="row">
				<div class="small-12 columns">
					<input type="hidden" value="abc" name="antisp" id="spamblock">
					<input type="submit" id="btnenviar" value="Enviar Mensagem" class="button small">
				</div>
			</div>
		</form>
	';
	return $retorno;
}
add_shortcode('formcontato', 'rb_formcontatosc');
