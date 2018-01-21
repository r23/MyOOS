<?php
/**
 * myoos functions and definitions
 *
 * @package myoos
 */
define( 'MYOOS_THEME_URL', get_template_directory_uri() );
define( 'MYOOS_THEME_TEMPLATE', get_template_directory() );


if ( ! function_exists( 'myoos_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function myoos_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on myoos, use a find and replace
	 * to change 'myoos' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'myoos', MYOOS_THEME_TEMPLATE . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	add_image_size( 'myoos-thumb', 300, 240, true );           // Thumb
	add_image_size( 'myoos-blog', 800, 480, true );            // Blog
	add_image_size( 'myoos-half', 585, 456, true );            // Half
	add_image_size( 'myoos-one-third', 360, 288, true );       // One third
	add_image_size( 'myoos-two-thirds', 750, 600, true );      // Two thirds
	add_image_size( 'myoos-full', 1130, 904, true );           // Whole
	add_image_size( 'myoos-square', 350, 350, true );          // Square
	add_image_size( 'myoos-square-big', 700, 700, true );      // Square Big
	add_image_size( 'myoos-wide', 700, 350, true );            // Wide
	add_image_size( 'myoos-tall', 350, 700, true );            // Tall




	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'myoos' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
	) );





	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'myoos_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // myoos_setup
add_action( 'after_setup_theme', 'myoos_setup' );


/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function myoos_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'myoos_content_width', 640 );
}
add_action( 'after_setup_theme', 'myoos_content_width', 0 );

/*
 * Loads the Options Panel
 *
 * If you're loading from a child theme use stylesheet_directory
 * instead of template_directory
 */

define( 'OPTIONS_FRAMEWORK_DIRECTORY', MYOOS_THEME_URL . '/inc/admin/' );
require_once dirname( __FILE__ ) . '/inc/admin/options-framework.php';

// Loads options.php from child or parent theme
$optionsfile = locate_template( 'options.php' );
load_template( $optionsfile );


/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function myoos_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'myoos' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'myoos_widgets_init' );


/**
 *  Allow Shortcodes in Widget Text
 */
add_filter('widget_text', 'do_shortcode');



/**
 * Enqueue scripts and styles.
 */
function myoos_scripts() {

	wp_enqueue_script( 'bootstrap', MYOOS_THEME_URL . '/js/bootstrap.min.js', array( 'jquery' ), '3.3.5', true );
	wp_enqueue_script( 'myoos-navigation', MYOOS_THEME_URL . '/js/navigation.js', array(), '20120206', true );
	wp_enqueue_script( 'myoos-skip-link-focus-fix', MYOOS_THEME_URL . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	wp_enqueue_style( 'myoos-style', get_stylesheet_uri() );
	wp_enqueue_style( 'google_fonts', '//fonts.googleapis.com/css?family=Megrim|Raleway|Open+Sans:400,400italic,700,700italic', false, null, 'all' );
	wp_enqueue_style( 'font_awesome', MYOOS_THEME_URL . '/css/font-awesome.min.css', false, '4.3.0', 'all' );

	// Add slider CSS only if is front page ans slider is enabled
	if( ( is_home() || is_front_page() ) && of_get_option('myoos_slider_checkbox') == 1 ) {
		wp_enqueue_style( 'flexslider', MYOOS_THEME_URL . '/css/flexslider.min.css' );
	}

	// Add slider JS only if is front page ans slider is enabled
	if( ( is_home() || is_front_page() ) && of_get_option('myoos_slider_checkbox') == 1 ) {
		// Add slider JS only if is front page ans slider is enabled
		wp_enqueue_script( 'flexslider', MYOOS_THEME_URL . '/js/jquery.flexslider-min.js', array('jquery'), '2.2.5', true );
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'myoos_scripts' );

/**
 * Remove version numbers and meta generator
 */
remove_action('wp_head', 'wp_generator');


function _remove_version() {
    return '';
}
add_filter('the_generator', '_remove_version');

function _remove_script_version( $src ){
    if ( strpos( $src, 'ver=' ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}
add_filter( 'script_loader_src', '_remove_script_version', 9999 );
add_filter( 'style_loader_src', '_remove_script_version', 9999 );

/**
 * Puts a link to MyOOS Community in the WordPress admin footer.
 */
function myoos_footer_text($default) {
	$default .= ' <span id="footer-myoos">' . __('| MyOOS integration by <a href="http://foren.myoos.de/">MyOOS Community</a>.', 'myoos') . '</span>';
	return $default;
}


if ( is_admin() ) {
	add_filter( 'admin_footer_text', 'myoos_footer_text' );
}

/**
 * Implement the Custom Header feature.
 */
require MYOOS_THEME_TEMPLATE . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require MYOOS_THEME_TEMPLATE . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require MYOOS_THEME_TEMPLATE . '/inc/extras.php';

/**
 * Customizer additions.
 */
require MYOOS_THEME_TEMPLATE . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require MYOOS_THEME_TEMPLATE . '/inc/jetpack.php';

/**
 * Load custom nav walker
 */
require MYOOS_THEME_TEMPLATE . '/inc/wp_bootstrap_navwalker.php';
