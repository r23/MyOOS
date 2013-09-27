<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package myoos
 */

get_header(); ?>

<div class="container">
	<div class="row" role="main">
        <div class="col-md-8">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'myoos' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
			</header><!-- .page-header -->

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'search' ); ?>

			<?php endwhile; ?>

			<?php myoos_content_nav( 'nav-below' ); ?>

		<?php else : ?>

			<?php get_template_part( 'no-results', 'search' ); ?>

		<?php endif; ?>
        </div>
		<div class="col-md-4">
			<?php get_sidebar(); ?>
		</div>		
		
	</div><!-- #content -->
</div><!-- #primary -->

<?php get_footer(); ?>