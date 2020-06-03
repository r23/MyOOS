<?php
$data = new CPDirectoryData( $atts );

if( !$data ) {
    return;
}

$dir_id = $data->get_directory_id();
?>
<div class="cp-dir" id="<?php echo esc_attr($dir_id); ?>">
    <form class="cp-dir-controls">
        <?php if( isset( $atts['filters'][$atts['source']] ) && in_array( 'search', $atts['filters'][$atts['source']] ) ) { ?>
            <div class="cp-dir-control cp-dir-control-text cp-dir-control-search">
                <?php $field_id = $dir_id . '-search'; ?>
                <label class="screen-reader-text" for="<?php esc_attr_e($field_id); ?>">Search</label>
                <input class="cp-dir-field cp-dir-field-search" id="<?php esc_attr_e($field_id); ?>" name="search" type="text" placeholder="<?php esc_attr_e( 'Search &hellip;', 'cp-dir' ); ?>" value="">
            </div>
        <?php } ?>
        <?php 
        $taxonomies_filters = $data->get_taxonomy_filters();
        if( $taxonomies_filters ) {
            foreach( $taxonomies_filters as $filter ) {
            ?>
                <div class="cp-dir-control cp-dir-control-select" data-field-name="<?php esc_attr_e( $filter['field_name'] ); ?>">
                    <label class="screen-reader-text" for="<?php esc_attr_e( $filter['select_id'] ); ?>"><?php printf( __( 'Filter By %s', 'cp-dir' ), ucfirst( $filter['label'] ) ); ?></label>
                    <?php 
                    wp_dropdown_categories( array(
                        'show_option_all' => '<span aria-hidden="true">' . sprintf( __( 'Filter "%s"', 'cp-dir' ), ucfirst( $filter['label'] ) ) . '</span>',
                        'taxonomy' => $filter['taxonomy'],
                        'hierarchical' => true,
                        'orderby' => 'name',
                        'name' => 'taxonomies[' . $filter['taxonomy'] . '][]',
                        'child_of' => $filter['parent_id'],
                        'id' => $filter['select_id'],
                        'class' => 'cp-dir-field cp-dir-field-tax',
                        'hide_if_empty' => true
                    ) ); 
                    ?>
                </div>
            <?php
            }
        }
        ?>
        <?php if( isset( $atts['filters'][$atts['source']] ) && in_array( 'clear', $atts['filters'][$atts['source']] ) ) { ?>
            <div class="cp-dir-control cp-dir-control-button cp-dir-control-clear">
                <button disabled><?php _e( 'Clear Results', 'cp-dir' ); ?></button>
            </div>
        <?php } ?>
    </form>
    <div class="cp-dir-content">
        <?php
        $entries = $data->get_entries();
        if( $entries ) {
            $fields = $data->get_fields();
            ?>
            <table>
                <thead>
                    <tr>
                        <?php
                        foreach( $fields as $field ) {
                            echo '<th>' . $field['label'] . '</th>';
                        }
                        ?>
                    </tr>
                </thead>
                <tbody class="cp-dir-content-list">
                    <?php foreach( $entries as $entry_id ) { ?>
                        <tr data-entry-id="<?php esc_attr_e( $entry_id ); ?>">
                            <?php 
                            foreach( $fields as $field ) {
                                $value = cp_dir_get_field_value( $entry_id, $field );
                                echo '<td class="' . esc_attr( $field['field_name'] ) . '" data-value="' . esc_attr( $value['attr'] ) . '">' . $value['content'] . '</td>';
                            }
                            ?>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php
            wp_enqueue_script( 'cp-dir-block' );

            $field_js = json_encode( $data->get_fields_js() );
            ob_start();
            ?>
            <script>
            cpDirectories['<?php esc_attr_e( $dir_id ); ?>'] = new List( '<?php esc_attr_e( $dir_id ); ?>', {
                    valueNames: <?php echo $field_js; ?>,
                    listClass: 'cp-dir-content-list',
                    searchClass: 'cp-dir-field-search',
            } );
            </script>
            <?php
            $inline_script = str_replace( array( '<script>', '</script>' ), '', ob_get_clean() );
            wp_add_inline_script( 'cp-dir-block', $inline_script );
        }
        ?>
    </div>
</div>