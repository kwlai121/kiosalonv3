<?php
function hermes_products_shortcode( $atts ) {
	global $hermes_opt;
	$atts = shortcode_atts( array(
							'number'=> 10,
							'columns'=>'4',
							'rows'=>'1',
							'el_class' => '',
							'type'=>'best_selling',
							'in_category'=>'',
							'style'=>'grid',
							'showon_effect'=>'',
							'item_layout'=>'box',
							'title'=>'',
							'widget_style' => '',
							'short_desc' => '',
							'desksmall' => '4',
							'tablet_count' => '3',
							'tabletsmall' => '2',
							'mobile_count' => '1',
							'margin' => '30',
							'autoplay' => 'false',
							'playtimeout' => '5000',
							'speed' => '250',
							), $atts, 'specifyproducts' );
	extract($atts);
	switch ($columns) {
		case '6':
			$class_column='col-lg-2 col-md-3 col-sm-4 col-xs-12';
			break;
		case '5':
			$class_column='col-lg-20 col-md-3 col-sm-4 col-xs-12';
			break;
		case '4':
			$class_column='col-lg-3 col-md-4 col-sm-6 col-xs-12';
			break;
		case '3':
			$class_column='col-md-4 col-sm-6 col-xs-12';
			break;
		case '2':
			$class_column='col-md-6 col-sm-6 col-xs-12';
			break;
		default:
			$class_column='col-md-3 col-sm-6 col-xs-12';
			break;
	}
	if($type=='') return;

	global $woocommerce;

	$_id = hermes_make_id();
	$_count = 1;
	$show_rating = $is_deals = false;
	if($type=='top_rate') $show_rating=true;
	if($type=='deals') $is_deals=true;
	
	$loop = hermes_woocommerce_query($type,$number, $in_category);
	$_total = $loop->found_posts > $number ? $number : $loop->found_posts;
	
	$owl_data = '';
	if($style == 'carousel'){
		$owl_data .= 'data-dots="false" data-nav="true" data-owl="slide" data-ow-rtl="false" ';
		$owl_data .= 'data-desksmall="'. esc_attr($desksmall) .'" ';
		$owl_data .= 'data-tabletsmall="'. esc_attr($tabletsmall) .'" ';
		$owl_data .= 'data-mobile="'. esc_attr($mobile_count) .'" ';
		$owl_data .= 'data-tablet="'. esc_attr($tablet_count) .'" ';
		$owl_data .= 'data-margin="'. esc_attr($margin) .'" ';
		$owl_data .= 'data-item-slide="'. esc_attr($columns) .'" ';
		$owl_data .= 'data-autoplay="'. esc_attr($autoplay) .'" ';
		$owl_data .= 'data-playtimeout="'. esc_attr($playtimeout) .'" ';
		$owl_data .= 'data-speed="'. esc_attr($speed) .'" ';
	}
	
	if ( $loop->have_posts() ) : 
		ob_start();
	?>
		
		<div class="woocommerce<?php echo (($el_class!='')?' '.$el_class:''); ?>">
			<?php if($title){ ?><h3 class="vc_widget_title vc_products_title <?php echo esc_attr($widget_style); ?>"><span><?php echo esc_html($title); ?></span></h3><?php } ?>
			<?php if($short_desc){ ?><div class="short_desc"><?php echo wpautop($short_desc) ?></div><?php } ?>
			<div class="inner-content <?php echo esc_attr($widget_style); ?>">
				<?php wc_get_template( 'product-layout/'.$style.'.php', array( 
							'show_rating' => $show_rating,
							'showon_effect' => $showon_effect,
							'_id'=>$_id,
							'loop'=>$loop,
							'columns_count'=>$columns,
							'class_column' => $class_column,
							'_total'=>$_total,
							'number'=>$number,
							'rows'=>$rows,
							'is_deals' => $is_deals,
							'itemlayout'=> $item_layout,
							'owl_attrs' => $owl_data,
							 ) ); ?>
			</div>
		</div>
	<?php 
		$content = ob_get_contents();
		ob_end_clean();
		wp_reset_postdata();
		return $content;
	endif; 
}
add_shortcode( 'specifyproducts', 'hermes_products_shortcode' );
?>