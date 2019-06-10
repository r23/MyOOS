<?php
/**
 * The Rich Snippet Shortcode
 *
 * @since      1.0.24
 * @package    RankMath
 * @subpackage RankMath\RichSnippet
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\RichSnippet;

use RankMath\Helper;
use RankMath\Traits\Hooker;
use RankMath\Traits\Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Snippet_Shortcode class.
 */
class Snippet_Shortcode {

	use Hooker, Shortcode;

	/**
	 * The Constructor.
	 */
	public function __construct() {
		$this->add_shortcode( 'rank_math_rich_snippet', 'rich_snippet' );
		$this->add_shortcode( 'rank_math_review_snippet', 'rich_snippet' );

		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		register_block_type(
			'rank-math/rich-snippet',
			[
				'render_callback' => [ $this, 'rich_snippet' ],
				'attributes'      => [
					'id' => [
						'default' => '',
						'type'    => 'integer',
					],
				],
			]
		);
	}

	/**
	 * Rich Snippet shortcode.
	 *
	 * @param  array $atts Optional. Shortcode arguments - currently only 'show'
	 *                     parameter, which is a comma-separated list of elements to show.
	 *
	 * @return string Shortcode output.
	 */
	public function rich_snippet( $atts ) {
		$atts = shortcode_atts(
			[ 'id' => get_the_ID() ],
			$atts,
			'rank_math_rich_snippet'
		);

		$post = get_post( $atts['id'] );
		if ( empty( $post ) ) {
			return esc_html__( 'Post ID does not exists or was deleted.', 'rank-math' );
		}

		return $this->do_filter( 'snippet/html', $this->get_snippet_content( $post ) );
	}

	/**
	 * Get Snippet content.
	 *
	 * @param WP_Post $post Post Object.
	 *
	 * @return string Shortcode output.
	 */
	public function get_snippet_content( $post ) {
		$schema = Helper::get_post_meta( 'rich_snippet', $post->ID );
		if ( ! $this->get_fields( $schema ) ) {
			return __( 'Snippet not selected.', 'rank-math' );
		}

		wp_enqueue_style( 'rank-math-review-snippet', rank_math()->assets() . 'css/rank-math-snippet.css', null, rank_math()->version );

		// Title.
		$title = Helper::get_post_meta( 'snippet_name', $post->ID );
		$title = $title ? $title : Helper::replace_vars( '%title%', $post );

		// Description.
		$excerpt = Helper::replace_vars( '%excerpt%', $post );
		$desc    = Helper::get_post_meta( 'snippet_desc', $post->ID );
		$desc    = $desc ? $desc : ( $excerpt ? $excerpt : Helper::get_post_meta( 'description', $post->ID ) );

		// Image.
		$image = Helper::get_thumbnail_with_fallback( $post->ID, 'medium' );

		ob_start();
		?>
			<div id="rank-math-rich-snippet-wrapper">

				<h5 class="rank-math-title"><?php echo $title; ?></h5>

				<?php if ( ! empty( $image ) ) { ?>
					<div class="rank-math-review-image">
						<img src="<?php echo esc_url( $image[0] ); ?>" />
					</div>
				<?php } ?>

				<div class="rank-math-review-data">
					<p><?php echo $desc; ?></p>
					<?php
					foreach ( $this->get_fields( $schema ) as $id => $field ) {
						$this->get_field_content( $id, $field, $post );
					}
					?>
				</div>

			</div>
		<?php

		return ob_get_clean();
	}

	/**
	 * Get Field Content.
	 *
	 * @param string  $id    Field ID.
	 * @param string  $field Field Name.
	 * @param WP_Post $post  Post Object.
	 */
	public function get_field_content( $id, $field, $post ) {
		if ( 'is_rating' === $id ) {
			$this->show_ratings( $post->ID, $field );
			return;
		}

		if ( ! $value = Helper::get_post_meta( "snippet_{$id}", $post->ID ) ) { // phpcs:ignore
			return;
		}
		?>
		<p>
			<strong><?php echo $field; ?>: </strong>
			<?php
			if ( in_array( $id, [ 'recipe_instructions', 'book_editions' ], true ) ) {
				$perform = "get_{$id}";
				$this->$perform( $value );
				return;
			}

			echo is_array( $value ) ? implode( ', ', $value ) : esc_html( $value );
			?>
		</p>
		<?php
	}

	/**
	 * Get Recipe Instructions.
	 *
	 * @param array $value Recipe instructions.
	 */
	public function get_recipe_instructions( $value ) {
		foreach ( $value as $key => $data ) {
			echo '<p><strong>' . $data['name'] . ': </strong>' . $data['text'] . '</p>';
		}
	}

	/**
	 * Get Book Editions.
	 *
	 * @param array $value Book editions.
	 */
	public function get_book_editions( $value ) {
		$hash = [
			'book_edition'   => __( 'Edition', 'rank-math' ),
			'name'           => __( 'Name', 'rank-math' ),
			'author'         => __( 'Author', 'rank-math' ),
			'isbn'           => __( 'ISBN', 'rank-math' ),
			'date_published' => __( 'Date Published', 'rank-math' ),
			'book_format'    => __( 'Format', 'rank-math' ),
		];
		foreach ( $value as $data ) {
			echo '<p>';
			foreach ( $hash as $id => $field ) {
				echo isset( $data[ $id ] ) ? "<strong>{$field} : </strong> {$data[ $id ]} <br />" : '';
			}
			echo '</p>';
		}

	}

	/**
	 * Display nicely formatted reviews.
	 *
	 * @param int   $post_id The Post ID.
	 * @param array $field   Array of review value and count field.
	 */
	public function show_ratings( $post_id, $field ) {
		wp_enqueue_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', null, rank_math()->version );
		$rating = isset( $field['value'] ) ? Helper::get_post_meta( "snippet_{$field['value']}", $post_id ) : '';
		$count  = isset( $field['count'] ) ? Helper::get_post_meta( $field['count'], $post_id ) : '';
		?>
		<div class="rank-math-total-wrapper">

			<strong><?php echo $this->do_filter( 'review/text', esc_html__( 'Editor\'s Rating:', 'rank-math' ) ); ?></strong><br />

			<span class="rank-math-total"><?php echo $rating; ?></span>

			<div class="rank-math-review-star">

				<div class="rank-math-review-result-wrapper">

					<?php echo \str_repeat( '<i class="fa fa-star"></i>', 5 ); ?>

					<div class="rank-math-review-result" style="width:<?php echo ( $rating * 20 ); ?>%;">
						<?php echo \str_repeat( '<i class="fa fa-star"></i>', 5 ); ?>
					</div>

				</div>

			</div>

		</div>
		<?php
	}

	/**
	 * Contact info shortcode, displays nicely formatted contact informations.
	 *
	 * @param string $type Snippet type.
	 *
	 * @return array Array of snippet fields.
	 */
	public function get_fields( $type ) {
		$fields = [
			'course'     => [
				'course_provider_type' => esc_html__( 'Course Provider', 'rank-math' ),
				'course_provider'      => esc_html__( 'Course Provider Name', 'rank-math' ),
				'course_provider_url'  => esc_html__( 'Course Provider URL', 'rank-math' ),
			],
			'event'      => [
				'url'                            => esc_html__( 'URL', 'rank-math' ),
				'event_type'                     => esc_html__( 'Event Type', 'rank-math' ),
				'event_venue'                    => esc_html__( 'Venue Name', 'rank-math' ),
				'event_venue_url'                => esc_html__( 'Venue URL', 'rank-math' ),
				'event_address'                  => esc_html__( 'Address', 'rank-math' ),
				'event_performer_type'           => esc_html__( 'Performer', 'rank-math' ),
				'event_performer'                => esc_html__( 'Performer Name', 'rank-math' ),
				'event_performer_url'            => esc_html__( 'Performer URL', 'rank-math' ),
				'event_status'                   => esc_html__( 'Event Status', 'rank-math' ),
				'event_startdate_date'           => esc_html__( 'Start Date', 'rank-math' ),
				'event__enddate'                 => esc_html__( 'End Date', 'rank-math' ),
				'event_ticketurl'                => esc_html__( 'Ticket URL', 'rank-math' ),
				'event_price'                    => esc_html__( 'Entry Price', 'rank-math' ),
				'event_currency'                 => esc_html__( 'Currency', 'rank-math' ),
				'event_availability'             => esc_html__( 'Availability', 'rank-math' ),
				'event_availability_starts_date' => esc_html__( 'Availability Starts', 'rank-math' ),
				'event_inventory'                => esc_html__( 'Stock Inventory', 'rank-math' ),
			],
			'jobposting' => [
				'jobposting_salary'          => esc_html__( 'Salary', 'rank-math' ),
				'jobposting_currency'        => esc_html__( 'Salary Currency', 'rank-math' ),
				'jobposting_payroll'         => esc_html__( 'Payroll', 'rank-math' ),
				'jobposting_startdate'       => esc_html__( 'Date Posted', 'rank-math' ),
				'jobposting_expirydate'      => esc_html__( 'Expiry Posted', 'rank-math' ),
				'jobposting_unpublish'       => esc_html__( 'Unpublish when expired', 'rank-math' ),
				'jobposting_employment_type' => esc_html__( 'Employment Type ', 'rank-math' ),
				'jobposting_organization'    => esc_html__( 'Hiring Organization ', 'rank-math' ),
				'jobposting_id'              => esc_html__( 'Posting ID', 'rank-math' ),
				'jobposting_url'             => esc_html__( 'Organization URL', 'rank-math' ),
				'jobposting_logo'            => esc_html__( 'Organization Logo', 'rank-math' ),
				'jobposting_address'         => esc_html__( 'Location', 'rank-math' ),
			],
			'music'      => [
				'url'        => esc_html__( 'URL', 'rank-math' ),
				'music_type' => esc_html__( 'Type', 'rank-math' ),
			],
			'product'    => [
				'product_sku'         => esc_html__( 'Product SKU', 'rank-math' ),
				'product_brand'       => esc_html__( 'Product Brand', 'rank-math' ),
				'product_currency'    => esc_html__( 'Product Currency', 'rank-math' ),
				'product_price'       => esc_html__( 'Product Price', 'rank-math' ),
				'product_price_valid' => esc_html__( 'Price Valid Until', 'rank-math' ),
				'product_instock'     => esc_html__( 'Product In-Stock', 'rank-math' ),
			],
			'recipe'     => [
				'recipe_type'                => esc_html__( 'Type', 'rank-math' ),
				'recipe_cuisine'             => esc_html__( 'Cuisine', 'rank-math' ),
				'recipe_keywords'            => esc_html__( 'Keywords', 'rank-math' ),
				'recipe_yield'               => esc_html__( 'Recipe Yield', 'rank-math' ),
				'recipe_calories'            => esc_html__( 'Calories', 'rank-math' ),
				'recipe_preptime'            => esc_html__( 'Preparation Time', 'rank-math' ),
				'recipe_cooktime'            => esc_html__( 'Cooking Time', 'rank-math' ),
				'recipe_totaltime'           => esc_html__( 'Total Time', 'rank-math' ),
				'recipe_rating'              => esc_html__( 'Rating', 'rank-math' ),
				'recipe_rating_min'          => esc_html__( 'Rating Minimum', 'rank-math' ),
				'recipe_rating_max'          => esc_html__( 'Rating Maximum', 'rank-math' ),
				'recipe_video'               => esc_html__( 'Recipe Video', 'rank-math' ),
				'recipe_video_thumbnail'     => esc_html__( 'Recipe Video Thumbnail', 'rank-math' ),
				'recipe_video_name'          => esc_html__( 'Recipe Video Name', 'rank-math' ),
				'recipe_video_date'          => esc_html__( 'Video Upload Date', 'rank-math' ),
				'recipe_video_description'   => esc_html__( 'Recipe Video Description', 'rank-math' ),
				'recipe_ingredients'         => esc_html__( 'Recipe Ingredients', 'rank-math' ),
				'recipe_instruction_name'    => esc_html__( 'Recipe Instruction Name', 'rank-math' ),
				'recipe_single_instructions' => esc_html__( 'Recipe Instructions', 'rank-math' ),
				'recipe_instructions'        => esc_html__( 'Recipe Instructions', 'rank-math' ),
			],
			'restaurant' => [
				'local_address'             => esc_html__( 'Address', 'rank-math' ),
				'local_geo'                 => esc_html__( 'Geo Coordinates', 'rank-math' ),
				'local_phone'               => esc_html__( 'Phone Number', 'rank-math' ),
				'local_price_range'         => esc_html__( 'Price Range', 'rank-math' ),
				'local_opens'               => esc_html__( 'Opening Time', 'rank-math' ),
				'local_closes'              => esc_html__( 'Closing Time', 'rank-math' ),
				'local_opendays'            => esc_html__( 'Open Days', 'rank-math' ),
				'restaurant_serves_cuisine' => esc_html__( 'Serves Cuisine', 'rank-math' ),
				'restaurant_menu'           => esc_html__( 'Menu URL', 'rank-math' ),
			],
			'video'      => [
				'video_url'       => esc_html__( 'Content URL', 'rank-math' ),
				'video_embed_url' => esc_html__( 'Embed URL', 'rank-math' ),
				'video_duration'  => esc_html__( 'Duration', 'rank-math' ),
				'video_views'     => esc_html__( 'Views', 'rank-math' ),
			],
			'person'     => [
				'person_email'     => esc_html__( 'Email', 'rank-math' ),
				'person_address'   => esc_html__( 'Address', 'rank-math' ),
				'person_gender'    => esc_html__( 'Gender', 'rank-math' ),
				'person_job_title' => esc_html__( 'Job Title', 'rank-math' ),
			],
			'review'     => [
				'is_rating' => [
					'value' => 'review_rating_value',
				],
			],
			'service'    => [
				'service_type'           => esc_html__( 'Service Type', 'rank-math' ),
				'service_price'          => esc_html__( 'Price', 'rank-math' ),
				'service_price_currency' => esc_html__( 'Currency', 'rank-math' ),
				'is_rating'              => [
					'value' => 'service_rating_value',
					'count' => 'service_rating_count',
				],
			],
			'software'   => [
				'software_price'                => esc_html__( 'Price', 'rank-math' ),
				'software_price_currency'       => esc_html__( 'Price Currency', 'rank-math' ),
				'software_operating_system'     => esc_html__( 'Operating System', 'rank-math' ),
				'software_application_category' => esc_html__( 'Application Category', 'rank-math' ),
				'is_rating'                     => [
					'value' => 'software_rating_value',
					'count' => 'software_rating_count',
				],
			],
			'book'       => [
				'url'           => esc_html__( 'URL', 'rank-math' ),
				'author'        => esc_html__( 'Author', 'rank-math' ),
				'book_editions' => esc_html__( 'Book Editions', 'rank-math' ),
			],
		];

		return isset( $fields[ $type ] ) ? apply_filters( 'rank_math/snippet/fields', $fields[ $type ] ) : false;
	}
}
