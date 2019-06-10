<?php
/**
 * The Music Class
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
 * Music class.
 */
class Music implements Snippet {

	/**
	 * Music rich snippet.
	 *
	 * @param array  $data   Array of json-ld data.
	 * @param JsonLD $jsonld JsonLD Instance.
	 *
	 * @return array
	 */
	public function process( $data, $jsonld ) {
		$entity = [
			'@context'    => 'https://schema.org',
			'@type'       => Helper::get_post_meta( 'snippet_music_type' ),
			'name'        => $jsonld->parts['title'],
			'description' => $jsonld->parts['desc'],
			'url'         => $jsonld->parts['url'],
		];

		return $entity;
	}
}
