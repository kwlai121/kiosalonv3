<?php
// All Hermes theme helper functions in here
function hermes_get_effect_list(){
	return array(
		esc_html__( 'None', 'hermes' ) 	=> '',
		esc_html__( 'Bounce In', 'hermes' ) 	=> 'bounceIn',
		esc_html__( 'Bounce In Down', 'hermes' ) 	=> 'bounceInDown',
		esc_html__( 'Bounce In Left', 'hermes' ) 	=> 'bounceInLeft',
		esc_html__( 'Bounce In Right', 'hermes' ) 	=> 'bounceInRight',
		esc_html__( 'Bounce In Up', 'hermes' ) 	=> 'bounceInUp',
		esc_html__( 'Fade In', 'hermes' ) 	=> 'fadeIn',
		esc_html__( 'Fade In Down', 'hermes' ) 	=> 'fadeInDown',
		esc_html__( 'Fade In Left', 'hermes' ) 	=> 'fadeInLeft',
		esc_html__( 'Fade In Right', 'hermes' ) 	=> 'fadeInRight',
		esc_html__( 'Fade In Up', 'hermes' ) 	=> 'fadeInUp',
		esc_html__( 'Flip In X', 'hermes' ) 	=> 'flipInX',
		esc_html__( 'Flip In Y', 'hermes' ) 	=> 'flipInY',
		esc_html__( 'Light Speed In', 'hermes' ) 	=> 'lightSpeedIn',
		esc_html__( 'Rotate In', 'hermes' ) 	=> 'rotateIn',
		esc_html__( 'Rotate In Down Left', 'hermes' ) 	=> 'rotateInDownLeft',
		esc_html__( 'Rotate In Down Right', 'hermes' ) 	=> 'rotateInDownRight',
		esc_html__( 'Rotate In Up Left', 'hermes' ) 	=> 'rotateInUpLeft',
		esc_html__( 'Rotate In Up Right', 'hermes' ) 	=> 'rotateInUpRight',
		esc_html__( 'Slide In Down', 'hermes' ) 	=> 'slideInDown',
		esc_html__( 'Slide In Left', 'hermes' ) 	=> 'slideInLeft',
		esc_html__( 'Slide In Right', 'hermes' ) 	=> 'slideInRight',
		esc_html__( 'Roll In', 'hermes' ) 	=> 'rollIn',
	);
}

function hermes_woocommerce_query($type,$post_per_page=-1,$cat='', $keyword = null){
	$args = hermes_woocommerce_query_args($type,$post_per_page,$cat, $keyword);
	return new WP_Query($args);
}
function hermes_vc_custom_css_class( $param_value, $prefix = '' ) {
	$css_class = preg_match( '/\s*\.([^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', $param_value ) ? $prefix . preg_replace( '/\s*\.([^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', '$1', $param_value ) : '';
	return $css_class;
}
function hermes_woocommerce_query_args($type,$post_per_page=-1,$cat='',$keyword=null){
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $post_per_page,
        'post_status' => 'publish',
		'date_query' => array(
			array(
			   'before' => date('Y-m-d H:i:s', current_time( 'timestamp' ))
			)
		 ),
		 'post_parent' => 0,
		 'tax_query' => array()
    );
    switch ($type) {
        case 'best_selling':
            $args['meta_key']='total_sales';
            $args['orderby']='meta_value_num';
            $args['ignore_sticky_posts']   = 1;
            $args['meta_query'] = array();
            break;
        case 'featured_product':
            $args['ignore_sticky_posts'] = 1;
            $args['meta_query'] = array();
            $args['tax_query'][] = array(
				'taxonomy'         => 'product_visibility',
				'terms'            => 'featured',
				'field'            => 'name',
				'operator'         => 'IN',
				'include_children' => false,
			);
            break;
        case 'top_rate':
            $args['meta_key']='_wc_average_rating';
            $args['orderby']='meta_value_num';
            $args['order']='DESC';
            $args['meta_query'] = array();
            break;
        case 'recent_product':
            $args['meta_query'] = array();
			$args['orderby'] = 'date';
			$args['order'] = 'DESC';
            break;
        case 'on_sale':
            $args['meta_query'] = array();
            $product_ids_on_sale    = wc_get_product_ids_on_sale();
			$product_ids_on_sale[]  = 0;
            $args['post__in'] = $product_ids_on_sale;
            break;
        case 'recent_review':
            if($post_per_page == -1) $_limit = 4;
            else $_limit = $post_per_page;
            global $wpdb;
            $query = "SELECT c.comment_post_ID FROM {$wpdb->posts} p, {$wpdb->comments} c WHERE p.ID = c.comment_post_ID AND c.comment_approved > 0 AND p.post_type = 'product' AND p.post_status = 'publish' AND p.comment_count > 0 ORDER BY c.comment_date ASC LIMIT 0, %d";
            $safe_sql = $wpdb->prepare( $query, $_limit );
			$results = $wpdb->get_results($safe_sql, OBJECT);
            $_pids = array();
            foreach ($results as $re) {
                $_pids[] = $re->comment_post_ID;
            }

            $args['meta_query'] = array();
            $args['post__in'] = $_pids;
            break;
        case 'deals':
            $args['meta_query'] = array();
            $args['meta_query'][] = array(
                                 'key' => '_sale_price_dates_to',
                                 'value' => '0',
                                 'compare' => '>');
            $product_ids_on_sale    = wc_get_product_ids_on_sale();
			$product_ids_on_sale[]  = 0;
            $args['post__in'] = $product_ids_on_sale;
            break;
    }
	if ( 'yes' == get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
		$args['tax_query'][] = array(
			array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => 'outofstock',
				'operator' => 'NOT IN',
			),
		);
		$args['meta_query'][] = array(
			'key'       => '_stock_status',
			'value'     => 'outofstock',
			'compare'   => 'NOT IN'
		);
	}
    if($cat!=''){
        $args['tax_query'][] = array(
			'taxonomy' => 'product_cat',
			'field' => 'slug',
			'terms' => $cat
		);
		if (!$type) {
			$args['orderby']='menu_order';
            $args['order']='ASC';
		}
    }
	if($keyword){
		$args['s'] = $keyword;
	}
    return $args;
}
function hermes_make_id($length = 5){
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
//Change excerpt length
add_filter( 'excerpt_length', 'hermes_excerpt_length', 999 );
function hermes_excerpt_length( $length ) {
	global $hermes_opt;
	if(isset($hermes_opt['excerpt_length'])){
		return $hermes_opt['excerpt_length'];
	}
	return 22;
}
function hermes_get_the_excerpt($post_id) {
	global $post;
	$temp = $post;
    $post = get_post( $post_id );
    setup_postdata( $post );
    $excerpt = get_the_excerpt();
    wp_reset_postdata();
    $post = $temp;
    return $excerpt;
}

//Add breadcrumbs
function hermes_breadcrumb() {
	global $post, $hermes_opt;
	
	$brseparator = '<span class="separator">/</span>';
	if (!is_home()) {
		echo '<div class="breadcrumbs">';
		
		echo '<a href="';
		echo esc_url( home_url( '/' ) );
		echo '">';
		echo esc_html__('Home', 'hermes');
		echo '</a>'.$brseparator;
		if (is_category() || is_single()) {
			the_category($brseparator);
			if (is_single()) {
				echo ''.$brseparator;
				the_title();
			}
		} elseif (is_page()) {
			if($post->post_parent){
				$anc = get_post_ancestors( $post->ID );
				$title = get_the_title();
				foreach ( $anc as $ancestor ) {
					$output = '<a href="'. esc_url(get_permalink($ancestor)).'" title="'.esc_attr(get_the_title($ancestor)).'">'. esc_html(get_the_title($ancestor)) .'</a>'.$brseparator;
				}
				echo wp_kses($output, array(
						'a'=>array(
							'href' => array(),
							'title' => array()
						),
						'span'=>array(
							'class'=>array()
						)
					)
				);
				echo '<span title="'.esc_attr($title).'"> '.esc_html($title).'</span>';
			} else {
				echo '<span> '. esc_html(get_the_title()).'</span>';
			}
		}
		elseif (is_tag()) {single_tag_title();}
		elseif (is_day()) {echo "<span>" . sprintf(esc_html__('Archive for %s', 'hermes'), the_time('F jS, Y')); echo '</span>';}
		elseif (is_month()) {echo "<span>" . sprintf(esc_html__('Archive for %s', 'hermes'), the_time('F, Y')); echo '</span>';}
		elseif (is_year()) {echo "<span>" . sprintf(esc_html__('Archive for %s', 'hermes'), the_time('Y')); echo '</span>';}
		elseif (is_author()) {echo "<span>" . esc_html__('Author Archive', 'hermes'); echo '</span>';}
		elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {echo "<span>" . esc_html__('Blog Archives', 'hermes'); echo '</span>';}
		elseif (is_search()) {echo "<span>" . esc_html__('Search Results', 'hermes'); echo '</span>';}
		
		echo '</div>';
	} else {
		echo '<div class="breadcrumbs">';
		
		echo '<a href="';
		echo esc_url( home_url( '/' ) );
		echo '">';
		echo esc_html__('Home', 'hermes');
		echo '</a>'.$brseparator;
		
		if(isset($hermes_opt['blog_header_text']) && $hermes_opt['blog_header_text']!=""){
			echo esc_html($hermes_opt['blog_header_text']);
		} else {
			echo esc_html__('Blog', 'hermes');
		}
		
		echo '</div>';
	}
}


//get taxonomy list by parent children
function hermes_get_all_taxonomy_terms($taxonomy = 'product_cat', $all = false){
	
	global $wpdb;
	
	$arr = array(
		'orderby' => 'name',
		'hide_empty' => 0
	);
	$categories = $wpdb->get_results($wpdb->prepare("SELECT t.name,t.slug,t.term_group,x.term_taxonomy_id,x.term_id,x.taxonomy,x.description,x.parent,x.count FROM {$wpdb->prefix}term_taxonomy x LEFT JOIN {$wpdb->prefix}terms t ON (t.term_id = x.term_id) WHERE x.taxonomy=%s ORDER BY x.parent ASC, t.name ASC;", $taxonomy));
	$output = array();
	if($all) $output = array( array('label' => esc_html__('All categories', 'hermes'), 'value' => '') );
	if(!is_array($categories)) return $output;
	hermes_get_repare_terms_children( 0, 0, $categories, 0, $output );
	
	return $output;
}

function hermes_get_repare_terms_children( $parent_id, $pos, $categories, $level, &$output ) {
	for ( $i = $pos; $i < count( $categories ); $i ++ ) {
		if ( isset($categories[ $i ]->parent) && $categories[ $i ]->parent == $parent_id ) {
			$name = str_repeat( " - ", $level ) . ucfirst($categories[ $i ]->name);
			$value = $categories[ $i ]->slug;
			$output[] = array( 'label' => $name, 'value' => $value );
			hermes_get_repare_terms_children( $categories[ $i ]->term_id, $i, $categories, $level + 1, $output );
		}
	}
}


//popup onload home page
add_action( 'wp_footer', 'hermes_popup_onload');
function hermes_popup_onload(){
	
	global $hermes_opt;
	
	echo '<div class="quickview-wrapper"><div class="overlay-bg" onclick="hideQuickView()"></div><div class="quick-modal"><span class="qvloading"></span><span class="closeqv"><i class="fa fa-times"></i></span><div id="quickview-content"></div><div class="clearfix"></div></div></div>';
	
	if(isset($hermes_opt['enable_popup']) && $hermes_opt['enable_popup']){
		if (is_front_page() && (!empty($hermes_opt['popup_onload_form']) || !empty($hermes_opt['popup_onload_content']))) {
			$no_again = 0; 
			if(isset($_COOKIE['no_again'])) $no_again = $_COOKIE['no_again'];
			if(!$no_again){
		?>
			<div class="popup-content" id="popup_onload">
				<div class="overlay-bg-popup"></div>
				<div class="popup-content-wrapper">
					<a class="close-popup" href="javascript:void(0)"><i class="fa fa-times"></i></a>
					<div class="popup-container">
						<div class="row">
							<div class="col-md-offset-6">
								<?php if(!empty($hermes_opt['popup_onload_content'])){ ?>
								<div class="popup-content-text">
									<?php echo wp_kses($hermes_opt['popup_onload_content'], array(
											'a' => array(
											'href' => array(),
											'title' => array()
											),
											'div' => array(
												'class' => array(),
											),
											'img' => array(
												'src' => array(),
												'alt' => array()
											),
											'h1' => array(
												'class' => array(),
											),
											'h2' => array(
												'class' => array(),
											),
											'h3' => array(
												'class' => array(),
											),
											'h4' => array(
												'class' => array(),
											),
											'ul' => array(),
											'li' => array(),
											'i' => array(
												'class' => array()
											),
											'br' => array(),
											'em' => array(),
											'strong' => array(),
											'p' => array(),
									)); ?>
								</div>
								<?php } ?>
								<?php if(!empty($hermes_opt['popup_onload_form'])){ ?>
								<div class="newletter-form">
									<?php 
										$short_code = (!empty($hermes_opt['use_mailchimp_form'])) ? 'mc4wp_form' : 'wysija_form';
										echo do_shortcode( '['. $short_code .' id="'. $hermes_opt['popup_onload_form'] .'"]' );
									?>
								</div>
								<?php } ?>
								<label class="not-again"><input type="checkbox" value="1" name="not-again" /><span><?php echo esc_html__('Do not show this popup again', 'hermes'); ?></span></label>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } 
		}
	}
}

// save purchase code in option
add_action( 'wp_ajax_hermes_save_purchased_code', 'hermes_save_purchased_code' );
add_action( 'wp_ajax_nopriv_hermes_save_purchased_code', 'hermes_save_purchased_code' );
function hermes_save_purchased_code() {
	$code = $_POST['code'];
	$item_id = $_POST['item_id'];
	update_option( 'envato_purchase_code_' . $item_id, $code );
	update_option( 'envato_purchase_code_hermes', $code );
	die('1');
}

// new page to submit purchase code
add_action('admin_menu', 'hermes_admin_menu');
function hermes_admin_menu() {
	$menu = add_theme_page( 'Hermes theme verify', 'Hermes theme verify', 'manage_options', 'hermes_theme_verify', 'hermes_theme_verify_page' );
	add_action('admin_print_scripts-' . $menu, 'hermes_theme_verify_script' );
}

// show notice in dashboard
add_action( 'admin_notices', 'hermes_admin_notice' );
function hermes_admin_notice() {
	$code = get_option( 'envato_purchase_code_hermes' );
	if (!$code) {
		?>
		<div class="notice notice-error">
			<p><?php echo sprintf(wp_kses(__( 'You did not register theme purchase code yet! Please search on youtube tutorial video to see how to get purchase code on themeforest.net then register it in <a href="%s">here</a>', 'hermes' ), array('a'=> array('href' => array()))), admin_url('admin.php?page=hermes_theme_verify')); ?></p>
		</div>
		<?php
	}
}

// custom js in admin
function hermes_theme_verify_script() {
	wp_enqueue_script( 'hermes-theme-verify', get_template_directory_uri() . '/js/theme-verify.js', array('jquery'), filemtime( get_template_directory() . '/js/theme-verify.js'), true );
}

// verify page content
function hermes_theme_verify_page() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'hermes' ) );
	}
	$code = get_option( 'envato_purchase_code_hermes' );
	?>
	<div id="theme-verify" class="wrap">
		<h1><?php _ex( 'Hermes theme puchased code verify', 'title of the main page', 'hermes' ); ?></h1>
		<p><?php echo esc_html__('From May 2018, you must verify purchased code in here to ensure you are buyer of Hermes theme item on Themeforest market', 'hermes'); ?></p>
		<div class="hermes-verify">
		<table class="form-table">
			<tr>
				<th class="row">
					<?php echo esc_html__('Purchased code:', 'hermes') ?>
				</th>
				<td>
					<p class="correct<?php echo esc_attr(($code) ? ' show': ''); ?>"><?php echo esc_html__('Your purchased code is correct, you can continue use Hermes theme', 'hermes') ?></p>
					<input type="text" id="purchased_code" value="<?php echo esc_attr($code) ?>" />
					<p class="incorrect"><?php echo esc_html__('Incorrect purchase code, please check again!', 'hermes') ?></p>
				<td>
			</tr>
		</table>
		<p class="submit">
			<input id="hermes-submit-code" type="button" class="button button-primary" value="<?php echo esc_html__('Save', 'hermes') ?>" />
			<img width="24" height="24" class="loading" src="<?php echo get_template_directory_uri() . '/images/small-loading.gif'; ?>" />
		</p>
		</div>
	</div>
	<?php
}
?>