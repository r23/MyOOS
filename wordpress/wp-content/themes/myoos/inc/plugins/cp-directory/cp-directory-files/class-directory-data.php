<?php
class CPDirectoryData {
    private static $id = 0;

    var $atts;
    var $post_type_object;
    
    var $taxonomy_filters = null;
    var $fields = null;
    

    function __construct( $atts ) {
        // Bumps the id on every init so each directory has unique ID.
        self::$id++;

        // Sets block attribute for easy access inside class.
        $this->atts = $atts;

        // Sets post type data
        $this->post_type_object = get_post_type_object( $this->atts['source'] );

        if( $this->post_type_object === null ) {
            return false;
        }
    }

    function get_directory_id() {
        return 'cp-dir-' . self::$id;
    }

    function get_taxonomy_filters() {
        if( $this->taxonomy_filters !== null ) {
            return $this->taxonomy_filters;
        }

        $this->taxonomy_filters = apply_filters( 'cp_dir_pre_taxonomies_filters_data', false, $this->atts );
 
        if ( false !== $this->taxonomy_filters ) {
            return $this->taxonomy_filters;
        }
        
        $taxonomies = get_object_taxonomies( $this->atts['source'], 'object' );
        $this->taxonomy_filters = array();
        foreach( $taxonomies as $taxonomy ) {
            if( isset( $this->atts['filters'][$this->atts['source']] ) && in_array( 'tax_' . $taxonomy->name, $this->atts['filters'][$this->atts['source']] ) ) {
                $parent_id = 0;
                if( isset( $this->atts['categories'][$taxonomy->name] ) && $this->atts['categories'][$taxonomy->name] ) {
                    $parent_id = $this->atts['categories'][$taxonomy->name];
                }
        
                if( in_array( 'tax_childs_' . $taxonomy->name, $this->atts['filters'][$this->atts['source']] ) ) {            
                    $terms = get_terms( $taxonomy->name, array(
                        'hide_empty' => true,
                        'parent' => $parent_id,
                    ) );
                    foreach( $terms as $term ) {
                        if( get_term_children( $term->term_id, $taxonomy->name ) ) {
                            $this->taxonomy_filters[] = array(
                                'label' => $term->name,
                                'taxonomy' => $taxonomy->name,
                                'parent_id' => $term->term_id,
                                'select_id' => $this->get_directory_id() . '-tax-' . $taxonomy->name . '-' . $term->term_id,
                                'field_name' => $taxonomy->name . '-' . $term->term_id,
                            );
                        }
                    }
                }
        
                if( empty( $this->taxonomy_filters ) ) {
                    // Use parent name if childs are being shown.
                    if( $parent_id ) {
                        $term = get_term( $parent_id, $taxonomy->name );

                        $label = $term->name;
                        $field_name = $taxonomy->name . '-' . $term->term_id;
                    }
                    else {
                        $label = $taxonomy->label;
                        $field_name = $taxonomy->name;
                    }
                    if( !$parent_id || get_term_children( $parent_id, $taxonomy->name ) ) {
                        $this->taxonomy_filters[] = array(
                            'label' => $label,
                            'taxonomy' => $taxonomy->name,
                            'parent_id' => $parent_id,
                            'select_id' => $this->get_directory_id() . '-tax-' . $taxonomy->name,
                            'field_name' => $field_name,
                        );
                    }
                }
            }
        }
    
        return apply_filters( 'cp_dir_taxonomies_filters_data', $this->taxonomy_filters, $this->atts );
    }

    function get_fields() {
        if( $this->fields !== null ) {
            return $this->fields;
        }

        $pre = apply_filters( 'cp_dir_pre_get_fields', false, $this->atts );
 
        if ( false !== $pre ) {
            return $pre;
        }

        $this->fields = array();

        $available_fields = cp_dir_get_available_fields( $this->atts['source'] );
        foreach( $available_fields as $field_key => $field_details ) {
            if( $field_details['default'] || ( isset( $this->atts['fields'][$this->atts['source']] ) && in_array( $field_key, $this->atts['fields'][$this->atts['source']] ) ) ) {
                if( $field_details['type'] == 'taxonomy' ) {
                    $taxonomies_filters = $this->get_taxonomy_filters();
                    if( $taxonomies_filters ) {
                        foreach( $taxonomies_filters as $filter ) {
                            $this->fields[] = array_merge( $field_details, array(
                                'label' => $filter['label'],
                                'field_name' => $filter['field_name'],
                                'args' => array( 'parent_id' => $filter['parent_id'] ),
                            ) );
                        }
                    }
                }
                else {
                    $this->fields[] = array_merge( $field_details, array(
                        'field_name' => ( isset( $field_details['args']['name_field'] ) && $field_details['args']['name_field'] ) ? 'name' : $field_details['name'],
                    ) );
                }
            }
        }
        
        return apply_filters( 'cp_dir_get_fields', $this->fields, $this->atts );
    }

    function get_fields_js() {
        $pre = apply_filters( 'cp_dir_pre_get_fields_js', false, $this->atts );
 
        if ( false !== $pre ) {
            return $pre;
        }

        $fields_js = array( array( 'data' => array( 'entry-id' ) ) );
        $fields = $this->get_fields();
        foreach( $fields as $field ) {
            if( $field['type'] == 'taxonomy' ) {
                $fields_js[] = array( 'name' => $field['field_name'], 'attr' => 'data-value' );
            }
            elseif( $field['name'] == 'post_title' ) {
                $fields_js[] = array( 'name' => $field['field_name'], 'attr' => 'data-value' );
            }
            else {
                $fields_js[] = $field['field_name'];
            }
        }

        return apply_filters( 'cp_dir_get_fields_js', $fields_js, $this->atts );
    }

    function get_entries() {
        $args = array(
            'numberposts'      => 200,
            'category'         => 0,
            'orderby'          => 'title',
            'order'            => 'ASC',
            'post_type'        => $this->atts['source'],
            'fields'           => 'ids'
        );

        if( $this->atts['categories'] ) {
            $taxonomies = get_object_taxonomies( $this->atts['source'] );
            foreach( $this->atts['categories'] as $taxonomy_name => $taxonomy_child_id ) {
                if( in_array( $taxonomy_name, $taxonomies ) && $taxonomy_child_id ) {
                    if( !isset($args['tax_query']) ) {
                        $args['tax_query'] = array();
                    }
                    $args['tax_query'][] = array(
                        'taxonomy' => $taxonomy_name,
                        'field' => 'term_id', 
                        'terms' => $taxonomy_child_id,
                        'include_children' => true,
                    );
                }
            }
        }

        $entries = get_posts( $args );

        return $entries;
    }
}