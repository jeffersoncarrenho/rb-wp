<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="pt-br" > <![endif]-->
<html class="no-js" lang="pt-br" >

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo rb_titulos(); ?></title>
  <?php wp_head(); global $dir_tema, $nome_blog, $link_blog;?>
  <link rel="alternate" href="<?php echo rb_getopcao('rssurl'); ?>" type="application/rss+xml" title="<?php echo $nome_blog;?> RSS Feed"/>
  <link rel="alternate" href="<?php echo rb_getopcao('rssurl'); ?>" type="application/atom+xml" title="<?php echo $nome_blog;?> Atom Feed"/>
  <link rel="pingback" href="<?php echo bloginfo('pingback_url'); ?>" />
  <link rel="shortcut icon" href="<?php echo $dir_tema;?>/img/favicon.ico" />
</head>
<body>
<header>
	<!--[if lt IE 9]>
		<style>
			.aviso-ie{margin:20px;text-align:center;font-size:20px}
			.aviso-ie a{color:#047abd;}
			.aviso-ie a:hover{text-decoration:underline;}
		</style>
		<div class="aviso-ie">
			<p>Seu navegador é muito antigo não pode exibir este site corretamente.</p>
			<p>Sugerimos enfaticamente que você <a href="https://browser-update.org/update.html" target="_blank" title="Atualize seu navegador">
				instale um navegador atualizado (clique aqui)</a> para ver este site e aumentar sua segurança na internet.</p>
		</div>
	<![endif]-->
	
	<div class="background">
		<div class="row">
			<div class="medium large-4 columns">
				<a href="<?php echo $link_blog;?>" title="<?php echo $nome_blog; ?>">
					<?php if(is_home()): ?>
						<h1 class="logoh1"><img src="<?php echo $dir_tema; ?>img/logo.png" alt="<?php echo $nome_blog; ?>" title="<?php echo $nome_blog; ?>" /></h1>
					<?php else: ?>
						<img src="<?php echo $dir_tema; ?>img/logo.png" alt="<?php echo $nome_blog; ?>" title="<?php echo $nome_blog; ?>"/>
					<?php endif; ?>				
				</a>
			</div>
			<div class="medium-6 large-8 columns">
				<ul class="medium-block-grid-5 right hide-for-small" id="social">
					<li><a href="<?php echo rb_getopcao('rssurl');?>"><img src="<?php echo $dir_tema; ?>img/trans.gif" alt="" class="ic-rss"/></a></li>
					<li><a href="<?php echo rb_getopcao('rssemail');?>"><img src="<?php echo $dir_tema; ?>img/trans.gif" alt="" class="ic-mail"/></a></li>
					<li><a href="<?php echo rb_getopcao('facebook');?>"><img src="<?php echo $dir_tema; ?>img/trans.gif" alt="" class="ic-facebook"/></a></li>
					<li><a href="<?php echo rb_getopcao('twitter');?>"><img src="<?php echo $dir_tema; ?>img/trans.gif" alt="" class="ic-twitter"/></a></li>
					<li><a href="<?php echo rb_getopcao('youtube');?>"><img src="<?php echo $dir_tema; ?>img/trans.gif" alt="" class="ic-youtube"/></a></li>
				</ul>
			</div><!--Fim logo + sociais-->
		</div>
		
		<div class="row">
			<nav class="top-bar" data-topbar role="navigation">
				<ul class="title-area">
					<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
				</ul>
			  	<section class="top-bar-section">
				    <!-- Right Nav Section -->
					<?php echo rb_mostra_menu('nav-topo'); ?>
					
					<!--<ul class="right">
						<li class="active"><a href="#">Home</a></li>
				      	<li class="has-dropdown"><a href="#">Categoria 1</a>
				      		<ul class="dropdown">
				          		<li><a href="#">Subcategoria 1</a></li>
				          		<li><a href="#">Subcategoria 2</a></li>
				          		<li><a href="#">Subcategoria 3</a></li>
				        	</ul>			        	
				      	</li>
				      	<li><a href="#">Categoria 2</a></li>
				        <li><a href="#">Categoria 3</a></li>
				 </ul>-->
				</section>
			</nav>
		</div><!--menu de navegação-->
	</div><!--Fim background-->
</header>