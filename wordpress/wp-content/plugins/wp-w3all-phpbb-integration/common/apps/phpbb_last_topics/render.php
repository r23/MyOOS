<?php
/**
 * C 2024 axew3.com
 */

 # wp_w3all_phpbb_last_topics($post_text, $topics_number, $text_words) -> class.wp.w3all.widgets-phpbb.php
 $showPostText = empty($attributes['showPostText']) ? 0 : 1;
 $topicsNum = empty($attributes['topicsNum']) ? 5 : $attributes['topicsNum'];
 $wordsNum = empty($attributes['wordsNum']) ? 30 : $attributes['wordsNum'];

?>
<div <?php echo get_block_wrapper_attributes(); ?>>
  <?php
  $lastTopics = new WP_w3all_widget_last_topics();
  echo $lastTopics->wp_w3all_phpbb_last_topics($showPostText, $topicsNum, $wordsNum);
  # Could be with Heartbeat updates
  # wp_w3all_get_phpbb_lastopics_short( $atts = '', $from_hb = false ) -> class.wp.w3all-phpbb.php
  # echo WP_w3all_phpbb::wp_w3all_get_phpbb_lastopics_short( $atts = '', true );
  ?>
</div>
