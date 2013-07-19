<?php
/* ----------------------------------------------------------------------
   $Id: login.php 437 2013-06-22 15:33:30Z r23 $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
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
  require 'includes/oos_main.php';

  //CAPTCHA
  if (!defined('SHOW_CAPTCHA')) {
    define('SHOW_CAPTCHA', 'false');
  }

  if (isset($_GET['action']) && ($_GET['action'] == 'process')) {

    //Check if userinput and CAPTCHA String are equal
    if ( (SHOW_CAPTCHA == 'true') && ($_SESSION['oos_captcha_string'] != $_POST['captchastring'])) {
      $_GET['login'] = 'fail';
    } else {
      // Check if email exists
      $check_admin_result = $dbconn->Execute("SELECT admin_id as login_id, admin_groups_id as login_groups_id, admin_firstname as login_firstname, admin_email_address as login_email_address, admin_password as login_password, admin_modified as login_modified, admin_logdate as login_logdate, admin_lognum as login_lognum FROM " . $oostable['admin'] . " WHERE admin_email_address = '" . oos_db_input($email_address) . "'");
      if (!$check_admin_result->RecordCount()) {
        $_GET['login'] = 'fail';
      } else {
        $check_admin = $check_admin_result->fields;
        // Check that password is good
        if (!oos_validate_password($password, $check_admin['login_password'])) {
          $_GET['login'] = 'fail';
        } else {
          if (isset($_SESSION['password_forgotten'])) {
            unset($_SESSION['password_forgotten']);
          }
          $_SESSION['login_id'] = $check_admin['login_id'];
          $_SESSION['login_groups_id'] = $check_admin['login_groups_id'];
          $_SESSION['login_first_name'] = $check_admin['login_firstname'];

          $login_email_address = $check_admin['login_email_address'];
          $login_logdate = $check_admin['login_logdate'];
          $login_lognum = $check_admin['login_lognum'];
          $login_modified = $check_admin['login_modified'];

          //$date_now = date('Ymd');
          $dbconn->Execute("UPDATE " . $oostable['admin'] . "
                        SET admin_logdate = now(), admin_lognum = admin_lognum+1
                        WHERE admin_id = '" . $_SESSION['login_id'] . "'");

          if (($login_lognum == 0) || !($login_logdate) || ($login_email_address == 'admin@localhost') || ($login_modified == '0000-00-00 00:00:00')) {
            oos_redirect_admin(oos_href_link_admin($aFilename['admin_account']));
          } else {
            oos_redirect_admin(oos_href_link_admin($aFilename['default']));
          }
        }
      }
    }
  }

require_once '/includes/classes/class_template.php';
$smarty = new myOOS_Smarty;


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
  document.login.email_address.select();
  document.login.email_address.focus();
}
//-->
</script>
</head>
<body onload="setFocus();">

<div id="ctr" align="center">
	<div class="login">
		<div class="login-form">
			<img src="images/login.gif" alt="Login" />
		<?php echo oos_draw_form('login', $aFilename['login'], 'action=process'); ?>
			<div class="form-block">
			<div class="inputlabel"><?php echo ENTRY_EMAIL_ADDRESS; ?></div>
			<div><?php echo oos_draw_input_field('email_address', '', 'class="inputbox" size="15"'); ?></div>

			<div class="inputlabel"><?php echo ENTRY_PASSWORD; ?></div>
			<div><?php echo oos_draw_password_field('password', '', 'class="inputbox" size="15"'); ?></div>
<?php
  if (SHOW_CAPTCHA == 'true') {
?>
			<div class="clr"></div>
			<div><img src="./captcha.php" alt="CAPTCHA"></div>
                        <div class="inputlabel"><?php echo SECURITYCODE; ?></div>
			<div><?php echo oos_draw_input_field('captchastring', '', 'class="inputbox" size="15"'); ?></div>
<?php
  }
?>

	        	<div align="left"><input type="submit" name="submit" class="button" value="Login" /></div>
                        <div class="ctr"><a href="<?php echo oos_href_link_admin($aFilename['password_forgotten'], '', 'SSL') . '">' . TEXT_PASSWORD_FORGOTTEN; ?></a></div>


        	</div>
			</form>
    	</div>
		<div class="login-text">

			<div class="ctr"><img src="images/security.gif" width="64" height="64" alt="security" /></div>
			<p><?php echo TEXT_WELCOME; ?></p>

        	</div>
		<div class="clr"></div>
	</div>
</div>
<div id="break"></div>

<?php
  if (isset($_GET['login']) && $_GET['login'] == 'fail') {
?>
	  <p><div align="center" class="smallText"><?php TEXT_LOGIN_ERROR; ?></div></p>
	  <div id="break"></div>
<?php
  }
?>


</body>
</html>
<?php require 'includes/oos_nice_exit.php'; ?>