<style type="text/css">
#w3all_phpbb_mchat_iframe{
/* activate this only if on shortcode mode */
/* position:absolute;top:0px; */
}
#w3allmchatshortwrapper{
z-index:999999;
position: fixed;
right:10px;
bottom:20px;
margin:0px;
padding:0px;
height:90%;
display:none;
}
.w3mchatbox{
overflow: hidden;
background: #E1EBF2;
padding:0px;
height:100%;
}
.w3mchatbox-inner{
width: 350px;
max-width:350px;
padding: 10px;
/*border: 1px solid #C7C3BF;*/
margin:0px;
}
.w3mchattoggle{ 
z-index:99999999;
position: fixed;
right:10px;
bottom:10px;
cursor:pointer;
color: #f3f3f3 !important;
text-transform: uppercase;
background: #105289;
padding: 6px;
border: 1px solid #000;
border-radius: 6px;
display: inline-block;
}
.w3mchattoggle:hover{
color: #fff !important;
border-color: #000 !important;
transition: all 0.3s ease 0s;
}
.w3inmchattoggle{
margin:0px;padding:0px;
}
}
</style>
<script>
// pre loader js code for iframe content 
jQuery( document ).ready(function() {
 jQuery('#w3_toogle_wrap_loader').attr( "class", "w3_wrap_loader" );
 jQuery('#w3all_phpbb_mchat_iframe').on('load', function () {
 jQuery('#w3_toogle_wrap_loader').attr( "class", "w3_no_wrap_loader" );
});
});

</script>
<style type="text/css" media="screen">
.w3_no_wrap_loader{
display:none;
}
.w3_wrap_loader{
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
.w3_loader {
height: 8px;
width: 30%;
position: relative; left: 50%;
transform: translateX(-50%);
overflow: hidden;
background-color: #ddd;
border-radius: 20px;
margin:0px;padding:0px;
}
.w3_loader:before{
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

<script>
	<?php global $w3all_url_to_cms; echo 'var w3UrlTophpBB = "'.$w3all_url_to_cms.'";'; ?>
jQuery(document).ready(function(){
  jQuery(function(){
    jQuery(".w3mchattoggle").click(function (event) {
      jQuery(this).text(function(i, text){
       if(text === "Close chat"){
       
       	 jQuery("#w3all_phpbb_mchat_iframe").attr("src", "");
      	}
       if(text === "Open chat"){
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
