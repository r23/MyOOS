<?php defined( 'ABSPATH' ) or die( 'forbidden' );
echo '<ul class="'.$w3feed_ul_class.'">';
     if ( $maxitems == 0 ) :
        echo'<li class="'.$w3feed_li_class.'"'.$w3feed_inline_style.'>'. _e( 'No items to display', 'wp-w3all-phpbb-integration' ).'</li>';
      else : ?>
        <?php
        $citems = count($rss_items);
        $ccitems = 0;
        foreach ( $rss_items as $item ) :
        $ccitems++;
        $author = $item->get_author();
            echo '<li class="'.$w3feed_li_class.'"' .$w3feed_inline_style. '>';
            ?>
                <a href="<?php echo esc_url( $item->get_permalink() ) .'"' . $w3feed_href_blank.''; ?>
                    title="<?php printf( __( 'Posted %s', 'wp-w3all-phpbb-integration' ), $item->get_date('j F Y | g:i a') ); ?>">
                    <?php echo esc_html( $item->get_title() ); ?>
                </a>
    <?php if($w3feed_text_words > 0){
                echo '<div>' . wp_w3all_R_num_of_words_parse($item->get_content(), $w3feed_text_words) . '</div>';
             if($ccitems < $citems){ echo '<hr />'; }
             } elseif ($w3feed_text_words == 'content') {
                echo '<div>' . wpautop($item->get_content()) . '</div>';
               }

       // if($ccitems < $citems){ echo '<hr />'; } // output a separator <hr>

         ?>
      </li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>