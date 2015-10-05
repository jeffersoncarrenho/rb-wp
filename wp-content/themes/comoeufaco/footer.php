<?php global $nome_blog, $link_blog; ?>
<footer>
	<div class="row rodape-box">
		<div class="large-4 columns">
			<h5>Assuntos</h5>
			<p>nuvem de tags</p>
		</div>
		<div class="large-4 columns">
			<iframe frameborder="0" allowtransparency="true" style="border:none !important; overflow:hidden; width:300px; height:298px;" scrolling="no" src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fcomoeufaco&width=300&height=298&colorscheme=light&show_faces=true&border_color=%23ffffff&stream=false&header=false&appId=131627786881234">
				
			</iframe>
		</div>
		<div class="large-4 columns">
			<h5>Atualizações</h5>
			<ul class="side-nav">
				<li><a href="">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</a></li>
				<li><a href="">Error repellat reiciendis eius veritatis quos in aliquid.</a></li>
				<li><a href="">Impedit minus illo tenetur ut laborum. Sed amet.</a></li>
				<li><a href="">Nisi atque necessitatibus delectus cupiditate aut minima consectetur.</a></li>
				<li><a href="">Natus cum eum tenetur quia voluptatem veritatis ipsa.</a></li>
			</ul>
		</div>
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
</footer>
</body>
</html>