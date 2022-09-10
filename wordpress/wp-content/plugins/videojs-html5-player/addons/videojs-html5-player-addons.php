<?php

function videojs_html5_player_display_addons()
{
    /*
    echo '<div class="wrap">';
    echo '<h2>' .__('Videojs HTML5 Player Add-ons', 'videojs-html5-player') . '</h2>';
    */
    $addons_data = array();

    $addon_1 = array(
        'name' => 'Disable Right Click',
        'thumbnail' => VIDEOJS_HTML5_PLAYER_URL.'/addons/images/videojs-disable-right-click.png',
        'description' => 'Disable right click on the Video.js player',
        'page_url' => 'https://wphowto.net/videojs-html5-player-for-wordpress-757',
    );
    array_push($addons_data, $addon_1);
    
    //Display the list
    foreach ($addons_data as $addon) {
        ?>
        <div class="videojs_html5_player_addons_item_canvas">
        <div class="videojs_html5_player_addons_item_thumb">
            <img src="<?php echo esc_url($addon['thumbnail']);?>" alt="<?php echo esc_attr($addon['name']);?>">
        </div>
        <div class="videojs_html5_player_addons_item_body">
        <div class="videojs_html5_player_addons_item_name">
            <a href="<?php echo esc_url($addon['page_url']);?>" target="_blank"><?php echo esc_html($addon['name']);?></a>
        </div>
        <div class="videojs_html5_player_addons_item_description">
        <?php echo esc_html($addon['description']);?>
        </div>
        <div class="videojs_html5_player_addons_item_details_link">
        <a href="<?php echo esc_url($addon['page_url']);?>" class="videojs_html5_player_addons_view_details" target="_blank">View Details</a>
        </div>    
        </div>
        </div>
        <?php
    }
    echo '</div>';//end of wrap
}
