<?php
/**
 * The Author Class
 *
 * @since      1.0.22
 * @package    RankMath
 * @subpackage RankMath\Paper
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\Paper;

use RankMath\User;
use RankMath\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Author class.
 */
class Author implements IPaper {

	/**
	 * Retrieves the SEO title set in the user metabox.
	 *
	 * @return string The SEO title for the user.
	 */
	public function title() {
		$title = User::get_meta( 'title', $this->get_user_id() );
		if ( '' !== $title ) {
			return $title;
		}

		return Paper::get_from_options( 'author_archive_title' );
	}

	/**
	 * Retrieves the SEO description set in the user metabox.
	 *
	 * @return string The SEO description for the user.
	 */
	public function description() {
		$description = User::get_meta( 'description', $this->get_user_id() );
		if ( '' !== $description ) {
			return $description;
		}

		return Paper::get_from_options( 'author_archive_description' );
	}

	/**
	 * Retrieves the robots set in the user metabox.
	 *
	 * @return string The robots for the specified user.
	 */
	public function robots() {
		$robots = Paper::robots_combine( User::get_meta( 'robots', $this->get_user_id() ) );

		if ( empty( $robots ) && Helper::get_settings( 'titles.author_custom_robots' ) ) {
			$robots = Paper::robots_combine( Helper::get_settings( 'titles.author_robots' ), true );
		}

		return $robots;
	}

	/**
	 * Retrieves the advanced robots set in the user metabox.
	 *
	 * @return array The advanced robots for the specified user.
	 */
	public function advanced_robots() {
		$robots = Paper::advanced_robots_combine( User::get_meta( 'advanced_robots', $this->get_user_id() ) );

		if ( empty( $robots ) && Helper::get_settings( 'titles.author_custom_robots' ) ) {
			$robots = Paper::advanced_robots_combine( Helper::get_settings( 'titles.author_advanced_robots' ), true );
		}

		return $robots;
	}

	/**
	 * Retrieves the canonical URL.
	 *
	 * @return array
	 */
	public function canonical() {
		return [
			'canonical'          => get_author_posts_url( $this->get_user_id(), get_query_var( 'author_name' ) ),
			'canonical_override' => User::get_meta( 'canonical_url', $this->get_user_id() ),
		];
	}

	/**
	 * Retrieves the keywords.
	 *
	 * @return string The focus keywords.
	 */
	public function keywords() {
		return User::get_meta( 'focus_keyword', $this->get_user_id() );
	}

	/**
	 * Get User ID.
	 *
	 * @return int Current user ID.
	 */
	private function get_user_id() {
		$author_id = get_query_var( 'author' );
		if ( $author_id ) {
			return $author_id;
		}

		return get_query_var( 'bbp_user_id' );
	}
}
