<div class="calendarp-event-shortcode">
	<div class="calendarp-event-shortcode-header">
		<?php echo calendarp_get_the_event_thumbnail( $event->ID, 'event_thumbnail' ); ?>
		<h3 class="entry-title">
			<a href="<?php echo esc_attr( get_permalink( $event->ID ) ); ?>"
				title="<?php esc_attr( sprintf( __( 'Permalink to %s', 'calendar-plus' ), get_permalink() ) ); ?>">
				<?php echo get_the_title( $event->ID ); ?>
			</a>
		</h3>

		<div class="page-meta entry-meta">
			<?php echo cpschool_get_post_meta( $event->ID, is_singular() ); ?>
		</div><!-- .entry-meta -->

	</div>
	<div class="calendarp-event-shortcode-content">
		<?php do_action( 'calendarp_content_event_content', $event ); ?>
		<div>
			<a class="btn btn-secondary cpschool-read-more-link" href="<?php echo esc_attr( get_permalink( $event->ID ) ); ?>">
				<?php _e( 'Open Event', 'cpschool' ); ?>
			</a>
		</div>
	</div>
</div>
