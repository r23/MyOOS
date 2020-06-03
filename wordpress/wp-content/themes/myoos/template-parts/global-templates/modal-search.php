<div id="modal-search" class="modal fade modal-slide-in-top modal-close-inline modal-site-width modal-padding-lg" tabindex="-1" role="dialog" aria-label="<?php echo esc_attr( 'slide-in menu', 'cpschool' ); ?>" aria-hidden="true">
	<div class="modal-dialog site-width-max" role="document">
		<div class="modal-content has-background has-header-main-bg-color-background-color">
			<div class="modal-header pb-0">
				<button type="button" class="close" data-dismiss="modal" aria-label="<?php esc_attr( 'Close search', 'cpschool' ); ?>">
					<i aria-hidden="true" class="cps-icon cps-icon-close"></i>
				</button>
			</div>
			<div class="modal-body">
				<form <?php cpschool_class( 'navbar-main-btn-search', 'search-form d-flex' ); ?> method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<label class="sr-only" for="search-form-header">Search</label>
					<input id="search-form-header" class="form-control form-control-lg" type="search" placeholder="Search..." value="<?php echo get_search_query(); ?>" name="s">
					<button aria-expanded="false" class="btn btn-secondary btn-lg" type="submit" aria-controls="search-form-header" aria-label="<?php esc_attr( 'Search Site', 'cpschool' ); ?>">
						<i aria-hidden="true" class="cps-icon cps-icon-search"></i>
						<span aria-hidden="true" class="d-none"><?php _e( 'Search' ); ?></span>
					</button>
				</form>
			</div>
		</div>
	</div>
</div><!-- #modal-search -->
