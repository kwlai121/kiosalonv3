<?php
/**
 * The template for displaying Author Archive pages
 *
 * @package LionThemes
 * @subpackage Hermes_theme
 * @since Hermes Themes 1.7
 */

get_header();

$hermes_opt = get_option( 'hermes_opt' );

if(isset($hermes_opt)){
	$bloglayout = 'nosidebar';
} else {
	$bloglayout = 'sidebar';
}
if(isset($hermes_opt['blog_layout']) && $hermes_opt['blog_layout']!=''){
	$bloglayout = $hermes_opt['blog_layout'];
}
if(isset($_GET['layout']) && $_GET['layout']!=''){
	$bloglayout = $_GET['layout'];
}
$blogsidebar = 'right';
if(isset($hermes_opt['sidebarblog_pos']) && $hermes_opt['sidebarblog_pos']!=''){
	$blogsidebar = $hermes_opt['sidebarblog_pos'];
}
switch($bloglayout) {
	case 'sidebar':
		$blogclass = 'blog-sidebar';
		$blogcolclass = 9;
		break;
	default:
		$blogclass = 'blog-nosidebar';
		$blogcolclass = 12;
		$blogsidebar = 'none';
}
if(isset($_GET['side']) && $_GET['side']!=''){
	$blogsidebar = $_GET['side'];
	$blogcolclass = 9;
}
$coldata = 3;
if(!isset($hermes_opt['blog_column'])){
	$blogcolumn = 'col-sm-12';
	$col_class = 'one';
}else{
	$blogcolumn = 'col-sm-' . $hermes_opt['blog_column'];
	switch($hermes_opt['blog_column']) {
		case 6:
			$col_class = 'two';
			$coldata = 2;
			break;
		case 4:
			$col_class = 'three';
			$coldata = 3;
			break;
		case 3:
			$col_class = 'four';
			$coldata = 4;
			break;
		default:
			$col_class = 'one';
			$coldata = 1;
	}
	
}
if(isset($_GET['col']) && $_GET['col']!=''){
	$col = $_GET['col'];
	switch($col) {
		case 2:
			$blogcolumn = 'col-sm-6';
			$col_class = 'two';
			$coldata = 2;
			break;
		case 3:
			$blogcolumn = 'col-sm-4';
			$col_class = 'three';
			$coldata = 3;
			break;
		case 4:
			$blogcolumn = 'col-sm-3';
			$col_class = 'four';
			$coldata = 4;
			break;
		default:
			$blogcolumn = 'col-sm-12';
			$col_class = 'one';
			$coldata = 1;
	}
}

$hermes_opt['blogcolumn'] = $blogcolumn;

update_option( 'hermes_opt', $hermes_opt );

?>
<div class="main-container page-wrapper">
	<div class="container">
		<header class="entry-header">
			<div class="container">
				<h1 class="entry-title"><?php if(isset($hermes_opt)) { echo esc_html($hermes_opt['blog_header_text']); } else { esc_html_e('Blog', 'hermes');}  ?></h1>
			</div>
		</header>
		<div class="row">
			<div class="col-xs-12">
				<?php hermes_breadcrumb(); ?>
			</div>
			<?php if($blogsidebar == 'left') :?>
				<?php get_sidebar('blog'); ?>
			<?php endif; ?>
			<div class="col-xs-12 <?php echo 'col-md-'.$blogcolclass; ?> content-area" id="main-column">
				<?php if ( have_posts() ) : ?>

					<?php
						the_post();
					?>

					<header class="archive-header">
						<h1 class="archive-title"><?php printf( esc_html__( 'Author Archives: %s', 'hermes' ), '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' ); ?></h1>
					</header><!-- .archive-header -->

					<?php
						rewind_posts();
					?>

					<?php
					// If a user has filled out their description, show a bio on their entries.
					if ( get_the_author_meta( 'description' ) ) : ?>
					<div class="author-info archives">
						<div class="author-avatar">
							<?php
							$author_bio_avatar_size = apply_filters( 'hermes_author_bio_avatar_size', 68 );
							echo get_avatar( get_the_author_meta( 'user_email' ), $author_bio_avatar_size );
							?>
						</div><!-- .author-avatar -->
						<div class="author-description">
							<h2><?php printf( esc_html__( 'About %s', 'hermes' ), get_the_author() ); ?></h2>
							<p><?php the_author_meta( 'description' ); ?></p>
						</div><!-- .author-description	-->
					</div><!-- .author-info -->
					<?php endif; ?>
				<?php endif; ?>
				
				
				<?php /* Start the Loop */ ?>
				<main id="main" class="blog-page blog-<?php echo esc_attr($col_class); ?>-column<?php echo  esc_attr(($blogsidebar != 'none') ? '-' .$blogsidebar : ''); ?> site-main">
					<?php if ( have_posts() ) : ?>
					<div class="row<?php echo esc_attr(($coldata > 1 && !empty($hermes_opt['enable_autogrid'] )) ? ' auto-grid':''); ?>" data-col="<?php echo esc_attr($coldata) ?>">
					<?php while ( have_posts() ) : the_post(); ?>
						<div class="col-xs-12 col-md-6 shuffle-item">
						<?php get_template_part( 'content', get_post_format() ); ?>
						</div>
					<?php endwhile; ?>
					</div>
					<?php hermes_bootstrap_pagination(); ?>
					<?php else : ?>
						<?php get_template_part( 'content', 'none' ); ?>
					<?php endif; ?>
				</main>
			</div>
			<?php if($blogsidebar == 'right') :?>
				<?php get_sidebar('blog'); ?>
			<?php endif; ?>
		</div>
		
	</div>
</div>
<?php get_footer(); ?>