<?php

/**
 * Plugin Name: WP Menu Icons
 * Plugin URI: http://www.quadmenu.com/
 * Description: Add icons to your menu items.
 * Version: 2.0.3
 * Author: WP Menu Icons
 * Author URI: http://www.quadmenu.com
 * Copyright: 2018 QuadMenu (http://www.quadmenu.com)
 */
if (!defined('ABSPATH')) {
  die('-1');
}
if (!defined('WPMI_PLUGIN_VERSION')) {
  define('WPMI_PLUGIN_VERSION', '2.0.3');
}
if (!defined('WPMI_PLUGIN_FILE')) {
  define('WPMI_PLUGIN_FILE', __FILE__);
}
if (!defined('WPMI_PLUGIN_DIR')) {
  define('WPMI_PLUGIN_DIR', __DIR__ . DIRECTORY_SEPARATOR);
}
if (!defined('WPMI_DOMAIN')) {
  define('WPMI_DOMAIN', 'wpmi');
}
if (!defined('WPMI_DB_KEY')) {
  define('WPMI_DB_KEY', '_menu_item_wpmi');
}

if (!class_exists('WPMI')) {

  class WPMI {

    private static $instance;
    public $default_values = array(
        'label' => 0,
        'position' => 'before',
        'icon' => '',
        'color' => '',
        'bgcolor' => '',
    );

    function get_icons() {
      return array (
        'cps-icon cps-icon-user-graduate-solid',
        'cps-icon cps-icon-theater-masks-solid',
        'cps-icon cps-icon-shapes-solid',
        'cps-icon cps-icon-book-open-solid',
        'cps-icon cps-icon-book-reader-solid',
        'cps-icon cps-icon-laptop-code-solid',
        'cps-icon cps-icon-graduation-cap-solid',
        'cps-icon cps-icon-chalkboard-teacher-solid',
        'cps-icon cps-icon-chalkboard-solid',
        'cps-icon cps-icon-school-solid',
        'cps-icon cps-icon-clock-regular',
        'cps-icon cps-icon-rocket-solid',
        'cps-icon cps-icon-user-nurse-solid',
        'cps-icon cps-icon-asterisk',
        'cps-icon cps-icon-plus',
        'cps-icon cps-icon-question',
        'cps-icon cps-icon-minus',
        'cps-icon cps-icon-music',
        'cps-icon cps-icon-envelope-o',
        'cps-icon cps-icon-heart',
        'cps-icon cps-icon-star1',
        'cps-icon cps-icon-star-o',
        'cps-icon cps-icon-user1',
        'cps-icon cps-icon-home1',
        'cps-icon cps-icon-clock-o',
        'cps-icon cps-icon-road',
        'cps-icon cps-icon-inbox',
        'cps-icon cps-icon-lock1',
        'cps-icon cps-icon-headphones',
        'cps-icon cps-icon-print',
        'cps-icon cps-icon-camera1',
        'cps-icon cps-icon-video-camera',
        'cps-icon cps-icon-gift1',
        'cps-icon cps-icon-plane',
        'cps-icon cps-icon-calendar1',
        'cps-icon cps-icon-comment1',
        'cps-icon cps-icon-key',
        'cps-icon cps-icon-bell-o',
        'cps-icon cps-icon-link1',
        'cps-icon cps-icon-money',
        'cps-icon cps-icon-umbrella',
        'cps-icon cps-icon-lightbulb-o',
        'cps-icon cps-icon-user-md',
        'cps-icon cps-icon-stethoscope',
        'cps-icon cps-icon-suitcase',
        'cps-icon cps-icon-bell1',
        'cps-icon cps-icon-cutlery',
        'cps-icon cps-icon-file-text-o',
        'cps-icon cps-icon-ambulance',
        'cps-icon cps-icon-medkit',
        'cps-icon cps-icon-superscript',
        'cps-icon cps-icon-microphone',
        'cps-icon cps-icon-sun-o',
        'cps-icon cps-icon-moon-o',
        'cps-icon cps-icon-wheelchair',
        'cps-icon cps-icon-space-shuttle',
        'cps-icon cps-icon-wordpress1',
        'cps-icon cps-icon-university',
        'cps-icon cps-icon-graduation-cap',
        'cps-icon cps-icon-child',
        'cps-icon cps-icon-paw',
        'cps-icon cps-icon-recycle',
        'cps-icon cps-icon-car',
        'cps-icon cps-icon-send-o',
        'cps-icon cps-icon-copyright',
        'cps-icon cps-icon-at',
        'cps-icon cps-icon-bus',
        'cps-icon cps-icon-cc',
        'cps-icon cps-icon-user-secret',
        'cps-icon cps-icon-user-plus',
        'cps-icon cps-icon-balance-scale',
        'cps-icon cps-icon-creative-commons',
        'cps-icon cps-icon-credit-card-alt',
        'cps-icon cps-icon-universal-access',
        'cps-icon cps-icon-wheelchair-alt',
        'cps-icon cps-icon-question-circle-o',
        'cps-icon cps-icon-vcard-o',
        'cps-icon cps-icon-user-o',
        'cps-icon cps-icon-grav',
        'cps-icon cps-icon-search1',
        'cps-icon cps-icon-envelope',
        'cps-icon cps-icon-bubble',
        'cps-icon cps-icon-users1',
        'cps-icon cps-icon-bell2',
        'cps-icon cps-icon-pin',
        'cps-icon cps-icon-calendar2',
        'cps-icon cps-icon-support',
        'cps-icon cps-icon-heart2',
        'cps-icon cps-icon-home4',
        'cps-icon cps-icon-ring_volume',
        'cps-icon cps-icon-where_to_vote',
        'cps-icon cps-icon-access_alarmalarm',
        'cps-icon cps-icon-data_usage',
        'cps-icon cps-icon-insert_emoticontag_facesmood',
        'cps-icon cps-icon-insert_invitationevent',
        'cps-icon cps-icon-camera_altphoto_cameralocal_see',
        'cps-icon cps-icon-local_restaurantrestaurant_menu',
        'cps-icon cps-icon-add_location',
        'cps-icon cps-icon-near_me',
        'cps-icon cps-icon-restaurant',
        'cps-icon cps-icon-departure_board',
        'cps-icon cps-icon-pie_chart',
        'cps-icon cps-icon-cake',
        'cps-icon cps-icon-notifications',
        'cps-icon cps-icon-school',
        'cps-icon cps-icon-sentiment_dissatisfied',
        'cps-icon cps-icon-sentiment_neutral',
        'cps-icon cps-icon-sentiment_satisfied',
        'cps-icon cps-icon-sentiment_very_dissatisfied',
        'cps-icon cps-icon-sentiment_very_satisfied',
        'cps-icon cps-icon-thumb_down_alt',
        'cps-icon cps-icon-thumb_up_alt',
        'cps-icon cps-icon-account_balance',
        'cps-icon cps-icon-search',
        'cps-icon cps-icon-verified_user',
        'cps-icon cps-icon-fingerprint',
        'cps-icon cps-icon-g_translate',
        'cps-icon cps-icon-wb_twighlight',
        'cps-icon cps-icon-emoji_emotions',
        'cps-icon cps-icon-emoji_events',
        'cps-icon cps-icon-emoji_objects',
        'cps-icon cps-icon-sports_esports',
        'cps-icon cps-icon-sports_football',
        'cps-icon cps-icon-sports_golf',
        'cps-icon cps-icon-sports_hockey',
        'cps-icon cps-icon-sports_rugby',
        'cps-icon cps-icon-sports_soccer',
        'cps-icon cps-icon-sports_volleyball',
        'cps-icon cps-icon-sports_tennis',
        'cps-icon cps-icon-lunch_dining',
        'cps-icon cps-icon-park',
        'cps-icon cps-icon-icecream',
        'cps-icon cps-icon-volunteer_activism',
        'cps-icon cps-icon-ac_unit',
        'cps-icon cps-icon-beach_access',
        'cps-icon cps-icon-child_care',
        'cps-icon cps-icon-room_service',
        'cps-icon cps-icon-activity',
        'cps-icon cps-icon-aperture',
        'cps-icon cps-icon-bell',
        'cps-icon cps-icon-book-open',
        'cps-icon cps-icon-book',
        'cps-icon cps-icon-bookmark',
        'cps-icon cps-icon-calendar',
        'cps-icon cps-icon-camera',
        'cps-icon cps-icon-chevron-left',
        'cps-icon cps-icon-chevron-right',
        'cps-icon cps-icon-clock',
        'cps-icon cps-icon-cloud',
        'cps-icon cps-icon-coffee',
        'cps-icon cps-icon-compass',
        'cps-icon cps-icon-dollar-sign',
        'cps-icon cps-icon-eye',
        'cps-icon cps-icon-file-text',
        'cps-icon cps-icon-folder',
        'cps-icon cps-icon-gift',
        'cps-icon cps-icon-globe',
        'cps-icon cps-icon-grid',
        'cps-icon cps-icon-heart3',
        'cps-icon cps-icon-help-circle',
        'cps-icon cps-icon-home',
        'cps-icon cps-icon-category',
        'cps-icon cps-icon-info',
        'cps-icon cps-icon-life-buoy',
        'cps-icon cps-icon-link',
        'cps-icon cps-icon-lock',
        'cps-icon cps-icon-mail',
        'cps-icon cps-icon-map-pin',
        'cps-icon cps-icon-menu',
        'cps-icon cps-icon-comment',
        'cps-icon cps-icon-paperclip',
        'cps-icon cps-icon-phone',
        'cps-icon cps-icon-printer',
        'cps-icon cps-icon-settings',
        'cps-icon cps-icon-share',
        'cps-icon cps-icon-shopping-bag',
        'cps-icon cps-icon-star',
        'cps-icon cps-icon-tag',
        'cps-icon cps-icon-thumbs-up',
        'cps-icon cps-icon-user',
        'cps-icon cps-icon-users',
        'cps-icon cps-icon-close',
        'cps-icon cps-icon-home2',
        'cps-icon cps-icon-home21',
        'cps-icon cps-icon-home3',
        'cps-icon cps-icon-pencil',
        'cps-icon cps-icon-pencil2',
        'cps-icon cps-icon-headphones1',
        'cps-icon cps-icon-music1',
        'cps-icon cps-icon-price-tags',
        'cps-icon cps-icon-lifebuoy',
        'cps-icon cps-icon-pushpin',
        'cps-icon cps-icon-location2',
        'cps-icon cps-icon-tv',
        'cps-icon cps-icon-drawer',
        'cps-icon cps-icon-bubbles3',
        'cps-icon cps-icon-bubbles4',
        'cps-icon cps-icon-key1',
        'cps-icon cps-icon-lock2',
        'cps-icon cps-icon-wrench',
        'cps-icon cps-icon-magic-wand',
        'cps-icon cps-icon-aid-kit',
        'cps-icon cps-icon-stats-dots',
        'cps-icon cps-icon-trophy',
        'cps-icon cps-icon-gift2',
        'cps-icon cps-icon-spoon-knife',
        'cps-icon cps-icon-lab',
        'cps-icon cps-icon-sphere',
        'cps-icon cps-icon-earth',
        'cps-icon cps-icon-heart1',
        'cps-icon cps-icon-facebook',
        'cps-icon cps-icon-instagram',
        'cps-icon cps-icon-twitter',
        'cps-icon cps-icon-youtube',
        'cps-icon cps-icon-wordpress',
        'cps-icon cps-icon-tumblr',
        'cps-icon cps-icon-linkedin1',
        'cps-icon cps-icon-linkedin',
        'dashicons dashicons-menu',
        'dashicons dashicons-admin-site',
        'dashicons dashicons-dashboard',
        'dashicons dashicons-admin-post',
        'dashicons dashicons-admin-media',
        'dashicons dashicons-admin-links',
        'dashicons dashicons-admin-page',
        'dashicons dashicons-admin-comments',
        'dashicons dashicons-admin-appearance',
        'dashicons dashicons-admin-plugins',
        'dashicons dashicons-admin-users',
        'dashicons dashicons-admin-tools',
        'dashicons dashicons-admin-settings',
        'dashicons dashicons-admin-network',
        'dashicons dashicons-admin-home',
        'dashicons dashicons-admin-generic',
        'dashicons dashicons-admin-collapse',
        'dashicons dashicons-filter',
        'dashicons dashicons-admin-customizer',
        'dashicons dashicons-admin-multisite',
        'dashicons dashicons-welcome-write-blog',
        'dashicons dashicons-welcome-add-page',
        'dashicons dashicons-welcome-view-site',
        'dashicons dashicons-welcome-widgets-menus',
        'dashicons dashicons-welcome-comments',
        'dashicons dashicons-welcome-learn-more',
        'dashicons dashicons-format-aside',
        'dashicons dashicons-format-image',
        'dashicons dashicons-format-gallery',
        'dashicons dashicons-format-video',
        'dashicons dashicons-format-status',
        'dashicons dashicons-format-quote',
        'dashicons dashicons-format-chat',
        'dashicons dashicons-format-audio',
        'dashicons dashicons-camera',
        'dashicons dashicons-images-alt',
        'dashicons dashicons-images-alt2',
        'dashicons dashicons-video-alt',
        'dashicons dashicons-video-alt2',
        'dashicons dashicons-video-alt3',
        'dashicons dashicons-media-archive',
        'dashicons dashicons-media-audio',
        'dashicons dashicons-media-code',
        'dashicons dashicons-media-default',
        'dashicons dashicons-media-document',
        'dashicons dashicons-media-interactive',
        'dashicons dashicons-media-spreadsheet',
        'dashicons dashicons-media-text',
        'dashicons dashicons-media-video',
        'dashicons dashicons-playlist-audio',
        'dashicons dashicons-playlist-video',
        'dashicons dashicons-controls-play',
        'dashicons dashicons-controls-pause',
        'dashicons dashicons-controls-forward',
        'dashicons dashicons-controls-skipforward',
        'dashicons dashicons-controls-back',
        'dashicons dashicons-controls-skipback',
        'dashicons dashicons-controls-repeat',
        'dashicons dashicons-controls-volumeon',
        'dashicons dashicons-controls-volumeoff',
        'dashicons dashicons-image-crop',
        'dashicons dashicons-image-rotate',
        'dashicons dashicons-image-rotate-left',
        'dashicons dashicons-image-rotate-right',
        'dashicons dashicons-image-flip-vertical',
        'dashicons dashicons-image-flip-horizontal',
        'dashicons dashicons-image-filter',
        'dashicons dashicons-undo',
        'dashicons dashicons-redo',
        'dashicons dashicons-editor-bold',
        'dashicons dashicons-editor-italic',
        'dashicons dashicons-editor-ul',
        'dashicons dashicons-editor-ol',
        'dashicons dashicons-editor-quote',
        'dashicons dashicons-editor-alignleft',
        'dashicons dashicons-editor-aligncenter',
        'dashicons dashicons-editor-alignright',
        'dashicons dashicons-editor-insertmore',
        'dashicons dashicons-editor-spellcheck',
        'dashicons dashicons-editor-expand',
        'dashicons dashicons-editor-contract',
        'dashicons dashicons-editor-kitchensink',
        'dashicons dashicons-editor-underline',
        'dashicons dashicons-editor-justify',
        'dashicons dashicons-editor-textcolor',
        'dashicons dashicons-editor-paste-word',
        'dashicons dashicons-editor-paste-text',
        'dashicons dashicons-editor-removeformatting',
        'dashicons dashicons-editor-video',
        'dashicons dashicons-editor-customchar',
        'dashicons dashicons-editor-outdent',
        'dashicons dashicons-editor-indent',
        'dashicons dashicons-editor-help',
        'dashicons dashicons-editor-strikethrough',
        'dashicons dashicons-editor-unlink',
        'dashicons dashicons-editor-rtl',
        'dashicons dashicons-editor-break',
        'dashicons dashicons-editor-code',
        'dashicons dashicons-editor-paragraph',
        'dashicons dashicons-editor-table',
        'dashicons dashicons-align-left',
        'dashicons dashicons-align-right',
        'dashicons dashicons-align-center',
        'dashicons dashicons-align-none',
        'dashicons dashicons-lock',
        'dashicons dashicons-unlock',
        'dashicons dashicons-calendar',
        'dashicons dashicons-calendar-alt',
        'dashicons dashicons-visibility',
        'dashicons dashicons-hidden',
        'dashicons dashicons-post-status',
        'dashicons dashicons-edit',
        'dashicons dashicons-trash',
        'dashicons dashicons-sticky',
        'dashicons dashicons-external',
        'dashicons dashicons-arrow-up',
        'dashicons dashicons-arrow-down',
        'dashicons dashicons-arrow-right',
        'dashicons dashicons-arrow-left',
        'dashicons dashicons-arrow-up-alt',
        'dashicons dashicons-arrow-down-alt',
        'dashicons dashicons-arrow-right-alt',
        'dashicons dashicons-arrow-left-alt',
        'dashicons dashicons-arrow-up-alt2',
        'dashicons dashicons-arrow-down-alt2',
        'dashicons dashicons-arrow-right-alt2',
        'dashicons dashicons-arrow-left-alt2',
        'dashicons dashicons-sort',
        'dashicons dashicons-leftright',
        'dashicons dashicons-randomize',
        'dashicons dashicons-list-view',
        'dashicons dashicons-exerpt-view',
        'dashicons dashicons-grid-view',
        'dashicons dashicons-share',
        'dashicons dashicons-share-alt',
        'dashicons dashicons-share-alt2',
        'dashicons dashicons-twitter',
        'dashicons dashicons-rss',
        'dashicons dashicons-email',
        'dashicons dashicons-email-alt',
        'dashicons dashicons-facebook',
        'dashicons dashicons-facebook-alt',
        'dashicons dashicons-googleplus',
        'dashicons dashicons-networking',
        'dashicons dashicons-hammer',
        'dashicons dashicons-art',
        'dashicons dashicons-migrate',
        'dashicons dashicons-performance',
        'dashicons dashicons-universal-access',
        'dashicons dashicons-universal-access-alt',
        'dashicons dashicons-tickets',
        'dashicons dashicons-nametag',
        'dashicons dashicons-clipboard',
        'dashicons dashicons-heart',
        'dashicons dashicons-megaphone',
        'dashicons dashicons-schedule',
        'dashicons dashicons-wordpress',
        'dashicons dashicons-wordpress-alt',
        'dashicons dashicons-pressthis',
        'dashicons dashicons-update',
        'dashicons dashicons-screenoptions',
        'dashicons dashicons-info',
        'dashicons dashicons-cart',
        'dashicons dashicons-feedback',
        'dashicons dashicons-cloud',
        'dashicons dashicons-translation',
        'dashicons dashicons-tag',
        'dashicons dashicons-category',
        'dashicons dashicons-archive',
        'dashicons dashicons-tagcloud',
        'dashicons dashicons-text',
        'dashicons dashicons-yes',
        'dashicons dashicons-no',
        'dashicons dashicons-no-alt',
        'dashicons dashicons-plus',
        'dashicons dashicons-plus-alt',
        'dashicons dashicons-minus',
        'dashicons dashicons-dismiss',
        'dashicons dashicons-marker',
        'dashicons dashicons-star-filled',
        'dashicons dashicons-star-half',
        'dashicons dashicons-star-empty',
        'dashicons dashicons-flag',
        'dashicons dashicons-warning',
        'dashicons dashicons-location',
        'dashicons dashicons-location-alt',
        'dashicons dashicons-vault',
        'dashicons dashicons-shield',
        'dashicons dashicons-shield-alt',
        'dashicons dashicons-sos',
        'dashicons dashicons-search',
        'dashicons dashicons-slides',
        'dashicons dashicons-analytics',
        'dashicons dashicons-chart-pie',
        'dashicons dashicons-chart-bar',
        'dashicons dashicons-chart-line',
        'dashicons dashicons-chart-area',
        'dashicons dashicons-groups',
        'dashicons dashicons-businessman',
        'dashicons dashicons-id',
        'dashicons dashicons-id-alt',
        'dashicons dashicons-products',
        'dashicons dashicons-awards',
        'dashicons dashicons-forms',
        'dashicons dashicons-testimonial',
        'dashicons dashicons-portfolio',
        'dashicons dashicons-book',
        'dashicons dashicons-book-alt',
        'dashicons dashicons-download',
        'dashicons dashicons-upload',
        'dashicons dashicons-backup',
        'dashicons dashicons-clock',
        'dashicons dashicons-lightbulb',
        'dashicons dashicons-microphone',
        'dashicons dashicons-desktop',
        'dashicons dashicons-tablet',
        'dashicons dashicons-smartphone',
        'dashicons dashicons-phone',
        'dashicons dashicons-index-card',
        'dashicons dashicons-carrot',
        'dashicons dashicons-building',
        'dashicons dashicons-store',
        'dashicons dashicons-album',
        'dashicons dashicons-palmtree',
        'dashicons dashicons-tickets-alt',
        'dashicons dashicons-money',
        'dashicons dashicons-smiley',
        'dashicons dashicons-thumbs-up',
        'dashicons dashicons-thumbs-down',
        'dashicons dashicons-layout',
     );
    }
    
    function get_predefined_colors() {
        static $colors;
        if( isset( $colors ) ) {
            return $colors;
        }
        
        /* Example of color array 
         $colors = array(
            "red"   => array("hex" => "#ff0000", "name" => "Red" ),
            "green" => array("hex" => "#00ff00", "name" => "Green" ),
            "blue"  => array("hex" => "#0000ff", "name" => "Blue" ),
        );
        */
        $colors = array();
        $colors = apply_filters( 'wpmi_predefined_colors', $colors );
        return $colors;
    }

    function get_hex_from_color_val( $val ) {
        if( $val == '' || $val == 'none' ) {
            return '';
        }
        if( 0 === strpos($val, '#') ) {
            return $val;
        }
        $colors = $this->get_predefined_colors();
        if( isset( $colors[ $val ] ) ) {
            return $colors[ $val ]['hex'];
        } else {
            return '';
        }

    }

    function nav_menu_selected_id() {

      $nav_menus = wp_get_nav_menus(array('orderby' => 'name'));

      $menu_count = count($nav_menus);

      // Get recently edited nav menu
      $recently_edited = (int) get_user_option('nav_menu_recently_edited');

      $nav_menu_selected_id = isset($_REQUEST['menu']) ? (int) $_REQUEST['menu'] : 0;

      // Are we on the add new screen?
      $add_new_screen = ( isset($_GET['menu']) && 0 == $_GET['menu'] ) ? true : false;

      $page_count = wp_count_posts('page');

      $one_theme_location_no_menus = ( 1 == count(get_registered_nav_menus()) && !$add_new_screen && empty($nav_menus) && !empty($page_count->publish) ) ? true : false;

      if (empty($recently_edited) && is_nav_menu($nav_menu_selected_id))
        $recently_edited = $nav_menu_selected_id;

      // Use $recently_edited if none are selected.
      if (empty($nav_menu_selected_id) && !isset($_GET['menu']) && is_nav_menu($recently_edited))
        $nav_menu_selected_id = $recently_edited;

      // On deletion of menu, if another menu exists, show it.
      if (!$add_new_screen && 0 < $menu_count && isset($_GET['action']) && 'delete' == $_GET['action'])
        $nav_menu_selected_id = $nav_menus[0]->term_id;

      // Set $nav_menu_selected_id to 0 if no menus.
      if ($one_theme_location_no_menus) {
        $nav_menu_selected_id = 0;
      } elseif (empty($nav_menu_selected_id) && !empty($nav_menus) && !$add_new_screen) {
        // if we have no selection yet, and we have menus, set to the first one in the list.
        $nav_menu_selected_id = $nav_menus[0]->term_id;
      }

      return $nav_menu_selected_id;
    }

    function nav_menu_item_icons() {

      $nav_menu_item_icons = array();

      foreach (wp_get_nav_menu_items($this->nav_menu_selected_id()) as $key => $item) {

        if (isset($item->icon) && $item->icon != '') {

          $nav_menu_item_icons[$item->ID] = $item->icon;
        }
      }

      return $nav_menu_item_icons;
    }

    function setup_nav_menu_item_icon($item) {

      $item->wpmi = new stdClass();

      if ($wpmi = wp_parse_args((array) get_post_meta($item->ID, WPMI_DB_KEY, true), $this->default_values)) {

        if (count($wpmi)) {
          foreach ($wpmi as $key => $value) {
            $item->wpmi->{$key} = $value;
          }
        }
      }

      return $item;
    }

    function includes() {
      require_once('includes/backend.php');
      require_once('includes/frontend.php');
    }

    function init() {
      add_filter('wp_setup_nav_menu_item', array($this, 'setup_nav_menu_item_icon'));
    }

    public static function do_activation() {
    }

    public static function instance() {
      if (!isset(self::$instance)) {
        self::$instance = new WPMI;
        self::$instance->init();
        self::$instance->includes();
      }
      return self::$instance;
    }

  }

  if (!function_exists('_WPMI')) {

    function _WPMI() {
      return WPMI::instance();
    }

    _WPMI();
  }
}


register_activation_hook(WPMI_PLUGIN_FILE, array('WPMI', 'do_activation'));
