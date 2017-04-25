<?php
/*
	Plugin Name: R23 Sketchfab Plugin
	Version: 1.0.2.
	Plugin URI: https://blog.r23.de/wordpress/wordpress-plugins/
	Description: Display Sketchfab models to wordpress.
	Author: r23 Team.
	Author URI: https://blog.r23.de/
	Text Domain: sketchfab-plugin
	Domain Path: /languages
	License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

/*
Copyright (C) 2017 r23

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/



/* Quit */
if ( !defined( 'ABSPATH' ) ){
    exit;
}


/* Konstanten */
define('R23_SKETCHFAB_DIR', dirname(__FILE__));
define('R23_SKETCHFAB_TEXTDOMAIN', 'sketchfab-plugin');
define('R23_SKETCHFAB_L10N_DIR', dirname(plugin_basename( __FILE__ )) . '/languages/');

if ( ! class_exists( 'R23_panel_shortcode', false ) ) {

	class R23_panel_shortcode{

		/**
		* $shortcode_tag 
		* holds the name of the shortcode tag
		* @var string
		*/
		public $shortcode_tag = 'sketchfab';

		/**
		* __construct 
		* class constructor will set the needed filter and action hooks
		* 
		* @param array $args 
		*/
		function __construct($args = array()){
			//add shortcode
			add_shortcode( $this->shortcode_tag, array( $this, 'shortcode_handler' ) );
		
			if ( is_admin() ){
				add_action('admin_head', array( $this, 'admin_head') );
			}
		}

		/**
		* shortcode_handler
		* @param  array  $atts shortcode attributes
		* @param  string $content shortcode content
		* @return string
		*/
		function shortcode_handler($atts , $content = null){
			// Attributes
			extract(shortcode_atts(array(
				'id' => '',
				"start" => get_settings('sketchfab-autostart'),
				"spin" => get_settings('sketchfab-autospin'),
				"preload" => get_settings('sketchfab-preload'),
				"width" => get_settings('sketchfab-width'),
				"height" => get_settings('sketchfab-height'),
			), $atts));


/*			
			GERMAN:
			Diese Rueckverlinkung darf nur entfernt werden,
			wenn Sie eine Branding-Free-Lizenz besitzen.
			:: Lizenzbedingungen: 
			https://blog.r23.de/

			ENGLISH:
			This back linking maybe only removed,
			if you possess a Branding-Free-Lizenz license.
			:: License conditions: 
			https://blog.r23.de/
*/			
			$output = '<!-- / R23 Sketchfab Plugin https://blog.r23.de/wordpress/wordpress-plugins/ -->';
			$output .= '<div class="sketchfab-embed-wrapper">';
			$output .= '<iframe width="' . $width . '" height="' . $height .'" ';
			$output .= 'src="https://sketchfab.com/models/'.$id.'/embed?';
			$output .= 'autospin='.$spin.'&amp;autostart='.$start.'&amp;preload='.$preload.'" ';
			$output .= 'frameborder="0" allowvr allowfullscreen mozallowfullscreen="true" webkitallowfullscreen="true" onmousewheel=""></iframe>';	

			$output .= '<div class="clear"></div>';
			$output .= '</div><!-- .r23 (end) -->';

			//return shortcode output
			return $output;
		}

		/**
		* admin_head
		* calls your functions into the correct filters
		* @return void
		*/
		function admin_head() {
			// check user permissions
			if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
				return;
			}
		
			// check if WYSIWYG is enabled
			if ( 'true' == get_user_option( 'rich_editing' ) ) {
				add_filter( 'mce_external_plugins', array( $this ,'mce_external_plugins' ) );
				add_filter( 'mce_buttons', array($this, 'mce_buttons' ) );
			}
		}


	
		/**
		* mce_external_plugins 
		* Adds our tinymce plugin
		* @param  array $plugin_array 
		* @return array
		*/
		function mce_external_plugins( $plugin_array ) {
			$plugin_array[$this->shortcode_tag] = plugins_url( 'admin/js/editor_plugin.js' , __FILE__ );
			return $plugin_array;
		}

		/**
		* mce_buttons 
		* Adds our tinymce button
		* @param  array $buttons 
		* @return array
		*/
		function mce_buttons( $buttons ) {
			array_push( $buttons, $this->shortcode_tag );
			return $buttons;
		}

	}//end class

	new R23_panel_shortcode();

}

// Load translations
function sketchfab_load_plugin_textdomain() {
	$locale = apply_filters( 'plugin_locale', get_locale(), R23_SKETCHFAB_TEXTDOMAIN );
	// wp-content/languages/plugin-name/plugin-name-de_DE.mo
	load_textdomain( R23_SKETCHFAB_TEXTDOMAIN, trailingslashit( WP_LANG_DIR ) . R23_SKETCHFAB_TEXTDOMAIN . '/' . R23_SKETCHFAB_TEXTDOMAIN . '-' . $locale . '.mo' );
	// wp-content/plugins/plugin-name/languages/plugin-name-de_DE.mo
	load_plugin_textdomain( R23_SKETCHFAB_TEXTDOMAIN, false, R23_SKETCHFAB_L10N_DIR );
}
add_action( 'init', 'sketchfab_load_plugin_textdomain' ); 
	
	
	
	
// Add settings menu to Wordpress
if ( is_admin() ){ // admin actions
	add_action( 'admin_menu', 'sketchfab_create_menu' );
} else {
    // non-admin enqueues, actions, and filters
}

function sketchfab_create_menu() {
	  
	$plugin_menu_title = __('Sketchfab Plugin Settings', R23_SKETCHFAB_TEXTDOMAIN);
	// Create top-level menu
	add_menu_page($plugin_menu_title, 'Sketchfab', 'administrator',
      __FILE__, 'sketchfab_settings_page', plugins_url('/images/sketchfab-menu-icon.png', __FILE__));
  
	// Call register settings function
	add_action( 'admin_init', 'register_settings' );
}

function register_settings() { // whitelist options
	register_setting( 'settings-group', 'sketchfab-width' );
	register_setting( 'settings-group', 'sketchfab-height' );
	register_setting( 'settings-group', 'sketchfab-autospin' );
	register_setting( 'settings-group', 'sketchfab-autostart' );
	register_setting( 'settings-group', 'sketchfab-preload' );
}

// Page displayed as the settings page
function sketchfab_settings_page() {
?>
  <div class="wrap">
  <h2><?php echo __('R23 Sketchfab Plugin', R23_SKETCHFAB_TEXTDOMAIN); ?></h2>

  <form method="post" action="options.php">
    <?php settings_fields( 'settings-group' ); ?>
    
    <h3><?php echo __('Default settings', R23_SKETCHFAB_TEXTDOMAIN); ?></h3>
    <table class="form-table">
      <tr valign="top">
        <th scope="row"><?php echo __('Width', R23_SKETCHFAB_TEXTDOMAIN); ?></th>
        <td><input type="text" name="sketchfab-width" value="<?php echo get_option('sketchfab-width'); ?>" /> px</td>
      </tr>
      <tr valign="top">
        <th scope="row"><?php echo __('Height', R23_SKETCHFAB_TEXTDOMAIN); ?></th>
        <td><input type="text" name="sketchfab-height" value="<?php echo get_option('sketchfab-height'); ?>" /> px</td>
      </tr>
      <tr valign="top">
        <th scope="row"><?php echo __('Autospin', R23_SKETCHFAB_TEXTDOMAIN); ?></th>
        <td><input type="text" name="sketchfab-autospin" value="<?php echo get_option('sketchfab-autospin'); ?>" /></td>
      </tr>
      <tr valign="top">
        <th scope="row"><?php echo __('Autostart', R23_SKETCHFAB_TEXTDOMAIN); ?></th>
        <td><input type="checkbox" name="sketchfab-autostart" value="1" <?php checked(get_option('sketchfab-autostart'), 1); ?>/></td>
      </tr>
      <tr valign="top">
        <th scope="row"><?php echo __('Preload', R23_SKETCHFAB_TEXTDOMAIN); ?></th>
        <td><input type="checkbox" name="sketchfab-preload" value="1" <?php checked(get_option('sketchfab-preload'), 1); ?>/></td>
      </tr>

    </table>
    
    <?php submit_button(); ?>
  </form> 
</div>

<?php } ?>