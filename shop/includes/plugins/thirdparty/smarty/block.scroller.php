<?php
/**
 * Smarty plugin
 * -----------------------------------------
 * File:    block.scroller.php
 * Type:    block
 * Name:    scroller
 * Params:  speed      scrolling speed
 *          width      width of the box
 *          height     height of the box
 * Author:  Andr\x{00E9} Rabold
 * Purpose: scrolls a text
 * Remarks: Javascript code was stolen from 
 *          http://www.portalzine.de
 * -----------------------------------------
 */
function smarty_block_scroller($params, $content, &$smarty)
{
  extract($params);

  if (!isset($speed)) $speed = "4";
  if (!isset($width)) $width = "140";
  if (!isset($height)) $height = "160"; 
  
  
  // forward the posted values to a preprocessor
  if (strval($content) != "") {
    if (strval($id) == "") {
      $id = (uniqid(""));
    }
    ?>
  <div style="height:<?php echo intval($height)+20?>;">
    <script language="JavaScript1.2" type="text/javascript">
    <!--
    var speed<?php echo $id ?>=<?php echo $speed ?>;
    iens6=document.all && document.getElementById;
    ns4=document.layers;
    contentheight<?php echo $id ?>=null;
    
    if (iens6) {
      document.open();
      document.write('<div id="scroller_container<?php echo $id ?>" style="position:absolute;width:<?php echo $width ?>;height:<?php echo $height ?>;overflow:hidden;border:none">');
      document.write('<div id="scroller_content<?php echo $id ?>" style="position:absolute;width:<?php echo $width ?>;left:0;top:0">');
    }
    //-->
    </script>
    <ilayer name="nscontainer<?php echo $id ?>" border="0" width="<?php echo $width ?>" height="<?php echo $height ?>" clip="0,0,<?php echo $width ?>,<?php echo $height ?>">
      <layer name="nscontent<?php echo $id ?>" border="0" width="<?php echo intval($width) + 5 ?>" height="<?php echo $height ?>" visibility=hidden>
        <!--INSERT CONTENT HERE-->
          <?php echo $content ?>
        <!--END CONTENT-->
      </layer>
    </ilayer>
    <script language="JavaScript1.2" type="text/javascript">
      <!--
      if (iens6) {
        document.write('</div></div>');
        document.close();
        var crossobj<?php echo $id ?>      = document.getElementById? document.getElementById("scroller_content<?php echo $id ?>") : document.all.scroller_content<?php echo $id ?>;
      }
      else if (ns4) {
        var crossobj<?php echo $id ?>      = document.nscontainer<?php echo $id ?>.document.nscontent<?php echo $id ?>;
      }
    
      function scroller_movedown<?php echo $id ?>() {
        if (iens6 && parseInt(crossobj<?php echo $id ?>.style.top) >= (contentheight<?php echo $id ?>*(-1)+100)) {
          crossobj<?php echo $id ?>.style.top = parseInt(crossobj<?php echo $id ?>.style.top)-speed<?php echo $id ?>;
        }
        else if (ns4 && crossobj<?php echo $id ?>.top >= (contentheight<?php echo $id ?>*(-1)+100)) {
          crossobj<?php echo $id ?>.top -= speed<?php echo $id ?>;
        }
        movedownvar<?php echo $id ?> = setTimeout("scroller_movedown<?php echo $id ?>()",100);
      }
    
      function scroller_moveup<?php echo $id ?>() {
        if (iens6 && parseInt(crossobj<?php echo $id ?>.style.top) <= 0) {
          crossobj<?php echo $id ?>.style.top = parseInt(crossobj<?php echo $id ?>.style.top)+speed<?php echo $id ?>;
        }
        else if (ns4 && crossobj<?php echo $id ?>.top <= 0) {
          crossobj<?php echo $id ?>.top += speed<?php echo $id ?>;
        }
        moveupvar<?php echo $id ?> = setTimeout("scroller_moveup<?php echo $id ?>()",100);
      }
    
      function scroller_clickstop<?php echo $id ?>() {
        if (window.moveupvar<?php echo $id ?>)   clearTimeout(window.moveupvar<?php echo $id ?>);
        if (window.movedownvar<?php echo $id ?>) clearTimeout(window.movedownvar<?php echo $id ?>);
      }
    
      function scroller_clicktop<?php echo $id ?>() {
        scroller_clickstop<?php echo $id ?>();
        if (iens6)
          crossobj<?php echo $id ?>.style.top = 0;
        else if (ns4)
          crossobj<?php echo $id ?>.top = 0;
      }
    
      function getcontent_height<?php echo $id ?>() {
        if (iens6)
          contentheight<?php echo $id ?> = crossobj<?php echo $id ?>.offsetHeight;
        else if (ns4)
          document.nscontainer<?php echo $id ?>.document.nscontent<?php echo $id ?>.visibility = "show";
      }

      function scroller_clickup<?php echo $id ?>() {
        if (contentheight<?php echo $id ?> == null)
          getcontent_height<?php echo $id ?>();
        if (window.moveupvar<?php echo $id ?>) { 
          clearTimeout(window.moveupvar<?php echo $id ?>); 
          moveupvar<?php echo $id ?> = null;
          return; 
        }
        if (window.movedownvar<?php echo $id ?>) {
          clearTimeout(window.movedownvar<?php echo $id ?>);
          movedownvar<?php echo $id ?> = null;
        }
        scroller_moveup<?php echo $id ?>();
      }

      function scroller_clickdown<?php echo $id ?>() {
        if (contentheight<?php echo $id ?> == null)
          getcontent_height<?php echo $id ?>();
        if (window.moveupvar<?php echo $id ?>) { 
          clearTimeout(window.moveupvar<?php echo $id ?>); 
          moveupvar<?php echo $id ?> = null;
        }
        if (window.movedownvar<?php echo $id ?>) {
          clearTimeout(window.movedownvar<?php echo $id ?>);
          movedownvar<?php echo $id ?> = null;
          return; 
        }
        scroller_movedown<?php echo $id ?>();
      }
      
      if (iens6) {
        document.open();
        document.write('<div align="right" style="width:<?php echo $width ?>;position:relative;top:<?php echo intval($height)+2 ?>">');
        document.write('  <div style="background-color:black"><img src="images/global.spacer.gif" width="<?php echo $width ?>" height="1" /></div>');
        document.write('  <a style="cursor:hand;" onclick="javascript:scroller_clickdown<?php echo $id ?>()"><img alt="Scroll down" src="images/scroll_down.gif" border="0" width="12" height="13"></a>');
        document.write('  <a style="cursor:hand;" onclick="javascript:scroller_clickup<?php echo $id ?>()"><img alt="Scroll up" src="images/scroll_up.gif" border="0" width="12" height="13"></a>');
        document.write('  <a style="cursor:hand;" onclick="javascript:scroller_clickstop<?php echo $id ?>()"><img alt="Stop scrolling" src="images/scroll_stop.gif" border="0" width="12" height="13"></a>');
        document.write('  <a style="cursor:hand;" onclick="javascript:scroller_clicktop<?php echo $id ?>()"><img alt="Back to top" src="images/scroll_top.gif" border="0" width="12" height="13"></a>');
        document.write('</div>');
        document.close();
      }
        
      //-->
    </script>
  </div>
    <?php
  } 
}
?>