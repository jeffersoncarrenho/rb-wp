<?php global $nome_blog, $link_blog, $dir_tema; ?>
<footer>
	<div class="row rodape-box">
				
		<?php dynamic_sidebar('Rodapé do Site'); ?>
				
	</div><!--rodape 3 blocos-->
	<div class="row rodape-copyright">
		<div class="medium-6 large-6 columns">
			<?php echo rb_mostra_menu('nav-rodape'); ?>
		</div>
		<div class="medium-6 large-6 columns text-right">
			<p>Copyright 2015 - <a href="<?php echo $link_blog; ?>"><?php echo $nome_blog; ?></a> | Todos os direitos reservados</p>
		</div>
	</div><!--rodape copyright-->
	<?php wp_footer(); ?>
	<script>
		var root = '<?php echo $dir_tema;?>';
	</script>
</footer>
	<?php 
		if(!is_user_logged_in()):
			echo rb_getopcao('analytics');
		endif;
	?>
	
</body>
</html>