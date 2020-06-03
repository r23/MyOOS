<div id="modal-slide-in-menu" class="modal fade modal-slide-in-right modal-full-height nav-styling-underline" tabindex="-1" role="dialog" aria-label="<?php echo esc_attr( 'slide-in menu', 'cpschool' ); ?>" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content has-background has-header-main-bg-color-background-color">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="<?php esc_attr( 'Close Menu', 'cpschool' ); ?>">
					<i aria-hidden="true" class="cps-icon cps-icon-close"></i>
				</button>
			</div>
			<div class="modal-body">
				<?php
				$mobile_menu_location = false;
				if ( has_nav_menu( 'mobile' ) ) {
					$mobile_menu_location = 'mobile';
				} elseif ( has_nav_menu( 'desktop' ) ) {
					$mobile_menu_location = 'desktop';
				} elseif ( has_nav_menu( 'desktop-extended' ) ) {
					$mobile_menu_location = 'desktop-extended';
				}

				if ( has_nav_menu( 'desktop-extended' ) ) {
					$expanded_nav_classes = '';

					if ( 'desktop-expanded' === $mobile_menu_location ) {
						$expanded_nav_classes .= ' menu-mobile';
					}
					?>

					<nav class="<?php echo esc_attr( $expanded_nav_classes ); ?>" aria-label="<?php esc_attr_e( 'Expanded', 'cpschool' ); ?>" role="navigation">
						<?php
						if ( has_nav_menu( 'desktop-extended' ) ) {
							wp_nav_menu(
								array(
									'menu_id'        => 'menu-main-desktop-extended',
									'menu_class'     => 'nav flex-column',
									'theme_location' => 'desktop-extended',
									'walker'         => new CPSchool_WP_Bootstrap_Navwalker( false ),
								)
							);
						}
						?>
					</nav>
					<?php
				}

				if ( 'desktop-expanded' !== $mobile_menu_location ) {
					?>
					<nav class="menu-mobile" aria-label="<?php esc_attr_e( 'Mobile', 'cpschool' ); ?>" role="navigation">
						<?php
						if ( $mobile_menu_location ) {
							wp_nav_menu(
								array(
									'menu_id'        => 'menu-main-mobile',
									'menu_class'     => 'nav flex-column',
									'theme_location' => $mobile_menu_location,
									'walker'         => new CPSchool_WP_Bootstrap_Navwalker( false ),
								)
							);
						}
						?>

					</nav>
					<?php
				}
				?>
			</div>
		</div>
	</div>
</div><!-- #modal-slide-in-menu -->
