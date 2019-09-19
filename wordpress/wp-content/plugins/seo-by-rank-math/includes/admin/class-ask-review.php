<?php
/**
 * Ask user to review Rank Math on wp.org, in the meta box after 2 weeks.
 *
 * @since      0.9.0
 * @package    RankMath
 * @subpackage RankMath\Admin
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\Admin;

use RankMath\Traits\Ajax;
use RankMath\Traits\Hooker;
use MyThemeShop\Helpers\Arr;

defined( 'ABSPATH' ) || exit;

/**
 * Ask_Review class.
 */
class Ask_Review {

	use Hooker, Ajax;

	/**
	 * Register hooks.
	 */
	public function hooks() {
		$this->ajax( 'already_reviewed', 'already_reviewed' );
		$this->filter( 'rank_math/metabox/tabs', 'add_metabox_tab' );
	}

	/**
	 * Add rich snippet tab to the metabox.
	 *
	 * @param array $tabs Array of tabs.
	 *
	 * @return array
	 */
	public function add_metabox_tab( $tabs ) {
		Arr::insert(
			$tabs,
			[
				'askreview' => [
					'icon'       => 'dashicons dashicons-heart',
					'title'      => '',
					'desc'       => '',
					'file'       => rank_math()->includes_dir() . 'metaboxes/ask-review.php',
					'capability' => 'onpage_general',
				],
			],
			11
		);

		return $tabs;
	}

	/**
	 * Set "already reviewed" flag.
	 */
	public function already_reviewed() {
		update_option( 'rank_math_already_reviewed', current_time( 'timestamp' ) );
		$this->success( 'success' );
	}

	/**
	 * Display tab content.
	 */
	public static function display() {
		ob_start();
		?>
		<div class="ask-review">

			<h3><?php _e( 'Rate Rank Math SEO', 'rank-math' ); ?></h3>

			<p>
				<?php _e( 'Hey, we noticed you are using Rank Math SEO plugin for more than 2 weeks – <em>that\'s awesome!</em> <br>Could you please do us a BIG favor and give it a rating on WordPress to help us spread the word and boost our motivation?', 'rank-math' ); ?>
			</p>

			<div class="stars-wrapper">

				<div class="face">
					<div class="smiley normal">
						<div class="eyes">
							<div class="eye"></div>
							<div class="eye"></div>
						</div>
						<div class="mouth"></div>
					</div>
				</div>

				<div class="stars">
					<?php for ( $i = 1; $i <= 5; $i++ ) { ?>
						<a href="https://s.rankmath.com/reviewrankmath" target="_blank">
							<span class="dashicons dashicons-star-filled"></span>
						</a>
					<?php } ?>
				</div>

			</div>

			<label>

				<input type="checkbox" id="already-reviewed" />

				<span>
					<?php _e( 'I already did. Please don\'t show this message again.', 'rank-math' ); ?>
				</span>

			</label>

		</div>
		<?php
		self::print_script();

		return ob_get_clean();
	}

	/**
	 * Print javascript
	 */
	public static function print_script() {
		?>
		<script>
			(function( $ ) {
				$( function() {
					var rating_wrapper  = $( '#setting-panel-askreview' ),
						rating_stars    = rating_wrapper.find( '.stars a' ),
						rating_smiley   = rating_wrapper.find( '.smiley' ),
						rating_contents = rating_wrapper.find( '.ask-review' );

					rating_stars.on( 'mouseenter', function() {
						var pos = $( this ).index();

						rating_stars.removeClass( 'highlighted' );
						rating_stars.slice( 0, pos + 1 ).addClass( 'highlighted' );

						if ( pos < 2 ) {
							rating_smiley.removeClass( 'normal happy' ).addClass( 'angry' );
						} else if ( pos > 3 ) {
							rating_smiley.removeClass( 'normal angry' ).addClass( 'happy' );
						} else {
							rating_smiley.removeClass( 'happy angry' ).addClass( 'normal' );
						}
					});

					$( '#already-reviewed' ).change(function() {
						$.ajax({
							url: ajaxurl,
							data: { action: 'rank_math_already_reviewed' },
						});
						rating_contents.animate({
							opacity: 0.01
						}, 1500, function() {
							$( '.rank-math-tabs-navigation > a' ).first().click();
							$( '.rank-math-tabs-navigation' ).children( '[href = "#setting-panel-askreview"]' ).remove();
						});
					});
				});
			})(jQuery);
		</script>
		<?php
	}
}
