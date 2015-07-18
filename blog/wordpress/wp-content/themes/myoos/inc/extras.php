<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package myoos
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function myoos_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	return $classes;
}
add_filter( 'body_class', 'myoos_body_classes' );

if ( version_compare( $GLOBALS['wp_version'], '4.1', '<' ) ) :
	/**
	 * Filters wp_title to print a neat <title> tag based on what is being viewed.
	 *
	 * @param string $title Default title text for current view.
	 * @param string $sep Optional separator.
	 * @return string The filtered title.
	 */
	function myoos_wp_title( $title, $sep ) {
		if ( is_feed() ) {
			return $title;
		}

		global $page, $paged;

		// Add the blog name.
		$title .= get_bloginfo( 'name', 'display' );

		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) ) {
			$title .= " $sep $site_description";
		}

		// Add a page number if necessary.
		if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
			$title .= " $sep " . sprintf( esc_html__( 'Page %s', 'myoos' ), max( $paged, $page ) );
		}

		return $title;
	}
	add_filter( 'wp_title', 'myoos_wp_title', 10, 2 );

	/**
	 * Title shim for sites older than WordPress 4.1.
	 *
	 * @link https://make.wordpress.org/core/2014/10/29/title-tags-in-4-1/
	 * @todo Remove this function when WordPress 4.3 is released.
	 */
	function myoos_render_title() {
		?>
		<title><?php wp_title( '|', true, 'right' ); ?></title>
		<?php
	}
	add_action( 'wp_head', 'myoos_render_title' );
endif;


if ( ! function_exists( 'sparkling_featured_slider' ) ) :
/**
 * Featured image slider, displayed on front page for static page and blog
 */
function sparkling_featured_slider() {
	if ( is_front_page() && of_get_option( 'myoos_slider_checkbox' ) == 1 ) {
		echo '<div class="flexslider">';
		echo '	<ul class="slides">';

        $count = of_get_option( 'myoos_slide_number' );
        $slidecat =of_get_option( 'myoos_slide_categories' );

		$query = new WP_Query( array( 'cat' =>$slidecat,'posts_per_page' =>$count ) );
		if ($query->have_posts()) :
			while ($query->have_posts()) : $query->the_post();

				if ( (function_exists( 'has_post_thumbnail' )) && ( has_post_thumbnail() ) ) :
					echo '<li><a href="'. get_permalink() .'">';
					echo get_the_post_thumbnail();
					echo '<div class="flex-caption">';
					if ( get_the_title() != '' ) echo '<h2 class="entry-title">'. get_the_title().'</h2>';
					if ( get_the_excerpt() != '' ) echo '<div class="excerpt">' . get_the_excerpt() .'</div>';
					echo '</div>';
					echo '</a></li>';
				endif;

			endwhile;
		endif;
		echo '	</ul>';
		echo '</div>';
	}
}
endif;



/*
 * This one shows/hides the an option when a checkbox is clicked.
 */
add_action( 'optionsframework_custom_scripts', 'optionsframework_custom_scripts' );


function optionsframework_custom_scripts() { ?>

<script type="text/javascript">
jQuery(document).ready(function() {

  jQuery('#myoos_slider_checkbox').click(function() {
      jQuery('#section-myoos_slide_categories').fadeToggle(400);
  });

  if (jQuery('#myoos_slider_checkbox:checked').val() !== undefined) {
    jQuery('#section-myoos_slide_categories').show();
  }

  jQuery('#myoos_slider_checkbox').click(function() {
      jQuery('#section-myoos_slide_number').fadeToggle(400);
  });

  if (jQuery('#myoos_slider_checkbox:checked').val() !== undefined) {
    jQuery('#section-myoos_slide_number').show();
  }

});
</script>


<?php
}		
