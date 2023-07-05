<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
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

if (isset($_GET['action']) && ($_GET['action'] == 'process')
    && (isset($_SESSION['formid']) && ($_SESSION['formid'] == $_POST['formid']))
) {
    $email_address = oos_prepare_input($_POST['email_address']);
    $password = oos_prepare_input($_POST['password']);

    if (empty($email_address) || !is_string($email_address)) {
        oos_redirect_admin(oos_href_link_admin($aContents['forbiden']));
    }

    if (empty($password) || !is_string($password)) {
        oos_redirect_admin(oos_href_link_admin($aContents['forbiden']));
    }
    // Check if email exists
    $check_admin_result = $dbconn->Execute("SELECT admin_id as login_id, admin_groups_id as login_groups_id, admin_firstname as login_firstname, admin_email_address as login_email_address, admin_password as login_password, admin_modified as login_modified, admin_logdate as login_logdate, admin_lognum as login_lognum FROM " . $oostable['admin'] . " WHERE admin_email_address = '" . oos_db_input($email_address) . "'");
    if (!$check_admin_result->RecordCount()) {
        $login = 'fail';
    } else {
        $check_admin = $check_admin_result->fields;
        // Check that password is good
        if (!oos_validate_password($password, $check_admin['login_password'])) {
            $login = 'fail';
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
            $dbconn->Execute(
                "UPDATE " . $oostable['admin'] . "
                        SET admin_logdate = now(), admin_lognum = admin_lognum+1
                        WHERE admin_id = '" . intval($_SESSION['login_id']) . "'"
            );

            oos_redirect_admin(oos_href_link_admin($aContents['default']));
        }
    }
}

$sFormid = md5(uniqid(rand(), true));
$_SESSION['formid'] = $sFormid;

require 'includes/header.php';
?>
    <div class="wrapper wrapper-content">


        <div class="login">

            <!-- begin login-content -->
            <div class="login-content">
            
<?php
if (isset($login) && $login == 'fail') {
    ?>            
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php echo TEXT_LOGIN_ERROR; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>            
    <?php
}
?>            
            
                <div class="login-header text-center">
                    <i class="mdi mdi-radar"></i> <span>MyOOS [Shopsystem] </span>
                </div>


                <?php echo oos_draw_form('id', 'login', $aContents['login'], 'action=process', 'post', true); ?>
                    <?php echo oos_draw_hidden_field('formid', $sFormid); ?>

                    <div class="form-group m-b-20">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="mdi mdi-account"></i></span>
                            </div>
                            <?php echo oos_draw_input_field('email_address', '', '', true, 'text', true, false, PLACEHOLDER_EMAIL_ADDRESS); ?>                            
                        </div>
                    </div>

                    <div class="form-group m-b-20">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="mdi mdi-radar"></i></span>
                            </div>
                            <?php echo oos_draw_input_field('password', '', '', true, 'password', false, false, PLACEHOLDER_PASSWORD); ?>        
                        </div>
                    </div>

                    <div class="form-group text-right m-t-20">
                        <div class="col-xs-12">
                            <button class="btn btn-primary btn-custom w-md" type="submit">Log In</button>
                        </div>
                    </div>

                    <div class="form-group row m-t-30">
                        <div class="col-sm-7  text-right">
                            <?php echo '<a href="' . oos_href_link_admin($aContents['password_forgotten']) . '" class="text-muted"><i class="fa fa-lock"></i> ' . TEXT_PASSWORD_FORGOTTEN; ?></a>
                        </div>
                    </div>
        
                </form>
            </div>
        </div>
        
    </div>

<?php
    require 'includes/bottom.php';
    require 'includes/nice_exit.php';
?>