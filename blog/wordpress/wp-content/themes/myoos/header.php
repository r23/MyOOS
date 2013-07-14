<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title><?php hybrid_document_title(); ?></title>
		<meta name="viewport" content="width=device-width">
		
		<link rel="profile" href="http://gmpg.org/xfn/11" />
		
	<?php if(of_get_option('favicon') != ''){ ?>
	<link rel="icon" href="<?php echo of_get_option('favicon', "" ); ?>" type="image/x-icon" />
	<?php } else { ?>
	<link rel="icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" type="image/x-icon" />
	<?php } ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	
		<?php $sTemplateDirectory = get_template_directory_uri(); ?>
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo $sTemplateDirectory; ?>/bootstrap/css/bootstrap.css" />
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo $sTemplateDirectory; ?>/bootstrap/css/responsive.css" />	
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo $sTemplateDirectory; ?>/css/prettyPhoto.css" />
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo $sTemplateDirectory; ?>/css/camera.css" />
		<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />


    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->
		
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<?php wp_head(); // wp_head ?>

	</head>
	<body class="<?php hybrid_body_class(); ?>">
		<?php 	
		/* The HTML5 Shim is required for older browsers, mainly older versions IE */ ?>
		<!--[if lt IE 8]>
		<div style=' clear: both; text-align:center; position: relative;'>
			<a href="http://www.microsoft.com/windows/internet-explorer/default.aspx?ocid=ie6_countdown_bannercode"><img src="http://storage.ie6countdown.com/assets/100/images/banners/warning_bar_0000_us.jpg" border="0" alt="" /></a>
		</div>
		<![endif]-->
		
		<div id="container">

			<?php get_template_part( 'menu', 'primary' ); // Loads the menu-primary.php template. ?>

			<header id="header">

				<hgroup id="branding">
					<h1 id="site-title"><a href="<?php echo home_url(); ?>" title="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
					<h2 id="site-description"><?php bloginfo( 'description' ); ?></h2>
				</hgroup><!-- #branding -->

			</header><!-- #header -->

			<?php if ( get_header_image() ) echo '<img class="header-image" src="' . esc_url( get_header_image() ) . '" alt="" />'; ?>

			<?php get_template_part( 'menu', 'secondary' ); // Loads the menu-secondary.php template. ?>

			<div id="main">

			<?php if ( current_theme_supports( 'breadcrumb-trail' ) ) breadcrumb_trail( array( 'container' => 'nav', 'separator' => '>', 'before' => __( 'You are here:', 'hybrid-base' ) ) ); ?>