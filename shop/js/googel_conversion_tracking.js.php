<?php
/* ----------------------------------------------------------------------
   $Id: googel_conversion_tracking.js.php,v 1.1 2007/06/07 16:33:21 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */
?>
<!-- Google Code for Purchase Conversion Page -->
<script language="JavaScript" type="text/javascript">
<!--
var google_conversion_id = <?php echo GOOGLE_CONVERSION_ID; ?>;
var google_conversion_language = "<?php echo GOOGLE_CONVERSION_LANGUAGE; ?>";
var google_conversion_format = "1";
var google_conversion_color = "666666";
if (1.0) {
  var google_conversion_value = 1.0;
}
var google_conversion_label = "Purchase";
//-->
</script>

<?php if ($request_type == 'SSL') { ?>

  <script language="JavaScript" src="https://www.googleadservices.com/pagead/conversion.js"></script>
  <noscript><img height=1 width=1 border=0 src="https://www.googleadservices.com/pagead/conversion/<?php echo GOOGLE_CONVERSION_ID; ?>/?value=1.0&label=Purchase&script=0"></noscript>

<?php }else { ?>

  <script language="JavaScript" src="http://www.googleadservices.com/pagead/conversion.js"></script>
  <noscript><img height=1 width=1 border=0 src="http://www.googleadservices.com/pagead/conversion/<?php echo GOOGLE_CONVERSION_ID; ?>/?value=1.0&label=Purchase&script=0"></noscript>

<?php } ?>

