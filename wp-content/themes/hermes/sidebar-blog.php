<?php if (is_active_sidebar('blog')) { ?> 
				<div class="col-xs-12 col-md-3 sidebar-blog" id="secondary">
					<?php do_action('before_sidebar'); ?> 
					<?php dynamic_sidebar('blog'); ?> 
				</div>
<?php } ?> 