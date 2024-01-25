<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: login.php,v 1.17 2003/02/14 12:57:29 dgw_
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */


define('OOS_VALID_MOD', 'yes');
require 'includes/main.php';

if (!isset($_SESSION['log_times'])) {
    $_SESSION['log_times'] = 1;
}

$login = '';
if (isset($_GET['action']) && ($_GET['action'] == 'process')
    && (isset($_SESSION['formid']) && ($_SESSION['formid'] == $_POST['formid']))
) {
    $_SESSION['log_times']++;

    if ($_SESSION['log_times'] >= 4) {
        $_SESSION['password_forgotten'] = 'password';
    }

    $email_address = oos_prepare_input($_POST['email_address']);
    $firstname = oos_prepare_input($_POST['firstname']);

    if (empty($email_address) || !is_string($email_address)) {
        oos_redirect_admin(oos_href_link_admin($aContents['forbiden']));
    }

    if (empty($firstname) || !is_string($firstname)) {
        oos_redirect_admin(oos_href_link_admin($aContents['forbiden']));
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

            $make_password = oos_create_random_value(8);		
            $crypted_password = oos_encrypt_password($make_password);

            $admintable = $oostable['admin'];
            $dbconn->Execute(
                "UPDATE $admintable
							SET admin_password = '" . $crypted_password . "'
							WHERE admin_id = '" . $check_admin['check_id'] . "'"
            );

            oos_mail($check_admin['check_firstname'] . ' ' . $check_admin['check_lastname'], $check_admin['check_email_address'], ADMIN_PASSWORD_SUBJECT, nl2br(sprintf(ADMIN_PASSWORD_EMAIL_TEXT, $make_password)), nl2br(sprintf(ADMIN_PASSWORD_EMAIL_TEXT, $make_password)), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
        }
    }
}

$sFormid = md5(uniqid(random_int(0, mt_getrandmax()), true));
$_SESSION['formid'] = $sFormid;

require 'includes/languages/' . $sLanguage . '/' . $aContents['login'];
require 'includes/header.php';
?>
    <div class="wrapper wrapper-content">


        <div class="login">

            <div class="login-content">
<?php
if (isset($_SESSION['password_forgotten'])) {
    ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php echo TEXT_FORGOTTEN_FAIL; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>    

                <div class="form-group text-right m-t-20">
                    <div class="col-xs-12">
                        <?php echo '<a href="' . oos_href_link_admin($aContents['login']) . '">'; ?><button class="btn btn-primary btn-custom w-md"><?php echo BUTTON_BACK; ?></button></a>
                    </div>
                </div>    
    <?php
} elseif ($login == 'success') {
    ?>

                <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?php echo TEXT_FORGOTTEN_SUCCESS; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>    

                <div class="form-group text-right m-t-20">
                    <div class="col-xs-12">
                        <?php echo '<a href="' . oos_href_link_admin($aContents['login']) . '">'; ?><button class="btn btn-primary btn-custom w-md"><?php echo BUTTON_BACK; ?></button></a>
                    </div>
                </div>    
    <?php
} else {
    if ($login == 'fail') {
        ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo TEXT_FORGOTTEN_ERROR; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

        <?php
    } ?>

            
                <div class="login-header text-center">
                    <i class="mdi mdi-radar"></i> <span>MyOOS [Shopsystem] </span>
                </div>

                <div class="login-text">
                    <p><?php echo TEXT_PASSWORD_INFO; ?></p>
                </div>                
        
            <?php echo oos_draw_form('id', 'login', $aContents['password_forgotten'], 'action=process', 'post', true); ?>
            <?php echo oos_draw_hidden_field('formid', $sFormid); ?>
        
                    <div class="form-group m-b-20">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="mdi mdi-account"></i></span>
                            </div>
                    <?php echo oos_draw_input_field('firstname', '', '', true, 'text'); ?>        
                        </div>
                    </div>

                    <div class="form-group m-b-20">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="mdi mdi-email"></i></span>
                            </div>
                    <?php echo oos_draw_input_field('email_address', '', '', true, 'text'); ?>                            
                        </div>
                    </div>

                    <div class="form-group text-right m-t-20">
                        <div class="col-xs-12">
                            <button class="btn btn-primary btn-custom w-md" type="submit"><?php echo BUTTON_SEND_PASSWORD; ?></button>
                        </div>
                    </div>
        
                </form>

    <?php
}
?>
            </div>
        </div>
    </div>

<?php
    require 'includes/bottom.php';
require 'includes/nice_exit.php';
?>