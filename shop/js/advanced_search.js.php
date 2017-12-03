<?php
/* ----------------------------------------------------------------------
   $Id: advanced_search.js.php,v 1.2 2008/01/15 10:16:51 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2017 by the MyOOS Development Team
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
<script type="text/javascript" language="JavaScript"><!--

function check_form() {
  var error_message = "<?php echo $aLang['js_error']; ?>";
  var error_found = false;
  var error_field;
  var keywords = document.advanced_search.keywords.value;
  var dfrom = document.advanced_search.dfrom.value;
  var dto = document.advanced_search.dto.value;
<?php
  if ($aUser['show_price'] == 1 ) {
?>
  var pfrom = document.advanced_search.pfrom.value;
  var pto = document.advanced_search.pto.value;
<?php 
  } 
?>
  var pfrom_float;
  var pto_float;

<?php
  if ($aUser['show_price'] == 1 ) {
?>
  if ( ((keywords == '') || (keywords.length < 1)) && ((dfrom == '') || (dfrom == '<?php echo DOB_FORMAT_STRING; ?>') || (dfrom.length < 1)) && ((dto == '') || (dto == '<?php echo DOB_FORMAT_STRING; ?>') || (dto.length < 1)) && ((pfrom == '') || (pfrom.length < 1)) && ((pto == '') || (pto.length < 1)) ) {
    error_message = error_message + "<?php echo $aLang['js_at_least_one_input']; ?>";
    error_field = document.advanced_search.keywords;
    error_found = true;
  }
<?php
  } else {
?>
  if ( ((keywords == '') || (keywords.length < 1)) && ((dfrom.length < 1)) && ((dto == '') || (dto == '<?php echo DOB_FORMAT_STRING; ?>') || (dto.length < 1))  && ((pto == '') || (pto.length < 1)) ) {
    error_message = error_message + "<?php echo $aLang['js_at_least_one_input']; ?>";
    error_field = document.advanced_search.keywords;
    error_found = true;
  }
<?php
  }
?>
  if ((dfrom.length > 0) && (dfrom != '<?php echo DOB_FORMAT_STRING; ?>')) {
    if (!IsValidDate(dfrom, '<?php echo DOB_FORMAT_STRING; ?>')) {
      error_message = error_message + "<?php echo $aLang['js_invalid_from_date']; ?>";
      error_field = document.advanced_search.dfrom;
      error_found = true;
    }
  }

  if ((dto.length > 0) && (dto != '<?php echo DOB_FORMAT_STRING; ?>')) {
    if (!IsValidDate(dto, '<?php echo DOB_FORMAT_STRING; ?>')) {
      error_message = error_message + "<?php echo $aLang['js_invalid_to_date']; ?>";
      error_field = document.advanced_search.dto;
      error_found = true;
    }
  }

  if ((dfrom.length > 0) && (dfrom != '<?php echo DOB_FORMAT_STRING; ?>') && (IsValidDate(dfrom, '<?php echo DOB_FORMAT_STRING; ?>')) && (dto.length > 0) && (dto != '<?php echo DOB_FORMAT_STRING; ?>') && (IsValidDate(dto, '<?php echo DOB_FORMAT_STRING; ?>'))) {
    if (!CheckDateRange(document.advanced_search.dfrom, document.advanced_search.dto)) {
      error_message = error_message + "<?php echo $aLang['js_to_date_less_than_from_date']; ?>";
      error_field = document.advanced_search.dto;
      error_found = true;
    }
  }
<?php
  if ($aUser['show_price'] == 1 ) {
?>
  if (pfrom.length > 0) {
    pfrom_float = parseFloat(pfrom);
    if (isNaN(pfrom_float)) {
      error_message = error_message + "<?php echo $aLang['js_price_to_must_be_num']; ?>";
      error_field = document.advanced_search.pfrom;
      error_found = true;
    }
  } else {
    pfrom_float = 0;
  }

  if (pto.length > 0) {
    pto_float = parseFloat(pto);
    if (isNaN(pto_float)) {
      error_message = error_message + "<?php echo $aLang['js_price_to_must_be_num']; ?>";
      error_field = document.advanced_search.pto;
      error_found = true;
    }
  } else {
    pto_float = 0;
  }

  if ( (pfrom.length > 0) && (pto.length > 0) ) {
    if ( (!isNaN(pfrom_float)) && (!isNaN(pto_float)) && (pto_float < pfrom_float) ) {
      error_message = error_message + "<?php echo $aLang['js_price_to_less_than_price_from']; ?>";
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
  if ($aUser['show_price'] == 1 ) {
?>
    RemoveFormatString(document.advanced_search.dfrom, "<?php echo DOB_FORMAT_STRING; ?>");
<?php
  }
?>
    RemoveFormatString(document.advanced_search.dto, "<?php echo DOB_FORMAT_STRING; ?>");
    return true;
  }
}
//--></script>
