<?php
/**
 * Plugin Name: Hermes LionThemes Helper
 * Plugin URI: https://demo.lion-themes.net/hermes
 * Description: The helper plugin for LionThemes themes.
 * Version: 1.2.4
 * Author: LionThemes
 * Author URI: https://demo.lion-themes.net/hermes
 * Text Domain: lionthemes
 * License: GPL/GNU.
 * Copyright 2016  LionThemes  (email : support@lion-themes.net)
*/
$current_theme = wp_get_theme();
$theme = $current_theme->get('TextDomain');
if ($theme == 'hermes') {
	
	define('LION_CURRENT_THEME', $theme);
	define('IMPORTER_PATH', plugin_dir_path( __FILE__ ) . 'wbc_importer');

	if ( file_exists( plugin_dir_path( __FILE__ ). 'inc/custom-fields.php' ) ) {
		require_once( plugin_dir_path( __FILE__ ) . 'inc/custom-fields.php' );
	}
	if ( file_exists( plugin_dir_path( __FILE__ ). 'inc/widgets.php' ) ) {
		require_once( plugin_dir_path( __FILE__ ). 'inc/widgets.php' );
	}
	if( class_exists('Vc_Manager') && file_exists( plugin_dir_path( __FILE__ ). 'inc/composer-shortcodes.php' ) ){
		require_once( plugin_dir_path( __FILE__ ). 'inc/composer-shortcodes.php' );
	}

	require_once( plugin_dir_path( __FILE__ ). 'inc/oneclick-importer.php' );

	//Redux wbc importer for import data one click.
	function lionthemes_redux_register_extension_loader($ReduxFramework) {
		
		if ( ! class_exists( 'ReduxFramework_extension_wbc_importer' ) ) {
			$class_file = plugin_dir_path( __FILE__ ) . 'wbc_importer/extension_wbc_importer.php';
			$class_file = apply_filters( 'redux/extension/' . $ReduxFramework->args['opt_name'] . '/wbc_importer', $class_file );
			if ( $class_file ) {
				require_once( $class_file );
			}
		}
		if ( ! isset( $ReduxFramework->extensions[ 'wbc_importer' ] ) ) {
			$ReduxFramework->extensions[ 'wbc_importer' ] = new ReduxFramework_extension_wbc_importer( $ReduxFramework );
		}
	}
	add_action("redux/extensions/". LION_CURRENT_THEME ."_opt/before", 'lionthemes_redux_register_extension_loader', 0);


	// add placeholder for input social icons 
	add_action("redux/field/". LION_CURRENT_THEME ."_opt/sortable/fieldset/after/". LION_CURRENT_THEME ."_opt", 'lionthemes_helper_redux_add_placeholder_sortable', 0);
	function lionthemes_helper_redux_add_placeholder_sortable($data){
		$fieldset_id = $data['id'] . '-list';
		$base_name = LION_CURRENT_THEME . '_opt['. $data['id'] .']';
		echo "<script type=\"text/javascript\">
				jQuery('#$fieldset_id li input[type=\"text\"]').each(function(){
					var my_name = jQuery(this).attr('name');
					placeholder = my_name.replace('$base_name', '').replace('[','').replace(']','');
					jQuery(this).attr('placeholder', placeholder);
					jQuery(this).next('span').attr('title', placeholder);
				});
			</script>";
	}

	//Less compiler
	function compileLess($input, $output, $params){
		// input and output location
		$inputFile = get_template_directory().'/less/'.$input;
		$outputFile = get_template_directory().'/css/'.$output;
		if(!file_exists($inputFile)) return;
		// include Less Lib
		if(file_exists( plugin_dir_path( __FILE__ ) . 'less/lessc.inc.php' )){
			require_once( plugin_dir_path( __FILE__ ) . 'less/lessc.inc.php' );
			try{
				$less = new lessc;
				$less->setVariables($params);
				$less->setFormatter("compressed");
				$cache = $less->cachedCompile($inputFile);
				file_put_contents($outputFile, $cache["compiled"]);
				$last_updated = $cache["updated"];
				$cache = $less->cachedCompile($cache);
				if ($cache["updated"] > $last_updated) {
					file_put_contents($outputFile, $cache["compiled"]);
				}
			}catch(Exception $e){
				$error_message = $e->getMessage();
				echo $error_message;
			}
		}
		return;
	}
	$shortcodes = array(
		'brands.php',
		'blogposts.php',
		'products.php',
		'productscategory.php',
		'testimonials.php',
		'countdown.php',
		'featurecontent.php',
		'categories.php',
	);
	//Shortcodes for Visual Composer
	foreach($shortcodes as $shortcode){
		if ( file_exists( plugin_dir_path( __FILE__ ). 'shortcodes/' . $shortcode ) ) {
			require_once plugin_dir_path( __FILE__ ) . 'shortcodes/' . $shortcode;
		}
	}

	add_action( 'init', 'lionthemes_remove_upsell' );
	function lionthemes_remove_upsell(){
		global $hermes_opt;
		if(isset($hermes_opt['enable_upsells']) && $hermes_opt['enable_upsells'] == false){
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
		}
	}


	// remove redux ads
	add_action('admin_enqueue_scripts','lionthemes_remove_redux_ads', 10, 1);
	function lionthemes_remove_redux_ads(){
		$remove_redux = 'jQuery(document).ready(function($){
							setTimeout(
								function(){
									$(".rAds, .redux-notice, .vc_license-activation-notice, #js_composer-update, #mega_main_menu-update, #vc_license-activation-notice").remove();
									$("tr[data-slug=\"mega_main_menu\"]").removeClass("update");
										$("tr[data-slug=\"js_composer\"]").removeClass("update");
										$("tr[data-slug=\"slider-revolution\"]").removeClass("is-uninstallable");
										$("tr[data-slug=\"slider-revolution\"]").next(".plugin-update-tr").remove();
										$("tr[data-slug=\"slider-revolution\"]").next(".plugin-update-tr").remove();
								}, 500);
						});';
		if ( ! wp_script_is( 'jquery', 'done' ) ) {
			wp_enqueue_script( 'jquery' );
		}
		wp_add_inline_script( 'jquery', $remove_redux ); 
	}


	add_action( 'wp_enqueue_scripts', 'lionthemes_register_script', 100 );
	function lionthemes_register_script(){
		// add adminbar style sheet
		$style = '@media screen and (max-width: 600px){
						#wpadminbar{
							position: fixed;
						}
					}';
		wp_add_inline_style( 'theme-options', $style );
	}

	function lionthemes_get_excerpt($post_id, $limit){
		$the_post = get_post($post_id);
		$the_excerpt = do_shortcode($the_post->post_content);
		$the_excerpt = strip_tags($the_excerpt);
		$words = explode(' ', $the_excerpt, $limit + 1);

		if(count($words) > $limit) :
			array_pop($words);
			array_push($words, '…');
			$the_excerpt = implode(' ', $words);
		endif;

		$the_excerpt = '<p>' . $the_excerpt . '</p>';

		return $the_excerpt;
	}

	// install table when active plugin
	register_activation_hook( __FILE__, 'lionthemes_new_like_post_table' );
	function lionthemes_new_like_post_table(){
		global $wpdb;
		$table_name = $wpdb->prefix.'hermes_user_like_ip';
		if($wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name) {
			 //table not in database. Create new table
			 $charset_collate = $wpdb->get_charset_collate();
			 $sql = "CREATE TABLE `{$table_name}` (
				  `post_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
				  `user_ip` VARCHAR(100) NOT NULL DEFAULT '',
				  PRIMARY KEY (`post_id`,`user_ip`)
			 ) {$charset_collate}";
			 require_once(  ABSPATH . 'wp-admin/includes/upgrade.php' );
			 dbDelta( $sql );
		}
	}
	// function display number view of posts.
	function hermes_get_post_viewed($postID){
		$count_key = 'post_views_count';
		delete_post_meta($postID, 'post_like_count');
		$count = get_post_meta($postID, $count_key, true);
		if($count==''){
			delete_post_meta($postID, $count_key);
			add_post_meta($postID, $count_key, '0');
			return 0;
		}
		return $count;
	}
	// function to count views.
	function hermes_set_post_view($postID){
		$count_key = 'post_views_count';
		$count = (int)get_post_meta($postID, $count_key, true);
		if(!$count){
			$count = 1;
			delete_post_meta($postID, $count_key);
			add_post_meta($postID, $count_key, $count);
		}else{
			$count++;
			update_post_meta($postID, $count_key, $count);
		}
	}
	// function display number like of posts.
	function hermes_get_liked($postID){
		global $wpdb;
		$table_name = $wpdb->prefix . 'hermes_user_like_ip';
		if($wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table_name)) != $table_name) {
			lionthemes_new_like_post_table();
			return 0;
		}else{
			$safe_sql = $wpdb->prepare("SELECT COUNT(*) FROM {$table_name} WHERE post_id = %s", $postID);
			$results = $wpdb->get_var( $safe_sql );
			return $results;
		}
	}

	add_action( 'wp_ajax_hermes_update_like', 'hermes_update_like' );
	add_action( 'wp_ajax_nopriv_hermes_update_like', 'hermes_update_like' );
	function hermes_get_the_user_ip(){
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
	function hermes_check_liked_post($postID){
		global $wpdb;
		$table_name = $wpdb->prefix . 'hermes_user_like_ip';
		$user_ip = hermes_get_the_user_ip();
		$safe_sql = $wpdb->prepare("SELECT COUNT(*) FROM {$table_name} WHERE post_id = %s AND user_ip = %s", $postID, $user_ip);
		$results = $wpdb->get_var( $safe_sql );
		return $results;
	}
	function hermes_update_like(){
		$count_key = 'post_like_count';
		if(empty($_POST['post_id'])){
		   die('0');
		}else{
			global $wpdb;
			$table_name = $wpdb->prefix . 'hermes_user_like_ip';
			$postID = intval($_POST['post_id']);
			$check = hermes_check_liked_post($postID);
			$ip = hermes_get_the_user_ip();
			$data = array('post_id' => $postID, 'user_ip' => $ip);
			if($check){
				//remove like record
				$wpdb->delete( $table_name, $data ); 
			}else{
				//add new like record
				$wpdb->insert( $table_name, $data );
			}
			echo hermes_get_liked($postID);
			die();
		}
	}
	add_action('lionthemes_view_count_button', 'lionthemes_view_count_button_html');
	function lionthemes_view_count_button_html($id){
		?>
		<div class="post-views" title="<?php echo esc_html__('Total Views', 'hermes') ?>" data-toggle="tooltip">
			<i class="fa fa-eye"></i><?php echo hermes_get_post_viewed($id); ?>
		</div>
		<?php
	}
	add_action('lionthemes_like_button', 'lionthemes_like_button_html');
	function lionthemes_like_button_html($id){
		$liked = hermes_check_liked_post($id); ?>
		<div class="likes-counter" title="<?php echo (!$liked) ?  esc_html__('Like this post', 'hermes') : esc_html__('Unlike this post', 'hermes'); ?>" data-toggle="tooltip">
			<a class="hermes_like_post<?php echo ($liked) ? ' liked':''; ?>" href="javascript:void(0)" data-post_id="<?php echo $id ?>" data-liked_title="<?php echo esc_html__('Unlike this post', 'hermes') ?>" data-unliked_title="<?php echo esc_html__('Like this post', 'hermes') ?>">
				<i class="fa fa-heart"></i><span class="number"><?php echo hermes_get_liked($id); ?></span>
			</a>
		</div>
	<?php
	}

	add_action('lionthemes_end_single_post', 'lionthemes_blog_sharing');
	//social share blog
	function lionthemes_blog_sharing() {
		$hermes_opt = get_option( 'hermes_opt' );
		
		if(isset($hermes_opt['blog_social_share']) && is_array($hermes_opt['blog_social_share'])){
			$blog_social_share = array_filter($hermes_opt['blog_social_share']);
		}
		if(!empty($blog_social_share)) {
			$postid = get_the_ID();
			
			$share_url = get_permalink( $postid );

			$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $postid ), 'large' );
			$postimg = $large_image_url[0];
			$posttitle = get_the_title( $postid );
			?>
			<div class="social-sharing">
				<div class="widget widget_socialsharing_widget">
					<ul class="social-icons">
						<?php if(!empty($hermes_opt['blog_social_share']['facebook'])){ ?>
						<li><a class="facebook social-icon" href="javascript:void(0)" data-toggle="tooltip" onclick="javascript:window.open('<?php echo 'https://www.facebook.com/sharer/sharer.php?u='.$share_url; ?>', '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600'); return false;" title="<?php echo esc_attr__('Facebook', 'hermes'); ?>"><i class="fa fa-facebook"></i></a></li>
						<?php } ?>
						<?php if(!empty($hermes_opt['blog_social_share']['twitter'])){ ?>
						<li><a class="twitter social-icon" href="javascript:void(0)" data-toggle="tooltip" onclick="javascript:window.open('<?php echo 'https://twitter.com/home?status='.$posttitle.'&nbsp;'.$share_url; ?>', '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600'); return false;" title="<?php echo esc_attr__('Twitter', 'hermes'); ?>"><i class="fa fa-twitter"></i></a></li>
						<?php } ?>
						<?php if(!empty($hermes_opt['blog_social_share']['pinterest'])){ ?>
						<li><a class="pinterest social-icon" href="javascript:void(0)" data-toggle="tooltip" onclick="javascript:window.open('<?php echo 'https://pinterest.com/pin/create/button/?url='.$share_url.'&amp;media='.$postimg.'&amp;description='.$posttitle; ?>', '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600'); return false;" title="<?php echo esc_attr__('Pinterest', 'hermes'); ?>"><i class="fa fa-pinterest"></i></a></li>
						<?php } ?>
						<?php if(!empty($hermes_opt['blog_social_share']['gplus'])){ ?>
						<li><a class="gplus social-icon" href="javascript:void(0)" data-toggle="tooltip" onclick="javascript:window.open('<?php echo 'https://plus.google.com/share?url='.$share_url; ?>', '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600'); return false;" title="<?php echo esc_attr__('Google +', 'hermes'); ?>"><i class="fa fa-google-plus"></i></a></li>
						<?php } ?>
						<?php if(!empty($hermes_opt['blog_social_share']['linkedin'])){ ?>
						<li><a class="linkedin social-icon" href="javascript:void(0)" data-toggle="tooltip" onclick="javascript:window.open('<?php echo 'https://www.linkedin.com/shareArticle?mini=true&amp;url='.$share_url.'&amp;title='.$posttitle; ?>', '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600'); return false;" title="<?php echo esc_attr__('LinkedIn', 'hermes'); ?>"><i class="fa fa-linkedin"></i></a></li>
						<?php } ?>
					</ul>
				</div>
			</div>
			<?php
		}
	}
	add_action('lionthemes_quickview_after_product_info', 'lionthemes_product_sharing');
	add_action( 'woocommerce_share', 'lionthemes_product_sharing', 40 );
	//social share products
	function lionthemes_product_sharing() {
		$hermes_opt = get_option( 'hermes_opt' );
		if(isset($_POST['data'])) { // for the quickview
			$postid = intval( $_POST['data'] );
		} else {
			$postid = get_the_ID();
		}
		if(isset($hermes_opt['pro_social_share']) && is_array($hermes_opt['pro_social_share'])){
			$pro_social_share = array_filter($hermes_opt['pro_social_share']);
		}
		if(!empty($pro_social_share)){
			$share_url = get_permalink( $postid );

			$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $postid ), 'large' );
			$postimg = $large_image_url[0];
			$posttitle = get_the_title( $postid );
			?>
			<div class="social-sharing">
				<div class="widget widget_socialsharing_widget">
					<h3 class="widget-title"><?php if(isset($hermes_opt['product_share_title'])) { echo esc_html($hermes_opt['product_share_title']); } else { esc_html_e('Share this product', 'hermes'); } ?></h3>
					<ul class="social-icons">
						<?php if(!empty($hermes_opt['pro_social_share']['facebook'])){ ?>
							<li><a class="facebook social-icon" href="javascript:void(0)" data-toggle="tooltip" onclick="javascript:window.open('<?php echo 'https://www.facebook.com/sharer/sharer.php?u='.$share_url; ?>', '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600'); return false;" title="<?php echo esc_attr__('Facebook', 'hermes'); ?>"><i class="fa fa-facebook"></i></a></li>
						<?php } ?>
						<?php if(!empty($hermes_opt['pro_social_share']['twitter'])){ ?>
							<li><a class="twitter social-icon" href="javascript:void(0)" data-toggle="tooltip" onclick="javascript:window.open('<?php echo 'https://twitter.com/home?status='.$posttitle.'&nbsp;'.$share_url; ?>', '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600'); return false;" title="<?php echo esc_attr__('Twitter', 'hermes'); ?>" ><i class="fa fa-twitter"></i></a></li>
						<?php } ?>
						<?php if(!empty($hermes_opt['pro_social_share']['pinterest'])){ ?>
							<li><a class="pinterest social-icon" href="javascript:void(0)" data-toggle="tooltip" onclick="javascript:window.open('<?php echo 'https://pinterest.com/pin/create/button/?url='.$share_url.'&amp;media='.$postimg.'&amp;description='.$posttitle; ?>', '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600'); return false;" title="<?php echo esc_attr__('Pinterest', 'hermes'); ?>"><i class="fa fa-pinterest"></i></a></li>
						<?php } ?>
						<?php if(!empty($hermes_opt['pro_social_share']['gplus'])){ ?>
						<li><a class="gplus social-icon" href="javascript:void(0)" data-toggle="tooltip" onclick="javascript:window.open('<?php echo 'https://plus.google.com/share?url='.$share_url; ?>', '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600'); return false;" title="<?php echo esc_attr__('Google +', 'hermes'); ?>"><i class="fa fa-google-plus"></i></a></li>
						<?php } ?>
						<?php if(!empty($hermes_opt['pro_social_share']['linkedin'])){ ?>
							<li><a class="linkedin social-icon" href="javascript:void(0)" data-toggle="tooltip" onclick="javascript:window.open('<?php echo 'https://www.linkedin.com/shareArticle?mini=true&amp;url='.$share_url.'&amp;title='.$posttitle; ?>', '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600'); return false;" title="<?php echo esc_attr__('LinkedIn', 'hermes'); ?>"><i class="fa fa-linkedin"></i></a></li>
						<?php } ?>
					</ul>
				</div>
			</div>
			<?php
		}
	}
}
