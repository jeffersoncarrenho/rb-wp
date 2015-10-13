<?php function custom_comments($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?>>
		<article id="comment-<?php comment_ID(); ?>">
			<header class="comment-author">
				<?php echo get_avatar($comment, $size='75'); ?>
				<div class="author-meta">
					<?php printf('<cite class="fn"><strong>%s</strong> comentou em</cite>', get_comment_author_link()) ?>
					<time datetime="<?php echo comment_date('c') ?>"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf('%1$s', get_comment_date(),  get_comment_time()) ?></a></time>
					<?php edit_comment_link('Editar', '', '') ?>
				</div>
			</header>
			
			
			<section class="comment">
				<?php comment_text() ?>
				<?php if ($comment->comment_approved == '0'): ?>
		   			<div class="alert-box alert radius text-center">Seu comentário está aguardando a revisão de um administrador!</div>
				<?php endif; ?>
				<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
			</section>
		</article>
<?php } ?>

<?php
//impede o acesso direto ao arquivo
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Esta página não pode ser acessada diretamente');

//verifica se o post não é protegido por senha
if(post_password_required()):
	echo '<p class="nocomments">Post protegido, digite a senha para ver os comentários.</p>';
	return;
endif;
?>
<!-- A partir daqui você pode editar -->
<div class="row collapse">
	<a name="comments" id="comments"></a>
	<!-- se existirem comentários para mostrar -->
	<?php if (have_comments()): ?>
		<h4>Comentários: <?php comments_number('0','1', '%');?></h4>
		<ul class="no-bullet comlist">
			<?php
				$args = array(
					'avatar_size'       => 60,
					'style'             => 'ul',					
					'format'            => 'html5',
					'type'				=> 'comment',
					'callback'			=> 'custom_comments',
				);
				wp_list_comments($args);
			?>
		</ul>
		
		<dl class="sub-nav">
			<dd class="left"><a href="#"><?php previous_comments_link('&laquo; Página anterior') ?></a></dd>
			<dd class="right"><a href="#"><?php next_comments_link('Próxima página &raquo;') ?></a></dd>
		</dl>
	<!-- se não existirem comentários para mostrar -->
	<?php else: ?>
		<h4>Comentários</h4>
		<?php if($post->comment_status == 'open'): ?>
			<p>Nenhum comentário foi publicado para este post. <a href="#respond">Seja o primeiro a comentar...</a></p>
		<!-- se os comentários estão proibidos -->
		<?php else: ?>
			<p>Atualmente não são permitidos comentários neste post.</p>
		<?php endif; ?>
	<?php endif; ?>
</div>

<?php if($post->comment_status == 'open'): ?>
	<div class="row collapse" id="respond">
		<h4>Deixe seu comentário</h4>
		<div class="cancel-comment-reply"> 
			<p><?php cancel_comment_reply_link(); ?></p>
		</div>
		
		<?php if(get_option('comment_registration') && !$user_ID): ?>
			<p>Você precisa estar <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">logado</a> para comentar.</p>
		<?php else: ?>
			<div class="small-12 columns">
				<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform" name="commentform">
  				<?php if($user_ID): ?>
  					<p>Logado como <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Logoff">Logoff &raquo;</a></p>
  				<?php else: ?>
  					<div class="row">
						<div class="small-8 columns"><input type="text" name="author" value="<?php echo $comment_author; ?>" tabindex="1" placeholder="Seu nome <?php if($req) echo '(obrigatório)'; ?>" /></div>
					</div>
					<div class="row">
						<div class="small-8 columns"><input type="text" name="email" value="<?php echo $comment_author_email; ?>" tabindex="2" placeholder="Seu email <?php if($req) echo '(obrigatório)'; ?>" /></div>
					</div>
					<div class="row">
						<div class="small-8 columns"><input type="text" name="url" value="<?php echo $comment_author_url; ?>" tabindex="3" placeholder="Site (opcional)" /></div>
					</div>
				<?php endif; ?>
				<div class="row">
					<div class="small-12 columns"><textarea name="comment" id="comment" tabindex="4" placeholder="Comentário"></textarea></div>
				</div>
				<div class="row">
					<div class="small-12 columns">
						<input type="submit" class="button small radius" value="Publicar meu comentário" onclick="document.commentform.submit();" />
						<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
						<?php comment_id_fields(); ?>
						<?php do_action('comment_form', $post->ID); ?>
					</div>
					
				</div>
				<!-- <p>Você pode usar estas tags em seu comentário: <?php echo allowed_tags(); ?></p> -->
				</form>
			</div>
		<?php endif; ?>
	</div>
<?php endif; ?>