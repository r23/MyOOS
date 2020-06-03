<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package cpschool
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cpschool_post_nav' ) ) {
	/**
	 * Display navigation to next/previous post when applicable.
	 */
	function cpschool_post_nav() {
		$disallowed_post_types = apply_filters( 'cpschool_disallowed_post_types_for_post_nav', array( 'page' ) );
		// Check whether the post type is allowed to output post meta.
		if ( in_array( get_post_type( get_post()->ID ), $disallowed_post_types, true ) ) {
			return;
		}

		$navigation_enabled = cpschool_get_content_theme_mod( 'navigation', get_post_type( get_the_ID() ), true );
		if ( ! $navigation_enabled ) {
			return;
		}

		// Don't print empty markup if there's nowhere to navigate.
		$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
		$next     = get_adjacent_post( false, '', false );

		if ( ! $next && ! $previous ) {
			return;
		}
		?>
		<nav class="container navigation post-navigation">
			<h2 class="sr-only"><?php esc_html_e( 'Post navigation', 'cpschool' ); ?></h2>
			<div class="row nav-links justify-content-between">
				<?php
				if ( get_previous_post_link() ) {
					previous_post_link( '<span class="nav-previous">%link</span>', _x( '<i aria-hidden="true" class="cps-icon cps-icon-chevron-left"></i>%title', 'Previous post link', 'cpschool' ) );
				}
				if ( get_next_post_link() ) {
					next_post_link( '<span class="nav-next">%link</span>', _x( '%title<i aria-hidden="true" class="cps-icon cps-icon-chevron-right"></i>', 'Next post link', 'cpschool' ) );
				}
				?>
			</div><!-- .nav-links -->
		</nav><!-- .navigation -->
		<?php
	}
}

if ( ! function_exists( 'cpschool_class' ) ) {
	/**
	 * Add site info hook to WP hook library.
	 */
	function cpschool_class( $context, $classes = array(), $return = false ) {
		if ( ! is_array( $classes ) ) {
			$classes = explode( ' ', $classes );
		}

		$classes = apply_filters( 'cpschool_class', $classes, $context );
		$classes = array_map( 'esc_attr', $classes );

		if ( $classes && ! $return ) {
			echo 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
		}

		return $classes;
	}
}

if ( ! function_exists( 'cpschool_posted_on' ) ) {
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function cpschool_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s"> (%4$s) </time>';
		}
		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);
		$posted_on   = apply_filters(
			'cpschool_posted_on',
			sprintf(
				'<span class="posted-on">%1$s <a href="%2$s" rel="bookmark">%3$s</a></span>',
				esc_html_x( 'Posted on', 'post date', 'cpschool' ),
				esc_url( get_permalink() ),
				apply_filters( 'cpschool_posted_on_time', $time_string )
			)
		);
		$byline      = apply_filters(
			'cpschool_posted_by',
			sprintf(
				'<span class="byline"> %1$s<span class="author vcard"><a class="url fn n" href="%2$s"> %3$s</a></span></span>',
				$posted_on ? esc_html_x( 'by', 'post author', 'cpschool' ) : esc_html_x( 'Posted by', 'post author', 'cpschool' ),
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				esc_html( get_the_author() )
			)
		);
		echo $posted_on . $byline; // WPCS: XSS OK.
	}
}

if ( ! function_exists( 'cpschool_entry_footer' ) ) {
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function cpschool_entry_footer() {
		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link( esc_html__( 'Leave a comment', 'cpschool' ), esc_html__( '1 Comment', 'cpschool' ), esc_html__( '% Comments', 'cpschool' ) );
			echo '</span>';
		}
		edit_post_link(
			sprintf(
				/* translators: %s: Name of current post */
				esc_html__( 'Edit %s', 'cpschool' ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
}

if ( ! function_exists( 'cpschool_categorized_blog' ) ) {
	/**
	 * Returns true if a blog has more than 1 category.
	 *
	 * @return bool
	 */
	function cpschool_categorized_blog() {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories(
			array(
				'fields'     => 'ids',
				'hide_empty' => 1,
				// We only need to know if there is more than one category.
				'number'     => 2,
			)
		);
		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );
		if ( $all_the_cool_cats > 1 ) {
			// This blog has more than 1 category so components_categorized_blog should return true.
			return true;
		} else {
			// This blog has only 1 category so components_categorized_blog should return false.
			return false;
		}
	}
}

if ( ! function_exists( 'cpschool_get_post_meta' ) ) {
	/**
	 * Get the post meta.
	 * Based on function from Twenty Twenty Theme.
	 *
	 * @param int    $post_id The ID of the post.
	 * @param string $location The location where the meta is shown.
	 */
	function cpschool_get_post_meta( $post_id = null, $single = true ) {
		global $post;

		// Require post ID.
		if ( ! $post_id ) {
			return;
		}

		/**
		 * Filters post types array
		 *
		 * This filter can be used to hide post meta information of post, page or custom post type registerd by child themes or plugins
		 *
		 * @since 1.0.0
		 *
		 * @param array Array of post types
		 */
		$disallowed_post_types = apply_filters( 'cpschool_post_meta_disallowed_post_types', array() );
		// Check whether the post type is allowed to output post meta.
		if ( in_array( get_post_type( $post_id ), $disallowed_post_types, true ) ) {
			return;
		}

		$post_meta = cpschool_get_content_theme_mod( 'meta', get_post_type( $post_id ), $single );
		if ( $post_meta === null ) {
			$post_meta = array(
				'post-date',
			);
		}
		$post_meta = apply_filters(
			'cpschool_post_meta_items',
			$post_meta,
			$post_id
		);

		// If the post meta setting has the value 'empty', it's explicitly empty and the default post meta shouldn't be output.
		if ( $post_meta && ! in_array( 'empty', $post_meta, true ) ) {
			$post = get_post( $post_id );
			setup_postdata( $post );

			ob_start();
			?>

			<ul class="list-inline">
				<?php
				/**
				 * Fires before post meta html display.
				 *
				 * Allow output of additional post meta info to be added by child themes and plugins.
				 *
				 * @since 1.0.0
				 *
				 * @param int   $post_ID Post ID.
				 */
				do_action( 'cpschool_start_of_post_meta_list', $post_id, $post_meta );

				// Author.
				if ( in_array( 'author', $post_meta, true ) ) {
					?>
					<li class="post-author list-inline-item<?php echo in_array( 'author-avatar', $post_meta, true ) ? ' post-author-has-avatar' : ''; ?>">
						<span class="screen-reader-text"><?php _e( 'Author', 'cpschool' ); ?></span>
						<?php if ( in_array( 'author-avatar', $post_meta, true ) ) { ?>
							<?php echo get_avatar( $post, 48 ); ?>
						<?php } else { ?>
							<span class="meta-icon" aria-hidden="true">
								<i class="cps-icon cps-icon-user"></i>
							</span>
						<?php } ?>
						<span class="meta-text">
							<?php
							printf(
								/* translators: %s: Author name */
								__( 'By %s', 'cpschool' ),
								'<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author_meta( 'display_name' ) ) . '</a>'
							);
							?>
						</span>
					</li>
					<?php
				}

				// Post date.
				if ( in_array( 'post-date', $post_meta, true ) ) {
					?>
					<li class="post-date list-inline-item">
						<span class="screen-reader-text"><?php _e( 'Publication date', 'cpschool' ); ?></span>
						<span class="meta-icon" aria-hidden="true">
							<i class="cps-icon cps-icon-calendar"></i>
						</span>
						<span class="meta-text">
							<?php the_time( get_option( 'date_format' ) ); ?>
						</span>
					</li>
					<?php
					if ( in_array( 'post-modified', $post_meta, true ) ) {
						?>
						<li class="post-modified list-inline-item">
							<span class="meta-text">
								<small>
									(<?php _e( 'Updated On ', 'cpschool' ); ?> <?php the_modified_date( get_option( 'date_format' ) ); ?>)
								</small>
							</span>
						</li>
						<?php
					}
				}

				// Post last modification date.
				if ( in_array( 'post-modified', $post_meta, true ) && ! in_array( 'post-date', $post_meta, true ) ) {
					?>
					<li class="post-modified list-inline-item">
						<span class="screen-reader-text"><?php _e( 'Last modification date', 'cpschool' ); ?></span>
							<span class="meta-icon" aria-hidden="true">
								<i class="cps-icon cps-icon-calendar"></i>
							</span>
						<span class="meta-text">
							<?php _e( 'Updated On ', 'cpschool' ); ?>
							<?php the_modified_date( get_option( 'date_format' ) ); ?>
						</span>
					</li>
					<?php
				}

				// Categories.
				if ( in_array( 'tax-category', $post_meta, true ) && has_category() ) {
					?>
					<li class="post-categories list-inline-item">
						<span class="screen-reader-text"><?php _e( 'Categories:', 'cpschool' ); ?></span>
						<span class="meta-icon" aria-hidden="true">
							<i class="cps-icon cps-icon-category"></i>
						</span>
						<span class="meta-text">
							<?php the_category( ', ' ); ?>
						</span>
					</li>
					<?php
				}

				// Tags.
				if ( in_array( 'tax-post_tag', $post_meta, true ) && has_tag() ) {
					?>
					<li class="post-tags list-inline-item">
						<span class="screen-reader-text"><?php _e( 'Tags:', 'cpschool' ); ?></span>
						<span class="meta-icon" aria-hidden="true">
							<i class="cps-icon cps-icon-tag"></i>
						</span>
						<span class="meta-text">
							<?php the_tags( '', ', ', '' ); ?>
						</span>
					</li>
					<?php
				}

				// Comments link.
				if ( in_array( 'comments', $post_meta, true ) && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
					?>
					<li class="post-comment-link list-inline-item">
						<span class="meta-icon" aria-hidden="true">
							<i class="cps-icon cps-icon-comment"></i>
						</span>
						<span class="meta-text">
							<?php comments_popup_link(); ?>
						</span>
					</li>
					<?php
				}

				// Sticky.
				if ( in_array( 'sticky', $post_meta, true ) && is_sticky() ) {
					?>
					<li class="post-sticky list-inline-item">
						<span class="meta-icon" aria-hidden="true">
							<i class="cps-icon cps-icon-pin"></i>
						</span>
						<span class="meta-text">
							<?php _e( 'Sticky post', 'cpschool' ); ?>
						</span>
					</li>
					<?php
				}

				/**
				 * Fires after post meta html display.
				 *
				 * Allow output of additional post meta info to be added by child themes and plugins.
				 *
				 * @since 1.0.0
				 *
				 * @param int   $post_ID Post ID.
				 */
				do_action( 'cpschool_end_of_post_meta_list', $post_id );
				?>
			</ul>

			<?php
			wp_reset_postdata();

			$meta_output = ob_get_clean();

			// If there is meta to output, return it.
			if ( $meta_output ) {
				return $meta_output;
			}
		}
	}
}

if ( ! function_exists( 'cpschool_pagination' ) ) {

	function cpschool_pagination( $args = array(), $class = 'pagination' ) {

		if ( $GLOBALS['wp_query']->max_num_pages <= 1 ) {
			return;
		}

		$args = wp_parse_args(
			$args,
			array(
				'mid_size'           => 2,
				'prev_next'          => true,
				'prev_text'          => __( '&laquo;', 'cpschool' ),
				'next_text'          => __( '&raquo;', 'cpschool' ),
				'screen_reader_text' => __( 'Posts navigation', 'cpschool' ),
				'type'               => 'array',
				'current'            => max( 1, get_query_var( 'paged' ) ),
			)
		);

		$links = paginate_links( $args );

		?>

		<nav aria-label="<?php echo $args['screen_reader_text']; ?>">
			<ul class="pagination">
				<?php
				foreach ( $links as $key => $link ) {
					?>
					<li class="page-item <?php echo strpos( $link, 'current' ) ? 'active' : ''; ?>">
						<?php echo str_replace( 'page-numbers', 'page-link', $link ); ?>
					</li>
					<?php
				}
				?>
			</ul>
		</nav>
		<?php
	}
}

if ( ! function_exists( 'cpschool_get_page_title' ) ) {
	function cpschool_get_page_title() {
		$title = false;

		if ( is_singular() && get_post_meta( get_the_ID(), 'cps_hero_title_disable', true ) ) {
			return false;
		}

		if ( is_search() ) {
			$title = sprintf(
				'%1$s %2$s',
				'<span class="color-accent">' . __( 'Search:', 'cpschool' ) . '</span>',
				'&ldquo;' . get_search_query() . '&rdquo;'
			);
		} elseif ( is_singular() ) {
			$title = get_the_title();
		} elseif ( is_404() ) {
			$title = __( 'Oops! That page can&rsquo;t be found.', 'cpschool' );
		} elseif ( is_archive() ) {
			$title = get_the_archive_title();
		} elseif ( is_home() ) {
			$title = get_theme_mod( 'posts_main_hero_title' );
			if ( ! $title ) {
				$blog_page_id = get_option( 'page_for_posts' );
				if ( $blog_page_id ) {
					$title = get_the_title( $blog_page_id );
				}
			}
		}

		return $title;
	}
}

if ( ! function_exists( 'cpschool_get_page_subtitle' ) ) {
	function cpschool_get_page_subtitle() {
		global $wp_query, $post;

		$subtitle = false;

		if ( is_search() ) {
			if ( $wp_query->found_posts ) {
				$subtitle = sprintf(
					/* translators: %s: Number of search results */
					_n(
						'We found %s result for your search.',
						'We found %s results for your search.',
						$wp_query->found_posts,
						'cpschool'
					),
					number_format_i18n( $wp_query->found_posts )
				);
			} else {
				$subtitle = __( 'We could not find any results for your search. You can give it another try through the search form below.', 'cpschool' );
			}
		} elseif ( is_singular() ) {
			if ( is_page() && has_excerpt() ) {
				$subtitle = $post->post_excerpt;
			}
		} elseif ( is_404() ) {
			$subtitle = __( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'cpschool' );
		} elseif ( is_archive() ) {
			$subtitle = get_the_archive_description();
		} elseif ( is_home() ) {
			$subtitle = get_theme_mod( 'posts_main_hero_subtitle' );
			if ( ! $subtitle ) {
				$blog_page_id = get_option( 'page_for_posts' );
				if ( $blog_page_id ) {
					$blog_page = get_post( $blog_page_id );
					$subtitle  = $blog_page->post_excerpt;
				}
			}
		}

		return $subtitle;
	}
}

if ( ! function_exists( 'cpschool_get_active_sidebars' ) ) {
	function cpschool_get_active_sidebars() {
		$option_name = 'entries_lists';

		if ( is_singular() ) {
			$post_id = get_the_ID();
		} elseif ( is_home() ) {
			$post_id = get_option( 'page_for_posts' );
		}

		if ( isset( $post_id ) ) {
			// Checks if sidebars are overwritten on single post/page.
			$custom = get_post_meta( $post_id, 'cps_sidebars_custom', true );
			if ( $custom ) {
				$sidebars_pos = get_post_meta( $post_id, 'cps_sidebars', true );
			}
			// Looks for settings in customizer if its not set for specific page.
			// TODO Consider using "cpschool_get_content_theme_mod" function in here.
			elseif ( is_page() ) {
				$option_name = 'pages';
			} elseif ( is_single() ) {
				$option_name = 'posts';
			}
		}

		if ( ! isset( $sidebars_pos ) ) {
			$sidebars_pos = get_theme_mod( $option_name . '_sidebars' );
		}

		if ( $sidebars_pos && is_array( $sidebars_pos ) ) {
			foreach ( $sidebars_pos as $sidebar_pos_key => $sidebar_pos ) {
				if ( ! is_active_sidebar( $sidebar_pos ) ) {
					unset( $sidebars_pos[ $sidebar_pos_key ] );
				}

				return $sidebars_pos;
			}
		}

		return array();
	}
}

if ( ! function_exists( 'cpschool_get_hero_style' ) ) {
	function cpschool_get_hero_style() {
		// Lets handle main blog page differently - it needs to be enabled specifically.
		if ( is_home() && ! get_theme_mod( 'posts_main_hero' ) ) {
			return false;
		}

		$hero_style = get_theme_mod( 'hero_main_style' );
		if ( $hero_style == 'disabled' ) {
			return false;
		}
		if ( is_singular() && get_post_meta( get_the_ID(), 'cps_hero_title_disable', true ) ) {
			return false;
		}

		// If there is no page title, we don't really want to display the hero for now.
		if ( cpschool_get_page_title() === false ) {
			return false;
		}

		// If hero only shows image and its missing, lets not display it at all.
		if ( $hero_style == 'full-title-under-img' && ! cpschool_has_hero_image() ) {
			return false;
		}

		return $hero_style;
	}
}

if ( ! function_exists( 'cpschool_get_hero_image' ) ) {
	function cpschool_has_hero_image() {
		$hero_style = get_theme_mod( 'hero_main_style' );

		if ( is_singular() && has_post_thumbnail() ) {
			return true;
		} elseif ( ( $hero_style != 'img-under-title' || is_customize_preview() ) && $hero_default_images = get_theme_mod( 'hero_main_default_images' ) ) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'cpschool_is_breadcrumb_enabled' ) ) {
	function cpschool_is_breadcrumb_enabled( $context ) {
		// Lets check if breadcrumb should even be displayed in current context.
		$hero_style = cpschool_get_hero_style();

		if ( $context == 'page' ) {
			if ( $hero_style && ( $hero_style != 'full-title-under-img' || get_theme_mod( 'hero_main_breadcrumb_style' ) == 'top_right' ) ) {
				return false;
			}
		} elseif ( $context == 'hero' ) {
			if ( $hero_style && ( $hero_style == 'full-title-under-img' && get_theme_mod( 'hero_main_breadcrumb_style' ) == 'above_title_no_bg' ) ) {
				return false;
			}
		}
		if ( is_singular() ) {
			// Lets look for settings in customizer if its not set for specific page.
			if ( is_page() ) {
				$option_name = 'pages';
			} elseif ( is_single() ) {
				$option_name = 'posts';
			}
		} else {
			$option_name = 'entries_lists';
		}

		$show_breadcrumb = get_theme_mod( $option_name . '_breadcrumb' );

		// We could consider hooking up to is_active_sidebar filter to check it.
		if ( $show_breadcrumb ) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'cpschool_breadcrumb' ) ) {
	function cpschool_show_breadcrumb( $context ) {
		if ( function_exists( 'breadcrumb_trail' ) ) {
			$args = array(
				'container'       => 'nav',
				'container_class' => $context ? implode( ' ', cpschool_class( $context, 'breadcrumb-trail breadcrumbs', true ) ) : 'breadcrumb-trail breadcrumbs',
				'before'          => '',
				'after'           => '',
				'list_tag'        => 'ol',
				'list_class'      => 'breadcrumb',
				'item_tag'        => 'li',
				'item_class'      => 'breadcrumb-item',
				'show_on_front'   => false,
				'network'         => false,
				'show_title'      => true,
				'show_browse'     => false,
				'labels'          => array(
					'aria_label'          => esc_attr_x( 'Breadcrumb', 'breadcrumbs aria label', 'breadcrumb-trail' ),
					'home'                => esc_html__( 'Home', 'breadcrumb-trail' ),
					'error_404'           => esc_html__( '404 Not Found', 'breadcrumb-trail' ),
					'archives'            => esc_html__( 'Archives', 'breadcrumb-trail' ),
					// Translators: %s is the search query.
					'search'              => esc_html__( 'Search results for: %s', 'breadcrumb-trail' ),
					// Translators: %s is the page number.
					'paged'               => esc_html__( 'Page %s', 'breadcrumb-trail' ),
					// Translators: %s is the page number.
					'paged_comments'      => esc_html__( 'Comment Page %s', 'breadcrumb-trail' ),
					// Translators: Minute archive title. %s is the minute time format.
					'archive_minute'      => esc_html__( 'Minute %s', 'breadcrumb-trail' ),
					// Translators: Weekly archive title. %s is the week date format.
					'archive_week'        => esc_html__( 'Week %s', 'breadcrumb-trail' ),

					// "%s" is replaced with the translated date/time format.
					'archive_minute_hour' => '%s',
					'archive_hour'        => '%s',
					'archive_day'         => '%s',
					'archive_month'       => '%s',
					'archive_year'        => '%s',
				),
				'post_taxonomy'   => array(
					// 'post'  => 'post_tag', // 'post' post type and 'post_tag' taxonomy
					// 'book'  => 'genre',    // 'book' post type and 'genre' taxonomy
				),
				'echo'            => true,
			);
			breadcrumb_trail( $args );
		}
	}
}

if ( ! function_exists( 'cpschool_get_post_type_icon_class' ) ) {
	/**
	 * Gets the icon class for post type.
	 *
	 * @return string|bool returns the class if found or false if it does not exists.
	 */
	function cpschool_get_post_type_icon_class( $post_type ) {
		$icons_class_map = array(
			'page' => 'cps-icon-file-text-o',
		);

		$icons_class_map = apply_filters( 'cpschool_post_types_icon_classs', $icons_class_map );

		if ( isset( $icons_class_map[ $post_type ] ) ) {
			return $icons_class_map[ $post_type ];
		}

		return false;
	}
}

if ( ! function_exists( 'cpschool_site_info' ) ) {
	/**
	 * Add site info hook to WP hook library.
	 */
	function cpschool_site_info() {
		do_action( 'cpschool_site_info' );
	}
}

if ( ! function_exists( 'cpschool_get_content_theme_mod' ) ) {
	/**
	 * Gets correct value of theme mod for shared content customizer settings.
	 *
	 * @param string $option_name
	 * @param string $post_type
	 * @param boolen $single
	 * @return value for given option name or null if it does not exists.
	 */
	function cpschool_get_content_theme_mod( $option_name, $post_type, $single ) {
		if ( $single ) {
			$option_prefix_map = array(
				'post' => 'posts',
				'page' => 'pages',
			);
			if ( isset( $option_prefix_map[ $post_type ] ) ) {
				$option_prefix = $option_prefix_map[ $post_type ];
			} else {
				$option_prefix = $post_type;
			}
		} else {
			$option_prefix = 'entries_lists';
		}

		$option_name = $option_prefix . '_' . $option_name;

		$option_value = get_theme_mod( $option_name, null );

		return $option_value;
	}
}