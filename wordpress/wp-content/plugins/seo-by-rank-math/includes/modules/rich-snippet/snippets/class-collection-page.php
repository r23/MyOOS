<?php
/**
 * The CollectionPage Class
 *
 * @since      1.0.13
 * @package    RankMath
 * @subpackage RankMath\RichSnippet
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\RichSnippet;

use RankMath\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Collection_Page class.
 */
class Collection_Page implements Snippet {

	/**
	 * Outputs code to allow recognition of the CollectionPage.
	 *
	 * @link https://schema.org/CollectionPage
	 *
	 * @param array  $data   Array of json-ld data.
	 * @param JsonLD $jsonld JsonLD Instance.
	 *
	 * @return array
	 */
	public function process( $data, $jsonld ) {
		$queried_object = get_queried_object();

		/**
		 * Allow developer to remove snippet data.
		 *
		 * @param bool $unsigned Default: false
		 * @param string $unsigned Taxonomy Name
		 */
		if ( true === Helper::get_settings( 'titles.remove_' . $queried_object->taxonomy . '_snippet_data' ) || true === apply_filters( 'rank_math/snippet/remove_taxonomy_data', false, $queried_object->taxonomy ) ) {
			return $data;
		}

		$data['CollectionPage'] = [
			'@context'    => 'https://schema.org/',
			'@type'       => 'CollectionPage',
			'headline'    => single_term_title( '', false ),
			'description' => strip_tags( term_description() ),
			'url'         => get_term_link( get_queried_object() ),
			'hasPart'     => $jsonld->get_post_collection( $data ),
		];

		return $data;
	}
}
