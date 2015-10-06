<div class="row content-sidebar"><!--conteudo + sidebar-->
	<div class="large-8 columns">
	<?php if(is_day()): ?>
		<h1 class="subheader"><small>Posts de:</small><?php the_time('j \d\e F \d\e Y'); ?></h1>
	<?php elseif(is_month()): ?>
		<h1 class="subheader"><small>Posts de:</small><?php the_time('F \d\e Y'); ?></h1>
	<?php elseif(is_year()): ?>
		<h1 class="subheader"><small>Posts de:</small><?php the_time('Y'); ?></h1>
	<?php elseif(is_category()): ?>
		<h1 class="subheader"><small>Posts da Categoria:</small><?php single_cat_title('', true); ?></h1>
	<?php elseif(is_tag()): ?>
		<h1 class="subheader"><small>Posts marcados com a tag:</small><?php wp_title('', true, '');?></h1>
	<?php elseif(is_author()): 
		global $author;
		$autor_dados = get_userdata($author);	
	?>	
		<h1 class="subheader"><small>Posts do autor:</small><?php echo $autor_dados->display_name; ?></h1>
	<?php elseif(is_search()): ?>
		<h1 class="subheader"><small>Resultados da busca por:</small><?php the_search_query(); ?></h1>
	<?php endif; ?>
	
		
	<?php if(have_posts()): while(have_posts()): the_post();?>
	<section>
		<div class="row post-list">
			<div class="large-12 columns">
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
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
	<div class="row paginacao">
		<?php rb_paginacao(); ?>			
	</div>
	<?php endwhile; else: ?>
		<section>
			<div class="row post-list">
				<div class="large-12 columns">
					<h3>Nenhum post encontrado</h3>
					<p>Nenhum post foi encontrado com base nos parâmetros fornecidos, tente usar o menu de navagação 
					ou fazer uma pesquisa para localizar o conteúdo desejado.</p>
				</div>			
			</div>
		</section>	
	<?php endif; ?>	
</div><!--loop de posts ou conteudo-->