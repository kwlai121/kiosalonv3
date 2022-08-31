<?php
/**
* Theme specific widgets or widget overrides
*
* @package LionThemes
* @subpackage Hermes_theme
* @since Hermes Themes 1.5
*/
 
/**
 * Register widgets
 *
 * @return void
 */
function hermes_widgets_init() {
	register_sidebar( array(
		'name' => esc_html__( 'Blog Sidebar', 'hermes' ),
		'id' => 'blog',
		'description' => esc_html__( 'Appears on blog page', 'hermes' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s first_last">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title"><span>',
		'after_title' => '</span></h3>',
	) );
	
	register_sidebar( array(
		'name' => esc_html__( 'Shop Sidebar', 'hermes' ),
		'id' => 'shop',
		'description' => esc_html__( 'Sidebar on shop page', 'hermes' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s first_last">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title"><span>',
		'after_title' => '</span></h3>',
	) );

	register_sidebar( array(
		'name' => esc_html__( 'Product Sidebar', 'hermes' ),
		'id' => 'product',
		'description' => esc_html__( 'Sidebar on product detail page', 'hermes' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s first_last">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title"><span>',
		'after_title' => '</span></h3>',
	) );

	register_sidebar( array(
		'name' => esc_html__( 'Top Bar Header', 'hermes' ),
		'id' => 'top_header',
		'description' => esc_html__( 'This area on top bar of header to display language switcher, currency switcher, hotline ...', 'hermes' ),
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h3 class="widget-title"><span>',
		'after_title' => '</span></h3>',
	) );

	
	register_sidebar( array(
		'name' => esc_html__( 'Footer 4 columns', 'hermes' ),
		'id' => 'footer_4columns',
		'description' => esc_html__( 'This area to display 4 widgets for 4 columns, use in Home 1, 2, 3 footer layout.', 'hermes' ),
		'before_widget' => '<div class="col-md-3 col-sm-6">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title"><span>',
		'after_title' => '</span></h3>',
	) );

	register_sidebar( array(
		'name' => esc_html__( 'Footer 3 columns left', 'hermes' ),
		'id' => 'footer_3columns_left',
		'description' => esc_html__( 'This area to display one widget for 3 columns left, use in Home 4, 5 footer layout.', 'hermes' ),
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h3 class="widget-title"><span>',
		'after_title' => '</span></h3>',
	) );

	register_sidebar( array(
		'name' => esc_html__( 'Footer 3 columns center', 'hermes' ),
		'id' => 'footer_3columns_center',
		'description' => esc_html__( 'This area to display 2 widgets for 3 columns center, use in Home 4, 5 footer layout.', 'hermes' ),
		'before_widget' => '<div class="col-md-2 col-sm-6">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title"><span>',
		'after_title' => '</span></h3>',
	) );

	
	register_sidebar( array(
		'name' => esc_html__( 'Footer 3 columns right', 'hermes' ),
		'id' => 'footer_3columns_right',
		'description' => esc_html__( 'This area to display one widget for 3 columns right, use in Home 4, 5 footer layout.', 'hermes' ),
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h3 class="widget-title"><span>',
		'after_title' => '</span></h3>',
	) );

	register_sidebar( array(
		'name' => esc_html__( 'Footer Vertical Newsletter', 'hermes' ),
		'id' => 'footer_newsletter',
		'description' => esc_html__( 'This area to display Newsletter widget for vertical form in footer, use in Home 1, 2 footer layout.', 'hermes' ),
		'before_widget' => '<div class="newletter-form-wrapper">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="newletter-title"><span>',
		'after_title' => '</span></h3>',
	) );

	register_sidebar( array(
		'name' => esc_html__( 'Footer Vertical Menu Links', 'hermes' ),
		'id' => 'footer_vertical_menu',
		'description' => esc_html__( 'This area to display vertical links in footer, use in Home 1, 2 footer layout.', 'hermes' ),
		'before_widget' => '<div class="widget widget_menu">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title"><span>',
		'after_title' => '</span></h3>',
	) );

	register_sidebar( array(
		'name' => esc_html__( 'Footer Payment Support', 'hermes' ),
		'id' => 'footer_payment',
		'description' => esc_html__( 'This area to display footer payment support.', 'hermes' ),
		'before_widget' => '<div class="widget-payment">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title"><span>',
		'after_title' => '</span></h3>',
	) );

	register_sidebar( array(
		'name' => esc_html__( 'Footer Copyright text', 'hermes' ),
		'id' => 'footer_copyright',
		'description' => esc_html__( 'This area to display copyright text.', 'hermes' ),
		'before_widget' => '<div class="widget-copyright">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title"><span>',
		'after_title' => '</span></h3>',
	) );
}
add_action( 'widgets_init', 'hermes_widgets_init' ); 
