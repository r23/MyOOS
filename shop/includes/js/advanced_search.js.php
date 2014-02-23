<?php
/* ----------------------------------------------------------------------
   $Id: advanced_search.js.php 289 2013-04-12 16:08:45Z r23 $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: advanced_search.php,v 1.49 2003/02/13 04:23:22 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

?>

<script type="text/javascript" language="JavaScript" src="js/general.js"></script>
<script type="text/javascript" language="JavaScript" src="js/popup_window.js"></script>
<script type="text/javascript" language="JavaScript">
/*<![CDATA[*/
/*ajax contrib start*/
var req;

function loadXMLDoc(key) {

<?php
  $sUrl = oos_href_link($aContents['quickfind'], '', 'NONSSL', true, false);
  if (strpos($sUrl, '&amp;') !== false) $sUrl = str_replace('&amp;', '&', $sUrl);
?>
   var url="<?php echo $sUrl; ?>&keywords="+key;

   // Internet Explorer

   try { req = new ActiveXObject("Msxml2.XMLHTTP"); }
   catch(e) { 
      try { req = new ActiveXObject("Microsoft.XMLHTTP"); }
      catch(oc) { req = null; }
   } 

   // Mozailla/Safari 
   if (!req && typeof XMLHttpRequest != "undefined") { req = new XMLHttpRequest(); }

   // Call the processChange() function when the page has loaded
   if (req != null) {
      req.onreadystatechange = processChange;
      req.open("GET", url, true);
      req.send("");
   }
}

function processChange() {
   // The page has loaded and the HTTP status code is 200 OK
   if (req.readyState == 4 && req.status == 200) {

      // Write the contents of this URL to the searchResult layer
      getObject("quicksearch").innerHTML = req.responseText;
   }
}

function getObject(name) {
   var ns4 = (document.layers) ? true : false;
   var w3c = (document.getElementById) ? true : false;
   var ie4 = (document.all) ? true : false;

   if (ns4) return eval('document.' + name);
   if (w3c) return document.getElementById(name);
   if (ie4) return eval('document.all.' + name);
   return false; 
}


window.onload = function() {
   getObject("keywords").focus();
}
/*ajax contrib end*/
function check_form() {
  var error_message = "<?php echo decode($aLang['js_error']); ?>";
  var error_found = false;
  var error_field;
  var keywords = document.advanced_search.keywords.value;
  var dfrom = document.advanced_search.dfrom.value;
  var dto = document.advanced_search.dto.value;
<?php
  if ($_SESSION['member']->group['show_price'] == 1 ) {
?>
  var pfrom = document.advanced_search.pfrom.value;
  var pto = document.advanced_search.pto.value;
<?php 
  } 
?>
  var pfrom_float;
  var pto_float;

<?php
  if ($_SESSION['member']->group['show_price'] == 1 ) {
?>
  if ( ((keywords == '') || (keywords.length < 1)) && ((dfrom == '') || (dfrom == '<?php echo DOB_FORMAT_STRING; ?>') || (dfrom.length < 1)) && ((dto == '') || (dto == '<?php echo DOB_FORMAT_STRING; ?>') || (dto.length < 1)) && ((pfrom == '') || (pfrom.length < 1)) && ((pto == '') || (pto.length < 1)) ) {
    error_message = error_message + "<?php echo decode($aLang['js_at_least_one_input']); ?>";
    error_field = document.advanced_search.keywords;
    error_found = true;
  }
<?php
  } else {
?>
  if ( ((keywords == '') || (keywords.length < 1)) && ((dfrom.length < 1)) && ((dto == '') || (dto == '<?php echo DOB_FORMAT_STRING; ?>') || (dto.length < 1))  && ((pto == '') || (pto.length < 1)) ) {
    error_message = error_message + "<?php echo decode($aLang['js_at_least_one_input']); ?>";
    error_field = document.advanced_search.keywords;
    error_found = true;
  }
<?php
  }
?>
  if ((dfrom.length > 0) && (dfrom != '<?php echo DOB_FORMAT_STRING; ?>')) {
    if (!IsValidDate(dfrom, '<?php echo DOB_FORMAT_STRING; ?>')) {
      error_message = error_message + "<?php echo decode($aLang['js_invalid_from_date']); ?>";
      error_field = document.advanced_search.dfrom;
      error_found = true;
    }
  }

  if ((dto.length > 0) && (dto != '<?php echo DOB_FORMAT_STRING; ?>')) {
    if (!IsValidDate(dto, '<?php echo DOB_FORMAT_STRING; ?>')) {
      error_message = error_message + "<?php echo decode($aLang['js_invalid_to_date']); ?>";
      error_field = document.advanced_search.dto;
      error_found = true;
    }
  }

  if ((dfrom.length > 0) && (dfrom != '<?php echo DOB_FORMAT_STRING; ?>') && (IsValidDate(dfrom, '<?php echo DOB_FORMAT_STRING; ?>')) && (dto.length > 0) && (dto != '<?php echo DOB_FORMAT_STRING; ?>') && (IsValidDate(dto, '<?php echo DOB_FORMAT_STRING; ?>'))) {
    if (!CheckDateRange(document.advanced_search.dfrom, document.advanced_search.dto)) {
      error_message = error_message + "<?php echo decode($aLang['js_to_date_less_than_from_date']); ?>";
      error_field = document.advanced_search.dto;
      error_found = true;
    }
  }
<?php
  if ($_SESSION['member']->group['show_price'] == 1 ) {
?>
  if (pfrom.length > 0) {
    pfrom_float = parseFloat(pfrom);
    if (isNaN(pfrom_float)) {
      error_message = error_message + "<?php echo decode($aLang['js_price_to_must_be_num']); ?>";
      error_field = document.advanced_search.pfrom;
      error_found = true;
    }
  } else {
    pfrom_float = 0;
  }

  if (pto.length > 0) {
    pto_float = parseFloat(pto);
    if (isNaN(pto_float)) {
      error_message = error_message + "<?php echo decode($aLang['js_price_to_must_be_num']); ?>";
      error_field = document.advanced_search.pto;
      error_found = true;
    }
  } else {
    pto_float = 0;
  }

  if ( (pfrom.length > 0) && (pto.length > 0) ) {
    if ( (!isNaN(pfrom_float)) && (!isNaN(pto_float)) && (pto_float < pfrom_float) ) {
      error_message = error_message + "<?php echo decode($aLang['js_price_to_less_than_price_from']); ?>";
      error_field = document.advanced_search.pto;
      error_found = true;
    }
  }
<?php
  }
?>
  if (error_found == true) {
    alert(error_message);
    error_field.focus();
    return false;
  } else {
<?php
  if ($_SESSION['member']->group['show_price'] == 1 ) {
?>
    RemoveFormatString(document.advanced_search.dfrom, "<?php echo DOB_FORMAT_STRING; ?>");
<?php
  }
?>
    RemoveFormatString(document.advanced_search.dto, "<?php echo DOB_FORMAT_STRING; ?>");
    return true;
  }
}
/*]]>*/
</script>
