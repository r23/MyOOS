<?php
/**
 * myoos functions and definitions
 *
 * @package myoos
 */

define( 'MYOOS_VERSION', '2.0.14' );
define( 'MYOOS_THEME_URL', get_template_directory_uri() );
define( 'MYOOS_THEME_TEMPLATE', get_template_directory() ); 
 
/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */

if ( ! function_exists( 'myoos_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
function myoos_setup() {

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on myoos, use a find and replace
	 * to change 'myoos' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'myoos', MYOOS_THEME_TEMPLATE . '/languages' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable support for Post Thumbnails on posts and pages
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'myoos' ),
	) );

	/**
	 * Enable support for Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );

	/**
	 * Setup the WordPress core custom background feature.
	 */
	add_theme_support( 'custom-background', apply_filters( 'myoos_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // myoos_setup
add_action( 'after_setup_theme', 'myoos_setup' );

/**
 * Register widgetized area and update sidebar with default widgets
 */
function myoos_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'myoos' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'myoos_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function myoos_scripts() {
	wp_enqueue_style( 'myoos-style', get_stylesheet_uri(), false, null);
	wp_enqueue_style( 'myoos-bootstrap', MYOOS_THEME_URL . '/css/bootstrap.min.css', false ,'3.0.0', 'all' );
	wp_enqueue_style( 'myoos-custom', MYOOS_THEME_URL . '/css/custom.css', false ,'1.0.0', 'all' );
	wp_enqueue_style( 'font-awesome', MYOOS_THEME_URL . '/css/font-awesome.min.css', false ,'3.2.0', 'all' );

	wp_enqueue_script( 'myoos-bootstrap', MYOOS_THEME_URL . '/js/bootstrap.min.js', array(), '20130821', true );
	wp_enqueue_script( 'myoos-application', MYOOS_THEME_URL . '/js/application.js', array(), '20130821', true );	
	
	wp_enqueue_script( 'myoos-navigation', MYOOS_THEME_URL . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'myoos-skip-link-focus-fix', MYOOS_THEME_URL . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'myoos-keyboard-image-navigation', MYOOS_THEME_URL . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}
}
add_action( 'wp_enqueue_scripts', 'myoos_scripts' );

/**
 * Implement the Custom Header feature.
 */
//require MYOOS_THEME_TEMPLATE . '/inc/custom-header.php';

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
require MYOOS_THEME_TEMPLATE . '/inc/myoos-customizer.php';
require MYOOS_THEME_TEMPLATE . '/inc/nav-menu-walker.php';

/**
 * Load Jetpack compatibility file.
 */
require MYOOS_THEME_TEMPLATE . '/inc/jetpack.php';


remove_action('wp_head', 'wp_generator');
