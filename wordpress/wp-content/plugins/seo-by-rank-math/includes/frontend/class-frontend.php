<?php
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-specific stylesheet and JavaScript.
 *
 * @since      0.9.0
 * @package    RankMath
 * @subpackage RankMath\Frontend
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\Frontend;

use RankMath\Post;
use RankMath\Helper;
use RankMath\Paper\Paper;
use RankMath\Traits\Hooker;
use RankMath\OpenGraph\Twitter;
use RankMath\OpenGraph\Facebook;
use RankMath\Frontend\Shortcodes;

defined( 'ABSPATH' ) || exit;

/**
 * Frontend class.
 */
class Frontend {

	use Hooker;

	/**
	 * The Constructor.
	 */
	public function __construct() {
		$this->includes();
		$this->hooks();

		/**
		 * Fires when frontend is included/loaded.
		 */
		$this->do_action( 'frontend/loaded' );
	}

	/**
	 * Include required files.
	 */
	private function includes() {

		rank_math()->shortcodes = new Shortcodes;

		if ( Helper::get_settings( 'general.breadcrumbs' ) ) {
			/**
			 * If breadcrumbs are active (which they supposedly are if the users has enabled this settings,
			 * there's no reason to have bbPress breadcrumbs as well.
			 */
			add_filter( 'bbp_get_breadcrumb', '__return_false' );
		}

		new Add_Attributes;
		new Remove_Reply_To_Com;
	}

	/**
	 * Hook into actions and filters.
	 */
	private function hooks() {

		$this->action( 'wp_enqueue_scripts', 'enqueue' );
		$this->action( 'wp', 'integrations' );
		$this->filter( 'the_content_feed', 'embed_rssfooter' );
		$this->filter( 'the_excerpt_rss', 'embed_rssfooter_excerpt' );

		// Reorder categories listing: put primary at the beginning.
		$this->filter( 'get_the_terms', 'reorder_the_terms', 10, 3 );

		// Redirect attachment page to parent post.
		if ( Helper::get_settings( 'general.attachment_redirect_urls', true ) ) {
			$this->action( 'wp', 'attachment_redirect_urls' );
		}

		// Redirect archives.
		if ( Helper::get_settings( 'titles.disable_author_archives' ) || Helper::get_settings( 'titles.disable_date_archives' ) ) {
			$this->action( 'wp', 'archive_redirect' );
		}

		// Custom robots text.
		if ( Helper::get_settings( 'general.robots_txt_content' ) ) {
			$this->action( 'robots_txt', 'robots_txt', 10, 2 );
		}
	}

	/**
	 * Initialize integrations.
	 */
	public function integrations() {
		$type = get_query_var( 'sitemap' );
		if ( ! empty( $type ) || is_customize_preview() ) {
			return;
		}

		Paper::get();
		new Facebook;
		new Twitter;

		// Leave this for backwards compatibility as AMP plugin uses head function. We can remove this in the future update.
		rank_math()->head = new Head;
	}

	/**
	 * Enqueue Styles and Scripts required by plugin.
	 */
	public function enqueue() {
		if ( ! is_user_logged_in() || ! Helper::has_cap( 'admin_bar' ) ) {
			return;
		}

		wp_enqueue_style( 'rank-math', rank_math()->assets() . 'css/rank-math.css', null, rank_math()->version );
		wp_enqueue_script( 'rank-math', rank_math()->assets() . 'js/rank-math.js', [ 'jquery' ], rank_math()->version, true );

		if ( is_singular() ) {
			Helper::add_json( 'objectID', Post::get_simple_page_id() );
			Helper::add_json( 'objectType', 'post' );
		} elseif ( is_category() || is_tag() || is_tax() ) {
			Helper::add_json( 'objectID', get_queried_object_id() );
			Helper::add_json( 'objectType', 'term' );
		} elseif ( is_author() ) {
			Helper::add_json( 'objectID', get_queried_object_id() );
			Helper::add_json( 'objectType', 'user' );
		}
	}

	/**
	 * Redirects attachment to its parent post if it has one.
	 */
	public function attachment_redirect_urls() {
		global $post;

		// Early bail.
		if ( ! is_attachment() ) {
			return;
		}

		$redirect = ! empty( $post->post_parent ) ? get_permalink( $post->post_parent ) : Helper::get_settings( 'general.attachment_redirect_default' );

		/**
		 * Redirect atachment to its parent post.
		 *
		 * @param string $redirect URL as calculated for redirection.
		 */
		Helper::redirect( $this->do_filter( 'frontend/attachment/redirect_url', $redirect ), 301 );
		exit;
	}

	/**
	 * When certain archives are disabled, this redirects those to the homepage.
	 */
	public function archive_redirect() {
		global $wp_query;

		if (
			( Helper::get_settings( 'titles.disable_date_archives' ) && $wp_query->is_date ) ||
			( true === Helper::get_settings( 'titles.disable_author_archives' ) && $wp_query->is_author )
		) {
			Helper::redirect( get_bloginfo( 'url' ), 301 );
			exit;
		}
	}

	/**
	 * Replace robots.txt content.
	 *
	 * @param  string $content Robots.txt file content.
	 * @param  bool   $public  Whether the site is considered "public".
	 * @return string New robots.txt content.
	 */
	public function robots_txt( $content, $public ) {
		if ( is_admin() ) {
			return $content;
		}

		return 0 === absint( $public ) ? $content : Helper::get_settings( 'general.robots_txt_content' );
	}

	/**
	 * Adds the RSS footer (or header) to the full RSS feed item.
	 *
	 * @param  string $content Feed item content.
	 * @return string
	 */
	public function embed_rssfooter( $content ) {
		return $this->embed_rss( $content, 'full' );
	}

	/**
	 * Adds the RSS footer (or header) to the excerpt RSS feed item.
	 *
	 * @param  string $content Feed item excerpt.
	 * @return string
	 */
	public function embed_rssfooter_excerpt( $content ) {
		return $this->embed_rss( $content, 'excerpt' );
	}

	/**
	 * Adds the RSS footer and/or header to an RSS feed item.
	 *
	 * @param  string $content Feed item content.
	 * @param  string $context Feed item context, either 'excerpt' or 'full'.
	 * @return string
	 */
	private function embed_rss( $content, $context = 'full' ) {
		if ( false === $this->can_embed_footer( $content, $context ) ) {
			return $content;
		}

		$before = $this->do_filter( 'frontend/rss/before_content', Helper::get_settings( 'general.rss_before_content' ) );
		$after  = $this->do_filter( 'frontend/rss/after_content', Helper::get_settings( 'general.rss_after_content' ) );

		if ( '' !== $before ) {
			$before = wpautop( $this->rss_replace_vars( $before ) );
		}

		if ( '' !== $after ) {
			$after = wpautop( $this->rss_replace_vars( $after ) );
		}

		if ( '' !== $before || '' !== $after ) {
			if ( 'excerpt' === $context && '' !== trim( $content ) ) {
				$content = wpautop( $content );
			}
			$content = $before . $content . $after;
		}

		return $content;
	}

	/**
	 * Can add the RSS footer and/or header to an RSS feed item.
	 *
	 * @param string $content Feed item content.
	 * @param string $context Feed item context, either 'excerpt' or 'full'.
	 *
	 * @return boolean
	 */
	private function can_embed_footer( $content, $context ) {
		/**
		 * Allow the RSS footer to be dynamically shown/hidden.
		 *
		 * @param bool   $show_embed Indicates if the RSS footer should be shown or not.
		 * @param string $context    The context of the RSS content - 'full' or 'excerpt'.
		 */
		if ( false === $this->do_filter( 'frontend/rss/include_footer', true, $context ) ) {
			return false;
		}

		if ( ! is_feed() ) {
			return false;
		}

		return true;
	}

	/**
	 * Replaces the possible RSS variables with their actual values.
	 *
	 * @param string $content The RSS content that should have the variables replaced.
	 *
	 * @return string
	 */
	private function rss_replace_vars( $content ) {
		global $post;

		/**
		 * Allow the developer to determine whether or not to follow the links in the bits Rank Math adds to the RSS feed, defaults to true.
		 *
		 * @param bool $unsigned Whether or not to follow the links in RSS feed, defaults to true.
		 */
		$no_follow = $this->do_filter( 'frontend/rss/nofollow_links', true );
		$no_follow = true === $no_follow ? 'rel="nofollow" ' : '';

		$author_link = '';
		if ( is_object( $post ) ) {
			$author_link = '<a ' . $no_follow . 'href="' . esc_url( get_author_posts_url( $post->post_author ) ) . '">' . esc_html( get_the_author() ) . '</a>';
		}
		$post_link      = '<a ' . $no_follow . 'href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a>';
		$blog_link      = '<a ' . $no_follow . 'href="' . esc_url( get_bloginfo( 'url' ) ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a>';
		$blog_desc_link = '<a ' . $no_follow . 'href="' . esc_url( get_bloginfo( 'url' ) ) . '">' . esc_html( get_bloginfo( 'name' ) ) . ' - ' . esc_html( get_bloginfo( 'description' ) ) . '</a>';

		// Featured image.
		$image = Helper::get_thumbnail_with_fallback( $post->ID, 'full' );
		$image = isset( $image[0] ) ? '<img src="' . $image[0] . '" style="display: block; margin: 1em auto">' : '';

		$content = stripslashes( trim( $content ) );
		$content = str_replace( '%AUTHORLINK%', $author_link, $content );
		$content = str_replace( '%POSTLINK%', $post_link, $content );
		$content = str_replace( '%BLOGLINK%', $blog_link, $content );
		$content = str_replace( '%BLOGDESCLINK%', $blog_desc_link, $content );
		$content = str_replace( '%FEATUREDIMAGE%', $image, $content );

		return $content;
	}

	/**
	 * Reorder terms for a post to put primary category to the beginning.
	 *
	 * @param  array|WP_Error $terms    List of attached terms, or WP_Error on failure.
	 * @param  int            $post_id  Post ID.
	 * @param  string         $taxonomy Name of the taxonomy.
	 * @return array
	 */
	public function reorder_the_terms( $terms, $post_id, $taxonomy ) {
		/**
		 * Filter: Allow disabling the primary term feature.
		 *
		 * @param bool $return True to disable.
		 */
		if ( true === $this->do_filter( 'primary_term', false ) ) {
			return $terms;
		}

		$post_id = empty( $post_id ) ? $GLOBALS['post']->ID : $post_id;

		// Get Primary Term.
		$primary = Helper::get_post_meta( "primary_{$taxonomy}", $post_id );
		if ( ! $primary ) {
			return $terms;
		}

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return [ $primary ];
		}

		$primary_term = null;
		foreach ( $terms as $index => $term ) {
			if ( $primary == $term->term_id ) {
				$primary_term = $term;
				unset( $terms[ $index ] );
				array_unshift( $terms, $primary_term );
				break;
			}
		}

		return $terms;
	}
}
