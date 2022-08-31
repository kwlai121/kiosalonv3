<?php
/**
 * Template Name: Fullwidth Template
 *
 * @package LionThemes
 * @subpackage Hermes_theme
 * @since Hermes Themes 1.7
 */
get_header(); 
?>
	<div id="main-content">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<?php hermes_breadcrumb(); ?>
				</div>
			</div>
		</div>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php 
				the_content(); 
			?>
			
		<?php endwhile; // end of the loop. ?>
	</div>
<?php
get_footer();
