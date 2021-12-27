<?php defined( 'ABSPATH' ) or die( 'forbidden' );
global $w3all_iframe_custom_w3fancyurl,$w3all_url_to_cms,$w3all_iframe_phpbb_link_yn,$w3all_iframe_custom_top_gap,$w3cookie_domain,$wp_w3all_forum_folder_wp;
$w3allhomeurl = get_home_url();
$current_user = wp_get_current_user();
$w3all_url_to_cms_clean = $w3all_url_to_cms;
$w3all_url_to_cms_clean0 = strpos($w3all_url_to_cms_clean, 'https://') !== false ? str_replace('https://', 'http://', $w3all_url_to_cms_clean) : str_replace('http://', 'https://', $w3all_url_to_cms_clean);
     
if( preg_match('/[^0-9]/',$w3phpbbuid) OR preg_match('/[^a-z]/',$w3phpbbwatch) OR preg_match('/[^a-z]/',$w3phpbbunwatch) OR preg_match('/[^A-Za-z]/',$w3iu_folder) OR preg_match('/[^A-Za-z]/',$w3iu) OR preg_match('/[^0-9]/',$w3phpbb_start) OR preg_match('/[^0-9]/',$w3topic_id) OR preg_match('/[^0-9]/',$w3forum_id) OR preg_match('/[^0-9]/',$w3post_id) OR preg_match('/[^0-9A-Za-z]/',$w3mode) OR preg_match('/[^0-9A-Za-z]/',$w3phpbbsid) ){

	die("Something goes wrong with your URL request, <a href=\"$w3allhomeurl\">please leave this page</a>.");
} 


function w3all_enqueue_scripts() { 
 wp_enqueue_script("jquery");
}

//function wp_w3all_add_ajax() {
	global $w3all_url_to_cms, $wp_w3all_forum_folder_wp,$w3all_iframe_phpbb_link_yn, $w3allhomeurl;
	
	if ($w3all_iframe_phpbb_link_yn > 0){
		$w3all_url_to_phpbb_ib = $w3allhomeurl . "/" . $wp_w3all_forum_folder_wp . "/?i=pm&folder=inbox";
	} else {
	        $w3all_url_to_phpbb_ib = $w3all_url_to_cms . "/ucp.php?i=pm&folder=inbox";
         }


 add_action('wp_enqueue_scripts', 'w3all_enqueue_scripts');
 
 
 ?>


<script>
	<?php echo 'var w3UrlTophpBB = "'.$w3all_url_to_cms.'";'; ?>
	document.domain = 'localhost';

jQuery(document).ready(function(){
  jQuery( "body" ).prepend( "<div id=\"w3_toogle_wrap_loader\" class=\"w3_no_wrap_loader\"><div class=\"w3all_loader\"></div></div><div class=\"w3_phpbb_ifr_all_over_btn\"></div>" );
  jQuery( ".w3_phpbb_ifr_all_over_btn" ).prepend( "<div class=\"w3all_wrap_phpbbALLover_iframe\"><iframe id=\"w3all_phpbbALLover_iframe\" name=\"w3all_phpbbALLover_iframe\" style=\"width:1px;min-width:100%;*width:100%;border:0;\" scrolling=\"no\" src=\"https://localhost/phpbb3\"></iframe></div>" );


//<iframe id="w3all_phpbbALLover_iframe" name="w3all_phpbbALLover_iframe" style="width:1px;min-width:100%;*width:100%;border:0;" scrolling="no" src="<?php echo $w3all_url_to_cms; ?>"></iframe>




// pre loader js code for iframe content 
  jQuery("#w3_toogle_wrap_loader").attr( "class", "w3_add_wrap_loader" );
  jQuery("#w3all_phpbbALLover_iframe").on("load", function () {
  jQuery("#w3_toogle_wrap_loader").attr( "class", "w3_sub_wrap_loader" );
 });
});

</script>
<style type="text/css" media="screen">

.w3_phpbb_ifr_all_over_btn{
	min-width:500px;
}
	
.w3all_wrap_phpbbALLover_iframe,.w3all_phpbbALLover_iframe{
position:absolute;
top:0%;
bottom:0%;
left:0%;
right:0%;
z-index: 98;
padding:100px;
width:100%;
min-width:500px;
}
.w3all_phpbbALLover_iframe{
	padding:100px;
	background-color:#000;
}

.w3_sub_wrap_loader{
display:none;
}
.w3_add_wrap_loader{
position:absolute;
top:0%;
bottom:0%;
left:0%;
right:0%;
background: rgba(0,0,0,0.8);
z-index: 99999999;
opacity:100;
-webkit-transition: opacity 400ms ease-in;
-moz-transition: opacity 400ms ease-in;
transition: opacity 400ms ease-in;
width:100%;
display:flex;
align-items: center;
text-align:center;
pointer-events: none;
}
.w3all_loader {
height: 8px;
width: 30%;
position: relative; left: 50%;
transform: translateX(-50%);
overflow: hidden;
background-color: #ddd;
border-radius: 20px;
margin:0px;padding:0px;
}
.w3all_loader:before{
height: 8px;
border-radius: 20px;
display: block;
position: absolute;
content: "";
left: -200px;
width: 200px;
background-color: #2980b9;
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

<?php

 echo "<script type=\"text/javascript\">

		var wp_u_logged = ".$current_user->ID.";
	
		function w3all_ajaxup_from_phpbb(res){
			var w3all_phpbb_u_logged  = /#w3all_phpbb_u_logged=1/ig.exec(res);
			 if( w3all_phpbb_u_logged == null && wp_u_logged > 0 || wp_u_logged == 0 && res.indexOf('#w3all_phpbb_u_logged=1') > -1 ){
			 jQuery('#w3_toogle_wrap_loader').attr( \"class\", \"w3_wrap_loader\" );
			 document.location.replace('".$w3allhomeurl."/index.php/".$wp_w3all_forum_folder_wp."/');
       }
       if(wp_u_logged == 0 && res.indexOf('#w3all_phpbb_u_logged=1') > -1){
       document.location.replace('".$w3allhomeurl."/index.php/".$wp_w3all_forum_folder_wp."/');
       } 
       
			var w3all_phpbbpmcount = /.*(#w3all_phpbbpmcount)=([0-9]+).*/ig.exec(res);
      if(w3all_phpbbpmcount !== null){
         w3all_ajaxup_from_phpbb_do(w3all_phpbbpmcount[2]);
       }

      var w3all_lochash = /.*(#w3all_lochash)=([0-9]+).*/ig.exec(res);
      if(w3all_lochash !== null && w3all_lochash[2] != 0){ 
         jQuery('html, body').animate({ scrollTop: w3all_lochash[2]}, 400);
       } else {
         jQuery('html, body').animate({ scrollTop: ".$w3all_iframe_custom_top_gap."}, 400);
       }
       
   } // END w3all_ajaxup_from_phpbb(res){
   
jQuery(document).ready(function(){
   
   // array() of allowed domains
   
    var w3all_orig_domains = ['".$w3all_orig."','".$w3all_orig_www."','".$w3all_url_to_cms_clean."','".$w3all_url_to_cms_clean0."','https://localhost','http://localhost'];

    iFrameResize({
				log         : false,
				inPageLinks : true,
        targetOrigin: '".$w3all_url_to_cms."', 
        checkOrigin : w3all_orig_domains, 
     // heightCalculationMethod: 'documentElementOffset', // If iframe not resize correctly, un-comment (or change with one of others available resize methods) 
     // see: https://github.com/davidjbradshaw/iframe-resizer#heightcalculationmethod
       
				onMessage : function(messageData){ // Callback fn when message is received 	
	   
				// w3all simple js check and redirects
				var w3all_passed_url = messageData.message.toString();
			  var w3all_ck = \"".$_SERVER['SERVER_NAME']."\";
        var w3all_pass_ext  = (w3all_passed_url.indexOf(w3all_ck) > -1);    
        var w3all_ck_preview = (w3all_passed_url.indexOf('preview') > -1);

if (w3all_ck_preview == false) { // or the phpBB passed preview link, will be recognized as external, and preview will redirect to full forum url instead
 // so these are maybe, external iframe redirects
    if (w3all_pass_ext == true) {
     	window.location.replace(w3all_passed_url); 
     }
    if (/^(f|ht)tps?:\/\//i.test(w3all_passed_url)) {
      window.location.replace(w3all_passed_url); 
    }
}


}
//,
//scrollCallback: function(x,y){
//return false;
//}
});
});
</script>";
?>
<script>

jQuery(document).ready(function(){
  jQuery(function(){
    jQuery(".w3mchattoggle").click(function (event) {
      jQuery(this).text(function(i, text){
       if(text === "Close forum"){
       
       	 jQuery("#w3all_phpbbALLover_iframe").attr("src", "");
      	}
       if(text === "Open forum"){
       	jQuery('#w3_toogle_wrap_loader').attr( "class", "w3_wrap_loader" );
       	 jQuery("#w3all_phpbb_mchat_iframe").attr("src", w3UrlTophpBB+"/app.php/mchat#w3allmchatif");
      	}
          return text === "Open chat" ? "Close chat" : "Open chat";
     })
   });
 })

if( typeof w3all_create_mchattoggleBox != 'undefined' ){	
    jQuery( "#content" ).append( "<div class='w3mchattoggle' style='text-align:center'><div class='w3inmchattoggle'>Open chat</div></div>" );
        jQuery(".w3mchattoggle").click(function(){
            jQuery(".w3mchatbox").animate({
                width: "toggle"
            });
        });
   }
})
</script>
