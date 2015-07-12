<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package myoos
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no">

<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
	<script src="<?php echo MYOOS_THEME_URL; ?>/js/html5shiv.js"></script>
	<script src="<?php echo MYOOS_THEME_URL; ?>/js/respond.min.js"></script>
<![endif]-->
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'myoos' ); ?></a>

	<header id="masthead" class="site-header" role="banner">
		<nav class="navbar navbar-default" role="navigation">
			<div class="container">
				<div class="row">
					<div class="site-navigation-inner col-sm-12">
						<div class="navbar-header">
		
							<!-- Toggle get grouped for better mobile display -->
							<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-responsive-collapse">
								<span class="sr-only"><?php esc_html_e( 'Toggle navigation', 'myoos' ); ?>Toggle navigation</span>
								<span class="fa fa-bars"></span>
							</button>
							<!-- End Toggle -->

	
							<?php if ( get_header_image() ) : ?>
							
							<!-- Logo -->
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
								<img src="<?php header_image(); ?>" alt="<?php bloginfo( 'name' ); ?>"/>
							</a>
							<!-- End Logo -->

							<?php else: ?>
							
							<div class="site-branding">
								<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
								<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
							</div><!-- .site-branding -->
							
							<?php endif; // End header image check. ?>
						</div>
	
						<?php 
								wp_nav_menu( array(
								'menu'              => 'primary',
								'theme_location'    => 'primary',
								'depth'             => 2,
								'container'         => 'div',
								'container_class'   => 'collapse navbar-collapse',
								'container_id'      => 'navbar-responsive-collapse',
								'menu_class'        => 'nav navbar-nav',
								'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
								'walker'            => new wp_bootstrap_navwalker())
							);		
					?>
					</div>
				</div>
			</div>			
		</nav><!-- .site-navigation -->
	</header><!-- #masthead -->	
	
	<div id="content" class="site-content">
