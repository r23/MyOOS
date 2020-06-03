<?php
class CPDirectoryEntryData {
    var $post;
    var $dir_id;

    function __construct( $post, $dir_id ) {
        $this->post = $post;
        $this->dir_id = $dir_id;
    }

    function get_fields() {
        $pre = apply_filters( 'cp_dir_pre_get_entry_fields', false, $this->post, $this->dir_id );
 
        if ( false !== $pre ) {
            return $pre;
        }

        $blocked_entry_fields = apply_filters( 'cp_dir_blocked_entry_fields', array( 'post_title' ) );

        $fields = array();

        $available_fields = cp_dir_get_available_fields( get_post_type( $this->post ) );
        foreach( $available_fields as $field_key => $field_details ) {
            if( in_array( $field_key, $blocked_entry_fields ) ) {
                continue;
            }
            if( $field_details['type'] == 'taxonomy' ) {
                $post_terms_childs_parents = wp_get_post_terms( $this->post->ID, $field_details['name'], array( 'fields' => 'id=>parent' ) );
                if( $post_terms_childs_parents ) {
                    $post_terms_parents = array_unique( array_values( $post_terms_childs_parents ) );
                    // Splits taxonomy into childs 
                    if( $post_terms_parents && count($post_terms_parents) > 1 || $post_terms_parents[0] !== 0 ) {
                        foreach( $post_terms_parents as $parent_id ) {
                            if( $parent_id ) {
                                $term = get_term( $parent_id, $field_details['name'] );
                                $fields[] = array_merge( $field_details, array(
                                    'label' => $term->name,
                                    'field_name' => $field_details['name'] . '-' . $term->term_id,
                                    'args' => array( 'parent_id' => $parent_id ),
                                ) );
                            }
                        }
                    }
                    else {
                        $fields[] = $field_details;
                    }

                    continue;
                }
            }
            
            $fields[] = $field_details;
        }
        
        return apply_filters( 'cp_dir_get_entry_fields', $fields, $this->post, $this->dir_id );
    }
    
    function get_dir_link() {
        $link = false;

        if( $this->dir_id ) {
            $link = get_permalink( $this->dir_id );
        }

        return apply_filters( 'cp_dir_get_entry_dir_link', $link, $this->post, $this->dir_id );
    }
}