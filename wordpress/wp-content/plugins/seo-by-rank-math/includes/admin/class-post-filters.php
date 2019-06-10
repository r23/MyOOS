<?php
/**
 * The admin post filters functionality.
 *
 * @since      0.9.0
 * @package    RankMath
 * @subpackage RankMath\Admin
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\Admin;

use RankMath\Helper;
use RankMath\Runner;
use RankMath\Traits\Hooker;
use MyThemeShop\Helpers\Param;

defined( 'ABSPATH' ) || exit;

/**
 * Post_Filters class.
 */
class Post_Filters implements Runner {

	use Hooker;

	/**
	 * Register hooks.
	 */
	public function hooks() {
		$this->action( 'admin_init', 'init' );
	}

	/**
	 * Intialize.
	 */
	public function init() {
		if ( ! Helper::has_cap( 'general' ) ) {
			return;
		}

		$this->filter( 'pre_get_posts', 'posts_by_seo_filters' );
		$this->filter( 'parse_query', 'filter_by_focus_keywords' );
		$this->filter( 'restrict_manage_posts', 'add_seo_filter', 11 );

		foreach ( Helper::get_allowed_post_types() as $post_type ) {
			$this->filter( "views_edit-$post_type", 'add_pillar_content_filter_link' );
		}
	}

	/**
	 * Filter post in admin by Rank Math's Filter value.
	 *
	 * @param \WP_Query $query The wp_query instance.
	 */
	public function posts_by_seo_filters( $query ) {
		if ( ! $this->can_seo_filters() ) {
			return;
		}

		if ( 'rank_math_seo_score' === $query->get( 'orderby' ) ) {
			$query->set( 'orderby', 'meta_value' );
			$query->set( 'meta_key', 'rank_math_seo_score' );
			$query->set( 'meta_type', 'numeric' );
		}

		if ( empty( $_GET['pillar_content'] ) && empty( $_GET['seo-filter'] ) ) {
			return;
		}

		$meta_query = [];

		// Check for pillar content filter.
		if ( ! empty( $_GET['pillar_content'] ) ) {
			$meta_query[] = [
				'key'   => 'rank_math_pillar_content',
				'value' => 'on',
			];
		}

		$this->set_seo_filters( $meta_query );
		$query->set( 'meta_query', $meta_query );
	}

	/**
	 * Filter post in admin by pillar content.
	 *
	 * @param \WP_Query $query The wp_query instance.
	 */
	public function filter_by_focus_keywords( $query ) {
		if ( ! $this->can_fk_filter() ) {
			return;
		}

		$query->set( 'post_status', 'publish' );
		if ( $ids = $this->fk_in_title() ) { // phpcs:ignore
			$query->set( 'post__in', $ids );
			return;
		}

		$focus_keyword = Param::get( 'focus_keyword', '' );
		if ( 1 === absint( $focus_keyword ) ) {
			$query->set(
				'meta_query',
				[
					'relation' => 'AND',
					[
						'key'     => 'rank_math_focus_keyword',
						'compare' => 'NOT EXISTS',
					],
					[
						'relation' => 'OR',
						[
							'key'     => 'rank_math_robots',
							'value'   => 'noindex',
							'compare' => 'NOT LIKE',
						],
						[
							'key'     => 'rank_math_robots',
							'compare' => 'NOT EXISTS',
						],
					],
				]
			);
			return;
		}

		$query->set( 'post_type', 'any' );
		$query->set(
			'meta_query',
			[
				[
					'relation' => 'OR',
					[
						'key'     => 'rank_math_focus_keyword',
						'value'   => $focus_keyword,
						'compare' => 'LIKE',
					],
					[
						'key'     => 'rank_math_focus_keyword',
						'value'   => $focus_keyword . ',',
						'compare' => 'LIKE',
					],
				],
			]
		);
	}

	/**
	 * Add columns for SEO title, description and focus keywords.
	 */
	public function add_seo_filter() {
		global $post_type;

		if ( 'attachment' === $post_type || ! in_array( $post_type, Helper::get_allowed_post_types(), true ) ) {
			return;
		}

		$options  = [
			''          => esc_html__( 'All Posts', 'rank-math' ),
			'great-seo' => esc_html__( 'SEO Score: Great', 'rank-math' ),
			'good-seo'  => esc_html__( 'SEO Score: Good', 'rank-math' ),
			'bad-seo'   => esc_html__( 'SEO Score: Bad', 'rank-math' ),
			'empty-fk'  => esc_html__( 'Focus Keyword Not Set', 'rank-math' ),
			'noindexed' => esc_html__( 'Articles noindexed', 'rank-math' ),
		];
		$selected = Param::get( 'seo-filter' );
		?>
		<select name="seo-filter">
			<?php foreach ( $options as $val => $option ) : ?>
				<option value="<?php echo $val; ?>" <?php selected( $selected, $val, true ); ?>><?php echo $option; ?></option>
			<?php endforeach; ?>
		</select>
		<?php
	}

	/**
	 * Add view to filter list for pillar content.
	 *
	 * @param array $views An array of available list table views.
	 */
	public function add_pillar_content_filter_link( $views ) {
		global $typenow;

		$current = empty( $_GET['pillar_content'] ) ? '' : ' class="current" aria-current="page"';
		$pillars = get_posts([
			'post_type'      => $typenow,
			'fields'         => 'ids',
			'posts_per_page' => -1,
			'meta_key'       => 'rank_math_pillar_content',
			'meta_value'     => 'on',
		]);

		$views['pillar_content'] = sprintf(
			'<a href="%1$s"%2$s>%3$s <span class="count">(%4$s)</span></a>',
			add_query_arg([
				'post_type'      => $typenow,
				'pillar_content' => 1,
			]),
			$current,
			esc_html__( 'Pillar Content', 'rank-math' ),
			number_format_i18n( count( $pillars ) )
		);

		return $views;
	}

	/**
	 * Can apply seo filters.
	 *
	 * @return bool
	 */
	private function can_seo_filters() {
		$screen = get_current_screen();
		if (
			is_null( $screen ) ||
			'edit' !== $screen->base ||
			! in_array( $screen->post_type, Helper::get_allowed_post_types(), true )
		) {
			return false;
		}

		return true;
	}

	/**
	 * Set SEO filters meta query.
	 *
	 * @param array $query Meta query.
	 */
	private function set_seo_filters( &$query ) {
		$filter = Param::get( 'seo-filter' );
		if ( false === $filter ) {
			return;
		}

		$hash = [
			'empty-fk'  => [
				'key'     => 'rank_math_focus_keyword',
				'compare' => 'NOT EXISTS',
			],
			'bad-seo'   => [
				'key'     => 'rank_math_seo_score',
				'value'   => 50,
				'compare' => '<=',
				'type'    => 'numeric',
			],
			'good-seo'  => [
				'key'     => 'rank_math_seo_score',
				'value'   => [ 51, 80 ],
				'compare' => 'BETWEEN',
			],
			'great-seo' => [
				'key'     => 'rank_math_seo_score',
				'value'   => 80,
				'compare' => '>',
			],
			'noindexed' => [
				'key'     => 'rank_math_robots',
				'value'   => 'noindex',
				'compare' => 'LIKE',
			],
		];

		if ( isset( $hash[ $filter ] ) ) {
			$query[] = $hash[ $filter ];
		}
	}

	/**
	 * Can apply focus keyword filter.
	 *
	 * @return bool
	 */
	private function can_fk_filter() {
		$screen = get_current_screen();
		if ( is_null( $screen ) || 'edit' !== $screen->base || ( ! isset( $_GET['focus_keyword'] ) && ! isset( $_GET['fk_in_title'] ) ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Check if focus keyword is in title.
	 *
	 * @return bool|array
	 */
	private function fk_in_title() {
		global $wpdb;

		$fk_in_title = Param::get( 'fk_in_title' );
		if ( ! $fk_in_title ) {
			return false;
		}

		$meta_query = new \WP_Meta_Query([
			[
				'key'     => 'rank_math_focus_keyword',
				'compare' => 'EXISTS',
			],
			[
				'relation' => 'OR',
				[
					'key'     => 'rank_math_robots',
					'value'   => 'noindex',
					'compare' => 'NOT LIKE',
				],
				[
					'key'     => 'rank_math_robots',
					'compare' => 'NOT EXISTS',
				],
			],
		]);

		$mq_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
		return $wpdb->get_col( "SELECT {$wpdb->posts}.ID FROM $wpdb->posts {$mq_sql['join']} WHERE 1=1 {$mq_sql['where']} AND {$wpdb->posts}.post_type = '$screen->post_type' AND ({$wpdb->posts}.post_status = 'publish') AND {$wpdb->posts}.post_title NOT REGEXP REPLACE({$wpdb->postmeta}.meta_value, ',', '|')" ); // phpcs:ignore
	}
}
