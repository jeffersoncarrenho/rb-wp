<?php get_header(); the_post();?>

<div class="row content-sidebar"><!--conteudo + sidebar-->
	<div class="large-8 columns">
		<article>
			<div class="row">
				<h1><?php the_title(); ?></h1>
			</div>
			<div class="row">
				<div class="large12 columns post-content">
					<?php the_content(); ?>						
				</div>
			</div>
		</article>	
	</div><!--loop de posts ou conteudo-->
	<?php get_sidebar(); ?>
	<?php get_footer(); ?>