<?php
/**
 * Calendar Plus Integrations with the theme.
 *
 * @package cpschool
 */

if ( ! function_exists( 'cpschool_cplus_post_meta' ) ) {
	add_filter( 'cpschool_post_meta_items', 'cpschool_cplus_post_meta', 10, 2 );
	function cpschool_cplus_post_meta( $post_meta, $post_id ) {
		if ( get_post_type( $post_id ) == 'calendar_event' ) {
			$post_meta = array( 'event-date', 'event-recurrence', 'event-time', 'event-categories' );
			if ( is_singular() && $post_id == get_queried_object_id() ) {
				$post_meta[] = 'event-add';
				$post_meta[] = 'event-ical';
			}
		}

		return $post_meta;
	}
}

if ( ! function_exists( 'cpschool_cplus_template_dir' ) ) {
	add_filter( 'calendarp_template_dir', 'cpschool_cplus_template_dir', 10, 2 );
	function cpschool_cplus_template_dir( $template_dir ) {
		return 'template-parts/calendar-plus/';
	}
}

if ( ! function_exists( 'cpschool_cplus_post_meta_list' ) ) {
	add_action( 'cpschool_start_of_post_meta_list', 'cpschool_cplus_post_meta_list', 10, 2 );
	function cpschool_cplus_post_meta_list( $post_id, $post_meta ) {
		if ( get_post_type( $post_id ) == 'calendar_event' ) {
			// Event date.
			if ( in_array( 'event-date', $post_meta, true ) && ! empty( calendarp_event_human_read_dates( 'date' ) ) ) {
				?>
				<li class="post-date list-inline-item">
					<span class="screen-reader-text"><?php _e( 'Event date', 'cpschool' ); ?></span>
					<span class="meta-icon" aria-hidden="true">
						<i class="cps-icon cps-icon-calendar"></i>
					</span>
					<span class="meta-text">
						<?php echo calendarp_event_human_read_dates( 'date' ); ?>
					</span>
				</li>
				<?php
			}

			// Event recurrance.
			if ( in_array( 'event-recurrence', $post_meta, true ) && ! empty( calendarp_event_human_read_dates( 'recurrence' ) ) ) {
				?>
				<li class="event-date list-inline-item">
					<span class="screen-reader-text"><?php _e( 'Event recurrence', 'cpschool' ); ?></span>
					<span class="meta-icon" aria-hidden="true">
						<i class="cps-icon cps-icon-calendar"></i>
					</span>
					<span class="meta-text">
						<?php echo calendarp_event_human_read_dates( 'recurrence' ); ?>
					</span>
				</li>
				<?php
			}

			// Event time.
			if ( in_array( 'event-time', $post_meta, true ) && ! empty( calendarp_event_human_read_dates( 'time' ) ) ) {
				?>
				<li class="event-time list-inline-item">
					<span class="screen-reader-text"><?php _e( 'Event time', 'cpschool' ); ?></span>
					<span class="meta-icon" aria-hidden="true">
						<i class="cps-icon cps-icon-clock"></i>
					</span>
					<span class="meta-text">
						<?php echo calendarp_event_human_read_dates( 'time' ); ?>
					</span>
				</li>
				<?php
			}

			// Event categories.
			if ( in_array( 'event-categories', $post_meta, true ) ) {
				$categories = get_the_term_list( get_the_ID(), 'calendar_event_category' );
				if ( $categories ) {
					?>
					<li class="event-categories list-inline-item">
						<span class="screen-reader-text"><?php _e( 'Event categories', 'cpschool' ); ?></span>
						<span class="meta-icon" aria-hidden="true">
							<i class="cps-icon cps-icon-category"></i>
						</span>
						<span class="meta-text">
							<?php echo $categories; ?>
						</span>
					</li>
					<?php
				}
			}

			// Event add.
			if ( in_array( 'event-add', $post_meta, true ) ) {
				?>

				</ul>
				<ul class="post-meta list-inline">

				<li class="post-add list-inline-item">
					<span class="meta-icon" aria-hidden="true">
						<i class="cps-icon cps-icon-bookmark"></i>
					</span>
					<span class="meta-text">
					<?php _e( 'Add To: ', 'cpschool' ); ?><?php calendarp_event_add_to_calendars_links(); ?>
					</span>
				</li>
				<?php
			}

			// Event ical.
			if ( in_array( 'event-ical', $post_meta, true ) ) {
				?>
				<li class="post-add list-inline-item">
					<span class="meta-icon" aria-hidden="true">
						<i class="cps-icon cps-icon-file-text-o"></i>
					</span>
					<span class="meta-text">
						<?php echo '<a href="' . esc_url( calendarp_get_ical_file_url( array( 'event' => get_the_ID() ) ) ) . '" title="' . esc_attr__( 'Download iCal file for this event', 'cpschool' ) . '"> ' . __( 'Download iCal file for this event', 'cpschool' ) . '</a>'; ?>
					</span>
				</li>
				<?php
			}
		}
	}
}

if ( ! function_exists( 'cpschool_cplus_singular_before_content' ) ) {
	add_action( 'cpschool_singular_before_content', 'cpschool_cplus_singular_before_content', 10 );
	function cpschool_cplus_singular_before_content() {
		if ( get_post_type() == 'calendar_event' && calendarp_event_has_location() ) {
			?>
			<div class="event-location mb-3">
				<p><i class="cps-icon cps-icon-location2"></i> <?php echo calendarp_the_event_location()->get_full_address(); ?></p>
				<div class="event-location-description"><?php echo calendarp_get_location_description(); ?></div>
				<?php echo calendarp_get_google_map_html( calendarp_the_event_location()->ID ); ?>
			</div>
			<?php
		}
	}
}
