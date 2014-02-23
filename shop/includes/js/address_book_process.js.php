<?php
/* ----------------------------------------------------------------------
   $Id: address_book_process.js.php 216 2013-04-02 08:24:45Z r23 $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: address_book_process.php,v 1.73 2003/02/13 01:58:23 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */
?>

<script language="javascript"><!--
function check_form() {
  var error = 0;
  var error_message = "<?php echo decode($aLang['js_error']); ?>";

  var firstname = document.add_entry.firstname.value;
  var lastname = document.add_entry.lastname.value;
  var street_address = document.add_entry.street_address.value;
  var postcode = document.add_entry.postcode.value;
  var city = document.add_entry.city.value;

<?php
 if (ACCOUNT_GENDER == 'true') {
?>
  if (document.add_entry.elements['gender'].type != "hidden") {
    if (document.add_entry.gender[0].checked || document.add_entry.gender[1].checked) {
    } else {
      error_message = error_message + "<?php echo decode($aLang['js_gender']); ?>";
      error = 1;
    }
  }
<?php
 }
?>
  if (firstname == "" || firstname.length < <?php echo ENTRY_FIRST_NAME_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo decode($aLang['js_first_name']); ?>";
    error = 1;
  }

  if (lastname == "" || lastname.length < <?php echo ENTRY_LAST_NAME_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo decode($aLang['js_last_name']); ?>";
    error = 1;
  }

  if (street_address == "" || street_address.length < <?php echo ENTRY_STREET_ADDRESS_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo decode($aLang['js_address']); ?>";
    error = 1;
  }

  if (postcode == "" || postcode.length < <?php echo ENTRY_POSTCODE_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo decode($aLang['js_post_code']); ?>";
    error = 1;
  }

  if (city == "" || city.length < <?php echo ENTRY_CITY_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo decode($aLang['js_city']); ?>";
    error = 1;
  }
<?php
  if (ACCOUNT_STATE == 'true') {
?>
  if (document.add_entry.state.value == "" || document.add_entry.state.length < <?php echo ENTRY_STATE_MIN_LENGTH; ?> ) {
     error_message = error_message + "<?php echo decode($aLang['js_state']); ?>";
     error = 1;
  }
<?php
  }
?>

  if (document.add_entry.country.value == 0) {
    error_message = error_message + "<?php echo decode($aLang['js_country']); ?>";
    error = 1;
  }

  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
//--></script>
