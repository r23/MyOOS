<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package myoos
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
<!-- Google fonts -->
<link href="http://fonts.googleapis.com/css?family=Noto+Serif:400,400italic,700|Open+Sans:300,400,600,700" rel="stylesheet" type="text/css">
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
<script src="<?php echo MYOOS_THEME_URL; ?>/js/html5shiv.js"></script>
<script src="<?php echo MYOOS_THEME_URL; ?>/js/respond.min.js"></script>
<![endif]-->
</head>

<body <?php body_class(); ?>>
<!--[if lt IE 7]>
	<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
<![endif]-->
<div id="wrapper">
	<!-- start header -->
	<header>
	<div class="container">
		<?php do_action( 'before' ); ?>	
		<div class="row">
			<?php if (get_theme_mod( 'header_logo_image' )) : ?>
			<div class="col-md-6 logo">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo get_theme_mod( 'header_logo_image' ); ?>"></a>
				<h1><?php bloginfo( 'description' ); ?></h1>
			</div>
			<?php else : ?>
			<div class="col-md-6 logo">
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<h3 class="site-description"><?php bloginfo( 'description' ); ?></h3>
			</div>
		
			<?php endif; ?>
				
		
		</div>
		<!-- Start of Main Nav Menu section -->
		<div class="navbar navbar-inverse" id="main-menu">
			<div class="container">
			<div class="navbar-inner">
				<!-- Responsive Navbar Part 1: Button for triggering responsive navbar (not covered in tutorial). Include responsive CSS to utilize. -->
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<!-- Our menu needs to go here -->
				<?php wp_nav_menu( array(
				'theme_location'		 => 'primary',
				'container_class' => 'nav-collapse',
				'menu_class'		=>	'nav',
				'depth'				=>	0,
				'fallback_cb'		=>	false,
				'walker'			=>	new MyOOS_Nav_Walker,
				)); 
				?>
			</div><!-- /.navbar-inner -->
			</div>
		</div><!-- /.navbar -->
		<!-- End Main Nav section -->
	</div>
	</header>
	<!-- end header -->
	<section id="inner-headline">
	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<div class="inner-heading">
					<h2>Post right sidebar</h2>
				</div>
			</div>
			<div class="col-md-8">
				<?php if ( function_exists('yoast_breadcrumb') ) {
					yoast_breadcrumb('<p id="breadcrumbs">','</p>');
				} ?>
			</div>
		</div>
	</div>
	</section>

	<section id="content">

