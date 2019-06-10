<?php
/**
 * Dashboard help tab template.
 *
 * @package    RankMath
 * @subpackage RankMath\Admin
 */

use RankMath\KB;

if ( ! current_user_can( 'manage_options' ) ) {
	return;
}

include_once 'plugin-activation.php';
?>

<div class="two-col rank-math-box-help">

	<div class="col">

		<div class="rank-math-box rank-math-ui">

			<header>

				<h3><?php esc_html_e( 'Next steps&hellip;', 'rank-math' ); ?></h3>

			</header>

			<div class="rank-math-box-content">

				<ul class="rank-math-list-icon">

					<li>
						<span class="dashicons-before dashicons-admin-settings"></span>
						<div>
							<strong><?php esc_html_e( 'Setup Rank Math', 'rank-math' ); ?></strong>
							<p><a href="<?php KB::the( 'how-to-setup' ); ?>" target="_blank"><?php esc_html_e( 'How to Properly Setup Rank Math', 'rank-math' ); ?></a></p>
						</div>
					</li>

					<li>
						<span class="dashicons-before dashicons-share-alt2"></span>
						<div>
							<strong><?php esc_html_e( 'Import', 'rank-math' ); ?></strong>
							<p><a href="<?php KB::the( 'seo-import' ); ?>" target="_blank"><?php esc_html_e( 'How to Import Data from Your Previous SEO Plugin', 'rank-math' ); ?></a></p>
						</div>
					</li>

					<li>
						<span class="dashicons-before dashicons-editor-spellcheck"></span>
						<div>
							<strong><?php esc_html_e( 'Post Screen', 'rank-math' ); ?></strong>
							<p><a href="<?php KB::the( 'score-100' ); ?>" target="_blank"><?php esc_html_e( 'How to Make Your Posts Pass All the Tests', 'rank-math' ); ?></a></p>
						</div>
					</li>

				</ul>

				<a class="button button-primary button-xlarge" href="<?php KB::the( 'rm-kb' ); ?>" target="_blank"><?php esc_html_e( 'Visit Knowledge Base', 'rank-math' ); ?></a>

			</div>

		</div>

	</div>

	<div class="col">

		<div class="rank-math-box rank-math-ui">

			<header>

				<h3><?php esc_html_e( 'Product Support', 'rank-math' ); ?></h3>

			</header>

			<div class="rank-math-box-content">

				<ul class="rank-math-list-icon">

					<li>
						<span class="dashicons-before dashicons-book"></span>
						<div>
							<strong><?php esc_html_e( 'Online Documentation', 'rank-math' ); ?></strong>
							<p><a href="<?php KB::the( 'rm-kb' ); ?>" target="_blank"><?php esc_html_e( 'Understand all the capabilities of Rank Math', 'rank-math' ); ?></a></p>
						</div>
					</li>

					<li>
						<span class="dashicons-before dashicons-testimonial"></span>
						<div>
							<strong><?php esc_html_e( 'Browse FAQ\'s', 'rank-math' ); ?></strong>
							<p><a href="https://s.rankmath.com/documentation" target="_blank"><?php esc_html_e( 'Find answers to the most commonly asked questions.', 'rank-math' ); ?></a></p>
						</div>
					</li>

					<li>
						<span class="dashicons-before dashicons-sos"></span>
						<div>
							<strong><?php esc_html_e( 'Ticket Support', 'rank-math' ); ?></strong>
							<p><a href="<?php KB::the( 'rm-support' ); ?>" target="_blank"><?php esc_html_e( 'Direct help from our qualified support team', 'rank-math' ); ?></a></p>
						</div>
					</li>

				</ul>

				<a class="button button-primary button-xlarge" href="<?php KB::the( 'rm-support' ); ?>" target="_blank"><?php esc_html_e( 'Visit Support Center', 'rank-math' ); ?></a>

			</div>

		</div>

	</div>

</div>
