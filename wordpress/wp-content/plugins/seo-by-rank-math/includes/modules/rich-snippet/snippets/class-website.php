<?php
/**
 * The Website Class
 *
 * @since      1.0.13
 * @package    RankMath
 * @subpackage RankMath\RichSnippet
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\RichSnippet;

defined( 'ABSPATH' ) || exit;

/**
 * Website class.
 */
class Website implements Snippet {

	/**
	 * Outputs code to allow recognition of the internal search engine.
	 *
	 * @link https://developers.google.com/structured-data/site-name
	 *
	 * @param array  $data   Array of json-ld data.
	 * @param JsonLD $jsonld JsonLD Instance.
	 *
	 * @return array
	 */
	public function process( $data, $jsonld ) {
		$data['WebSite'] = [
			'@context' => 'https://schema.org',
			'@type'    => 'WebSite',
			'@id'      => home_url( '/#website' ),
			'url'      => get_home_url(),
			'name'     => $jsonld->get_website_name(),
		];

		/**
		 * Allow disabling of the json+ld output.
		 *
		 * @param boolean Whether or not to display json+ld search on the frontend
		 */
		if ( ! apply_filters( 'rank_math/json_ld/disable_search', false ) ) {
			/**
			 * Allows filtering of the search URL.
			 *
			 * @param string $search_url The search URL for this site with a `{search_term_string}` variable.
			 */
			$search_url = apply_filters( 'rank_math/json_ld/search_url', home_url( '/?s={search_term_string}' ) );

			$data['WebSite']['potentialAction'] = [
				'@type'       => 'SearchAction',
				'target'      => $search_url,
				'query-input' => 'required name=search_term_string',
			];
		}

		return $data;
	}
}
