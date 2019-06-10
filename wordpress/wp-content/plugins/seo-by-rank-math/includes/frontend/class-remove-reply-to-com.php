<?php
/**
 * The class handles the removal of replytocom.
 *
 * @since      1.0.15
 * @package    RankMath
 * @subpackage RankMath\Frontend
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\Frontend;

use RankMath\Helper;
use RankMath\Traits\Hooker;

defined( 'ABSPATH' ) || exit;

/**
 * Remove Reply To Com class.
 */
class Remove_Reply_To_Com {

	use Hooker;

	/**
	 * The Constructor.
	 */
	public function __construct() {
		if ( $this->clean_reply_to_com() ) {
			$this->filter( 'comment_reply_link', 'remove_reply_to_com' );
			$this->action( 'template_redirect', 'replytocom_redirect', 1 );
		}
	}

	/**
	 * Removes the ?replytocom variable from the link, replacing it with a #comment-<number> anchor.
	 *
	 * @param  string $link The comment link as a string.
	 * @return string The modified link.
	 */
	public function remove_reply_to_com( $link ) {
		return preg_replace( '`href=(["\'])(?:.*(?:\?|&|&#038;)replytocom=(\d+)#respond)`', 'href=$1#comment-$2', $link );
	}

	/**
	 * Redirects out the ?replytocom variables.
	 *
	 * @return bool True when redirect has been done.
	 */
	public function replytocom_redirect() {

		if ( isset( $_GET['replytocom'] ) && is_singular() ) {
			$url          = get_permalink( $GLOBALS['post']->ID );
			$query_string = remove_query_arg( 'replytocom', sanitize_text_field( $_SERVER['QUERY_STRING'] ) );
			if ( ! empty( $query_string ) ) {
				$url .= '?' . $query_string;
			}
			$url .= '#comment-' . sanitize_text_field( $_GET['replytocom'] );
			Helper::redirect( $url, 301 );
			return true;
		}

		return false;
	}

	/**
	 * Checks whether we can allow the feature that removes ?replytocom query parameters.
	 *
	 * @return bool True to remove, false not to remove.
	 */
	private function clean_reply_to_com() {
		/**
		 * Filter: 'rank_math_remove_reply_to_com' - Allow disabling the feature that removes ?replytocom query parameters.
		 *
		 * @param bool $return True to remove, false not to remove.
		 */
		return (bool) $this->do_filter( 'frontend/remove_reply_to_com', true );
	}
}
