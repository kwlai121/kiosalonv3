<?php
/**
 * The template for displaying image attachments.
 *
 * @package LionThemes
 * @subpackage Hermes_Themes
 * @since Hermes Themes 1.7
 */

get_header();
?> 
				<div class="col-md-12 content-area image-attachment" id="main-column">
					<main id="main" class="site-main">
						<?php 
						while (have_posts()) {
							the_post(); 
						?> 

						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							<header class="entry-header">
								<?php the_title('<h1 class="entry-title">', '</h1>'); ?> 

								<div class="entry-meta">
									<?php
										$metadata = wp_get_attachment_metadata();
										printf(wp_kses(__('Published <span class="entry-date"><time class="entry-date" datetime="%1$s">%2$s</time></span> at <a href="%3$s" title="Link to full-size image">%4$s &times; %5$s</a> in <a href="%6$s" title="Return to %7$s" rel="gallery">%8$s</a>', 'hermes'), array('span', 'time', 'a')),
											esc_attr(get_the_date('c')),
											esc_html(get_the_date()),
											esc_url(wp_get_attachment_url()),
											$metadata['width'],
											$metadata['height'],
											esc_url(get_permalink($post->post_parent)),
											esc_attr(strip_tags(get_the_title($post->post_parent))),
											get_the_title($post->post_parent)
										);

										echo ' ';
										hermes_bootstrap_edit_post_link();
									?> 
								</div><!-- .entry-meta -->

								<ul id="image-navigation" class="image-navigation pager">
									<li class="nav-previous previous"><?php previous_image_link(false, wp_kses(__('<span class="meta-nav">&larr;</span> Previous', 'hermes'), array('span'))); ?></li>
									<li class="nav-next next"><?php next_image_link(false, wp_kses(__('Next <span class="meta-nav">&rarr;</span>', 'hermes'), array('span'))); ?></li>
								</ul><!-- #image-navigation -->
							</header><!-- .entry-header -->

							<div class="entry-content">
								<div class="entry-attachment">
									<div class="attachment">
										<?php hermes_bootstrap_attached_image(); ?> 
									</div><!-- .attachment -->

									<?php if (has_excerpt()) { ?> 
									<div class="entry-caption">
										<?php the_excerpt(); ?> 
									</div><!-- .entry-caption -->
									<?php } //endif; ?> 
								</div><!-- .entry-attachment -->

								<?php
								the_content();
								
								/**
								 * This wp_link_pages option adapt to use bootstrap pagination style.
								 * The other part of this pager is in inc/template-tags.php function name hermes_bootstrap_link_pages_link() which is called by wp_link_pages_link filter.
								 * 
								 * NOPE! NO! DON'T! This function really does not works with WordPress image attachment page.
								 * @todo Check again that WordPress already fix pages in image.php bug and re-enable it. (Last WP version checked but still not work is 3.7.1)
								*/
								/* wp_link_pages(array(
									   'before' => '<div class="page-links">' . esc_html__('Pages:', 'hermes') . ' <ul class="pagination">',
									   'after'  => '</ul></div>',
									   'separator' => ''
								));*/
								?> 
							</div><!-- .entry-content -->

							<?php hermes_bootstrap_edit_post_link(); ?> 
						</article><!-- #post-## -->

						<?php
							// If comments are open or we have at least one comment, load up the comment template
							if (comments_open() || '0' != get_comments_number()) {
								comments_template();
							}
						?> 

						<?php 
						} //endwhile; // end of the loop. 
						?> 
					</main>
				</div>
<?php get_footer(); ?> 