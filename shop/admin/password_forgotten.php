<?php
/* ----------------------------------------------------------------------
   $Id: password_forgotten.php,v 1.1 2007/06/08 17:14:41 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: login.php,v 1.17 2003/02/14 12:57:29 dgw_ 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/main.php';

  if (isset($_GET['action']) && ($_GET['action'] == 'process')) {
    $log_times = $_POST['log_times']+1;
    if ($log_times >= 4) {
      $_SESSION['password_forgotten'] = 'password';
    }

// Check if email exists
    $admintable = $oostable['admin'];
    $check_admin_result = $dbconn->Execute("SELECT admin_id as check_id, admin_firstname as check_firstname, admin_lastname as check_lastname, admin_email_address as check_email_address FROM $admintable WHERE admin_email_address = '" . oos_db_input($email_address) . "'");
    if (!$check_admin_result->RecordCount()) {
      $login = 'fail';
    } else {
      $check_admin = $check_admin_result->fields;
      if ($check_admin['check_firstname'] != $firstname) {
        $login = 'fail';
      } else {
        $login = 'success';
        $make_password = oos_create_random_value(7);
        $crypted_password = oos_encrypt_password($make_password);

        oos_mail($check_admin['check_firstname'] . ' ' . $check_admin['admin_lastname'], $check_admin['check_email_address'], ADMIN_PASSWORD_SUBJECT, nl2br(sprintf(ADMIN_EMAIL_TEXT, $make_password)), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS); 
        $admintable = $oostable['admin'];
        $dbconn->Execute("UPDATE $admintable
                          SET admin_password = '" . $crypted_password . "'
                          WHERE admin_id = '" . $check_admin['check_id'] . "'");
      }
    }
  }
  require('includes/languages/' . $_SESSION['language'] . '/' . $aContents['login']);

  if ($login == 'success') {
    $success_message = TEXT_FORGOTTEN_SUCCESS;
  } elseif ($login == 'fail') {
    $info_message = TEXT_FORGOTTEN_ERROR;
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?> - Administration [OOS]</title>
<style type="text/css">
@import url(includes/admin_login.css);
</style>
<script language="javascript" type="text/javascript">
<!--
function setFocus() {
  document.loginForm.email_address.select();
  document.loginForm.email_address.focus();
}
//-->
</script>
</head>
<body onLoad="setFocus();">
<div id="ctr" align="center">
	<div class="login">
	
<?php
  if (isset($_SESSION['password_forgotten'])) {
?>

	  <p><div class="smallText"><?php echo TEXT_FORGOTTEN_FAIL; ?></div></p>
	  <p><div class="smallText"><?php echo '<a href="' . oos_href_link_admin($aContents['login'], '' , 'SSL') . '">' . oos_image_swap_button('back','back_off.gif', IMAGE_BACK) . '</a>'; ?></div></p>
	  <div id="break"></div>
<?php
  } elseif (isset($success_message)) {
?>

	  <p><div class="smallText"><?php echo $success_message; ?></div></p>
	  <p><div class="smallText"><?php echo '<a href="' . oos_href_link_admin($aContents['login'], '' , 'SSL') . '">' . oos_image_swap_button('back','back_off.gif', IMAGE_BACK) . '</a>'; ?></div></p>
	  <div id="break"></div>
<?php
  } else {
?>

		<div class="login-form">
		
		<?php echo oos_draw_form('login', $aContents['password_forgotten'], 'action=process'); ?>
		<?php echo oos_draw_hidden_field('log_times', $log_times); ?>
		
			<img src="images/login.gif" alt="<?php echo HEADING_PASSWORD_FORGOTTEN; ?>" />
			
		<div class="smallText"><?php echo TEXT_PASSWORD_INFO; ?></div>
			<div class="form-block">
			<div class="inputlabel"><?php echo ENTRY_FIRSTNAME; ?></div>
			<div><?php echo oos_draw_input_field('firstname', '', 'class="inputbox" size="15"'); ?></div>

			<div class="inputlabel"><?php echo ENTRY_EMAIL_ADDRESS; ?></div>
			<div><?php echo oos_draw_input_field('email_address', '', 'class="inputbox" size="15"'); ?></div>

			
			<div class="ctr"><?php echo '<a href="' . oos_href_link_admin($aContents['login'], '' , 'SSL') . '">' . oos_image_swap_button('back','back_off.gif', IMAGE_BACK) . '</a> ' . oos_image_swap_submits('confirm','confirm_off.gif', IMAGE_BUTTON_LOGIN); ?></div>
	
		</div>
			</form>
		</div>
		<div class="login-text">

			<div class="ctr"><img src="images/security.gif" width="64" height="64" alt="security" /></div>
			<p><?php echo HEADING_PASSWORD_FORGOTTEN; ?></p>

			<p><?php echo $info_message; ?></p>

		</div>
		<div class="clr"></div>

<?php
  }
?>
	</div>
</div>

<div id="break"></div>

</body>
</html>
<?php require 'includes/oos_nice_exit.php'; ?>