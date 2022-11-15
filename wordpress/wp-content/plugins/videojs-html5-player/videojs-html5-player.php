<?php
/*
Plugin Name: Videojs HTML5 Player
Version: 1.1.9
Plugin URI: https://wphowto.net/videojs-html5-player-for-wordpress-757
Author: naa986
Author URI: https://wphowto.net/
Description: Easily embed videos using videojs html5 player
Text Domain: videojs-html5-player
Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('VIDEOJS_HTML5_PLAYER')) {

    class VIDEOJS_HTML5_PLAYER {

        var $plugin_version = '1.1.9';
        var $plugin_url;
        var $plugin_path;
        var $videojs_version = '7.14.3';

        function __construct() {
            define('VIDEOJS_HTML5_PLAYER_VERSION', $this->plugin_version);
            define('VIDEOJS_HTML5_PLAYER_SITE_URL', site_url());
            define('VIDEOJS_HTML5_PLAYER_URL', $this->plugin_url());
            define('VIDEOJS_HTML5_PLAYER_PATH', $this->plugin_path());
            $this->plugin_includes();
        }

        function plugin_includes() {
            if (is_admin()) {
                add_filter('plugin_action_links', array($this, 'plugin_action_links'), 10, 2);
                include_once('addons/videojs-html5-player-addons.php');
            }
            add_action('plugins_loaded', array($this, 'plugins_loaded_handler'));
            add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
            add_action('wp_enqueue_scripts', 'videojs_html5_player_enqueue_scripts');
            add_action('admin_menu', array($this, 'add_options_menu'));
            add_action('wp_head', 'videojs_html5_player_header');
            add_shortcode('videojs_video', 'videojs_html5_video_embed_handler');
            //allows shortcode execution in the widget, excerpt and content
            add_filter('widget_text', 'do_shortcode');
            add_filter('the_excerpt', 'do_shortcode', 11);
            add_filter('the_content', 'do_shortcode', 11);
        }
        
        function enqueue_admin_scripts($hook) {
            if('settings_page_videojs-html5-player-settings' != $hook) {
                return;
            }
            wp_register_style('videojs-html5-player-addons-menu', VIDEOJS_HTML5_PLAYER_URL.'/addons/videojs-html5-player-addons.css');
            wp_enqueue_style('videojs-html5-player-addons-menu');
        }

        function plugin_url() {
            if ($this->plugin_url)
                return $this->plugin_url;
            return $this->plugin_url = plugins_url(basename(plugin_dir_path(__FILE__)), basename(__FILE__));
        }
        
        function plugin_path(){ 	
            if ( $this->plugin_path ) return $this->plugin_path;		
            return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
        }

        function plugin_action_links($links, $file) {
            if ($file == plugin_basename(dirname(__FILE__) . '/videojs-html5-player.php')) {
                $links[] = '<a href="options-general.php?page=videojs-html5-player-settings">'.__('Settings', 'videojs-html5-player').'</a>';
            }
            return $links;
        }
        
        function plugins_loaded_handler()
        {
            load_plugin_textdomain('videojs-html5-player', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/'); 
        }

        function add_options_menu() {
            if (is_admin()) {
                add_options_page(__('Videojs Settings', 'videojs-html5-player'), __('Videojs HTML5 Player', 'videojs-html5-player'), 'manage_options', 'videojs-html5-player-settings', array($this, 'options_page'));
            }
        }

        function options_page() {          
            $plugin_tabs = array(
                'videojs-html5-player-settings' => __('Add-ons', 'videojs-html5-player'),
                //'videojs-html5-player-settings&action=addons' => __('Add-ons', 'videojs-html5-player')
            );
            $url = "https://wphowto.net/videojs-html5-player-for-wordpress-757";
            $link_text = sprintf(__('Please visit the <a target="_blank" href="%s">Video.js plugin</a> documentation page for setup instructions.', 'videojs-html5-player'), esc_url($url));          
            $allowed_html_tags = array(
                'a' => array(
                    'href' => array(),
                    'target' => array()
                )
            );
            echo '<div class="wrap"><h2>Videojs HTML5 Player - v'.VIDEOJS_HTML5_PLAYER_VERSION.'</h2>';               
            echo '<div class="update-nag">'.wp_kses($link_text, $allowed_html_tags).'</div>';
            $current = '';
            $action = '';
            if (isset($_GET['page'])) {
                $current = sanitize_text_field($_GET['page']);
                if (isset($_GET['action'])) {
                    $action = sanitize_text_field($_GET['action']);
                    $current .= "&action=" . $action;
                }
            }
            $content = '';
            $content .= '<h2 class="nav-tab-wrapper">';
            foreach ($plugin_tabs as $location => $tabname) {
                if ($current == $location) {
                    $class = ' nav-tab-active';
                } else {
                    $class = '';
                }
                $content .= '<a class="nav-tab' . $class . '" href="?page=' . $location . '">' . $tabname . '</a>';
            }
            $content .= '</h2>';
            $allowed_html_tags = array(
                'a' => array(
                    'href' => array(),
                    'class' => array()
                ),
                'h2' => array(
                    'href' => array(),
                    'class' => array()
                )
            );
            echo wp_kses($content, $allowed_html_tags);

            if(!empty($action))
            { 
                switch($action)
                {
                    case 'addons':
                        videojs_html5_player_display_addons();
                        break;
                }
            }
            else
            {
                videojs_html5_player_display_addons(); //$this->general_settings();
            }

            echo '</div>';
        }

    }

    $GLOBALS['easy_video_player'] = new VIDEOJS_HTML5_PLAYER();
}

function videojs_html5_player_enqueue_scripts() {
    if (!is_admin()) {
        $plugin_url = plugins_url('', __FILE__);
        wp_enqueue_script('jquery');
        wp_register_style('videojs', $plugin_url . '/videojs/video-js.min.css');
        wp_enqueue_style('videojs');
        /*
        wp_register_style('videojs-style', $plugin_url . '/videojs-html5-player.css');
        wp_enqueue_style('videojs-style');
        */
        wp_register_script('videojs', $plugin_url . '/videojs/video.min.js', array('jquery'), VIDEOJS_HTML5_PLAYER_VERSION, true);
        wp_enqueue_script('videojs');
    }
}

function videojs_html5_player_header() {
    if (!is_admin()) {
        $config = '<!-- This site is embedding videos using the Videojs HTML5 Player plugin v' . VIDEOJS_HTML5_PLAYER_VERSION . ' - http://wphowto.net/videojs-html5-player-for-wordpress-757 -->';
        echo $config;
    }
}

function videojs_html5_video_embed_handler($atts) {
    $atts = shortcode_atts(array(
        'url' => '',
        'webm' => '',
        'ogv' => '',
        'width' => '',
        'controls' => '',
        'preload' => 'auto',
        'autoplay' => 'false',
        'loop' => '',
        'muted' => '',
        'poster' => '',
        'class' => '',
    ), $atts);
    $atts = array_map('sanitize_text_field', $atts);
    extract($atts);
    if(empty($url)){
        return __('you need to specify the src of the video file', 'videojs-html5-player');
    }
    //src
    $src = '<source src="'.esc_url($url).'" type="video/mp4" />';
    if (!empty($webm)) {
        $webm = '<source src="'.esc_url($webm).'" type="video/webm" />';
        $src = $src.$webm; 
    }
    if (!empty($ogv)) {
        $ogv = '<source src="'.esc_url($ogv).'" type="video/ogg" />';
        $src = $src.$ogv; 
    }
    //http streaming
    if (strpos($url, '.m3u8') !== false) {
        $src = '<source src="'.esc_url($url).'" type="application/x-mpegURL" />';
    }
    //controls
    if($controls == "false") {
        $controls = "";
    } 
    else{
        $controls = " controls";
    }
    //preload
    if($preload == "metadata") {
        $preload = ' preload="metadata"';
    }
    else if($preload == "none") {
        $preload = ' preload="none"';
    }
    else{
        $preload = ' preload="auto"';
    }
    //autoplay
    if($autoplay == "true"){
        $autoplay = " autoplay";
    }
    else{
        $autoplay = "";
    }
    //loop
    if($loop == "true"){
        $loop = " loop";
    }
    else{
        $loop = "";
    }
    //muted
    if($muted == "true"){
        $muted = " muted";
    }
    else{
        $muted = "";
    }
    //poster
    if(!empty($poster)) {
        $poster = ' poster="'.esc_url($poster).'"';
    }
    //playsinline
    $playsinline = ' playsinline';
    $id = uniqid();
    $player = "videojs".$id;
    $esc_attr = 'esc_attr';
    //custom style
    $style = '';   
    if(!empty($width)){
        $style = <<<EOT
        <style>
        #$player {
            max-width:{$width}px;   
        }
        </style>
EOT;
        
    }
    $output = <<<EOT
    <video-js id="{$esc_attr($player)}" class="vjs-big-play-centered"{$controls}{$preload}{$autoplay}{$loop}{$muted}{$poster}{$playsinline} data-setup='{"fluid": true}'>
        $src
    </video-js>
    $style       
EOT;
    return $output;
}
