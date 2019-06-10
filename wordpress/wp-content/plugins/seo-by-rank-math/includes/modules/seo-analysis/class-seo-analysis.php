<?php
/**
 * The SEO Analysis
 *
 * @since      0.9.0
 * @package    RankMath
 * @subpackage RankMath\modules
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\SEO_Analysis;

use RankMath\Helper;
use RankMath\Traits\Hooker;
use MyThemeShop\Helpers\Conditional;

defined( 'ABSPATH' ) || exit;

/**
 * SEO_Analysis class.
 */
class SEO_Analysis {

	use Hooker;

	/**
	 * The Constructor.
	 */
	public function __construct() {
		if ( Conditional::is_heartbeat() ) {
			return;
		}

		if ( is_admin() ) {
			$this->admin = new Admin;
		}

		$this->filter( 'rank_math/admin_bar/items', 'admin_bar_items', 11 );
	}

	/**
	 * Add admin bar item.
	 *
	 * @param array $items Array of menu items.
	 * @return array
	 */
	public function admin_bar_items( $items ) {
		$items['seo-analysis'] = [
			'id'        => 'rank-math-seo-analysis',
			'title'     => esc_html__( 'SEO Analysis', 'rank-math' ),
			'href'      => Helper::get_admin_url( 'seo-analysis' ),
			'parent'    => 'rank-math',
			'meta'      => [ 'title' => esc_html__( 'Site-wide analysis', 'rank-math' ) ],
			'_priority' => 50,
		];

		if ( ! is_admin() && ! is_404() ) {
			$link = is_front_page() ? '' : ( is_ssl() ? 'https' : 'http' ) . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

			$items['analyze'] = [
				'id'        => 'rank-math-analyze-me',
				'title'     => $link ? esc_html__( 'Analyze this Page', 'rank-math' ) : esc_html__( 'SEO Analysis', 'rank-math' ),
				'href'      => Helper::get_admin_url( 'seo-analysis' ) . ( $link ? '&u=' . urlencode( $link ) : '' ),
				'parent'    => 'rank-math-seo-analysis',
				'meta'      => [ 'title' => esc_html__( 'SEO Analysis for this page', 'rank-math' ) ],
				'_priority' => 52,
			];
		}

		return $items;
	}
}
