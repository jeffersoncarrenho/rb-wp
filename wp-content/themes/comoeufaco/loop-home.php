<?php 
	global $dir_tema, $link_blog, $WP_Query;
	$query_destaque = new WP_Query('showposts=1');
?>

<section>
	<?php if($query_destaque->have_posts()):while($query_destaque->have_posts()): $query_destaque->the_post(); ?>
	<div class="row destaque-home">
		<div class="medium-4 large-4 columns">
			<a href="<?php the_permalink(); ?>" class="th"><?php rb_thumb(300,250);?></a>
		</div>
		<div class="medium-8 large-8 columns">
			<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<?php rb_resumopost(30, ''); ?>
			<ul class="inline-list show-for-large-up">
				<li><small>Publicado em: </small><?php the_time('d/m/Y'); ?></li>
				<li><small>Categoria(s)</small><?php the_category(', '); ?></li>
				<li><small>Autor</small><?php the_author_posts_link(); ?></li>
				<li><small>Interação</small><?php comments_popup_link('nenhum comentário', '1 comentário', '% comentários'); ?></li>
			</ul>
			<a href="<?php the_permalink(); ?>" class="button small secondary radius">Continue Lendo &raquo;</a>
			 
		</div>
	</div><!--post destaque-->
	<?php endwhile; endif; ?>
	<div class="row ad-home">
		<div class="large-12 columns text-center">
			<?php echo rb_getopcao('adhome'); ?>
		</div>
	</div><!--publicidade-->
</section>


<div class="row content-sidebar"><!--conteudo + sidebar-->
	<div class="large-8 columns">
		<?php 
			$por_pagina = get_option('posts_per_page')-1;
			$query_home = new WP_Query("showposts={$por_pagina}&offset=1");
			if($query_home->have_posts()):while($query_home->have_posts()): $query_home->the_post();
		?>
		<section>
			<div class="row post-list">
				<div class="large-12 columns">
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				</div>
				<div class="small-4 medium-3 large-4 columns">
					<a href="<?php the_permalink(); ?>" class="th"><?php rb_thumb(200,165);?></a>
				</div>
				<div class="small-8 medium-9 large-8 columns">
					<?php rb_resumopost(30, ''); ?>
					<a href="<?php the_permalink(); ?>" class="button tiny secondary">Continue Lendo &raquo;</a>
				</div>
			</div>
		</section>
		<?php endwhile; endif; ?>
		
		<div class="row paginacao">
			<?php rb_paginacao(); ?>			
		</div>				
	</div><!--loop de posts ou conteudo-->