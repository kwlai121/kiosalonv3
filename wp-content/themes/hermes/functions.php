<?php
/**
 * Hermes Themes functions and definitions
 *
 * @package LionThemes
 * @subpackage Hermes_theme
 * @since Hermes Themes 1.7
 */
//Plugin-Activation
require_once( get_template_directory().'/class-tgm-plugin-activation.php' );

 //Init the Redux Framework
if ( class_exists( 'ReduxFramework' ) && !isset( $redux_demo )){
	if(file_exists( trailingslashit(get_stylesheet_directory()) . 'theme-config.php')){
		require_once( trailingslashit(get_stylesheet_directory()) . 'theme-config.php' );
	}else{
		require_once( trailingslashit(get_template_directory()) . 'theme-config.php' );
	}
}

// require system parts
if ( file_exists( get_template_directory().'/includes/theme-helper.php' ) ) {
	require_once( get_template_directory().'/includes/theme-helper.php' );
}
if ( file_exists( get_template_directory().'/includes/widget-areas.php' ) ) {
	require_once( get_template_directory().'/includes/widget-areas.php' );
}
if ( file_exists( get_template_directory().'/includes/head-media.php' ) ) {
	require_once( get_template_directory().'/includes/head-media.php' );
}
if ( file_exists( get_template_directory().'/includes/bootstrap-extras.php' ) ) {
	require_once( get_template_directory().'/includes/bootstrap-extras.php' );
}
if ( file_exists( get_template_directory().'/includes/bootstrap-tags.php' ) ) {
	require_once( get_template_directory().'/includes/bootstrap-tags.php' );
}
if ( file_exists( get_template_directory().'/includes/woo-hook.php' ) ) {
	require_once( get_template_directory().'/includes/woo-hook.php' );
}

// theme setup
function hermes_setup(){
	// Load languages
	load_theme_textdomain( 'hermes', trailingslashit(get_template_directory()) . 'languages' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// This theme supports a variety of post formats.
	add_theme_support( 'post-formats', array( 'image', 'gallery', 'video', 'audio' ) );
	
	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	if ( ! isset( $content_width ) ) $content_width = 625;
	
	add_theme_support( 'title-tag' );
	
	// This theme uses a custom image size for featured images, displayed on "standard" posts.
	add_theme_support( 'post-thumbnails' );

	set_post_thumbnail_size( 1170, 9999 ); // Unlimited height, soft crop
	add_image_size( 'hermes-category-thumb', 1170, 650, true ); // (cropped)
	add_image_size( 'hermes-category-full', 1170, 650, true ); // (cropped)
	add_image_size( 'hermes-post-thumb', 1170, 650, true ); // (cropped)
	add_image_size( 'hermes-post-thumbwide', 590, 350, true ); // (cropped)
	
	register_nav_menu( 'primary', esc_html__( 'Primary Menu', 'hermes' ) );
	register_nav_menu( 'mobilemenu', esc_html__( 'Mobile Menu', 'hermes' ) );
	add_theme_support( 'woocommerce' );
	if(class_exists('WooCommerce')){
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}
	
	// for update options 
	$hermes_opt = get_option( 'hermes_opt' );
	if (!isset($hermes_opt['addcart_scrolltop'])) $hermes_opt['addcart_scrolltop'] = 1;
	if (isset($hermes_opt['sticky_header']) && $hermes_opt['sticky_header'] == 1) $hermes_opt['sticky_header'] = 'desktop';
	if (!isset($hermes_opt['addcart_scrolltop']) || (isset($hermes_opt['sticky_header']) && $hermes_opt['sticky_header'] == 1)) {
		update_option('hermes_opt', $hermes_opt);
	}
}
add_action( 'after_setup_theme', 'hermes_setup');

/*
* Theme support
 */
add_theme_support( 'custom-background', array() );
add_theme_support( 'custom-header', array() );
/**
* TGM-Plugin-Activation
*/
add_action( 'tgmpa_register', 'hermes_register_required_plugins'); 
function hermes_register_required_plugins(){
	$plugins = array(
				array(
					'name'               => esc_html__('LionThemes Helper', 'hermes'),
					'slug'               => 'lionthemes-helper',
					'source'             => trailingslashit(get_template_directory()) . 'plugins/lionthemes-helper.zip',
					'required'           => true,
				),
				array(
					'name'               => esc_html__('Mega Main Menu', 'hermes'),
					'slug'               => 'mega_main_menu',
					'source'             => 'https://lion-themes.net/plugins/mega_main_menu.zip',
					'required'           => true,
				),
				array(
					'name'               => esc_html__('Revolution Slider', 'hermes'),
					'slug'               => 'revslider',
					'source'             => 'https://lion-themes.net/plugins/revslider.zip',
					'required'           => true,
				),
				array(
					'name'               => esc_html__('WPBakery Page Builder', 'hermes'),
					'slug'               => 'js_composer',
					'source'             => 'https://lion-themes.net/plugins/js_composer.zip',
					'required'           => true,
				),
				// Plugins from the Online WordPress Plugin
				array(
					'name'               => esc_html__('Redux Framework', 'hermes'),
					'slug'               => 'redux-framework',
					'required'           => true,
				),
				array(
					'name'      => esc_html__('Classic Editor', 'hermes'),
					'slug'      => 'classic-editor',
					'required'  => false,
				),
				array(
					'name'      => esc_html__('Contact Form 7', 'hermes'),
					'slug'      => 'contact-form-7',
					'required'  => false,
				),
				array(
					'name'      => esc_html__('MailPoet Newsletters', 'hermes'),
					'slug'      => 'wysija-newsletters',
					'required'  => false,
				),
				array(
					'name'      => esc_html__('Mailchimp for WordPress', 'hermes'),
					'slug'      => 'mailchimp-for-wp',
					'required'  => false,
				),
				array(
					'name'      => esc_html__('Projects', 'hermes'),
					'slug'      => 'projects-by-woothemes',
					'source'	=> 'https://lion-themes.net/plugins/projects-by-woothemes.zip',
					'required'  => false,
				),
				array(
					'name'      => esc_html__('Shortcodes Ultimate', 'hermes'),
					'slug'      => 'shortcodes-ultimate',
					'required'  => false,
				),
				array(
					'name'      => esc_html__('Testimonials', 'hermes'),
					'slug'      => 'testimonials-by-woothemes',
					'source'	=> 'https://lion-themes.net/plugins/testimonials-by-woothemes.zip',
					'required'  => false,
				),
				array(
					'name'      => esc_html__('TinyMCE Advanced', 'hermes'),
					'slug'      => 'tinymce-advanced',
					'required'  => false,
				),
				array(
					'name'      => esc_html__('Widget Importer & Exporter', 'hermes'),
					'slug'      => 'widget-importer-exporter',
					'required'  => false,
				),
				array(
					'name'      => esc_html__('WooCommerce', 'hermes'),
					'slug'      => 'woocommerce',
					'required'  => true,
				),
				array(
					'name'      => esc_html__('YITH WooCommerce Compare', 'hermes'),
					'slug'      => 'yith-woocommerce-compare',
					'required'  => false,
				),
				array(
					'name'      => esc_html__('YITH WooCommerce Wishlist', 'hermes'),
					'slug'      => 'yith-woocommerce-wishlist',
					'required'  => false,
				),
				array(
					'name'      => esc_html__('YITH WooCommerce Zoom Magnifier', 'hermes'),
					'slug'      => 'yith-woocommerce-zoom-magnifier',
					'required'  => false,
				),
			);
			
	/**
	 * Array of configuration settings. Amend each line as needed.
	 * If you want the default strings to be available under your own theme domain,
	 * leave the strings uncommented.
	 * Some of the strings are added into a sprintf, so see the comments at the
	 * end of each line for what each argument will be.
	 */
	$config = array(
		'default_path' => '',                      // Default absolute path to pre-packaged plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
		'strings'      => array(
			'page_title'                      => esc_html__( 'Install Required Plugins', 'hermes' ),
			'menu_title'                      => esc_html__( 'Install Plugins', 'hermes' ),
			'installing'                      => esc_html__( 'Installing Plugin: %s', 'hermes' ), // %s = plugin name.
			'oops'                            => esc_html__( 'Something went wrong with the plugin API.', 'hermes' ),
			'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'hermes' ),
			'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'hermes' ), // %1$s = plugin name(s).
			'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'hermes' ), // %1$s = plugin name(s).
			'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'hermes' ), // %1$s = plugin name(s).
			'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'hermes' ), // %1$s = plugin name(s).
			'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'hermes' ), // %1$s = plugin name(s).
			'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'hermes' ), // %1$s = plugin name(s).
			'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'hermes' ), // %1$s = plugin name(s).
			'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'hermes' ),
			'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins', 'hermes' ),
			'return'                          => esc_html__( 'Return to Required Plugins Installer', 'hermes' ),
			'plugin_activated'                => esc_html__( 'Plugin activated successfully.', 'hermes' ),
			'complete'                        => esc_html__( 'All plugins installed and activated successfully. %s', 'hermes' ), // %s = dashboard link.
			'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
		)
	);
	tgmpa( $plugins, $config );
}
