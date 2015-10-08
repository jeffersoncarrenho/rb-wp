	<div class="large-4 columns">
		<aside>
			<div class="row widget-box">
				<div class="large-12 columns">
					<?php global $link_blog; ?>
					<form action="<?php echo $link_blog; ?>" method="GET">
						<div class="row collapse">
							<div class="small-8 medium-9 large-10 columns">
						    	<input type="text" name="s" id="s" placeholder="Pesquisar">
						    </div>
						    <div class="small-4 medium-3 large-2 columns">
							    <input type="submit" value="IR" class="button prefix" />
						    </div>
						</div>
					</form>
				</div>
			</div>
			
			<?php dynamic_sidebar('Sidebar Principal'); ?>			
			
		</aside>
	</div><!--sidebar-->
	</div><!--conteudo + sidebar-->