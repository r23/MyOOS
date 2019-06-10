<?php
/**
 * The Sitemap Module
 *
 * @since      0.9.0
 * @package    RankMath
 * @subpackage RankMath\Sitemap
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\Sitemap;

use RankMath\Helper;
use RankMath\Module;
use RankMath\Admin\Options;
use MyThemeShop\Helpers\Str;
use RankMath\KB;

defined( 'ABSPATH' ) || exit;

/**
 * Admin class.
 */
class Admin extends Module {

	/**
	 * The Constructor.
	 */
	public function __construct() {

		$directory = dirname( __FILE__ );
		$this->config(array(
			'id'        => 'sitemap',
			'directory' => $directory,
			'help'      => array(
				'title' => esc_html__( 'Sitemap', 'rank-math' ),
				'view'  => $directory . '/views/help.php',
			),
		));
		parent::__construct();

		$this->action( 'init', 'register_setting_page', 999 );
		$this->filter( 'rank_math/sitemap/settings', 'post_type_settings' );
		$this->filter( 'rank_math/sitemap/settings', 'taxonomy_settings' );
		$this->filter( 'rank_math/sitemap/settings', 'special_seprator' );
		$this->action( 'rank_math/metabox/settings/advanced', 'metabox_settings_advanced', 9 );

		// Attachment.
		$this->filter( 'media_send_to_editor', 'media_popup_html', 10, 2 );
		$this->filter( 'attachment_fields_to_edit', 'media_popup_fields', 20, 2 );
		$this->filter( 'attachment_fields_to_save', 'media_popup_fields_save', 20, 2 );
	}

	/**
	 * Register setting page.
	 */
	public function register_setting_page() {
		$sitemap_url = Router::get_base_url( 'sitemap_index.xml' );

		$tabs = array(
			'general' => array(
				'icon'  => 'fa fa-cogs',
				'title' => esc_html__( 'General', 'rank-math' ),
				'file'  => $this->directory . '/settings/general.php',
				'desc'  => esc_html__( 'This tab contains settings related to the XML sitemaps.', 'rank-math' ) . ' <a href="' . KB::get( 'sitemap-general' ) . '" target="_blank">' . esc_html__( 'Learn more', 'rank-math' ) . '</a>',
				/* translators: sitemap url */
				'after' => $this->get_notice_start() . sprintf( esc_html__( 'When sitemaps are enabled, your sitemap index can be found here: %s', 'rank-math' ), '<a href="' . $sitemap_url . '" target="_blank">' . $sitemap_url . '</a>' ) . '</p></div>',
			),
		);

		if ( Helper::is_author_archive_indexable() ) {
			$tabs['authors'] = array(
				'icon'  => 'fa fa-users',
				'title' => esc_html__( 'Authors', 'rank-math' ),
				/* translators: Learn more link. */
				'desc'  => sprintf( esc_html__( 'Set the sitemap options for author archive pages. %s.', 'rank-math' ), '<a href="https://s.rankmath.com/sitemaps" target="_blank">' . esc_html__( 'Learn more', 'rank-math' ) . '</a>' ),
				'file'  => $this->directory . '/settings/authors.php',
			);
		}

		$tabs = $this->do_filter( 'sitemap/settings', $tabs );

		new Options( array(
			'key'        => 'rank-math-options-sitemap',
			'title'      => esc_html__( 'Sitemap Settings', 'rank-math' ),
			'menu_title' => esc_html__( 'Sitemap Settings', 'rank-math' ),
			'capability' => 'rank_math_sitemap',
			'folder'     => 'titles',
			'position'   => 99,
			'tabs'       => $tabs,
		));
	}

	/**
	 * Add post type tabs into sitemap option panel
	 *
	 * @param  array $tabs Hold tabs for optional panel.
	 * @return array
	 */
	public function post_type_settings( $tabs ) {
		$icons  = Helper::choices_post_type_icons();
		$things = array(
			'attachment' => esc_html__( 'attachments', 'rank-math' ),
			'product'    => esc_html__( 'your product pages', 'rank-math' ),
		);
		$urls   = array(
			'attachment' => KB::get( 'sitemap-media' ),
			'product'    => KB::get( 'sitemap-product' ),
		);

		// Post type label seprator.
		$tabs['p_types'] = array(
			'title' => esc_html__( 'Post Types:', 'rank-math' ),
			'type'  => 'seprator',
		);

		foreach ( Helper::get_accessible_post_types() as $post_type ) {
			$object      = get_post_type_object( $post_type );
			$sitemap_url = Router::get_base_url( $object->name . '-sitemap.xml' );
			$notice_end  = '</p><div class="rank-math-cmb-dependency hidden" data-relation="or"><span class="hidden" data-field="pt_' . $post_type . '_sitemap" data-comparison="=" data-value="on"></span></div></div>';
			$name        = strtolower( $object->label );

			/* translators: Post Type label */
			$thing = isset( $things[ $post_type ] ) ? $things[ $post_type ] : sprintf( __( 'single %s', 'rank-math' ), $name );
			$url   = isset( $urls[ $post_type ] ) ? $urls[ $post_type ] : in_array( $name, [ 'post', 'page' ] ) ? KB::get( "sitemap-{$name}" ) : '';

			$tabs[ 'sitemap-post-type-' . $object->name ] = array(
				'title'     => $object->label,
				'icon'      => isset( $icons[ $object->name ] ) ? $icons[ $object->name ] : $icons['default'],
				/* translators: %1$s: thing, %2$s: Learn more link. */
				'desc'      => sprintf( esc_html__( 'Sitemap settings for %1$s. %2$s.', 'rank-math' ), $thing, '<a href="' . $url . '" target="_blank">' . esc_html__( 'Learn more', 'rank-math' ) . '</a>' ),
				'post_type' => $object->name,
				'file'      => $this->directory . '/settings/post-types.php',
				/* translators: Post Type Sitemap Url */
				'after'     => $this->get_notice_start() . sprintf( esc_html__( 'Sitemap URL: %s', 'rank-math' ), '<a href="' . $sitemap_url . '" target="_blank">' . $sitemap_url . '</a>' ) . $notice_end,
			);

			if ( 'attachment' === $post_type ) {
				$tabs[ 'sitemap-post-type-' . $object->name ]['after'] = $this->get_notice_start() . esc_html__( 'Please note that this will add the attachment page URLs to the sitemap, not direct image URLs.', 'rank-math' ) . $notice_end;
			}
		}

		return $tabs;
	}

	/**
	 * Add taxonomy tabs into sitemap option panel
	 *
	 * @param  array $tabs Hold tabs for optional panel.
	 * @return array
	 */
	public function taxonomy_settings( $tabs ) {
		$icons = Helper::choices_taxonomy_icons();

		// Taxonomy label seprator.
		$tabs['t_types'] = array(
			'title' => esc_html__( 'Taxonomies:', 'rank-math' ),
			'type'  => 'seprator',
		);

		foreach ( Helper::get_accessible_taxonomies() as $taxonomy ) {
			if ( 'post_format' === $taxonomy->name ) {
				continue;
			}
			$sitemap_url = Router::get_base_url( $taxonomy->name . '-sitemap.xml' );
			$notice_end  = '</p><div class="rank-math-cmb-dependency hidden" data-relation="or"><span class="hidden" data-field="tax_' . $taxonomy->name . '_sitemap" data-comparison="=" data-value="on"></span></div></div>';

			switch ( $taxonomy->name ) {
				case 'product_cat':
				case 'product_tag':
					/* translators: Taxonomy singular label */
					$thing = sprintf( __( 'your product %s pages', 'rank-math' ), strtolower( $taxonomy->labels->singular_name ) );
					$url   = KB::get( "sitemap-$taxonomy->name" );
					break;

				default:
					/* translators: Taxonomy singular label */
					$thing = sprintf( __( '%s archives', 'rank-math' ), strtolower( $taxonomy->labels->singular_name ) );
					$name  = strtolower( $taxonomy->labels->name );
					$url   = in_array( $name, [ 'category', 'tags' ] ) ? KB::get( "sitemap-{$name}" ) : '';
			}

			$tabs[ 'sitemap-taxonomy-' . $taxonomy->name ] = array(
				'icon'     => isset( $icons[ $taxonomy->name ] ) ? $icons[ $taxonomy->name ] : $icons['default'],
				'title'    => $taxonomy->label,
				/* translators: %1$s: thing, %2$s: Learn more link. */
				'desc'     => sprintf( esc_html__( 'Sitemap settings for %1$s. %2$s.', 'rank-math' ), $thing, '<a href="' . $url . '" target="_blank">' . esc_html__( 'Learn more', 'rank-math' ) . '</a>' ),
				'taxonomy' => $taxonomy->name,
				'file'     => $this->directory . '/settings/taxonomies.php',
				/* translators: Taxonomy Sitemap Url */
				'after'    => $this->get_notice_start() . sprintf( esc_html__( 'Sitemap URL: %s', 'rank-math' ), '<a href="' . $sitemap_url . '" target="_blank">' . $sitemap_url . '</a>' ) . $notice_end,
			);
		}

		return $tabs;
	}

	/**
	 * Add Special seprator into sitemap option panel
	 *
	 * @param  array $tabs Hold tabs for optional panel.
	 * @return array
	 */
	public function special_seprator( $tabs ) {
		if ( Helper::is_module_active( 'news-sitemap' ) || Helper::is_module_active( 'video-sitemap' ) ) {
			$tabs['special'] = array(
				'title' => esc_html__( 'Special Sitemaps:', 'rank-math' ),
				'type'  => 'seprator',
			);
		}

		return $tabs;
	}

	/**
	 * Metabox API -----------------------------------------------------------
	 */

	/**
	 * Metabox settings in advanced tab.
	 *
	 * @param \CMB2 $cmb The CMB2 metabox object.
	 */
	public function metabox_settings_advanced( $cmb ) {
		$cmb->add_field( array(
			'id'         => 'rank_math_news_sitemap_genres',
			'type'       => 'text',
			'name'       => esc_html__( 'News Sitemap - Genres', 'rank-math' ),
			'desc'       => wp_kses_post( __( 'A comma-separated list of properties characterizing the content of the article, such as "PressRelease" or "UserGenerated." See <a href="https://support.google.com/news/publisher/answer/93992" target="_blank">Google News content properties</a> for a list of possible values.', 'rank-math' ) ),
			'default'    => 'Blog',
			'show_on_cb' => array( $this, 'show_on' ),
		) );

		$cmb->add_field( array(
			'id'         => 'rank_math_news_sitemap_keywords',
			'type'       => 'text',
			'name'       => esc_html__( 'News Sitemap - Keywords', 'rank-math' ),
			'desc'       => wp_kses_post( __( 'A comma-separated list of keywords describing the topic of the article. Keywords may be drawn from, but are not limited to, the list of existing Google News keywords. More information: <a href="https://support.google.com/news/publisher/answer/116037" target="_blank">Google News keywords</a>.', 'rank-math' ) ),
			'show_on_cb' => array( $this, 'show_on' ),
		) );

		$cmb->add_field( array(
			'id'         => 'rank_math_news_sitemap_stock_tickers',
			'type'       => 'text',
			'name'       => esc_html__( 'News Sitemap - Stock Tickers', 'rank-math' ),
			'desc'       => wp_kses_post( __( 'A comma-separated list of up to 5 stock tickers of the companies, mutual funds, or other financial entities that are the main subject of the article. Relevant primarily for business articles. More information: <a href="https://support.google.com/news/publisher/answer/74288" target="_blank">Creating a Google News Sitemap</a>.', 'rank-math' ) ),
			'show_on_cb' => array( $this, 'show_on' ),
		) );
	}

	/**
	 * Show field check callback.
	 *
	 * @param  CMB2_Field $field The current field.
	 * @return boolean
	 */
	public function show_on( $field ) {

		$news_sitemap_enabled = Helper::is_module_active( 'news-sitemap' );
		$is_post_type_news    = in_array( get_post_type(), (array) Helper::get_settings( 'sitemap.news_sitemap_post_type' ) );

		if ( $news_sitemap_enabled && $is_post_type_news ) {
			return true;
		}

		return false;
	}

	/**
	 * Adds new "exclude from sitemap" checkbox to media popup in the post editor.
	 *
	 * @param  array  $form_fields Default form fields.
	 * @param  object $post        Current post.
	 * @return array New form fields
	 */
	function media_popup_fields( $form_fields, $post ) {
		$exclude   = get_post_meta( $post->ID, 'rank_math_exclude_sitemap', true );
		$checkbox  = '<label><input type="checkbox" name="attachments[' . $post->ID . '][rank_math_media_exclude_sitemap]" ' . checked( $exclude, true, 0 ) . ' /> ';
		$checkbox .= esc_html__( 'Exclude this image from sitemap', 'rank-math' ) . '</label>';

		$form_fields['rank_math_exclude_sitemap'] = array( 'tr' => "\t\t<tr><td></td><td>$checkbox</td></tr>\n" );

		return $form_fields;
	}

	/**
	 * Saves new "exclude from sitemap" field as post meta to attachment.
	 *
	 * @param  array $post       Attachment ID.
	 * @param  array $attachment Attachment data.
	 * @return array Post
	 */
	function media_popup_fields_save( $post, $attachment ) {

		if ( isset( $attachment['rank_math_media_exclude_sitemap'] ) ) {
			update_post_meta( $post['ID'], 'rank_math_exclude_sitemap', true );
		} else {
			delete_post_meta( $post['ID'], 'rank_math_exclude_sitemap' );
		}

		Cache_Watcher::invalidate_post( $post['ID'] );

		return $post;
	}

	/**
	 * Adds html attribute data-sitemapexclude to img tag in the post editor
	 * when necessary.
	 *
	 * @param  string $html          Original img HTML tag.
	 * @param  int    $attachment_id Attachment ID.
	 * @return string New img HTML tag.
	 */
	public function media_popup_html( $html, $attachment_id ) {
		$post = get_post( $attachment_id );
		if ( Str::starts_with( 'image', $post->post_mime_type ) && get_post_meta( $attachment_id, 'rank_math_exclude_sitemap', true ) ) {
			$html = str_replace( ' class="', ' data-sitemapexclude="true" class="', $html );
		}
		return $html;
	}

	/**
	 * Get notice start html div
	 *
	 * @return string
	 */
	private function get_notice_start() {
		return '<div class="cmb-row notice notice-alt notice-info info inline" style="border:0;margin:15px 0 -10px;padding: 1px 12px"><p>';
	}
}
