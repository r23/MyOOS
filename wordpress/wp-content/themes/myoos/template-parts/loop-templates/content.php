<?php
/**
 * Post rendering content according to caller of get_template_part.
 *
 * @package cpschool
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>
<div <?php cpschool_class( 'entry-col', 'entry-col' ); ?>>
	<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
		<header <?php cpschool_class( 'entry-header', 'entry-header' ); ?>>
			<?php
			$content_indicator = false;
			$icon_class        = cpschool_get_post_type_icon_class( get_post_type() );
			if ( $icon_class ) {
				$post_type_object  = get_post_type_object( get_post_type() );
				$label             = $post_type_object->labels->singular_name;
				$content_indicator = '<span class="entry-type-idicator cps-icon ' . $icon_class . '" aria-hidden="true" title="' . sprintf( esc_html__( 'This is a "%s"', 'cpschool' ), $label ) . '"></span><span class="screen-reader-text">' . sprintf( esc_html__( '(This is a "%s")', 'cpschool' ), $label ) . '</span>';
			}
			the_title(
				sprintf( '<h2 class="entry-title">%s<a href="%s" rel="bookmark">', $content_indicator, esc_url( get_permalink() ) ),
				'</a></h2>'
			);
			?>

			<?php
			$cpschool_get_post_meta = cpschool_get_post_meta( get_the_ID(), is_singular() );
			if ( $cpschool_get_post_meta ) {
				?>
				<div class="entry-meta">
					<?php echo cpschool_get_post_meta( get_the_ID(), is_singular() ); ?>
				</div><!-- .entry-meta -->
				<?php
			}
			?>
		</header><!-- .entry-header -->

		<?php
		if ( has_post_thumbnail() ) {
			if ( ( get_theme_mod( 'entries_lists_featured_image_style' ) != 'disabled' && ( is_archive() || is_home() || is_search() ) ) || is_customize_preview() ) {
				?>
			<div <?php cpschool_class( 'entry-featured-image', 'entry-featured-image' ); ?>>
				<?php echo get_the_post_thumbnail( $post->ID, 'large' ); ?>
			</div>
				<?php
			}
		}
		?>

		<?php
		$content_type = get_theme_mod( 'entries_lists_content_type' );
		if ( $content_type != 'no-content' ) {
			?>
			<div class="entry-content">
				<?php
				if ( $content_type == 'content' ) {
					the_content();
				} else {
					the_excerpt();
				}
				?>
			</div><!-- .entry-content -->
			<?php
		}
		?>

		<?php
		/*
		<footer class="entry-footer">
			<?php //cpschool_entry_footer(); ?>
		</footer><!-- .entry-footer -->
		*/
		?>
	</article><!-- #post-## -->
</div>
