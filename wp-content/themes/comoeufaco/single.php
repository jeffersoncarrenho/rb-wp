<?php get_header(); the_post();?>

<div class="row content-sidebar"><!--conteudo + sidebar-->
	<div class="large-8 columns">
		<article>
			<div class="row">
				<h1><?php the_title(); ?></h1>
				<div class="medium-7 large-6 columns">
					<img src="img/ad300x250.gif" alt="" />	
				</div>
				<div class="medium-5 large-6 columns post-meta">
					<p><small>Publicado em: </small><br /><strong><?php the_time('d/m/Y'); ?></strong></p>
					<p><small>Categoria(s): </small><br /><strong><a href=""><?php the_category(', '); ?></a></strong></p>
					<p><small>Autor: </small><br /><strong><a href=""><?php the_author_posts_link(); ?></a></strong></p>
					<p><small>Interação:</small><br /><strong><?php comments_popup_link('nenhum comentário', '1 comentário', '% comentários'); ?></strong></p>
				</div>				
			</div>
			<div class="row">
				<div class="large12 columns post-content">
					<?php the_content(); ?>	
					<p><?php the_tags('Mais Sobre: '); ?></p>		
				</div>
			</div>
		</article>
		<section>
			<div class="row box-single">
				<h4>Compartilhe este post</h4>
				<div class="large-12 columns">
					<?php rb_botoessociais(); ?>
				</div>	
			</div>
		</section>
		<section>
			<div class="row box-single">
				<h4>Posts relacionados</h4>
				<div class="medium-6 large-6 columns relacionados">
					<?php rb_relacionados(); ?>
				</div>
				<div class="medium-6 large-6 columns">
					<img src="img/ad300x250.gif" alt="" />
				</div>
			</div>
		</section>
		<section>
			<div class="row box-single">
				<h4>Sobre o autor</h4>
				<div class="small-2 columns">
					<?php echo get_avatar(get_the_author_meta('ID'),100); ?>
				</div>
				<div class="small-10 columns">
					<div class="row">
						<div class="medium-6 columns">
							<h5><strong><?php the_author(); ?></strong></h5>
						</div>
						<div class="medium-6 columns hide-for-small">
							 <ul class="inline-list right">
							 	<li><a href=""><img src="img/trans.gif" alt="" /></a></li>
							 	<li><a href=""><img src="img/trans.gif" alt="" /></a></li>
							 	<li><a href=""><img src="img/trans.gif" alt="" /></a></li>
							 	<li><a href=""><img src="img/trans.gif" alt="" /></a></li>
							 	<li><a href=""><img src="img/trans.gif" alt="" /></a></li>
							 </ul>
						</div>
					</div>
					<div class="row">
						<div class="small-12 columns">
							<p><?php the_author_meta('description'); ?></p>
						</div>
					</div>								
				</div>												
			</div>			
		</section>
		<section>	
			<div class="row box-single">
				<?php comments_template(); ?>
			</div>
		</section>
		
	</div><!--loop de posts ou conteudo-->
	<?php get_sidebar(); ?>
	<?php get_footer(); ?>