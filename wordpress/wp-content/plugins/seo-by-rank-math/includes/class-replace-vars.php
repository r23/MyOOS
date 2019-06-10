<?php
/**
 * Variable Replacer
 *
 * This class implements the replacing of `%variable_placeholders%` with their real value based on the current
 * requested page/post/cpt/etc in text strings.
 *
 * @since      0.9.0
 * @package    RankMath
 * @subpackage RankMath\Core
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath;

use MyThemeShop\Helpers\Str;
use MyThemeShop\Helpers\WordPress;
use RankMath\Admin\Admin_Helper;
use RankMath\Traits\Hooker;
use RankMath\Traits\Replacement;

defined( 'ABSPATH' ) || exit;

/**
 * Replace_Vars class.
 */
class Replace_Vars {

	use Hooker, Replacement;

	/**
	 * Register variable replacements.
	 *
	 * @var array
	 */
	protected static $replacements = [];

	/**
	 * Additional variable replacements registered by other plugins/themes.
	 *
	 * @var array
	 */
	protected static $external_replacements = [];

	/**
	 * Hold counter variable data.
	 *
	 * @var array
	 */
	protected static $counters = [];

	/**
	 * Default post/page/cpt information.
	 *
	 * @var array
	 */
	protected $defaults = array(
		'ID'            => '',
		'name'          => '',
		'post_author'   => '',
		'post_content'  => '',
		'post_date'     => '',
		'post_excerpt'  => '',
		'post_modified' => '',
		'post_title'    => '',
		'taxonomy'      => '',
		'term_id'       => '',
		'term404'       => '',
		'filename'      => '',
	);

	/**
	 * Replace `%variable_placeholders%` with their real value based on the current requested page/post/cpt.
	 *
	 * @param  string $string The string to replace the variables in.
	 * @param  array  $args   The object some of the replacement values might come from, could be a post, taxonomy or term.
	 * @param  array  $omit   Variables that should not be replaced by this function.
	 * @return string
	 */
	public function replace( $string, $args = [], $omit = [] ) {
		$string = strip_tags( $string );

		// Let's see if we can bail super early.
		if ( ! Str::contains( '%', $string ) ) {
			return $string;
		}

		if ( Str::ends_with( ' %sep%', $string ) ) {
			$string = str_replace( ' %sep%', '', $string );
		}

		$this->args = (object) wp_parse_args( $args, $this->defaults );

		// Clean $omit array.
		if ( is_array( $omit ) && ! empty( $omit ) ) {
			$omit = array_map( array( __CLASS__, 'remove_var_delimiter' ), $omit );
		}

		$replacements = [];
		if ( preg_match_all( '/%(([a-z0-9_-]+)\(([^)]*)\)|[^\s]+)%/iu', $string, $matches ) ) {
			$replacements = $this->set_up_replacements( $matches, $omit );
		}

		/**
		 * Allow customization of the replacements before they are applied.
		 *
		 * @param array $replacements The replacements.
		 */
		$replacements = $this->do_filter( 'replacements', $replacements );

		// Do the actual replacements.
		if ( is_array( $replacements ) && [] !== $replacements ) {
			$string = str_replace( array_keys( $replacements ), array_values( $replacements ), $string );
		}

		/**
		 * Allow overruling of whether or not to remove placeholders which didn't yield a replacement.
		 *
		 * @example <code>add_filter( 'rank_math/replacements_final', '__return_false' );</code>
		 * @param bool $final
		 */
		if ( true === $this->do_filter( 'replacements_final', true ) && ( isset( $matches[1] ) && is_array( $matches[1] ) ) ) {
			// Remove non-replaced variables.
			// Make sure the $omit variables do not get removed.
			$remove = array_diff( $matches[1], $omit );
			$remove = array_map( array( __CLASS__, 'add_var_delimiter' ), $remove );
			$string = str_replace( $remove, '', $string );
		}

		// Undouble separators which have nothing between them, i.e. where a non-replaced variable was removed.
		if ( isset( $replacements['%sep%'] ) && Str::is_non_empty( $replacements['%sep%'] ) ) {
			$q_sep  = preg_quote( $replacements['%sep%'], '`' );
			$string = preg_replace( '`' . $q_sep . '(?:\s*' . $q_sep . ')*`u', $replacements['%sep%'], $string );
		}

		return $string;
	}

	/**
	 * Register new replacement %variables%.
	 * For use by other plugins/themes to register extra variables.
	 *
	 * @see rank_math_register_var_replacement() for a usage example.
	 *
	 * @param  string $var       The name of the variable to replace, i.e. '%var%' the surrounding % are optional.
	 * @param  mixed  $callback  Function or method to call to retrieve the replacement value for the variable
	 *                           and should *return* the replacement value. DON'T echo it.
	 * @param  array  $args      Array with title, desc and example values.
	 * @return bool Whether the replacement function was succesfully registered.
	 */
	public static function register_replacement( $var, $callback, $args = [] ) {
		$success = false;

		if ( ! is_string( $var ) || empty( $var ) ) {
			return false;
		}

		$var = self::remove_var_delimiter( $var );

		if ( false === preg_match( '`^[A-Z0-9_-]+$`i', $var ) ) {
			trigger_error( esc_html__( 'A replacement variable can only contain alphanumeric characters, an underscore or a dash. Try renaming your variable.', 'rank-math' ), E_USER_WARNING );
			return false;
		}

		if ( ! method_exists( __CLASS__, 'get_' . $var ) ) {
			if ( ! isset( self::$external_replacements[ $var ] ) ) {
				$success                             = true;
				$args['callback']                    = $callback;
				self::$external_replacements[ $var ] = $args;
			} else {
				trigger_error( esc_html__( 'A replacement variable with the same name has already been registered. Try making your variable name unique.', 'rank-math' ), E_USER_WARNING );
			}
		} else {
			trigger_error( esc_html__( 'You cannot overrule a Rank Math standard variable replacement by registering a variable with the same name. Use the "wpseo_replacements" filter instead to adjust the replacement value.', 'rank-math' ), E_USER_WARNING );
		}

		return $success;
	}

	/**
	 * Enqueue Styles and Scripts required by plugin.
	 */
	public static function setup_json() {

		// Fetch data for this post.
		if ( Admin_Helper::is_post_edit() ) {
			global $post;
			\setup_postdata( $post );

			$author = get_userdata( $post->post_author );
			if ( $author ) {
				self::$replacements['name']['example'] = $author->display_name;
			}

			self::$replacements['id']['example']           = $post->ID;
			self::$replacements['userid']['example']       = $post->post_author;
			self::$replacements['title']['example']        = get_the_title();
			self::$replacements['date']['example']         = get_the_date();
			self::$replacements['modified']['example']     = get_the_modified_date();
			self::$replacements['excerpt']['example']      = WordPress::strip_shortcodes( self::get_safe_excerpt( $post ) );
			self::$replacements['excerpt_only']['example'] = $post->post_excerpt;

			// Custom Fields.
			$json          = [];
			$custom_fields = get_post_custom( $post->ID );
			if ( ! empty( $custom_fields ) ) {
				foreach ( $custom_fields as $custom_field_name => $custom_field ) {
					if ( substr( $custom_field_name, 0, 1 ) === '_' ) {
						continue;
					}

					$json[ $custom_field_name ] = $custom_field[0];
				}
			}
			Helper::add_json( 'customFields', $json );

			// Custom Taxonomies.
			self::set_custom_taxonomies( $post->ID );
		}

		// Fetch data for this term.
		if ( Admin_Helper::is_term_edit() ) {
			global $taxnow;
			$tag_id = isset( $_REQUEST['tag_ID'] ) ? absint( $_REQUEST['tag_ID'] ) : 0;
			$term   = get_term( $tag_id, $taxnow, OBJECT, 'edit' );

			self::$replacements['term']['example']             = $term->name;
			self::$replacements['term_description']['example'] = term_description( $term );
		}
		Helper::add_json( 'variables', apply_filters( 'rank_math/vars/replacements', array_merge( self::$replacements, self::$external_replacements ) ) );
	}

	/**
	 * Get safe excerpt.
	 *
	 * @param WP_Post $post Post instance.
	 *
	 * @return string
	 */
	public static function get_safe_excerpt( $post ) {
		if ( '' !== $post->post_excerpt ) {
			return strip_tags( $post->post_excerpt );
		} elseif ( '' !== $post->post_content ) {
			return wp_html_excerpt( WordPress::strip_shortcodes( $post->post_content ), 155 );
		}

		return '';
	}

	/**
	 * Setup the replacements.
	 */
	public static function setup() {
		global $wp_customize;
		if ( isset( $wp_customize ) ) {
			return;
		}

		if ( empty( self::$replacements ) ) {
			self::set_replacements();
		}

		if ( empty( self::$external_replacements ) ) {
			/**
			 * Action: 'rank_math/vars/register_extra_replacements' - Allows for registration of additional
			 * variables to replace.
			 */
			do_action( 'rank_math/vars/register_extra_replacements' );
		}
	}

	/**
	 * Get list of "swap variables" with descriptions.
	 * Used in the basic JS swap variables function: in meta box preview,
	 * options page title previews, and the variables dropdown.
	 */
	public static function set_replacements() {
		$self = new Replace_Vars();

		$current_user = wp_get_current_user();
		$post_id      = 1;
		$post_title   = esc_html__( 'Hello World', 'rank-math' );
		$posts_array  = get_posts( array( 'posts_per_page' => 1 ) );

		if ( ! empty( $posts_array[0] ) ) {
			$post_id    = $posts_array[0]->ID;
			$post_title = $posts_array[0]->post_title;
		}

		// Basic Variables.
		self::$replacements['title'] = array(
			'name'    => esc_html__( 'Post Title', 'rank-math' ),
			'desc'    => esc_html__( 'Title of the current post/page', 'rank-math' ),
			'example' => $post_title,
		);

		self::$replacements['parent_title'] = array(
			'name'    => esc_html__( 'Post Title of parent page', 'rank-math' ),
			'desc'    => esc_html__( 'Title of the parent page of the current post/page', 'rank-math' ),
			'example' => esc_html__( 'Example Title', 'rank-math' ),
		);

		self::$replacements['sep'] = array(
			'name'    => esc_html__( 'Separator Character', 'rank-math' ),
			'desc'    => esc_html__( 'Separator character, as set in the Title Settings', 'rank-math' ),
			'example' => $self->get_sep(),
		);

		self::$replacements['sitename'] = array(
			'name'    => esc_html__( 'Site Title', 'rank-math' ),
			'desc'    => esc_html__( 'Title of the site', 'rank-math' ),
			'example' => get_bloginfo( 'name' ),
		);

		self::$replacements['sitedesc'] = array(
			'name'    => esc_html__( 'Site Description', 'rank-math' ),
			'desc'    => esc_html__( 'Description of the site', 'rank-math' ),
			'example' => get_bloginfo( 'description' ),
		);

		self::$replacements['date'] = array(
			'name'    => esc_html__( 'Date Published', 'rank-math' ),
			'desc'    => wp_kses_post( __( 'Publication date of the current post/page <strong>OR</strong> specified date on date archives', 'rank-math' ) ),
			'example' => current_time( get_option( 'date_format' ) ),
		);

		self::$replacements['modified'] = array(
			'name'    => esc_html__( 'Date Modified', 'rank-math' ),
			'desc'    => esc_html__( 'Last modification date of the current post/page', 'rank-math' ),
			'example' => current_time( get_option( 'date_format' ) ),
		);

		self::$replacements['excerpt'] = array(
			'name'    => esc_html__( 'Post Excerpt', 'rank-math' ),
			'desc'    => esc_html__( 'Excerpt of the current post (or auto-generated if it does not exist)', 'rank-math' ),
			'example' => esc_html__( 'Post Excerpt', 'rank-math' ),
		);

		self::$replacements['excerpt_only'] = array(
			'name'    => esc_html__( 'Post Excerpt', 'rank-math' ),
			'desc'    => esc_html__( 'Excerpt of the current post (without auto-generation)', 'rank-math' ),
			'example' => esc_html__( 'Post Excerpt', 'rank-math' ),
		);

		self::$replacements['tag'] = array(
			'name'    => esc_html__( 'Post Tag', 'rank-math' ),
			'desc'    => wp_kses_post( __( 'First tag (alphabetically) associated to the current post <strong>OR</strong> current tag on tag archives', 'rank-math' ) ),
			'example' => esc_html__( 'Example Tag', 'rank-math' ),
		);

		self::$replacements['tags'] = array(
			'name'    => esc_html__( 'Post Tags', 'rank-math' ),
			'desc'    => esc_html__( 'Comma-separated list of tags associated to the current post', 'rank-math' ),
			'example' => esc_html__( 'Example Tag 1, Example Tag 2', 'rank-math' ),
		);

		self::$replacements['category'] = array(
			'name'    => esc_html__( 'Post Category', 'rank-math' ),
			'desc'    => wp_kses_post( __( 'First category (alphabetically) associated to the current post <strong>OR</strong> current category on category archives', 'rank-math' ) ),
			'example' => esc_html__( 'Example Category', 'rank-math' ),
		);

		self::$replacements['categories'] = array(
			'name'    => esc_html__( 'Post Categories', 'rank-math' ),
			'desc'    => esc_html__( 'Comma-separated list of categories associated to the current post', 'rank-math' ),
			'example' => esc_html__( 'Example Category 1, Example Category 2', 'rank-math' ),
		);

		self::$replacements['term'] = array(
			'name'    => esc_html__( 'Current Term', 'rank-math' ),
			'desc'    => esc_html__( 'Current term name', 'rank-math' ),
			'example' => esc_html__( 'Example Term', 'rank-math' ),
		);

		self::$replacements['term_description'] = array(
			'name'    => esc_html__( 'Term Description', 'rank-math' ),
			'desc'    => esc_html__( 'Current term description', 'rank-math' ),
			'example' => esc_html__( 'Example Term Description', 'rank-math' ),
		);

		self::$replacements['search_query'] = array(
			'name'    => esc_html__( 'Search Query', 'rank-math' ),
			'desc'    => esc_html__( 'Search query (only available on search results page)', 'rank-math' ),
			'example' => esc_html__( 'example search', 'rank-math' ),
		);

		self::$replacements['name'] = array(
			'name'    => esc_html__( 'Post Author', 'rank-math' ),
			'desc'    => esc_html__( 'Display author\'s nicename of the current post, page or author archive.', 'rank-math' ),
			'example' => $current_user->display_name,
		);

		self::$replacements['user_description'] = array(
			'name'    => esc_html__( 'Author Description', 'rank-math' ),
			'desc'    => esc_html__( 'Author\'s biographical info of the current post, page or author archive.', 'rank-math' ),
			'example' => get_the_author_meta( 'desc' ),
		);

		self::$replacements['filename'] = array(
			'name'    => esc_html__( 'File Name', 'rank-math' ),
			'desc'    => esc_html__( 'File Name of the attachment', 'rank-math' ),
			'example' => 'sunrise at Maldives',
		);

		// Advanced.
		self::$replacements['userid'] = array(
			'name'    => esc_html__( 'Author ID', 'rank-math' ),
			'desc'    => esc_html__( 'Author\'s user id of the current post, page or author archive.', 'rank-math' ),
			'example' => $current_user->ID,
		);

		self::$replacements['id'] = array(
			'name'    => esc_html__( 'Post ID', 'rank-math' ),
			'desc'    => esc_html__( 'ID of the current post/page', 'rank-math' ),
			'example' => $post_id,
		);

		self::$replacements['focuskw'] = array(
			'name'    => esc_html__( 'Focus Keyword', 'rank-math' ),
			'desc'    => esc_html__( 'Focus Keyword of the current post', 'rank-math' ),
			'example' => esc_html__( 'Focus Keyword', 'rank-math' ),
		);

		self::$replacements['page'] = array(
			'name'    => esc_html__( 'Page', 'rank-math' ),
			'desc'    => esc_html__( 'Page number with context (i.e. page 2 of 4). Only displayed on page 2 and above.', 'rank-math' ),
			'example' => ' page 2 of 4',
		);

		self::$replacements['pagetotal'] = array(
			'name'    => esc_html__( 'Max Pages', 'rank-math' ),
			'desc'    => esc_html__( 'Max pages number', 'rank-math' ),
			'example' => '4',
		);

		self::$replacements['pagenumber'] = array(
			'name'    => esc_html__( 'Page Number', 'rank-math' ),
			'desc'    => esc_html__( 'Current page number', 'rank-math' ),
			'example' => '4',
		);

		self::$replacements['currenttime'] = array(
			'name'    => esc_html__( 'Current Time', 'rank-math' ),
			'desc'    => esc_html__( 'Current server time', 'rank-math' ),
			'example' => current_time( get_option( 'time_format' ) ),
		);

		self::$replacements['currentdate'] = array(
			'name'    => esc_html__( 'Current Date', 'rank-math' ),
			'desc'    => esc_html__( 'Current server date', 'rank-math' ),
			'example' => current_time( get_option( 'date_format' ) ),
		);

		self::$replacements['currentday'] = array(
			'name'    => esc_html__( 'Current Day', 'rank-math' ),
			'desc'    => esc_html__( 'Current server day', 'rank-math' ),
			'example' => current_time( 'dS' ),
		);

		self::$replacements['currentmonth'] = array(
			'name'    => esc_html__( 'Current Month', 'rank-math' ),
			'desc'    => esc_html__( 'Current server month', 'rank-math' ),
			'example' => current_time( 'F' ),
		);

		self::$replacements['currentyear'] = array(
			'name'    => esc_html__( 'Current Year', 'rank-math' ),
			'desc'    => esc_html__( 'Current server year', 'rank-math' ),
			'example' => current_time( 'Y' ),
		);

		self::$replacements['pt_single'] = array(
			'name'    => esc_html__( 'Post Type Name Singular', 'rank-math' ),
			'desc'    => esc_html__( 'Name of current post type (singular)', 'rank-math' ),
			'example' => esc_html__( 'Product', 'rank-math' ),
		);

		self::$replacements['pt_plural'] = array(
			'name'    => esc_html__( 'Post Type Name Plural', 'rank-math' ),
			'desc'    => esc_html__( 'Name of current post type (plural)', 'rank-math' ),
			'example' => esc_html__( 'Products', 'rank-math' ),
		);

		self::$replacements['customfield(field-name)'] = array(
			'name'    => esc_html__( 'Custom Field (advanced)', 'rank-math' ),
			'desc'    => esc_html__( 'Custom field value.', 'rank-math' ),
			'example' => esc_html__( 'Custom field value', 'rank-math' ),
		);

		self::$replacements['date(F jS, Y)'] = array(
			'name'    => esc_html__( 'Date Published (advanced)', 'rank-math' ),
			'desc'    => esc_html__( 'Publish date with custom formatting pattern.', 'rank-math' ),
			'example' => date( 'F jS, Y' ),
		);

		self::$replacements['modified(F jS, Y)'] = array(
			'name'    => esc_html__( 'Date Modified (advanced)', 'rank-math' ),
			'desc'    => esc_html__( 'Modified date with custom formatting pattern.', 'rank-math' ),
			'example' => date( 'F jS, Y' ),
		);

		self::$replacements['currenttime(F jS, Y)'] = array(
			'name'    => esc_html__( 'Current Time (advanced)', 'rank-math' ),
			'desc'    => esc_html__( 'Current server time with custom formatting pattern.', 'rank-math' ),
			'example' => current_time( 'F jS, Y' ),
		);

		self::$replacements['categories(limit=3&separator= | &exclude=12,23)'] = array(
			'name'    => esc_html__( 'Categories (advanced)', 'rank-math' ),
			'desc'    => esc_html__( 'Output list of categories associated to the current post, with customization options.', 'rank-math' ),
			'example' => esc_html__( 'Example Category 1 | Example Category 2', 'rank-math' ),
		);

		self::$replacements['tags(limit=3&separator= | &exclude=12,23)'] = array(
			'name'    => esc_html__( 'Tags (advanced)', 'rank-math' ),
			'desc'    => esc_html__( 'Output list of tags associated to the current post, with customization options.', 'rank-math' ),
			'example' => esc_html__( 'Example Tag 1 | Example Tag 2', 'rank-math' ),
		);

		self::$replacements['count(varname)'] = array(
			'name'    => esc_html__( 'Counter', 'rank-math' ),
			'desc'    => esc_html__( 'Starts at 1 and increments by 1.', 'rank-math' ),
			'example' => '2',
		);
	}

	/**
	 * Set custom taxonomies.
	 *
	 * @param  int $post_id The current post ID.
	 * @return void
	 */
	public static function set_custom_taxonomies( $post_id ) {
		$custom_taxonomies = get_post_taxonomies( $post_id );
		if ( empty( $custom_taxonomies ) ) {
			return;
		}

		$json = [];
		foreach ( $custom_taxonomies as $taxonomy ) {
			if ( in_array( $taxonomy, [ 'category', 'post_tag' ] ) ) {
				continue;
			}

			$name = str_replace( '_', ' ', $taxonomy );
			$name = ucwords( str_replace( '-', ' ', $name ) );
			/* translators: Taxonomy name. */
			$title = sprintf( __( '%s Title', 'rank-math' ), $name );
			/* translators: Taxonomy name. */
			$desc = sprintf( __( '%s Description', 'rank-math' ), $name );

			self::$replacements[ "customterm({$taxonomy})" ] = [
				'name'    => $title,
				'desc'    => esc_html__( 'Custom Term title.', 'rank-math' ),
				'example' => $title,
			];

			self::$replacements[ "customterm({$taxonomy}_desc)" ] = [
				'name'    => $desc,
				'desc'    => esc_html__( 'Custom Term description.', 'rank-math' ),
				'example' => $desc,
			];

			$json[ $taxonomy ]          = $title;
			$json[ "{$taxonomy}_desc" ] = $desc;
		}
		Helper::add_json( 'customTerms', $json );
	}

	/**
	 * Retrieves the queried post type.
	 *
	 * @return string The queried post type.
	 */
	protected function get_queried_post_type() {
		$post_type = get_post_type();

		if ( false !== $post_type ) {
			return $post_type;
		}

		$post_type = get_query_var( 'post_type' );
		if ( is_array( $post_type ) ) {
			$post_type = reset( $post_type );
		}

		return $post_type;
	}

	/**
	 * Get the separator for use as replacement string.
	 *
	 * @return string
	 */
	protected function get_sep() {
		$sep = $this->do_filter( 'settings/title_separator', Helper::get_settings( 'titles.title_separator' ) );

		return htmlentities( $sep, ENT_COMPAT, 'UTF-8', false );
	}

	/**
	 * Get the title of the parent page of the current page/cpt for use as replacement string.
	 * Only applicable for hierarchical post types.
	 *
	 * @return string|null
	 */
	private function get_parent_title() {
		$replacement = null;

		if ( is_singular() || is_admin() ) {
			if ( isset( $this->args->post_parent ) && 0 !== $this->args->post_parent ) {
				$replacement = get_the_title( $this->args->post_parent );
			}
		}

		return $replacement;
	}

	/**
	 * Get the site's name for use as replacement string.
	 *
	 * @return string|null
	 */
	private function get_sitename() {
		static $replacement;

		if ( ! isset( $replacement ) ) {
			$sitename = wp_strip_all_tags( get_bloginfo( 'name' ), true );
			if ( '' !== $sitename ) {
				$replacement = $sitename;
			}
		}

		return $replacement;
	}

	/**
	 * Get the site's tag line / description for use as replacement string.
	 *
	 * @return string|null
	 */
	private function get_sitedesc() {
		static $replacement;

		if ( ! isset( $replacement ) ) {
			$description = trim( strip_tags( get_bloginfo( 'description' ) ) );
			if ( '' !== $description ) {
				$replacement = $description;
			}
		}

		return $replacement;
	}

	/**
	 * Get the date of the post/page/cpt for use as replacement string.
	 *
	 * @param string $format (Optional) PHP date format defaults to the date_format option if not specified. Default: ''.
	 * @return string|null
	 */
	private function get_date( $format = '' ) {
		if ( '' !== $this->args->post_date ) {
			$format = $format ? $format : get_option( 'date_format' );
			return mysql2date( $format, $this->args->post_date, true );
		}

		if ( get_query_var( 'day' ) && get_query_var( 'day' ) !== '' ) {
			return get_the_date( $format );
		}

		if ( single_month_title( ' ', false ) && '' !== single_month_title( ' ', false ) ) {
			return single_month_title( ' ', false );
		}

		if ( '' !== get_query_var( 'year' ) ) {
			return get_query_var( 'year' );
		}

		return null;
	}

	/**
	 * Get the post/page/cpt modified time for use as replacement string.
	 *
	 * @param string $format (Optional) PHP date format defaults to the date_format option if not specified. Default: ''.
	 * @return string|null
	 */
	private function get_modified( $format = '' ) {
		$replacement = null;

		if ( ! empty( $this->args->post_modified ) ) {
			$format      = $format ? $format : get_option( 'date_format' );
			$replacement = mysql2date( $format, $this->args->post_modified, true );
		}

		return $replacement;
	}

	/**
	 * Get the post/page/cpt excerpt for use as replacement string.
	 * The excerpt will be auto-generated if it does not exist.
	 *
	 * @return string|null
	 */
	private function get_excerpt() {
		$replacement = null;

		if ( ! empty( $this->args->ID ) ) {
			if ( '' !== $this->args->post_excerpt ) {
				$replacement = strip_tags( $this->args->post_excerpt );
			} elseif ( '' !== $this->args->post_content ) {
				$replacement = wp_html_excerpt( WordPress::strip_shortcodes( $this->args->post_content ), 155 );
			}
		}

		return $replacement;
	}

	/**
	 * Get the post/page/cpt excerpt for use as replacement string (without auto-generation).
	 *
	 * @return string|null
	 */
	private function get_excerpt_only() {
		$replacement = null;

		if ( ! empty( $this->args->ID ) && '' !== $this->args->post_excerpt ) {
			$replacement = strip_tags( $this->args->post_excerpt );
		}

		return $replacement;
	}

	/**
	 * Get the current tag for use as replacement string.
	 *
	 * @return string|null
	 */
	private function get_tag() {
		$replacement = null;

		if ( ! empty( $this->args->ID ) ) {
			$tags = $this->get_terms( $this->args->ID, 'post_tag', true );
			if ( '' !== $tags ) {
				$replacement = $tags;
			}
		}

		return $replacement;
	}

	/**
	 * Get the current tags for use as replacement string.
	 *
	 * @param array $args Arguments to get terms.
	 * @return string|null
	 */
	private function get_tags( $args = [] ) {
		$replacement = null;

		if ( ! empty( $this->args->ID ) ) {
			$tags = $this->get_terms( $this->args->ID, 'post_tag', false, $args );
			if ( '' !== $tags ) {
				$replacement = $tags;
			}
		}

		return $replacement;
	}

	/**
	 * Get the post/cpt category for use as replacement string.
	 *
	 * @return string|null
	 */
	private function get_category() {
		$replacement = null;

		if ( ! empty( $this->args->ID ) ) {
			$cat = $this->get_terms( $this->args->ID, 'category', true );
			if ( '' !== $cat ) {
				$replacement = $cat;
			}
		}

		if ( ( ! isset( $replacement ) || '' === $replacement ) && ( isset( $this->args->cat_name ) && ! empty( $this->args->cat_name ) ) ) {
			$replacement = $this->args->cat_name;
		}

		return $replacement;
	}

	/**
	 * Get the post/cpt categories (comma separated) for use as replacement string.
	 *
	 * @param array $args Array of arguments.
	 * @return string|null
	 */
	private function get_categories( $args = [] ) {
		$replacement = null;

		if ( ! empty( $this->args->ID ) ) {
			$cat = $this->get_terms( $this->args->ID, 'category', false, $args );
			if ( '' !== $cat ) {
				$replacement = $cat;
			}
		}

		return $replacement;
	}

	/**
	 * Get the term name for use as replacement string.
	 *
	 * @return string|null
	 */
	private function get_term() {
		$replacement = null;

		if ( is_category() || is_tag() || is_tax() ) {
			global $wp_query;
			$replacement = $wp_query->queried_object->name;
		} elseif ( ! empty( $this->args->taxonomy ) && ! empty( $this->args->name ) ) {
			$replacement = $this->args->name;
		}

		return $replacement;
	}

	/**
	 * Get the term description for use as replacement string.
	 *
	 * @return string|null
	 */
	private function get_term_description() {
		if ( is_category() || is_tag() || is_tax() ) {
			global $wp_query;
			return $wp_query->queried_object->description;
		}

		if ( isset( $this->args->term_id ) && ! empty( $this->args->taxonomy ) ) {
			$term_desc = get_term_field( 'description', $this->args->term_id, $this->args->taxonomy );
			if ( '' !== $term_desc ) {
				return trim( strip_tags( $term_desc ) );
			}
		}

		return null;
	}

	/**
	 * Get the current search phrase for use as replacement string.
	 *
	 * @return string|null
	 */
	private function get_search_query() {
		$replacement = null;

		if ( ! isset( $replacement ) ) {
			$search = get_search_query();
			if ( '' !== $search ) {
				$replacement = $search;
			}
		}

		return $replacement;
	}

	/**
	 * Get the post/page/cpt author's user id for use as replacement string.
	 *
	 * @return string
	 */
	private function get_userid() {
		return ! empty( $this->args->post_author ) ? $this->args->post_author : get_query_var( 'author' );
	}

	/**
	 * Get the post/page/cpt author's "nice name" for use as replacement string.
	 *
	 * @return string|null
	 */
	private function get_name() {
		$replacement = null;

		$user_id = $this->get_userid();
		$name    = get_the_author_meta( 'display_name', $user_id );
		if ( '' !== $name ) {
			$replacement = $name;
		}

		return $replacement;
	}

	/**
	 * Get the filename of the attachment.
	 *
	 * @return string|null
	 */
	private function get_filename() {
		if ( empty( $this->args->filename ) ) {
			return null;
		}

		$replacement = null;
		$name        = \pathinfo( $this->args->filename );

		// Remove size if any embedded.
		$name = explode( '-', $name['filename'] );
		if ( Str::contains( 'x', end( $name ) ) ) {
			array_pop( $name );
		}

		// Format name.
		$name = join( ' ', $name );
		$name = trim( str_replace( '_', ' ', $name ) );
		if ( '' !== $name ) {
			$replacement = $name;
		}

		return $replacement;
	}

	/**
	 * Get the post/page/cpt author's users description for use as a replacement string.
	 *
	 * @return string|null
	 */
	private function get_user_description() {
		$replacement = null;

		$user_id     = $this->get_userid();
		$description = get_the_author_meta( 'description', $user_id );
		if ( '' !== $description ) {
			$replacement = $description;
		}

		return $replacement;
	}

	/**
	 * Get the post/page/cpt ID for use as replacement string.
	 *
	 * @return string|null
	 */
	private function get_id() {
		$replacement = null;

		if ( ! empty( $this->args->ID ) ) {
			$replacement = $this->args->ID;
		}

		return $replacement;
	}

	/**
	 * Get the post/page/cpt's focus keyword for use as replacement string.
	 *
	 * @return string|null
	 */
	private function get_focuskw() {
		$replacement = null;

		if ( ! empty( $this->args->ID ) ) {
			$focus_kw = Helper::get_post_meta( 'focus_keyword', $this->args->ID );
			if ( '' !== $focus_kw ) {
				$replacement = $focus_kw;
			}
		}

		return $replacement;
	}

	/**
	 * Get the current page number with context (i.e. 'page 2 of 4') for use as replacement string.
	 *
	 * @return string
	 */
	private function get_page() {
		$replacement = null;

		$max  = $this->determine_max_pages();
		$page = $this->determine_page_number();
		$sep  = $this->get_sep();

		if ( $max > 1 && $page > 1 ) {
			/* translators: 1: current page number, 2: total number of pages. */
			$replacement = sprintf( $sep . ' ' . __( 'Page %1$d of %2$d', 'rank-math' ), $page, $max );
		}

		return $replacement;
	}

	/**
	 * Get the current page number for use as replacement string.
	 *
	 * @return string|null
	 */
	private function get_pagenumber() {
		$replacement = null;

		$page = $this->determine_page_number();
		if ( isset( $page ) && $page > 0 ) {
			$replacement = (string) $page;
		}

		return $replacement;
	}

	/**
	 * Get the current page total for use as replacement string.
	 *
	 * @return string|null
	 */
	private function get_pagetotal() {
		$replacement = null;

		$max = $this->determine_max_pages();
		if ( isset( $max ) && $max > 0 ) {
			$replacement = (string) $max;
		}

		return $replacement;
	}

	/**
	 * Get the current time for use as replacement string.
	 *
	 * @param string $format (Optional) PHP date format defaults to the date_format option if not specified. Default: ''.
	 * @return string
	 */
	private function get_currenttime( $format = '' ) {
		static $replacement;

		if ( ! isset( $replacement ) ) {
			$format      = $format ? $format : get_option( 'time_format' );
			$replacement = date_i18n( $format );
		}

		return $replacement;
	}

	/**
	 * Get the current date for use as replacement string.
	 *
	 * @param string $format (Optional) PHP date format defaults to the date_format option if not specified. Default: ''.
	 * @return string
	 */
	private function get_currentdate( $format = '' ) {
		static $replacement;

		if ( ! isset( $replacement ) ) {
			$format      = $format ? $format : get_option( 'date_format' );
			$replacement = date_i18n( $format );
		}

		return $replacement;
	}

	/**
	 * Get the current day for use as replacement string.
	 *
	 * @return string
	 */
	private function get_currentday() {
		static $replacement;

		if ( ! isset( $replacement ) ) {
			$replacement = date_i18n( 'j' );
		}

		return $replacement;
	}

	/**
	 * Get the current month for use as replacement string.
	 *
	 * @return string
	 */
	private function get_currentmonth() {
		static $replacement;

		if ( ! isset( $replacement ) ) {
			$replacement = date_i18n( 'F' );
		}

		return $replacement;
	}

	/**
	 * Get the current year for use as replacement string.
	 *
	 * @return string
	 */
	private function get_currentyear() {
		static $replacement;

		if ( ! isset( $replacement ) ) {
			$replacement = date_i18n( 'Y' );
		}

		return $replacement;
	}

	/**
	 * Get the post type single label for use as replacement string.
	 *
	 * @return string|null
	 */
	private function get_pt_single() {
		$replacement = null;

		$name = $this->determine_pt_names( 'single' );
		if ( isset( $name ) && '' !== $name ) {
			$replacement = $name;
		}

		return $replacement;
	}

	/**
	 * Get a post/page/cpt's custom field value for use as replacement string.
	 *
	 * @param  string $name The name of custom field of which value is to be retrieved.
	 * @return string|null
	 */
	private function get_customfield( $name ) {
		global $post;
		$replacement = null;

		if ( Str::is_non_empty( $name ) ) {
			if ( ( is_singular() || is_admin() ) && ( is_object( $post ) && isset( $post->ID ) ) ) {
				$name = get_post_meta( $post->ID, $name, true );
				if ( '' !== $name ) {
					$replacement = $name;
				}
			}
		}

		return $replacement;
	}


	/**
	 * Get a post/page/cpt's custom term value for use as replacement string.
	 *
	 * @param  string $name The name of the taxonomy of which value is to be retrieved.
	 * @return string|null
	 */
	private function get_customterm( $name ) {
		if ( Str::is_non_empty( $name ) ) {
			global $post;
			$taxonomy = str_replace( '_desc', '', $name );
			return Str::ends_with( 'desc', $name ) ? $this->get_terms( $post->ID, $taxonomy, true, [], 'description' ) : $this->get_terms( $post->ID, $taxonomy, true, [], 'name' );
		}

		return null;
	}

	/**
	 * Get the counter for the given variable.
	 *
	 * @param  string $name The name of field.
	 * @return string|null
	 */
	private function get_count( $name ) {

		if ( ! isset( self::$counters[ $name ] ) ) {
			self::$counters[ $name ] = 0;
		}

		return ++self::$counters[ $name ];
	}

	/**
	 * Get the post type plural label for use as replacement string.
	 *
	 * @return string|null
	 */
	private function get_pt_plural() {
		$replacement = null;

		$name = $this->determine_pt_names( 'plural' );
		if ( isset( $name ) && '' !== $name ) {
			$replacement = $name;
		}

		return $replacement;
	}

	/**
	 * Retrieve the replacements for the variables found.
	 *
	 * @param  array $matches Variables found in the original string - regex result.
	 * @param  array $omit    Variables that should not be replaced by this function.
	 * @return array Retrieved replacements - this might be a smaller array as some variables
	 *               may not yield a replacement in certain contexts.
	 */
	private function set_up_replacements( $matches, $omit ) {
		$replacements = [];

		foreach ( $matches[1] as $k => $var ) {

			// Don't set up replacements which should be omitted.
			if ( in_array( $var, $omit, true ) ) {
				continue;
			}

			$args   = [];
			$method = 'get_' . $var;

			// Complex Tags.
			if ( ! empty( $matches[2][ $k ] ) && ! empty( $matches[3][ $k ] ) ) {
				$args   = $this->normalize_args( $matches[3][ $k ] );
				$method = 'get_' . $matches[2][ $k ];
			}

			if ( method_exists( $this, $method ) ) {
				$replacement = $this->$method( $args );
			} elseif ( isset( self::$external_replacements[ $var ] ) && ! is_null( isset( self::$external_replacements[ $var ] ) ) ) {
				$replacement = call_user_func( self::$external_replacements[ $var ]['callback'], $var, $args );
			}

			// Replacement retrievals can return null if no replacement can be determined, root those outs.
			if ( isset( $replacement ) ) {
				$var                  = self::add_var_delimiter( $var );
				$replacements[ $var ] = $replacement;
			}

			unset( $replacement, $method );
		}

		return $replacements;
	}

	/**
	 * Get the title of the post/page/cpt for use as replacement string.
	 *
	 * @return string|null
	 */
	private function get_title() {
		$replacement = null;

		// Get post type name as Title.
		if ( is_post_type_archive() && ! Post::is_shop_page() ) {
			$post_type   = $this->get_queried_post_type();
			$replacement = get_post_type_object( $post_type )->labels->name;
		} elseif ( Str::is_non_empty( $this->args->post_title ) ) {
			$replacement = stripslashes( $this->args->post_title );
		}

		return $replacement;
	}

	/**
	 * Convert string to argument array.
	 *
	 * @param  string $string The string need to be convereted.
	 * @return array
	 */
	private function normalize_args( $string ) {
		if ( ! Str::contains( '=', $string ) ) {
			return $string;
		}

		return wp_parse_args( $string, [] );
	}

	/**
	 * Get a post's terms, comma delimited.
	 *
	 * @param int    $id            ID of the post to get the terms for.
	 * @param string $taxonomy      The taxonomy to get the terms for this post from.
	 * @param bool   $return_single If true, return the first term.
	 * @param array  $args          Array of passed arguments.
	 * @param string $field         The term field to return.
	 * @return string Either a single term or a comma delimited string of terms.
	 */
	private function get_terms( $id, $taxonomy, $return_single = false, $args = [], $field = 'name' ) {
		$output = '';

		// If we're on a specific tag, category or taxonomy page, use that.
		if ( is_category() || is_tag() || is_tax() ) {
			$term   = $GLOBALS['wp_query']->get_queried_object();
			$output = $term->name;
		}

		if ( ! $output && ! empty( $id ) && ! empty( $taxonomy ) ) {

			$args = wp_parse_args( $args, array(
				'limit'     => 99,
				'separator' => ', ',
				'exclude'   => [],
			) );

			if ( ! empty( $args['exclude'] ) ) {
				$args['exclude'] = array_map( 'trim', explode( ',', $args['exclude'] ) );
			}

			$terms = get_the_terms( $id, $taxonomy );
			if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
				$count  = 0;
				$output = [];
				foreach ( $terms as $term ) {

					// Limit.
					$count++;
					if ( $count > $args['limit'] ) {
						break;
					}

					// Exclude.
					if ( in_array( $term->term_id, $args['exclude'], true ) ) {
						continue;
					}

					if ( $return_single ) {
						$output = $term->{$field};
						break;
					}

					$output[] = $term->{$field};
				}

				$output = is_array( $output ) ? join( $args['separator'], $output ) : $output;
			}
		}
		unset( $terms, $term );

		/**
		 * Allows filtering of the terms list used to replace %category%, %tag%.
		 *
		 * @param string $output Comma-delimited string containing the terms.
		 */
		return $this->do_filter( 'vars/terms', $output );
	}
}
