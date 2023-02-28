<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: login.php,v 1.75 2003/02/13 03:01:49 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce

   Max Order - 2003/04/27 JOHNSON - Copyright (c) 2003 Matti Ressler - mattifinn@optusnet.com.au
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

$_SESSION['login_count'] = 0;

$bError = false;

// cookie-notice
if ($bNecessary === false) {
    oos_redirect(oos_href_link($aContents['home']));
}


// require  the password crypto functions
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_password.php';
require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/user_login.php';

if (isset($_POST['action']) && ($_POST['action'] == 'process')
    && (isset($_SESSION['formid']) && ($_SESSION['formid'] == $_POST['formid']))
) {


    // start the session
    if ($session->hasStarted() === false) {
        $session->start();
    }

    if (!isset($_SESSION['user'])) {
        $_SESSION['user'] = new oosUser();
        $_SESSION['user']->anonymous();
    }

    $email_address = oos_db_prepare_input($_POST['email_address']);
    $password = oos_db_prepare_input($_POST['password']);

    if (empty($email_address) || !is_string($email_address)) {
        $_SESSION['error_message'] = $aLang['text_login_error'];
        oos_redirect(oos_href_link($aContents['login']));
    }

    if (empty($password) || !is_string($password)) {
        $_SESSION['error_message'] = $aLang['text_login_error'];
        oos_redirect(oos_href_link($aContents['login']));
    }

    /* Check if it is ok to login */
    if (!isset($_SESSION['password_forgotten_count'])) {
        $_SESSION['login_count'] = 1;
    } else {
        $_SESSION['login_count'] ++;
    }

    if ($_SESSION['login_count'] > 6) {
        oos_redirect(oos_href_link($aContents['403']));
    }

    // Check if email exists
    $customerstable = $oostable['customers'];
    $sql = "SELECT customers_id, customers_gender, customers_firstname, customers_lastname,
                   customers_password, customers_wishlist_link_id, customers_language,
                   customers_email_address, customers_2fa_active, customers_default_address_id, customers_max_order 
            FROM $customerstable
            WHERE customers_login = '1'
              AND customers_email_address = '" . oos_db_input($email_address) . "'";
    $check_customer_result = $dbconn->Execute($sql);

    if (!$check_customer_result->RecordCount()) {
        $bError = true;
    } else {
        $check_customer = $check_customer_result->fields;

        // Check that password is good
        if (!oos_validate_password($password, $check_customer['customers_password'])) {
            $bError = true;
        } else {
            $options = [
                'cost' => COST
            ];

            // Is password hash no longer up to date?
            if (password_needs_rehash($check_customer['customers_password'], PASSWORD_DEFAULT, $options)) {
                // create new hash, add Pepper again!
                $new_hash = password_hash($password . PEPPER, PASSWORD_DEFAULT, $options);

                // Update hash in DB
                $customerstable = $oostable['customers'];
                $dbconn->Execute(
                    "UPDATE $customerstable
                        SET customers_password = '" . oos_db_input($new_hash) . "'
                        WHERE customers_id = '" . intval($check_customer['customers_id']) . "'"
                );
            }

            $_SESSION['customer_2fa_id'] = $check_customer['customers_id'];
            $_SESSION['password'] = $password;

            // customers_2fa_active
            if ($check_customer['customers_2fa_active'] == '1') {
                oos_redirect(oos_href_link($aContents['login_2fa']));
            } else {
                oos_redirect(oos_href_link($aContents['login_2fa_info']));
            }

            oos_redirect(oos_href_link($aContents['login_process'], 'formid=' . $_SESSION['formid'] . '&action=process'));
        }
    }
}

// links breadcrumb
$oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aContents['login']));
$sCanonical = oos_href_link($aContents['login'], '', false, true);

if (isset($bError) && ($bError == true)) {
    $_SESSION['error_message'] = $aLang['text_login_error'];
}

$aTemplate['page'] = $sTheme . '/page/user_login.html';

$nPageType = OOS_PAGE_TYPE_SERVICE;
$sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;

require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
    include_once MYOOS_INCLUDE_PATH . '/includes/message.php';
    include_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}

// assign Smarty variables;
$smarty->assign(
    array(
          'breadcrumb'        => $oBreadcrumb->trail(),
          'heading_title'    => $aLang['navbar_title'],
          'robots'            => 'noindex,follow,noodp,noydir',
          'login_active'    => 1,

          'canonical'        => $sCanonical
      )
);

// register the outputfilter
$smarty->loadFilter('output', 'trimwhitespace');

// display the template
$smarty->display($aTemplate['page']);
