<?php
/**
* Bootstrap extras
*
* @package LionThemes
* @subpackage Hermes_theme
* @since Hermes Themes 1.7
*/

if (!function_exists('hermes_bootstrap_comment_reply_link_class')) {
	/**
	 * modify comment reply link by adding bootstrap button class.
	 * 
	 * @todo Change comment link class modification to use WordPress hook action/filter when it's available.
	 * @param string $class
	 * @return string
	 */
	function hermes_bootstrap_comment_reply_link_class($class) 
	{
		$class = str_replace("class='comment-reply-link", "class='comment-reply-link btn btn-default btn-sm", $class);
		$class = str_replace("class=\"comment-reply-login", "class=\"comment-reply-login btn btn-default btn-sm", $class);

		return $class;
	}// hermes_bootstrap_comment_reply_link_class
}
add_filter('comment_reply_link', 'hermes_bootstrap_comment_reply_link_class');


if (!function_exists('hermes_bootstrap_excerpt_more')) {
	function hermes_bootstrap_excerpt_more($more) 
	{
		return ' &hellip;';
	}// hermes_bootstrap_excerpt_more
}
add_filter('excerpt_more', 'hermes_bootstrap_excerpt_more');


if (!function_exists('hermes_bootstrap_image_send_to_editor')) {
	/**
	 * remove rel attachment that is not valid html element
	 * @param string $html
	 * @param integer $id
	 * @return string
	 */
	function hermes_bootstrap_image_send_to_editor($html, $id) 
	{
		if ($id > 0) {
			$html = str_replace('rel="attachment wp-att-'.$id.'"', '', $html);
		}

		return $html;
	}// hermes_bootstrap_image_send_to_editor
}
add_filter('image_send_to_editor', 'hermes_bootstrap_image_send_to_editor', 10, 2);


if (!function_exists('hermes_bootstrap_link_pages_link')) {
	/**
	 * replace pagination in posts/pages content to support bootstrap pagination class.
	 * 
	 * @param string $link
	 * @param integer $i
	 * @return string
	 */
	function hermes_bootstrap_link_pages_link($link, $i) 
	{
		if (strpos($link, '<a') === false) {
			return '<li class="active"><a href="#">' . $link . '</a></li>';
		} else {
			return '<li>' . $link . '</li>';
		}
	}// hermes_bootstrap_link_pages_link
}
add_filter('wp_link_pages_link', 'hermes_bootstrap_link_pages_link', 10, 2);


if (!function_exists('hermes_bootstrap_nav_menu_class')) {
	/**
	 * Add custom class to nav menu
	 * @param array $classes
	 * @param object $menu_item
	 * @return array
	 */
	function hermes_bootstrap_nav_menu_class($classes = array(), $menu_item = false) 
	{
		if (!is_array($menu_item->classes)) {
			return $classes;
		}

		if(in_array('current-menu-item', $menu_item->classes)){
			$classes[] = 'active';
		}

		if (in_array('menu-item-has-children', $menu_item->classes)) {
			$classes[] = 'dropdown';
		}

		if (in_array('sub-menu', $menu_item->classes)) {
			$classes[] = 'dropdown-menu';
		}

		return $classes;
	}// hermes_bootstrap_nav_menu_class
}
add_filter('nav_menu_css_class', 'hermes_bootstrap_nav_menu_class', 10, 2);

