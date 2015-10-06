<?php 
$nome_app = 'Configurações do Tema';
$prefix_app= 'rb_';
global $dir_tema;
$path_app = $dir_tema.'admin/';

if (is_admin()) {
	$tela = array(
		array(
			'nome'=>$nome_app,
			'tipo'=>'titulo',						
		),
		array(
			'nome'=>'Redes Sociais',
			'tipo'=>'sessao',				
		),
		array(
			'tipo'=>'abrir',									
		),
		array(
			'nome'=>'RSS',
			'tipo'=>'text',
			'desc'=>'Link para seu feed rss',
			'id'=>$prefix_app.'rssurl',
			'std'=> get_bloginfo('rss2_url'),
		),
		array(
			'tipo'=>'fechar',									
		),
		array(
			'nome'=>'Redes Sociais2',
			'tipo'=>'sessao',				
		),
		array(
			'tipo'=>'abrir',									
		),
		array(
			'nome'=>'RSS',
			'tipo'=>'textarea',
			'desc'=>'Link para seu feed rss',
			'id'=>$prefix_app.'rssurl2',
			'std'=> get_bloginfo('rss2_url'),
		),
		array(
			'tipo'=>'fechar',									
		),
	);
	
	//gera tela de gerenciamento de configurações dinamicamente
	function rb_geratelaadm(){
		global $prefix_app, $tela, $path_app;
		if (isset($_GET['saved']) && $_GET['saved'] =='true') {
			echo '<div class="updated fade"><p><strong>Configurações salvas com sucesso!</strong></p></div>';
		}
		?>
		<div class="wrap rb-wrap">
			<form method="post">
				<?php
				$i = 0;
				foreach ($tela as $item) {
					switch ($item['tipo']) {
						case 'titulo':
							echo '<h2>'.$item['nome'].'</h2>';
							break;
						case 'sessao':
							$i++;
							?>
							<div class="rb-section">
								<div class="rb-title">
									<h3><img src="<?php echo $path_app.'trans.gif'; ?>" alt="" class="inactive" /><?php echo $item['nome']; ?></h3>
									<span class="submit"><input type="submit" value="Salvar Mudanças" name="save<?php echo $i; ?>" /></span>
									<div class="clearfix"></div>
								</div>								
							<?php
								break;
							case 'abrir':
								echo '<div class="rb-opts">';
								break;
							case 'fechar':
								echo '</div></div><br />';
								break;
							case 'text':
								?>
								<div class="rb-input">
									<label><?php echo $item['nome']; ?></label>
									<input type="text" name="<?php echo $item['id']; ?>" value="<?php echo rb_getconfig($item); ?>" />
									<small><?php echo $item['desc'];?></small>
									<div class="clearfix"></div>
								</div>
								<?php
								break;
								case 'textarea':
								?>
								<div class="rb-input">
									<label><?php echo $item['nome']; ?></label>
									<textarea name="<?php echo $item['id']; ?>"><?php echo rb_getconfig($item); ?></textarea>
									<small><?php echo $item['desc'];?></small>
									<div class="clearfix"></div>
								</div>
								<?php
								break;
						}
					}
			?>
				<input type="hidden" name="action" value="save"/>
				</form>
			</div>
			<?php			
	}

	//salvar as configurações no banco e criar o menu no painel
	function rb_salvaropcoes(){
		global $nome_app, $prefix_app, $tela;
		if (isset($_GET['page']) && $_GET['page'] == 'paineladmin') {
			if (isset($_POST['action'])&& $_POST['action'] == 'save') {
				foreach ($tela as $item) {
					if (isset($item['id']) && $_POST[$item['id']]!='') {
						update_option($item['id'], $_POST[$item['id']]);
					}elseif(isset($item['id']) && $_POST[$item['id']]==''){
						delete_option($item['id']);
					}
				}
				header("Location: admin.php?page=paineladmin&saved=true");
				die;
			}
		}
		add_theme_page($nome_app, 'Configs do Tema', 'administrator', 'paineladmin', 'rb_geratelaadm');		
	}
	add_action('admin_menu', 'rb_salvaropcoes');
	
	//inclui os arquivos CSS e JS do painel
	function rb_scriptsadm(){
		global $path_app;
		wp_register_style('css-admin', $path_app.'style.css', array(),'1.0','all');
		wp_register_script('js-admin', $path_app.'script.js', array(),'1.0');
		wp_enqueue_style('css-admin');
		wp_enqueue_script('js-admin');
	}
	add_action('admin_init', 'rb_scriptsadm');
	
	//pega as configurações do banco para exibir no formulário
	function rb_getconfig($item=null, $getstd='true'){
		if (is_array($item) && sizeof($item)>0) {
			$output = stripslashes(get_option($item['id']));
			if ($output == '' && $getstd == TRUE && isset($item['std'])) {
				$output = $item['std'];			
			}
			return $output;
		}
		return NULL;
	}
	
	
}
























