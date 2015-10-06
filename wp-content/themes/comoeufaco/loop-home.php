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
			<img src="img/ad728x90.gif" alt="" />
		</div>
	</div><!--publicidade-->
</section>

<div class="row content-sidebar"><!--conteudo + sidebar-->
	<div class="large-8 columns">
		<section>
			<div class="row post-list">
				<div class="large-12 columns">
					<h3><a href="">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</a></h3>
				</div>
				<div class="small-4 medium-3 large-4 columns">
					<a href="" class="th"><img src="img/thumb2.jpg" alt="" /></a>
				</div>
				<div class="small-8 medium-9 large-8 columns">
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptates repudiandae delectus saepe aut quis officia quas accusamus corrupti aperiam voluptas similique distinctio optio tempora. Pariatur excepturi omnis odit quisquam sunt.</p>
					<a href="" class="button tiny secondary">Continue lendo &raquo;</a>
				</div>
			</div>
		</section>
		<section>
			<div class="row post-list">
				<div class="large-12 columns">
					<h3><a href="">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</a></h3>
				</div>
				<div class="small-4 medium-3 large-4 columns">
					<a href="" class="th"><img src="img/thumb3.jpg" alt="" /></a>
				</div>
				<div class="small-8 medium-9 large-8 columns">
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptates repudiandae delectus saepe aut quis officia quas accusamus corrupti aperiam voluptas similique distinctio optio tempora. Pariatur excepturi omnis odit quisquam sunt.</p>
					<a href="" class="button tiny secondary">Continue lendo &raquo;</a>
				</div>
			</div>
		</section>
		<section>
			<div class="row post-list">
				<div class="large-12 columns">
					<h3><a href="">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</a></h3>
				</div>
				<div class="small-4 medium-3 large-4 columns">
					<a href="" class="th"><img src="img/thumb4.jpg" alt="" /></a>
				</div>
				<div class="small-8 medium-9 large-8 columns">
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptates repudiandae delectus saepe aut quis officia quas accusamus corrupti aperiam voluptas similique distinctio optio tempora. Pariatur excepturi omnis odit quisquam sunt.</p>
					<a href="" class="button tiny secondary">Continue lendo &raquo;</a>
				</div>
			</div>
		</section>
		<section>
			<div class="row post-list">
				<div class="large-12 columns">
					<h3><a href="">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</a></h3>
				</div>
				<div class="small-4 medium-3 large-4 columns">
					<a href="" class="th"><img src="img/thumb5.jpg" alt="" /></a>
				</div>
				<div class="small-8 medium-9 large-8 columns">
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptates repudiandae delectus saepe aut quis officia quas accusamus corrupti aperiam voluptas similique distinctio optio tempora. Pariatur excepturi omnis odit quisquam sunt.</p>
					<a href="" class="button tiny secondary">Continue lendo &raquo;</a>
				</div>
			</div>
		</section>
		
		<section>
			<div class="row post-list">
				<div class="large-12 columns">
					<h3><a href="">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</a></h3>
				</div>
				<div class="small-4 medium-3 large-4 columns">
					<a href="" class="th"><img src="img/thumb1.jpg" alt="" /></a>
				</div>
				<div class="small-8 medium-9 large-8 columns">
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptates repudiandae delectus saepe aut quis officia quas accusamus corrupti aperiam voluptas similique distinctio optio tempora. Pariatur excepturi omnis odit quisquam sunt.</p>
					<a href="" class="button tiny secondary">Continue lendo &raquo;</a>
				</div>
			</div>
		</section>
		
		<div class="row paginacao">
			<div class="pagination-centered">
				<ul class="pagination">
					<li class="arrow unavailable"><a href="">&laquo;</a></li>
					<li class="current"><a href="">1</a></li>
					<li><a href="">2</a></li>
					<li><a href="">3</a></li>
					<li><a href="">4</a></li>
					<li class="unavailable"><a href="">&hellip;</a></li>
					<li><a href="">12</a></li>
					<li><a href="">13</a></li>
				  	<li class="arrow"><a href="">&raquo;</a></li>
				</ul>
			</div>			
		</div>
				
	</div><!--loop de posts ou conteudo-->