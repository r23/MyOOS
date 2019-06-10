<?php
/**
 * The serp checklist functionality.
 *
 * @since      0.9.0
 * @package    RankMath
 * @subpackage RankMath\Admin
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\Admin;

use RankMath\KB;
use RankMath\CMB2;
use RankMath\Helper;
use RankMath\Traits\Hooker;

defined( 'ABSPATH' ) || exit;

/**
 * Serp_Checklist class.
 */
class Serp_Checklist {

	use Hooker;

	/**
	 * Locale strings.
	 *
	 * @var array
	 */
	private $locale = [];

	/**
	 * Display SERP checklist.
	 */
	public function display() {
		$method = 'display_' . CMB2::current_object_type() . '_list';
		?>
		<div id="rank-math-serp-checklist" class="rank-math-serp-checklist">
			<?php
			foreach ( $this->get_groups() as $group => $state ) :
				$list = $this->$method();
				if ( isset( $list[ $group ] ) ) :
					?>
				<div id="rank-math-serp-group-<?php echo $group; ?>" class="rank-math-serp-group state-<?php echo $state; ?>" data-id="<?php echo $group; ?>">
					<div class="group-handle">
						<span class="group-status"></span>
						<h4><?php echo $this->get_heading( $group ); ?></h4>
						<button type="button" class="group-handlediv" aria-expanded="true"><span class="screen-reader-text"><?php printf( esc_html__( 'Toggle tests: %s', 'rank-math' ), $this->get_heading( $group ) ); // phpcs:ignore ?></span><span class="toggle-indicator" aria-hidden="true"></span></button>
					</div>
					<ul>
						<?php $this->print_list( $list[ $group ] ); ?>
					</ul>
				</div>
					<?php
				endif;
			endforeach;
			?>
		</div>
		<?php
		Helper::add_json( 'assessor', [ '__' => $this->locale ] );
	}

	/**
	 * Display SERP checklist for posts.
	 */
	private function display_post_list() {
		$is_connected = Helper::is_site_connected();

		/* translators: link to registration screen */
		$power_words_not_connected = sprintf( esc_html__( 'Please connect your %s to calculate the Power Words used.', 'rank-math' ), '<a href="' . Helper::get_connect_url() . '" target="_blank">Rank Math account</a>' );
		/* translators: link to registration screen */
		$sentiments_not_connected = sprintf( esc_html__( 'Please connect your %s to calculate the Sentiments of the content.', 'rank-math' ), '<a href="' . Helper::get_connect_url() . '" target="_blank">Rank Math account</a>' );

		$tests = [
			'basic'               => [
				'keywordInTitle'           => [
					'ok'      => esc_html__( 'Hurray! You\'re using Focus Keyword in the SEO Title.', 'rank-math' ),
					'fail'    => esc_html__( 'Focus Keyword does not appear in the SEO title.', 'rank-math' ),
					'empty'   => esc_html__( 'Add Focus Keyword to the SEO title.', 'rank-math' ),
					'tooltip' => esc_html__( 'Make sure the focus keyword appears in the SEO post title too.', 'rank-math' ),
					'score'   => 'en' === substr( get_locale(), 0, 2 ) ? 30 : 32,
				],
				'keywordInMetaDescription' => [
					'ok'      => esc_html__( 'Focus Keyword used inside SEO Meta Description.', 'rank-math' ),
					'fail'    => esc_html__( 'Focus Keyword not found in your SEO Meta Description.', 'rank-math' ),
					'empty'   => esc_html__( 'Add Focus Keyword to your SEO Meta Description.', 'rank-math' ),
					'tooltip' => esc_html__( 'Make sure the focus keyword appears in the SEO description too.', 'rank-math' ),
					'score'   => 2,
				],
				'keywordInPermalink'       => [
					'ok'      => esc_html__( 'Focus Keyword used in the URL.', 'rank-math' ),
					'fail'    => esc_html__( 'Focus Keyword not found in the URL.', 'rank-math' ),
					'empty'   => esc_html__( 'Use Focus Keyword in the URL.', 'rank-math' ),
					'tooltip' => esc_html__( 'Include the focus keyword in the slug (permalink) of this post.', 'rank-math' ),
					'score'   => 5,
				],
				'keywordIn10Content'       => [
					'ok'      => esc_html__( 'Focus Keyword appears in the first 10% of the content.', 'rank-math' ),
					'fail'    => esc_html__( 'Focus Keyword doesn\'t appear at the beginning of your content.', 'rank-math' ),
					'empty'   => esc_html__( 'Use Focus Keyword at the beginning of your content.', 'rank-math' ),
					'tooltip' => esc_html__( 'The first 10% of the content should contain the Focus Keyword preferably at the beginning.', 'rank-math' ),
					'score'   => 3,
				],
				'keywordInContent'         => [
					'ok'      => esc_html__( 'Focus Keyword found in the content.', 'rank-math' ),
					'fail'    => esc_html__( 'Focus Keyword doesn\'t appear in the content.', 'rank-math' ),
					'empty'   => esc_html__( 'Use Focus Keyword in the content.', 'rank-math' ),
					'tooltip' => esc_html__( 'It is recommended to make the focus keyword appear in the post content too.', 'rank-math' ),
					'score'   => 3,
				],
				'lengthContent'            => [
					'ok'      => esc_html__( 'Your content is {0} words long. Good job!', 'rank-math' ),
					'fail'    => esc_html__( 'Your content is {0} words long. Consider using at least 600 words.', 'rank-math' ),
					/* translators: link to kb article */
					'empty'   => sprintf( esc_html__( 'Content should be %s long.', 'rank-math' ), '<a href="' . KB::get( 'content-length' ) . '" target="_blank">600-2500 words</a>' ),
					'tooltip' => esc_html__( 'Minimum recommended content length should be 600 words.', 'rank-math' ),
					'score'   => 8,
				],
			],
			'advanced'            => [
				'lengthPermalink'      => [
					'ok'      => esc_html__( 'URL is {0} characters long. Kudos!', 'rank-math' ),
					'fail'    => esc_html__( 'URL is {0} characters long. Considering shortening it.', 'rank-math' ),
					'empty'   => esc_html__( 'URL unavailable. Add a short URL.', 'rank-math' ),
					'tooltip' => esc_html__( 'Permalink should be at most 75 characters long.', 'rank-math' ),
					'score'   => 4,
				],
				'keywordInSubheadings' => [
					'ok'      => esc_html__( 'Focus Keyword found in the subheading(s).', 'rank-math' ),
					'fail'    => esc_html__( 'Focus Keyword not found in subheading(s) like H2, H3, H4, etc..', 'rank-math' ),
					'empty'   => esc_html__( 'Use Focus Keyword in subheading(s) like H2, H3, H4, etc..', 'rank-math' ),
					'tooltip' => esc_html__( 'It is recommended to add the focus keyword as part of one or more subheadings in the content.', 'rank-math' ),
					'score'   => 3,
				],
				'keywordInImageAlt'    => [
					'ok'      => esc_html__( 'Focus Keyword found in image alt attribute(s).', 'rank-math' ),
					'fail'    => esc_html__( 'Focus Keyword not found in image alt attribute(s).', 'rank-math' ),
					'empty'   => esc_html__( 'Add an image with your Focus Keyword as alt text.', 'rank-math' ),
					'gallery' => esc_html__( 'We detected a gallery in your content & assuming that you added Focus Keyword in alt in at least one of the gallery images.', 'rank-math' ),
					'tooltip' => esc_html__( 'It is recommended to add the focus keyword in the alt attribute of one or more images.', 'rank-math' ),
					'score'   => 2,
				],
				'linksHasExternals'    => [
					'ok'      => esc_html__( 'Great! You are linking to external resources.', 'rank-math' ),
					'fail'    => esc_html__( 'No outbound links were found. Link out to external resources.', 'rank-math' ),
					'empty'   => esc_html__( 'Link out to external resources.', 'rank-math' ),
					'tooltip' => esc_html__( 'It helps visitors read more about a topic and prevents pogosticking.', 'rank-math' ),
					'score'   => 4,
				],
				'linksNotAllExternals' => [
					'ok'      => esc_html__( 'At least one external link with DoFollow found in your content.', 'rank-math' ),
					'fail'    => esc_html__( 'We found {0} outbound links in your content and all of them are nofollow.', 'rank-math' ),
					'empty'   => esc_html__( 'Add DoFollow links pointing to external resources.', 'rank-math' ),
					'tooltip' => esc_html__( 'PageRank Sculpting no longer works. Your posts should have a mix of nofollow and DoFollow links.', 'rank-math' ),
					'score'   => 2,
				],
				'keywordDensity'       => [
					'ok'      => esc_html__( 'Keyword Density is {0}, the Focus Keyword and combination appears {1} times.', 'rank-math' ),
					'fail'    => esc_html__( 'Keyword Density is {0}, the Focus Keyword and combination appears {1} times.', 'rank-math' ),
					'empty'   => esc_html__( 'Keyword Density is {0}. Aim for around 1% Keyword Density.', 'rank-math' ),
					'tooltip' => esc_html__( 'There is no ideal keyword density percentage, but it should not be too high. The most important thing is to keep the copy natural.', 'rank-math' ),
					'score'   => 6,
				],
				'linksHasInternal'     => [
					'ok'      => esc_html__( 'You are linking to other resources on your website which is great.', 'rank-math' ),
					'fail'    => esc_html__( 'We couldn\'t find any internal links in your content.', 'rank-math' ),
					'empty'   => esc_html__( 'Add internal links in your content.', 'rank-math' ),
					'tooltip' => esc_html__( 'Internal links decrease your bounce rate and improve SEO.', 'rank-math' ),
					'score'   => 5,
				],
				'keywordNotUsed'       => [
					'ok'      => esc_html__( 'You haven\'t used this Focus Keyword before.', 'rank-math' ),
					/* translators: focus keyword link */
					'fail'    => sprintf( esc_html__( 'You have %s this Focus Keyword.', 'rank-math' ), '<a target="_blank" class="focus-keyword-link" href="' . admin_url( 'edit.php?focus_keyword=%focus_keyword%&post_type=%post_type%' ) . '">' . __( 'already used', 'rank-math' ) . '</a>' ),
					'empty'   => esc_html__( 'Set a Focus Keyword for this content.', 'rank-math' ),
					'looking' => esc_html__( 'We are searching in database.', 'rank-math' ),
					'score'   => 0,
				],
			],
			'title-readability'   => [
				'titleStartWithKeyword' => [
					'ok'      => esc_html__( 'Focus Keyword used at the beginning of SEO title.', 'rank-math' ),
					'fail'    => esc_html__( 'Focus Keyword doesn\'t appear at the beginning of SEO title.', 'rank-math' ),
					'empty'   => esc_html__( 'Use the Focus Keyword near the beginning of SEO title.', 'rank-math' ),
					'tooltip' => esc_html__( 'The SEO page title should contain the Focus Keyword preferably at the beginning.', 'rank-math' ),
					'score'   => 3,
				],
				'titleSentiment'        => [
					'ok'      => $is_connected ? esc_html__( 'Your title has a positive or a negative sentiment.', 'rank-math' ) : $sentiments_not_connected,
					/* translators: link to kb article */
					'fail'    => $is_connected ? sprintf( __( 'Your title doesn\'t contain a %s word.', 'rank-math' ), '<a href="' . KB::get( 'sentiments' ) . '" target="_blank">positive or a negative sentiment</a>' ) : $sentiments_not_connected,
					'empty'   => esc_html__( 'Titles with positive or negative sentiment work best for higher CTR.', 'rank-math' ),
					'tooltip' => esc_html__( 'Headlines with a strong emotional sentiment (positive or negative) tend to receive more clicks.', 'rank-math' ),
					'score'   => 1,
				],
				'titleHasPowerWords'    => [
					'ok'      => $is_connected ? esc_html__( 'Your title contains {0} power word(s). Booyah!', 'rank-math' ) : $power_words_not_connected,
					/* translators: link to kb article */
					'fail'    => $is_connected ? sprintf( esc_html__( 'Your title doesn\'t contain a %s. Add at least one.', 'rank-math' ), '<a href="https://sumo.com/stories/power-words" target="_blank">power word</a>' ) : $power_words_not_connected,
					/* translators: link to kb article */
					'empty'   => sprintf( esc_html__( 'Add %s to your title to increase CTR.', 'rank-math' ), '<a href="https://sumo.com/stories/power-words" target="_blank">power words</a>' ),
					/* translators: link to registration screen */
					'tooltip' => esc_html__( 'Power Words are tried-and-true words that copywriters use to attract more clicks.', 'rank-math' ),
					'score'   => 1,
				],
				'titleHasNumber'        => [
					'ok'      => esc_html__( 'You are using a number in your SEO title.', 'rank-math' ),
					'fail'    => esc_html__( 'Your SEO title doesn\'t contain a number.', 'rank-math' ),
					'empty'   => esc_html__( 'Add a number to your title to improve CTR.', 'rank-math' ),
					'tooltip' => esc_html__( 'Headlines with numbers are 36% more likely to generate clicks, according to research by Conductor.', 'rank-math' ),
					'score'   => 1,
				],
			],
			'content-readability' => [
				'contentHasTOC'             => [
					/* translators: link to kb article */
					'ok'      => sprintf( __( 'You seem to be using a <a href="%s" target="_blank">Table of Contents plugin</a> to break-down your text.', 'rank-math' ), KB::get( 'toc' ) ),
					/* translators: link to kb article */
					'fail'    => sprintf( __( 'You don\'t seem to be using a <a href="%s" target="_blank">Table of Contents plugin</a>.', 'rank-math' ), KB::get( 'toc' ) ),
					'empty'   => esc_html__( 'Use Table of Content to break-down your text.', 'rank-math' ),
					'tooltip' => esc_html__( ' Table of Contents help break down content into smaller, digestible chunks. It makes reading easier which in turn results in better rankings.', 'rank-math' ),
					'score'   => 2,
				],
				'calculateFleschReading'    => [
					/* translators: Link to kb article */
					'ok'      => esc_html__( 'Your Flesch Readability score is {1} and is regarded as {0}', 'rank-math' ),
					'fail'    => esc_html__( 'Your Flesch Readability score is {1} and is regarded as {0}.', 'rank-math' ),
					'empty'   => esc_html__( 'Add some content to calculate Flesch Readability score.', 'rank-math' ),
					'tooltip' => esc_html__( 'Try to make shorter sentences, using less difficult words to improve readability.', 'rank-math' ),
					'score'   => 6,
				],
				'contentHasShortParagraphs' => [
					'ok'      => esc_html__( 'Kudos! You are using short paragraphs.', 'rank-math' ),
					'fail'    => esc_html__( 'At least one paragraph is long. Consider using short paragraphs.', 'rank-math' ),
					'empty'   => esc_html__( 'Add short and concise paragraphs for better readability and UX.', 'rank-math' ),
					'tooltip' => esc_html__( 'Short paragraphs are easier to read and more pleasing to the eye. Long paragraphs scare the visitor, and they might result to SERPs looking for better readable content.', 'rank-math' ),
					'score'   => 3,
				],
				'contentHasAssets'          => [
					'ok'      => esc_html__( 'Your content contains images and/or video(s).', 'rank-math' ),
					'fail'    => esc_html__( 'You are not using rich media like images or videos.', 'rank-math' ),
					'empty'   => esc_html__( 'Add a few images and/or videos to make your content appealing.', 'rank-math' ),
					'tooltip' => esc_html__( 'Content with images and/or video feels more inviting to users. It also helps supplement your textual content.', 'rank-math' ),
					'score'   => 6,
				],
			],
		];
		$tests = $this->do_filter( 'researches/tests', $tests, 'post' );
		Helper::add_json(
			'assessor',
			[
				'researchesTests' => array_merge( $tests['basic'], $tests['advanced'], $tests['title-readability'], $tests['content-readability'] ),
				'imgAlt'          => Helper::get_settings( 'general.add_img_alt' ) && Helper::get_settings( 'general.img_alt_format' ) ? trim( Helper::get_settings( 'general.img_alt_format' ) ) : false,
			]
		);

		return $tests;
	}

	/**
	 * Display SERP checklist for terms.
	 */
	private function display_term_list() {
		$tests = [
			'basic'    => [
				'keywordInTitle'           => [
					'ok'      => esc_html__( 'Hurray! You\'re using Focus Keyword in the SEO Title.', 'rank-math' ),
					'fail'    => esc_html__( 'Focus Keyword does not appear in the SEO title.', 'rank-math' ),
					'empty'   => esc_html__( 'Add Focus Keyword to the SEO title.', 'rank-math' ),
					'tooltip' => esc_html__( 'Make sure the focus keyword appears in the SEO Term too.', 'rank-math' ),
					'score'   => 40,
				],
				'keywordInMetaDescription' => [
					'ok'      => esc_html__( 'Focus Keyword used inside SEO Meta Description.', 'rank-math' ),
					'fail'    => esc_html__( 'Focus Keyword not found in your SEO Meta Description.', 'rank-math' ),
					'empty'   => esc_html__( 'Add Focus Keyword to your SEO Meta Description.', 'rank-math' ),
					'tooltip' => esc_html__( 'Make sure the focus keyword appears in the SEO description too.', 'rank-math' ),
					'score'   => 20,
				],
				'keywordInPermalink'       => [
					'ok'      => esc_html__( 'Focus Keyword used in the URL.', 'rank-math' ),
					'fail'    => esc_html__( 'Focus Keyword not found in the URL.', 'rank-math' ),
					'empty'   => esc_html__( 'Use Focus Keyword in the URL.', 'rank-math' ),
					'tooltip' => esc_html__( 'Include the focus keyword in the slug (permalink) of this term.', 'rank-math' ),
					'score'   => 30,
				],
			],
			'advanced' => [
				'titleStartWithKeyword' => [
					'ok'      => esc_html__( 'Focus Keyword used at the beginning of SEO title.', 'rank-math' ),
					'fail'    => esc_html__( 'Focus Keyword doesn\'t appear at the beginning of SEO title.', 'rank-math' ),
					'empty'   => esc_html__( 'Use the Focus Keyword near the beginning of SEO title.', 'rank-math' ),
					'tooltip' => esc_html__( 'The SEO Term title should contain the Focus Keyword preferably at the beginning.', 'rank-math' ),
					'score'   => 10,
				],
				'keywordNotUsed'        => [
					'ok'    => esc_html__( 'You haven\'t used this Focus Keyword before.', 'rank-math' ),
					'fail'  => esc_html__( 'You have already used this Focus Keyword.', 'rank-math' ),
					'empty' => esc_html__( 'Set a Focus Keyword for this content.', 'rank-math' ),
					'score' => 0,
				],
			],
		];

		$tests = $this->do_filter( 'researches/tests', $tests, 'term' );
		Helper::add_json(
			'assessor',
			[
				'researchesTests' => array_merge( $tests['basic'], $tests['advanced'] ),
			]
		);

		return $tests;
	}

	/**
	 * Display SERP checklist for users.
	 */
	private function display_user_list() {
		$tests = [
			'basic'    => [
				'keywordInTitle'           => [
					'ok'      => esc_html__( 'Hurray! You\'re using Focus Keyword in the SEO Title.', 'rank-math' ),
					'fail'    => esc_html__( 'Focus Keyword does not appear in the SEO title.', 'rank-math' ),
					'empty'   => esc_html__( 'Add Focus Keyword to the SEO title.', 'rank-math' ),
					'tooltip' => esc_html__( 'Make sure the focus keyword appears in the SEO Author too.', 'rank-math' ),
					'score'   => 40,
				],
				'keywordInMetaDescription' => [
					'ok'      => esc_html__( 'Focus Keyword used inside SEO Meta Description.', 'rank-math' ),
					'fail'    => esc_html__( 'Focus Keyword not found in your SEO Meta Description.', 'rank-math' ),
					'empty'   => esc_html__( 'Add Focus Keyword to your SEO Meta Description.', 'rank-math' ),
					'tooltip' => esc_html__( 'Make sure the focus keyword appears in the SEO description too.', 'rank-math' ),
					'score'   => 20,
				],
				'keywordInPermalink'       => [
					'ok'      => esc_html__( 'Focus Keyword used in the URL.', 'rank-math' ),
					'fail'    => esc_html__( 'Focus Keyword not found in the URL.', 'rank-math' ),
					'empty'   => esc_html__( 'Use Focus Keyword in the URL.', 'rank-math' ),
					'tooltip' => esc_html__( 'Include the focus keyword in the slug (permalink) of this author.', 'rank-math' ),
					'score'   => 30,
				],
			],
			'advanced' => [
				'titleStartWithKeyword' => [
					'ok'      => esc_html__( 'Focus Keyword used at the beginning of SEO title.', 'rank-math' ),
					'fail'    => esc_html__( 'Focus Keyword doesn\'t appear at the beginning of SEO title.', 'rank-math' ),
					'empty'   => esc_html__( 'Use the Focus Keyword near the beginning of SEO title.', 'rank-math' ),
					'tooltip' => esc_html__( 'The SEO Author title should contain the Focus Keyword preferably at the beginning.', 'rank-math' ),
					'score'   => 10,
				],
				'keywordNotUsed'        => [
					'ok'    => esc_html__( 'You haven\'t used this Focus Keyword before.', 'rank-math' ),
					'fail'  => esc_html__( 'You have already used this Focus Keyword.', 'rank-math' ),
					'empty' => esc_html__( 'Set a Focus Keyword for this content.', 'rank-math' ),
					'score' => 0,
				],
			],
		];

		$tests = $this->do_filter( 'researches/tests', $tests, 'user' );
		Helper::add_json(
			'assessor',
			[
				'researchesTests' => array_merge( $tests['basic'], $tests['advanced'] ),
			]
		);

		return $tests;
	}

	/**
	 * Print checklist.
	 *
	 * @param array $list Array of checklist to print.
	 */
	private function print_list( $list ) {
		$primary = [
			'keywordInTitle',
			'keywordInMetaDescription',
			'keywordInPermalink',
			'keywordIn10Content',
			'keywordInImageAlt',
			'keywordNotUsed',
			'titleStartWithKeyword',
		];

		foreach ( $list as $id => $item ) :
			if ( $this->is_invalid( $id ) ) {
				continue;
			}

			$this->add_locale( $id, $item );
			?>
			<li class="seo-check-<?php echo $id; ?> test-fail<?php echo in_array( $id, $primary, true ) ? ' is-primary' : ''; ?>">
				<span class="seo-check-text"><?php echo str_replace( [ '{0}', '{1}' ], '_', $item['fail'] ); ?></span>
				<?php echo isset( $item['tooltip'] ) ? Admin_Helper::get_tooltip( $item['tooltip'] ) : ''; ?>
			</li>
			<?php
		endforeach;
	}

	/**
	 * If invalid to print.
	 *
	 * @param string $id List id.
	 *
	 * @return bool
	 */
	private function is_invalid( $id ) {
		return 'en' !== substr( get_locale(), 0, 2 ) && in_array( $id, [ 'titleSentiment', 'titleHasPowerWords' ], true );
	}

	/**
	 * Add locale
	 *
	 * @param string $id   List id.
	 * @param array  $item List info.
	 */
	private function add_locale( $id, $item ) {
		foreach ( [ 'ok', 'fail', 'empty', 'looking', 'gallery' ] as $key ) {
			if ( ! empty( $item[ $key ] ) ) {
				$this->locale[ $id . '.' . $key ] = $item[ $key ];
			}
		}
	}

	/**
	 * Get heading of the checklist heading.
	 *
	 * @param  string $id ID of the checklist section.
	 * @return string
	 */
	private function get_heading( $id ) {
		$hash = [
			'basic'               => esc_html__( 'Basic SEO', 'rank-math' ),
			'advanced'            => esc_html__( 'Additional', 'rank-math' ),
			'title-readability'   => esc_html__( 'Title Readability', 'rank-math' ),
			'content-readability' => esc_html__( 'Content Readability', 'rank-math' ),
		];

		return isset( $hash[ $id ] ) ? $hash[ $id ] : esc_html__( 'Unkown', 'rank-math' );
	}

	/**
	 * Get checklist groups.
	 */
	private function get_groups() {
		$defaults = [
			'basic'               => 'open',
			'advanced'            => 'open',
			'title-readability'   => 'open',
			'content-readability' => 'open',
		];
		$groups   = get_user_meta( get_current_user_id(), 'rank_math_metabox_checklist_layout', true );

		return $groups ? array_merge( $defaults, $groups ) : $defaults;
	}
}
