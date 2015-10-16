<?php get_header(); global $dir_tema; ?>

<div class="row content-sidebar"><!--conteudo + sidebar-->
	<div class="large-8 columns">
		<article>
			<div class="row">
				<h1>Página não encontrada</h1>
			</div>
			<div class="row">
				<div class="large12 columns post-content">
					<img src="<?php echo $dir_tema;?>/img/404.jpg" alt="" />
					<p>A página que você tentou acessar não foi localizada, isso pode ter acontecido por vários motivos, veja alguns:</p>
					<ul>
						<li>Nós excluímos o conteúdo</li>
						<li>O link que você tentou acessar está errado</li>
						<li>Alguém te sacaneou com um link inexistente</li>
					</ul>
					<p>Mas não fique desapontado, use a caixa de busca na lateral do blog e tente fazer uma pesquisa sobre o assunto, talvez você encontre outros conteúdos semelhantes ou relacionados. Boa Sorte!</p>
						
				</div>
			</div>
		</article>	
	</div><!--loop de posts ou conteudo-->
	<?php get_sidebar(); ?>
	<?php get_footer(); ?>