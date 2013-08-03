<?php
/* ----------------------------------------------------------------------
   $Id: customers_image.php 409 2013-06-11 15:53:40Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  function oos_get_random_picture_name($length = 24, $extension = 'jpg') {
    $sStr = "";

    for ($index = 1; $index <= $length; $index++) {
      // Pick random number between 1 and 62
      $randomNumber = rand(1, 62);
      // Select random character based on mapping.
      if ($randomNumber < 11) {
        $sStr .= Chr($randomNumber + 48 - 1); // [ 1,10] => [0,9]
      } else if ($randomNumber < 37) {
        $sStr .= Chr($randomNumber + 65 - 10); // [11,36] => [A,Z]
      } else {
        $sStr .= Chr($randomNumber + 97 - 36); // [37,62] => [a,z]
      }
    }

    $sStr .= '.' . $extension;

    if (file_exists(OOS_ABSOLUTE_PATH . OOS_IMAGES . OOS_CUSTOMERS_IMAGES . $sStr)) {
      oos_get_random_picture_name(26, $extension);
    }
    return $sStr;
  }


  if (!isset($_SESSION['customer_id'])) {
    $_SESSION['navigation']->set_snapshot();
    oos_redirect(oos_href_link($aContents['login'], '', 'SSL'));
  }


  if ( (isset($_POST['action']))  && ($_POST['action'] == 'add_customers_image') ) {
    if ( ($_POST['remove_image'] == 'yes') && (isset($_SESSION['customer_id'])) ) {
      $customerstable = $oostable['customers'];
      $query = "SELECT customers_image
                FROM $customerstable
               WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'";
      $customers_image = $dbconn->GetOne($query);

      @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . OOS_CUSTOMERS_IMAGES . $customers_image);

      $customerstable = $oostable['customers'];
      $query = "UPDATE $customerstable"
           . " SET customers_image = ?"
           . " WHERE customers_id = ?";
      $result = $dbconn->Execute($query, array('', (int)$_SESSION['customer_id']));


    }

    require_once 'includes/classes/class_upload.php';

    if (oos_is_not_null($_FILES['id']['tmp_name']) and ($_FILES['id']['tmp_name'] != 'none')) {

      $customers_image_file = new upload('id');
      $customers_image_file->set_destination(OOS_ABSOLUTE_PATH . OOS_IMAGES . OOS_CUSTOMERS_IMAGES);

      if ($customers_image_file->parse()) {
        if (isset($_SESSION['customer_id'])) {

          $extension = oos_get_extension($_FILES['id']['name']);
          $picture_tempname = oos_get_random_picture_name(26, $extension);
          $customers_image_file->set_filename($picture_tempname);

          $customerstable = $oostable['customers'];
          $query = "UPDATE $customerstable"
              . " SET customers_image = ?"
              . " WHERE customers_id = ?";
          $result = $dbconn->Execute($query, array((string)$picture_tempname, (int)$_SESSION['customer_id']));

          $customers_image_file->save();
        }
      }
    }
  }


  require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/user_customers_image.php';

  $customerstable = $oostable['customers'];
  $address_bookstable = $oostable['address_book'];
  $customers_infotable = $oostable['customers_info'];

  $sql = "SELECT c.customers_gender, c.customers_firstname, c.customers_lastname, c.customers_image,
                 a.entry_city, a.entry_country_id, ci.customers_info_date_account_created AS date_account_created
          FROM $customerstable c,
               $address_bookstable a,
               $customers_infotable ci
          WHERE c.customers_id = '" . intval($_SESSION['customer_id']) . "'
            AND a.customers_id = c.customers_id
            AND ci.customers_info_id = c.customers_id
            AND a.address_book_id = '" . intval($_SESSION['customer_default_address_id']) . "'";
  $customer = $dbconn->GetRow($sql);

  if ($myworld['customers_gender'] == 'm') {
    $gender = $aLang['male'];
  } elseif ($account['customers_gender'] == 'f') {
    $gender = $aLang['female'];
  }

  $sCountryName = oos_get_country_name($myworld['entry_country_id']);
  $sAccountCreated = oos_date_short($myworld['date_account_created']);

  // links breadcrumb
  $oBreadcrumb->add($aLang['text_yourstore'], oos_href_link($aContents['yourstore']));
  $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aContents['customers_image']));

  $aTemplate['page'] = $sTheme .  '/modules/customers_image.tpl';

  $nPageType = OOS_PAGE_TYPE_ACCOUNT;

  require_once MYOOS_INCLUDE_PATH . '/includes/oos_system.php';
  if (!isset($option)) {
    require_once MYOOS_INCLUDE_PATH . '/includes/info_message.php';
    require_once MYOOS_INCLUDE_PATH . '/includes/oos_blocks.php';
  }

  // assign Smarty variables;
  $smarty->assign(
      array(
          'oos_breadcrumb'    => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
          'oos_heading_title' => $aLang['heading_title'],
          'oos_heading_image' => 'contact_us.gif',

          'customer'        => $customer,
          'gender'          => $gender,
          'country_name'    => $sCountryName,
          'account_created' => $sAccountCreated
      )
  );


// display the template
$smarty->display($aTemplate['page']);
