<?php get_header(); the_post();?>

<div class="row content-sidebar"><!--conteudo + sidebar-->
	<div class="large-8 columns">
		<article>
			<div class="row">
				<h1><?php the_title(); ?></h1>
				<div class="medium-7 large-6 columns">
					<?php 
						$adtopo = stripslashes(get_the_author_meta('adtopopost'));
						if ($adtopo!='') echo $adtopo;
						else echo rb_getopcao('adtopopost');
					?>	
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
					<?php 
						$adrodape = stripslashes(get_the_author_meta('adrodapepost'));
						if ($adrodape!='') echo $adrodape;
						else echo rb_getopcao('adrodapepost');
					?>
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
							 	<?php
							 		$user_site = get_the_author_meta('user_url');
									$user_twitter = get_the_author_meta('twitter');
									$user_facebook = get_the_author_meta('facebook');
									$user_linkedin = get_the_author_meta('linkedin');
									$user_gplus = get_the_author_meta('googleplus');
									global $dir_tema;
							 	?>
							 	
							 	<?php if($user_site):?>
							 		<li>
							 			<a href="<?php echo $user_site; ?>" title="Site" >
							 				<img src="<?php echo $dir_tema;?>img/trans.gif" alt="" class="autor-link"></a></li>
							 	<?php endif; ?>
							 	<?php if($user_twitter):?>
							 		<li>
							 			<a href="<?php echo $user_twitter; ?>" title="Twitter" >
							 				<img src="<?php echo $dir_tema;?>img/trans.gif" alt="" class="autor-twitter"></a></li>
							 	<?php endif; ?>
							 	<?php if($user_facebook):?>
							 		<li>
							 			<a href="<?php echo $user_facebook; ?>" title="Facebook" >
							 				<img src="<?php echo $dir_tema;?>img/trans.gif" alt="" class="autor-facebook"></a></li>
							 	<?php endif; ?>
							 	<?php if($user_linkedin):?>
							 		<li>
							 			<a href="<?php echo $user_linkedin; ?>" title="LinkedIn" >
							 				<img src="<?php echo $dir_tema;?>img/trans.gif" alt="" class="autor-linkedin"></a></li>
							 	<?php endif; ?>
							 	<?php if($user_gplus ):?>
							 		<li>
							 			<a href="<?php echo user_gplus ; ?>" title="Google+" >
							 				<img src="<?php echo $dir_tema;?>img/trans.gif" alt="" class="autor-gplus"></a></li>
							 	<?php endif; ?>
							 	
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