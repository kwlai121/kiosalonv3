<?php
function lionthemes_categories_shortcode( $atts ) {
	
	$atts = shortcode_atts( array(
							'title'=> '',
							'short_desc' => '',
							'categories' => '',
							'showon_effect'=> '',
							'columns'=> '5',
							'rows'=> '1',
							'el_class' => '',
							'custom_style' => '',
							'style'=>'grid',
							'desksmall' => '4',
							'tablet_count' => '3',
							'tabletsmall' => '2',
							'mobile_count' => '1',
							'margin' => '0',
							'autoplay' => 'true',
							'playtimeout' => '5000',
							'speed' => '250',
							), $atts, 'categories' ); 
	extract($atts);
	switch ($columns) {
		case '6':
			$class_column='col-sm-2 col-xs-6';
			break;
		case '5':
			$class_column='col-md-20 col-sm-4 col-xs-6';
			break;
		case '4':
			$class_column='col-sm-3 col-xs-6';
			break;
		case '3':
			$class_column='col-sm-4 col-xs-6';
			break;
		case '2':
			$class_column='col-sm-6 col-xs-6';
			break;
		default:
			$class_column='col-sm-12 col-xs-6';
			break;
	}
	
	if($categories=='') return;
	$categories = array_filter(explode(',', $categories));
	if(empty($categories)) return;
	$categories = get_terms(array(
		'taxonomy' => 'product_cat',
		'hide_empty' => false,
		'slug' => $categories
	));
	$owl = array(
		'data-owl="slide"',
		'data-desksmall="'. esc_attr($desksmall).'"',
		'data-tabletsmall="'. esc_attr($tabletsmall) .'"',
		'data-mobile="'. esc_attr($mobile_count) .'"',
		'data-tablet="'. $tablet_count .'"',
		'data-margin="'. esc_attr($margin) .'"',
		'data-item-slide="'. esc_attr($columns) .'"',
		'data-dots="false"',
		'data-nav="true"',
		'data-autoplay="' . esc_attr($autoplay) . '"',
		'data-playtimeout="' . esc_attr($playtimeout) . '"',
		'data-speed="' . esc_attr($speed) . '"',
	);
	$list_class = ($style == 'carousel') ? ' owl-carousel owl-theme' : '';
	ob_start(); ?>
	<div class="categories-list-widget <?php echo esc_attr($el_class); ?> <?php if($custom_style){ ?><?php echo  $custom_style  ?><?php } ?>">
		<?php if($title){ ?>
		<h3 class="vc_widget_title vc_categories_title"><span><?php echo esc_html($title) ?></span></h3>
		<?php } ?>
		<?php if($short_desc){ ?>
		<div class="short_desc"><?php echo nl2br($short_desc) ?></div>
		<?php } ?>
		<div class="category-list<?php echo $list_class ?>" <?php echo ($style == 'carousel') ? implode(' ', $owl) : ''; ?>>
			<?php foreach($categories as $key=>$category){  ?>
			<div class="category-item">
				<a href="<?php echo get_term_link( $category->term_id, $category->taxonomy ); ?>">
				<?php 
					$image = get_term_meta($category->term_id, '_square_image'); 
					if(!empty($image[0])){ ?>
					<img src="<?php echo esc_url($image[0]) ?>" alt="<?php echo $category->name ?>" />
					<?php }else{
						woocommerce_subcategory_thumbnail( $category );
					}
				?>
				</a>
				<div class="cat-name">
					<div class="cat-center">
						<h3><a href="<?php echo get_term_link( $category->term_id, $category->taxonomy ); ?>"><?php echo $category->name; ?></a></h3>
						<p class="quanlity"><?php echo sprintf( _n( '(%s Item)', '(%s Items)', $category->count, 'hermes' ), $category->count ) ?></p>
						<a href="<?php echo get_term_link( $category->term_id, $category->taxonomy ); ?>" class="btn"><?php echo esc_html( 'Shop now' ); ?></a>
					</div>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>	
	<?php 
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
} 
add_shortcode( 'categories', 'lionthemes_categories_shortcode' );
?>