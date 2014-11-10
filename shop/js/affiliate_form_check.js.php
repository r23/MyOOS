<?php
/* ----------------------------------------------------------------------
   $Id: affiliate_form_check.js.php,v 1.1 2007/06/07 16:33:21 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
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
    return false;
  }

  var a_first_name = document.affiliate_signup.a_firstname.value;
  var a_last_name = document.affiliate_signup.a_lastname.value;

<?php
   if (ACCOUNT_DOB == 'true') echo '  var a_dob = document.affiliate_signup.a_dob.value;' . "\n";
?>

  var a_email_address = document.affiliate_signup.a_email_address.value;
  var a_street_address = document.affiliate_signup.a_street_address.value;
  var a_postcode = document.affiliate_signup.a_postcode.value;
  var a_city = document.affiliate_signup.a_city.value;
  var a_telephone = document.affiliate_signup.a_telephone.value;
  var a_password = document.affiliate_signup.a_password.value;
  var a_confirmation = document.affiliate_signup.a_confirmation.value;

<?php
   if (ACCOUNT_GENDER == 'true') {
?>
  if (document.affiliate_signup.elements['a_gender'].type != "hidden") {
    if (document.affiliate_signup.a_gender[0].checked || document.affiliate_signup.a_gender[1].checked) {
    } else {
      error_message = error_message + "<?php echo decode($aLang['js_gender']); ?>";
      error = 1;
    }
  }
<?php
  }
?>

  if (document.affiliate_signup.elements['a_firstname'].type != "hidden") {
    if (a_first_name == '' || a_first_name.length < <?php echo ENTRY_FIRST_NAME_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo decode($aLang['js_first_name']); ?>";
      error = 1;
    }
  }

  if (document.affiliate_signup.elements['a_lastname'].type != "hidden") {
    if (a_last_name == '' || a_last_name.length < <?php echo ENTRY_LAST_NAME_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo decode($aLang['js_last_name']); ?>";
      error = 1;
    }
  }

<?php
   if (ACCOUNT_DOB == 'true') {
?>
  if (document.affiliate_signup.elements['a_dob'].type != "hidden") {
    if (a_dob == '' || a_dob.length < <?php echo ENTRY_DOB_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo decode($aLang['js_dob']); ?>";
      error = 1;
    }
  }
<?php
  }
?>

  if (document.affiliate_signup.elements['a_email_address'].type != "hidden") {
    if (a_email_address == '' || a_email_address.length < <?php echo ENTRY_EMAIL_ADDRESS_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo decode($aLang['js_email_address']); ?>";
      error = 1;
    }
  }

  if (document.affiliate_signup.elements['a_street_address'].type != "hidden") {
    if (a_street_address == '' || a_street_address.length < <?php echo ENTRY_STREET_ADDRESS_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo $aLang['js_address']; ?>";
      error = 1;
    }
  }

  if (document.affiliate_signup.elements['a_postcode'].type != "hidden") {
    if (a_postcode == '' || a_postcode.length < <?php echo ENTRY_POSTCODE_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo $aLang['js_post_code']; ?>";
      error = 1;
    }
  }

  if (document.affiliate_signup.elements['a_city'].type != "hidden") {
    if (a_city == '' || a_city.length < <?php echo ENTRY_CITY_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo $aLang['js_city']; ?>";
      error = 1;
    }
  }


  if (document.affiliate_signup.elements['a_country'].type != "hidden") {
    if (document.affiliate_signup.a_country.value == 0) {
      error_message = error_message + "<?php echo $aLang['js_country']; ?>";
      error = 1;
    }
  }

  if (document.affiliate_signup.elements['a_telephone'].type != "hidden") {
    if (a_telephone == '' || a_telephone.length < <?php echo ENTRY_TELEPHONE_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo $aLang['js_telephone']; ?>";
      error = 1;
    }
  }

  if (document.affiliate_signup.elements['a_password'].type != "hidden") {
    if ((a_password != a_confirmation) || (a_password == '' || a_password.length < <?php echo ENTRY_PASSWORD_MIN_LENGTH; ?>)) {
      error_message = error_message + "<?php echo $aLang['js_password']; ?>";
      error = 1;
    }
  }

  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    submitted = true;
    return true;
  }
}
//--></script>
