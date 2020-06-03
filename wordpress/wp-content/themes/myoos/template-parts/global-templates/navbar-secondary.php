<?php
if ( has_nav_menu( 'secondary-left' ) || has_nav_menu( 'secondary-right' ) || apply_filters( 'navbar-secondary-force-show', false ) ) {
	?>
	<nav id="navbar-secondary" <?php cpschool_class( 'navbar-secondary', 'navbar navbar-expand-md nav-styling-underline has-background has-header-secondary-bg-color-background-color' ); ?> aria-label="<?php esc_html_e( 'secondary', 'cpschool' ); ?>">
		<div <?php cpschool_class( 'navbar-secondary-container', 'navbar-container' ); ?>>
			<?php do_action( 'cpschool_navbar_secondary_container_start' ); ?>

			<div id="navbar-nav-secondary-left" class="navbar-nav-container">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'secondary-left',
						'container'      => false,
						'menu_class'     => 'nav navbar-nav justify-content-start',
						'fallback_cb'    => '',
						'menu_id'        => 'menu-secondary-left',
						'depth'          => 2,
						'walker'         => new CPSchool_WP_Bootstrap_Navwalker( true ),
					)
				);
				?>
			</div>

			<div id="navbar-nav-secondary-right" class="navbar-nav-container">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'secondary-right',
						'container'      => false,
						'menu_class'     => 'nav navbar-nav',
						'fallback_cb'    => '',
						'menu_id'        => 'menu-secondary-right',
						'depth'          => 2,
						'walker'         => new CPSchool_WP_Bootstrap_Navwalker( true ),
					)
				);
				?>
			</div>

			<?php do_action( 'cpschool_navbar_secondary_container_start' ); ?>
		</div>
	</nav>
	<?php
}
