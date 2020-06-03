<?php
/**
 * CP Directories - Universal directory framework that can easily be integrated with themes and other plugins.
 */

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists('CPDirectory') ) :

    class CPDirectory {
        /** @var object stores instance so only single one is possible  */
        private static $instance;

        /** 
         * @var string path to the framework directory.
         */
        var $dir;

        /** 
         * @var string url to the framework directory.
         */
        var $dir_uri;
        
        private function __construct() {
            // This will need filtering so it can be included in all kinds of places.
            $this->dir = get_template_directory() . '/inc/plugins/cp-directory';
            $this->dir_uri = get_template_directory_uri() . '/inc/plugins/cp-directory';

            // Includes helpers necessary for blog templates
            require_once( $this->dir . '/cp-directory-files/helpers.php' );

            add_action( 'init', array($this, 'register_block') );
            add_filter( 'the_content', array($this, 'single_content'), 20 );
        }

        /**
         * Starts the class so only single instance is possible
         *
         * @return void
         */
        public static function get_instance() {
            if( !isset( self::$instance ) ) {
                self::$instance = new CPDirectory();
            }
    
            // Returns the instance
            return self::$instance;
        }

        /**
         * Registers everything needed for blocks.
         *
         * @return void
         */
        public function register_block() {
            // Checks if ACF is loaded. It is required.
            if( ! class_exists('ACF') ) {
                return;
            }

            // Filters available sources (post types) to choose from.
            $sources = cp_dir_get_sources();

            // Checks if sources are valid and prepares the data.
            $block_data = array();
            foreach( $sources as $post_type ) {
                $post_type_object =  get_post_type_object( $post_type );
                if( $post_type_object ) {
                    // TODO: Move it to dedicated function.
                    $block_taxonomy_data = array();
                    $taxonomies = get_object_taxonomies( $post_type, 'objects' );
                    if( $taxonomies ) {
                        foreach( $taxonomies as $taxonomy_name => $taxonomy_details ) {
                            $block_taxonomy_data[] = array(
                                'name' => $taxonomy_name,
                                'label' => $taxonomy_details->label
                            );
                        }
                    }

                    $block_data[] = array(
                        'name' => $post_type,
                        'label' => $post_type_object->label,
                        'taxonomies' => $block_taxonomy_data,
                        'filters' => cp_dir_get_available_filters( $post_type ),
                        'fields' => cp_dir_get_available_fields( $post_type ),
                    );
                }
            }

            // Continue only if we have some data ready.
            if( !$block_data ) {
                return;
            }

            $blocks_dir = $this->dir . '/cp-directory-files/blocks';
            $blocks_dir_uri = $this->dir_uri . '/cp-directory-files/blocks';

            $editor_js = 'cp-dir/index.js';
            wp_register_script(
                'cp-dir-block-editor',
                "$blocks_dir_uri/$editor_js",
                array(
                    'wp-blocks',
                    'wp-editor',
                    'wp-i18n',
                    'wp-element',
                ),
                filemtime( "$blocks_dir/$editor_js" )
            );
            wp_localize_script( 'cp-dir-block-editor', 'CPDir', $block_data );

            $editor_css = 'cp-dir/editor.css';
            wp_register_style(
                'cp-dir-block-editor',
                "$blocks_dir_uri/$editor_css",
                array(),
                filemtime( "$blocks_dir/$editor_css" )
            );

            $block_js = 'cp-dir/frontend.js';
            wp_register_script(
                'cp-dir-block',
                "$blocks_dir_uri/$block_js",
                array('jquery'),
                filemtime( "$blocks_dir/$block_js" )
            );

            /*
            $style_css = 'cp-dir/style.css';
            wp_register_style(
                'cp-dir-block',
                "$blocks_dir_uri/$style_css",
                array(),
                filemtime( "$blocks_dir/$style_css" )
            );
            */

            register_block_type( 'cp-dir/cp-dir', array(
                'render_callback' => array( $this, 'block_content' ),
                'attributes' => array(
                    'source' => array(
                        'type' => 'string',
                        'default' => $block_data[0]['name']
                    ),
                    'categories' => array(
                        'type' => 'object',
                        'default' => array(),
                    ),
                    'filters' => array(
                        'type' => 'object',
                        'default' => array(),
                    ),
                    'fields' => array(
                        'type' => 'object',
                        'default' => array(),
                    ),
                    /*
                    'sort_by' => array(
                        'type' => 'string',
                        'default' => '',
                    ),*/
                ),
                'editor_script' => 'cp-dir-block-editor',
                'editor_style'  => 'cp-dir-block-editor',
                //'style'         => 'cp-dir-block',
            ) );
        }

        /**
         * Renders directory content.
         *
         * @return string HTML of the directory.
         */
        public function block_content( $atts ) {
            // Includes class used to prepare the data for directory.
            require_once( $this->dir . '/cp-directory-files/class-directory-data.php' );

            ob_start();

            include( $this->dir . '/cp-directory-files/blocks/cp-dir/template-parts/directory.php' );
            
            return ob_get_clean();
        }

        /**
         * Filters content to include fields.
         *
         * @return string HTML of the single entry that is part of directory.
         */
        public function single_content( $content ) {
            $sources = cp_dir_get_sources();
            if( is_main_query() && is_single() && in_array( get_post_type(), $sources ) ) {
                // Includes class used to prepare the data for directory.
                require_once( $this->dir . '/cp-directory-files/class-directory-entry-data.php' );

                ob_start();

                include( $this->dir . '/cp-directory-files/blocks/cp-dir/template-parts/entry.php' );

                return ob_get_clean();
            }
            
            return $content;
        }
    }

    CPDirectory::get_instance();

endif;