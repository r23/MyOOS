<?php
/* ----------------------------------------------------------------------
   $Id: form_check.js.php 216 2013-04-02 08:24:45Z r23 $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: form_check.js.php,v 1.8 2003/02/10 22:30:55 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */
?>

<script language="javascript"><!--

var submitted = false;

function check_form() {
  var error = 0;
  var error_message = "<?php echo decode($aLang['js_error']); ?>";

  if (submitted == true) {
    alert("<?php echo decode($aLang['js_error_submitted']); ?>");
    return FALSE;  }

  var first_name = document.account_edit.firstname.value;
  var last_name = document.account_edit.lastname.value;

<?php
   if (ACCOUNT_DOB == 'true') echo '  var dob = document.account_edit.dob.value;' . "\n";
?>

  var email_address = document.account_edit.email_address.value;
  var street_address = document.account_edit.street_address.value;
  var postcode = document.account_edit.postcode.value;
  var city = document.account_edit.city.value;
  var telephone = document.account_edit.telephone.value;
<?php
  if ($show_password == 'true') {
?>
  var password = document.account_edit.password.value;
  var confirmation = document.account_edit.confirmation.value;
<?php
   }
   if (ACCOUNT_GENDER == 'true') {
?>
  if (document.account_edit.elements['gender'].type != "hidden") {
    if (document.account_edit.gender[0].checked || document.account_edit.gender[1].checked) {
    } else {
      error_message = error_message + "<?php echo decode($aLang['js_gender']); ?>";
      error = 1;
    }
  }
<?php
  }
?>

  if (document.account_edit.elements['firstname'].type != "hidden") {
    if (first_name == '' || first_name.length < <?php echo ENTRY_FIRST_NAME_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo decode($aLang['js_first_name']); ?>";
      error = 1;
    }
  }

  if (document.account_edit.elements['lastname'].type != "hidden") {
    if (last_name == '' || last_name.length < <?php echo ENTRY_LAST_NAME_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo decode($aLang['js_last_name']); ?>";
      error = 1;
    }
  }

<?php
   if (ACCOUNT_DOB == 'true') {
?>
  if (document.account_edit.elements['dob'].type != "hidden") {
    if (dob == '' || dob.length < <?php echo ENTRY_DOB_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo decode($aLang['js_dob']); ?>";
      error = 1;
    }
  }
<?php
  }
?>

  if (document.account_edit.elements['email_address'].type != "hidden") {
    if (email_address == '' || email_address.length < <?php echo ENTRY_EMAIL_ADDRESS_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo decode($aLang['js_email_address']); ?>";
      error = 1;
    }
  }

  if (document.account_edit.elements['street_address'].type != "hidden") {
    if (street_address == '' || street_address.length < <?php echo ENTRY_STREET_ADDRESS_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo decode($aLang['js_address']); ?>";
      error = 1;
    }
  }

  if (document.account_edit.elements['postcode'].type != "hidden") {
    if (postcode == '' || postcode.length < <?php echo ENTRY_POSTCODE_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo decode($aLang['js_post_code']); ?>";
      error = 1;
    }
  }

  if (document.account_edit.elements['city'].type != "hidden") {
    if (city == '' || city.length < <?php echo ENTRY_CITY_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo decode($aLang['js_city']); ?>";
      error = 1;
    }
  }


  if (document.account_edit.elements['country'].type != "hidden") {
    if (document.account_edit.country.value == 0) {
      error_message = error_message + "<?php echo decode($aLang['js_country']); ?>";
      error = 1;
    }
  }

  if (document.account_edit.elements['telephone'].type != "hidden") {
    if (telephone == '' || telephone.length < <?php echo ENTRY_TELEPHONE_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo decode($aLang['js_telephone']); ?>";
      error = 1;
    }
  }

<?php
  if ($show_password == 'true') {
?>

  if (document.account_edit.elements['password'].type != "hidden") {
    if ((password != confirmation) || (password == '' || password.length < <?php echo ENTRY_PASSWORD_MIN_LENGTH; ?>)) {
      error_message = error_message + "<?php echo decode($aLang['js_password']); ?>";
      error = 1;
    }
  }
<?php
  }
?>

  if (error == 1) {
    alert(error_message);
    return FALSE;  } else {
    submitted = TRUE;
    return true;
  }
}
//--></script>
