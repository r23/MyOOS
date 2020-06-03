<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package cpschool
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php do_action( 'wp_body_open' ); ?>

<div <?php cpschool_class( 'site-page-wrapper', 'site' ); ?> id="page">
	<a class="skip-link sr-only sr-only-focusable" href="#content"><?php esc_html_e( 'Skip to content', 'cpschool' ); ?></a>

	<?php get_template_part( 'template-parts/global-templates/bar', 'alert' ); ?>

	<?php get_template_part( 'template-parts/global-templates/navbar', 'secondary' ); ?>

	<div id="wrapper-navbar-main-top" <?php cpschool_class( 'navbar-main-wrapper-top' ); ?>></div><?php // This is used to detect stickness of navigation ?>
	<div id="wrapper-navbar-main" <?php cpschool_class( 'navbar-main-wrapper', 'wrapper-navbar' ); ?> itemscope itemtype="http://schema.org/WebSite">
		<nav id="navbar-main" <?php cpschool_class( 'navbar-main', 'navbar navbar-expand-md nav-styling-underline has-background has-header-main-bg-color-background-color' ); ?> aria-label="<?php esc_html_e( 'main', 'cpschool' ); ?>">
			<div <?php cpschool_class( 'navbar-main-container', 'navbar-container' ); ?>>
				<?php do_action( 'cpschool_navbar_main_container_start' ); ?>

				<!-- Your site title as branding in the menu -->
				<?php if ( ! has_custom_logo() ) { ?>
					<?php if ( is_front_page() && is_home() ) : ?>
						<h1 class="navbar-brand-holder mb-0 h-style-disable">
					<?php else : ?>
						<div class="navbar-brand-holder">
					<?php endif; ?>
							<?php do_action( 'cpschool_navbar_main_logo_text_before' ); ?>

							<a <?php cpschool_class( 'navbar-brand', 'navbar-brand logo-font' ); ?>  rel="home" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" itemprop="url">
								<span class="navbar-brand-text"><?php bloginfo( 'name' ); ?></span>

								<?php if ( ( get_theme_mod( 'header_main_logo_position' ) == 'dropbox' && get_theme_mod( 'header_main_show_tagline' ) ) || is_customize_preview() ) { ?>
									<br/><span <?php cpschool_class( 'navbar-brand-subtext', 'navbar-brand-subtext navbar-brand-dropbox-subtext' ); ?>><?php bloginfo( 'description' ); ?></span>
								<?php } ?>
							</a>

							<?php if ( ( get_theme_mod( 'header_main_logo_position' ) != 'dropbox' && get_theme_mod( 'header_main_show_tagline' ) ) || is_customize_preview() ) { ?>
								<a rel="home" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" <?php cpschool_class( 'navbar-brand-subtext', 'navbar-brand-subtext' ); ?>>
									<?php bloginfo( 'description' ); ?>
								</a>
							<?php } ?>

							<?php do_action( 'cpschool_navbar_main_logo_text_after' ); ?>
					<?php if ( is_front_page() && is_home() ) : ?>
						</h1>
					<?php else : ?>
						</div>
					<?php endif; ?>
				<?php } else { ?>
					<div class="navbar-brand-holder">
						<?php the_custom_logo(); ?>
					</div>
				<?php } ?><!-- end custom logo -->

				<div class="navbar-navs-container">
					<?php
					wp_nav_menu(
						array(
							'theme_location'  => 'desktop',
							'container_class' => 'navbar-nav-container',
							'container_id'    => 'navbar-main-nav-desktop',
							'menu_class'      => 'nav navbar-nav',
							'fallback_cb'     => '',
							'menu_id'         => 'menu-main-desktop',
							'depth'           => 2,
							'walker'          => new CPSchool_WP_Bootstrap_Navwalker( true ),
						)
					);
					?>
					
					<div id="navbar-main-nav-buttons">
						<ul class="nav navbar-nav navbar-button-nav-right">
							<?php if ( get_theme_mod( 'header_main_enable_search' ) || is_customize_preview() ) { ?>
								<li id="navbar-main-btn-search" <?php cpschool_class( 'navbar-main-btn-search' ); ?>>
									<button type="button" class="btn btn-secondary nav-link has-styling" data-toggle="modal" data-target="#modal-search">
										<i aria-hidden="true" class="cps-icon cps-icon-search"></i>
										<span aria-hidden="true" class="d-none"><?php _e( 'Search' ); ?></span>
										<span class="sr-only"><?php _e( 'Toggle search interface', 'cpschool' ); ?></span>
									</button>
								</li>
							<?php } ?>

							<?php
							$btn_slidein_classes = array( 'btn-modal-slide-in-menu-holder' );
							if ( has_nav_menu( 'desktop-extended' ) ) {
								$btn_slidein_classes[] = 'has-desktop';
							}
							if ( has_nav_menu( 'desktop' ) || has_nav_menu( 'desktop-extended' ) || has_nav_menu( 'mobile' ) ) {
								$btn_slidein_classes[] = 'has-mobile';
							}
							?>
							<li id="navbar-main-btn-slide-in-menu" <?php cpschool_class( 'navbar-main-btn-slide-in', $btn_slidein_classes ); ?>>
								<button type="button" class="btn btn-secondary nav-link has-styling" data-toggle="modal" data-target="#modal-slide-in-menu">
									<i aria-hidden="true" class="cps-icon cps-icon-menu"></i>
									<span aria-hidden="true" class="d-none"><?php _e( 'Menu' ); ?></span>
									<span class="sr-only"><?php _e( 'Toggle extended navigation', 'cpschool' ); ?></span>
								</button>
							</li>
						</ul>
					</div>
				</div>

				<?php do_action( 'cpschool_navbar_main_container_end' ); ?>
				
			</div><!-- #navbar-container -->
		</nav>
	</div><!-- #wrapper-navbar end -->

	<?php
	$hero_style = get_theme_mod( 'hero_main_style' );
	get_template_part( 'template-parts/global-templates/hero-pagetitle', $hero_style );
