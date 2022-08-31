<?php


// Import slider, setup menu locations, setup home page
function lionthemes_wbc_extended_example( $demo_active_import , $demo_directory_path ) {

	reset( $demo_active_import );
	$current_key = key( $demo_active_import );

	// Revolution Slider import all
	if ( class_exists( 'RevSlider' ) ) {
		$wbc_sliders_array = array(
			'Hermes' => array('home-1.zip', 'home-2-slider.zip', 'home-3-slider.zip', 'home4.zip', 'home-5-slider.zip')
		);

		if ( isset( $demo_active_import[$current_key]['directory'] ) && !empty( $demo_active_import[$current_key]['directory'] ) && array_key_exists( $demo_active_import[$current_key]['directory'], $wbc_sliders_array ) ) {
			$wbc_slider_import = $wbc_sliders_array[$demo_active_import[$current_key]['directory']];
			foreach($wbc_slider_import as $file_backup){
				if ( file_exists( $demo_directory_path . $file_backup ) ) {
					$slider = new RevSlider();
					$slider->importSliderFromPost( true, true, $demo_directory_path . $file_backup );
				}
			}
		}
	}
	// menu localtion settings
	$wbc_menu_array = array( 'Hermes' );

	if ( isset( $demo_active_import[$current_key]['directory'] ) && !empty( $demo_active_import[$current_key]['directory'] ) && in_array( $demo_active_import[$current_key]['directory'], $wbc_menu_array ) ) {
		$primary_menu = get_term_by( 'name', 'Main menu', 'nav_menu' );
		
		if ( isset( $primary_menu->term_id ) ) {
			set_theme_mod( 'nav_menu_locations', array(
					'primary' => $primary_menu->term_id,
					'mobilemenu'  => $primary_menu->term_id
				)
			);
		}
	}
	
	// megamenu options
	global $mega_main_menu;
	
	$exported_file = $demo_directory_path . 'mega-main-menu-settings.json';
	
	if ( file_exists( $exported_file ) ) {
		$backup_file_content = file_get_contents ( $exported_file );
		
		if ( $backup_file_content !== false && ( $options_backup = json_decode( $backup_file_content, true ) ) ) {
			update_option( $mega_main_menu->constant[ 'MM_OPTIONS_NAME' ], $options_backup );
		}
	}

	// Home page setup default
	$page_options = array(
		'page_on_front' => 'Home Shop 1',
		'page_for_posts' => 'Blog',
		'projects_page_id' => 'Portfolio',
		'yith_wcwl_wishlist_page_id' => 'Wishlist',
		'woocommerce_shop_page_id' => 'Shop',
		'woocommerce_cart_page_id' => 'Cart',
		'woocommerce_checkout_page_id' => 'Checkout',
		'woocommerce_myaccount_page_id' => 'My account',
	);
	
	foreach ( $page_options as $key => $page_title ) {
		$page = get_page_by_title( $page_title );
		if ( isset( $page->ID ) ) {
			if ($key == 'projects_page_id') {
				update_option( 'projects-pages-fields', array( $key => $page->ID ));
			} else {
				update_option( $key, $page->ID );
			}
		}
	}
	
	$project_imgs = array(
		'project-archive' => array('width' => 600, 'height' => 600, 'crop' => 'yes'),
		'project-single' => array('width' => 800, 'height' => 800, 'crop' => 'yes'),
		'project-thumbnail' => array('width' => 150, 'height' => 150, 'crop' => 'yes'),
	);
	update_option( 'projects-images-fields', $project_imgs );
	update_option( 'woocommerce_single_image_width', 570 );
	update_option( 'woocommerce_thumbnail_image_width', 350 );
	update_option( 'woocommerce_thumbnail_cropping', 'custom' );
	update_option( 'woocommerce_thumbnail_cropping_custom_width', 83 );
	update_option( 'woocommerce_thumbnail_cropping_custom_height', 106 );
	update_option( 'yith_wcmg_lens_opacity', 0.5 );
	update_option( 'show_on_front', 'page' );
	update_option( 'yith_woocompare_compare_button_in_products_list', 'no' );
	update_option( 'permalink_structure', '/%postname%/' );
	update_option( 'woocommerce_permalinks', 'a:5:{s:12:"product_base";s:8:"/product";s:13:"category_base";s:16:"product-category";s:8:"tag_base";s:11:"product-tag";s:14:"attribute_base";s:0:"";s:22:"use_verbose_page_rules";b:0;}' );
}
add_action( 'wbc_importer_after_content_import', 'lionthemes_wbc_extended_example', 10, 2 );


add_action( 'wordpress_importer_after_import_menu_item', 'lionthemes_helper_update_extra_menu_meta', 10, 2);
function lionthemes_helper_update_extra_menu_meta($item, $id) {
	$only_update = array('mmm_submenu_type', 'mmm_submenu_columns', 'mmm_submenu_enable_full_width', 'mmm_item_descr');
	if (!empty($item['postmeta'])) {
		foreach ( $item['postmeta'] as $meta ) {
			if (in_array($meta['key'], $only_update) && $meta['value']) {
				$value = strval($meta['value']);
				if (is_serialized($meta['value'])) {
					$value = unserialize($meta['value']);
				}
				update_post_meta( $id, $meta['key'], $value);
			}
		}
	}
}

add_action( 'wbc_importer_after_add_widget_to_sidebar', 'lionthemes_reset_nav_menu_after_imported' );
add_action( 'radium_theme_import_widget_after_import', 'lionthemes_reset_nav_menu_after_imported' );
function lionthemes_reset_nav_menu_after_imported () {
	$sidebars_widgets = get_option( 'sidebars_widgets' );
	$sidebars_widgets['wp_inactive_widgets'] = array();
	$navs = array(
		'footer_4columns' => array('Information', 'Our services', 'My account'),
		'footer_3columns_center' => array('Information', 'Our services'),
		'footer_vertical_menu' => array('Footer links'),
	);
	$_widgets = array(
		'blog' => array('categories', 'hermes_recent_post', 'hermes_recent_comment', 'archives', 'meta'),
		'shop' => array('woocommerce_product_categories', 'woocommerce_price_filter', 'woocommerce_layered_nav', 'tag_cloud', 'yith-woocompare-widget', 'woocommerce_products', 'text'),
		'top_header' => array('text'),
		'footer_4columns' => array('nav_menu', 'black-studio-tinymce', 'text'),
		'footer_3columns_left' => array('black-studio-tinymce', 'text'),
		'footer_3columns_center' => array('nav_menu'),
		'footer_3columns_right' => array('wysija'),
		'footer_newsletter' => array('wysija'),
		'footer_vertical_menu' => array('nav_menu'),
		'footer_payment' => array('black-studio-tinymce', 'text'),
	);
	
	$widget_nav_menu = get_option( 'widget_nav_menu' );
	$menu_update = array();
	foreach($_widgets as $area => $mandotory) {
		if (!empty($sidebars_widgets[$area])) {
			foreach($sidebars_widgets[$area] as $key => $widget) {
				$wg_data = explode('-', $widget);
				$wg_id = $wg_data[count($wg_data) - 1];
				$wg_key = str_replace('-' . $wg_id, '', $widget);
				if(!in_array($wg_key, $mandotory)) unset($sidebars_widgets[$area][$key]);
				if($wg_key == 'nav_menu') {
					$menu_update[$area][] = $wg_id;
				}
			}
		}
		
	}
	$menu_update = array_filter($menu_update);
	if (!empty(array_filter($menu_update))) {
		foreach($menu_update as $area => $ids) {
			if(!empty($navs[$area])) {
				foreach($navs[$area] as $k => $menu_name) {
					if (!empty($menu_update[$area][$k])) {
						$menu_wg_id = $menu_update[$area][$k];
						$menu_term = get_term_by( 'name', $menu_name, 'nav_menu' );
						if(isset($menu_term->term_id) && $menu_wg_id && $widget_nav_menu[$menu_wg_id]) {
							$widget_nav_menu[$menu_wg_id]['nav_menu'] = $menu_term->term_id;
							if ($area != 'footer_vertical_menu') $widget_nav_menu[$key]['title'] = $menu_name;
						} 
					} 
				}
			}
		}
	}
	update_option('sidebars_widgets', $sidebars_widgets);
	update_option('widget_nav_menu', $widget_nav_menu);
}


add_action('import_start', 'lionthemes_before_start_import_dummy');
function lionthemes_before_start_import_dummy() {
	$default_posts = array('Shop', 'My account', 'Cart', 'Checkout','Hello world!', 'Sample Page', 'Wishlist');
	foreach($default_posts as $page) {
		if ($post_id = post_exists( $page )) {
			wp_delete_post($post_id, true);
		}
	}
}