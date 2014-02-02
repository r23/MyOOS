<?php
/**
 * Avoid direct calls to this file
 *
 * @since 1.0
 * @author ppfeufer
 *
 * @package 2 Click Social Media Buttons
 */
if(!function_exists('add_action')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');

	exit();
} // END if(!function_exists('add_action'))

/**
 * Create HTML list of nav menu input items.
 *
 * @since 1.0
 * @author ppfeufer
 *
 * @uses Walker_Nav_Menu
 *
 * @package 2 Click Social Media Buttons
 */
class Twoclick_Social_Media_Buttons_Pages_Walker extends Walker_Nav_Menu {
	function __construct($fields = false) {
		if($fields) {
			$this->db_fields = $fields;
		}
	}

	function start_lvl(&$output, $depth) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class='children'>\n";
	}

	function end_lvl(&$output, $depth) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent</ul>";
	}

	/**
	 * @see Walker::start_el()
	 * @since WP 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param object $args
	 */
	function start_el(&$output, $item, $depth, $args) {
		global $_nav_menu_placeholder;

		$array_TwoclickSettings = get_option('twoclick_buttons_settings');
		if(is_array($array_TwoclickSettings)) {
			if(isset($array_TwoclickSettings['twoclick_buttons_exclude_page']) && is_array($array_TwoclickSettings['twoclick_buttons_exclude_page'])) {
				$array_ExcludePages = $array_TwoclickSettings['twoclick_buttons_exclude_page'];
			}
		}

		$_nav_menu_placeholder = (0 > $_nav_menu_placeholder) ? intval($_nav_menu_placeholder) - 1 : -1;
		$possible_object_id = isset($item->post_type) && 'nav_menu_item' == $item->post_type ? $item->object_id : $_nav_menu_placeholder;
		$possible_db_id = (!empty($item->ID)) && (0 < $possible_object_id) ? (int) $item->ID : 0;

		$indent = ($depth) ? str_repeat("\t", $depth) : '';

		$output .= $indent . '<li>';
		$output .= '<div class="exclude-pages-element"><label class="menu-item-title">';
		$output .= '<input type="checkbox" class="menu-item-checkbox';

		if(!empty($item->_add_to_top)) {
			$output .= ' add-to-top';
		}

		$var_sChecked = '';
		if(isset($array_ExcludePages[esc_attr($item->object_id)]) && $array_ExcludePages[esc_attr($item->object_id)] == '1') {
			$var_sChecked = ' checked="checked"';
		}

		$output .= '" name="twoclick_buttons_settings[twoclick_buttons_exclude_page][' . esc_attr($item->object_id) . ']" value="1"' . $var_sChecked . ' /> ';
		$output .= empty($item->label) ? esc_html($item->title) : esc_html($item->label);
		$output .= '</label> <span class="exclude-pages-view-page"><a href="' . get_permalink(esc_attr($item->object_id)) . '">view</a></span></div>';
	}
}