<?php defined( 'ABSPATH' ) or die( 'forbidden' );
// copyright 2022 axew3.com
// wp_w3all_phpbb_iframe_short vers 1.0

 add_action('wp_enqueue_scripts', 'wp_w3all_short_enqueue_scripts');
 add_action('wp_head','wp_w3all_short_add_css_ajax');
 add_shortcode( 'w3allphpbbiframe', 'wp_w3all_phpbb_iframe_short' );


function wp_w3all_phpbb_iframe_short( $atts ){
  global $w3all_config,$w3all_custom_output_files,$w3cookie_domain,$w3all_url_to_cms;

 if(is_array($atts)){
  $atts = array_map ('trim', $atts); 

    $ltm = shortcode_atts( array(
        'wp_page_name' => '',
        'wp_page_iframe_top_gap' => '0',
    ), $atts );
  }

   $ltm['wp_page_iframe_top_gap'] = intval($ltm['wp_page_iframe_top_gap']);

   if( $w3all_custom_output_files == 1 ) {
     $file = ABSPATH . 'wp-content/plugins/wp-w3all-config/wp_w3all_phpbb_iframe_short.php';
    ob_start();
      include($file);
    return ob_get_clean();
    } else {
     $file = WPW3ALL_PLUGIN_DIR . 'views/wp_w3all_phpbb_iframe_short.php';
    ob_start();
      include($file);
    return ob_get_clean();
    }
}


function wp_w3all_short_enqueue_scripts() {
 wp_enqueue_script("jquery");
}

function wp_w3all_short_add_css_ajax() {
  global $w3all_url_to_cms, $wp_w3all_forum_folder_wp,$w3all_iframe_phpbb_link_yn, $w3allhomeurl;

          $w3all_url_to_phpbb_ib = $w3all_url_to_cms . "/ucp.php?i=pm&folder=inbox";

$s = "
<script type=\"text/javascript\" src=\"".plugins_url()."/wp-w3all-phpbb-integration/addons/resizer/iframeResizer.min.js\"></script>
<script type=\"text/javascript\">
// pre loader js code for iframe content
//jQuery( document ).ready(function() {
 //jQuery('#w3idwloader').attr( \"class\", \"w3_wrap_loader\" );
//});
jQuery(window).load(function() {
 jQuery('#w3idwloader').css(\"display\",\"none\");
});

function w3all_ajaxup_from_phpbb_do(res){
jQuery(document).ready(function() {
if ( parseInt(res,10) > 0 && null == (document.getElementById('wp-admin-bar-w3all_phpbb_pm')) ){
var resp = '".__( 'You have ', 'wp-w3all-phpbb-integration' )."' + parseInt(res,10) + '".__( ' unread forum PM', 'wp-w3all-phpbb-integration' )."';
 jQuery('#wp-admin-bar-root-default').append('<li id=\"wp-admin-bar-w3all_phpbb_pm\"><a class=\"ab-item\" href=\"".$w3all_url_to_phpbb_ib."\">' + resp + '</li>');
 // window.location.reload(true);// this could be a work around for different themes, but lead to loop in this way
} else if (parseInt(res,10) > 0){
  var r = '".__( 'You have ', 'wp-w3all-phpbb-integration' )."' + parseInt(res,10) + '".__( ' unread forum PM', 'wp-w3all-phpbb-integration' )."';
  jQuery( 'li.w3all_phpbb_pmn' ).children().text( r );
} else {
 if( parseInt(res,10) == 0 && null !== (document.getElementById('wp-admin-bar-w3all_phpbb_pm'))){
  jQuery('li[id=wp-admin-bar-w3all_phpbb_pm]').remove();
 }
}
});
}

function w3allNormalize_phpBBUrl_onParent(href){
// try to 'normalize' passed relative links: needed all after last slash /
// exception are kind of passed urls like: /phpbb323/app.php/help/faq
// and if SEO mods that may assign some different kind of links values
// by the way, SEO absolute urls http(s) should be (all?) already considered here ...

var boardU = '".$w3all_url_to_cms."';
var phpbbRUrl = href.split(/^.+?(\w+.+)$/);
if( href.indexOf('app.php') > -1 ){ // since the previous not 'normalize' this type of passed value (and may miss something else)
   phpbburl = href.split(/^.+?(app\.php.+)$/);
   w3allappend = phpbburl[1];
 } else if ( /^https?/ig.exec(href) !== null ){ // absolute http(s) passed: try to 'normalize' a possible seo mod
   phpbburl = href.split(boardU);
   w3allappend = phpbburl[1];
 } else if ( phpbbRUrl[1] && phpbbRUrl[1].length > 1 ){ // 'normalize' any other
   w3allappend = phpbbRUrl[1];
 } else if ( phpbbRUrl[0].length > 1 ){
   w3allappend = phpbbRUrl[0];
   }
// ... if still not normalized
if(/^\W/ig.exec(w3allappend) !== null){
  w3allappend = w3allappend.split(/^.+?(\w+.+)$/);
  if(w3allappend[1]){
    w3allappend = w3allappend[1];
  }
  if ( w3allappend[1] && w3allappend[1].charAt(0) == '/' ){
    w3allappend = w3allappend[1].substr(1);
  }
}
return w3allappend;
}
</script>
<style type=\"text/css\" media=\"screen\">
.w3preloadtext{
color:#DDD;
font-size:3.5em;
font-family:impact,arial, sans-serif;
font-style:italic;
text-shadow: rgba(0,0,0,0) -1px 0px;
}
.w3_wrap_loader{
position:fixed;
top:0%;
bottom:0%;
left:0%;
right:0%;
background: rgba(0,0,0,0.95);
z-index:99999;
opacity:90;
-webkit-transition: opacity 400ms ease-in;
-moz-transition: opacity 400ms ease-in;
transition: opacity 400ms ease-in;
width:100%;
text-align:center;
display:flex;
flex-direction:column;
align-items:center;
justify-content:center;
pointer-events:none;
height:100%;
}
.ww3_loader{
width:100%;
text-align:center;
}
.w3_loader {
height: 8px;
width: 30%;
align-items: center;
justify-content: center;
position: relative; left: 50%;
transform: translateX(-50%);
overflow: hidden;
background-color: #DDD;
border-radius: 20px;
margin:0px;padding:0px;
}
.w3_loader:before{
height: 8px;
border-radius: 20px;
display: block;
position: absolute;
content: \"\";
left: -200px;
width: 200px;
background-color: #333;
animation: loading 1s linear infinite;
}
@keyframes loading {
from {left: -200px; width: 30%;}
50% {width: 30%;}
70% {width: 70%;}
80% { left: 50%;}
95% {left: 120%;}
to {left: 100%;}
}
</style>
";
  echo $s;
}

