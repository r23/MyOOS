<?php

if (!defined('ABSPATH')) {
  die('-1');
}

if (!class_exists('WPMI_Frontend')) {

  class WPMI_Frontend extends WPMI {

    private static $instance;

    function nav_menu_item_title($title, $item, $args, $depth) {
      if (!is_admin() && !wp_doing_ajax()) {
        $title = '<span class="menu-item-text">'.$title.'</span>';
        $wpmi = get_post_meta($item->ID, WPMI_DB_KEY, true);

        if ( $wpmi ) {
          $classes = array();
          $style = $color = '';

          if (isset($wpmi['icon']) && $wpmi['icon'] != '') {

            foreach ($wpmi as $key => $value) {

              if (in_array($key, array('position')) && $value != '') {
                $classes[] = "nav-icon-{$key}-{$value}";
              }

              if ($key === 'icon' && $value) {
                //EB-TODO this should not be done here
                if (strpos($value, 'dashicon') !== false) {
                  wp_enqueue_style( 'dashicons' );
                }
                $classes[] = str_replace('dashicons ', 'cps-icon cps-dashicon ', $value);
              }
            }

            if (!empty($wpmi['label'])) {
              $title = '<span class="screen-reader-text">'.$title.'</span>';
              $classes[] = 'nav-icon-no-label';
            }

            $color_val      = ( isset($wpmi['color']) ) ? $wpmi['color'] : '';
            $bgcolor_val    = ( isset($wpmi['bgcolor']) ) ? $wpmi['bgcolor'] : '';
            
            $color   = $this->get_hex_from_color_val( $color_val );
            $bgcolor = $this->get_hex_from_color_val( $bgcolor_val );

            $styles = array();
            if (!empty($color)) {
              $styles[] = 'color:' . $color;
            }

            if (!empty($bgcolor)) {
              $styles[] = 'background-color:' . $bgcolor;
              $classes[] = 'nav-icon-has-bg';
            }

            if($styles) {
              $style = ' style="' . esc_attr( implode(';', $styles) .';' ) . '"';
            }

            $icon = '<i' . $style . ' class="nav-icon ' . join(' ', array_map('esc_attr', $classes)) . '" aria-hidden="true"></i>';

            if (isset($wpmi['position']) && $wpmi['position'] == 'after') {
              $title = $title . $icon;
            } else {
              $title = $icon . $title;
            }
          }
        }
      }

      return apply_filters('wp_menu_icons_item_title', $title, $item, $wpmi, $title);
    }

    function init() {
      add_filter('nav_menu_item_title', array($this, 'nav_menu_item_title'), 999, 4);
    }

    public static function instance() {
      if (!isset(self::$instance)) {
        self::$instance = new self();
        self::$instance->init();
      }
      return self::$instance;
    }

  }

  WPMI_Frontend::instance();
}

